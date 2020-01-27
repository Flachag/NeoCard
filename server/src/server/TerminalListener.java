package server;

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
     * Constructeur initialisant le socket.
     * @param socket
     */
    public TerminalListener(Socket socket) {
        this.socket = socket;
        initialize();
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

    @Override
    public void run() {
        while (true) {
            System.out.println("Client connecté : " + socket.getInetAddress().getHostAddress());

            sendMessage("CONNECTED");

            String commande = receiveMessage();

            System.out.println(commande);
        }
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
        return null;
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