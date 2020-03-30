# Composants

* 	Adafruit Feather HUZZAH ESP8266
* 	Module iO lecteur RFID MFRC522 VMA405

### Documentation

*	[ESP8266](https://cdn-learn.adafruit.com/downloads/pdf/adafruit-feather-huzzah-esp8266.pdf)
*	[MFRC522](https://www.velleman.eu/downloads/29/infosheets/mfrc522_datasheet.pdf)

# Configurer le TPE

## Branchements

![alt text](Montage.jpg)

## Developpeur

### Installation

1. Installer avant tout le pilote pour détecter l'ESP8266 : http://www.silabs.com/products/development-tools/software/usb-to-uart-bridge-vcp-drivers.

2. Installer un l'IDE Arduino : https://www.arduino.cc/en/Main/Software.
Ouvrir le fichier **src.ino** avec l'IDE.

### Configuration

#### Ajout des bibliothèques

1. Dans l'IDE aller dans **Outils > Gestionnaire de bibliothèques**

2. Rechercher et installer les bibliothèques suivantes (si elles ne sont pas détecter par l'IDE *(surligner en orange)*) :
	* 	SPI
	* 	MFRC522
	* 	ESP8266WiFi
	* 	ESP8266HTTPClient
	* 	WiFiClient

#### Ajout de la carte

1. Aller ensuite dans **Fichier > Préférences**.
Dans le champs **Url de gestionnaire de cartes supplémentaires** ajoutez : http://arduino.esp8266.com/stable/package_esp8266com_index.json.

2. Aller dans **Outils > Type de carte > Gestionnaire de carte**, rechercher *esp8266* et installer sa dernière version.

3. Toujours dans **Outils** placer les paramètres suivants :

![alt text](Parametres.jpg)

*Lors du branchement de l'ESP8266, selectionnez le bon port*

La configuration est terminée. Vous pouvez téléverser le programme dans la carte.

## Général

### Exécuter l'application

```
java -jar TPE_Interface.jar
```

### Informations

L'application est compilé avec **JDK 1.8.0_241**.
Bibliothèques présentes : 
	*	JavaFx 11.0.2
	*	SerialComm 2.6.0

Lors du fonctionnement du TPE la **LED rouge** indique que l'ESP8266 **n'est pas connecté à un réseau**.