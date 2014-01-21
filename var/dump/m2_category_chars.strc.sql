CREATE TABLE `m2_category_chars` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `m2_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `parent_id` int(11) unsigned NOT NULL,
  `type_value` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `type_id` int(10) unsigned NOT NULL DEFAULT '0',
  `variable_value` varchar(255) NOT NULL DEFAULT '',
  `str_title` varchar(255) NOT NULL DEFAULT '',
  `is_custom` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `char_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `type_value_str` varchar(255) NOT NULL DEFAULT '',
  `order` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ssgn_project_id` (`m2_id`)
) ENGINE=MyISAM AUTO_INCREMENT=273 DEFAULT CHARSET=utf8