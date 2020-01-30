// pour le RC522:
#include <SPI.h>
#include <MFRC522.h>

// pour la communication WiFi
#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>
#include <WiFiClient.h>

// pour les fonctions
#include  "functions.h"

const int pinRST = 5;  // pin RST du module RC522 : GPIO5
const int pinSDA = 4; // pin SDA du module RC522 : GPIO4

MFRC522 rfid(pinSDA, pinRST);

String ssid = "ssid";
String password = "password";
String UID = "UID";
int secretCode = 0;

void setup() {
  Serial.println("Initialisation des modules WIFI et RFID");
  SPI.begin();
  rfid.PCD_Init();
  Serial.begin(9600);

  Serial.print("Connexion au reseau WiFi");

  boolean existingWifi = false;

  if (!existingWifi) {
    Serial.println();
    Serial.print("Entrez SSID : ");
    ssid = serialRead();
    Serial.println();
    Serial.print("Entrez mot de passe : ");
    password = serialRead();
  }

  while (WiFi.status() != WL_CONNECTED) {
    Serial.print(".");
    delay(1000);
  }

  Serial.println();
  Serial.println("Connexion au reseau reussi !");

  Serial.print("Nom du reseau WiFi : ");
  Serial.println(ssid);

  Serial.print("Adresse IP TPE : ");
  Serial.println(WiFi.localIP());

  Serial.println("Tentative de connexion au serveur");

  Serial.println("En attente de lecture de la carte");
}

void loop() {
  if (UID.equals("UID")) {
    if (rfid.PICC_IsNewCardPresent())  // detect tag
    {
      if (rfid.PICC_ReadCardSerial())  // read contains
      {
        UID = "";
        for (byte i = 0; i < rfid.uid.size; i++) {
          UID.concat(String(rfid.uid.uidByte[i] < 0x10 ? "0" : ""));
          UID.concat(String(rfid.uid.uidByte[i], HEX));
        }
        UID.toUpperCase();
        Serial.println(UID);
      }
    }
  }
}
