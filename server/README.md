## Configuration du serveur

Créer un fichier de configuration dans le dossier conf (à créer) qui doit se situer au même niveau que le .jar

* database.conf 
    * url="url de la base de donnée"
    * user="user"
    * password = "mot de passe"

Pour executer le jar : java -jar server.jar [-p <port>]
Si l'option -p n'est pas spécifiée, lance le serveur sur le port 80.