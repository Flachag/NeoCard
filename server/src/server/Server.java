package server;

import java.io.FileInputStream;
import java.io.IOException;
import java.io.InputStream;
import java.net.InetAddress;
import java.net.ServerSocket;
import java.net.Socket;
import java.net.UnknownHostException;
import java.util.Properties;

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
     * Constructeur qui lance le serveur.
     */
    public Server() {
        try {
            Properties properties = new Properties();
            String fileName = "conf/server.conf";
            InputStream is = new FileInputStream(fileName);
            properties.load(is);

            port = Integer.parseInt(properties.getProperty("port"));
            ip = getHostIp();

            server = new ServerSocket(port, 50, InetAddress.getByName(ip));

            start();
        }
        catch (UnknownHostException e) {
            System.err.println("Impossible de lancer le serveur." +
                            "\nLe programme n'arrive pas à récupérer l'adresse ip de la machine");
            e.printStackTrace();
        }
        catch (IOException e) {
            System.err.println("Impossible de lancer le serveur." +
                            "\nInformations érronées dans le fichier 'conf/server.conf'" +
                            "\nVoir le readme pour le configurer");
            e.printStackTrace();
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