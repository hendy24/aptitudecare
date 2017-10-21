CREATE TABLE IF NOT EXISTS `_email` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `obj` longblob,
  `is_sent` tinyint(1) NOT NULL DEFAULT '0',
  `datetime_created` datetime default NULL,
  `recipient_email` text default NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

