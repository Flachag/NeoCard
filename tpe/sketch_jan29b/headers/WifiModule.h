#ifndef MY_WIFI_H
#define MY_WIFI_H

/**
   Bibliothèques
*/
#include <Arduino.h>

/**
   Classe permettant de définir les attributs et fonctions de
   WifiModule.
*/
class WifiModule {
  private:
    /**
       Nom du réseau Wifi.
    */
    String ssid;

    /**
       Clé secrète du réseau Wifi.
    */
    String password;

    /**
       Nombre de tentative de connexion.
    */
    int tryingConnect;

  public:
    /**
       Constructeur par défaut.
    */
    WifiModule();

    /**
       Tentative de connexion au réseau Wifi.
    */
    void connection();

    /**
       Donne l'adresse IP du module Wifi.
       @return String - IP du module Wifi.
    */
    String getIpAddress();

    /**
       Donne l'état du module Wifi.
       @return int - code de l'état.
    */
    int getWiFiStatus();

    /**
       Vérifie si le nom du réseau Wifi existe.
       @return boolean - vrai si le réseau existe.
    */
    boolean existSSID(String ssid);
};

#endif
