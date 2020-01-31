package database;

import java.sql.*;
import java.time.LocalDate;

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

    /**
     * Vérifie si une ip est connue. (Présente dans la BDD)
     * @param ip String IP à vérifier.
     * @return int L'id du compte associé au TPE si l'ip est connue. -1 sinon.
     */
    public static int checkIP(String ip) {
        try {
            PreparedStatement pstmt = connection.prepareStatement("SELECT idAccount FROM terminal WHERE ip = ?");
            pstmt.setString(1, ip);
            ResultSet rs = pstmt.executeQuery();

            int idAccount = -1;
            if (rs.next())
                idAccount = rs.getInt(1);

            rs.close();
            pstmt.close();

            return idAccount;
        }
        catch (SQLException e) {
            System.err.println("Erreur dans la vérification de l'adresse IP");
            return -1;
        }
    }

    /**
     * Effectue un paiement entre le compte associé au TPE et une carte.
     * @param idReceiver int ID du compte recepteur. (Compte associé au TPE)
     * @param cardID int Numéro de la carte.
     * @param amount float Montant de la transaction.
     * @return boolean True si le paiement a été effectué.
     */
    public static boolean pay(int idReceiver ,int cardID, float amount) {
        try {
            if (amount < 0)
                throw new SQLException();

            //On récupère le compte associé à la carte
            PreparedStatement pstmt = connection.prepareStatement("SELECT idAccount FROM card WHERE id = ?");
            pstmt.setInt(1, cardID);
            ResultSet rs = pstmt.executeQuery();
            int idAccount;
            if (rs.next())
                idAccount = rs.getInt("idAccount");
            else
                throw new SQLException();
            pstmt.close();
            rs.close();

            //On vérifie si le client l'émetteur possède assez de fond.
            if (soldeCompte(idAccount) < amount)
                throw new SQLException();

            //On effectue la transaction
            pstmt = connection.prepareStatement("INSERT INTO transaction(type, amount, idIssuer, idReceiver, \"date\") " +
                    "VALUES(? , ?, ?, ?, ?)");
            pstmt.setString(1, "TYPETODO");
            pstmt.setFloat(2, amount);
            pstmt.setInt(3, idAccount);
            pstmt.setInt(4, idReceiver);
            pstmt.setDate(5, Date.valueOf(LocalDate.now()));

            if (pstmt.executeUpdate() == 0) //Si aucune ligne n'a été insérée il y'a un problème
                throw new SQLException();

            connection.commit();
            pstmt.close();

            System.out.println("Paiement effectué avec succès.\n" +
                    "Emetteur : " + idReceiver + "   Receveur : " + idReceiver + "    Montant : " + amount);
            return true;
        }
        catch (SQLException e) {
            System.err.println("Erreur lors d'un virement avec un TPE" +
                    "\nID du compte associé au TPE : " + idReceiver +
                    "\nNuméro de la carte : " + cardID +
                    "\nMontant : " + amount);
            return false;
        }
    }

    /**
     * Récupère le solde d'un compte.
     * @param accountID int Numéro du compte
     * @return float Solde du compte.
     */
    public static float soldeCompte(int accountID) {
        try {
            float solde = 0;
            PreparedStatement pstmt = connection.prepareStatement("SELECT SUM(amount) FROM transaction WHERE idReceiver = ?");
            pstmt.setInt(1, accountID);
            ResultSet rs = pstmt.executeQuery();

            if (rs.next())
                solde += rs.getFloat(1);

            pstmt = connection.prepareStatement("SELECT SUM(amount) FROM transaction WHERE idIssuer = ?");
            pstmt.setInt(1, accountID);
            rs = pstmt.executeQuery();

            if (rs.next())
                solde -= rs.getFloat(1);

            rs.close();
            pstmt.close();
            return solde;
        }
        catch (SQLException e) {
            System.err.println("Erreur lors de la récupération du solde du compte : " + accountID);
            return 0;
        }
    }
}