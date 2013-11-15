-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2013 at 03:36 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `creatorsstudio`
--

-- --------------------------------------------------------

--
-- Table structure for table `points`
--

CREATE TABLE IF NOT EXISTS `points` (
  `user_id` varchar(128) NOT NULL,
  `point_name` varchar(128) NOT NULL,
  `description` varchar(140) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `image_path` varchar(512) NOT NULL,
  `url` varchar(512) NOT NULL,
  `text_url` varchar(512) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `fk_points` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `points`
--

INSERT INTO `points` (`user_id`, `point_name`, `description`, `latitude`, `longitude`, `image_path`, `url`, `text_url`, `id`) VALUES
('creatorsstudio', 'Starbucks TEC', 'VisÃ­tenos ahora!', 20.735, -103.45550000000003, 'uploads/kang2.jpg', 'www.starbucks.com', 'Starbucks', 6),
('creatorsstudio', 'Eros Facebook', 'Mi facebook', 20.735, -103.45550000000003, 'uploads/kang3.jpg', 'www.facebook,com', 'Facebook', 7),
('creatorsstudio', 'Starbucks China', 'China', 39.85842879879298, 116.4017409375, 'uploads/AlienJ3.png', 'www.starbucks.com/china', 'China', 8);

--
-- Triggers `points`
--
DROP TRIGGER IF EXISTS `decreasePoint`;
DELIMITER //
CREATE TRIGGER `decreasePoint` AFTER DELETE ON `points`
 FOR EACH ROW UPDATE users 
    SET users.points = users.points + 1
    WHERE old.user_id = users.id
//
DELIMITER ;
DROP TRIGGER IF EXISTS `increasePoint`;
DELIMITER //
CREATE TRIGGER `increasePoint` BEFORE INSERT ON `points`
 FOR EACH ROW UPDATE users 
    SET users.points = users.points - 1
    WHERE new.user_id = users.id
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` varchar(128) NOT NULL,
  `name` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `points` int(11) NOT NULL,
  `country` varchar(128) NOT NULL,
  `city` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `points`, `country`, `city`) VALUES
('creatorsstudio', 'Creators Studio', 'info@creatorsstudio.mx', '37f62f1363b04df4370753037853fe88', 1, 'México', 'Zapopan');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `points`
--
ALTER TABLE `points`
  ADD CONSTRAINT `fk_points` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
