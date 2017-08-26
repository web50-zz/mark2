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



LOCK TABLES `m2_chars_types` WRITE;
/*!40000 ALTER TABLE `m2_chars_types` DISABLE KEYS */;
INSERT INTO `m2_chars_types` VALUES (1,'root','','',0,0,1,'','','',1,34,1,0,0),(120,'Размеры','razmery','razmery',1,0,1,'','','',2,11,2,0,0),(121,'Длина','dlina','razmery/dlina',1,0,1,'','','',3,4,3,0,0),(122,'Ширина','shirina','razmery/shirina',1,0,1,'','','',5,6,3,0,0),(123,'Высота','vysota','razmery/vysota',1,0,1,'','','',7,8,3,0,0),(124,'Толщина стенки ','tolschina_stenki_','razmery/tolschina_stenki_',1,0,1,'','','',9,10,3,0,0),(125,'Вес','ves','ves',1,0,1,'','','',12,13,2,0,0),(140,'Статусы','statusy','statusy',1,0,1,'','','',14,19,2,0,1),(141,'Есть в наличии','est_v_nalichii','statusy/est_v_nalichii',2,0,1,'','','',15,16,3,0,0),(142,'Под заказ','pod_zakaz','statusy/pod_zakaz',2,0,1,'','','',17,18,3,0,0),(143,'Экспорт в Яндекс','eksport_v_yandeks','eksport_v_yandeks',1,0,1,'','','',20,23,2,0,1),(144,'Не экспортировать','ne_eksportirovat','eksport_v_yandeks/ne_eksportirovat',2,0,1,'','','',21,22,3,0,0),(145,'Оплата','oplata','oplata',1,0,1,'','','',24,29,2,0,1),(146,'Полная предоплата','trebuetsya_polnaya_predoplata','oplata/trebuetsya_polnaya_predoplata',2,0,1,'','','',25,26,3,0,0),(147,'Доставка','dostavka','dostavka',1,0,1,'','','',30,33,2,0,1),(148,'Самовывоз','samovyvoz','dostavka/samovyvoz',2,0,1,'','','',31,32,3,0,0),(149,'Наличными при получении','nalichnymi_pri_poluchenii','oplata/nalichnymi_pri_poluchenii',2,0,1,'','','',27,28,3,0,0);

/*!40000 ALTER TABLE `m2_chars_types` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-11-23 21:30:54
