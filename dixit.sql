-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Sam 26 Mai 2012 à 10:46
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`us_id`, `us_name`, `us_lastname`, `us_pseudo`, `us_password`, `us_mail`, `us_birthdate`, `us_signin_date`, `us_last_connexion`) VALUES
(1, 'Dixit', 'Online', 'Dixit Online', 'a612da00f6fa24882beb7be190e5f7390567a4a2', 'admin@dixit.com', '0000-00-00 00:00:00', '2012-05-26 11:07:19', '2012-05-26 11:07:19'),
(2, 'Pierre', 'Criulanscy', 'Deltod', 'd41e2668def681f4b1a7a9d21174fade4300d6ea', 'pcriulan@gmail.com', '1991-04-04 00:00:00', '2012-04-20 10:42:35', '2012-04-20 10:42:35'),
(3, '', '', 'Matteo', '1f60877a7aa2de96284f1e0c7b331a8d24a2a50a', '', '1991-04-04 00:00:00', '2012-04-20 11:48:25', '2012-04-20 11:48:25'),
(4, '', '', 'Joueur3', '1f60877a7aa2de96284f1e0c7b331a8d24a2a50a', '', '1991-04-04 00:00:00', '2012-04-23 22:47:58', '2012-04-23 22:47:58'),
(5, '', '', 'Cécilia', '1f60877a7aa2de96284f1e0c7b331a8d24a2a50a', '', '0000-00-00 00:00:00', '2012-04-26 19:18:39', '2012-04-26 19:18:39');
