package server;

import java.io.IOException;
import java.net.InetAddress;
import java.net.ServerSocket;
import java.net.Socket;

/**
 * Serveur multithread créant une connexion pour chaque terminal qui se connecte.
 * Utilise le protocole TCP.
 */
public class Server {

    /**
     * Le port sur lequel écoute le serveur.
     */
    private int port;

    /**
     * L'adresse ip du serveur.
     */
    private String ip;

    /**
     * Le socket du serveur.
     */
    private ServerSocket server;

    /**
     * Spécifie si le serveur est lancé ou non.
     */
    private boolean isRunning;

    /**
     * Constructeur qui lance le serveur.
     * @param port int Port du serveur.
     * @param ip String Adresse ip du serveur.
     */
    public Server(int port, String ip) {
        try {
            server = new ServerSocket(port, 50, InetAddress.getByName(ip));
            this.port = port;
            this.ip = ip;

            start();
        }
        catch (IOException e) {
            e.printStackTrace();
        }
    }

    /**
     * Lance le serveur.
     */
    public void start() {
        isRunning = true;

        //Dans un thread à part comme il est dans une boucle infinie
        Thread t = new Thread(new ServerListener());
        t.start();

        System.out.println("Le serveur est lancé\nPort : " + port + "\nIP : " + ip);
    }

    /**
     * Ferme le serveur.
     */
    public void stop() {
        isRunning = false;

        System.out.println("Le serveur est stoppé");
    }

    /**
     * Gère les tentatives de connexion par les terminaux clients.
     */
    private class ServerListener implements Runnable {

        @Override
        public void run() {
            while(isRunning){
                try {
                    //On attend une connexion d'un client
                    Socket client = server.accept();

                    //Une fois reçue, on la traite dans un thread séparé
                    System.out.println("Client : " + client.getRemoteSocketAddress() + " connecté au serveur");
                    Thread t = new Thread(new TerminalListener(client));
                    t.start();
                }
                catch (IOException e) {
                    e.printStackTrace();
                }
            }

            //Une fois sorti de la boucle infinie on ferme le serveur
            try {
                server.close();
            }
            catch (IOException e) {
                e.printStackTrace();
                server = null;
            }
        }
    }
}