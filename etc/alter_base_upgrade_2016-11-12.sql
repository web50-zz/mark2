DROP TABLE IF EXISTS `m2_item_link_types`;
CREATE TABLE `m2_item_link_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `not_available` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dop_params` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order` (`order`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

LOCK TABLES `m2_item_link_types` WRITE;
INSERT INTO `m2_item_link_types` VALUES (1,0,'С этим товаром покупают',0,''),(2,1,'С этим товаром просматривают',0,'');
UNLOCK TABLES;

DROP TABLE IF EXISTS `m2_item_links`;
CREATE TABLE `m2_item_links` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(11) unsigned NOT NULL,
  `linked_item_id` int(11) unsigned NOT NULL,
  `order` int(11) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ssgn_project_id` (`item_id`),
  KEY `order` (`order`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
