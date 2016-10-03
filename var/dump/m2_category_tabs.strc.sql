CREATE TABLE `m2_category_tabs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `m2_category_id` int(11) unsigned NOT NULL,
  `order` int(11) unsigned NOT NULL,
  `name` varchar(128) NOT NULL,
  `type` varchar(32) NOT NULL,
  `content` mediumtext NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ssgn_project_id` (`m2_category_id`),
  KEY `order` (`order`)
) ENGINE=MyISAM AUTO_INCREMENT=138 DEFAULT CHARSET=utf8
