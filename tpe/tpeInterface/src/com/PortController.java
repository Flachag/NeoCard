package com;

import com.fazecast.jSerialComm.SerialPort;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.event.EventHandler;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.control.Button;
import javafx.scene.control.Spinner;
import javafx.scene.control.SpinnerValueFactory;
import main.App;
import tpe.TPEController;

import java.net.URL;
import java.util.ArrayList;
import java.util.ResourceBundle;

/**
 * Classe permettant la gestion graphique du choix du port lorsque plusieurs sont détectés
 */
public class PortController implements Initializable {
    private static SerialPort[] ports;

    @FXML
    private Spinner<String> selectPort;

    @FXML
    private Button selectionner;

    /**
     * Méthodes qui récupère les ports trouvés
     * @param p - ports trouvés
     */
    public static void initPorts(SerialPort[] p) {
        ports = p;
    }

    @Override
    public void initialize(URL location, ResourceBundle resources) {
        ArrayList<String> listPortsCom = new ArrayList<>();
        for(SerialPort port : ports){
            listPortsCom.add(port.getSystemPortName());
        }
        ObservableList<String> portsCom = FXCollections.observableArrayList(listPortsCom);
        SpinnerValueFactory<String> valueFactory = new SpinnerValueFactory.ListSpinnerValueFactory<String>(portsCom);
        this.selectPort.setValueFactory(valueFactory);

        selectionner.setOnAction(new EventHandler<ActionEvent>() {
            @Override
            public void handle(ActionEvent event) {
                TPEController.initArduino(selectPort.getValue());
                try {
                    App.showSettingPage();
                }catch (Exception ex){
                    System.err.println("Impossible d'acceder à la page de paramètre");
                }
            }
        });
    }

}
