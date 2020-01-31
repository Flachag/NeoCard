String serialRead() {
  // Attente de saisie
  while (Serial.available() == 0) {}
  String str = Serial.readString();
  return str.substring(0, str.length()-1);
}
