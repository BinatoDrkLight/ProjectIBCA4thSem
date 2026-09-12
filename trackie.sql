
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `A_id` int(11) NOT NULL AUTO_INCREMENT,
  `A_fName` varchar(20) NOT NULL,
  `A_sName` varchar(20) NOT NULL,
  `A_address` varchar(30) NOT NULL,
  `A_area` varchar(40) NOT NULL,
  PRIMARY KEY (`A_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (14,'Admin','One','Special','Budhanilkantha');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
DROP TABLE IF EXISTS `bookmarks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bookmarks` (
  `Bm_id` int(11) NOT NULL AUTO_INCREMENT,
  `B_id` int(11) DEFAULT NULL,
  `P_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`Bm_id`),
  KEY `B_id` (`B_id`),
  KEY `P_id` (`P_id`),
  CONSTRAINT `bookmarks_ibfk_1` FOREIGN KEY (`B_id`) REFERENCES `bus` (`B_id`),
  CONSTRAINT `bookmarks_ibfk_2` FOREIGN KEY (`P_id`) REFERENCES `passenger` (`P_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `bookmarks` DISABLE KEYS */;
/*!40000 ALTER TABLE `bookmarks` ENABLE KEYS */;
DROP TABLE IF EXISTS `bus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bus` (
  `B_id` int(11) NOT NULL AUTO_INCREMENT,
  `B_model` varchar(20) NOT NULL,
  `B_reg_no` varchar(30) NOT NULL,
  PRIMARY KEY (`B_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `bus` DISABLE KEYS */;
INSERT INTO `bus` VALUES (1,'Sajha Yatayat','Ba12Ka98'),(2,'Nepal Yatayat','Ka34Kha78'),(3,'Kasthamandap','Ba23Ka73'),(4,'Valley Yatayat','Kha54ka60'),(5,'Sajha Yatayat','Cha43Kha80');
/*!40000 ALTER TABLE `bus` ENABLE KEYS */;
DROP TABLE IF EXISTS `d_phone_nos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_phone_nos` (
  `D_phone_no_id` int(11) NOT NULL AUTO_INCREMENT,
  `D_phone_no` varchar(10) NOT NULL,
  `D_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`D_phone_no_id`),
  KEY `d_phone_nos_ibfk_1` (`D_id`),
  CONSTRAINT `d_phone_nos_ibfk_1` FOREIGN KEY (`D_id`) REFERENCES `driver` (`D_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `d_phone_nos` DISABLE KEYS */;
INSERT INTO `d_phone_nos` VALUES (26,'9823820000',28),(27,'9823820002',29),(28,'9823820003',30),(29,'9823820004',31),(30,'9823820005',32),(31,'9823820006',33),(32,'9823820007',34),(33,'9823820008',NULL),(34,'9823820009',NULL),(35,'9823820010',37);
/*!40000 ALTER TABLE `d_phone_nos` ENABLE KEYS */;
DROP TABLE IF EXISTS `driver`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `driver` (
  `D_id` int(11) NOT NULL AUTO_INCREMENT,
  `D_fName` varchar(20) NOT NULL,
  `D_sName` varchar(20) NOT NULL,
  `D_address` varchar(30) NOT NULL,
  `D_license_no` varchar(20) NOT NULL,
  `D_pic_path` varchar(20) DEFAULT NULL,
  `A_id` int(11) DEFAULT NULL,
  `B_id` int(11) DEFAULT NULL,
  `S_id` int(11) DEFAULT NULL,
  `R_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`D_id`),
  KEY `fk_a_id` (`A_id`),
  KEY `fk_b_id` (`B_id`),
  KEY `fk_r_id` (`R_id`),
  KEY `fk_s_id` (`S_id`),
  CONSTRAINT `fk_a_id` FOREIGN KEY (`A_id`) REFERENCES `admin` (`A_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_b_id` FOREIGN KEY (`B_id`) REFERENCES `bus` (`B_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_r_id` FOREIGN KEY (`R_id`) REFERENCES `route` (`R_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_s_id` FOREIGN KEY (`S_id`) REFERENCES `schedule` (`S_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `driver` DISABLE KEYS */;
INSERT INTO `driver` VALUES (28,'Ram','Lal','Naranthan','120000000001',NULL,14,1,7,1),(29,'Pogo','Lal','Naranthan','120000000002',NULL,14,2,8,2),(30,'Sano','Manjiro','Gokarna','120000000003',NULL,14,3,9,3),(31,'Yami','Dancho','BlackBull','120000000004',NULL,14,2,10,3),(32,'Nova','Chrono','Clovers Kingdom','120000000005',NULL,14,4,11,4),(33,'Yuno','Grinberryall','Spade Kingdom','120000000006',NULL,14,2,12,5),(34,'Secre','kilt','Heart Kingdom','120000000007',NULL,14,5,13,2),(37,'Orochimaru','Sanin','Hidden Leaf','120000000010',NULL,14,1,16,1);
/*!40000 ALTER TABLE `driver` ENABLE KEYS */;
DROP TABLE IF EXISTS `location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `location` (
  `L_id` int(11) NOT NULL AUTO_INCREMENT,
  `L_longitude` varchar(40) NOT NULL,
  `L_latitude` varchar(40) NOT NULL,
  `B_id` int(11) DEFAULT NULL,
  `P_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`L_id`),
  KEY `location_ibfk_1` (`B_id`),
  KEY `location_ibfk_2` (`P_id`),
  CONSTRAINT `location_ibfk_1` FOREIGN KEY (`B_id`) REFERENCES `bus` (`B_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `location_ibfk_2` FOREIGN KEY (`P_id`) REFERENCES `passenger` (`P_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `location` DISABLE KEYS */;
INSERT INTO `location` VALUES (10,'85.3180416','27.7086208',2,NULL),(11,'85.3531534256043','27.753121455341315',1,NULL),(12,'85.3539071000','27.74029000',3,NULL),(13,'85.34646000','27.70939000',4,NULL),(14,'85.31523700','27.70634500',5,NULL);
/*!40000 ALTER TABLE `location` ENABLE KEYS */;
DROP TABLE IF EXISTS `loginadmin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loginadmin` (
  `La_id` int(11) NOT NULL AUTO_INCREMENT,
  `La_username` varchar(40) NOT NULL,
  `La_gmail` varchar(40) NOT NULL,
  `La_password` varchar(255) NOT NULL,
  `La_role` varchar(20) DEFAULT 'Admin',
  `A_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`La_id`),
  KEY `fk_la_admin` (`A_id`),
  CONSTRAINT `fk_la_admin` FOREIGN KEY (`A_id`) REFERENCES `admin` (`A_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `loginadmin` DISABLE KEYS */;
INSERT INTO `loginadmin` VALUES (1,'superadmin','superadmin@gmail.com','$2y$10$o2qPnrE832SgkvuOMvEN2ezRzTFfUy.E.UrYgDkYQilnHflcWNeum','SuperAdmin',NULL),(14,'adminone@gmail.com','adminone@gmail.com','$2y$10$6yrInksASU0hlAbNBt/Xxej8DcgveHWRRoToA6H5a0lwb9SobaFgu','Admin',14);
/*!40000 ALTER TABLE `loginadmin` ENABLE KEYS */;
DROP TABLE IF EXISTS `loginregister`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loginregister` (
  `Lr_id` int(11) NOT NULL AUTO_INCREMENT,
  `Lr_username` varchar(30) NOT NULL,
  `Lr_gmail` varchar(30) NOT NULL,
  `Lr_password` varchar(225) NOT NULL,
  `Lr_user` varchar(10) NOT NULL DEFAULT 'Passenger',
  `D_id` int(11) DEFAULT NULL,
  `P_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`Lr_id`),
  KEY `loginregister_ibfk_1` (`D_id`),
  KEY `loginregister_ibfk_2` (`P_id`),
  CONSTRAINT `loginregister_ibfk_1` FOREIGN KEY (`D_id`) REFERENCES `driver` (`D_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `loginregister_ibfk_2` FOREIGN KEY (`P_id`) REFERENCES `passenger` (`P_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `loginregister` DISABLE KEYS */;
INSERT INTO `loginregister` VALUES (24,'binatolight@gmail.com','binatolight@gmail.com','$2y$10$wUZ5/ri4UFnAuJOOTFXmoudw6PNUYznxCLPvk7V2Gf53TCM4ga7oC','Passenger',NULL,14),(28,'ramlal@gmail.com','ramlal@gmail.com','$2y$10$C1gMdkOtEvlX4Hi.HwVdye9SJzpsZx0laevEPBRS6gKaTrJJ8oyPS','Driver',28,NULL),(29,'pogolal@gmail.com','pogolal@gmail.com','$2y$10$JiGZ3l0U0zuiGmyH.n6ku.uV8czPevdBXwsNDBbBDzygMtnzpEDnW','Driver',29,NULL),(30,'sanomanjiro@gmail.com','sanomanjiro@gmail.com','$2y$10$B6Vt/jOzk5kN66RCkv/xPesCbdo7jTcjbH4j0WgxmEzTo5iStfH4G','Driver',30,NULL),(31,'yamidancho@gmail.com','yamidancho@gmail.com','$2y$10$tlFWEOKnIkZiHZZbxAichOTAloKM2o1C2mjOZfEb3GQsQkSWGl9fq','Driver',31,NULL),(32,'novachrono@gmail.com','novachrono@gmail.com','$2y$10$wb6s1/EdDn6oxYedqYmAm.z/ruQzcjBPh2hhu7p4YYHN4W7.aYzam','Driver',32,NULL),(33,'yunogrinberryall@gmail.com','yunogrinberryall@gmail.com','$2y$10$/sKk74fdZtyUzJAIlW9VF.2CBdrGe1PQ23dFlhlcLfUPNcEU1uURi','Driver',33,NULL),(34,'secrekilt@gmail.com','secrekilt@gmail.com','$2y$10$3BFG4jPFVJl50XLMq6kVousHz3ei6XajwKpsE5tA4hvhcHalHhzFW','Driver',34,NULL),(35,'gohanson@gmail.com','gohanson@gmail.com','$2y$10$IR7N.3eqQaixcNL0QnqNreU13rDMLqaIUYGVocxYa/cdFASILKYha','Driver',NULL,NULL),(36,'paintendo@gmail.com','paintendo@gmail.com','$2y$10$Rvik83PI7rOknGfePuCj9O4XDWVWl0pqCWW8uXlZLh2HNyMZ/PcHi','Driver',NULL,NULL),(37,'orochimarusanin@gmail.com','orochimarusanin@gmail.com','$2y$10$J4jJtjZtGtl6PbMWxGpY/.MGuXnynfDPp.kSrwh2h5Cr1//MB/CPq','Driver',37,NULL),(38,'usernone@gmail.com','usernone@gmail.com','$2y$10$jY759Jm0IRe2eToOa0X3WeTeNfgQ7gPHjQlDlBG7RSOK87MYfk9Wi','Passenger',NULL,18),(39,'adminone@gmail.com','adminone@gmail.com','$2y$10$5IYYThJQEXa5UCazMSpXtO67b6wPpPmx6w8PEGpdB8SDm5YQpKSDe','Passenger',NULL,19);
/*!40000 ALTER TABLE `loginregister` ENABLE KEYS */;
DROP TABLE IF EXISTS `p_phone_nos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `p_phone_nos` (
  `P_phone_no_id` int(11) NOT NULL AUTO_INCREMENT,
  `P_phone_no` varchar(10) NOT NULL,
  `P_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`P_phone_no_id`),
  KEY `p_phone_nos_ibfk_1` (`P_id`),
  CONSTRAINT `p_phone_nos_ibfk_1` FOREIGN KEY (`P_id`) REFERENCES `passenger` (`P_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `p_phone_nos` DISABLE KEYS */;
INSERT INTO `p_phone_nos` VALUES (13,'9823820860',14),(14,'9823829861',NULL),(15,'9841000001',NULL),(16,'9823820123',NULL),(17,'9868000001',18),(18,'9868000007',19);
/*!40000 ALTER TABLE `p_phone_nos` ENABLE KEYS */;
DROP TABLE IF EXISTS `passenger`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `passenger` (
  `P_id` int(11) NOT NULL AUTO_INCREMENT,
  `P_fName` varchar(20) NOT NULL,
  `P_sName` varchar(20) NOT NULL,
  `P_gmail` varchar(30) NOT NULL,
  `P_pic_path` varchar(30) NOT NULL,
  `B_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`P_id`),
  KEY `passenger_ibfk_1` (`B_id`),
  CONSTRAINT `passenger_ibfk_1` FOREIGN KEY (`B_id`) REFERENCES `bus` (`B_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `passenger` DISABLE KEYS */;
INSERT INTO `passenger` VALUES (14,'Binato','Light','binatolight@gmail.com','',NULL),(18,'User','None','usernone@gmail.com','',NULL),(19,'Admin','One','adminone@gmail.com','',NULL);
/*!40000 ALTER TABLE `passenger` ENABLE KEYS */;
DROP TABLE IF EXISTS `route`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `route` (
  `R_id` int(11) NOT NULL AUTO_INCREMENT,
  `R_name` varchar(40) NOT NULL,
  `R_start` varchar(20) NOT NULL,
  `R_end` varchar(20) NOT NULL,
  PRIMARY KEY (`R_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `route` DISABLE KEYS */;
INSERT INTO `route` VALUES (1,'Budhanilkantha','Naranthan','Ratnapark'),(2,'Tokha','Hepali Height','Saibaba Chowk'),(3,'Gokarneshwor','Gokarna','Kalanki'),(4,'Kathmandu MN','Gausala','kamalpokhari'),(5,'Kirtipur','Ratnapark','Kirtipur');
/*!40000 ALTER TABLE `route` ENABLE KEYS */;
DROP TABLE IF EXISTS `schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schedule` (
  `S_id` int(11) NOT NULL AUTO_INCREMENT,
  `S_day` varchar(10) NOT NULL,
  `S_sTime` time NOT NULL,
  `S_eTime` time NOT NULL,
  PRIMARY KEY (`S_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `schedule` DISABLE KEYS */;
INSERT INTO `schedule` VALUES (7,'Sunday','02:00:00','03:00:00'),(8,'Sunday','04:00:00','05:00:00'),(9,'Sunday','05:00:00','06:00:00'),(10,'Sunday','06:00:00','07:00:00'),(11,'Sunday','06:30:00','07:30:00'),(12,'Tuesday','02:00:00','03:00:00'),(13,'Tuesday','04:00:00','05:00:00'),(14,'Tuesday','05:00:00','06:00:00'),(15,'Tuesday','06:00:00','07:00:00'),(16,'Tuesday','06:30:00','07:30:00');
/*!40000 ALTER TABLE `schedule` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

