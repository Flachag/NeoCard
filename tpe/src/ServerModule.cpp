/**
   Bibliothèques
*/
#include "ServerModule.h"
#include  "WifiModule.h"
#include "functions.h"
#include <ESP8266HTTPClient.h>

/**
        Constructeur par défaut.
*/
ServerModule::ServerModule(WifiModule wifi) {
  this->tryingConnect = 0;
  this->wifiModule = wifi;
}

/**
          Tentative de connexion à l'adresse HTTP.
*/
void ServerModule::connection() {
  Serial.println("Tentative de connexion au serveur");

  Serial.println("Entrez l'adresse du serveur : ");
  while (!Serial.available()) {}
  this->address  = Serial.readString();

  Serial.println("Entrez montant : ");
  while (!Serial.available()) {}
  this->amount  = Serial.readString();
}

/**
      Envoie d'une commande via un header à l'adresse HTTP.
*/
void ServerModule::payCommand(String uidCard) {
  HTTPClient http;
  WiFiClient client;
  const char * headerKeys[] = {"Result"} ;
  const size_t numberOfHeaders = 1;

  if (http.begin(client, this->address)) {
    http.addHeader("Command", "PAY:" + uidCard + ":" + this->amount);
    http.collectHeaders(headerKeys, numberOfHeaders);

    Serial.println("Connexion a l'adresse : " + this->address);

    int httpCode = http.GET();
    if (httpCode > 0) {
      Serial.println();
      Serial.println("Command : PAY:" + uidCard + ":" + this->amount + " envoye au serveur");
    } else {
      Serial.println("Command : PAY:" + uidCard + ":" + this->amount + " a echoue");
    }
    Serial.println(http.header("Result"));
    
  } else {
    Serial.println("Impossible de se connecter a l'adresse : " + this->address);
  }
}
