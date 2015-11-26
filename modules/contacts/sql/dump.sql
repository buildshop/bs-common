CREATE TABLE IF NOT EXISTS `{prefix}contacts_maps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `zoom` int(11) DEFAULT NULL,
  `height` varchar(5) DEFAULT NULL,
  `width` varchar(5) DEFAULT '100%',
  `center` point NOT NULL,
  `type` varchar(20) DEFAULT 'yandex#map',
  `drag` tinyint(1) DEFAULT '1',
  `scrollZoom` tinyint(1) NOT NULL DEFAULT '1',
  `searchControl` tinyint(1) DEFAULT '1',
  `mapTools` tinyint(1) DEFAULT '0',
  `zoomControl` tinyint(1) DEFAULT '1',
  `zoomControl_top` int(11) DEFAULT '5',
  `zoomControl_bottom` int(11) DEFAULT NULL,
  `zoomControl_left` int(11) DEFAULT '15',
  `zoomControl_right` int(11) DEFAULT NULL,
  `mapTools_top` int(11) DEFAULT '5',
  `mapTools_left` int(11) DEFAULT '15',
  `auto_show_routers` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;



CREATE TABLE IF NOT EXISTS `{prefix}contacts_markers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `map_id` int(11) DEFAULT NULL,
  `coords` point NOT NULL,
  `name` text,
  `preset` varchar(50) DEFAULT 'islands#icon',
  `icon_file` varchar(255) DEFAULT NULL,
  `icon_file_offset_x` varchar(5) DEFAULT NULL,
  `icon_file_offset_y` varchar(5) DEFAULT NULL,
  `color` varchar(7) DEFAULT '#0095b6',
  `icon_content` varchar(255) DEFAULT NULL,
  `hint_content` varchar(255) DEFAULT NULL,
  `balloon_content_header` varchar(255) DEFAULT NULL,
  `balloon_content_body` varchar(255) DEFAULT NULL,
  `balloon_content_footer` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `{prefix}contacts_router` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `map_id` int(11) DEFAULT NULL,
  `start_coords` point NOT NULL,
  `end_coords` point NOT NULL,
  `mapStateAutoApply` tinyint(1) NOT NULL DEFAULT '0',
  `name` text,
  `opacity` float(2,1) DEFAULT NULL,
  `preset` varchar(50) DEFAULT 'islands#icon',
  `color` varchar(7) DEFAULT '#0095b6',
  `start_icon_content` varchar(255) DEFAULT 'A',
  `end_icon_content` varchar(255) DEFAULT 'B',
  `start_balloon_content_body` text,
  `end_balloon_content_body` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;


CREATE TABLE IF NOT EXISTS `{prefix}contacts_router_translate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;