package com;

import com.fazecast.jSerialComm.SerialPort;

import java.io.PrintWriter;
import java.util.Scanner;


/**
 *  Classe permettant de créer un objet Arduino pour simplier les actions
 */
public class Arduino {
    private SerialPort comPort;
    private String portDescription;
    private int baud_rate;

    /**
     * Constructeur par défaut
     */
    public Arduino() {

    }

    /**
     * Constructeur
     * @param portDescription - port série de communication
     */
    public Arduino(String portDescription) {
        this.portDescription = portDescription;
        comPort = SerialPort.getCommPort(this.portDescription);
    }

    /**
     * Constructeur
     * @param portDescription - port série de communication
     * @param baud_rate - vitesse de communication
     */
    public Arduino(String portDescription, int baud_rate) {
        this.portDescription = portDescription;
        comPort = SerialPort.getCommPort(this.portDescription);
        this.baud_rate = baud_rate;
        comPort.setBaudRate(this.baud_rate);
    }

    /**
     * Méthode permettant d'ouvrir une connexion USB entre l'Arduino et la machine
     * @return vrai si la connexion est ouverte
     */
    public boolean openConnection() {
        if (comPort.openPort()) {
            try {
                Thread.sleep(100);
            } catch (Exception e) {
            }
            return true;
        } else {
            System.err.println("Impossible de se connecter a se port");
            System.exit(-1);
            return false;
        }
    }

    /**
     * Méthode qui ferme la connexion entre l'Arduino et la machine
     */
    public void closeConnection() {
        comPort.closePort();
    }

    /**
     * Méthode qui change la vitesse de communication
     * @param baud_rate - vitesse de communication
     */
    public void setBaudRate(int baud_rate) {
        this.baud_rate = baud_rate;
        comPort.setBaudRate(this.baud_rate);
    }

    /**
     * Méthode qui donne le port "COMX"
     * @return le port
     */
    public String getPortDescription() {
        return portDescription;
    }

    /**
     * Méthode qui défine le port série
     * @param portDescription - port série
     */
    public void setPortDescription(String portDescription) {
        this.portDescription = portDescription;
        comPort = SerialPort.getCommPort(this.portDescription);
    }

    /**
     * Méthode qui donne le port série
     * @return le port série
     */
    public SerialPort getSerialPort() {
        return comPort;
    }

    /**
     * Méthode qui lit ce qu'envoie l'Arduino
     * @return la chaine envoyé par l'Arduino
     */
    public String serialRead() {
        //will be an infinite loop if incoming data is not bound
        comPort.setComPortTimeouts(SerialPort.TIMEOUT_READ_SEMI_BLOCKING, 0, 0);
        String out = "";
        Scanner in = new Scanner(comPort.getInputStream());
        try {
            while (in.hasNextLine()) {
                out = in.nextLine();
                if (!out.isEmpty()) {
                    break;
                }
            }
            in.close();
        } catch (Exception e) {
            e.printStackTrace();
        }
        return out;
    }

    /**
     * Méthode qui lit ce qu'envoie l'Arduino
     * @param limit - limite de réception
     * @return la chaine envoyé par l'Arduino
     */
    public String serialRead(int limit) {
        //in case of unlimited incoming data, set a limit for number of readings
        comPort.setComPortTimeouts(SerialPort.TIMEOUT_READ_SEMI_BLOCKING, 0, 0);
        String out = "";
        int count = 0;
        Scanner in = new Scanner(comPort.getInputStream());
        try {
            while (in.hasNext() && count <= limit) {
                out += (in.next() + "\n");
                count++;
            }
            in.close();
        } catch (Exception e) {
            e.printStackTrace();
        }
        return out;
    }

    /**
     * Méthode qui envoie à l'Arduino une chaine de caractères
     * @param s - la chaine
     */
    public void serialWrite(String s) {
        //writes the entire string at once.
        comPort.setComPortTimeouts(SerialPort.TIMEOUT_SCANNER, 0, 0);
        try {
            Thread.sleep(5);
        } catch (Exception e) {
        }
        PrintWriter pout = new PrintWriter(comPort.getOutputStream());
        pout.print(s);
        pout.flush();

    }

    /**
     * Méthode qui envoie à l'Arduino une chaine de caractères
     * @param s - la chaine
     * @param noOfChars - le nombre de caractères
     * @param delay - temps d'attente
     */
    public void serialWrite(String s, int noOfChars, int delay) {
        //writes the entire string, 'noOfChars' characters at a time, with a delay of 'delay' between each send.
        comPort.setComPortTimeouts(SerialPort.TIMEOUT_SCANNER, 0, 0);
        try {
            Thread.sleep(5);
        } catch (Exception e) {
        }
        PrintWriter pout = new PrintWriter(comPort.getOutputStream());
        int j = 0;
        for (int i = 0; i < s.length() - noOfChars; i += noOfChars) {
            pout.write(s.substring(i, i + noOfChars));
            pout.flush();
            try {
                Thread.sleep(delay);
            } catch (Exception e) {
            }
            j = i;
        }
        pout.write(s.substring(j));
        pout.flush();

    }

    /**
     * Méthode qui envoie à l'Arduino un caractère
     * @param c - le caractère
     */
    public void serialWrite(char c) {
        //writes the character to output stream.
        comPort.setComPortTimeouts(SerialPort.TIMEOUT_SCANNER, 0, 0);
        try {
            Thread.sleep(5);
        } catch (Exception e) {
        }
        PrintWriter pout = new PrintWriter(comPort.getOutputStream());
        pout.write(c);
        pout.flush();
    }

    /**
     * Méthode qui envoie à l'Arduino un caractère
     * @param c - le caractère
     * @param delay - temps d'attente
     */
    public void serialWrite(char c, int delay) {
        //writes the character followed by a delay of 'delay' milliseconds.
        comPort.setComPortTimeouts(SerialPort.TIMEOUT_SCANNER, 0, 0);
        try {
            Thread.sleep(5);
        } catch (Exception e) {
        }
        PrintWriter pout = new PrintWriter(comPort.getOutputStream());
        pout.write(c);
        pout.flush();
        try {
            Thread.sleep(delay);
        } catch (Exception e) {
        }
    }
}
