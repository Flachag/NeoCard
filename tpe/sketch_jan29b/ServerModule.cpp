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
  this->address = serialRead();

  if (this->wifiModule.getWiFiStatus() != 3) {
    Serial.println();
    Serial.println("Pas de connexion Wifi, veuillez-vous reconnecter");
    this->wifiModule.connection();
  }
}

/**
      Envoie d'une commande via un header à l'adresse HTTP.
*/
void ServerModule::sendCommand(String commande) {
  HTTPClient http;
  WiFiClient client;
  const char * headerKeys[] = {"Result"} ;
  const size_t numberOfHeaders = 1;

  if (http.begin(client, this->address)) {
    http.setUserAgent("TPE:" + this->wifiModule.getIpAddress());
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
    Serial.println(http.header("Result"));
    http.stop();
  } else {
    Serial.println("Impossible de se connecter à l'adresse : " + this->address);
  }
}
