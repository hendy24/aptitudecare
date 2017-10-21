
--
-- Table structure for table `search_data`
--

CREATE TABLE IF NOT EXISTS `search_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content_type` varchar(15) DEFAULT NULL,
  `content_id` int(10) unsigned DEFAULT NULL,
  `data` longtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `content_type` (`content_type`,`content_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------


--
-- Table structure for table `search_index`
--

CREATE TABLE IF NOT EXISTS `search_index` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content_id` int(10) unsigned NOT NULL DEFAULT '0',
  `content_type` varchar(30) NOT NULL DEFAULT '',
  `token` text,
  `tcount` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`(50)),
  KEY `content_id` (`content_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `search_log`
--

CREATE TABLE IF NOT EXISTS `search_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `query` text,
  `hits` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
