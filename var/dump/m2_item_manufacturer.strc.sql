CREATE TABLE `m2_item_manufacturer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(11) unsigned NOT NULL,
  `order` int(11) unsigned NOT NULL,
  `manufacturer_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ssgn_project_id` (`item_id`),
  KEY `order` (`order`),
  KEY `manufacturer_id` (`manufacturer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8