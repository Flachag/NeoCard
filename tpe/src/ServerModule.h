#ifndef MY_SERVER_H
#define MY_SERVER_H

/**
   Bibliothèques
*/
#include <Arduino.h>
#include  "WifiModule.h"

/**
   Classe permettant de définir les attributs et fonctions de
   ServerModule.
*/
class ServerModule {
  private:
    /**
       Adresse HTTP de connexion au serveur.
    */
    String address;

    /**
       Montant de la transaction.
    */
    String amount;

    /**
       Nombre de tentative de connexion.
    */
    int tryingConnect;

    /**
       Module Wifi.
    */
    WifiModule wifiModule;

  public:
    /**
        Constructeur par défaut.
    */
    ServerModule(WifiModule wifi);

    /**
           Tentative de connexion à l'adresse HTTP.
    */
    void connection();

    /**
       Envoie d'une commande via un header à l'adresse HTTP.
    */
    void payCommand(String uidCard);
};

#endif
