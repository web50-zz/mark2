CREATE TABLE `m2_item_text` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` smallint(5) unsigned NOT NULL,
  `order` smallint(5) unsigned NOT NULL,
  `name` varchar(128) NOT NULL,
  `real_name` varchar(64) NOT NULL,
  `type` varchar(32) NOT NULL,
  `size` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `width` smallint(5) unsigned NOT NULL DEFAULT '0',
  `height` smallint(5) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ssgn_project_id` (`item_id`),
  KEY `order` (`order`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8