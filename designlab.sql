-- phpMyAdmin SQL Dump
-- version 4.1.1
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Gegenereerd op: 15 okt 2014 om 10:08
-- Serverversie: 5.5.38-0+wheezy1
-- PHP-versie: 5.4.4-14+deb7u14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databank: `designlab`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `engines`
--

CREATE TABLE IF NOT EXISTS `engines` (
  `id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `queries`
--

CREATE TABLE IF NOT EXISTS `queries` (
  `id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `query` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `snippets`
--

CREATE TABLE IF NOT EXISTS `snippets` (
  `id` bigint(1) NOT NULL AUTO_INCREMENT,
  `engine_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `query_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `position` int(1) NOT NULL,
  `link_cache` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `link_extern` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `engine_id` (`engine_id`),
  KEY `query_id` (`query_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=666785 ;

--
-- Beperkingen voor gedumpte tabellen
--

--
-- Beperkingen voor tabel `snippets`
--
ALTER TABLE `snippets`
  ADD CONSTRAINT `snippets_ibfk_1` FOREIGN KEY (`engine_id`) REFERENCES `engines` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `snippets_ibfk_2` FOREIGN KEY (`query_id`) REFERENCES `queries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
