CREATE TABLE `m2_item_manufacturer` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` smallint(5) unsigned NOT NULL,
  `order` smallint(5) unsigned NOT NULL,
  `manufacturer_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ssgn_project_id` (`item_id`),
  KEY `order` (`order`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8