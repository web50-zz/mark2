CREATE TABLE `m2_chars` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `m2_id` int(11) unsigned NOT NULL DEFAULT '0',
  `parent_id` int(11) unsigned NOT NULL,
  `type_value` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `type_id` int(10) unsigned NOT NULL DEFAULT '0',
  `order` int(10) unsigned NOT NULL DEFAULT '0',
  `variable_value` varchar(255) NOT NULL DEFAULT '',
  `str_title` varchar(255) NOT NULL DEFAULT '',
  `is_custom` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `char_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `type_value_str` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `ssgn_project_id` (`m2_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1443 DEFAULT CHARSET=utf8
