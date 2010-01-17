
CREATE TABLE `users` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`firstname` varchar(255) NOT NULL,
	`lastname` varchar(255) NOT NULL,
	`username` varchar(255) NOT NULL,
	`password` varchar(255) NOT NULL,
	`email` varchar(255) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `posts` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`postdate` datetime NOT NULL default '0000-00-00 00:00:00',
	`permalink` varchar(255) NOT NULL,
	`title` varchar(255) NOT NULL,
	`excerpt` varchar(255) NOT NULL,
	`post` text NOT NULL,
	`commentsallowed` TINYINT UNSIGNED NOT NULL,
	`user_id` varchar(255) NOT NULL,
	PRIMARY KEY  (`post_id`)
	FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `comments` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`permalink` VARCHAR(255) NOT NULL,
	`author` VARCHAR(255) NOT NULL,
	`authoremail` VARCHAR(255) NOT NULL,
	`authorurl` VARCHAR(255) NOT NULL,
	`postdate` datetime NOT NULL default '0000-00-00 00:00:00',
	`comment` text NOT NULL,
	`approved` TINYINT UNSIGNED NOT NULL default '1',
	`post_id` INT UNSIGNED NOT NULL,
	PRIMARY KEY  (`id`),
	FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `categories` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL,
	`parent` INT UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `category2posts` (
	`category_id` INT NOT NULL,
	`post_id` INT NOT NULL,   
	PRIMARY KEY (`category_id`, `post_id`),  
	FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`), 
	FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tags` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL,
	PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tags2posts` (
	`tag_id` INT NOT NULL,
	`post_id` INT NOT NULL,   
	PRIMARY KEY (`tag_id`, `post_id`),  
	FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`), 
	FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

