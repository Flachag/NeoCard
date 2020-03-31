package server;

import database.ApiWrapper;
import exception.ApiConnectionException;

import java.io.IOException;
import java.net.*;
import java.util.ArrayList;

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
     * Le socket du serveur protégé.
     */
    private ServerSocket server;

    /**
     * Spécifie si le serveur est lancé ou non.
     */
    private boolean isRunning;

    /**
     * Les IP des TPE connectés pour empécher une double connexion du même TPE.
     */
    public static ArrayList<String> ipTpeConnectes;

    /**
     * Constructeur qui lance le serveur.
     * @param port int Port sur lequel le serveur va écouter.
     */
    public Server(int port) {
        try {
            this.port = port;
            ipTpeConnectes = new ArrayList<>(3);
            ip = getHostIp2();

            server = new ServerSocket(port, 50, InetAddress.getByName(ip));

            try {
                ApiWrapper.openConnection();
            }
            catch (ApiConnectionException e) {
                System.err.println(e.getMessage());
                System.exit(1);
            }

            start();
        }
        catch (UnknownHostException e) {
            System.err.println("Impossible de lancer le serveur." +
                            "\nLe programme n'arrive pas à récupérer l'adresse ip de la machine");
            System.exit(1);
        }
        catch (IOException e) {
            System.err.println("Impossible de lancer le serveur." +
                    "\nLe port spécifié (" + port + ") est déjà utilisé");
            System.exit(1);
        }
    }

    /**
     * Récupère l'adresse ip sur le réseau de la machine executant ce programme.
     * @return String Adresse ipv4.
     * @throws UnknownHostException Le programme n'arrive pas à récupérer l'adresse ip.
     */
    private String getHostIp() throws UnknownHostException {
        return InetAddress.getLocalHost().getHostAddress();
    }

    /**
     * Récupère l'adresse ip sur le réseau de la machine executant ce programme.
     * Facon numéro 2.
     * @return String Adresse ipv4.
     * @throws UnknownHostException Le programme n'arrive pas à récupérer l'adresse ip.
     */
    private String getHostIp2() throws UnknownHostException {
        String ip = "ERROR";
        try(final DatagramSocket socket = new DatagramSocket()){
            socket.connect(InetAddress.getByName("8.8.8.8"), 10002);
            ip = socket.getLocalAddress().getHostAddress();
        } catch (SocketException e) {
            e.printStackTrace();
        }
        return ip;
    }

    /**
     * Lance le serveur.
     */
    public void start() {
        isRunning = true;

        //Dans un thread à part comme il est dans une boucle infinie
        Thread t = new Thread(new ServerListener());
        t.start();

        System.out.println("Le serveur est lancé\nPort : " + port + "\nIP : " + ip + "\n");
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
                    //On attend une connexion d'un TPE
                    Socket client = server.accept();
                    String ipClient = client.getInetAddress().getHostAddress();

                    //Rejete la connexion si le TPE est déjà connecté
                    if (alreadyConnected(ipClient)) {
                        System.err.println("TPE : " + ipClient + " est déjà connecté au serveur");
                        client.close();
                        continue;
                    }

                    //Une fois reçue, on la traite dans un thread séparé
                    System.out.println("TPE : " + ipClient + " connecté au serveur");
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
                ApiWrapper.closeConnection();
            }
            catch (IOException e) {
                System.exit(1);
            }
        }

        /**
         * Vérifie si le TPE est déjà connecté au serveur.
         * Ajoute le TPE à la liste des TPE connectés si il n'y est pas.
         * @param ipTPE Adresse IP du TPE.
         * @return boolean True si le TPE est déjà connecté.
         */
        private boolean alreadyConnected(String ipTPE) {
            if (ipTpeConnectes.contains(ipTPE))
                return true;

            ipTpeConnectes.add(ipTPE);
            return false;
        }
    }
}