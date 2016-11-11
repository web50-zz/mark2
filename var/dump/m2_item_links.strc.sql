DROP TABLE IF EXISTS `m2_item_links`;
CREATE TABLE `m2_item_links` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(11) unsigned NOT NULL,
  `linked_item_id` int(11) unsigned NOT NULL,
  `order` int(11) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ssgn_project_id` (`item_id`),
  KEY `order` (`order`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
