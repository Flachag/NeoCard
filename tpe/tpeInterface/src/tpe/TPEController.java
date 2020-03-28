package tpe;

import com.Arduino;
import com.fazecast.jSerialComm.SerialPort;
import javafx.application.Platform;
import javafx.beans.value.ChangeListener;
import javafx.beans.value.ObservableValue;
import javafx.concurrent.Task;
import javafx.event.ActionEvent;
import javafx.event.EventHandler;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.control.TextField;
import javafx.scene.image.ImageView;
import javafx.scene.layout.Pane;
import javafx.scene.paint.Color;

import java.io.File;
import java.net.URL;
import java.util.ResourceBundle;
import java.util.Scanner;

/**
 * Classe permettant la gestion graphique des paramètres
 */
public class TPEController implements Initializable {
    private static Arduino arduino;
    private Wifi wifi;
    @FXML
    private Button appliParam;

    @FXML
    private TextField ssidText;

    @FXML
    private TextField passwordText;

    @FXML
    private Button appliMontant;

    @FXML
    private TextField montantText;

    @FXML
    private Label statusParam;

    @FXML
    private Pane scanCard;

    @FXML
    private Pane amount;

    @FXML
    private Pane network;

    @FXML
    private ImageView succes;

    @FXML
    private ImageView error;

    /**
     * Méthode qui permet de créer l'objet Arduino sur le port sélectionné
     *
     * @param portCom - port série
     */
    public static void initArduino(String portCom) {
        arduino = new Arduino(portCom, 115200);
        arduino.openConnection();
    }

    @Override
    public void initialize(URL arg0, ResourceBundle arg1) {
        this.wifi = new Wifi();
        this.checkFile();

        appliParam.setOnAction(new EventHandler<ActionEvent>() {
            @Override
            public void handle(ActionEvent event) {
                statusParam.setText("");
                wifi.setSSID(ssidText.getText());
                wifi.setPassword(passwordText.getText());
                saveData();
                try {
                    Thread.sleep(1000);
                } catch (Exception ex) {
                    System.err.println("Impossible de faire patienter le programme");
                }
                new Thread(sendParam()).start();
            }
        });

        montantText.textProperty().addListener(new ChangeListener<String>() {
            @Override
            public void changed(ObservableValue<? extends String> observable, String oldValue,
                                String newValue) {
                if (!newValue.matches("\\d*(\\.\\d*)?")) {
                    montantText.setText(oldValue);
                }
            }
        });

        appliMontant.setOnAction(new EventHandler<ActionEvent>() {
            @Override
            public void handle(ActionEvent event) {
                new Thread(sendAmount()).start();
            }
        });
    }

    /**
     * Méthode qui sauvegarde les données wifi
     */
    private void saveData() {
        this.wifi.save();
    }

    /**
     * Méthode qui vérifie les données wifi
     */
    private void checkData() {
        this.wifi.load();
        if (!this.wifi.getSSID().isEmpty()) {
            this.ssidText.setText(this.wifi.getSSID());
        }
        if (!this.wifi.getPassword().isEmpty()) {
            this.passwordText.setText(this.wifi.getPassword());
        }
    }

    /**
     * Méthode qui définie le montant et attend la lecture de la carte
     *
     * @return la tâche
     */
    private Task<Void> sendAmount() {
        Task<Void> longRunningTask = new Task<Void>() {

            @Override
            protected Void call() throws Exception {
                Platform.runLater(() -> {
                    String amountCommand = montantText.getText();

                    if (amountCommand.isEmpty() || amountCommand.equals(".")) {
                        amountCommand = "0";
                    }

                    String receive = "";
                    boolean dataReceving = true;

                    SerialPort comPort = arduino.getSerialPort();
                    Scanner in = new Scanner(comPort.getInputStream());

                    while (in.hasNextLine()) {
                        receive = in.nextLine();
                        System.out.println(receive);
                        switch (receive) {
                            case "Entrez l'adresse du serveur : ":
                                arduino.serialWrite("http://192.168.0.12");
                                break;
                            case "Entrez montant : ":
                                arduino.serialWrite(amountCommand);
                                dataReceving = false;
                                break;
                        }
                        if (!dataReceving) {
                            break;
                        }
                    }
                    network.setVisible(false);
                    amount.setVisible(false);
                    scanCard.setVisible(true);
                    new Thread(checkPayement()).start();
                });
                return null;
            }
        };

        return longRunningTask;
    }

    /**
     * Méthode qui vérifie le résultat de la transaction
     *
     * @return la tâche
     */
    private Task<Void> checkPayement() {
        Task<Void> longRunningTask = new Task<Void>() {

            @Override
            protected Void call() throws Exception {
                Platform.runLater(() -> {
                    String receive = "";
                    boolean dataReceving = true;

                    SerialPort comPort = arduino.getSerialPort();
                    Scanner in = new Scanner(comPort.getInputStream());

                    while (in.hasNextLine()) {
                        receive = in.nextLine();
                        System.out.println(receive);
                        switch (receive) {
                            case "PAIEMENT_REFUSED":
                                error.setVisible(true);
                                dataReceving = false;
                                try {
                                    Thread.sleep(1000);
                                } catch (Exception ex) {
                                    System.err.println("Impossible de faire patienter le programme");
                                }
                                break;
                            case "PAIEMENT_ACCEPTED":
                                succes.setVisible(true);
                                dataReceving = false;
                                try {
                                    Thread.sleep(1000);
                                } catch (Exception ex) {
                                    System.err.println("Impossible de faire patienter le programme");
                                }
                                break;
                        }
                        if (receive.contains("a echoue") || receive.contains("envoye au serveur")) {
                            error.setVisible(true);
                            dataReceving = false;
                            try {
                                Thread.sleep(1000);
                            } catch (Exception ex) {
                                System.err.println("Impossible de faire patienter le programme");
                            }
                            break;
                        }
                        if (!dataReceving) {
                            break;
                        }
                    }

                    new Thread(sendParam()).start();
                });
                return null;
            }
        };

        return longRunningTask;
    }

    /**
     * Méthode qui paramètre le wifi de l'Arduino
     *
     * @return la tâche
     */
    private Task<Void> sendParam() {
        Task<Void> longRunningTask = new Task<Void>() {

            @Override
            protected Void call() throws Exception {
                Platform.runLater(() -> {
                    statusParam.setText("");
                    error.setVisible(false);
                    succes.setVisible(false);
                    scanCard.setVisible(false);
                    network.setVisible(true);
                    amount.setVisible(true);

                    String receive = "";
                    boolean dataReceving = true;

                    arduino.closeConnection();
                    arduino.openConnection();
                    SerialPort comPort = arduino.getSerialPort();
                    Scanner in = new Scanner(comPort.getInputStream());

                    while (in.hasNextLine()) {
                        receive = in.nextLine();
                        System.out.println(receive);
                        switch (receive) {
                            case "Entrez SSID : ":
                                System.out.println(wifi.getSSID());
                                arduino.serialWrite(wifi.getSSID());
                                statusParam.setText("");
                                break;
                            case "SSID inconnu":
                                statusParam.setText("SSID inconnu");
                                statusParam.setTextFill(Color.RED);
                                dataReceving = false;
                                break;
                            case "Entrez mot de passe : ":
                                System.out.println(wifi.getPassword());
                                arduino.serialWrite(wifi.getPassword());
                                statusParam.setText("");
                                break;
                            case "Trop de tentative, veuillez reessayer":
                                statusParam.setText("Trop de tentatives de connexion ont échoué, veuillez réessayer");
                                statusParam.setTextFill(Color.RED);
                                dataReceving = false;
                                arduino.serialWrite("finishWait");
                                break;
                            case "Connexion au reseau reussi !":
                                statusParam.setText("Connexion au réseau réussi !");
                                statusParam.setTextFill(Color.GREEN);
                                amount.setDisable(false);
                                arduino.serialWrite("finishWait");
                                dataReceving = false;
                                break;
                        }
                        if (!dataReceving) {
                            break;
                        }
                    }
                });
                return null;
            }
        };

        return longRunningTask;
    }

    /**
     * Méthode qui vérifie le fichier de sauvegarde
     */
    private void checkFile() {
        File file = new File(System.getProperty("user.home") + File.separator + "NeoCard" + File.separator + "data.neo");
        if (!file.exists()) {
            try {
                file.getParentFile().mkdirs();
                file.createNewFile();
            } catch (Exception e) {
                System.err.println("Impossible de créer le fichier de sauvegarde");
            }
        } else {
            this.checkData();
        }
    }
}