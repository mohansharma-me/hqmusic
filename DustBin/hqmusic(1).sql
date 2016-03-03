-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 19, 2014 at 12:41 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hqmusic`
--

-- --------------------------------------------------------

--
-- Table structure for table `albums`
--

CREATE TABLE IF NOT EXISTS `albums` (
  `album_id` int(11) NOT NULL AUTO_INCREMENT,
  `album_category_id` int(11) NOT NULL,
  `album_name` text NOT NULL,
  `album_description` text NOT NULL,
  `album_slug` text NOT NULL,
  `album_year` text NOT NULL,
  `album_art` text NOT NULL,
  `album_cast` text NOT NULL,
  `album_imdb_json` text NOT NULL,
  `album_last_updated` date NOT NULL,
  PRIMARY KEY (`album_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `albums`
--

INSERT INTO `albums` (`album_id`, `album_category_id`, `album_name`, `album_description`, `album_slug`, `album_year`, `album_art`, `album_cast`, `album_imdb_json`, `album_last_updated`) VALUES
(1, 2, 'Sonu Nigam', 'Sonu nigam''s all time hit songs', 'sonu-nigam', '2011', '/tempmovie.jpg', 'Sonu Nigam', '', '2014-12-14');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` text NOT NULL,
  `category_slug` text NOT NULL,
  `category_parent_id` int(11) NOT NULL,
  PRIMARY KEY (`category_id`),
  KEY `category_parent_id` (`category_parent_id`),
  KEY `category_parent_id_2` (`category_parent_id`),
  KEY `category_parent_id_3` (`category_parent_id`),
  KEY `category_parent_id_4` (`category_parent_id`),
  KEY `category_parent_id_5` (`category_parent_id`),
  KEY `category_parent_id_6` (`category_parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `category_slug`, `category_parent_id`) VALUES
(1, 'Bollywood Songs', 'bollywood-songs', 0),
(2, 'Artists', 'artists', 1);

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `file_id` int(11) NOT NULL AUTO_INCREMENT,
  `file_song_id` int(11) NOT NULL,
  `kbps` text NOT NULL,
  `file_size` text NOT NULL,
  PRIMARY KEY (`file_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`file_id`, `file_song_id`, `kbps`, `file_size`) VALUES
(1, 1, '128', '1.28 MB'),
(2, 1, '260', '2.60 MB'),
(3, 2, '320', '3.20 MB'),
(4, 2, '512', '5.12 MB');

-- --------------------------------------------------------

--
-- Table structure for table `songs`
--

CREATE TABLE IF NOT EXISTS `songs` (
  `song_id` int(11) NOT NULL AUTO_INCREMENT,
  `song_album_id` int(11) NOT NULL,
  `song_name` text NOT NULL,
  `song_slug` text NOT NULL,
  PRIMARY KEY (`song_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `songs`
--

INSERT INTO `songs` (`song_id`, `song_album_id`, `song_name`, `song_slug`) VALUES
(1, 1, 'Song 1', 'song-1'),
(2, 1, 'Song 2', 'song-2');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
