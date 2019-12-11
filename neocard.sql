SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `Utilisateur`;
CREATE TABLE `Utilisateur` (
  `idUtil` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dateNaiss` date NOT NULL,
  `mdp` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`idUtil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `Compte`;
CREATE TABLE `Compte` (
  `idCompte` int(11) NOT NULL AUTO_INCREMENT,
  `idUtil` int(11) NOT NULL,
  `libelle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`idCompte`),
  FOREIGN KEY (`idUtil`) REFERENCES `Utilisateur`(`idUtil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `Carte`;
CREATE TABLE `Carte` (
  `idCarte` int(11) NOT NULL AUTO_INCREMENT,
  `idCompte` int(11) NOT NULL,
  `code` int(4),
  PRIMARY KEY (`idCarte`),
  FOREIGN KEY (`idCompte`) REFERENCES `Compte`(`idCompte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `Transaction`;
CREATE TABLE `Transaction` (
  `numTransac` int(11) NOT NULL AUTO_INCREMENT,
  `typeTransac` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `libelle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `montant` int(11) NOT NULL,
  `emetteur` int(11),
  `recepteur` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`numTransac`),
  FOREIGN KEY (`emetteur`) REFERENCES `Compte`(`idCompte`),
  FOREIGN KEY (`recepteur`) REFERENCES `Compte`(`idCompte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `TPE`;
CREATE TABLE `TPE` (
  `numTPE` int(11) NOT NULL AUTO_INCREMENT,
  `idCompte` int(11) NOT NULL,
  PRIMARY KEY (`numTPE`),
  FOREIGN KEY (`idCompte`) REFERENCES `Compte`(`idCompte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;