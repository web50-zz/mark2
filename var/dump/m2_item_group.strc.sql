CREATE TABLE `m2_item_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `created_datetime` datetime NOT NULL,
  `creator_uid` mediumint(8) unsigned NOT NULL,
  `changed_datetime` datetime NOT NULL,
  `changer_uid` mediumint(8) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8