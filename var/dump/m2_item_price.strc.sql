CREATE TABLE `m2_item_price` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` smallint(5) unsigned NOT NULL,
  `order` smallint(5) unsigned NOT NULL,
  `type` varchar(32) NOT NULL,
  `content` mediumtext NOT NULL,
  `price_value` varchar(255) NOT NULL,
  `currency` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ssgn_project_id` (`item_id`),
  KEY `order` (`order`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8