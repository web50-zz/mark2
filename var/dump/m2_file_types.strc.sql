CREATE TABLE `m2_file_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order` int(10) unsigned NOT NULL,
  `prefix` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `width` varchar(20) NOT NULL DEFAULT '0',
  `height` varchar(20) NOT NULL DEFAULT '0',
  `not_available` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_image` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `preview_type` tinyint(1) unsigned NOT NULL,
  `dop_params` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order` (`order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8