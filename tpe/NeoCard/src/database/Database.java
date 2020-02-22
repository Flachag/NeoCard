package database;

import java.sql.*;


/**
 * Interface entre les la base de donn�e et le programme. Contient toutes les
 * m�thodes pour int�ragir avec cette derni�re.
 * 
 * @see SingleConnection
 */
public class Database {

	/**
	 * La connexion avec la base de donn�e.
	 */
	private static Connection connection;

	/**
	 * Ouvre la connexion avec la base de donn�e.
	 */
	public static void openConnection() {
		connection = SingleConnection.INSTANCE.getConnection();
	}

	/**
	 * Ferme la connexion avec la base de donn�e. IMPOSSIBLE de la rouvrir, �
	 * n'appeller quand cas de fermeture du serveur.
	 */
	public static void closeConnection() {
		try {
			connection.close();
			connection = null;
		} catch (SQLException e) {
			e.printStackTrace();
		}
	}
	

	public static boolean checkAccount(String identifiant, String pass) throws Exception {
		try {

			PreparedStatement pstmt = connection
					.prepareStatement("SELECT * FROM user WHERE username = ? OR email = ?");
			pstmt.setString(1, identifiant);
			pstmt.setString(2, identifiant);
			ResultSet rs = pstmt.executeQuery();

			String passwordHashed = "";
			if (rs.next()) 
				passwordHashed = rs.getString("password");

			rs.close();
			pstmt.close();

			return BCrypt.checkpw(pass, passwordHashed);
		} catch (SQLException e) {
			System.err.println("Erreur dans la v�rification de compte");
			return false;
		}
	}
}