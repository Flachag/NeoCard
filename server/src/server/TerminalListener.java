package server;

import database.Database;

import java.io.*;
import java.net.Socket;

/**
 * Interface entre le serveur et les TPE clients.
 * Une instance de cette classe = 1 connexion par 1 TPE unique.
 * Receptionne les messages puis répond au TPE associé.
 */
public class TerminalListener implements Runnable {

    /**
     * Socket attribué par le serveur.
     */
    private Socket socket;

    /**
     * Flux d'entrée pour recevoir les messages du terminal.
     */
    private BufferedReader in;

    /**
     * Flux de sortie pour envoyer les messages au terminal.
     */
    private PrintWriter out;

    /**
     * ID du compte associé au TPE.
     */
    private int idAccount;

    /**
     * True tant que le serveur est connecté.
     */
    private boolean isRunning;

    /**
     * Constructeur initialisant le socket.
     * @param socket Socket Connexion TPE-Serveur
     */
    public TerminalListener(Socket socket) {
        this.socket = socket;
        initialize();
        isRunning = true;
    }

    /**
     * Initialise le flux d'entrée et le flux de sortie.
     */
    private void initialize() {
        try {
            in = new BufferedReader(new InputStreamReader(socket.getInputStream()));
            out = new PrintWriter(socket.getOutputStream());
        }
        catch (IOException e) {
            e.printStackTrace();
        }
    }

    /**
     * Ferme la connexion avec le TPE.
     */
    private void closeConnection() {
        try {
            in.close();
            out.close();
            socket.close();
        }
        catch (IOException e) {
            System.err.println("Erreur lors de la fermeture de la connexion avec un TPE");
        }
    }

    /**
     * Vérifie si le TPE est connu de la BDD.
     * S'il est connu, affecte l'id du compte associé au TPE.
     * @return boolean True s'il est valide.
     */
    private boolean isValide() {
        String ip = socket.getInetAddress().getHostAddress();
        int idAccount = Database.checkIP(ip);
        if (idAccount < 0) {
            sendMessage("UNKNOWN_TPE");
            System.err.println("Un TPE non connu a essayé de se connecter (ip : " + ip + ")");
            closeConnection();
            return false;
        }

        this.idAccount = idAccount;
        return true;
    }

    @Override
    public void run() {
        if (!isValide())
            return;
        sendMessage("CONNECTED");

        while (isRunning) {
            //ON attend la réponse du TPE
            String commande = receiveMessage();

            //Et on traite la réponse
            switch (commande.substring(0, 3)) {
                case "ERR" : {
                    System.err.println("Erreur le serveur n'a pas pu recevoir la réponse du TPE : " +
                            socket.getInetAddress().getHostAddress());
                    isRunning = false;
                    break;
                }
                case "PAY" : pay(commande); break;
                case "END" : isRunning = false; break;
                default : System.out.println("Commande inconnue :\n" + commande);
            }
        }

        System.out.println("TPE : " + socket.getInetAddress().getHostAddress() + " déconnecté.");
    }

    /**
     * Essaye d'effectuer un paiement.
     * @param commande String Contient la commande envoyée par le TPE.
     */
    private void pay(String commande) {
        String[] args = commande.split(":");
        int cardID = Integer.parseInt(args[1]);
        float amount = Float.parseFloat(args[2]);

        //Coupe la connexion si il y'a une erreur de paiement.
        if (!Database.pay(idAccount, cardID, amount))
            isRunning = false;
    }

    /**
     * Attend un message du terminal connecté.
     * @return String Message envoyé par le terminal.
     */
    private String receiveMessage() {
        try {
            return in.readLine();
        }
        catch (IOException e) {
            e.printStackTrace();
        }
        return "ERR";
    }

    /**
     * Envoie une chaine de caractère au terminal connecté.
     * @param message Le message.
     */
    private void sendMessage(String message) {
        out.write(message);
        out.flush();
    }
}