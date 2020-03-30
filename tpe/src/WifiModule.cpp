/**
   Bibliothèques
*/
#include "WifiModule.h"
#include "functions.h"
#include <ESP8266WiFi.h>
#include <WiFiClient.h>

/**
     Constructeur par défaut.
*/
WifiModule::WifiModule() {
  WiFi.mode(WIFI_STA);
  this->tryingConnect = 0;
}

/**
       Donne l'adresse IP du module Wifi.
       @return String - IP du module Wifi.
*/
String WifiModule::getIpAddress() {
  String ip = "";
  if (WiFi.status() == WL_CONNECTED)  {
    IPAddress ipAddress = WiFi.localIP();
    for (int i = 0; i < 4; i++) {
      ip += i  ? "." + String(ipAddress[i]) : String(ipAddress[i]);
    }
  }
  return ip;
}

/**
       Vérifie si le nom du réseau Wifi existe.
       @return boolean - vrai si le réseau existe.
*/
boolean WifiModule::existSSID(String ssid) {
  boolean flag = false;
  int n = WiFi.scanNetworks();
  if (n == 0) {
    Serial.println("Aucun reseau WiFi trouve");
  } else {
    for (int i = 0; i < n; ++i) {
      if (WiFi.SSID(i) == ssid) {
        flag = true;
        break;
      }
    }
  }
  return flag;
}

/**
      Donne l'état du module Wifi.
      @return int - code de l'état.
*/
int WifiModule::getWiFiStatus() {
  return WiFi.status();
}

/**
      Tentative de connexion au réseau Wifi.
*/
void WifiModule::connection() {
  if (WiFi.status() != WL_CONNECTED) {
    this->tryingConnect = 0;
    Serial.println();
    Serial.print("Tentative de connexion au reseau WiFi");
wifiConnect:
    WiFi.disconnect();
    Serial.println();
    this->ssid = "";
    this->password = "";
    digitalWrite(LED_BUILTIN, LOW);

    Serial.println("Entrez SSID : ");
    while (!Serial.available()) {}
    this->ssid = Serial.readString();

    if (!existSSID(this->ssid)) {
      Serial.println("SSID inconnu");
      goto wifiConnect;
    }

    Serial.println("Entrez mot de passe : ");
    while (!Serial.available()) {}
    this->password = Serial.readString();

    WiFi.begin(this->ssid, this->password);

    Serial.println();
    Serial.print("Test de connexion au reseau WiFi");

wifiStatus:
    if (this->tryingConnect == 25) {
      Serial.println();
      String waitResponse = "";

      Serial.println("Trop de tentative, veuillez reessayer");
      while (!Serial.available()) {}
      waitResponse = Serial.readString();

      this->tryingConnect = 0;
      goto wifiConnect;
    } else {
      this->tryingConnect++;
    }

    if (WiFi.status() != WL_CONNECTED) {
      if (this->tryingConnect == 1) {
        Serial.println();
      }
      delay(500);
      Serial.print(".");
      goto wifiStatus;
    }
    else if (WiFi.status() == WL_CONNECTED)  {
      Serial.println();
      String waitResponse = "";

      Serial.println("Connexion au reseau reussi !");
      while (!Serial.available()) {}
      waitResponse = Serial.readString();

      Serial.print("Nom du reseau WiFi : ");
      Serial.println(this->ssid);

      Serial.print("Adresse IP TPE : ");
      Serial.println(WiFi.localIP());
      Serial.println();

      digitalWrite(LED_BUILTIN, HIGH);
    } else {
      goto wifiStatus;
    }
  }
}
