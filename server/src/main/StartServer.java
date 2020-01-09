package main;

import server.Server;

import java.net.InetAddress;
import java.net.UnknownHostException;

public class StartServer {

    public static void main(String[] args) throws Exception {
        final int port = 80;
        new Server(port, getHostIp());
    }

    /**
     * Récupère l'adresse ip sur le réseau de la machine executant ce programme.
     * @return String Adresse ipv4.
     * @throws UnknownHostException Le programme n'arrive pas à récupérer l'adresse ip.
     */
    private static String getHostIp() throws UnknownHostException {
        return InetAddress.getLocalHost().getHostAddress();
    }
}
