#ifndef MY_SERVER_H
#define MY_SERVER_H

#include <Arduino.h>

class ServerModule {
  private:
    String address;
    int tryingConnect;

  public:
    ServerModule();

    void connection();
    void sendCommand(String commande);
};

#endif
