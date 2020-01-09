package server;

import java.net.Socket;

/**
 * Gère les commandes recues par les TPE clients.
 * Une instance de cette classe = 1 connexion par 1 TPE unique.
 */
public class TerminalListener implements Runnable {

    /**
     * Socket attribué par le serveur.
     */
    private Socket socket;

    /**
     * Constructeur initialisant le socket.
     * @param socket
     */
    public TerminalListener(Socket socket) {
        this.socket = socket;
    }

    @Override
    public void run() {
        while (true) {
            try {
                Thread.sleep(2000);
                System.out.println("Client connecté : " + socket.getInetAddress().getHostAddress());
            } catch (InterruptedException e) {
                e.printStackTrace();
            }
        }
    }
}