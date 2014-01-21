CREATE TABLE `m2_item_indexer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `order` int(11) unsigned NOT NULL DEFAULT '0',
  `article` varchar(255) NOT NULL,
  `not_available` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `files_list` text,
  `text_list` longtext,
  `prices_list` text NOT NULL,
  `manufacturers_list` text NOT NULL,
  `chars_list` longtext NOT NULL,
  `category_list` text NOT NULL,
  `last_changed` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `m2` (`item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8