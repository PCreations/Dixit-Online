-- MySQL dump 10.13  Distrib 5.5.16, for Win32 (x86)
--
-- Host: localhost    Database: dixit
-- ------------------------------------------------------
-- Server version	5.5.16-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `boards`
--

DROP TABLE IF EXISTS `boards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `boards` (
  `tu_id` int(11) NOT NULL,
  `ca_id` int(11) NOT NULL,
  PRIMARY KEY (`tu_id`,`ca_id`),
  KEY `fk_boards2` (`ca_id`),
  CONSTRAINT `fk_boards` FOREIGN KEY (`tu_id`) REFERENCES `turns` (`tu_id`),
  CONSTRAINT `fk_boards2` FOREIGN KEY (`ca_id`) REFERENCES `cards` (`ca_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `boards`
--

LOCK TABLES `boards` WRITE;
/*!40000 ALTER TABLE `boards` DISABLE KEYS */;
/*!40000 ALTER TABLE `boards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cards`
--

DROP TABLE IF EXISTS `cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cards` (
  `ca_id` int(11) NOT NULL AUTO_INCREMENT,
  `ca_name` char(255) DEFAULT NULL,
  `ca_image` char(255) DEFAULT NULL,
  PRIMARY KEY (`ca_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cards`
--

LOCK TABLES `cards` WRITE;
/*!40000 ALTER TABLE `cards` DISABLE KEYS */;
/*!40000 ALTER TABLE `cards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `deck`
--

DROP TABLE IF EXISTS `deck`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `deck` (
  `gt_id` int(11) NOT NULL,
  `ca_id` int(11) NOT NULL,
  PRIMARY KEY (`gt_id`,`ca_id`),
  KEY `fk_deck2` (`ca_id`),
  CONSTRAINT `fk_deck` FOREIGN KEY (`gt_id`) REFERENCES `game_types` (`gt_id`),
  CONSTRAINT `fk_deck2` FOREIGN KEY (`ca_id`) REFERENCES `cards` (`ca_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `deck`
--

LOCK TABLES `deck` WRITE;
/*!40000 ALTER TABLE `deck` DISABLE KEYS */;
/*!40000 ALTER TABLE `deck` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `earned_points`
--

DROP TABLE IF EXISTS `earned_points`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `earned_points` (
  `us_id` int(11) NOT NULL,
  `tu_id` int(11) NOT NULL,
  PRIMARY KEY (`us_id`,`tu_id`),
  KEY `fk_earned_points2` (`tu_id`),
  CONSTRAINT `fk_earned_points` FOREIGN KEY (`us_id`) REFERENCES `users` (`us_id`),
  CONSTRAINT `fk_earned_points2` FOREIGN KEY (`tu_id`) REFERENCES `turns` (`tu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `earned_points`
--

LOCK TABLES `earned_points` WRITE;
/*!40000 ALTER TABLE `earned_points` DISABLE KEYS */;
/*!40000 ALTER TABLE `earned_points` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game_status`
--

DROP TABLE IF EXISTS `game_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_status` (
  `gs_id` int(11) NOT NULL AUTO_INCREMENT,
  `gs_name` char(255) NOT NULL,
  PRIMARY KEY (`gs_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_status`
--

LOCK TABLES `game_status` WRITE;
/*!40000 ALTER TABLE `game_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `game_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game_types`
--

DROP TABLE IF EXISTS `game_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_types` (
  `gt_id` int(11) NOT NULL AUTO_INCREMENT,
  `gt_name` char(255) NOT NULL,
  `gt_nb_players` int(11) NOT NULL,
  PRIMARY KEY (`gt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_types`
--

LOCK TABLES `game_types` WRITE;
/*!40000 ALTER TABLE `game_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `game_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `games`
--

DROP TABLE IF EXISTS `games`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `games` (
  `ga_id` int(11) NOT NULL AUTO_INCREMENT,
  `gt_id` int(11) NOT NULL,
  `gs_id` int(11) NOT NULL,
  PRIMARY KEY (`ga_id`),
  KEY `fk_ga_gs` (`gs_id`),
  KEY `fk_ga_gt` (`gt_id`),
  CONSTRAINT `fk_ga_gs` FOREIGN KEY (`gs_id`) REFERENCES `game_status` (`gs_id`),
  CONSTRAINT `fk_ga_gt` FOREIGN KEY (`gt_id`) REFERENCES `game_types` (`gt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `games`
--

LOCK TABLES `games` WRITE;
/*!40000 ALTER TABLE `games` DISABLE KEYS */;
/*!40000 ALTER TABLE `games` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hands`
--

DROP TABLE IF EXISTS `hands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hands` (
  `ca_id` int(11) NOT NULL,
  `us_id` int(11) NOT NULL,
  `ga_id` int(11) NOT NULL,
  PRIMARY KEY (`ca_id`,`us_id`,`ga_id`),
  KEY `fk_hands2` (`us_id`),
  KEY `fk_hands3` (`ga_id`),
  CONSTRAINT `fk_hands` FOREIGN KEY (`ca_id`) REFERENCES `cards` (`ca_id`),
  CONSTRAINT `fk_hands2` FOREIGN KEY (`us_id`) REFERENCES `users` (`us_id`),
  CONSTRAINT `fk_hands3` FOREIGN KEY (`ga_id`) REFERENCES `games` (`ga_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hands`
--

LOCK TABLES `hands` WRITE;
/*!40000 ALTER TABLE `hands` DISABLE KEYS */;
/*!40000 ALTER TABLE `hands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pick`
--

DROP TABLE IF EXISTS `pick`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pick` (
  `ga_id` int(11) NOT NULL,
  `ca_id` int(11) NOT NULL,
  PRIMARY KEY (`ga_id`,`ca_id`),
  KEY `fk_pick2` (`ca_id`),
  CONSTRAINT `fk_pick` FOREIGN KEY (`ga_id`) REFERENCES `games` (`ga_id`),
  CONSTRAINT `fk_pick2` FOREIGN KEY (`ca_id`) REFERENCES `cards` (`ca_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pick`
--

LOCK TABLES `pick` WRITE;
/*!40000 ALTER TABLE `pick` DISABLE KEYS */;
/*!40000 ALTER TABLE `pick` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plays`
--

DROP TABLE IF EXISTS `plays`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plays` (
  `us_id` int(11) NOT NULL,
  `ga_id` int(11) NOT NULL,
  PRIMARY KEY (`us_id`,`ga_id`),
  KEY `fk_plays2` (`ga_id`),
  CONSTRAINT `fk_plays` FOREIGN KEY (`us_id`) REFERENCES `users` (`us_id`),
  CONSTRAINT `fk_plays2` FOREIGN KEY (`ga_id`) REFERENCES `games` (`ga_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plays`
--

LOCK TABLES `plays` WRITE;
/*!40000 ALTER TABLE `plays` DISABLE KEYS */;
/*!40000 ALTER TABLE `plays` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `turns`
--

DROP TABLE IF EXISTS `turns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `turns` (
  `tu_id` int(11) NOT NULL AUTO_INCREMENT,
  `us_id` int(11) NOT NULL,
  `tu_date_start` datetime DEFAULT NULL,
  `tu_date_end` datetime DEFAULT NULL,
  `di_comments` char(150) NOT NULL,
  PRIMARY KEY (`tu_id`),
  KEY `fk_conduct` (`us_id`),
  CONSTRAINT `fk_conduct` FOREIGN KEY (`us_id`) REFERENCES `users` (`us_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `turns`
--

LOCK TABLES `turns` WRITE;
/*!40000 ALTER TABLE `turns` DISABLE KEYS */;
/*!40000 ALTER TABLE `turns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `us_id` int(11) NOT NULL AUTO_INCREMENT,
  `us_name` char(255) DEFAULT NULL,
  `us_lastname` char(255) DEFAULT NULL,
  `us_pseudo` char(255) NOT NULL,
  `us_password` char(255) NOT NULL,
  `us_mail` char(255) NOT NULL,
  `us_birthdate` datetime DEFAULT NULL,
  `us_signin_date` datetime DEFAULT NULL,
  `us_last_connexion` datetime DEFAULT NULL,
  PRIMARY KEY (`us_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_friends`
--

DROP TABLE IF EXISTS `users_friends`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_friends` (
  `us_id` int(11) NOT NULL,
  `use_us_id` int(11) NOT NULL,
  `uf_date` datetime NOT NULL,
  `uf_status` int(11) NOT NULL,
  PRIMARY KEY (`us_id`,`use_us_id`),
  KEY `fk_users_friends2` (`use_us_id`),
  CONSTRAINT `fk_users_friends` FOREIGN KEY (`us_id`) REFERENCES `users` (`us_id`),
  CONSTRAINT `fk_users_friends2` FOREIGN KEY (`use_us_id`) REFERENCES `users` (`us_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_friends`
--

LOCK TABLES `users_friends` WRITE;
/*!40000 ALTER TABLE `users_friends` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_friends` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `votes`
--

DROP TABLE IF EXISTS `votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `votes` (
  `us_id` int(11) NOT NULL,
  `ca_id` int(11) NOT NULL,
  `tu_id` int(11) NOT NULL,
  `vo_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`us_id`,`ca_id`,`tu_id`),
  KEY `fk_votes2` (`ca_id`),
  KEY `fk_votes3` (`tu_id`),
  CONSTRAINT `fk_votes` FOREIGN KEY (`us_id`) REFERENCES `users` (`us_id`),
  CONSTRAINT `fk_votes2` FOREIGN KEY (`ca_id`) REFERENCES `cards` (`ca_id`),
  CONSTRAINT `fk_votes3` FOREIGN KEY (`tu_id`) REFERENCES `turns` (`tu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `votes`
--

LOCK TABLES `votes` WRITE;
/*!40000 ALTER TABLE `votes` DISABLE KEYS */;
/*!40000 ALTER TABLE `votes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-05-07 16:47:57
