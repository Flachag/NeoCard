/**
   Bibliothèques
*/
#include <SPI.h>
#include <MFRC522.h>
#include  "WifiModule.h"
#include  "ServerModule.h"

/**
   Constantes
*/
const int pinRST = 5;  // pin RST du module RC522 : GPIO5
const int pinSDA = 4; // pin SDA du module RC522 : GPIO4

MFRC522 rfid(pinSDA, pinRST);
String UID = "UID";
int secretCode = 0;

/**
   Fonction d'initialisation du l'ESP
*/
void setup() {
  Serial.begin(115200);

  Serial.println("Initialisation des modules WIFI et RFID");
  SPI.begin();
  rfid.PCD_Init();

  WifiModule wifiModule;
  wifiModule.connection();

  ServerModule ServerModule;
  ServerModule.connection();

  Serial.println("En attente de lecture de la carte");
}

/**
   Fonction continue de l'ESP
*/
void loop() {
  if (UID.equals("UID")) {
    if (rfid.PICC_IsNewCardPresent())  // detecte tag
    {
      if (rfid.PICC_ReadCardSerial())  // lecture contenu
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
