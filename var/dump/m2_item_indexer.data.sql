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
-- Dumping data for table `m2_item_indexer`
--

LOCK TABLES `m2_item_indexer` WRITE;
/*!40000 ALTER TABLE `m2_item_indexer` DISABLE KEYS */;
INSERT INTO `m2_item_indexer` VALUES (1219,1286,'Товар1','tovar1',1,'123',0,'[{\"real_name\":\"dd090ba030d6e1a9490bb5488676583e.jpg\",\"id\":\"2681\",\"file_type\":\"3\",\"order\":\"11\",\"type\":\"image/jpeg\"},{\"real_name\":\"fb7044ab62858575f59e86801bfac6d5.jpg\",\"id\":\"2682\",\"file_type\":\"3\",\"order\":\"12\",\"type\":\"image/jpeg\"},{\"real_name\":\"a4e7a3431fbdaf35591aa8e1b1059263.jpg\",\"id\":\"2683\",\"file_type\":\"3\",\"order\":\"13\",\"type\":\"image/jpeg\"},{\"real_name\":\"b937f29b9089e1608591e5c5179d41b5.jpg\",\"id\":\"2684\",\"file_type\":\"3\",\"order\":\"14\",\"type\":\"image/jpeg\"},{\"real_name\":\"9c110d4d751dbb5049c9233c6e0ccb16.jpg\",\"id\":\"2685\",\"file_type\":\"3\",\"order\":\"15\",\"type\":\"image/jpeg\"}]','[{\"title\":\"Краткое описание\",\"content\":\"<p>Текст описания товара должен быть не&nbsp;только убедительным, но&nbsp;и&nbsp;красиво оформленным.</p><p>&nbsp;</p>\",\"type\":\"5\"}]','[{\"type\":\"7\",\"price_value\":\"70000.00\",\"content\":\"\",\"currency\":\"3\"}]','[]','[]','[{\"category_id\":\"339\",\"title\":\"Товары 21\",\"uri\":\"/tovary_1/tovary_8/tovary_21/\"},{\"category_id\":\"329\",\"title\":\"Товары 11\",\"uri\":\"/tovary_2/tovary_11/\"},{\"category_id\":\"349\",\"title\":\"Товары 32\",\"uri\":\"/tovary_3/tovary_15/tovary_32/\"},{\"category_id\":\"355\",\"title\":\"Популярные товары\",\"uri\":\"/populyarnye_tovary/\"}]','[{\"type\":\"3\",\"linked_item_id\":\"1287\",\"order\":\"0\",\"title\":\"Товар 2\"},{\"type\":\"3\",\"linked_item_id\":\"1291\",\"order\":\"1\",\"title\":\"Товар 6\"},{\"type\":\"3\",\"linked_item_id\":\"1292\",\"order\":\"2\",\"title\":\"Товар 7\"},{\"type\":\"2\",\"linked_item_id\":\"1290\",\"order\":\"3\",\"title\":\"Товар 5\"},{\"type\":\"2\",\"linked_item_id\":\"1287\",\"order\":\"4\",\"title\":\"Товар 2\"},{\"type\":\"2\",\"linked_item_id\":\"1288\",\"order\":\"5\",\"title\":\"Товар 3\"},{\"type\":\"2\",\"linked_item_id\":\"1289\",\"order\":\"6\",\"title\":\"Товар 4\"},{\"type\":\"2\",\"linked_item_id\":\"1292\",\"order\":\"7\",\"title\":\"Товар 7\"}]','2016-11-23 19:36:05',''),(1220,1287,'Товар 2','tovar_2',2,'12345',0,'[{\"real_name\":\"fe93a3a58b7c5444cb6f5bd76eb6eb0f.jpg\",\"id\":\"2671\",\"file_type\":\"3\",\"order\":\"5\",\"type\":\"image/jpeg\"}]','[]','[{\"type\":\"7\",\"price_value\":\"35000.00\",\"content\":\"\",\"currency\":\"3\"}]','[]','[]','[{\"category_id\":\"339\",\"title\":\"Товары 21\",\"uri\":\"/tovary_1/tovary_8/tovary_21/\"}]','[{\"type\":\"3\",\"linked_item_id\":\"1292\",\"order\":\"0\",\"title\":\"Товар 7\"},{\"type\":\"3\",\"linked_item_id\":\"1290\",\"order\":\"1\",\"title\":\"Товар 5\"},{\"type\":\"3\",\"linked_item_id\":\"1289\",\"order\":\"2\",\"title\":\"Товар 4\"},{\"type\":\"3\",\"linked_item_id\":\"1288\",\"order\":\"3\",\"title\":\"Товар 3\"}]','2016-11-23 19:36:09',''),(1221,1288,'Товар 3','tovar_3',3,'3',0,'[{\"real_name\":\"883159154d901b1a31853ef11ca60b0c.jpg\",\"id\":\"2672\",\"file_type\":\"3\",\"order\":\"6\",\"type\":\"image/jpeg\"}]','[]','[{\"type\":\"7\",\"price_value\":\"5000.00\",\"content\":\"\",\"currency\":\"3\"}]','[]','[]','[{\"category_id\":\"339\",\"title\":\"Товары 21\",\"uri\":\"/tovary_1/tovary_8/tovary_21/\"},{\"category_id\":\"355\",\"title\":\"Популярные товары\",\"uri\":\"/populyarnye_tovary/\"}]','[]','2016-11-23 19:35:47',''),(1222,1289,'Товар 4','tovar_4',4,'4',0,'[{\"real_name\":\"235c66c16056578a3b23fdd444ea2d76.jpeg\",\"id\":\"2673\",\"file_type\":\"3\",\"order\":\"7\",\"type\":\"image/jpeg\"}]','[]','[{\"type\":\"7\",\"price_value\":\"18500.00\",\"content\":\"\",\"currency\":\"3\"}]','[{\"manufacturer_id\":\"2\",\"title\":\"IBM\",\"name\":\"ibm\"}]','[]','[{\"category_id\":\"339\",\"title\":\"Товары 21\",\"uri\":\"/tovary_1/tovary_8/tovary_21/\"},{\"category_id\":\"355\",\"title\":\"Популярные товары\",\"uri\":\"/populyarnye_tovary/\"}]','[]','2016-11-23 20:05:30',''),(1223,1290,'Товар 5','tovar_5',5,'5',0,'[{\"real_name\":\"bc1e6214f39a8382720798d361c94b24.jpg\",\"id\":\"2674\",\"file_type\":\"3\",\"order\":\"8\",\"type\":\"image/jpeg\"}]','[]','[{\"type\":\"7\",\"price_value\":\"0.00\",\"content\":\"9350\",\"currency\":\"3\"}]','[]','[]','[{\"category_id\":\"339\",\"title\":\"Товары 21\",\"uri\":\"/tovary_1/tovary_8/tovary_21/\"}]','[{\"type\":\"1\",\"linked_item_id\":\"1286\",\"order\":\"0\",\"title\":\"Товар1\"}]','2016-11-23 19:35:54',''),(1224,1291,'Товар 6','tovar_6',6,'6',0,'[]','[]','[{\"type\":\"7\",\"price_value\":\"1870.00\",\"content\":\"\",\"currency\":\"3\"}]','[{\"manufacturer_id\":\"3\",\"title\":\"Motorola\",\"name\":\"motorola\"}]','[]','[{\"category_id\":\"339\",\"title\":\"Товары 21\",\"uri\":\"/tovary_1/tovary_8/tovary_21/\"}]','[]','2016-11-23 20:05:42',''),(1225,1292,'Товар 7','tovar_7',0,'7',0,'[{\"real_name\":\"55a47bcf90c33211dc60c58277fb6e40.png\",\"id\":\"2676\",\"file_type\":\"3\",\"order\":\"10\",\"type\":\"image/png\"}]','[]','[{\"type\":\"7\",\"price_value\":\"16320.00\",\"content\":\"\",\"currency\":\"3\"}]','[{\"manufacturer_id\":\"2\",\"title\":\"IBM\",\"name\":\"ibm\"}]','[]','[{\"category_id\":\"339\",\"title\":\"Товары 21\",\"uri\":\"/tovary_1/tovary_8/tovary_21/\"},{\"category_id\":\"355\",\"title\":\"Популярные товары\",\"uri\":\"/populyarnye_tovary/\"}]','[]','2016-11-23 20:05:22','');
/*!40000 ALTER TABLE `m2_item_indexer` ENABLE KEYS */;
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
