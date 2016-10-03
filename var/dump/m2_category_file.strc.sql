CREATE TABLE `m2_category_file` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `m2_category_id` int(11) unsigned NOT NULL,
  `order` int(11) unsigned NOT NULL,
  `name` varchar(128) NOT NULL,
  `real_name` varchar(64) NOT NULL,
  `type` varchar(32) NOT NULL,
  `size` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `width` smallint(5) unsigned NOT NULL DEFAULT '0',
  `height` smallint(5) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `file_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ssgn_project_id` (`m2_category_id`),
  KEY `order` (`order`)
) ENGINE=MyISAM AUTO_INCREMENT=177 DEFAULT CHARSET=utf8
