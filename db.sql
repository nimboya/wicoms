-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.32 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             8.0.0.4478
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping database structure for worthwhile
CREATE DATABASE IF NOT EXISTS `wicoms` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `wicoms`;


-- Dumping structure for table worthwhile.accts
CREATE TABLE IF NOT EXISTS `accts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uname` text NOT NULL,
  `pwd` text NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table worthwhile.accts: 1 rows
/*!40000 ALTER TABLE `accts` DISABLE KEYS */;
INSERT INTO `accts` (`id`, `uname`, `pwd`, `type`) VALUES
	(1, 'admin', 'admin', 0),
	(3, 'john', 'john', 0);
/*!40000 ALTER TABLE `accts` ENABLE KEYS */;


-- Dumping structure for table worthwhile.content
CREATE TABLE IF NOT EXISTS `content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `dnt` text NOT NULL,
  `category` text NOT NULL,
  `type` int(11) NOT NULL,
  `photo` text NOT NULL,
  `shorturl` text NOT NULL,
  `uname` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shrturl` (`shorturl`(30))
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COMMENT='type 0 - basic\r\ntype 1 - list/detail';

-- Dumping data for table worthwhile.content: 4 rows
/*!40000 ALTER TABLE `content` DISABLE KEYS */;
INSERT INTO `content` (`id`, `title`, `content`, `dnt`, `category`, `type`, `photo`, `shorturl`, `uname`) VALUES
	(1, 'Red Bangle', 'Red Ankara Bangle (Round and Square)', '15-03-2014 04:31:37 pm', 'ankara-bangles', 0, '399331065324733be3202.jpg', 'red-bangle', 'admin'),
	(2, 'Brownish White Bangle', 'Brownish white set', '15-03-2014 04:57:57 pm', 'ankara-bangles', 0, '56862319532478df42f2f.jpg', 'brownish-white-bangle', 'admin'),
	(3, 'About Us', 'This is about the company', '12-04-2014 06:49:42 pm', 'about-us', 0, '', 'about-us', 'admin'),
	(4, 'Welcome To Worthwhile Enterprise', 'Welcome to the Official Website of Worthwhile Enterprise', '28-04-2014 08:11:31 am', 'home-page', 0, '', 'welcome-to-worthwhile-enterprise', 'admin');
/*!40000 ALTER TABLE `content` ENABLE KEYS */;


-- Dumping structure for table worthwhile.pages
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pagename` text NOT NULL,
  `pageurl` text NOT NULL,
  `pagetype` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pageurl` (`pageurl`(30))
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COMMENT='Pages in the app\r\ntype 0 - basic\r\ntype 1 - list/detail';

-- Dumping data for table worthwhile.pages: ~5 rows (approximately)
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` (`id`, `pagename`, `pageurl`, `pagetype`) VALUES
	(2, 'Ankara Bangles', 'ankara-bangles', 1),
	(3, 'Ankara Chokers', 'ankara-chokers', 1),
	(4, 'About Us', 'about-us', 0),
	(5, 'Home Page', 'home-page', 0);
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
