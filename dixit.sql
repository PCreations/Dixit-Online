-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Jeu 26 Avril 2012 à 14:25
-- Version du serveur: 5.1.53
-- Version de PHP: 5.3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `dixit`
--

-- --------------------------------------------------------

--
-- Structure de la table `games`
--

CREATE TABLE IF NOT EXISTS `games` (
  `ga_id` int(11) NOT NULL AUTO_INCREMENT,
  `ga_name` varchar(255) NOT NULL,
  `ga_creation_date` datetime NOT NULL,
  `gt_id` int(11) NOT NULL,
  `us_id` int(11) NOT NULL,
  PRIMARY KEY (`ga_id`),
  KEY `gt_id` (`gt_id`),
  KEY `us_id` (`us_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `games`
--


--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `games_ibfk_2` FOREIGN KEY (`gt_id`) REFERENCES `game_types` (`gt_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `games_ibfk_3` FOREIGN KEY (`us_id`) REFERENCES `users` (`us_id`) ON DELETE CASCADE ON UPDATE CASCADE;
