-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 02, 2012 at 03:29 
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `nana41x`
--

-- --------------------------------------------------------

--
-- Table structure for table `memo`
--

CREATE TABLE IF NOT EXISTS `memo` (
  `MemoID` varchar(15) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `MemoDesc` text COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `memo`
--

INSERT INTO `memo` (`MemoID`, `MemoDesc`) VALUES
('PU', '01/09/12\r\n--------\r\n- Presensi: tampilan hari tidak sesuai dengan di jadwal.\r\n- virtual student enrollment 20121: 016201200049 IR 2012 Class 1 tidak muncul "tanpa klik class, langsung dipilihkan mk untuk IR 2012 class 1"'),
('BP', ''),
('SKB', '');
