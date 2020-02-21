#include "WifiModule.h"
#include "functions.h"

// pour la communication WiFi
#include <ESP8266WiFi.h>
#include <WiFiClient.h>

WifiModule::WifiModule() {
  WiFi.mode(WIFI_STA);
  this->tryingConnect = 0;
}

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

int WifiModule::getWiFiStatus() {
  return WiFi.status();
}


void WifiModule::connection() {
  this->tryingConnect = 0;
  Serial.println();
  Serial.print("Tentative de connexion au reseau WiFi");
  // si en memoire

  // sinon
wifiConnect:
  WiFi.disconnect();
  Serial.println();
  Serial.println("Entrez SSID : ");
  this->ssid = serialRead();

  if (!existSSID(this->ssid)) {
    Serial.println("SSID inconnu");
    goto wifiConnect;
  }

  Serial.println("Entrez mot de passe : ");
  this->password = serialRead();

  WiFi.begin(this->ssid, this->password);

  Serial.println();
  Serial.print("Test de connexion au reseau WiFi");

wifiStatus:
  if (this->tryingConnect == 50) {
    Serial.println();
    Serial.println("Trop de tentative, veuillez reessayer");
    this->tryingConnect = 0;
    connection();
  } else {
    this->tryingConnect++;
  }

  if (WiFi.status() != WL_CONNECTED) {
    if (this->tryingConnect == 1) {
      Serial.println();
    }
    delay(500);
    if (this->tryingConnect % 2 == 0) {
      Serial.print(".");
    }
    goto wifiStatus;
  }
  else if (WiFi.status() == WL_CONNECTED)  {
    Serial.println();
    Serial.println("Connexion au reseau reussi !");

    Serial.print("Nom du reseau WiFi : ");
    Serial.println(this->ssid);

    Serial.print("Adresse IP TPE : ");
    Serial.println(WiFi.localIP());
    Serial.println();
  } else {
    goto wifiStatus;
  }
}
