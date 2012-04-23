-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Lun 23 Avril 2012 à 21:43
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
  KEY `ca_id` (`ca_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `boards`
--


-- --------------------------------------------------------

--
-- Structure de la table `cards`
--

CREATE TABLE IF NOT EXISTS `cards` (
  `ca_id` int(11) NOT NULL AUTO_INCREMENT,
  `ca_name` char(255) CHARACTER SET latin1 DEFAULT NULL,
  `ca_image` char(255) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`ca_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `cards`
--

INSERT INTO `cards` (`ca_id`, `ca_name`, `ca_image`) VALUES
(1, 'femme violon', '1.png'),
(2, 'planètes', '2.png'),
(3, 'garçon plante', '3.png'),
(4, 'escalier', '4.png'),
(5, 'dragon', '5.png'),
(6, 'garçon bulles', '6.png'),
(7, 'garçon ciel escalier', '7.png'),
(8, 'garçon livres', '8.png');

-- --------------------------------------------------------

--
-- Structure de la table `card_status`
--

CREATE TABLE IF NOT EXISTS `card_status` (
  `ct_id` int(11) NOT NULL AUTO_INCREMENT,
  `ct_name` varchar(100) NOT NULL,
  PRIMARY KEY (`ct_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `card_status`
--

INSERT INTO `card_status` (`ct_id`, `ct_name`) VALUES
(1, 'En main'),
(2, 'Posée');

-- --------------------------------------------------------

--
-- Structure de la table `deck`
--

CREATE TABLE IF NOT EXISTS `deck` (
  `gt_id` int(11) NOT NULL,
  `ca_id` int(11) NOT NULL,
  PRIMARY KEY (`gt_id`,`ca_id`),
  KEY `ca_id` (`ca_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `deck`
--

INSERT INTO `deck` (`gt_id`, `ca_id`) VALUES
(2, 1),
(3, 1),
(2, 2),
(3, 2),
(2, 3),
(3, 3),
(2, 4),
(3, 4),
(2, 5),
(3, 5),
(2, 6),
(3, 6),
(2, 7),
(3, 7),
(2, 8),
(3, 8);

-- --------------------------------------------------------

--
-- Structure de la table `earned_points`
--

CREATE TABLE IF NOT EXISTS `earned_points` (
  `us_id` int(11) NOT NULL,
  `tu_id` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  PRIMARY KEY (`us_id`,`tu_id`),
  KEY `tu_id` (`tu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `earned_points`
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


-- --------------------------------------------------------

--
-- Structure de la table `game_types`
--

CREATE TABLE IF NOT EXISTS `game_types` (
  `gt_id` int(11) NOT NULL AUTO_INCREMENT,
  `gt_name` char(255) CHARACTER SET latin1 NOT NULL,
  `gt_nb_players` int(11) NOT NULL,
  PRIMARY KEY (`gt_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `game_types`
--

INSERT INTO `game_types` (`gt_id`, `gt_name`, `gt_nb_players`) VALUES
(2, 'partie à 8 cartes', 2),
(3, '3 joueurs', 3);

-- --------------------------------------------------------

--
-- Structure de la table `hands`
--

CREATE TABLE IF NOT EXISTS `hands` (
  `ca_id` int(11) NOT NULL,
  `us_id` int(11) NOT NULL,
  `ga_id` int(11) NOT NULL,
  `ct_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ca_id`,`us_id`,`ga_id`),
  KEY `us_id` (`us_id`),
  KEY `ct_id` (`ct_id`),
  KEY `ga_id` (`ga_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `hands`
--


-- --------------------------------------------------------

--
-- Structure de la table `pick`
--

CREATE TABLE IF NOT EXISTS `pick` (
  `ga_id` int(11) NOT NULL,
  `ca_id` int(11) NOT NULL,
  PRIMARY KEY (`ga_id`,`ca_id`),
  KEY `ca_id` (`ca_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pick`
--


-- --------------------------------------------------------

--
-- Structure de la table `plays`
--

CREATE TABLE IF NOT EXISTS `plays` (
  `us_id` int(11) NOT NULL,
  `ga_id` int(11) NOT NULL,
  `pl_position` int(11) NOT NULL,
  PRIMARY KEY (`us_id`,`ga_id`),
  KEY `ga_id` (`ga_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- Contenu de la table `plays`
--


-- --------------------------------------------------------

--
-- Structure de la table `turns`
--

CREATE TABLE IF NOT EXISTS `turns` (
  `tu_id` int(11) NOT NULL AUTO_INCREMENT,
  `ga_id` int(11) NOT NULL,
  `us_id` int(11) NOT NULL,
  `tu_date_start` datetime DEFAULT NULL,
  `tu_date_end` datetime DEFAULT NULL,
  `tu_comment` char(150) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`tu_id`),
  KEY `ga_id` (`ga_id`),
  KEY `us_id` (`us_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `turns`
--


-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `us_id` int(11) NOT NULL AUTO_INCREMENT,
  `us_name` char(255) CHARACTER SET latin1 DEFAULT NULL,
  `us_lastname` char(255) CHARACTER SET latin1 DEFAULT NULL,
  `us_pseudo` char(255) CHARACTER SET latin1 NOT NULL,
  `us_password` char(255) CHARACTER SET latin1 NOT NULL,
  `us_mail` char(255) CHARACTER SET latin1 NOT NULL,
  `us_birthdate` datetime DEFAULT NULL,
  `us_signin_date` datetime DEFAULT NULL,
  `us_last_connexion` datetime DEFAULT NULL,
  PRIMARY KEY (`us_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`us_id`, `us_name`, `us_lastname`, `us_pseudo`, `us_password`, `us_mail`, `us_birthdate`, `us_signin_date`, `us_last_connexion`) VALUES
(2, 'Pierre', 'Criulanscy', 'Deltod', 'd41e2668def681f4b1a7a9d21174fade4300d6ea', 'pcriulan@gmail.com', '1991-04-04 00:00:00', '2012-04-20 10:42:35', '2012-04-20 10:42:35'),
(3, '', '', 'Joueur2', '1f60877a7aa2de96284f1e0c7b331a8d24a2a50a', '', '1991-04-04 00:00:00', '2012-04-20 11:48:25', '2012-04-20 11:48:25'),
(4, '', '', 'Joueur 3', '1f60877a7aa2de96284f1e0c7b331a8d24a2a50a', '', '1991-04-04 00:00:00', '2012-04-23 22:47:58', '2012-04-23 22:47:58');

-- --------------------------------------------------------

--
-- Structure de la table `users_friends`
--

CREATE TABLE IF NOT EXISTS `users_friends` (
  `us_id` int(11) NOT NULL,
  `use_us_id` int(11) NOT NULL,
  `fr_date` datetime NOT NULL,
  `fr_status` int(11) NOT NULL,
  PRIMARY KEY (`us_id`,`use_us_id`),
  KEY `use_us_id` (`use_us_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `users_friends`
--


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
  KEY `ca_id` (`ca_id`),
  KEY `tu_id` (`tu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `votes`
--


--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `boards`
--
ALTER TABLE `boards`
  ADD CONSTRAINT `boards_ibfk_1` FOREIGN KEY (`tu_id`) REFERENCES `turns` (`tu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `boards_ibfk_2` FOREIGN KEY (`ca_id`) REFERENCES `cards` (`ca_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `deck`
--
ALTER TABLE `deck`
  ADD CONSTRAINT `deck_ibfk_1` FOREIGN KEY (`gt_id`) REFERENCES `game_types` (`gt_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `deck_ibfk_2` FOREIGN KEY (`ca_id`) REFERENCES `cards` (`ca_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `earned_points`
--
ALTER TABLE `earned_points`
  ADD CONSTRAINT `earned_points_ibfk_1` FOREIGN KEY (`us_id`) REFERENCES `users` (`us_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `earned_points_ibfk_2` FOREIGN KEY (`tu_id`) REFERENCES `turns` (`tu_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `games_ibfk_2` FOREIGN KEY (`gt_id`) REFERENCES `game_types` (`gt_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `games_ibfk_3` FOREIGN KEY (`us_id`) REFERENCES `users` (`us_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `hands`
--
ALTER TABLE `hands`
  ADD CONSTRAINT `hands_ibfk_5` FOREIGN KEY (`ga_id`) REFERENCES `games` (`ga_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hands_ibfk_1` FOREIGN KEY (`ca_id`) REFERENCES `cards` (`ca_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hands_ibfk_2` FOREIGN KEY (`us_id`) REFERENCES `users` (`us_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hands_ibfk_4` FOREIGN KEY (`ct_id`) REFERENCES `card_status` (`ct_id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `pick`
--
ALTER TABLE `pick`
  ADD CONSTRAINT `pick_ibfk_1` FOREIGN KEY (`ga_id`) REFERENCES `games` (`ga_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pick_ibfk_2` FOREIGN KEY (`ca_id`) REFERENCES `cards` (`ca_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `plays`
--
ALTER TABLE `plays`
  ADD CONSTRAINT `plays_ibfk_1` FOREIGN KEY (`us_id`) REFERENCES `users` (`us_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `plays_ibfk_2` FOREIGN KEY (`ga_id`) REFERENCES `games` (`ga_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `turns`
--
ALTER TABLE `turns`
  ADD CONSTRAINT `turns_ibfk_1` FOREIGN KEY (`ga_id`) REFERENCES `games` (`ga_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `turns_ibfk_2` FOREIGN KEY (`us_id`) REFERENCES `users` (`us_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `users_friends`
--
ALTER TABLE `users_friends`
  ADD CONSTRAINT `users_friends_ibfk_1` FOREIGN KEY (`us_id`) REFERENCES `users` (`us_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_friends_ibfk_2` FOREIGN KEY (`use_us_id`) REFERENCES `users` (`us_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `votes_ibfk_1` FOREIGN KEY (`us_id`) REFERENCES `users` (`us_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `votes_ibfk_2` FOREIGN KEY (`ca_id`) REFERENCES `cards` (`ca_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `votes_ibfk_3` FOREIGN KEY (`tu_id`) REFERENCES `turns` (`tu_id`) ON DELETE CASCADE ON UPDATE CASCADE;
