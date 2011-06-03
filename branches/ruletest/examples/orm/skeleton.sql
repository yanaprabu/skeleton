CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `post_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

INSERT INTO `comments` (`id`, `post_id`, `author_id`, `content`) VALUES
(1, 1, 1, 'Cool post, man!'),
(2, 2, 1, 'Testing'),
(3, 2, 1, 'This is a test');

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `author_id` int(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `author_id` (`author_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `posts` (`id`, `author_id`, `title`, `content`) VALUES
(1, 1, 'Blue Post', 'This is a blue post'),
(2, 1, 'Green post', 'This is a green post');

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `active` char(1) NOT NULL DEFAULT 'N',
  `username` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `access` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
  KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `active`, `userid`, `firstname`, `lastname`, `email`, `access`) VALUES
(1, 'Y', 'cory', 'Cory', 'Kaufman', 'cory@brightbridge.net', '');
