-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  lun. 27 jan. 2020 à 21:03
-- Version du serveur :  5.7.26
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
-- Base de données :  `projet_tut`
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
-- Structure de la table `terminal`
--

DROP TABLE IF EXISTS `terminal`;
CREATE TABLE IF NOT EXISTS `terminal` (
                                          `id` int(11) NOT NULL AUTO_INCREMENT,
                                          `idAccount` int(11) NOT NULL,
                                          PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `transaction`
--

DROP TABLE IF EXISTS `transaction`;
CREATE TABLE IF NOT EXISTS `transaction` (
                                             `id` int(11) NOT NULL AUTO_INCREMENT,
                                             `type` varchar(255) NOT NULL,
                                             `amount` float NOT NULL,
                                             `idIssuer` int(11) NOT NULL,
                                             `idReceiver` int(11) NOT NULL,
                                             `date` date NOT NULL,
                                             PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
