#ifndef MY_WIFI_H
#define MY_WIFI_H

#include <Arduino.h>

class WifiModule {
  private:
    String ssid;
    String password;
    int tryingConnect;

  public:
    WifiModule();

    void connection();
    boolean existSSID(String ssid);
};

#endif
