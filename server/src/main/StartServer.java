package main;

import server.Server;

import java.util.Scanner;

/**
 * Main pour démarrer le serveur.
 */
public class StartServer {

    /**
     * Démarre le serveur sur la machine executant le programme.
     * @param args String[] Inutile.
     */
    public static void main(String[] args) {
        Server server = new Server();

        new Scanner(System.in).next();//Attend une entrée clavier pour fermer le serveur.
        server.stop();
        System.exit(0);
    }
}