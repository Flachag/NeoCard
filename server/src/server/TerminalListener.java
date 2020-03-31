package server;

import database.ApiWrapper;
import exception.NonConnecteException;

import java.io.*;
import java.net.Socket;
import java.net.SocketException;
import java.net.SocketTimeoutException;

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
     * Déconnecte automatiquement le TPE si aucune réponse en 10 secondes.
     * @param socket Socket Connexion TPE-Serveur
     */
    public TerminalListener(Socket socket) {
        this.socket = socket;
        try {
            socket.setSoTimeout(10000);
        } catch (SocketException e) {
            e.printStackTrace();
        }
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
            isRunning = false;
            Server.ipTpeConnectes.remove(socket.getInetAddress().getHostAddress());
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
        int idAccount = 0;
        try {
            idAccount = ApiWrapper.checkIP(ip);
        }
        catch (NonConnecteException e) {
            System.err.println(e.getMessage());
            System.exit(1);
        }
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

        while (isRunning) {
            //ON attend la réponse du TPE
            String commande = receiveMessage();

            //Si la réponse est incomplète
            if (!isCommand(commande))
                continue;

            commande = commande.replaceFirst("Command: ", "");

            //Et on traite la réponse
            switch (commande.substring(0, 3)) {
                case "ERR" : {
                    System.err.println("Erreur le serveur n'a pas pu recevoir la réponse du TPE : " +
                            socket.getInetAddress().getHostAddress());
                    sendMessage("ERROR");
                    closeConnection();
                    break;
                }
                case "PAY" : {
                    pay(commande);
                    closeConnection();
                    break;
                }
                default : sendMessage("UNKNOWN_COMMAND");System.err.println("Commande inconnue :\n" + commande);
            }
        }

        System.out.println("TPE : " + socket.getInetAddress().getHostAddress() + " déconnecté.");
    }

    /**
     * Vérifie si un String est bien une commande.
     * @param toCheck String à tester.
     * @return True si c'est bien une commande.
     */
    private boolean isCommand(String toCheck) {
        try {
            return toCheck.startsWith("Command");
        } catch (Exception e) {
            return false;
        }
    }

    /**
     * Essaye d'effectuer un paiement.
     * @param commande String Contient la commande envoyée par le TPE.
     */
    private void pay(String commande) {
        String[] args = commande.split(":");
        String cardUID = args[1];
        float amount = Float.parseFloat(args[2]);

        //Coupe la connexion si il y'a une erreur de paiement.
        if (!ApiWrapper.pay(idAccount, cardUID, amount))
            sendMessage("PAIEMENT_REFUSED");
        else
            sendMessage("PAIEMENT_ACCEPTED");
    }

    /**
     * Attend un message du terminal connecté.
     * @return String Message envoyé par le terminal.
     */
    private String receiveMessage() {
        try {
            String response = in.readLine();
            if (response == null)
                throw new IOException();

            return response;
        }
        catch (SocketTimeoutException e) {
            sendMessage("TIMEOUT");
            closeConnection();
        }
        catch (IOException e) {
            sendMessage("ERROR_WHILE_READING_COMMAND");
            closeConnection();
        }
        return "ERR";
    }

    /**
     * Envoie une requête HTTP au TPE associé.
     * Cette requête contient un header 'Result'.
     * @param valeur Valeur du header 'Result'.
     */
    private void sendMessage(String valeur) {
        final String HTTP_header = "HTTP/1.1 200 OK\r\n" +
                "Result: " + valeur + "\r\n" +
                "\r\n";

        out.write(HTTP_header);
        out.flush();
    }
}