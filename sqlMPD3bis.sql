-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Sam 26 Mai 2012 à 14:44
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
-- Structure de la table `boards`
--

CREATE TABLE IF NOT EXISTS `boards` (
  `tu_id` int(11) NOT NULL,
  `ca_id` int(11) NOT NULL,
  PRIMARY KEY (`tu_id`,`ca_id`),
  KEY `fk_boards2` (`ca_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `cards`
--

CREATE TABLE IF NOT EXISTS `cards` (
  `ca_id` int(11) NOT NULL AUTO_INCREMENT,
  `us_id` int(11) NOT NULL,
  `ca_name` char(255) DEFAULT NULL,
  `ca_image` char(255) DEFAULT NULL,
  PRIMARY KEY (`ca_id`),
  KEY `fk_to_create` (`us_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `cards_decks`
--

CREATE TABLE IF NOT EXISTS `cards_decks` (
  `ca_id` int(11) NOT NULL,
  `de_id` int(11) NOT NULL,
  PRIMARY KEY (`ca_id`,`de_id`),
  KEY `fk_cards_decks2` (`de_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `chats`
--

CREATE TABLE IF NOT EXISTS `chats` (
  `us_id` int(11) NOT NULL,
  `me_id` bigint(20) NOT NULL,
  `ga_id` int(11) NOT NULL,
  PRIMARY KEY (`us_id`,`me_id`,`ga_id`),
  KEY `fk_chats2` (`me_id`),
  KEY `fk_chats3` (`ga_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `decks`
--

CREATE TABLE IF NOT EXISTS `decks` (
  `de_id` int(11) NOT NULL AUTO_INCREMENT,
  `us_id` int(11) NOT NULL,
  `de_name` char(255) NOT NULL,
  `de_status` smallint(6) NOT NULL,
  PRIMARY KEY (`de_id`),
  KEY `fk_decks_users` (`us_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `earned_points`
--

CREATE TABLE IF NOT EXISTS `earned_points` (
  `us_id` int(11) NOT NULL,
  `tu_id` int(11) NOT NULL,
  PRIMARY KEY (`us_id`,`tu_id`),
  KEY `fk_earned_points2` (`tu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `games`
--

CREATE TABLE IF NOT EXISTS `games` (
  `ga_id` int(11) NOT NULL AUTO_INCREMENT,
  `de_id` int(11) NOT NULL,
  `us_id` int(11) NOT NULL,
  `ga_name` char(255) DEFAULT NULL,
  `ga_creation_date` datetime DEFAULT NULL,
  `ga_password` char(255) DEFAULT NULL,
  `ga_nb_players` int(11) DEFAULT NULL,
  PRIMARY KEY (`ga_id`),
  KEY `fk_create` (`us_id`),
  KEY `fk_decks_games` (`de_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `hands`
--

CREATE TABLE IF NOT EXISTS `hands` (
  `tu_played_id` int(11) NOT NULL,
  `tu_id` int(11) NOT NULL,
  `ca_id` int(11) NOT NULL,
  `us_id` int(11) NOT NULL,
  PRIMARY KEY (`tu_played_id`,`tu_id`,`ca_id`,`us_id`),
  KEY `fk_hands2` (`tu_id`),
  KEY `fk_hands3` (`ca_id`),
  KEY `fk_hands4` (`us_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `me_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `me_text` char(255) DEFAULT NULL,
  `me_date` datetime DEFAULT NULL,
  PRIMARY KEY (`me_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `pick`
--

CREATE TABLE IF NOT EXISTS `pick` (
  `ga_id` int(11) NOT NULL,
  `ca_id` int(11) NOT NULL,
  PRIMARY KEY (`ga_id`,`ca_id`),
  KEY `fk_pick2` (`ca_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `plays`
--

CREATE TABLE IF NOT EXISTS `plays` (
  `us_id` int(11) NOT NULL,
  `ga_id` int(11) NOT NULL,
  `pl_status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`us_id`,`ga_id`),
  KEY `fk_plays2` (`ga_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `turns`
--

CREATE TABLE IF NOT EXISTS `turns` (
  `tu_id` int(11) NOT NULL AUTO_INCREMENT,
  `us_id` int(11) NOT NULL,
  `ga_id` int(11) NOT NULL,
  `tu_date_start` datetime DEFAULT NULL,
  `tu_date_end` datetime DEFAULT NULL,
  `di_comments` char(150) NOT NULL,
  PRIMARY KEY (`tu_id`),
  KEY `fk_conduct` (`us_id`),
  KEY `fk_games_turns` (`ga_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `us_id` int(11) NOT NULL,
  `us_name` char(255) DEFAULT NULL,
  `us_lastname` char(255) DEFAULT NULL,
  `us_pseudo` char(255) NOT NULL,
  `us_password` char(255) NOT NULL,
  `us_avatar` char(255) DEFAULT NULL,
  `us_mail` char(255) NOT NULL,
  `us_birthdate` datetime DEFAULT NULL,
  `us_signin_date` datetime DEFAULT NULL,
  `us_last_connexion` datetime DEFAULT NULL,
  PRIMARY KEY (`us_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `users_cards_votes`
--

CREATE TABLE IF NOT EXISTS `users_cards_votes` (
  `us_id` int(11) NOT NULL,
  `ca_id` int(11) NOT NULL,
  PRIMARY KEY (`us_id`,`ca_id`),
  KEY `fk_users_cards_votes2` (`ca_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Vote pour les cartes ajouté par les joueurs';

-- --------------------------------------------------------

--
-- Structure de la table `users_friends`
--

CREATE TABLE IF NOT EXISTS `users_friends` (
  `us_id` int(11) NOT NULL,
  `us_friend_id` int(11) NOT NULL,
  `fr_date` datetime NOT NULL,
  `fr_status` int(11) NOT NULL,
  PRIMARY KEY (`us_id`,`us_friend_id`),
  KEY `fk_users_friends2` (`us_friend_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `votes`
--

CREATE TABLE IF NOT EXISTS `votes` (
  `us_id` int(11) NOT NULL,
  `ca_id` int(11) NOT NULL,
  `tu_id` int(11) NOT NULL,
  `vo_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`us_id`,`ca_id`,`tu_id`),
  KEY `fk_votes2` (`ca_id`),
  KEY `fk_votes3` (`tu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
