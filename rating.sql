-- Не предусмотрено проверки id менеджера

CREATE TABLE `manager_rating_votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL,
  `hostname` varchar(128) NOT NULL DEFAULT '',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`),
  KEY `hostname` (`hostname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `manager_rating` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL,
  `total` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;