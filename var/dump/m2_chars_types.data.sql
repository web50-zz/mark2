-- MySQL dump 10.13  Distrib 5.1.63, for debian-linux-gnu (i486)
--
-- Host: localhost    Database: mrk2_u9_ru
-- ------------------------------------------------------
-- Server version	5.1.63-0+squeeze1

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
-- Dumping data for table `m2_chars_types`
--

LOCK TABLES `m2_chars_types` WRITE;
/*!40000 ALTER TABLE `m2_chars_types` DISABLE KEYS */;
INSERT INTO `m2_chars_types` VALUES (1,'root','','',0,0,1,'','','',1,64,1,0,0),(2,'Канализация  два','kanalizaciya_dva','kanalizaciya_dva',1,0,1,'','','',2,23,2,2,0),(3,'Канализация','kanalizatsiya','kanalizatsiya',1,0,1,'Канализация загородного дома, выполненная при помощи септика – это система, состоящая из двух элементов: отстойник (собственно сам септик) и устройства почвенной фильтрации. Отстойник состоит чаще всего из 2-3 камер, соединённых между собой через систему гидрозатворов.<br><br>Современные системы канализации для загородного дома можно разделить на 3 категории: <br><br><br>- накопительная емкость<br>- септик <br>- станция биологической очистки','','',24,45,2,4,0),(7,'Монтаж в почву с плохой впитываемостью','montazh_v_pochvu_s_plohoy_vpityv','kanalizatsiya/montazh_v_pochvu_s_plohoy_vpityv',1,0,1,'<br>','<p>\n	<img alt=\"\" src=\"/files/?id=34\" style=\"width: 680px; height: 3220px; \" /></p>\n<p>\n	&nbsp;</p>\n','',25,26,3,0,0),(73,'Монтаж в условиях высокого уровня грунтовых вод','1','kanalizatsiya/1',1,0,1,'','','',27,28,3,0,0),(74,'werwerwer','12','kanalizatsiya/12',1,0,1,'','','',29,30,3,0,0),(75,'Повторное использование воды','1','kanalizatsiya/1',1,0,1,'','','',31,32,3,0,0),(76,'Запах','1','kanalizatsiya/1',1,0,1,'','','',33,34,3,0,0),(77,'Стоимость обслуживания','1','kanalizatsiya/1',1,0,1,'','','',35,36,3,0,0),(78,'Соотношение цена качество','1','kanalizatsiya/1',1,0,1,'','','',37,38,3,0,0),(79,'Энерго зависимость','1','kanalizatsiya/1',1,0,1,'ertert','','',39,40,3,0,0),(25,'Прочность  корпуса (при подвижке  грунта)','zd','kanalizaciya_dva/zd',1,0,1,'<br>','<p>\n	&nbsp;</p>\n<p>\n	&nbsp;</p>\n<p>\n	&nbsp;</p>\n<p>\n	&nbsp;</p>\n<p>\n	&nbsp;</p>\n<p>\n	&nbsp;</p>\n<p>\n	&nbsp;</p>\n<p>\n	&nbsp;</p>\n','',3,4,3,0,0),(26,'Удобство  монтажа','Удобство  монтажа','kanalizaciya_dva/Удобство  монтажа',1,0,1,'Канализация загородного дома, выполненная при помощи септика – это система, состоящая из двух элементов: отстойник (собственно сам септик) и устройства почвенной фильтрации. Отстойник состоит чаще всего из 2-3 камер, соединённых между собой через систему гидрозатворов.<br><br>','<p>\n	<img alt=\"\" src=\"/files/?id=32\" /></p>\n<p>\n	&nbsp;</p>\n<p>\n	<img alt=\"\" src=\"/files/?id=31\" style=\"width: 680px; height: 484px; \" /></p>\n','',5,6,3,0,0),(80,'Вымывание бактерий при залповом сбросе','1','kanalizaciya_dva/1',1,0,1,'','','',7,8,3,0,0),(81,'Реакция на подтопление станции(при отключении электроэнергии','1','kanalizaciya_dva/1',1,0,1,'','','',9,10,3,0,0),(82,'Попадание в систему «инородных» предметов — памперсов, бумажных полотенец и др. ','1','kanalizaciya_dva/1',1,0,1,'','','',11,12,3,0,0),(83,'Чувстви- тельность  к химическому составу сточных вод','1','kanalizaciya_dva/1',1,0,1,'','','',13,14,3,0,0),(84,'Работа станции  при попадании жировых  отходов','1','kanalizaciya_dva/1',1,0,1,'','','',15,16,3,0,0),(85,'Популярность','1','kanalizaciya_dva/1',1,0,1,'','','',17,18,3,0,0),(86,'Соотношение  цена /качество','1','kanalizaciya_dva/1',1,0,1,'','','',19,20,3,0,0),(87,'Степень очистки стоков','1','kanalizaciya_dva/1',1,0,1,'','','',21,22,3,0,0),(88,'testese','test','kanalizatsiya/test',1,0,1,'etset','','',41,42,3,0,0),(89,'werwer','werwe','kanalizatsiya/werwe',1,0,1,'rwerwerwer','','',43,44,3,0,0),(94,'ecwerververver5656','ecwerververver5656e','ecwerververver5656e',1,0,1,'werwerwe','','',46,49,2,0,0),(95,'Размер одежды','size','size',1,0,1,'werwerwerwerw','','',50,63,2,0,1),(97,'XL','xl','size/xl',2,0,1,'','','',51,52,3,0,0),(98,'XXL','xxl','size/xxl',2,0,1,'','','',53,54,3,0,0),(99,'erter','ertert','size/ertert',2,0,1,'ertert','','',55,56,3,0,0),(100,'S','s','size/s',2,0,1,'','','',57,58,3,0,0),(101,'L','l','size/l',2,0,1,'','','',59,60,3,0,0),(102,'S','s0','size/s0',2,0,1,'','','',61,62,3,0,0),(103,'rtyrt','yrty','ecwerververver5656e/yrty',1,0,1,'rtyrty','','',47,48,3,0,0);
/*!40000 ALTER TABLE `m2_chars_types` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-01-18  2:27:01
