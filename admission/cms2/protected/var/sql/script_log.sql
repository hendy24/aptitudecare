CREATE TABLE IF NOT EXISTS `script_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `datetime_initiated` datetime DEFAULT NULL,
  `datetime_ended` datetime DEFAULT NULL,
  `site` varchar(25) DEFAULT NULL,
  `script` text,
  `output` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;