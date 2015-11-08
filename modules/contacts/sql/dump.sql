
CREATE TABLE IF NOT EXISTS `{prefix}contacts_managers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `office_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `phones` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;


CREATE TABLE IF NOT EXISTS `{prefix}contacts_markers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `office_id` int(11) DEFAULT NULL,
  `coordx` varchar(15) DEFAULT NULL,
  `coordy` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;


CREATE TABLE IF NOT EXISTS `{prefix}contacts_office` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coordx` varchar(15) DEFAULT NULL,
  `coordy` varchar(15) DEFAULT NULL,
  `phones` text,
  `address` text,
  `showInMap` tinyint(1) DEFAULT NULL,
  `switch` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;
