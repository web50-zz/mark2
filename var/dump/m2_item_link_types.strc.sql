CREATE TABLE `m2_item_link_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `not_available` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dop_params` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order` (`order`)
) ENGINE=MyISAMDEFAULT CHARSET=utf8