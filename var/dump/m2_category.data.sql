-- MySQL dump 10.13  Distrib 5.5.41, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: termt_u9_ru
-- ------------------------------------------------------
-- Server version	5.5.41-0+wheezy1

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
-- Dumping data for table `m2_category`
--

LOCK TABLES `m2_category` WRITE;
/*!40000 ALTER TABLE `m2_category` DISABLE KEYS */;
INSERT INTO `m2_category` VALUES (1,'Каталог','catalog','/catalog/',1,0,1,'','','',1,70,1,0,''),(319,'Товары 1','tovary_1','/tovary_1/',1,0,1,'','','',2,27,2,0,''),(320,'Товары 2','tovary_2','/tovary_2/',1,0,1,'','','',28,39,2,0,''),(321,'Товары 3','tovary_3','/tovary_3/',1,0,1,'','','',40,51,2,0,''),(322,'Товары 4','tovary_4','/tovary_4/',1,0,1,'','','',52,57,2,0,''),(323,'Товары 5','tovary_5','/tovary_5/',1,0,1,'','','',58,61,2,0,''),(324,'Товары 6','tovary_6','/tovary_6/',1,0,1,'','','',62,65,2,0,''),(325,'Товары 7','tovary_7','/tovary_1/tovary_7/',1,0,1,'','','',3,4,3,0,''),(326,'Товары 8','tovary_8','/tovary_1/tovary_8/',1,0,1,'','','',5,14,3,0,''),(327,'Товары 9','tovary_9','/tovary_1/tovary_9/',1,0,1,'','','',15,20,3,0,''),(328,'Товары 10','tovary_10','/tovary_1/tovary_10/',1,0,1,'','','',21,26,3,0,''),(329,'Товары 11','tovary_11','/tovary_2/tovary_11/',1,0,1,'','','',29,30,3,0,''),(330,'Товары 12','tovary_12','/tovary_2/tovary_12/',1,0,1,'','','',31,36,3,0,''),(331,'Товары 13','tovary_13','/tovary_2/tovary_13/',1,0,1,'','','',37,38,3,0,''),(332,'Товары 14','tovary_14','/tovary_3/tovary_14/',1,0,1,'','','',41,42,3,0,''),(333,'Товары 15','tovary_15','/tovary_3/tovary_15/',1,0,1,'','','',43,50,3,0,''),(334,'Товары 16','tovary_16','/tovary_4/tovary_16/',1,0,1,'','','',53,54,3,0,''),(335,'Товары 17','tovary_17','/tovary_4/tovary_17/',1,0,1,'','','',55,56,3,0,''),(336,'Товары 18','tovary_18','/tovary_5/tovary_18/',1,0,1,'','','',59,60,3,0,''),(337,'Товары 19','tovary_19','/tovary_6/tovary_19/',1,0,1,'','','',63,64,3,0,''),(338,'Товары 20','tovary_20','/tovary_20/',1,0,1,'','','',66,67,2,0,''),(339,'Товары 21','tovary_21','/tovary_1/tovary_8/tovary_21/',1,0,1,'','','',6,7,4,0,''),(340,'Товары 22','tovary_22','/tovary_1/tovary_8/tovary_22/',1,0,1,'','','',8,9,4,0,''),(341,'Товары 23','tovary_23','/tovary_1/tovary_8/tovary_23/',1,0,1,'','','',10,11,4,0,''),(342,'Товары 25','tovary_25','/tovary_1/tovary_8/tovary_25/',1,0,1,'','','',12,13,4,0,''),(343,'Товары 26','tovary_26','/tovary_1/tovary_9/tovary_26/',1,0,1,'','','',16,17,4,0,''),(344,'Товары 27','tovary_27','/tovary_1/tovary_9/tovary_27/',1,0,1,'','','',18,19,4,0,''),(345,'Товары 28','tovary_28','/tovary_1/tovary_10/tovary_28/',1,0,1,'','','',22,23,4,0,''),(346,'Товары 29','tovary_29','/tovary_1/tovary_10/tovary_29/',1,0,1,'','','',24,25,4,0,''),(347,'Товары 30','tovary_30','/tovary_2/tovary_12/tovary_30/',1,0,1,'','','',32,33,4,0,''),(348,'Товары 31','tovary_31','/tovary_2/tovary_12/tovary_31/',1,0,1,'','','',34,35,4,0,''),(349,'Товары 32','tovary_32','/tovary_3/tovary_15/tovary_32/',1,0,1,'','','',44,45,4,0,''),(350,'Товары 33','tovary_33','/tovary_3/tovary_15/tovary_33/',1,0,1,'','','',46,47,4,0,''),(351,'Товары 33','tovary_330','/tovary_3/tovary_15/tovary_330/',1,0,1,'','','',48,49,4,0,''),(355,'Популярные товары','populyarnye_tovary','/populyarnye_tovary/',1,0,0,'','','',68,69,2,0,'');
/*!40000 ALTER TABLE `m2_category` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-11-23 21:30:54
