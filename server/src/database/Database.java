package database;

import java.sql.*;

/**
 * Interface entre les la base de donnée et le programme.
 * Contient toutes les méthodes pour intéragir avec cette dernière.
 * @see SingleConnection
 */
public class Database {

    /**
     * La connexion avec la base de donnée.
     */
    private static Connection connection;

    public static boolean test() {
        try {
            Statement pstmt = connection.createStatement();
            ResultSet rs = pstmt.executeQuery("SELECT * FROM utilisateur");
            while(rs.next()) {
                System.out.println(rs.getString("nom"));
            }

            return true;
        }
        catch (SQLException e) {
            return false;
        }
    }

    /**
     * Ouvre la connexion avec la base de donnée.
     */
    public static void openConnection() {
        connection = SingleConnection.INSTANCE.getConnection();
    }

    /**
     * Ferme la connexion avec la base de donnée.
     * IMPOSSIBLE de la rouvrir, à n'appeller quand cas de fermeture du serveur.
     */
    public static void closeConnection() {
        try {
            connection.close();
            connection = null;
        }
        catch (SQLException e) {
            e.printStackTrace();
        }
    }
}