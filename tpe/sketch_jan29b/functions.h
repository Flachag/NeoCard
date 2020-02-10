#ifndef MY_FUNC_H
#define MY_FUNC_H

#include <Arduino.h>

inline String serialRead() {
  // Attente de saisie
  while (Serial.available() == 0) {}
  String str = Serial.readString();
  return str.substring(0, str.length()-1);
}

#endif
