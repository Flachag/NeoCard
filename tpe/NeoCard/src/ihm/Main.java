package ihm;

import java.io.IOException;
import java.net.InetAddress;

import database.Database;
import javafx.application.Application;
import javafx.fxml.*;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.image.Image;
import javafx.stage.*;

public class Main extends Application {
	
	@Override
	public void start(Stage primaryStage) throws IOException {
		Database.openConnection();

		Parent root = null;
		InetAddress address = InetAddress.getByName("www.java.com");
		if (address.isReachable(1000)) {
			root = FXMLLoader.load(getClass().getResource("/ressources/login.fxml"));
		} else {
			root = FXMLLoader.load(getClass().getResource("/ressources/internetError.fxml"));
		}

		primaryStage.setTitle("NeoCard");
		primaryStage.getIcons().add(new Image("/ressources/credit-card.png"));
		primaryStage.setResizable(false);
		primaryStage.setScene(new Scene(root, 856, 562));
		primaryStage.show();
	}

	public static void main(String[] args) {
		launch(args);
	}
}