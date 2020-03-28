package main;

import com.PortController;
import com.fazecast.jSerialComm.SerialPort;
import javafx.application.Application;
import javafx.fxml.FXMLLoader;
import javafx.fxml.JavaFXBuilderFactory;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.image.Image;
import javafx.stage.Stage;
import tpe.TPEController;

import java.io.IOException;


/**
 * Classe permettant la gestion graphique du programme
 */
public class App extends Application {

    private static Stage stage;

    /**
     * Méthode permettant d'afficher la page de paramètre
     * @return la page
     * @throws Exception
     */
    public static Parent showSettingPage() throws Exception {
        Parent page = (Parent) FXMLLoader.load(App.class.getResource("/ressources/tpe.fxml"), null, new JavaFXBuilderFactory());
        Scene scene = new Scene(page, 856, 450);
        stage.setScene(scene);
        return page;
    }

    /**
     * Méthode qui lance l'application
     * @param args - les arguments de lancements
     */
    public static void main(String[] args) {
        launch(args);
    }

    @Override
    public void start(Stage primaryStage) throws IOException {
        Parent root = null;
        stage = primaryStage;
        SerialPort ports[] = SerialPort.getCommPorts();
        if (ports.length == 1) {
            TPEController.initArduino(ports[0].getSystemPortName());
            root = FXMLLoader.load(getClass().getResource("/ressources/tpe.fxml"));
            stage.setScene(new Scene(root, 856, 450));
        } else if (ports.length > 1) {
            PortController.initPorts(ports);
            root = FXMLLoader.load(getClass().getResource("/ressources/portcom.fxml"));
            stage.setScene(new Scene(root, 412, 269));
        } else {
            System.err.println("Aucun ports trouvés");
            System.exit(-1);
        }

        stage.setTitle("TPE Interface");
        stage.getIcons().add(new Image("/ressources/credit-card.png"));
        stage.setResizable(false);
        stage.show();
    }
}