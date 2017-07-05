CREATE DATABASE  IF NOT EXISTS `tstech_test` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `tstech_test`;
-- MySQL dump 10.13  Distrib 5.7.9, for Win32 (AMD64)
--
-- Host: localhost    Database: tstech_test
-- ------------------------------------------------------
-- Server version	5.7.11-log

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
-- Table structure for table `account`
--

DROP TABLE IF EXISTS `account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `balance` decimal(20,2) NOT NULL DEFAULT '0.00',
  `client_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_account_client_id_idx` (`client_id`),
  CONSTRAINT `FK_account_client_id` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account`
--

LOCK TABLES `account` WRITE;
/*!40000 ALTER TABLE `account` DISABLE KEYS */;
INSERT INTO `account` VALUES (1,0.00,1),(2,0.00,4),(3,0.00,6);
/*!40000 ALTER TABLE `account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `account_deposit`
--

DROP TABLE IF EXISTS `account_deposit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_deposit` (
  `deposit_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  PRIMARY KEY (`deposit_id`,`account_id`),
  KEY `FK_deposit_account_id_idx` (`account_id`),
  CONSTRAINT `FK_ad_account_id` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_ad_deposit_id` FOREIGN KEY (`deposit_id`) REFERENCES `deposit` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_deposit`
--

LOCK TABLES `account_deposit` WRITE;
/*!40000 ALTER TABLE `account_deposit` DISABLE KEYS */;
INSERT INTO `account_deposit` VALUES (1,1),(2,2),(3,3);
/*!40000 ALTER TABLE `account_deposit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(45) DEFAULT NULL,
  `lastname` varchar(45) DEFAULT NULL,
  `sex` enum('male','female') DEFAULT NULL,
  `birthdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client`
--

LOCK TABLES `client` WRITE;
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
INSERT INTO `client` VALUES (1,'Zend','LastZend','male','1978-08-24 00:00:00'),(2,'Zend','LastAlex','male','1980-02-05 00:00:00'),(3,'Alex','LastAlex','male','1990-07-27 00:00:00'),(4,'Test','LastZend','female','1996-09-03 00:00:00'),(5,'Test','LastAlex','female','1968-03-24 00:00:00'),(6,'Zend','LastAlex','female','1950-05-05 00:00:00');
/*!40000 ALTER TABLE `client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `deposit`
--

DROP TABLE IF EXISTS `deposit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `deposit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `balance` double(20,2) DEFAULT NULL,
  `deposit_percent` int(11) DEFAULT NULL,
  `open_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `deposit`
--

LOCK TABLES `deposit` WRITE;
/*!40000 ALTER TABLE `deposit` DISABLE KEYS */;
INSERT INTO `deposit` VALUES (1,'depo 1',971.96,10,'2017-01-01 00:00:00'),(2,'depo 5',450.00,20,'2010-10-31 00:00:00'),(3,'depo 10',50.00,0,'2017-11-29 00:00:00');
/*!40000 ALTER TABLE `deposit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_log`
--

DROP TABLE IF EXISTS `transaction_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) DEFAULT NULL,
  `type` enum('commission','payment') DEFAULT NULL,
  `percent` int(11) DEFAULT NULL,
  `amount` decimal(20,2) DEFAULT NULL,
  `deposit_id` int(11) DEFAULT NULL,
  `log_date` datetime DEFAULT NULL,
  `deposit_balance_before` decimal(20,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tl_client_id_idx` (`client_id`),
  KEY `FK_tl_deposit_id_idx` (`deposit_id`),
  CONSTRAINT `FK_tl_client_id` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_tl_deposit_id` FOREIGN KEY (`deposit_id`) REFERENCES `deposit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_log`
--

LOCK TABLES `transaction_log` WRITE;
/*!40000 ALTER TABLE `transaction_log` DISABLE KEYS */;
INSERT INTO `transaction_log` VALUES (1,1,'payment',10,100.00,1,'2017-10-01 00:00:00',1000.00),(2,1,'commission',NULL,66.00,1,'2017-10-01 00:00:00',1100.00),(3,1,'commission',NULL,62.04,1,'2000-01-01 00:00:00',1034.00),(4,4,'commission',NULL,50.00,2,'2000-01-01 00:00:00',500.00),(5,6,'commission',NULL,50.00,3,'2000-01-01 00:00:00',100.00);
/*!40000 ALTER TABLE `transaction_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'tstech_test'
--

--
-- Dumping routines for database 'tstech_test'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-07-05  7:48:51
