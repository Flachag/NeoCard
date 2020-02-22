#ifndef MY_WIFI_H
#define MY_WIFI_H

/**
   Biblioth�ques
*/
#include <Arduino.h>

/**
   Classe permettant de d�finir les attributs et fonctions de
   WifiModule.
*/
class WifiModule {
  private:
    /**
       Nom du r�seau Wifi.
    */
    String ssid;

    /**
       Cl� secr�te du r�seau Wifi.
    */
    String password;

    /**
       Nombre de tentative de connexion.
    */
    int tryingConnect;

  public:
    /**
       Constructeur par d�faut.
    */
    WifiModule();

    /**
       Tentative de connexion au r�seau Wifi.
    */
    void connection();

    /**
       Donne l'adresse IP du module Wifi.
       @return String - IP du module Wifi.
    */
    String getIpAddress();

    /**
       Donne l'�tat du module Wifi.
       @return int - code de l'�tat.
    */
    int getWiFiStatus();

    /**
       V�rifie si le nom du r�seau Wifi existe.
       @return boolean - vrai si le r�seau existe.
    */
    boolean existSSID(String ssid);
};

#endif
