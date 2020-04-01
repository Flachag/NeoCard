-- phpMyAdmin SQL Dump
-- version 5.0.0-rc1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  mar. 31 mars 2020 à 16:46
-- Version du serveur :  5.7.29-0ubuntu0.18.04.1
-- Version de PHP :  7.2.24-0ubuntu0.18.04.3

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

CREATE TABLE `account` (
  `id` varchar(25) NOT NULL,
  `idUser` int(11) NOT NULL,
  `label` varchar(255) NOT NULL DEFAULT 'Compte Courant'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `account`
--

INSERT INTO `account` (`id`, `idUser`, `label`) VALUES
('1', 5, 'Compte Courant'),
('3', 7, 'Compte Courant'),
('4', 8, 'Compte Courant'),
('5', 9, 'Compte Courant'),
('6', 10, 'Compte Courant'),
('d4ff7f629f3e444e09c7', 15, 'Compte Courant'),
('e0a7d4755c71e32578e9', 16, 'Compte Courant'),
('04fa9c8ef8d1e45826c2', 19, 'Compte Courant');

-- --------------------------------------------------------

--
-- Structure de la table `card`
--

CREATE TABLE `card` (
  `id` int(11) NOT NULL,
  `uid` varchar(255) NOT NULL,
  `account_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `card`
--

INSERT INTO `card` (`id`, `uid`, `account_id`) VALUES
(1, '2E0A54C3', 1);

-- --------------------------------------------------------

--
-- Structure de la table `migration_versions`
--

CREATE TABLE `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
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

CREATE TABLE `terminal` (
  `ip` varchar(39) NOT NULL,
  `idAccount` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `terminal`
--

INSERT INTO `terminal` (`ip`, `idAccount`, `id`) VALUES
('192.168.0.4', 4, 1),
('192.168.0.3', 2, 2),
('192.168.0.26', 2, 3),
('192.168.1.24', 1, 4),
('192.168.1.23', 1, 5),
('192.168.0.15', 4, 6),
('192.168.1.39', 4, 8);

-- --------------------------------------------------------

--
-- Structure de la table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `hash` varchar(128) NOT NULL,
  `amount` float NOT NULL,
  `idIssuer` varchar(25) DEFAULT NULL,
  `idReceiver` varchar(25) DEFAULT NULL,
  `date` date NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `transaction`
--

INSERT INTO `transaction` (`id`, `hash`, `amount`, `idIssuer`, `idReceiver`, `date`, `label`, `type`) VALUES
(70, 'c21a36100cad5cd7dbf77aa4e3f62f80', 50, NULL, '1', '2020-03-28', 'Dépôt', 'Dépôt');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `roles` json NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `name`, `surname`, `password`, `username`, `email`, `banned`, `roles`) VALUES
(7, 'PERNOT', 'Anthony', '$2y$13$zl.YpoEhNZb2kpRG.11QWOtxU6n4PbMYULbjoHSnF5gEKfYQHaq16', 'totoprnt', 'anthony.pernot@hotmail.fr', 0, '[\"ROLE_USER\"]'),
(5, 'Chagras', 'Flavien', '$2y$13$r12wRtfEKdHv6iPTkrjmiOFhMhqoJK7o2yHl1K2R1H9lS2mZzIehu', 'Flachag', 'flavien.chagras@gmail.com', 0, '[\"ROLE_ADMIN\"]'),
(6, 'test', 'test', 'yolo', 'test', 'test', 0, '[\"ROLE_USER\"]'),
(8, 'BLAISE', 'Lucas', '$2y$13$gxCmRMOF/2LF7sRE6CW5Su.7RcsuU.alMBLHRH4dX0KTIkBPSZr92', 'blaise98u', 'lucas.blaise1@etu.univ-lorraine.fr', 0, '[\"ROLE_USER\"]'),
(9, 'test', 'test', '$2y$13$H.pfoo/4Ltt6kwBF5fXgQu9SEcQKxOajEp9UjlyaKYvy0HEmZDfJq', 'test', 'test@test.fr', 0, '[\"ROLE_USER\"]'),
(10, 'Test', 'test', '$2y$13$TUagbS6GS89KSVbQCKWeJOm3p4bSdQQ2TUV5TYMG.u7KSeJPeIpQa', 'test1', 'fzerflavien.chagras@gmail.com', 0, '[\"ROLE_USER\"]'),
(15, 'said', 'kesseiri', '$2y$13$VH9WugVKKOVuirzdPz7HO.3M.Rv6Kogior4G8/GSW6IrBlII/fysO', 'said57', 'test.said@gmail.com', 0, '[\"ROLE_USER\"]'),
(16, 'Mayer', 'Gauthier', '$2y$13$2Rton0Yo76/n1lhWECsTi.TmLt5BMG5If9FyNvqBhCgbz3PJiIGDW', 'Gauthier', 'mayer.gauthier@gmail.com', 0, '[\"ROLE_ADMIN\", \"ROLE_API_USER\"]'),
(17, 'TPE', 'TPE', '$2y$13$L.RqBjiwXFzlntLcQT1loOjDQe.LxhmGXWb8dpR3o0AFI59IVh9ma', 'serveur', 'admin@admin.com', 0, '[\"ROLE_API_USER\"]');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `card`
--
ALTER TABLE `card`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migration_versions`
--
ALTER TABLE `migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `terminal`
--
ALTER TABLE `terminal`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `card`
--
ALTER TABLE `card`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `terminal`
--
ALTER TABLE `terminal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

