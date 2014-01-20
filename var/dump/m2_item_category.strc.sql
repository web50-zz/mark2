CREATE TABLE `m2_item_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) DEFAULT NULL,
  `item_id` int(10) DEFAULT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `NewIndex1` (`category_id`),
  KEY `NewIndex2` (`item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8