CREATE TABLE `m2_manufacturer_in_groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `created_datetime` datetime NOT NULL,
  `creator_uid` mediumint(8) unsigned NOT NULL,
  `changed_datetime` datetime NOT NULL,
  `changer_uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mailer_list_id` (`group_id`),
  KEY `contact_id` (`item_id`)
) ENGINE=MyISAMDEFAULT CHARSET=utf8