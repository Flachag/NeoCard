package database;

import exception.ApiConnectionException;
import exception.NonConnecteException;
import org.apache.hc.client5.http.fluent.Form;
import org.apache.hc.client5.http.fluent.Request;
import org.apache.hc.client5.http.fluent.Response;
import org.apache.hc.core5.util.Timeout;

import java.io.FileInputStream;
import java.io.IOException;
import java.io.InputStream;
import java.util.Properties;

/**
 * Interface avec l'API du serveur web.
 * L'énumération permet d'assurer un singleton malgré le multithread.
 */
public enum ApiWrapper {

    /**
     * L'instance unique.
     */
    INSTANCE;

    /**
     * L'URL de l'API.
     */
    private String url;

    /**
     * L'username du compte pouvant intérargir avec l'API.
     */
    private String username;

    /**
     * Le mot de passe du compte pouvant intérargir avec l'API.
     * Utiliser char[] permet une meilleure sécurité que String. (Car un String est immutable)
     */
    private char[] password;

    /**
     * Constructeur PRIVE initialisant la connexion avec la base de donnée. (car enum)
     * Java l'appelle automatiquement la PREMIERE FOIS que l'on tente d'accéder à l'instance.
     *
     * Utilise le fichier /conf/database.conf pour les paramètres de connexion. (user etc)
     */
    ApiWrapper() {
        try {
            Properties properties = new Properties();
            String fileName = "conf/api.conf";

            InputStream is = new FileInputStream(fileName);
            properties.load(is);

            url = properties.getProperty("url");
            username = properties.getProperty("username");
            password = properties.getProperty("password").toCharArray();
        }
        catch (IOException e) {
            System.err.println("Fichier 'conf/api.conf' est manquant.\n" +
                    "Voir le readme pour le configurer");
            System.exit(1);
        }
    }

    /**
     * Se connecte à l'API en utilisant les paramètres de connexion.
     * A APPELER AVANT TOUTE AUTRE REQUETE.
     * @throws ApiConnectionException Est lancée si un problème de connexion occure.
     */
    public static void openConnection() throws ApiConnectionException {
        try {
           Response response = Request.post(INSTANCE.url)
                    .connectTimeout(Timeout.ofSeconds(5))
                    .bodyForm(Form.form()
                            .add("_username", INSTANCE.username)
                            .add("_password", String.valueOf(INSTANCE.password))
                            .build()
                    ).execute();

           INSTANCE.password = null; //On n'a plus besoin de stocker le mdp pour la suite => sécurité

           if (response.returnContent().asString().contains("<title>Connexion</title>"))
                throw new ApiConnectionException("Username ou mot de passe incorrect");

           checkRole();
        }
        catch (IOException e) {
            throw new ApiConnectionException("Pas de connexion internet");
        }
    }

    /**
     * Déconnecte le serveur de l'API.
     */
    public static void closeConnection() {
        try {
            Request.get(INSTANCE.url + "deconnexion")
                    .connectTimeout(Timeout.ofSeconds(5))
                    .execute();
        }
        catch (IOException e) {
            System.err.println("Pas de connexion internet");
        }

    }

    /**
     * Vérifie si le rôle du compte connecté a bien le droit d'utiliser l'API.
     * @throws ApiConnectionException Est lancée si un problème de connexion occure.
     * @throws IOException Est lancée si un problème de connexion occure, indépendant de l'API. (Connexion internet...)
     */
    private static void checkRole() throws ApiConnectionException, IOException {
        int responseCode = Request.get(INSTANCE.url + "api/checkIP/" + 0)
                .connectTimeout(Timeout.ofSeconds(5))
                .execute().returnResponse().getCode();

        if (responseCode == 403)
            throw new ApiConnectionException("Le compte doit avoir le rôle : \"ROLE_API_USER\"");
    }

    /**
     * Vérifie si une ip est connue. (Présente dans la BDD de l'application web)
     * @param ip IP à vérifier.
     * @return L'id du compte associé au TPE si l'ip est connue. -1 sinon.
     * @throws NonConnecteException
     */
    public static int checkIP(String ip) throws NonConnecteException {
        try {
            String body = Request.get(INSTANCE.url + "api/checkIP/" + ip)
                    .connectTimeout(Timeout.ofSeconds(5))
                    .execute().returnContent().asString();

            return Integer.parseInt(body.replaceAll("[^0-9]", ""));
        }
        catch (IOException e) {
            return -1;
        }
        catch (Exception e) {
            throw new NonConnecteException("checkIP");
        }
    }

    /**
     * Effectue un paiement entre le compte associé au TPE et une carte.
     * @param idReceiver ID du compte recepteur. (Compte associé au TPE)
     * @param cardUID UID de la carte emettrice. (Compte associé à la carte)
     * @param amount Montant de la transaction.
     * @return True si le paiement a été effectué.
     */
    public static boolean pay(int idReceiver, String cardUID, float amount) {
        final String ERROR_MESSAGE = "Erreur lors d'un virement avec un TPE" +
                "\nID du compte associé au TPE : " + idReceiver +
                "\nUID de la carte : " + cardUID +
                "\nMontant : " + amount;

        try {
            checkLess3Decimal(amount);

            int responseCode = Request.get(INSTANCE.url + "api/pay/" + idReceiver + "/" + cardUID + "/" + amount)
                    .connectTimeout(Timeout.ofSeconds(5))
                    .execute().returnResponse().getCode();

            if (responseCode == 403)
                System.err.println(ERROR_MESSAGE);
            else
                System.out.println("Paiement effectué avec succès.\n" +
                        "Emetteur : " + cardUID + "   Receveur : " + idReceiver + "    Montant : " + amount);

            return responseCode != 403;
        }
        catch (NumberFormatException tooMuchDecimals) {
            System.err.println(ERROR_MESSAGE+"\n" + tooMuchDecimals.getMessage());
            return false;
        }
        catch (IOException e) {
            return false;
        }
    }

    /**
     * Vérifie que le montant d'une transaction comporte au maximum 2 décimales.
     * @param amount Le montant.
     * @throws NumberFormatException Lancée si le montant a plus de 2 décimales.
     */
    private static void checkLess3Decimal(float amount) throws NumberFormatException {
        String[] parser = String.valueOf(amount).split("\\.");
        if (parser.length == 2 && parser[1].length() > 2)
            throw new NumberFormatException("Le montant ne peut qu'avoir 2 décimales : " + amount);
    }

    /**
     * Retourne le solde d'un compte.
     * @param accountID Numéro du compte.
     * @return Solde du compte.
     * @throws NonConnecteException Lancée si le serveur n'est pas connecté à l'API.
     */
    private static float soldeCompte(int accountID) throws NonConnecteException {
        try {
            String body = Request.get(INSTANCE.url + "api/soldeCompte/" + accountID)
                    .connectTimeout(Timeout.ofSeconds(5))
                    .execute().returnContent().asString();

            return Float.parseFloat(body.replaceAll("[^0-9.]", ""));
        }
        catch (IOException e) {
            return -1;
        }
        catch (Exception e) {
            throw new NonConnecteException("soldeCompte");
        }
    }
}