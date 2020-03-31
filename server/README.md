## Configurer le serveur

Créer un fichier de configuration dans le dossier conf (à créer) qui doit se situer au même niveau que le .jar

* api.conf 
    * url="url de l'API *(Par exemple http://localhost/Web/ptut/public/ en local)*"
    * username= "user" *(D'un compte de l'app web ayant le role : ROLE_API_USER)*
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
| Paiement | PAY:UIDcarte:Montant | PAIEMENT_ACCEPTED | PAIEMENT_REFUSED |

## Gestion de la sécurité

* A chaque fois qu'un client essaye de se connecter au serveur, ce dernier
vérifie si l'IP du client est connue.
    * Si l'IP est inconnue, le serveur répond par ```UNKNOWN_TPE``` dans le body de la réponse HTML.
    * Si elle est connue, le serveur récupère l'ID du compte associé au TPE dans la base de donnée. 
    
* Ce serveur n'a aucun lien direct avec la base de donnée.
    Toutes les requêtes avec cette dernière passe par l'API de l'application web.
    * Seul un utilisateur disposant d'un compte avec un rôle permettant de communiquer avec l'API 
        peut lancer ce serveur.
