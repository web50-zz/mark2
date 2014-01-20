CREATE TABLE `m2_category` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT 'Наименование',
  `name` varchar(32) NOT NULL COMMENT 'Имя для URI',
  `uri` varchar(255) NOT NULL COMMENT 'Полный URI проекта',
  `type` tinyint(1) unsigned NOT NULL COMMENT 'Тип 0 - категория, 1 - проект',
  `link_id` smallint(5) unsigned NOT NULL,
  `visible` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `brief` text NOT NULL COMMENT 'краткое описание',
  `description` text NOT NULL COMMENT 'подробное описание',
  `short_description` text NOT NULL COMMENT 'описание',
  `left` smallint(5) unsigned NOT NULL,
  `right` smallint(5) unsigned NOT NULL,
  `level` smallint(5) unsigned NOT NULL,
  `output_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `node` (`left`,`right`,`level`)
) ENGINE=MyISAM AUTO_INCREMENT=110 DEFAULT CHARSET=utf8