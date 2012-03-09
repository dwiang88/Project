# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.5.9)
# Database: temp
# Generation Time: 2012-03-09 10:17:53 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table billable
# ------------------------------------------------------------

DROP TABLE IF EXISTS `billable`;

CREATE TABLE `billable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `amount` decimal(11,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table clients
# ------------------------------------------------------------

DROP TABLE IF EXISTS `clients`;

CREATE TABLE `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(64) DEFAULT NULL,
  `address` text,
  `telephone` varchar(32) DEFAULT NULL,
  `fax` varchar(32) DEFAULT NULL,
  `website` varchar(64) DEFAULT NULL,
  `notes` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table contacts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `contacts`;

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `position` varchar(64) DEFAULT NULL,
  `telephone` varchar(32) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `mobile` varchar(32) DEFAULT NULL,
  `notes` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `log`;

CREATE TABLE `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `minutes` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table messages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `messages`;

CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sent_from` int(11) DEFAULT NULL,
  `sent_to` int(11) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table projects
# ------------------------------------------------------------

DROP TABLE IF EXISTS `projects`;

CREATE TABLE `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client` varchar(64) DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `contact` varchar(64) DEFAULT NULL,
  `porder` varchar(64) DEFAULT NULL,
  `brief` text,
  `quoted` int(64) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table proof_versions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `proof_versions`;

CREATE TABLE `proof_versions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `proof_id` int(11) DEFAULT NULL,
  `version_name` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table proofs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `proofs`;

CREATE TABLE `proofs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table tasks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tasks`;

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` varchar(32) DEFAULT '',
  `user_id` int(11) DEFAULT NULL,
  `priority` varchar(32) DEFAULT '3. Medium',
  `deadline` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_updated` datetime DEFAULT NULL,
  `date_completed` datetime DEFAULT NULL,
  `notes` text,
  `todo_date` date DEFAULT NULL,
  `sort_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table todo
# ------------------------------------------------------------

DROP TABLE IF EXISTS `todo`;

CREATE TABLE `todo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `task_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `sort_order` int(11) DEFAULT '0',
  `done` tinyint(1) DEFAULT '0',
  `type` int(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `username`, `password`)
VALUES
	(1,'user','password');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table widgets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `widgets`;

CREATE TABLE `widgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL,
  `title` varchar(128) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `content` text,
  `col` int(11) DEFAULT NULL,
  `sort_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `widgets` WRITE;
/*!40000 ALTER TABLE `widgets` DISABLE KEYS */;

INSERT INTO `widgets` (`id`, `user_id`, `type`, `name`, `title`, `link`, `content`, `col`, `sort_order`)
VALUES
	(2,1,'sticky','sticky','Sticky',NULL,'737\r\nWCCF\r\nFB Training\r\nLGT\r\n\r\n',0,3),
	(3,1,'custom','search','Google Search','http://www.google.co.uk','<form method=\"get\" action=\"http://www.google.co.uk/search\">\n						<input type=\"text\"   name=\"q\" size=\"32\"\n						 maxlength=\"255\" value=\"\" />\n						<input type=\"submit\" value=\"Go\" />\n						</form>',1,0),
	(4,1,'db','projects','My Projects','projects.php','',0,1),
	(5,1,'db','mytasks','My Tasks','tasks.php','',0,0),
	(6,1,'custom','google1','Color Picker',NULL,'<script src=\"http://www.gmodules.com/ig/ifr?url=http://colorchoosergadget.googlecode.com/svn/trunk/colorChooser.xml&amp;up_paletteName=Color%20Chooser&amp;up_colors=FFFFFF%20FFFFFF%20FFFFFF%20FFFFFF%20FFFFFF%20FFFFFF%20FFFFFF%20FFFFFF%20FFFFFF%20FFFFFF&amp;synd=open&amp;w=272&amp;h=220&amp;title=__UP_paletteName__&amp;border=%23ffffff%7C3px%2C1px+solid+%23999999&amp;output=js\"></script>',2,1),
	(72,1,'custom','','Password Generator','','<script src=\"http://www.gmodules.com/ig/ifr?url=http://www.amonya.com/igoogle/tool/pwd.xml&amp;up_digits=8&amp;synd=open&amp;w=272&amp;h=100&amp;title=Simple+Password+Generator&amp;lang=all&amp;country=ALL&amp;border=%23ffffff%7C3px%2C1px+solid+%23999999&amp;output=js\"></script>',0,2),
	(64,1,'db','messages','Messageboard',NULL,NULL,2,0),
	(86,1,'rss','twitter','Twitter Mentions','http://search.twitter.com/search?lang=all&q=+inknpixel','http://search.twitter.com/search.atom?q=inknpixel',1,2);

/*!40000 ALTER TABLE `widgets` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
