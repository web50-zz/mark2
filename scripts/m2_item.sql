-- MySQL dump 10.13  Distrib 5.1.63, for debian-linux-gnu (i486)
--
-- Host: localhost    Database: avic_u9_ru
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
-- Table structure for table `m2_item`
--

DROP TABLE IF EXISTS `m2_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m2_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` varchar(20) NOT NULL DEFAULT '0',
  `price2` varchar(20) NOT NULL DEFAULT '0',
  `not_available` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `article` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order` (`order`)
) ENGINE=MyISAM AUTO_INCREMENT=1217 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m2_item`
--

LOCK TABLES `m2_item` WRITE;
/*!40000 ALTER TABLE `m2_item` DISABLE KEYS */;
INSERT INTO `m2_item` VALUES (1068,400,'524','КУРТКА мужская','0','0',0,'7.25A'),(1067,399,'523','КУРТКА мужская','0','0',0,'7.28'),(1066,398,'522','КУРТКА мужская','0','0',0,'7.38'),(1061,393,'517','КАРМАН навесной','0','0',0,'10.53'),(1062,394,'518','ПОЯС-КАРМАН','0','0',0,'10.52'),(1063,395,'519','САРАФАН','0','0',0,'5.40'),(1064,396,'520','САРАФАН','0','0',0,'1.39'),(1065,397,'521','КУРТКА мужская','0','0',0,'7.61'),(1060,392,'516','РЮКЗАК','0','0',0,'10.48В'),(1059,391,'515','РЮКЗАК','0','0',0,'10.48A'),(1058,390,'514','СУМКА рекламного агента','0','0',0,'10.18'),(1057,389,'513','СУМКА рекламного агента','0','0',0,'10.12'),(1056,388,'512','СУМКА рекламного агента','0','0',0,'10.19a'),(1055,387,'511','СУМКА рекламного агента','0','0',0,'10.21'),(1054,386,'510','СУМКА рекламного агента','0','0',0,'10.56B'),(1053,385,'509','НАКИДКА рекламная','0','0',0,'5.67'),(1052,384,'508','ЖИЛЕТ утепленный','0','0',0,'6.45-6.44'),(1051,383,'507','САРАФАН распашной','0','0',0,'5.65'),(1050,382,'505','БРЮКИ с лампасами','0','0',0,'2.34-2.33'),(1049,381,'503','ЭПОЛЕТЫ','0','0',0,'10.60'),(1048,380,'502','ТРЕУГОЛКА','0','0',0,'9.53'),(1047,379,'501','ЖИЛЕТ уни','0','0',0,'6.56'),(1046,378,'499','ЖАКЕТ женский','0','0',0,'0.43'),(1045,377,'498','ПИДЖАК мужской','0','0',0,'0.42'),(1044,376,'497','ПИДЖАК мужской','0','0',0,'0.16'),(1043,375,'496','ПАЛЬТО швейцара','0','0',0,'0.30'),(1042,374,'495','БРЮКИ женские','0','0',0,'2.10A'),(1041,373,'494','БРЮКИ женские','0','0',0,'2.07Ж-D'),(1040,372,'493','БРЮКИ мужские','0','0',0,'2.23A'),(1039,371,'492','ПОЛУКОМБИНЕЗОН женский','0','0',0,'8.24'),(1038,370,'491','ПОЛУКОМБИНЕЗОН мужской','0','0',0,'8.18'),(1037,369,'490','БРЮКИ мужские','0','0',0,'2.07М-D'),(1036,368,'489','БРЮКИ мужские','0','0',0,'2.20A'),(1035,367,'488','БРИДЖИ женские','0','0',0,'2.19A'),(1034,366,'487','ХАЛАТ мужской','0','0',0,'1.14'),(1033,365,'486','ПЕРЕДНИК женский','0','0',0,'5.82'),(1032,364,'485','ТУНИКА женская','0','0',0,'4B.68A'),(1031,363,'484','САРАФАН','0','0',0,'1.38'),(1030,362,'483','ХАЛАТ мужской','0','0',0,'1.05B'),(1029,361,'482','ХАЛАТ мужской','0','0',0,'1.05A'),(1028,360,'481','ХАЛАТ женский','0','0',0,'1.21A-1'),(1027,359,'480','ХАЛАТ женский','0','0',0,'1.21C'),(1026,358,'479','ХАЛАТ женский','0','0',0,'1.18D'),(1025,357,'478','ХАЛАТ женский','0','0',0,'1.18C-1'),(1024,356,'477','ХАЛАТ женский','0','0',0,'1.18B'),(1023,355,'476','ХАЛАТ женский','0','0',0,'1.15'),(1022,354,'475','ХАЛАТ женский','0','0',0,'1.16'),(1021,353,'474','ПЕРЕДНИК женский','0','0',0,'5.28A'),(1020,352,'473','ПЛАТЬЕ','0','0',0,'1.04'),(1019,351,'472','ПЕРЕДНИК женский','0','0',0,'5.12'),(1018,350,'471','ПЛАТЬЕ','0','0',0,'1.30A'),(1017,349,'470','ТУНИКА мужская','0','0',0,'4B.122A'),(1016,348,'469','ТУНИКА мужская','0','0',0,'4B.156'),(1015,347,'468','БРЮКИ женские','0','0',0,'2.60'),(1014,346,'467','ТУНИКА женская','0','0',0,'4B.191'),(1013,345,'466','ТУНИКА женская','0','0',0,'4B.111A'),(1012,344,'465','ТУНИКА женская','0','0',0,'4B.53'),(1011,343,'463','ТУНИКА мужская','0','0',0,'4B.32M'),(1010,342,'462','ТУНИКА женская','0','0',0,'4B.17'),(1009,341,'461','ТУНИКА женская','0','0',0,'4B.128'),(1008,340,'460','ТУНИКА женская','0','0',0,'4A.157'),(1007,339,'459','ТУНИКА женская','0','0',0,'4B.32C'),(1006,338,'458','ТУНИКА женская','0','0',0,'4B.33Ж'),(1005,337,'457','ЖАКЕТ-туника','0','0',0,'4B.129'),(1004,336,'456','ТУНИКА женская','0','0',0,'4B.20'),(1003,335,'455','ТУНИКА женская','0','0',0,'4B.67A'),(1002,334,'454','ТУНИКА женская','0','0',0,'4B.40С'),(1001,333,'453','ТУНИКА женская','0','0',0,'4B.119A'),(1000,332,'452','ТУНИКА женская','0','0',0,'4B.150'),(999,331,'450','РУБАШКА мужская','0','0',0,'4A.178'),(998,330,'449','ТУНИКА женская','0','0',0,'4B.98B'),(997,329,'448','КАПРИ женские','0','0',0,'2.32'),(996,328,'447','НАКОЛКА','0','0',0,'9.08A'),(995,327,'445','ФАРТУК женский','0','0',0,'5.121'),(994,326,'444','ПЕРЕДНИК уни','0','0',0,'5.04'),(993,325,'442','ФУТБОЛКА мужская','0','0',0,'4A.139B'),(992,324,'441','ФУТБОЛКА женская','0','0',0,'4A.140B'),(991,323,'440','ПЕРЕДНИК уни','0','0',0,'5.55A'),(990,322,'439','ПЕРЕДНИК уни','0','0',0,'5.55C'),(989,321,'438','РУБАШКА поло','0','0',0,'4B.123A'),(988,320,'437','КУРТКА ПОВАРСКАЯ мужская','0','0',0,'4С.14В-1'),(987,319,'436','ЮБКА женская','0','0',0,'3.03A'),(986,318,'435','ФУТБОЛКА','0','0',0,'4A.139E'),(985,317,'434','ФУТБОЛКА','0','0',0,'4A.140E'),(984,316,'433','ТУНИКА мужская/женская','0','0',0,'4B.263М, 4B.263Ж'),(983,315,'432','КИМОНО уни','0','0',0,'4B.87'),(982,314,'431','ПЕРЕДНИК уни','0','0',0,'5.80'),(981,313,'430','ТУНИКА женская','0','0',0,'4B.82A'),(980,312,'429','ПЕРЕДНИК','0','0',0,'5.89'),(979,311,'428','ДЖИНСЫ стилизованные','0','0',0,'2.50'),(978,310,'427','СОРОЧКА мужская','0','0',0,'4A.78H'),(977,309,'426','ЮБКА колокол','0','0',0,'3.22B-2'),(976,308,'425','ЮБКА 1/4 солнце','0','0',0,'3.22B'),(975,307,'424','ЮБКА женская','0','0',0,'3.25'),(974,306,'423','ЮБКА солнце','0','0',0,'3.34'),(973,305,'422','БЛУЗА женская','0','0',0,'4A.136'),(972,304,'421','ПЛАТЬЕ-ШОРТЫ','0','0',0,'2.42'),(971,303,'420','БЛУЗА трикотажная','0','0',0,'4A.130A'),(970,302,'419','ЮБКА укороченная','0','0',0,'3.11A'),(969,301,'418','БЛУЗА женская','0','0',0,'4A.74B'),(968,300,'417','КОРСЕТ на косточках','0','0',0,'6A.29B'),(967,299,'416','БЛУЗА женская','0','0',0,'4A.109'),(966,298,'415','ЖИЛЕТ женский','0','0',0,'6A.07'),(965,297,'414','ЖИЛЕТ мужской','0','0',0,'6A.10'),(964,296,'413','ПЕРЕДНИК с воланом','0','0',0,'5.58A'),(963,295,'412','ГАЛСТУК женский','0','0',0,'10.64'),(962,294,'411','ЖИЛЕТ мужской','0','0',0,'6A.08'),(961,293,'410','КИТЕЛЬ банкетный','0','0',0,'0.09D-2'),(960,292,'409','КИТЕЛЬ банкетный','0','0',0,'0.51'),(959,291,'407','КИТЕЛЬ банкетный','0','0',0,'0.09A'),(958,290,'406','КИТЕЛЬ банкетный','0','0',0,'0.50'),(957,289,'405','КИТЕЛЬ банкетный','0','0',0,'0.10A-1'),(956,288,'404','КИТЕЛЬ банкетный','0','0',0,'0.10A'),(955,287,'403','КИТЕЛЬ банкетный','0','0',0,'0.10D-2'),(954,286,'401','Перчатки','0','0',0,'11.16h'),(953,285,'400','КИТЕЛЬ банкетный','0','0',0,'0.10'),(952,284,'399','ЭПОЛЕТЫ','0','0',0,'10.29'),(951,283,'398','ПОГОНЫ витые','0','0',0,'10.49'),(950,282,'397','ГАЛСТУК мужской','0','0',0,'10.23'),(949,281,'396','ГАЛСТУК женский','0','0',0,'10.24'),(948,280,'395','ГАЛСТУК женский','0','0',0,'10.05'),(947,279,'394','БЛУЗА женская','0','0',0,'4A.55A'),(946,278,'393','БЛУЗА женская','0','0',0,'4A.167'),(945,277,'392','ФУТБОЛКА женская','0','0',0,'4.220B'),(944,276,'391','Юбка без пояса','0','0',0,'3.48'),(943,275,'390','Юбка прямая','0','0',0,'3.56'),(942,274,'389','Юбка прямая','0','0',0,'3.49'),(941,273,'388','ЮБКА без пояса','0','0',0,'3.10'),(940,272,'387','Юбка укороченная','0','0',0,'3.04'),(939,271,'386','ЮБКА классическая','0','0',0,'3.02'),(938,270,'385','ЮБКА классическая','0','0',0,'3.01'),(937,269,'384','БРЮКИ мужские','0','0',0,'2.45'),(936,268,'383','БРЮКИ мужские','0','0',0,'2.06'),(935,267,'382','БРЮКИ женские','0','0',0,'2.49'),(934,266,'381','БРЮКИ женские','0','0',0,'2.03'),(933,265,'377','БЛУЗА женская','0','0',0,'4A.49'),(932,264,'376','БЛУЗА женская','0','0',0,'4A.49F'),(931,263,'375','БЛУЗА женская','0','0',0,'4A.49G'),(930,262,'374','БЛУЗА женская','0','0',0,'4A.05С'),(929,261,'373','БЛУЗА женская','0','0',0,'4A.07С'),(928,260,'372','БЛУЗА женская','0','0',0,'4A.89C'),(927,259,'369','СОРОЧКА мужская','0','0',0,'4A.48D-2'),(926,258,'368','СОРОЧКА мужская','0','0',0,'4A.48E'),(925,257,'367','СОРОЧКА мужская','0','0',0,'4A.48D'),(924,256,'366','СОРОЧКА мужская','0','0',0,'4A.48C'),(923,255,'365','БЛУЗА женская','0','0',0,'4A.490R'),(922,254,'364','БЛУЗА женская','0','0',0,'4A.490S'),(921,253,'363','БЛУЗА женская','0','0',0,'4A.490N'),(920,252,'362','БЛУЗА женская','0','0',0,'4A.490M'),(919,251,'361','БЛУЗА женская','0','0',0,'4A.490K'),(918,250,'360','БЛУЗА женская','0','0',0,'4A.490H-2'),(917,249,'358','БЛУЗА женская','0','0',0,'4A.490H'),(916,248,'357','БЛУЗА женская','0','0',0,'4A.490B'),(915,247,'356','БЛУЗА женская','0','0',0,'4A.490A'),(914,246,'355','БЛУЗА женская','0','0',0,'4A.490G-2'),(913,245,'354','БЛУЗА женская','0','0',0,'4A.490G'),(912,244,'353','БЛУЗА женская','0','0',0,'4A.490-E-2'),(911,243,'352','ЖИЛЕТ женский','0','0',0,'11.01A'),(910,242,'351','ЖИЛЕТ мужской','0','0',0,'11.01A'),(909,241,'350','ЖИЛЕТ мужской','0','0',0,'6А.22М'),(908,240,'349','ЖИЛЕТ мужской','0','0',0,'6А.23A-2'),(907,239,'348','ЖИЛЕТ мужской','0','0',0,'6А.23A'),(906,238,'347','ЖИЛЕТ женский','0','0',0,'6A.107'),(905,237,'344','ЖИЛЕТ женский','0','0',0,'6А.68'),(904,236,'343','ЖИЛЕТ женский','0','0',0,'6А.24B'),(903,235,'342','ЖИЛЕТ женский','0','0',0,'6А.24С'),(902,234,'341','ЖИЛЕТ женский','0','0',0,'6А.24A'),(901,233,'340','ЖИЛЕТ женский','0','0',0,'6A.01E-2'),(900,232,'339','ЖИЛЕТ женский','0','0',0,'6A.01E'),(899,231,'338','ЖИЛЕТ женский','0','0',0,'6А.01В'),(898,230,'336','ЖАКЕТ женский','0','0',0,'0.04'),(897,229,'335','ПИДЖАК мужской','0','0',0,'0.17'),(896,228,'334','КУРТКА ПОВАРСКАЯ женская','0','0',0,'4C.38D'),(895,227,'330','КУРТКА ПОВАРСКАЯ мужская','0','0',0,'4C.78B'),(894,226,'329','КУРТКА ПОВАРСКАЯ мужская','0','0',0,'4C.199'),(893,225,'328','КУРТКА ПОВАРСКАЯ мужская','0','0',0,'4C.51E'),(892,224,'327','КУРТКА ПОВАРСКАЯ мужская','0','0',0,'4C.51B'),(891,223,'324','КУРТКА ПОВАРСКАЯ мужская','0','0',0,'4C.14E'),(890,222,'323','КУРТКА ПОВАРСКАЯ мужская','0','0',0,'4C.14F'),(889,221,'322','КИМОНО','0','0',0,'4B.14D-1'),(888,220,'320','КУРТКА ПОВАРСКАЯ мужская','0','0',0,'4C.14'),(887,219,'319','КУРТКА ПОВАРСКАЯ мужская','0','0',0,'4C.12Э'),(886,218,'318','КУРТКА ПОВАРСКАЯ мужская','0','0',0,'4C.12K'),(885,217,'316','КУРТКА ПОВАРСКАЯ мужская','0','0',0,'4C.12G'),(884,216,'311','КУРТКА ПОВАРСКАЯ мужская','0','0',0,'4C.12A'),(883,215,'309','ПОЛУКОМБИНЕЗОН мужской','0','0',0,'8.12'),(882,214,'308','ПОЛУКОМБИНЕЗОН мужской','0','0',0,'8.02'),(881,213,'305','ПЕРЕДНИК женский','0','0',0,'5.110B'),(880,212,'304','ПЕРЕДНИК женский','0','0',0,'5.110'),(879,211,'303','ФАРТУК мужской','0','0',0,'5.144'),(878,210,'302','ФАРТУК женский','0','0',0,'5.143'),(877,209,'300','ФАРТУК женский','0','0',0,'5.96Ж'),(876,208,'298','ФАРТУК мужской','0','0',0,'5.96М'),(875,207,'296','НАКИДКА женская','0','0',0,'5.87B-2'),(874,206,'295','ПЛАТЬЕ-фартук','0','0',0,'5.91'),(873,205,'294','ФАРТУК женский','0','0',0,'5.131A'),(872,204,'293','ФАРТУК уни','0','0',0,'5.69'),(871,203,'292','ФАРТУК женский','0','0',0,'5.174'),(870,202,'291','ФАРТУК женский','0','0',0,'5.35'),(869,201,'290','ФАРТУК уни','0','0',0,'5.77'),(868,200,'289','ФАРТУК уни','0','0',0,'5.70C'),(867,199,'288','ФАРТУК уни','0','0',0,'5.70B'),(866,198,'286','ФАРТУК уни','0','0',0,'5.70'),(865,197,'285','ФАРТУК мужской','0','0',0,'5.101B'),(864,196,'284','ФАРТУК мужской','0','0',0,'5.101'),(863,195,'283','ФАРТУК женский','0','0',0,'5.107A'),(862,194,'282','ФАРТУК женский','0','0',0,'5.107'),(861,193,'281','ПЕРЕДНИК мужской','0','0',0,'5.94'),(860,192,'280','ПЕРЕДНИК женский','0','0',0,'5.104'),(859,191,'279','ПЕРЕДНИК женский','0','0',0,'5.108A'),(858,190,'278','ПЕРЕДНИК уни','0','0',0,'5.29'),(857,189,'272','ПЕРЕДНИК уни','0','0',0,'5.04G-1'),(856,188,'271','ПЕРЕДНИК уни','0','0',0,'5.04F'),(855,187,'269','ПЕРЕДНИК уни','0','0',0,'5.04C-1'),(854,186,'267','ПЕРЕДНИК уни','0','0',0,'5.04A-2'),(853,185,'262','ФАРТУК уни','0','0',0,'5.03'),(852,184,'260','ЖИЛЕТ стеганый женский','0','0',0,'6B.92'),(851,183,'259','ЖИЛЕТ утепленный женский','0','0',0,'6B.98A'),(850,182,'258','ЖИЛЕТ стеганый женский','0','0',0,'6B.14B-Ж-2'),(849,181,'257','ЖИЛЕТ стеганый мужской','0','0',0,'6B.73A'),(848,180,'256','ЖИЛЕТ мужской','0','0',0,'6B.52'),(847,179,'255','ЖИЛЕТ стеганый мужской','0','0',0,'6B.76'),(846,178,'254','ЖИЛЕТ стеганый мужской','0','0',0,'6B.75'),(845,177,'253','ЖИЛЕТ мужской','0','0',0,'6B.15'),(844,176,'252','ЖИЛЕТ стеганый женский','0','0',0,'6B.18D'),(843,175,'250','ЖИЛЕТ стеганый женский','0','0',0,'6B.18B-6B.18С'),(842,174,'249','ЖИЛЕТ стеганый женский','0','0',0,'6B.18A'),(841,173,'248','ЖИЛЕТ стеганый женский','0','0',0,'6B.18'),(840,172,'247','ЖИЛЕТ стеганый женский','0','0',0,'6B.25D'),(839,171,'245','ЖИЛЕТ стеганый женский','0','0',0,'6B.25B-6B.25C'),(838,170,'244','ЖИЛЕТ стеганый женский','0','0',0,'6B.25A'),(837,169,'243','ЖИЛЕТ стеганый женский','0','0',0,'6B.25'),(836,168,'241','ЖИЛЕТ стеганый мужской','0','0',0,'6B.12B-6B.12С'),(835,167,'240','ЖИЛЕТ стеганый мужской','0','0',0,'6B.12A'),(834,166,'239','ЖИЛЕТ стеганый мужской','0','0',0,'6B.12'),(833,165,'238','ЖИЛЕТ стеганый мужской','0','0',0,'6B.26D'),(832,164,'236','ЖИЛЕТ стеганый мужской','0','0',0,'6B.26B -6B.26C'),(831,163,'235','ЖИЛЕТ стеганый мужской','0','0',0,'6B.26A'),(830,162,'234','ЖИЛЕТ стеганый мужской','0','0',0,'6B.26'),(829,161,'233','КУРТКА женская','0','0',0,'7.81Ж'),(828,160,'232','КУРТКА толстовка','0','0',0,'1555'),(827,159,'231','КУРТКА толстовка','0','0',0,'7.16M'),(826,158,'230','КУРТКА толстовка','0','0',0,'7.16Ж'),(825,157,'229','КУРТКА мужская','0','0',0,'7.39'),(824,156,'228','КУРТКА ветровка уни','0','0',0,'7.08C'),(823,155,'227','КУРТКА ветровка уни','0','0',0,'7.08A'),(822,154,'226','КУРТКА ветровка уни','0','0',0,'7.08'),(821,153,'225','КУРТКА мужская','0','0',0,'7.69'),(820,152,'224','КУРТКА мужская','0','0',0,'7.26'),(819,151,'223','КУРТКА мужская','0','0',0,'7.37'),(818,150,'222','КУРТКА мужская','0','0',0,'7.06H'),(817,149,'221','КУРТКА мужская','0','0',0,'7.06F'),(816,148,'220','КУРТКА мужская','0','0',0,'7.06D'),(815,147,'219','КУРТКА мужская','0','0',0,'7.06C'),(814,146,'218','КУРТКА мужская','0','0',0,'7.06B'),(813,145,'217','КУРТКА женская','0','0',0,'7.05Ж-F'),(812,144,'216','КУРТКА женская','0','0',0,'7.05Ж-A'),(811,143,'215','КУРТКА мужская','0','0',0,'7.05M-B'),(810,142,'213','КУРТКА мужская','0','0',0,'7.05M-A'),(809,141,'212','КУРТКА мужская','0','0',0,'7.05M'),(808,140,'211','КУРТКА мужская','0','0',0,'7.51C'),(807,139,'209','КУРТКА мужская','0','0',0,'7.51B-2'),(806,138,'208','КУРТКА мужская','0','0',0,'7.51A'),(805,137,'207','КУРТКА мужская','0','0',0,'7.51'),(804,136,'206','КУРТКА  мужская','0','0',0,'7.49'),(803,135,'205','КУРТКА мужская','0','0',0,'7.68'),(802,134,'204','КУРТКА женская','0','0',0,'7.01ж-С'),(801,133,'203','КУРТКА мужская','0','0',0,'7.01м'),(800,132,'202','КУРТКА  женская','0','0',0,'7.01ж'),(799,131,'194','ТУНИКА женская','0','0',0,'4B.151A'),(798,130,'168','КУРТКА мужская','0','0',0,'7.43A'),(797,129,'167','ПОЛУКОМБИНЕЗОН женский','0','0',0,'8.04'),(796,128,'166','ПОЛУКОМБИНЕЗОН мужской','0','0',0,'8.18A'),(795,127,'165','ЮБКА женская','0','0',0,'3.37'),(794,126,'164','ЮБКА в складку','0','0',0,'3.13'),(793,125,'163','ЖАКЕТ женский','0','0',0,'0.59'),(792,124,'161','БРЮКИ женские','0','0',0,'2.47'),(791,123,'159','ТУНИКА женская','0','0',0,'4B.191'),(790,122,'158','ЮБКА рабочая','0','0',0,'3.30'),(789,121,'157','НАКИДКА-жилет','0','0',0,'6B.71'),(788,120,'156','РУБАШКА-поло уни','0','0',0,'4B.123A'),(787,119,'155','РУБАШКА-поло женская','0','0',0,'11.34a'),(786,118,'154','РУБАШКА-поло','0','0',0,'11.35a'),(785,117,'153','РУБАШКА-поло уни','0','0',0,'11.32a'),(784,116,'152','ФУТБОЛКА женская','0','0',0,'11.28a'),(783,115,'151','ФУТБОЛКА женская','0','0',0,'11.24a'),(782,114,'150','ФУТБОЛКА мужская','0','0',0,'11.22a'),(781,113,'149','ФУТБОЛКА мужская','0','0',0,'11.20a'),(780,112,'148','БАНДАНА зимняя','0','0',0,'9.86'),(779,111,'147','ШАПКА ушанка','0','0',0,'9.47C'),(778,110,'145','КОЗЫРЕК широкий','0','0',0,'9.55'),(777,109,'144','ПИЛОТКА','0','0',0,'9.22'),(776,108,'143','ПИЛОТКА','0','0',0,'9.19'),(775,107,'142','ШАПОЧКА таблетка','0','0',0,'9.06'),(774,106,'141','БЕРЕТ уни','0','0',0,'9.70B'),(773,105,'139','ШАПОЧКА женская','0','0',0,'9.92'),(772,104,'138','ПАНАМА уни','0','0',0,'9.96'),(771,103,'137','НАКОЛКА','0','0',0,'9.79'),(770,102,'135','БАНДАНА уни','0','0',0,'9.71A'),(769,101,'134','БАНДАНА уни','0','0',0,'9.71'),(768,100,'133','БЕРЕТ повара','0','0',0,'9.04'),(767,99,'132','ШАПОЧКА повара','0','0',0,'9.88'),(766,98,'130','ШАПОЧКА повара','0','0',0,'9.50B'),(765,97,'129','БЕРЕТ повара','0','0',0,'9.01'),(764,96,'128','КОЛПАК повара','0','0',0,'9.02A'),(763,95,'127','БЕРЕТ повара','0','0',0,'9.05'),(762,94,'126','БЕРЕТ повара','0','0',0,'9.94'),(761,93,'125','БРЮКИ мужские','0','0',0,'2.45'),(760,92,'124','БРЮКИ мужские','0','0',0,'2.07М'),(759,91,'121','БРЮКИ уни','0','0',0,'2.12'),(758,90,'120','БРЮКИ женские','0','0',0,'2.46A'),(757,89,'119','БРЮКИ женские','0','0',0,'2.15A'),(756,88,'118','БРЮКИ женские','0','0',0,'2.08Ж'),(755,87,'117','БРЮКИ женские','0','0',0,'2.03A'),(754,86,'116','КИМОНО уни','0','0',0,'4B.120'),(753,85,'115','БЛУЗА женская','0','0',0,'4A.227A'),(752,84,'114','БЛУЗА женская','0','0',0,'4A.61B'),(751,83,'113','БЛУЗА женская','0','0',0,'4A.61A'),(750,82,'112','ЖИЛЕТ-корсет','0','0',0,'6A.57'),(749,81,'111','ЖИЛЕТ-корсет','0','0',0,'6A.89A'),(748,80,'110','ЖИЛЕТ-корсет','0','0',0,'6A.66'),(747,79,'109','ЖИЛЕТ женский','0','0',0,'6A.22Ж'),(746,78,'108','КУРТКА ПОВАРСКАЯ мужская','0','0',0,'4C.262'),(745,77,'107','КИТЕЛЬ банкетный','0','0',0,'0.09'),(744,76,'106','КИТЕЛЬ банкетный','0','0',0,'0.10С'),(743,75,'105','КИТЕЛЬ банкетный','0','0',0,'0.10B'),(742,74,'104','СПЕНСЕР женский','0','0',0,'0.06'),(741,73,'103','МАНТИЯ мужская','0','0',0,'0.45'),(740,72,'102','ГАЛСТУК','0','0',0,'10.93'),(739,71,'100','БАБОЧКА одинарная','0','0',0,'10.25A'),(738,70,'99','БАБОЧКА двойная','0','0',0,'10.25B'),(737,69,'97','БРЮКИ женские','0','0',0,'2.75A'),(736,68,'96','БРЮКИ женские','0','0',0,'2.03B'),(735,67,'95','СОРОЧКА мужская','0','0',0,'4A.118B'),(734,66,'94','СОРОЧКА мужская','0','0',0,'4A.147'),(733,65,'92','БЛУЗА женская','0','0',0,'4A.179'),(732,64,'91','БЛУЗА женская','0','0',0,'4A.78C'),(731,63,'90','БЛУЗА женская','0','0',0,'4A.101'),(730,62,'89','БЛУЗА женская','0','0',0,'4A.108'),(729,61,'88','БЛУЗА женская','0','0',0,'4A.130'),(728,60,'87','ЖИЛЕТ мужской','0','0',0,'6A.03М'),(727,59,'86','ЖИЛЕТ-манишка','0','0',0,'6A.87'),(726,58,'85','ЖИЛЕТ женский','0','0',0,'6A.03'),(725,57,'84','ЖИЛЕТ женский','0','0',0,'6A.47'),(724,56,'83','ЖИЛЕТ женский','0','0',0,'6A.60'),(723,55,'82','ЖИЛЕТ женский','0','0',0,'6A.24'),(722,54,'81','ЖИЛЕТ женский','0','0',0,'6A.01D'),(721,53,'80','ЖИЛЕТ женский','0','0',0,'6A.01'),(720,52,'79','БЛУЗА женская','0','0',0,'4A.177'),(719,51,'78','ТУНИКА мужская','0','0',0,'4B.65A-2'),(718,50,'77','ТУНИКА мужская','0','0',0,'4B.14D'),(717,49,'76','ТУНИКА мужская','0','0',0,'4B.34'),(716,48,'75','ТУНИКА мужская','0','0',0,'4B.24'),(715,47,'74','ТУНИКА женская','0','0',0,'4B.28'),(714,46,'73','ТУНИКА женская','0','0',0,'4B.97'),(713,45,'71','ТУНИКА женская','0','0',0,'4B.98'),(712,44,'70','ТУНИКА женская','0','0',0,'4B.64B'),(711,43,'69','ТУНИКА женская','0','0',0,'4B.64C'),(710,42,'68','ПЛАТЬЕ-фартук','0','0',0,'5.16'),(709,41,'67','ПЛАТЬЕ-фартук','0','0',0,'5.37'),(708,40,'66','ПЛАТЬЕ-фартук','0','0',0,'5.32'),(707,39,'65','ФАРТУК-НАКИДКА женская','0','0',0,'5.120'),(706,38,'64','ФАРТУК-НАКИДКА женская','0','0',0,'5.118'),(705,37,'63','НАКИДКА женская','0','0',0,'5.87D'),(704,36,'61','НАКИДКА женская','0','0',0,'5.87'),(703,35,'60','ПЕРЕДНИК мужской','0','0',0,'5.109'),(702,34,'59','ПЕРЕДНИК женский','0','0',0,'5.108'),(701,33,'56','ПЕРЕДНИК уни','0','0',0,'5.42'),(700,32,'55','ПЕРЕДНИК уни','0','0',0,'5.125A'),(699,31,'54','ПЕРЕДНИК уни','0','0',0,'5.04D'),(698,30,'53','ПЕРЕДНИК уни','0','0',0,'5.04B'),(697,29,'50','ФАРТУК уни','0','0',0,'5.03B'),(696,28,'48','КУРТКА ПОВАРСКАЯ женская','0','0',0,'4C.38A'),(695,27,'46','КУРТКА ПОВАРСКАЯ мужская','0','0',0,'4C.51D'),(694,26,'45','КУРТКА ПОВАРСКАЯ мужская','0','0',0,'4C.14С'),(693,25,'44','КУРТКА ПОВАРСКАЯ мужская','0','0',0,'4C.12D'),(692,24,'42','БЛУЗА женская','0','0',0,'4A.145'),(691,23,'41','БЛУЗА женская','0','0',0,'4A.490F'),(690,22,'40','ЖАКЕТ женский','0','0',0,'0.40'),(689,21,'39','ЖАКЕТ женский','0','0',0,'0.26'),(688,20,'38','ЖАКЕТ женский','0','0',0,'0.20'),(687,19,'37','ЖАКЕТ женский','0','0',0,'0.22'),(686,18,'36','ЖАКЕТ женский','0','0',0,'0.04A'),(685,17,'33','ЖАКЕТ женский','0','0',0,'0.03'),(684,16,'32','КУРТКА ПОВАРСКАЯ женская','0','0',0,'4C.37'),(683,15,'31','КИТЕЛЬ банкетный','0','0',0,'0.09D'),(682,14,'30','КИТЕЛЬ банкетный','0','0',0,'0.10D'),(681,13,'28','Жилет вязаный','0','0',0,'11.01A'),(680,12,'27','Мантия женская','0','0',0,'0.46'),(679,11,'26','ШАПОЧКА белл-боя','0','0',0,'9.76'),(678,10,'25','СОРОЧКА мужская','0','0',0,'4A.48B'),(677,9,'24','ФРАК мужской','0','0',0,'0.12B'),(676,8,'21','Блуза женская','0','0',0,'4A.490E'),(675,7,'20','Юбка классическая','0','0',0,'3.03'),(674,6,'19','ЖИЛЕТ женский','0','0',0,'6A.80'),(673,5,'18','СОРОЧКА мужская','0','0',0,'4A.48A'),(672,4,'16','Юбка зауженная','0','0',0,'3.46'),(671,3,'14','СОРОЧКА мужская','0','0',0,'4A.48L'),(670,2,'13','Брюки мужские','0','0',0,'2.05'),(669,1,'12','Жилет мужской','0','0',0,'6A.23B'),(668,0,'10','Блуза женская','0','0',0,'4A.187'),(1069,401,'525','ЖИЛЕТ мужской','0','0',0,'6A.23'),(1070,402,'526','СОРОЧКА мужская','0','0',0,'11.45а'),(1071,403,'527','СОРОЧКА мужская','0','0',0,'11.47a'),(1072,404,'528','СОРОЧКА мужская','0','0',0,'11.46a'),(1073,405,'529','СОРОЧКА мужская','0','0',0,'2506.60'),(1074,406,'530','БЛУЗА женская','0','0',0,'11.41a'),(1075,407,'531','БЛУЗА женская','0','0',0,'11.43a'),(1076,408,'532','БЛУЗА женская','0','0',0,'2510.50'),(1077,409,'533','БЛУЗА женская','0','0',0,'11.42a'),(1078,410,'537','БЛУЗА женская','0','0',0,'4A.203'),(1079,411,'538','ТУНИКА женская','0','0',0,'4B.82'),(1080,412,'542','ЖИЛЕТ женский','0','0',0,'6A.102'),(1081,413,'543','БЛУЗА женская','0','0',0,'4.108A'),(1082,414,'549','БЛУЗА женская','0','0',0,'4A.258'),(1083,415,'550','БЛУЗА женская','0','0',0,'4A.230'),(1084,416,'551','ТУНИКА женская','0','0',0,'4B.128'),(1085,417,'552','ТУНИКА женская','0','0',0,'4B.127'),(1086,418,'555','БЛУЗА женская','0','0',0,'4A.05C'),(1087,419,'556','БЛУЗА женская','0','0',0,'4A.06A'),(1088,420,'557','БЛУЗА женская','0','0',0,'4A.49'),(1089,421,'558','БЛУЗА женская','0','0',0,'4A.07G'),(1090,422,'560','ПЛАТОК','0','0',0,'10.54'),(1091,423,'563','ШАРФ','0','0',0,'10.111'),(1092,424,'564','КОСЫНКА','0','0',0,'10.55'),(1093,425,'565','ГАЛСТУК','0','0',0,'10.61'),(1094,426,'567','ФУТБОЛКА унисекс','0','0',0,'11.19a'),(1095,427,'568','ФУТБОЛКА унисекс','0','0',0,'6140'),(1096,428,'572','ФУТБОЛКА женская','0','0',0,'11.23a'),(1097,429,'573','ФУТБОЛКА женская','0','0',0,'11.21a'),(1098,430,'574','ФУТБОЛКА для сублимации','0','0',0,'11.30a'),(1099,431,'575','ФУТБОЛКА мужская','0','0',0,'11.27a'),(1100,432,'576','ФУТБОЛКА мужская','0','0',0,'11.29a'),(1101,433,'577','ФУТБОЛКА женская','0','0',0,'4379'),(1102,434,'578','ФУТБОЛКА женская','0','0',0,'11.31a'),(1103,435,'579','ФУТБОЛКА мужская','0','0',0,'11.25a'),(1104,436,'580','ФУТБОЛКА женская','0','0',0,'11.26a'),(1105,437,'581','РУБАШКА поло мужская','0','0',0,'11.50a'),(1106,438,'583','РУБАШКА поло женская','0','0',0,'11.33a'),(1107,439,'584','Комплект 1','0','0',0,'-'),(1108,440,'585','Комплект 2','0','0',0,'-'),(1109,441,'586','Комплект 3','0','0',0,'-'),(1110,442,'587','Комплект 4','0','0',0,'-'),(1111,443,'588','Комплект 5','0','0',0,'-'),(1112,444,'589','Комплект 6','0','0',0,'-'),(1113,445,'590','Комплект 7 и 8','0','0',0,'-'),(1114,446,'591','Комллект 9','0','0',0,'-'),(1115,447,'592','Комплект 10','0','0',0,'-'),(1116,448,'593','Комплект 11','0','0',0,'-'),(1117,449,'594','Комплект 12','0','0',0,'-'),(1118,450,'595','Комплект 13','0','0',0,'-'),(1119,451,'596','Комплект 14','0','0',0,'-'),(1120,452,'597','Комплект 15','0','0',0,'-'),(1121,453,'598','Комплект 16','0','0',0,'-'),(1122,454,'599','Комплект 17','0','0',0,'-'),(1123,455,'605','ЖИЛЕТ женский','0','0',0,'6A.96'),(1124,456,'606','Комплект 18','0','0',0,'-'),(1125,457,'607','Комплект 19','0','0',0,'-'),(1126,458,'608','Комплект 20','0','0',0,'-'),(1127,459,'609','Комплект 21','0','0',0,'-'),(1128,460,'610','Комплект 2-ка','0','0',0,'М 146 - 139'),(1129,461,'611','Комплект 2-ка','0','0',0,'М 146 - 143'),(1130,462,'612','Комплект 2-ка','0','0',0,'М 152-153'),(1131,463,'613','Комплект 2-ка','0','0',0,'Ч 160 - 156'),(1132,464,'614','Комплект 3-ка','0','0',0,'М 160-156-3'),(1133,465,'615','Комплект 3-ка','0','0',0,'М146-139-3'),(1134,466,'616','Комплект 3-ка','0','0',0,'М146-143-3'),(1135,467,'617','Комплект 3-ка','0','0',0,'М152-153-3'),(1136,468,'619','Комплект 4-ка','0','0',0,'М 141'),(1137,469,'620','Комплект 2-ка','0','0',0,'149'),(1138,470,'621','Комплект 3-ка','0','0',0,'150'),(1139,471,'622','Блуза для девочки','0','0',0,'544'),(1140,472,'623','Блуза для девочки','0','0',0,'545'),(1141,473,'624','Бадлон','0','0',0,''),(1142,474,'625','Жилет вязаный','0','0',0,'0182'),(1143,475,'626','Жакет вязаный','0','0',0,'1060'),(1144,476,'627','Жакет вязаный','0','0',0,'0137'),(1145,477,'628','Комплект 22 и 23','0','0',0,'-'),(1146,478,'629','Комплект 24 и 25','0','0',0,'-'),(1147,479,'630','Комплект 26 и 27','0','0',0,'-'),(1148,480,'631','Футболка дл. рукав','0','0',0,'Stan Fashion'),(1149,481,'632','Футболка дл. рукав','0','0',0,'Stan Casual'),(1150,482,'633','ГАЛСТУК','0','0',0,'10.04Э'),(1151,483,'634','Жилет женский стеганый','0','0',0,'6B.113'),(1152,484,'635','Фартук','0','0',0,'5.202'),(1153,485,'636','Куртка','0','0',0,'7.90b'),(1154,486,'637','ГАЛСТУК женский','0','0',0,'10.82'),(1155,487,'638','БЛУЗА женская','0','0',0,'4A.55'),(1156,488,'639','Сарафан','0','0',0,'136'),(1157,489,'640','Сарафан','0','0',0,'119'),(1158,490,'642','Рубашка-поло','0','0',0,'4А.225'),(1159,491,'643','Сумка для пиццы','0','0',0,'13.47'),(1160,492,'644','Бейсболка Unit First','0','0',0,'11.37a'),(1161,493,'645','Бейсболка Unit Standart','0','0',0,'11.39a'),(1162,494,'646','Бейсболка StanClassic','0','0',0,'11.38b'),(1163,495,'647','Козырек Unit Sun','0','0',0,'11.40a'),(1164,496,'648','ЖАКЕТ женский','0','0',0,'0.41'),(1165,497,'649','ЖАКЕТ женский','0','0',0,'0.43'),(1166,498,'651','ПЛАТЬЕ','0','0',0,'1.66'),(1167,499,'652','ПЛАТЬЕ','0','0',0,'1.26В'),(1168,500,'653','БРЮКИ женские','0','0',0,'2.75'),(1169,501,'654','ЖИЛЕТ-ФРАК','0','0',0,'6.69'),(1170,502,'655','Перчатки','0','0',0,'11.15h'),(1171,503,'656','Перчатки','0','0',0,'11.17h'),(1172,504,'657','Перчатки','0','0',0,'11.18-1h'),(1173,505,'659','ТОЛСТОВКА с капюшоном','0','0',0,'2401'),(1174,506,'660','КУРТКА-ветровка','0','0',0,'11.63a'),(1175,507,'661','Ветровка на подкладке','0','0',0,'1842'),(1176,508,'662','Бейсболка Unit Promo','0','0',0,'1846'),(1177,509,'663','БЛУЗА женская','0','0',0,'11.44a'),(1178,510,'664','СОРОЧКА мужская','0','0',0,'11.48a'),(1179,511,'665','СОРОЧКА мужская','0','0',0,'11.49a'),(1180,512,'666','МАЙКА мужская','0','0',0,'11.51a'),(1181,513,'667','МАЙКА женская','0','0',0,'11.52a'),(1182,514,'668','ФУТБОЛКА женская','0','0',0,'11.53a'),(1183,515,'669','ФУТБОЛКА мужская','0','0',0,'11.54a'),(1184,516,'670','ФУТБОЛКА мужская','0','0',0,'11.55a'),(1185,517,'671','ФУТБОЛКА женская','0','0',0,'11.56a'),(1186,518,'672','ФУТБОЛКА женская','0','0',0,'11.57a'),(1187,519,'673','РУБАШКА-ПОЛО женская','0','0',0,'11.58a'),(1188,520,'674','РУБАШКА-ПОЛО мужская','0','0',0,'11.59a'),(1189,521,'675','РУБАШКА-ПОЛО мужская','0','0',0,'11.60a'),(1190,522,'676','РУБАШКА-ПОЛО женская','0','0',0,'11.61a'),(1191,523,'677','РУБАШКА-ПОЛО мужская','0','0',0,'11.62а'),(1192,524,'678','ТОЛСТОВКА женская','0','0',0,'11.64a'),(1193,525,'679','КУРТКА  мужская','0','0',0,'11.65a'),(1194,526,'680','БАНДАНА','0','0',0,'11.66a'),(1195,527,'681','КОЗЫРЕК','0','0',0,'11.67a'),(1196,528,'682','Бейсболка','0','0',0,'11.68a'),(1197,529,'683','ФАРТУК универсальный','0','0',0,'11.69a'),(1198,530,'684','ПИДЖАК мужской','0','0',0,'0.48'),(1199,531,'685','Перчатки','0','0',0,'11.18-2h'),(1200,532,'686','ШАПКА флисовая','0','0',0,'11.70h'),(1201,533,'687','ШАРФ флисовый','0','0',0,'11.71h'),(1202,534,'688','ВАРЕЖКИ флисовые','0','0',0,'11.72h'),(1203,535,'689','ВАРЕЖКИ комбинированные','0','0',0,'11.73h'),(1204,536,'690','Платье','0','0',0,'1.104'),(1205,537,'691','БЛУЗА женская','0','0',0,'4.307'),(1206,538,'692','ЖАКЕТ женский','0','0',0,'0.73'),(1207,539,'693','ПЕРЕДНИК','0','0',0,'5.217'),(1208,540,'694','ПЛАТЬЕ-ФАРТУК','0','0',0,'5.218'),(1209,541,'695','БЛУЗА женская','0','0',0,'4.308'),(1210,542,'696','КИТЕЛЬ ПОВАРСКОЙ  мужской','0','0',0,'4С.321'),(1211,543,'697','КИТЕЛЬ ПОВАРСКОЙ мужской','0','0',0,'4С.51F'),(1212,544,'698','ПЕРЕДНИК мужской','0','0',0,'5.219'),(1213,545,'699','ЖАКЕТ мужской','0','0',0,'0.72'),(1214,546,'700','ЮБКА на подкладке','0','0',0,'11.76h'),(1215,547,'701','ЖИЛЕТ женский','0','0',0,'11.75h'),(1216,548,'702','БРЮКИ женские','0','0',0,'11.77h');
/*!40000 ALTER TABLE `m2_item` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-07-15 11:28:21