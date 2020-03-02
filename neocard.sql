-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 185.212.225.190:3306
-- Généré le :  Dim 02 fév. 2020 à 11:38
-- Version du serveur :  5.7.29-0ubuntu0.18.04.1
-- Version de PHP :  7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `flavien`
--

-- --------------------------------------------------------

--
-- Structure de la table `account`
--

DROP TABLE IF EXISTS `account`;
CREATE TABLE IF NOT EXISTS `account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idUser` int(11) NOT NULL,
  `label` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `account`
--

INSERT INTO `account` (`id`, `idUser`, `label`) VALUES
(1, 5, 'Compte Courant'),
(2, 6, 'Compte Courant'),
(3, 7, 'Compte Courant');

-- --------------------------------------------------------

--
-- Structure de la table `card`
--

DROP TABLE IF EXISTS `card`;
CREATE TABLE IF NOT EXISTS `card` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idAccount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `migration_versions`
--

DROP TABLE IF EXISTS `migration_versions`;
CREATE TABLE IF NOT EXISTS `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migration_versions`
--

INSERT INTO `migration_versions` (`version`, `executed_at`) VALUES
('20191216124849', '2019-12-16 12:53:56'),
('20191216125452', '2019-12-16 12:55:37'),
('20191216131636', '2019-12-16 13:16:50'),
('20191217174015', '2019-12-17 17:41:18'),
('20191217191751', '2019-12-17 19:18:08');

-- --------------------------------------------------------

--
-- Structure de la table `terminal`
--

DROP TABLE IF EXISTS `terminal`;
CREATE TABLE IF NOT EXISTS `terminal` (
  `ip` varchar(39) NOT NULL,
  `idAccount` int(11) NOT NULL,
  PRIMARY KEY (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `terminal`
--

INSERT INTO `terminal` (`ip`, `idAccount`) VALUES
('10.10.191.190', 1);

-- --------------------------------------------------------

--
-- Structure de la table `transaction`
--

DROP TABLE IF EXISTS `transaction`;
CREATE TABLE IF NOT EXISTS `transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `amount` float NOT NULL,
  `idIssuer` int(11) DEFAULT NULL,
  `idReceiver` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `transaction`
--

INSERT INTO `transaction` (`id`, `type`, `amount`, `idIssuer`, `idReceiver`, `date`, `label`) VALUES
(1, 'Virement', 250, NULL, 1, '2019-12-31', 'Initialisation du Compte'),
(12, 'Virement', 10, 1, 2, '2020-01-29', 'Test'),
(3, 'Virement', 1, 2, 1, '2020-01-28', 'kdo'),
(4, 'Virement', 1, 2, 1, '2020-01-28', 'ks'),
(5, 'Virement', 1, 2, 1, '2020-01-28', 'ks'),
(6, 'Virement', 50, 1, 2, '2020-01-24', 'ks'),
(7, 'Virement', 1, 2, 1, '2020-01-28', 'ks'),
(8, 'Virement', 1000, NULL, 3, '2020-01-28', 'Initialisation du Compte'),
(9, 'Virement', 50, 1, 3, '2020-01-28', 'Kdo Pernot'),
(10, 'Virement', 25, 3, 1, '2020-01-28', 'kdo flav'),
(14, 'Virement', 0.25, 1, 3, '2020-01-31', 'test');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `birthday` date NOT NULL,
  `password` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `name`, `surname`, `birthday`, `password`, `username`, `email`) VALUES
(7, 'PERNOT', 'Anthony', '2000-03-28', '$2y$13$zl.YpoEhNZb2kpRG.11QWOtxU6n4PbMYULbjoHSnF5gEKfYQHaq16', 'totoprnt', 'anthony.pernot@hotmail.fr'),
(5, 'Chagras', 'Flavien', '1999-04-23', '$2y$13$9jU6kiCYFCD4zs5aNznIq.dz0ptmIKAMC4TJorGvtzrCY4K6eaGdW', 'Flachag', 'flavien.chagras@gmail.com'),
(6, 'sayer', 'jul', '2000-10-21', '$2y$13$yV6VG1MKnWh3WQB98IbwnOFosi.Y3Fn.XckB12LhJyE74GD4eDhR2', 'wilders', 'jean-bonaubo@singejj.com');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
