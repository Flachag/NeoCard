#include "ServerModule.h"
#include  "WifiModule.h"
#include "functions.h"

// pour la communication avec le serveur
#include <ESP8266HTTPClient.h>
WifiModule wifiModule;

ServerModule::ServerModule() {
  this->tryingConnect = 0;
}

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

void ServerModule::sendCommand(String commande) {
  HTTPClient http;
  http.setUserAgent("TPE:" + wifiModule.getIpAddress());
  http.addHeader("Command", commande);

  if (http.begin(this->address)) {
    Serial.println("Connexion à l'adresse : " + this->address);
    int httpCode = http.GET();
    if (httpCode > 0) {
      Serial.println();
      Serial.print("Commande : ");
      Serial.print(commande);
      Serial.println(" envoyé au serveur");
    } else {
      Serial.print("Commande : ");
      Serial.print(commande);
      Serial.println(" a échoué");
    }
  } else {
    Serial.println("Impossible de se connecter à l'adresse : " + this->address);
  }

  String payload = http.getString();
  Serial.println("received payload:\n<<");
  Serial.println(payload);
  Serial.println(">>");
  Serial.println();

  for (int i = 0; i < http.headers(); i++) {
    Serial.println(http.header(i));
  }
}
