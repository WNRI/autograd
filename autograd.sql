-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 14, 2013 at 01:00 
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `autograd`
--

-- --------------------------------------------------------

--
-- Table structure for table `hike`
--

DROP TABLE IF EXISTS `hike`;
CREATE TABLE IF NOT EXISTS `hike` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL,
  `path` linestring NOT NULL,
  `description` text NOT NULL,
  `placeStart` int(11) NOT NULL,
  `placeEnd` int(11) NOT NULL,
  `pathLength` int(11) NOT NULL,
  `pathMaxElevation` int(11) NOT NULL,
  `pathMinElevation` int(11) NOT NULL,
  `pathElevationDifference` int(11) NOT NULL,
  `pathElevationIncrease` int(11) NOT NULL,
  `pathElevationDecrease` int(11) NOT NULL,
  `estimatedDurationAtoB` decimal(11,5) NOT NULL,
  `estimatedDurationBtoA` decimal(11,5) NOT NULL,
  `elevationProfileData` text NOT NULL,
  `hikeDifficultyFromAToB` text,
  `hikeDifficultyFromAToBToA` text,
  `hikeDifficultyFromBToA` text,
  PRIMARY KEY (`id`),
  KEY `place_start` (`placeStart`),
  KEY `place_end` (`placeEnd`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8
CHARACTER SET utf8
DEFAULT CHARACTER SET utf8
COLLATE utf8_general_ci
DEFAULT COLLATE utf8_general_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `place`
--

DROP TABLE IF EXISTS `place`;
CREATE TABLE IF NOT EXISTS `place` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL,
  `point` point NOT NULL,
  `yrId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8
CHARACTER SET utf8
DEFAULT CHARACTER SET utf8
COLLATE utf8_general_ci
DEFAULT COLLATE utf8_general_ci ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hike`
--
ALTER TABLE `hike`
  ADD CONSTRAINT `hike_ibfk_1` FOREIGN KEY (`placeStart`) REFERENCES `place` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `hike_ibfk_2` FOREIGN KEY (`placeEnd`) REFERENCES `place` (`id`) ON UPDATE CASCADE;

  
--
-- CREATE FUNCTION distance
--
DROP FUNCTION IF EXISTS `distance`;
DELIMITER $$
 CREATE FUNCTION distance (a POINT, b POINT) RETURNS double DETERMINISTIC
   BEGIN
     RETURN 6371 * 2 * ASIN(SQRT(POWER(SIN(RADIANS(ABS(X(a)) - ABS(X(b)))), 2) + COS(RADIANS(ABS(X(a)))) * COS(RADIANS(ABS(X(b)))) * POWER(SIN(RADIANS(Y(a) - Y(b))), 2)));
   END  $$
DELIMITER ;


