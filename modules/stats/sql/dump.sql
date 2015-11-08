CREATE TABLE IF NOT EXISTS `{prefix}mainh` (
  `i` int(11) NOT NULL AUTO_INCREMENT,
  `dt` char(10) DEFAULT NULL,
  `cnt1` int(11) DEFAULT NULL,
  `cnt2` int(11) DEFAULT NULL,
  `cnt3` int(11) DEFAULT NULL,
  `cnt4` int(11) DEFAULT NULL,
  `cnt5` int(11) DEFAULT NULL,
  PRIMARY KEY (`i`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{prefix}mainp` (
  `i` int(11) NOT NULL AUTO_INCREMENT,
  `dt` char(2) DEFAULT NULL,
  `god` char(4) DEFAULT NULL,
  `cnt1` int(11) DEFAULT NULL,
  `cnt2` int(11) DEFAULT NULL,
  `cnt3` int(11) DEFAULT NULL,
  `cnt4` int(11) DEFAULT NULL,
  `cnt5` int(11) DEFAULT NULL,
  PRIMARY KEY (`i`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{prefix}surf` (
  `i` int(11) NOT NULL AUTO_INCREMENT,
  `day` char(3) DEFAULT NULL,
  `dt` char(8) DEFAULT NULL,
  `tm` char(5) DEFAULT NULL,
  `refer` text,
  `ip` char(64) DEFAULT NULL,
  `proxy` char(64) DEFAULT NULL,
  `host` char(64) DEFAULT NULL,
  `lang` char(2) DEFAULT NULL,
  `user` text,
  `req` text,
  PRIMARY KEY (`i`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;