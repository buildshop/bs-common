
CREATE TABLE IF NOT EXISTS `{prefix}poll` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` longtext,
  `seo_title` varchar(255) NOT NULL,
  `seo_keywords` varchar(255) NOT NULL,
  `seo_description` varchar(255) NOT NULL,
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` datetime NOT NULL,
  `many` tinyint(1) NOT NULL DEFAULT '1',
  `switch` tinyint(1) NOT NULL DEFAULT '1',
  `ordern` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `{prefix}poll_choice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poll_id` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `votes` int(11) unsigned NOT NULL DEFAULT '0',
  `ordern` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `choice_poll` (`poll_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `{prefix}poll_vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `choice_id` int(11) unsigned NOT NULL,
  `poll_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '',
  `timestamp` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `poll_id` (`poll_id`),
  KEY `choice_id` (`choice_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
