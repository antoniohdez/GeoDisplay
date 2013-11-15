-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 15-11-2013 a las 18:35:00
-- Versión del servidor: 5.1.44
-- Versión de PHP: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `creatorstudio`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `points`
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Volcar la base de datos para la tabla `points`
--

INSERT INTO `points` (`user_id`, `point_name`, `description`, `latitude`, `longitude`, `image_path`, `url`, `text_url`, `id`) VALUES
('creatorsstudio', 'Starbucks TEC', 'VisÃ­tenos ahora!', 29.7638521021792, -95.39153515625, 'uploads/circle.gif', 'www.starbucks.com', 'Starbucks', 6),
('creatorsstudio', 'Eros Facebook', 'Mi facebook', 20.735, -103.4555, 'uploads/kang3.jpg', 'www.facebook,com', 'Facebook', 7),
('creatorsstudio', 'Starbucks China', 'China', 39.858428798793, 116.4017409375, 'uploads/AlienJ3.png', 'www.starbucks.com/china', 'China', 8),
('creatorsstudio', 'ldfjnvdl', 'fovfmgk', 20.7350903, -103.4550562, 'uploads/751E43AFE.png', 'www.creators.com', 'fjlnvf', 15);

--
-- (Evento) desencadenante `points`
--
DROP TRIGGER IF EXISTS `increasePoint`;
DELIMITER //
CREATE TRIGGER `increasePoint` BEFORE INSERT ON `points`
 FOR EACH ROW UPDATE users 
    SET users.points = users.points - 1
    WHERE new.user_id = users.id
//
DELIMITER ;
DROP TRIGGER IF EXISTS `decreasePoint`;
DELIMITER //
CREATE TRIGGER `decreasePoint` AFTER DELETE ON `points`
 FOR EACH ROW UPDATE users 
    SET users.points = users.points + 1
    WHERE old.user_id = users.id
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
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
-- Volcar la base de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `points`, `country`, `city`, `logo_path`) VALUES
('creatorsstudio', 'Creators Studio', 'info@creatorsstudio.mx', '37f62f1363b04df4370753037853fe88', 5, 'México', 'Zapopan', 'logo/751E43AFE.png');

--
-- Filtros para las tablas descargadas (dump)
--

--
-- Filtros para la tabla `points`
--
ALTER TABLE `points`
  ADD CONSTRAINT `fk_points` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
