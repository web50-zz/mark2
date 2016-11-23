CREATE TABLE `m2_item_price` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(11) unsigned NOT NULL,
  `order` int(11) unsigned NOT NULL,
  `type` varchar(32) NOT NULL,
  `content` mediumtext NOT NULL,
  `price_value` decimal(9,2) NOT NULL DEFAULT '0.00',
  `currency` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order` (`order`),
  KEY `type` (`type`),
  KEY `price_value` (`price_value`),
  KEY `item_id` (`item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3405 DEFAULT CHARSET=utf8