-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Dim 03 Septembre 2017 à 11:22
-- Version du serveur :  5.7.14
-- Version de PHP :  5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `e_fly`
--

-- --------------------------------------------------------

--
-- Structure de la table `avion`
--

CREATE TABLE `avion` (
  `no_avion` varchar(50) NOT NULL,
  `type` text NOT NULL,
  `nb_place` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `avion`
--

INSERT INTO `avion` (`no_avion`, `type`, `nb_place`) VALUES
('air_u55', 'sddw', 45),
('AVINASH2', 'Air bus', 5),
('boeing_120', 'BOEING 120', 120),
('GOPAL002', 'BOEING', 5),
('test', 'Air bus', 5);

-- --------------------------------------------------------

--
-- Structure de la table `place`
--

CREATE TABLE `place` (
  `no_avion` varchar(50) NOT NULL,
  `no_place` varchar(50) NOT NULL,
  `occupation` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `place`
--

INSERT INTO `place` (`no_avion`, `no_place`, `occupation`) VALUES
('test', '', ''),
('air_u55', 'air_02', '02'),
('air_u55', 'air_22', 'C22'),
('air_u55', 'air_52', 'A25'),
('air_u55', 'air_P02', 'P02'),
('test', 'test_P2', 'P22'),
('test', 'test_P3', 'P30');

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

CREATE TABLE `reservation` (
  `no_reservation` varchar(50) NOT NULL,
  `no_vol` varchar(50) NOT NULL,
  `no_place` varchar(50) NOT NULL,
  `date_reservation` datetime NOT NULL,
  `nom_voyageur` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `vol`
--

CREATE TABLE `vol` (
  `no_vol` varchar(50) NOT NULL,
  `heure_depart` datetime NOT NULL,
  `heure_arrive` datetime NOT NULL,
  `ville_depart` text NOT NULL,
  `ville_arrive` text NOT NULL,
  `frais` float NOT NULL,
  `no_avion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `vol`
--

INSERT INTO `vol` (`no_vol`, `heure_depart`, `heure_arrive`, `ville_depart`, `ville_arrive`, `frais`, `no_avion`) VALUES
('MK_289', '2017-08-22 12:13:30', '2017-08-24 08:46:23', 'laba', 'ici', 5, 'air_u55'),
('NJ  45', '2121-12-12 00:12:01', '2121-12-13 05:56:04', 'Lyon', 'Milan', 5000, 'AVINASH2'),
('TP 602', '2018-02-13 19:56:05', '2018-02-13 21:12:05', 'maurice', 'tana', 25000200, 'boeing_120');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `avion`
--
ALTER TABLE `avion`
  ADD UNIQUE KEY `no_avion` (`no_avion`);

--
-- Index pour la table `place`
--
ALTER TABLE `place`
  ADD PRIMARY KEY (`no_place`),
  ADD UNIQUE KEY `no_place` (`no_place`);

--
-- Index pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`no_reservation`),
  ADD UNIQUE KEY `no_reservation` (`no_reservation`);

--
-- Index pour la table `vol`
--
ALTER TABLE `vol`
  ADD PRIMARY KEY (`no_vol`),
  ADD UNIQUE KEY `no_vol` (`no_vol`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
