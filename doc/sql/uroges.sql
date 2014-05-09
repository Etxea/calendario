-- MySQL dump 10.13  Distrib 5.5.37, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: uroges
-- ------------------------------------------------------
-- Server version	5.5.37-0ubuntu0.14.04.1

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
-- Table structure for table `addressbooks`
--

DROP TABLE IF EXISTS `addressbooks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `addressbooks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `principaluri` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `displayname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uri` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `ctag` int(11) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `principaluri` (`principaluri`,`uri`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addressbooks`
--

LOCK TABLES `addressbooks` WRITE;
/*!40000 ALTER TABLE `addressbooks` DISABLE KEYS */;
/*!40000 ALTER TABLE `addressbooks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `calendarchanges`
--

DROP TABLE IF EXISTS `calendarchanges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calendarchanges` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uri` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `synctoken` int(11) unsigned NOT NULL,
  `calendarid` int(11) unsigned NOT NULL,
  `operation` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `calendarid_synctoken` (`calendarid`,`synctoken`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calendarchanges`
--

LOCK TABLES `calendarchanges` WRITE;
/*!40000 ALTER TABLE `calendarchanges` DISABLE KEYS */;
/*!40000 ALTER TABLE `calendarchanges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `calendarobjects`
--

DROP TABLE IF EXISTS `calendarobjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calendarobjects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `calendardata` mediumblob,
  `uri` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `calendarid` int(10) unsigned NOT NULL,
  `lastmodified` int(11) unsigned DEFAULT NULL,
  `etag` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `size` int(11) unsigned NOT NULL,
  `componenttype` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `firstoccurence` int(11) unsigned DEFAULT NULL,
  `lastoccurence` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `calendarid` (`calendarid`,`uri`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calendarobjects`
--

LOCK TABLES `calendarobjects` WRITE;
/*!40000 ALTER TABLE `calendarobjects` DISABLE KEYS */;
/*!40000 ALTER TABLE `calendarobjects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `calendars`
--

DROP TABLE IF EXISTS `calendars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calendars` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `principaluri` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `displayname` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uri` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `synctoken` int(11) unsigned NOT NULL DEFAULT '0',
  `description` text COLLATE utf8_unicode_ci,
  `calendarorder` int(11) unsigned NOT NULL DEFAULT '0',
  `calendarcolor` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timezone` text COLLATE utf8_unicode_ci,
  `components` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `transparent` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `principaluri` (`principaluri`,`uri`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calendars`
--

LOCK TABLES `calendars` WRITE;
/*!40000 ALTER TABLE `calendars` DISABLE KEYS */;
/*!40000 ALTER TABLE `calendars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `calendarsubscriptions`
--

DROP TABLE IF EXISTS `calendarsubscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calendarsubscriptions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uri` varchar(200) NOT NULL,
  `principaluri` varchar(100) NOT NULL,
  `source` text,
  `displayname` varchar(100) DEFAULT NULL,
  `refreshrate` varchar(10) DEFAULT NULL,
  `calendarorder` int(11) unsigned NOT NULL DEFAULT '0',
  `calendarcolor` varchar(10) DEFAULT NULL,
  `striptodos` tinyint(1) DEFAULT NULL,
  `stripalarms` tinyint(1) DEFAULT NULL,
  `stripattachments` tinyint(1) DEFAULT NULL,
  `lastmodified` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `principaluri` (`principaluri`,`uri`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calendarsubscriptions`
--

LOCK TABLES `calendarsubscriptions` WRITE;
/*!40000 ALTER TABLE `calendarsubscriptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `calendarsubscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cards`
--

DROP TABLE IF EXISTS `cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cards` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `addressbookid` int(11) unsigned NOT NULL,
  `carddata` mediumblob,
  `uri` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastmodified` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cards`
--

LOCK TABLES `cards` WRITE;
/*!40000 ALTER TABLE `cards` DISABLE KEYS */;
/*!40000 ALTER TABLE `cards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groupmembers`
--

DROP TABLE IF EXISTS `groupmembers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groupmembers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `principal_id` int(10) unsigned NOT NULL,
  `member_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `principal_id` (`principal_id`,`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groupmembers`
--

LOCK TABLES `groupmembers` WRITE;
/*!40000 ALTER TABLE `groupmembers` DISABLE KEYS */;
/*!40000 ALTER TABLE `groupmembers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `locks`
--

DROP TABLE IF EXISTS `locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `locks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner` varchar(100) DEFAULT NULL,
  `timeout` int(10) unsigned DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  `token` varchar(100) DEFAULT NULL,
  `scope` tinyint(4) DEFAULT NULL,
  `depth` tinyint(4) DEFAULT NULL,
  `uri` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `uri` (`uri`(767))
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locks`
--

LOCK TABLES `locks` WRITE;
/*!40000 ALTER TABLE `locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ocupacion_otros`
--

DROP TABLE IF EXISTS `ocupacion_otros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ocupacion_otros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `tipo` varchar(12) NOT NULL,
  `fecha` date NOT NULL,
  `caldav_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ocupacion_otros`
--

LOCK TABLES `ocupacion_otros` WRITE;
/*!40000 ALTER TABLE `ocupacion_otros` DISABLE KEYS */;
INSERT INTO `ocupacion_otros` VALUES (17,1,'3','2014-01-01',41),(19,1,'5','2014-01-06',43),(20,1,'5','2014-02-04',44),(21,1,'5','2014-04-07',45),(23,1,'6','2014-01-06',47),(25,1,'1','2014-01-01',49);
/*!40000 ALTER TABLE `ocupacion_otros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ocupacion_servicios`
--

DROP TABLE IF EXISTS `ocupacion_servicios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ocupacion_servicios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `servicio_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `caldav_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ocupacion_servicios`
--

LOCK TABLES `ocupacion_servicios` WRITE;
/*!40000 ALTER TABLE `ocupacion_servicios` DISABLE KEYS */;
INSERT INTO `ocupacion_servicios` VALUES (6,1,3,'2014-01-01',16);
/*!40000 ALTER TABLE `ocupacion_servicios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `principals`
--

DROP TABLE IF EXISTS `principals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `principals` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uri` varchar(200) NOT NULL,
  `email` varchar(80) DEFAULT NULL,
  `displayname` varchar(80) DEFAULT NULL,
  `vcardurl` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uri` (`uri`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `principals`
--

LOCK TABLES `principals` WRITE;
/*!40000 ALTER TABLE `principals` DISABLE KEYS */;
/*!40000 ALTER TABLE `principals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicios`
--

DROP TABLE IF EXISTS `servicios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servicios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `nombre_corto` varchar(6) NOT NULL,
  `estado` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicios`
--

LOCK TABLES `servicios` WRITE;
/*!40000 ALTER TABLE `servicios` DISABLE KEYS */;
INSERT INTO `servicios` VALUES (1,0,'Planta Izquierda','Planta',1),(2,0,'Consulta numero 2','Consul',1),(3,3,'Ambulatorio 1','Amb1',1),(4,4,'Guardia','Guardi',0);
/*!40000 ALTER TABLE `servicios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicios_tipo`
--

DROP TABLE IF EXISTS `servicios_tipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servicios_tipo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicios_tipo`
--

LOCK TABLES `servicios_tipo` WRITE;
/*!40000 ALTER TABLE `servicios_tipo` DISABLE KEYS */;
INSERT INTO `servicios_tipo` VALUES (3,'Ambulatorio'),(100,'Baja Labora'),(2,'Consulta'),(4,'Guardias'),(1,'Planta');
/*!40000 ALTER TABLE `servicios_tipo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `digesta1` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `apellidos` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `categoria` int(11) NOT NULL,
  `rol` int(11) NOT NULL,
  `username` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'admin','admin admin',1,1,'admin','admin');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-05-09 11:14:02
