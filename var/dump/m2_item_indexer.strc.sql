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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8