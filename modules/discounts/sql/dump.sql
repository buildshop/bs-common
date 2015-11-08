
INSERT INTO `{prefix}grid_columns` (`grid_id`, `key`, `ordern`) VALUES
('shopdiscount-grid', 0, 1),
('shopdiscount-grid', 1, 2),
('shopdiscount-grid', 2, 3),
('shopdiscount-grid', 3, 4),
('shopdiscount-grid', 4, 5);


CREATE TABLE IF NOT EXISTS `{prefix}shop_discount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `switch` tinyint(1) DEFAULT NULL,
  `sum` varchar(10) DEFAULT '',
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `roles` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `active` (`switch`),
  KEY `start_date` (`start_date`),
  KEY `end_date` (`end_date`)
) ENGINE=MyISAM;


CREATE TABLE IF NOT EXISTS `{prefix}shop_discount_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `discount_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `discount_id` (`discount_id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM;


CREATE TABLE IF NOT EXISTS `{prefix}shop_discount_manufacturer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `discount_id` int(11) DEFAULT NULL,
  `manufacturer_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `discount_id` (`discount_id`),
  KEY `manufacturer_id` (`manufacturer_id`)
) ENGINE=MyISAM;
