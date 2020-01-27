package main;

import database.Database;
import server.Server;

/**
 * Main pour démarrer le serveur.
 */
public class StartServer {

    /**
     * Démarre le serveur sur la machine executant le programme.
     * @param args String[] Inutile.
     */
    public static void main(String[] args) {
        Database.openConnection();
        Database.test();
        //new Server();
    }
}
