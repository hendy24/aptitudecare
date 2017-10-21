CREATE TABLE IF NOT EXISTS `record_flag` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `table` text NOT NULL,
  `type` enum('SINGLE','MULTI') NOT NULL,
  `val` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;