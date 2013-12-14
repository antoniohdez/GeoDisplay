-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 13, 2013 at 01:52 AM
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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `audio_path` varchar(512) NOT NULL,
  `video_path` varchar(512) NOT NULL,
  `facebook` varchar(256) NOT NULL,
  `twitter` varchar(256) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_points` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `points`
--

INSERT INTO `points` (`user_id`, `point_name`, `description`, `latitude`, `longitude`, `image_path`, `url`, `id`, `audio_path`, `video_path`, `facebook`, `twitter`) VALUES
('creatorsstudio', 'PcMicro', 'Venta de equipo de computo al mejor precio', 20.8052687, -103.47306470000001, 'uploads/reloj.jpg', 'www.pcmicro.com', 18, 'audio/reloj3.jpg', 'video/reloj2.jpg', 'www.facebook.com', 'www.twitter.com'),
('creatorsstudio', 'Prueba 2', 'Lorem ipsum', 20.36959, -102.7692404, 'uploads/reloj.jpg', 'www.google.com', 20, 'audio/reloj2.jpg', 'video/reloj3.jpg', 'www.facebook.com', 'ww.twitter.com');

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
  `logo_path` varchar(512) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `points`, `country`, `city`, `logo_path`) VALUES
('creatorsstudio', 'Creators Studio', 'info@creatorsstudio.mx', '37f62f1363b04df4370753037853fe88', 7, 'MÃ©xico', 'Guadalajara', 'logo/reloj2.jpg');

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
