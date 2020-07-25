CREATE DATABASE  IF NOT EXISTS `reserve` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `reserve`;
-- MySQL dump 10.13  Distrib 8.0.20, for Win64 (x86_64)
--
-- Host: localhost    Database: reserve
-- ------------------------------------------------------
-- Server version	5.6.48

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `000m_system`
--

DROP TABLE IF EXISTS `000m_system`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `000m_system` (
  `code` varchar(128) NOT NULL,
  `value` varchar(1024) NOT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `000m_system`
--

LOCK TABLES `000m_system` WRITE;
/*!40000 ALTER TABLE `000m_system` DISABLE KEYS */;
/*!40000 ALTER TABLE `000m_system` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `001m_role`
--

DROP TABLE IF EXISTS `001m_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `001m_role` (
  `RoleId` int(11) NOT NULL COMMENT 'ロールID',
  `RoleName` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ロール名称',
  PRIMARY KEY (`RoleId`,`RoleName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `001m_role`
--

LOCK TABLES `001m_role` WRITE;
/*!40000 ALTER TABLE `001m_role` DISABLE KEYS */;
INSERT INTO `001m_role` VALUES (0,'システム管理者');
/*!40000 ALTER TABLE `001m_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `002m_permission`
--

DROP TABLE IF EXISTS `002m_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `002m_permission` (
  `RoleId` int(11) NOT NULL,
  `FunctionId` int(11) NOT NULL,
  PRIMARY KEY (`RoleId`,`FunctionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='権限テーブル';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `002m_permission`
--

LOCK TABLES `002m_permission` WRITE;
/*!40000 ALTER TABLE `002m_permission` DISABLE KEYS */;
/*!40000 ALTER TABLE `002m_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `003m_function`
--

DROP TABLE IF EXISTS `003m_function`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `003m_function` (
  `FunctionId` int(11) NOT NULL,
  `FunctionName` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`FunctionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='機能テーブル';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `003m_function`
--

LOCK TABLES `003m_function` WRITE;
/*!40000 ALTER TABLE `003m_function` DISABLE KEYS */;
/*!40000 ALTER TABLE `003m_function` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `010m_user`
--

DROP TABLE IF EXISTS `010m_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `010m_user` (
  `UserId` varchar(12) NOT NULL,
  `Pass` varchar(100) NOT NULL,
  `ExpireDate` date NOT NULL,
  `UserDiv` char(16) NOT NULL,
  `UserName` varchar(50) NOT NULL,
  `RoleId` int(11) NOT NULL DEFAULT '999',
  `UpdUserId` varchar(16) NOT NULL,
  `UpdDateTime` datetime NOT NULL,
  PRIMARY KEY (`UserId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `010m_user`
--

LOCK TABLES `010m_user` WRITE;
/*!40000 ALTER TABLE `010m_user` DISABLE KEYS */;
INSERT INTO `010m_user` VALUES ('admin','1234','0000-00-00','1','',0,'','0000-00-00 00:00:00'),('demo','1234','0000-00-00','2','',999,'','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `010m_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `090t_ope_log`
--

DROP TABLE IF EXISTS `090t_ope_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `090t_ope_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID番号',
  `OpeDate` datetime NOT NULL,
  `OpeUser` varchar(16) NOT NULL,
  `Type` tinyint(1) NOT NULL COMMENT '1:登録 2:更新 3:削除',
  `detail` varchar(2048) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='操作ログの記録';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `090t_ope_log`
--

LOCK TABLES `090t_ope_log` WRITE;
/*!40000 ALTER TABLE `090t_ope_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `090t_ope_log` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-07-25 21:41:37
