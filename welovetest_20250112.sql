-- MySQL dump 10.13  Distrib 8.0.40, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: welove_test
-- ------------------------------------------------------
-- Server version	8.4.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `owners`
--

DROP TABLE IF EXISTS `owners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `owners` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `owner_email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `owners`
--

LOCK TABLES `owners` WRITE;
/*!40000 ALTER TABLE `owners` DISABLE KEYS */;
INSERT INTO `owners` VALUES (1,'Molnár Ádám','molnaradam@moszerviz.com'),(6,'Teszt Elek','elek.teszt@moszerviz.com'),(7,'Kis Cipó 2','cipo.kiss@moszerviz.com'),(8,'sdfasdf','adsfsda'),(9,'sadfasdf','sdf@asdf.hu'),(10,'Ez a legújabb','legujabb@moszerviz.com'),(11,'Kiss Pál','pal.kiss@moszerviz.com');
/*!40000 ALTER TABLE `owners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_owner_pivot`
--

DROP TABLE IF EXISTS `project_owner_pivot`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_owner_pivot` (
  `project_id` int NOT NULL,
  `owner_id` int NOT NULL,
  KEY `fk_project_owner_pivot_projects1_idx` (`project_id`),
  KEY `fk_project_owner_pivot_owners1_idx` (`owner_id`),
  CONSTRAINT `fk_project_owner_pivot_owners1` FOREIGN KEY (`owner_id`) REFERENCES `owners` (`id`),
  CONSTRAINT `fk_project_owner_pivot_projects1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_owner_pivot`
--

LOCK TABLES `project_owner_pivot` WRITE;
/*!40000 ALTER TABLE `project_owner_pivot` DISABLE KEYS */;
INSERT INTO `project_owner_pivot` VALUES (3,1),(2,6),(1,1),(6,1);
/*!40000 ALTER TABLE `project_owner_pivot` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_status_pivot`
--

DROP TABLE IF EXISTS `project_status_pivot`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_status_pivot` (
  `project_id` int NOT NULL,
  `status_id` int NOT NULL,
  UNIQUE KEY `project_id_UNIQUE` (`project_id`),
  KEY `fk_project_status_pivot_statuses1_idx` (`status_id`),
  CONSTRAINT `fk_project_status_pivot_projects` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`),
  CONSTRAINT `fk_project_status_pivot_statuses1` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_status_pivot`
--

LOCK TABLES `project_status_pivot` WRITE;
/*!40000 ALTER TABLE `project_status_pivot` DISABLE KEYS */;
INSERT INTO `project_status_pivot` VALUES (1,1),(6,2),(2,3),(3,3);
/*!40000 ALTER TABLE `project_status_pivot` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `projects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` VALUES (1,'Lorem ipsum dolor sit amet, consectetur adipiscing elit.','Phasellus porttitor molestie erat. Mauris vulputate at arcu at elementum. Etiam faucibus varius porta. Donec in magna in lorem congue varius vel in elit. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Sed sit amet diam molestie, elementum mi dapibus, egestas libero. Duis sem lectus, laoreet quis semper nec, mattis eget ex. Integer ac lobortis tortor. Sed at varius ipsum. Cras eget lacus non turpis egestas faucibus. Morbi vel iaculis felis, sed lobortis nunc. Donec placerat magna id quam vestibulum accumsan.'),(2,'igen','Teszt redirect'),(3,'Vestibulum sapien metus, feugiat non nunc sed, laoreet luctus dolor.','Vestibulum nibh urna, rutrum sit amet sem ut, rhoncus tristique lectus. Etiam laoreet efficitur tincidunt. In hac habitasse platea dictumst. Integer fringilla mi quam. Cras enim orci, pharetra eu blandit id, tincidunt at justo. Nunc sed leo a sapien laoreet pretium eleifend eget purus. Sed in sapien quis diam posuere pharetra quis vel sapien. Sed sed aliquet neque, in rhoncus ex. Aliquam posuere euismod magna, ut consequat nulla placerat vitae. Aenean fringilla, tellus sed aliquet molestie, nisi urna aliquet quam, et tincidunt mauris quam vitae ligula. Nullam tristique mattis pretium. Pellentesque vel ultricies elit. Maecenas elementum magna dignissim ex molestie, quis fringilla leo venenatis. Donec ut velit eget ex commodo volutpat vitae non arcu. Aenean finibus ullamcorper justo, nec ullamcorper magna ullamcorper nec.'),(6,'Próbafeladat továbbítása 2','Miért nincs még elküldve a próbafeladat? Már rég el kellett volna küldeni!!! 2 ');
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `statuses`
--

DROP TABLE IF EXISTS `statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `statuses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `key` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_UNIQUE` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `statuses`
--

LOCK TABLES `statuses` WRITE;
/*!40000 ALTER TABLE `statuses` DISABLE KEYS */;
INSERT INTO `statuses` VALUES (1,'todo','Fejlesztésre vár'),(2,'in_progress','Folyamatban'),(3,'done','Kész');
/*!40000 ALTER TABLE `statuses` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-01-12 19:19:10
