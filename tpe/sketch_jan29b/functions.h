#ifndef MY_FUNC_H
#define MY_FUNC_H

/**
   Bibliothèques
*/
#include <Arduino.h>

/**
   Fonction inline permettant de récupérer la saisie au clavier
   du SerialMonitor.
   @return string - La chaine saisie.
*/
inline String serialRead() {
  // Attente de saisie
  while (Serial.available() == 0) {}
  String str = Serial.readString();
  return str.substring(0, str.length() - 1);
}

#endif
