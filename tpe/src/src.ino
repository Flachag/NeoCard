/**
   Bibliothèques
*/
#include <SPI.h>
#include <MFRC522.h>
#include  "WifiModule.h"
#include  "ServerModule.h"

/**
   Variables
*/
const int pinRST = 4;  // pin RST du module RC522
const int pinSDA = 5; // pin SDA du module RC522

MFRC522 rfid(pinSDA, pinRST);
String UID = "UID";
int secretCode = 0;
int afficher = 0;
WifiModule wifiModule;
ServerModule ServerModule(wifiModule);

/**
   Fonction d'initialisation du l'ESP
*/
void setup() {
  Serial.begin(115200);

  Serial.println("Initialisation des modules WIFI et RFID");
  SPI.begin();
  rfid.PCD_Init();

  wifiModule.connection();
  ServerModule.connection();
}

/**
   Fonction continue de l'ESP
*/
void loop() {
  if (afficher == 0) {
    Serial.println("En attente de lecture de la carte");
    afficher = 1;
    UID = "UID";
  }
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
        //Serial.println(UID);
        ServerModule.sendCommand("PAY:" + UID + ":10");
        afficher = 0;
      }
    }
  }
}
