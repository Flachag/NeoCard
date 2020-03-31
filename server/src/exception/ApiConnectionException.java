package exception;

/**
 * Exception lancée s'il y'a un problème lors de la connexion à l'API.
 */
public class ApiConnectionException extends Exception {

    public ApiConnectionException(String message) {
        super("Erreur connexion à l'API : " + message);
    }
}