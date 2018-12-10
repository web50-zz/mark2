CREATE TABLE `m2_chars_in_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT '0',
  `index1` longtext,
  `index2` longtext,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8