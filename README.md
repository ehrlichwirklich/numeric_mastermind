# numeric_mastermind
Simple PHP Numeric Mastermind 

This project is in progress. Currently, the front is just CSS,HTML,JS and AJAX. The backend is php connected to a mysql db. Everything is currently locally hosted.

#DB Schema

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `num_mastermind` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `num_mastermind`;

DROP TABLE IF EXISTS `auth`;
CREATE TABLE IF NOT EXISTS `auth` (
  `authid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(40) NOT NULL,
  `last_login` timestamp NOT NULL,
  PRIMARY KEY (`authid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Simple auth  table for saving games ';

DROP TABLE IF EXISTS `difficulty`;
CREATE TABLE IF NOT EXISTS `difficulty` (
  `diffid` int(11) NOT NULL AUTO_INCREMENT,
  `diffname` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tries` int(100) NOT NULL,
  PRIMARY KEY (`diffid`),
  UNIQUE KEY `difficulty` (`diffname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the difficulties of games';

DROP TABLE IF EXISTS `game`;
CREATE TABLE IF NOT EXISTS `game` (
  `gid` int(11) NOT NULL AUTO_INCREMENT,
  `tabid` varchar(20) NOT NULL,
  `authid` int(11) DEFAULT NULL,
  `diffid` int(11) NOT NULL,
  `answer` varchar(4) NOT NULL,
  PRIMARY KEY (`gid`),
  KEY `difficulty_setting` (`diffid`),
  KEY `authentication` (`authid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Store game related info and foreign keys ';

DROP TABLE IF EXISTS `turns`;
CREATE TABLE IF NOT EXISTS `turns` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `gid` int(11) NOT NULL,
  `guess` varchar(4) NOT NULL,
  `num` int(11) NOT NULL COMMENT 'which turn is it',
  `correct` int(4) NOT NULL DEFAULT '0' COMMENT 'correct position and value ',
  `pos` int(4) NOT NULL DEFAULT '0' COMMENT 'incorrect position correct value',
  PRIMARY KEY (`tid`),
  KEY `game_flow` (`gid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Store turns from games';


ALTER TABLE `game`
  ADD CONSTRAINT `authentication` FOREIGN KEY (`authid`) REFERENCES `auth` (`authid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `difficulty_setting` FOREIGN KEY (`diffid`) REFERENCES `difficulty` (`diffid`) ON UPDATE CASCADE;

ALTER TABLE `turns`
  ADD CONSTRAINT `game_flow` FOREIGN KEY (`gid`) REFERENCES `game` (`gid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

