s3a_s13_blaise_chagras_kesseiri_mayer_thommet
# Neocoard

Projet Tutoré @IUT-NC 2020

## Installation

Utilisez [composer](https://getcomposer.org/) pour installer NeoCard.

```bash
git clone git@bitbucket.org:depinfoens/s3a_s13_blaise_chagras_kesseiri_mayer_thommet.git
cd web
composer install
```

Il faut modifier le fichier de configuration pour la base de donnée nommé **.env** afin d'y ajouter votre propre base:
DATABASE_URL=mysql://root:root@127.0.0.1:3306/ptut?serverVersion=5.7 pour un localhost

## Utilisation

Lancez un serveur XAMP, importez le fichier de création de la BDD ([sql/neocard.sql](https://bitbucket.org/depinfoens/s3a_s13_blaise_chagras_kesseiri_mayer_thommet/src/master/neocard.sql))

## Disparités avec le sujet

- [x] *Nous utilisons Slim 3*
- [x] *Nous utilisons [FIG Cookies](https://github.com/dflydev/dflydev-fig-cookies) 1.0.2 pour gérer les cookies*
- [x] *Nous utilisons [PHPView](https://github.com/slimphp/PHP-View) 2.2 pour gérer les vues et les layouts*
- [x] *Nous utilisons [Flash](https://github.com/slimphp/Slim-Flash) pour gérer les messages Flash (erreurs,succès..)*

## Contributions
**CHAGRAS Flavien** - SI2 @[Flachag](https://bitbucket.org/%7B806fdb70-aa86-4e38-b980-658683b646d7%7D/)

**BLAISE Lucas** - S3A

**KESSEIRI Mohammed** - S3A

**MAYER Gauthier** - S3A

**THOMMET Sacha** - S3A