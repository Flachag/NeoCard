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
        Server server;
        if (args.length == 2 && args[0].equals("-p"))
            server = new Server(Integer.parseInt(args[1]));
        else
            server = new Server(80);

        new Scanner(System.in).next();//Attend une entrée clavier pour fermer le serveur.
        server.stop();
        System.exit(0);
    }
}