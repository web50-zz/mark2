CREATE TABLE `m2_item_variation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(10) unsigned NOT NULL,
  `order` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` varchar(20) NOT NULL DEFAULT '0',
  `qty` int(10) unsigned NOT NULL DEFAULT '0',
  `not_available` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `article` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order` (`order`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8