## Configurer le serveur

Créer un fichier de configuration dans le dossier conf (à créer) qui doit se situer au même niveau que le .jar

* database.conf 
    * url="url de la base de donnée"
    * user="user"
    * password = "mot de passe"

## Executer le serveur

```java -jar server.jar [-p <port>]```

Par défaut, lance le serveur sur le port 80 si l'option -p n'est pas spécifiée.

## Communiquer avec le serveur

Fonctionne avec des des requêtes **HTTP GET**.

A chaque fois inclure un header **Command** avec comme valeur la commande à éxecuter.

Le serveur répond avec un code de retour dans le **header Result** de la réponse HTTP.

| Requête | Valeur du header Command | Valeur du header Result cas BON | Valeur du header Result cas ERREUR |
| --------------- | --------------- | --------------- | ---------------- |
| Paiement | PAY:IDemetteur:Montant | PAIEMENT_ACCEPTED | PAIEMENT_REFUSED |

## Gestion de la sécurité

* A chaque fois qu'un client essaye de se connecter au serveur, ce dernier
vérifie si l'IP du client est connue.
    * Si l'IP est inconnue, le serveur répond par ```UNKNOWN_TPE``` dans le body de la réponse HTML.
    * Si elle est connue, le serveur récupère l'ID du compte associé au TPE dans la base de donnée. 