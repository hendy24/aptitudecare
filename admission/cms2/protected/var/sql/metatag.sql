CREATE TABLE IF NOT EXISTS `metatag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `page` text,
  `title` text,
  `meta_keywords` text,
  `meta_description` text,
  `meta_robots` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=47 ;

--
-- Dumping data for table `metatag`
--

INSERT INTO `metatag` (`id`, `page`, `title`, `meta_keywords`, `meta_description`, `meta_robots`) VALUES
(46, 'home', 'Welcome Home!', 'homepage, home, demo', 'this is the homepage for an example website', 'nofollow');