package ihm;

import java.io.IOException;
import java.net.URL;
import java.util.ResourceBundle;


import database.Database;
import javafx.event.ActionEvent;
import javafx.event.EventHandler;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.control.*;
import javafx.scene.paint.Color;

public class LoginController implements Initializable {

	@FXML
	private Button connexion;
	@FXML
	private Button retry;
	@FXML
	private TextField identifiant;
	@FXML
	private TextField password;
	@FXML
	private Label status;

	@Override
	public void initialize(URL arg0, ResourceBundle arg1) {
		connexion.setOnAction(new EventHandler<ActionEvent>() {
			@Override
			public void handle(ActionEvent arg) {
				try {
					if (Database.checkAccount(identifiant.getText(), password.getText())) {
						status.setText("Connexion réussi !");
						status.setTextFill(Color.GREEN);
					} else {
						status.setText("Connexion échoué !");
						status.setTextFill(Color.RED);
					}
				} catch (Exception e) {
					e.printStackTrace();
				}
			}
		});
	}
}