#ifndef MY_SERVER_H
#define MY_SERVER_H

/**
   Biblioth�ques
*/
#include <Arduino.h>

/**
   Classe permettant de d�finir les attributs et fonctions de
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
        Constructeur par d�faut.
    */
    ServerModule();

    /**
           Tentative de connexion � l'adresse HTTP.
    */
    void connection();

    /**
       Envoie d'une commande via un header � l'adresse HTTP.
    */
    void sendCommand(String commande);
};

#endif
