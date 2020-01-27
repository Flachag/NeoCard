package database;

import java.io.FileInputStream;
import java.io.IOException;
import java.io.InputStream;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.util.Properties;

/**
 * Permet une connexion unique avec la base de donnée.
 * L'énumération permet d'assurer un singleton malgré le multithread.
 * Accessible uniquement dans le package database.
 */
enum SingleConnection {

    /**
     * L'instance unique.
     */
    INSTANCE;

    /**
     * L'attribut Connection de l'instance.
     */
    private Connection connection;

    /**
     * Constructeur PRIVE initialisant la connexion avec la base de donnée. (car enum)
     * Java l'appelle automatiquement la PREMIERE FOIS que l'on tente d'accéder à l'instance.
     * @see #initialize()
     */
    SingleConnection() {
        connection = initialize();
        try {
            connection.setAutoCommit(false);
        }
        catch (SQLException e) {
            e.printStackTrace();
        }
    }

    /**
     * Retourne la connexion avec la base de donnée.
     * @return Connection Connexion avec la base de donnée.
     */
    synchronized Connection getConnection() {
        return connection;
    }

    /**
     * Retourne une connexion avec la base de donnée.
     * Utilise le fichier /conf/database.conf pour les paramètres de connexion. (user etc)
     * @return Connection La connexion avec la base de donnée.
     */
    private Connection initialize() {
        try {
            Properties properties = new Properties();
            String fileName = "conf/database.conf";

            InputStream is = new FileInputStream(fileName);
            properties.load(is);

            String url = properties.getProperty("url");
            return DriverManager.getConnection(url, properties);
        }
        catch (IOException e) {
            System.err.println("Fichier 'conf/database.conf' est manquant.\n" +
                                "Voir le readme pour le configurer");
            e.printStackTrace();
        }
        catch (SQLException e) {
            System.err.println("Impossible de se connecter à la BDD." +
                                "\nInformations érronées dans le fichier 'conf/database.conf'" +
                                "\nVoir le readme pour le configurer");
            e.printStackTrace();
        }
        return null;
    }
}