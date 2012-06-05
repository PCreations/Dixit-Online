-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Mar 05 Juin 2012 à 18:09
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

DROP TABLE IF EXISTS `boards`;
CREATE TABLE IF NOT EXISTS `boards` (
  `tu_id` int(11) NOT NULL,
  `ca_id` int(11) NOT NULL,
  PRIMARY KEY (`tu_id`,`ca_id`),
  KEY `ca_id` (`ca_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `boards`
--


-- --------------------------------------------------------

--
-- Structure de la table `cards`
--

DROP TABLE IF EXISTS `cards`;
CREATE TABLE IF NOT EXISTS `cards` (
  `ca_id` int(11) NOT NULL AUTO_INCREMENT,
  `us_id` int(11) NOT NULL,
  `ca_name` varchar(255) DEFAULT NULL,
  `ca_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ca_id`),
  KEY `us_id` (`us_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=53 ;

--
-- Contenu de la table `cards`
--

INSERT INTO `cards` (`ca_id`, `us_id`, `ca_name`, `ca_image`) VALUES
(1, 1, '1', 'carte_1.png'),
(2, 1, '2', 'carte_2.png'),
(3, 1, '3', 'carte_3.png'),
(4, 1, '4', 'carte_4.png'),
(5, 1, '5', 'carte_5.png'),
(6, 1, '6', 'carte_6.png'),
(7, 1, '7', 'carte_7.png'),
(8, 1, '8', 'carte_8.png'),
(9, 1, '9', 'carte_9.png'),
(10, 1, '10', 'carte_10.png'),
(11, 1, '11', 'carte_11.png'),
(12, 1, '12', 'carte_12.png'),
(13, 1, '13', 'carte_13.png'),
(14, 1, '14', 'carte_14.png'),
(15, 1, '15', 'carte_15.png'),
(16, 1, '16', 'carte_16.png'),
(17, 1, '17', 'carte_17.png'),
(18, 1, '18', 'carte_18.png'),
(19, 1, '19', 'carte_19.png'),
(20, 1, '20', 'carte_20.png'),
(21, 1, '21', 'carte_21.png'),
(22, 1, '22', 'carte_22.png'),
(23, 1, '23', 'carte_23.png'),
(24, 1, '24', 'carte_24.png'),
(25, 1, '25', 'carte_25.png'),
(26, 1, '26', 'carte_26.png'),
(27, 1, '27', 'carte_27.png'),
(28, 1, '28', 'carte_28.png'),
(29, 1, '29', 'carte_29.png'),
(30, 1, '30', 'carte_30.png'),
(31, 1, '31', 'carte_31.png'),
(32, 1, '32', 'carte_32.png'),
(33, 1, '33', 'carte_33.png'),
(34, 1, '34', 'carte_34.png'),
(35, 1, '35', 'carte_35.png'),
(36, 1, '36', 'carte_36.png'),
(37, 1, '37', 'carte_37.png'),
(38, 1, '38', 'carte_38.png'),
(39, 1, '39', 'carte_39.png'),
(40, 1, '40', 'carte_40.png'),
(41, 1, '41', 'carte_41.png'),
(42, 1, '42', 'carte_42.png'),
(43, 1, '43', 'carte_43.png'),
(44, 1, '44', 'carte_44.png'),
(45, 1, '45', 'carte_45.png'),
(46, 1, '46', 'carte_46.png'),
(47, 1, '47', 'carte_47.png'),
(48, 1, '48', 'carte_48.png'),
(49, 1, '49', 'carte_49.png'),
(50, 1, '50', 'carte_50.png'),
(51, 1, '51', 'carte_51.png'),
(52, 1, '52', 'carte_52.png');

-- --------------------------------------------------------

--
-- Structure de la table `cards_decks`
--

DROP TABLE IF EXISTS `cards_decks`;
CREATE TABLE IF NOT EXISTS `cards_decks` (
  `ca_id` int(11) NOT NULL,
  `de_id` int(11) NOT NULL,
  PRIMARY KEY (`ca_id`,`de_id`),
  KEY `de_id` (`de_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `cards_decks`
--

INSERT INTO `cards_decks` (`ca_id`, `de_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 1);

-- --------------------------------------------------------

--
-- Structure de la table `chats`
--

DROP TABLE IF EXISTS `chats`;
CREATE TABLE IF NOT EXISTS `chats` (
  `us_id` int(11) NOT NULL,
  `ga_id` int(11) NOT NULL,
  `ch_text` varchar(255) NOT NULL,
  `ch_date` datetime NOT NULL,
  KEY `us_id` (`us_id`),
  KEY `ga_id` (`ga_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `chats`
--


-- --------------------------------------------------------

--
-- Structure de la table `decks`
--

DROP TABLE IF EXISTS `decks`;
CREATE TABLE IF NOT EXISTS `decks` (
  `de_id` int(11) NOT NULL AUTO_INCREMENT,
  `us_id` int(11) NOT NULL,
  `de_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `de_status` smallint(6) NOT NULL,
  PRIMARY KEY (`de_id`),
  KEY `us_id` (`us_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `decks`
--

INSERT INTO `decks` (`de_id`, `us_id`, `de_name`, `de_status`) VALUES
(1, 1, 'Défaut', 1);

-- --------------------------------------------------------

--
-- Structure de la table `earned_points`
--

DROP TABLE IF EXISTS `earned_points`;
CREATE TABLE IF NOT EXISTS `earned_points` (
  `us_id` int(11) NOT NULL,
  `tu_id` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  PRIMARY KEY (`us_id`,`tu_id`),
  KEY `tu_id` (`tu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `earned_points`
--


-- --------------------------------------------------------

--
-- Structure de la table `errors`
--

DROP TABLE IF EXISTS `errors`;
CREATE TABLE IF NOT EXISTS `errors` (
  `er_id` int(11) NOT NULL AUTO_INCREMENT,
  `er_msg` varchar(255) NOT NULL,
  `er_date` datetime NOT NULL,
  PRIMARY KEY (`er_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `errors`
--


-- --------------------------------------------------------

--
-- Structure de la table `games`
--

DROP TABLE IF EXISTS `games`;
CREATE TABLE IF NOT EXISTS `games` (
  `ga_id` int(11) NOT NULL AUTO_INCREMENT,
  `de_id` int(11) NOT NULL,
  `us_id` int(11) NOT NULL,
  `ga_name` varchar(255) DEFAULT NULL,
  `ga_creation_date` datetime DEFAULT NULL,
  `ga_password` varchar(255) DEFAULT NULL,
  `ga_nb_players` int(11) NOT NULL,
  `ga_points_limit` int(11) NOT NULL,
  PRIMARY KEY (`ga_id`),
  KEY `de_id` (`de_id`),
  KEY `us_id` (`us_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `games`
--


-- --------------------------------------------------------

--
-- Structure de la table `hands`
--

DROP TABLE IF EXISTS `hands`;
CREATE TABLE IF NOT EXISTS `hands` (
  `ca_id` int(11) NOT NULL,
  `us_id` int(11) NOT NULL,
  `tu_id` int(11) NOT NULL,
  `tu_played_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`ca_id`,`us_id`,`tu_id`),
  KEY `us_id` (`us_id`),
  KEY `tu_id` (`tu_id`),
  KEY `tu_played_id` (`tu_played_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `hands`
--


-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `me_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `me_text` varchar(255) DEFAULT NULL,
  `me_date` datetime DEFAULT NULL,
  PRIMARY KEY (`me_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `messages`
--


-- --------------------------------------------------------

--
-- Structure de la table `pick`
--

DROP TABLE IF EXISTS `pick`;
CREATE TABLE IF NOT EXISTS `pick` (
  `ga_id` int(11) NOT NULL,
  `ca_id` int(11) NOT NULL,
  `pi_order` int(11) NOT NULL,
  PRIMARY KEY (`ga_id`,`ca_id`),
  KEY `ca_id` (`ca_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `pick`
--


-- --------------------------------------------------------

--
-- Structure de la table `plays`
--

DROP TABLE IF EXISTS `plays`;
CREATE TABLE IF NOT EXISTS `plays` (
  `us_id` int(11) NOT NULL,
  `ga_id` int(11) NOT NULL,
  `pl_position` int(11) NOT NULL,
  `pl_status` enum('Attente','Prêt','Inactif') CHARACTER SET utf8 NOT NULL DEFAULT 'Attente',
  PRIMARY KEY (`us_id`,`ga_id`),
  KEY `ga_id` (`ga_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `plays`
--


-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `total_players_in_game`
--
DROP VIEW IF EXISTS `total_players_in_game`;
CREATE TABLE IF NOT EXISTS `total_players_in_game` (
`ga_id` int(11)
,`nbTotalPlayer` bigint(21)
);
-- --------------------------------------------------------

--
-- Structure de la table `turns`
--

DROP TABLE IF EXISTS `turns`;
CREATE TABLE IF NOT EXISTS `turns` (
  `tu_id` int(11) NOT NULL AUTO_INCREMENT,
  `ga_id` int(11) NOT NULL,
  `us_id` int(11) NOT NULL,
  `tu_date_start` datetime DEFAULT NULL,
  `tu_date_end` datetime DEFAULT NULL,
  `tu_comment` varchar(150) NOT NULL,
  PRIMARY KEY (`tu_id`),
  KEY `ga_id` (`ga_id`),
  KEY `us_id` (`us_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

--
-- Contenu de la table `turns`
--


--
-- Déclencheurs `turns`
--
DROP TRIGGER IF EXISTS `before_inset_turns`;
DELIMITER //
CREATE TRIGGER `before_inset_turns` BEFORE INSERT ON `turns`
 FOR EACH ROW BEGIN
	DECLARE oldTurnComment VARCHAR(255);
	SELECT tu_comment INTO oldTurnComment
	FROM turns
	WHERE ga_id = NEW.ga_id
	ORDER BY tu_id DESC
	LIMIT 1;
	IF oldTurnComment = "" THEN
		INSERT INTO errors(er_msg, er_date) VALUES('ERR_TURN', NOW());
	END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `us_id` int(11) NOT NULL AUTO_INCREMENT,
  `us_name` varchar(255) DEFAULT NULL,
  `us_lastname` varchar(255) DEFAULT NULL,
  `us_pseudo` varchar(255) NOT NULL,
  `us_password` varchar(255) NOT NULL,
  `us_avatar` varchar(255) DEFAULT NULL,
  `us_mail` varchar(255) NOT NULL,
  `us_birthdate` date DEFAULT NULL,
  `us_signin_date` datetime DEFAULT NULL,
  `us_last_connexion` datetime DEFAULT NULL,
  PRIMARY KEY (`us_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`us_id`, `us_name`, `us_lastname`, `us_pseudo`, `us_password`, `us_avatar`, `us_mail`, `us_birthdate`, `us_signin_date`, `us_last_connexion`) VALUES
(1, 'Joueur', '1', 'Joueur1', '1f60877a7aa2de96284f1e0c7b331a8d24a2a50a', NULL, 'test@test.com', '0000-00-00', '2012-05-26 21:59:44', '2012-05-26 21:59:44'),
(2, 'Joueur', '2', 'Joueur2', '1f60877a7aa2de96284f1e0c7b331a8d24a2a50a', NULL, 'j2@gmail.com', '1991-04-04', '2012-05-26 22:10:00', '2012-05-26 22:10:00'),
(3, 'Joueur', '3', 'Joueur3', '1f60877a7aa2de96284f1e0c7b331a8d24a2a50a', NULL, '', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `users_cards_votes`
--

DROP TABLE IF EXISTS `users_cards_votes`;
CREATE TABLE IF NOT EXISTS `users_cards_votes` (
  `us_id` int(11) NOT NULL,
  `ca_id` int(11) NOT NULL,
  PRIMARY KEY (`us_id`,`ca_id`),
  KEY `ca_id` (`ca_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Vote pour les cartes ajouté par les joueurs';

--
-- Contenu de la table `users_cards_votes`
--


-- --------------------------------------------------------

--
-- Structure de la table `users_friends`
--

DROP TABLE IF EXISTS `users_friends`;
CREATE TABLE IF NOT EXISTS `users_friends` (
  `us_id` int(11) NOT NULL,
  `us_friend_id` int(11) NOT NULL,
  `uf_date` datetime NOT NULL,
  `uf_status` int(11) NOT NULL,
  PRIMARY KEY (`us_id`,`us_friend_id`),
  KEY `us_friend_id` (`us_friend_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `users_friends`
--


-- --------------------------------------------------------

--
-- Structure de la table `votes`
--

DROP TABLE IF EXISTS `votes`;
CREATE TABLE IF NOT EXISTS `votes` (
  `us_id` int(11) NOT NULL,
  `ca_id` int(11) NOT NULL,
  `tu_id` int(11) NOT NULL,
  `vo_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`us_id`,`ca_id`,`tu_id`),
  KEY `ca_id` (`ca_id`),
  KEY `tu_id` (`tu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `votes`
--


-- --------------------------------------------------------

--
-- Structure de la vue `total_players_in_game`
--
DROP TABLE IF EXISTS `total_players_in_game`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `total_players_in_game` AS select `ga`.`ga_id` AS `ga_id`,(select count(`plays`.`ga_id`) from `plays` where (`plays`.`ga_id` = `ga`.`ga_id`)) AS `nbTotalPlayer` from `games` `ga`;

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
-- Contraintes pour la table `cards`
--
ALTER TABLE `cards`
  ADD CONSTRAINT `cards_ibfk_2` FOREIGN KEY (`us_id`) REFERENCES `users` (`us_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `cards_decks`
--
ALTER TABLE `cards_decks`
  ADD CONSTRAINT `cards_decks_ibfk_1` FOREIGN KEY (`ca_id`) REFERENCES `cards` (`ca_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cards_decks_ibfk_2` FOREIGN KEY (`de_id`) REFERENCES `decks` (`de_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chats_ibfk_1` FOREIGN KEY (`us_id`) REFERENCES `users` (`us_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chats_ibfk_2` FOREIGN KEY (`ga_id`) REFERENCES `games` (`ga_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `decks`
--
ALTER TABLE `decks`
  ADD CONSTRAINT `decks_ibfk_1` FOREIGN KEY (`us_id`) REFERENCES `users` (`us_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `games_ibfk_1` FOREIGN KEY (`de_id`) REFERENCES `decks` (`de_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `games_ibfk_2` FOREIGN KEY (`us_id`) REFERENCES `users` (`us_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `hands`
--
ALTER TABLE `hands`
  ADD CONSTRAINT `hands_ibfk_3` FOREIGN KEY (`ca_id`) REFERENCES `cards` (`ca_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hands_ibfk_4` FOREIGN KEY (`us_id`) REFERENCES `users` (`us_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hands_ibfk_5` FOREIGN KEY (`tu_id`) REFERENCES `turns` (`tu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hands_ibfk_6` FOREIGN KEY (`tu_played_id`) REFERENCES `turns` (`tu_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `turns_ibfk_3` FOREIGN KEY (`ga_id`) REFERENCES `games` (`ga_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `turns_ibfk_4` FOREIGN KEY (`us_id`) REFERENCES `users` (`us_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `users_cards_votes`
--
ALTER TABLE `users_cards_votes`
  ADD CONSTRAINT `users_cards_votes_ibfk_1` FOREIGN KEY (`us_id`) REFERENCES `users` (`us_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_cards_votes_ibfk_2` FOREIGN KEY (`ca_id`) REFERENCES `cards` (`ca_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `users_friends`
--
ALTER TABLE `users_friends`
  ADD CONSTRAINT `users_friends_ibfk_1` FOREIGN KEY (`us_id`) REFERENCES `users` (`us_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_friends_ibfk_2` FOREIGN KEY (`us_friend_id`) REFERENCES `users` (`us_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `votes_ibfk_1` FOREIGN KEY (`us_id`) REFERENCES `users` (`us_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `votes_ibfk_2` FOREIGN KEY (`ca_id`) REFERENCES `cards` (`ca_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `votes_ibfk_3` FOREIGN KEY (`tu_id`) REFERENCES `turns` (`tu_id`) ON DELETE CASCADE ON UPDATE CASCADE;
