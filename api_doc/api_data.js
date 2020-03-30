define({ "api": [
  {
    "type": "get",
    "url": "/deconnexion",
    "title": "Ferme la session.",
    "name": "deconnexion",
    "group": "Login",
    "version": "0.0.0",
    "filename": "C:/UwAmp/www/s3a_s13_blaise_chagras_kesseiri_mayer_thommet/web/src/Controller/APIController.php",
    "groupTitle": "Login"
  },
  {
    "type": "post",
    "url": "/",
    "title": "Ouvre une session avec un rôle qui a accès à l'API.",
    "name": "login",
    "group": "Login",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "_password",
            "description": "<p>Mot de passe du compte.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "_username",
            "description": "<p>Identifiant du compte du compte.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "C:/UwAmp/www/s3a_s13_blaise_chagras_kesseiri_mayer_thommet/web/src/Controller/APIController.php",
    "groupTitle": "Login"
  },
  {
    "type": "get",
    "url": "/api/checkIP/{ip}",
    "title": "Retourne le numéro de comtpe associé au TPE.",
    "name": "checkIP",
    "group": "Serveur_API",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "ip",
            "description": "<p>ip à vérifier.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "idAccount",
            "description": "<p>le comtpe associé à l'IP.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"idAccount\": \"d4ff7e629c3e666e09c7\",\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 404 not found": [
          {
            "group": "Error 404 not found",
            "type": "int",
            "optional": false,
            "field": "idAccount",
            "description": "<p>-1 si l'ip est associée à aucun compte.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "C:/UwAmp/www/s3a_s13_blaise_chagras_kesseiri_mayer_thommet/web/src/Controller/APIController.php",
    "groupTitle": "Serveur_API"
  },
  {
    "type": "get",
    "url": "api/pay/{idReceiver}/{cardUID}/{amount}",
    "title": "Fait un paiement.",
    "name": "pay",
    "group": "Serveur_API",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "idReceiver",
            "description": "<p>id du compte qui va recevoir le paiement.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "cardUID",
            "description": "<p>UID de la carte qi effectue le paiement.</p>"
          },
          {
            "group": "Parameter",
            "type": "double",
            "optional": false,
            "field": "amount",
            "description": "<p>montant du paiement.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>success si le paiement est effectué.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"status\": \"success\",\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 403": [
          {
            "group": "Error 403",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>error si le paiement ne peut pas être fait.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "C:/UwAmp/www/s3a_s13_blaise_chagras_kesseiri_mayer_thommet/web/src/Controller/APIController.php",
    "groupTitle": "Serveur_API"
  },
  {
    "type": "get",
    "url": "/api/soldeCompte/{accountId}",
    "title": "Retourne le solde du compte associé au numéro.",
    "name": "soldeCompte",
    "group": "Serveur_API",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "accountId",
            "description": "<p>id du compte dont l'on veut le solde.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "double",
            "optional": false,
            "field": "solde",
            "description": "<p>le solde du compte associé à idAccount.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"solde\": 78.2,\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 404 not found": [
          {
            "group": "Error 404 not found",
            "type": "int",
            "optional": false,
            "field": "solde",
            "description": "<p>-1 si idAccount est associé à aucun compte.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "C:/UwAmp/www/s3a_s13_blaise_chagras_kesseiri_mayer_thommet/web/src/Controller/APIController.php",
    "groupTitle": "Serveur_API"
  }
] });
