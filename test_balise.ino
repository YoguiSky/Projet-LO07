#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>

const char* ssid = "Box";
const char* password = "Louis090102";
const char* serverUrl = "http://192.168.1.130/logvalues.php";

void setup() {
  Serial.begin(115200);

  // Connecter le ESP32 au réseau WiFi
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connexion au WiFi...");
  }

  Serial.println("Connecté au WiFi");

  
}

void loop() {
  // Créer un objet JSON
  DynamicJsonDocument jsonDoc(256);
  jsonDoc["IdBalise"] = 1;
  jsonDoc["temperature"] = random(15, 20);
  jsonDoc["humidite"] = random(25, 30);

  // Convertir l'objet JSON en chaîne JSON
  String jsonData;
  serializeJson(jsonDoc, jsonData);
  jsonData = "jsonData=" +jsonData;
  Serial.println("Données JSON :");
  Serial.println(jsonData);

  // Envoyer la requête HTTP POST avec le paquet JSON
  HTTPClient http;
  http.begin(serverUrl);
  
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  //http.addHeader("jsonData", "application/json");
  //http.collectHeaders("jsonData", 1);

  int httpResponseCode = http.POST(jsonData);

  if (httpResponseCode > 0) {
    Serial.print("Code de réponse HTTP : ");
    Serial.println(httpResponseCode);
    String response = http.getString();
    Serial.println(response);
  } else {
    Serial.print("Erreur HTTP : ");
    Serial.println(httpResponseCode);
  }

  http.end();
  delay(5000);
}
