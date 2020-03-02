#ifndef MY_SERVER_H
#define MY_SERVER_H

/**
   Bibliothèques
*/
#include <Arduino.h>

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
       Nombre de tentative de connexion.
    */
    int tryingConnect;

  public:
    /**
        Constructeur par défaut.
    */
    ServerModule();

    /**
           Tentative de connexion à l'adresse HTTP.
    */
    void connection();

    /**
       Envoie d'une commande via un header à l'adresse HTTP.
    */
    void sendCommand(String commande);
};

#endif
