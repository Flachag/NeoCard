package exception;

/**
 * Exception lancée si on tente d'appeler une méthode de l'API sans être connecté à celle-ci.
 */
public class NonConnecteException extends Exception {

    public NonConnecteException(String message) {
        super("Vous devez être connecté pour effectuer cette action : " + message);
    }
}