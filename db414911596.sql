-- phpMyAdmin SQL Dump
-- version 2.6.4-pl3
-- http://www.phpmyadmin.net
-- 
-- Serveur: db414911596.db.1and1.com
-- Généré le : Samedi 26 Mai 2012 à 10:32
-- Version du serveur: 5.0.91
-- Version de PHP: 5.3.3-7+squeeze9
-- 
-- Base de données: `db414911596`
-- 

-- --------------------------------------------------------

-- 
-- Structure de la table `boards`
-- 

CREATE TABLE `boards` (
  `tu_id` int(11) NOT NULL,
  `ca_id` int(11) NOT NULL,
  PRIMARY KEY  (`tu_id`,`ca_id`),
  KEY `ca_id` (`ca_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Contenu de la table `boards`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `cards`
-- 

CREATE TABLE `cards` (
  `ca_id` int(11) NOT NULL auto_increment,
  `ca_name` char(255) character set latin1 default NULL,
  `ca_image` char(255) character set latin1 default NULL,
  PRIMARY KEY  (`ca_id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8 AUTO_INCREMENT=53 ;

-- 
-- Contenu de la table `cards`
-- 

INSERT INTO `cards` VALUES (1, '1', 'carte_1.png');
INSERT INTO `cards` VALUES (2, '2', 'carte_2.png');
INSERT INTO `cards` VALUES (3, '3', 'carte_3.png');
INSERT INTO `cards` VALUES (4, '4', 'carte_4.png');
INSERT INTO `cards` VALUES (5, '5', 'carte_5.png');
INSERT INTO `cards` VALUES (6, '6', 'carte_6.png');
INSERT INTO `cards` VALUES (7, '7', 'carte_7.png');
INSERT INTO `cards` VALUES (8, '8', 'carte_8.png');
INSERT INTO `cards` VALUES (9, '9', 'carte_9.png');
INSERT INTO `cards` VALUES (10, '10', 'carte_10.png');
INSERT INTO `cards` VALUES (11, '11', 'carte_11.png');
INSERT INTO `cards` VALUES (12, '12', 'carte_12.png');
INSERT INTO `cards` VALUES (13, '13', 'carte_13.png');
INSERT INTO `cards` VALUES (14, '14', 'carte_14.png');
INSERT INTO `cards` VALUES (15, '15', 'carte_15.png');
INSERT INTO `cards` VALUES (16, '16', 'carte_16.png');
INSERT INTO `cards` VALUES (17, '17', 'carte_17.png');
INSERT INTO `cards` VALUES (18, '18', 'carte_18.png');
INSERT INTO `cards` VALUES (19, '19', 'carte_19.png');
INSERT INTO `cards` VALUES (20, '20', 'carte_20.png');
INSERT INTO `cards` VALUES (21, '21', 'carte_21.png');
INSERT INTO `cards` VALUES (22, '22', 'carte_22.png');
INSERT INTO `cards` VALUES (23, '23', 'carte_23.png');
INSERT INTO `cards` VALUES (24, '24', 'carte_24.png');
INSERT INTO `cards` VALUES (25, '25', 'carte_25.png');
INSERT INTO `cards` VALUES (26, '26', 'carte_26.png');
INSERT INTO `cards` VALUES (27, '27', 'carte_27.png');
INSERT INTO `cards` VALUES (28, '28', 'carte_28.png');
INSERT INTO `cards` VALUES (29, '29', 'carte_29.png');
INSERT INTO `cards` VALUES (30, '30', 'carte_30.png');
INSERT INTO `cards` VALUES (31, '31', 'carte_31.png');
INSERT INTO `cards` VALUES (32, '32', 'carte_32.png');
INSERT INTO `cards` VALUES (33, '33', 'carte_33.png');
INSERT INTO `cards` VALUES (34, '34', 'carte_34.png');
INSERT INTO `cards` VALUES (35, '35', 'carte_35.png');
INSERT INTO `cards` VALUES (36, '36', 'carte_36.png');
INSERT INTO `cards` VALUES (37, '37', 'carte_37.png');
INSERT INTO `cards` VALUES (38, '38', 'carte_38.png');
INSERT INTO `cards` VALUES (39, '39', 'carte_39.png');
INSERT INTO `cards` VALUES (40, '40', 'carte_40.png');
INSERT INTO `cards` VALUES (41, '41', 'carte_41.png');
INSERT INTO `cards` VALUES (42, '42', 'carte_42.png');
INSERT INTO `cards` VALUES (43, '43', 'carte_43.png');
INSERT INTO `cards` VALUES (44, '44', 'carte_44.png');
INSERT INTO `cards` VALUES (45, '45', 'carte_45.png');
INSERT INTO `cards` VALUES (46, '46', 'carte_46.png');
INSERT INTO `cards` VALUES (47, '47', 'carte_47.png');
INSERT INTO `cards` VALUES (48, '48', 'carte_48.png');
INSERT INTO `cards` VALUES (49, '49', 'carte_49.png');
INSERT INTO `cards` VALUES (50, '50', 'carte_50.png');
INSERT INTO `cards` VALUES (51, '51', 'carte_51.png');
INSERT INTO `cards` VALUES (52, '52', 'carte_52.png');

-- --------------------------------------------------------

-- 
-- Structure de la table `deck`
-- 

CREATE TABLE `deck` (
  `gt_id` int(11) NOT NULL,
  `ca_id` int(11) NOT NULL,
  PRIMARY KEY  (`gt_id`,`ca_id`),
  KEY `ca_id` (`ca_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Contenu de la table `deck`
-- 

INSERT INTO `deck` VALUES (4, 1);
INSERT INTO `deck` VALUES (4, 2);
INSERT INTO `deck` VALUES (4, 3);
INSERT INTO `deck` VALUES (4, 4);
INSERT INTO `deck` VALUES (4, 5);
INSERT INTO `deck` VALUES (4, 6);
INSERT INTO `deck` VALUES (4, 7);
INSERT INTO `deck` VALUES (4, 8);
INSERT INTO `deck` VALUES (4, 9);
INSERT INTO `deck` VALUES (4, 10);
INSERT INTO `deck` VALUES (4, 11);
INSERT INTO `deck` VALUES (4, 12);
INSERT INTO `deck` VALUES (4, 13);
INSERT INTO `deck` VALUES (4, 14);
INSERT INTO `deck` VALUES (4, 15);
INSERT INTO `deck` VALUES (4, 16);
INSERT INTO `deck` VALUES (4, 17);
INSERT INTO `deck` VALUES (4, 18);
INSERT INTO `deck` VALUES (4, 19);
INSERT INTO `deck` VALUES (4, 20);
INSERT INTO `deck` VALUES (4, 21);
INSERT INTO `deck` VALUES (4, 22);
INSERT INTO `deck` VALUES (4, 23);
INSERT INTO `deck` VALUES (4, 24);
INSERT INTO `deck` VALUES (4, 25);
INSERT INTO `deck` VALUES (4, 26);
INSERT INTO `deck` VALUES (4, 27);
INSERT INTO `deck` VALUES (4, 28);
INSERT INTO `deck` VALUES (4, 29);
INSERT INTO `deck` VALUES (4, 30);
INSERT INTO `deck` VALUES (4, 31);
INSERT INTO `deck` VALUES (4, 32);
INSERT INTO `deck` VALUES (4, 33);
INSERT INTO `deck` VALUES (4, 34);
INSERT INTO `deck` VALUES (4, 35);
INSERT INTO `deck` VALUES (4, 36);
INSERT INTO `deck` VALUES (4, 37);
INSERT INTO `deck` VALUES (4, 38);
INSERT INTO `deck` VALUES (4, 39);
INSERT INTO `deck` VALUES (4, 40);
INSERT INTO `deck` VALUES (4, 41);
INSERT INTO `deck` VALUES (4, 42);
INSERT INTO `deck` VALUES (4, 43);
INSERT INTO `deck` VALUES (4, 44);
INSERT INTO `deck` VALUES (4, 45);
INSERT INTO `deck` VALUES (4, 46);
INSERT INTO `deck` VALUES (4, 47);
INSERT INTO `deck` VALUES (4, 48);
INSERT INTO `deck` VALUES (4, 49);
INSERT INTO `deck` VALUES (4, 50);
INSERT INTO `deck` VALUES (4, 51);
INSERT INTO `deck` VALUES (4, 52);

-- --------------------------------------------------------

-- 
-- Structure de la table `earned_points`
-- 

CREATE TABLE `earned_points` (
  `us_id` int(11) NOT NULL,
  `tu_id` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  PRIMARY KEY  (`us_id`,`tu_id`),
  KEY `tu_id` (`tu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Contenu de la table `earned_points`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `game_types`
-- 

CREATE TABLE `game_types` (
  `gt_id` int(11) NOT NULL auto_increment,
  `gt_name` char(255) character set latin1 NOT NULL,
  `gt_nb_players` int(11) NOT NULL,
  `gt_points_limit` int(11) NOT NULL,
  PRIMARY KEY  (`gt_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- 
-- Contenu de la table `game_types`
-- 

INSERT INTO `game_types` VALUES (2, 'partie à 8 cartes', 2, 10);
INSERT INTO `game_types` VALUES (3, '3 joueurs', 3, 5);
INSERT INTO `game_types` VALUES (4, 'Partie avec 52 cartes', 3, 10);

-- --------------------------------------------------------

-- 
-- Structure de la table `games`
-- 

CREATE TABLE `games` (
  `ga_id` int(11) NOT NULL auto_increment,
  `ga_name` varchar(255) NOT NULL,
  `ga_creation_date` datetime NOT NULL,
  `gt_id` int(11) NOT NULL,
  `us_id` int(11) NOT NULL,
  PRIMARY KEY  (`ga_id`),
  KEY `gt_id` (`gt_id`),
  KEY `us_id` (`us_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `games`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `hands`
-- 

CREATE TABLE `hands` (
  `ca_id` int(11) NOT NULL,
  `us_id` int(11) NOT NULL,
  `tu_id` int(11) NOT NULL,
  `tu_played_id` int(11) default NULL,
  PRIMARY KEY  (`ca_id`,`us_id`,`tu_id`),
  KEY `us_id` (`us_id`),
  KEY `tu_id` (`tu_id`),
  KEY `tu_played_id` (`tu_played_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Contenu de la table `hands`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `pick`
-- 

CREATE TABLE `pick` (
  `ga_id` int(11) NOT NULL,
  `ca_id` int(11) NOT NULL,
  PRIMARY KEY  (`ga_id`,`ca_id`),
  KEY `ca_id` (`ca_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Contenu de la table `pick`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `plays`
-- 

CREATE TABLE `plays` (
  `us_id` int(11) NOT NULL,
  `ga_id` int(11) NOT NULL,
  `pl_position` int(11) NOT NULL,
  `pl_status` enum('Attente','Prêt','Inactif') character set utf8 NOT NULL default 'Attente',
  PRIMARY KEY  (`us_id`,`ga_id`),
  KEY `ga_id` (`ga_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

-- 
-- Contenu de la table `plays`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `turns`
-- 

CREATE TABLE `turns` (
  `tu_id` int(11) NOT NULL auto_increment,
  `ga_id` int(11) NOT NULL,
  `us_id` int(11) NOT NULL,
  `tu_date_start` datetime default NULL,
  `tu_date_end` datetime default NULL,
  `tu_comment` char(150) character set latin1 NOT NULL,
  PRIMARY KEY  (`tu_id`),
  KEY `ga_id` (`ga_id`),
  KEY `us_id` (`us_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `turns`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `users`
-- 

CREATE TABLE `users` (
  `us_id` int(11) NOT NULL auto_increment,
  `us_name` char(255) character set latin1 default NULL,
  `us_lastname` char(255) character set latin1 default NULL,
  `us_pseudo` char(255) character set latin1 NOT NULL,
  `us_password` char(255) character set latin1 NOT NULL,
  `us_mail` char(255) character set latin1 NOT NULL,
  `us_birthdate` datetime default NULL,
  `us_signin_date` datetime default NULL,
  `us_last_connexion` datetime default NULL,
  PRIMARY KEY  (`us_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- 
-- Contenu de la table `users`
-- 

INSERT INTO `users` VALUES (2, 'Pierre', 'Criulanscy', 'Deltod', 'd41e2668def681f4b1a7a9d21174fade4300d6ea', 'pcriulan@gmail.com', '1991-04-04 00:00:00', '2012-04-20 10:42:35', '2012-04-20 10:42:35');
INSERT INTO `users` VALUES (3, '', '', 'Matteo', '1f60877a7aa2de96284f1e0c7b331a8d24a2a50a', '', '1991-04-04 00:00:00', '2012-04-20 11:48:25', '2012-04-20 11:48:25');
INSERT INTO `users` VALUES (4, '', '', 'Joueur3', '1f60877a7aa2de96284f1e0c7b331a8d24a2a50a', '', '1991-04-04 00:00:00', '2012-04-23 22:47:58', '2012-04-23 22:47:58');
INSERT INTO `users` VALUES (5, '', '', 'Cécilia', '1f60877a7aa2de96284f1e0c7b331a8d24a2a50a', '', '0000-00-00 00:00:00', '2012-04-26 19:18:39', '2012-04-26 19:18:39');

-- --------------------------------------------------------

-- 
-- Structure de la table `users_friends`
-- 

CREATE TABLE `users_friends` (
  `us_id` int(11) NOT NULL,
  `use_us_id` int(11) NOT NULL,
  `fr_date` datetime NOT NULL,
  `fr_status` int(11) NOT NULL,
  PRIMARY KEY  (`us_id`,`use_us_id`),
  KEY `use_us_id` (`use_us_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Contenu de la table `users_friends`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `votes`
-- 

CREATE TABLE `votes` (
  `us_id` int(11) NOT NULL,
  `ca_id` int(11) NOT NULL,
  `tu_id` int(11) NOT NULL,
  `vo_date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`us_id`,`ca_id`,`tu_id`),
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
  ADD CONSTRAINT `hands_ibfk_1` FOREIGN KEY (`ca_id`) REFERENCES `cards` (`ca_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hands_ibfk_2` FOREIGN KEY (`us_id`) REFERENCES `users` (`us_id`) ON DELETE CASCADE ON UPDATE CASCADE,
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
