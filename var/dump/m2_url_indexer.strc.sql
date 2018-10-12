CREATE TABLE `m2_url_indexer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(10) unsigned NOT NULL,
  `category_id` int(10) NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  KEY `category_id` (`category_id`),
  KEY `url` (`url`)
) ENGINE=MyISAMDEFAULT CHARSET=utf8