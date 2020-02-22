/**
   Bibliothèques
*/
#include "ServerModule.h"
#include  "WifiModule.h"
#include "functions.h"
#include <ESP8266HTTPClient.h>

/**
   Constantes
*/
final WifiModule wifiModule;

/**
        Constructeur par défaut.
*/
ServerModule::ServerModule() {
  this->tryingConnect = 0;
}

/**
          Tentative de connexion à l'adresse HTTP.
*/
void ServerModule::connection() {
  Serial.println("Tentative de connexion au serveur");
  Serial.println("Entrez l'adresse du serveur : ");
  this->address = serialRead();

  if (wifiModule.getWiFiStatus() != 3) {
    Serial.println();
    Serial.println("Pas de connexion Wifi, veuillez-vous reconnecter");
    wifiModule.connection();
  }

  sendCommand("PAY:1:10");

}

/**
      Envoie d'une commande via un header à l'adresse HTTP.
*/
void ServerModule::sendCommand(String commande) {
  HTTPClient http;
  WiFiClient client;
  const char * headerKeys[] = {"result"} ;
  const size_t numberOfHeaders = 1;

  if (http.begin(client, this->address)) {
    http.setUserAgent("TPE:" + wifiModule.getIpAddress());
    http.addHeader("Command", commande);
    http.collectHeaders(headerKeys, numberOfHeaders);

    Serial.println("Connexion à l'adresse : " + this->address);
    delay(2000);

    int httpCode = http.GET();
    if (httpCode > 0) {
      Serial.println();
      Serial.print("Command : ");
      Serial.print(commande);
      Serial.println(" envoyé au serveur");

    } else {
      Serial.print("Command : ");
      Serial.print(commande);
      Serial.println(" a échoué");
    }
    Serial.println(http.header("result"));
  } else {
    Serial.println("Impossible de se connecter à l'adresse : " + this->address);
  }
}
