
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

CREATE TABLE `posts` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`post_date` datetime NOT NULL default '0000-00-00 00:00:00',
	`permalink` varchar(255) NOT NULL,
	`title` varchar(255) NOT NULL,
	`excerpt` varchar(255) NOT NULL,
	`post` text NOT NULL,
	`comments_allowed` TINYINT UNSIGNED NOT NULL,
	`users_id` INT(10) UNSIGNED NOT NULL,
	`active` char(1) NOT NULL DEFAULT '0',
	PRIMARY KEY  (`id`),
	FOREIGN KEY (`users_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
	FOREIGN KEY (`posts_id`) REFERENCES `posts` (`id`),
	FOREIGN KEY (`users_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `categories` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL,
	`parent` INT UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `categories2posts` (
	`categories_id` INT(10) NOT NULL,
	`posts_id` INT(10) NOT NULL,   
	PRIMARY KEY (`categories_id`, `posts_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tags` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL,
	PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tags2posts` (
	`tags_id` INT(10) NOT NULL,
	`posts_id` INT(10) NOT NULL,   
	PRIMARY KEY (`tags_id`, `posts_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

