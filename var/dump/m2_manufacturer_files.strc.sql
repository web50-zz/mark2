
DROP TABLE IF EXISTS `m2_manufacturer_files`;
CREATE TABLE `m2_manufacturer_files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_datetime` datetime NOT NULL,
  `creator_uid` int(10) unsigned NOT NULL,
  `changed_datetime` datetime NOT NULL,
  `changer_uid` int(10) unsigned NOT NULL,
  `order` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `real_name` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `type` varchar(64) NOT NULL,
  `size` mediumint(8) unsigned NOT NULL,
  `width` smallint(5) unsigned NOT NULL,
  `height` smallint(5) unsigned NOT NULL,
  `file_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `item_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `order` (`order`),
  KEY `m2_item` (`item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;


