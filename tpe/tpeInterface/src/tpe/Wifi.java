package tpe;

import javax.crypto.Cipher;
import javax.crypto.spec.SecretKeySpec;
import java.io.*;

/**
 * Classe permettant de définir les données wifi
 */
public class Wifi implements Serializable {
    private final String key = "Xh:vd&ph}RG@+=6k";
    private String SSID;
    private String password;

    /**
     * Constructeur
     */
    public Wifi() {
        this.SSID = "";
        this.password = "";
    }

    /**
     * Constructeur
     * @param SSID - nom du wifi
     * @param password - mot de passe du wifi
     */
    public Wifi(String SSID, String password) {
        this.SSID = SSID;
        this.password = password;
    }

    /**
     * Méthode qui donne le nom du wifi
     * @return le nom du wifi
     */
    public String getSSID() {
        return SSID;
    }

    /**
     * Méthode qui définie le nom du wifi
     * @param SSID - le nom du wifi
     */
    public void setSSID(String SSID) {
        this.SSID = SSID;
    }

    /**
     * Méthode qui crypte et sauvegarde les données wifi dans le fichier data.neo
     */
    public void save() {
        try {
            SecretKeySpec skeyspec = new SecretKeySpec(this.key.getBytes(), "Blowfish");

            Cipher cipher = Cipher.getInstance("Blowfish");
            cipher.init(Cipher.ENCRYPT_MODE, skeyspec);

            byte[] SSIDEncrypted = cipher.doFinal(this.SSID.getBytes("UTF8"));
            byte[] passwordEncrypted = cipher.doFinal(this.password.getBytes("UTF8"));

            ObjectOutputStream file = new ObjectOutputStream(new FileOutputStream((System.getProperty("user.home") + File.separator + "NeoCard" + File.separator + "data.neo")));
            file.writeObject(SSIDEncrypted);
            file.writeObject(passwordEncrypted);
            file.close();
        } catch (Exception ex) {
            System.err.println("Erreur lors de la sauvegarde des paramètres");
        }
    }

    /**
     * Méthode qui decrypte les données wifi du fichier data.neo
     */
    public void load() {
        try {
            ObjectInputStream file = new ObjectInputStream(new FileInputStream((System.getProperty("user.home") + File.separator + "NeoCard" + File.separator + "data.neo")));
            byte[] SSIDEncrypted = ((byte[]) (file.readObject()));
            byte[] passwordEncrypted = ((byte[]) (file.readObject()));
            file.close();

            SecretKeySpec skeyspec = new SecretKeySpec(this.key.getBytes(), "Blowfish");

            Cipher cipher = Cipher.getInstance("Blowfish");
            cipher.init(Cipher.DECRYPT_MODE, skeyspec);

            this.SSID = new String(cipher.doFinal(SSIDEncrypted));
            this.password = new String(cipher.doFinal(passwordEncrypted));
        } catch (Exception ex) {
            System.err.println("Erreur lors de la lecture des paramètres");
        }
    }

    /**
     * Méthode qui donne le mot de passe du wifi
     * @return le mot de passe
     */
    public String getPassword() {
        return password;
    }

    /**
     * Méthode qui définie le mot de passe du wifi
     * @param password - le mot de passe
     */
    public void setPassword(String password) {
        this.password = password;
    }
}
