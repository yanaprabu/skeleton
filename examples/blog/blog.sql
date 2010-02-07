
DROP TABLE IF EXISTS `tags2posts`;
DROP TABLE IF EXISTS `tags`;
DROP TABLE IF EXISTS `categories2posts`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `comments`;
DROP TABLE IF EXISTS `posts`;
DROP TABLE IF EXISTS `users`;

-- --------------------------------------------------------

-- 
-- Table structure for table `users`
--

CREATE TABLE `users` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`firstname` varchar(255) NOT NULL,
	`lastname` varchar(255) NOT NULL,
	`username` varchar(255) NOT NULL,
	`password` varchar(255) NOT NULL,
	`email` varchar(255) NOT NULL,
	`active` char(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `users`
-- 

INSERT INTO `users` VALUES (1, 'matt', '', 'matt', 'pass', 'matt@mail.com', '1');
INSERT INTO `users` VALUES (2, 'chris', '', 'chris', 'pass', 'chris@mail.com', '1');

-- --------------------------------------------------------

-- 
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`post_date` datetime NOT NULL default '0000-00-00 00:00:00',
	`permalink` varchar(255) NOT NULL,
	`title` varchar(255) NOT NULL,
	`excerpt` varchar(255) NOT NULL,
	`content` text NOT NULL,
	`comments_allowed` TINYINT UNSIGNED NOT NULL,
	`users_id` INT(10) UNSIGNED NOT NULL,
	`active` char(1) NOT NULL DEFAULT '0',
	PRIMARY KEY  (`id`),
	FOREIGN KEY (`users_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `posts`
-- 

INSERT INTO `posts` VALUES (1, '2010-01-24 00:00:00', 'my-first-post', 'My first post', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent commodo convallis lectus, quis condimentum neque pretium in.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent commodo convallis lectus, quis condimentum neque pretium in. Aliquam nulla nisi, aliquam sed lacinia nec, iaculis eu est. Quisque tristique pellentesque iaculis. Sed ut nulla et elit pharetra aliquam ultricies posuere nulla. Praesent sed tristique mauris. Phasellus venenatis sollicitudin accumsan. Aenean quis ante libero. Nulla nec consequat erat. In tincidunt mattis lectus, consequat pretium enim volutpat sed. Nulla pellentesque dapibus lectus sed scelerisque. ', 0, 1, '1');
INSERT INTO `posts` VALUES (2, '2010-01-21 00:00:00', 'the-second-post', 'The second post', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent commodo convallis lectus, quis condimentum neque pretium in.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent commodo convallis lectus, quis condimentum neque pretium in. Aliquam nulla nisi, aliquam sed lacinia nec, iaculis eu est. Quisque tristique pellentesque iaculis. Sed ut nulla et elit pharetra aliquam ultricies posuere nulla. Praesent sed tristique mauris. Phasellus venenatis sollicitudin accumsan. Aenean quis ante libero. Nulla nec consequat erat. In tincidunt mattis lectus, consequat pretium enim volutpat sed. Nulla pellentesque dapibus lectus sed scelerisque. ', 1, 2, '1');



-- --------------------------------------------------------

-- 
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`author` VARCHAR(255) NOT NULL,
	`author_email` VARCHAR(255) NOT NULL,
	`author_url` VARCHAR(255) NOT NULL,
	`users_id` INT(10) UNSIGNED NOT NULL,
	`comment_date` datetime NOT NULL default '0000-00-00 00:00:00',
	`comment` text NOT NULL,
	`approved` CHAR(1) NOT NULL default '1',
	`posts_id` INT UNSIGNED NOT NULL,
	PRIMARY KEY  (`id`),
	FOREIGN KEY (`posts_id`) REFERENCES `posts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `comments`
--

INSERT INTO `comments` 
(`id`,`author`,`author_email`,`author_url`,`users_id`,`comment_date`,`comment`,`approved` ,`posts_id`)
VALUES 
(NULL , 'Matt', 'matt@mail.com', 'mysite.com', '', '2010-02-02 00:00:00', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent commodo convallis lectus, quis condimentum neque pretium in.', '1', '1'),
(NULL , 'Mike', 'mike@mail.com', 'mikewebsite.com', '', '2010-02-04 00:00:00', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent commodo convallis lectus, quis condimentum neque pretium in.', '1', '1')

-- --------------------------------------------------------

-- 
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL,
	`parent` INT UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `categories`
-- 

INSERT INTO `categories` VALUES (1, 'php', 0);
INSERT INTO `categories` VALUES (2, 'mysql', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `categories2posts`
--

CREATE TABLE `categories2posts` (
	`categories_id` INT(10) UNSIGNED NOT NULL,
	`posts_id` INT(10) UNSIGNED NOT NULL,   
	PRIMARY KEY (`categories_id`, `posts_id`),  
	FOREIGN KEY (`categories_id`) REFERENCES `categories` (`id`), 
	FOREIGN KEY (`posts_id`) REFERENCES `posts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `categories2posts`
--


-- --------------------------------------------------------

-- 
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL,
	PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `tags`
-- 

INSERT INTO `tags` VALUES (1, 'php');
INSERT INTO `tags` VALUES (2, 'sql');

-- --------------------------------------------------------

-- 
-- Table structure for table `tags2posts`
--

CREATE TABLE `tags2posts` (
	`tags_id` INT(10) UNSIGNED NOT NULL,
	`posts_id` INT(10) UNSIGNED NOT NULL,   
	PRIMARY KEY (`tags_id`, `posts_id`),  
	FOREIGN KEY (`tags_id`) REFERENCES `tags` (`id`), 
	FOREIGN KEY (`posts_id`) REFERENCES `posts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `tags2posts`
--

