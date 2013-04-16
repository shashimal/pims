-- phpMyAdmin SQL Dump
-- version 3.2.2.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 17, 2010 at 01:47 PM
-- Server version: 5.1.37
-- PHP Version: 5.2.10-2ubuntu6.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `pims`
--

-- --------------------------------------------------------

--
-- Table structure for table `clinic_reason`
--

CREATE TABLE IF NOT EXISTS `clinic_reason` (
  `patient_no` varchar(20) NOT NULL,
  `episode_no` int(11) NOT NULL,
  `gp` varchar(40) DEFAULT NULL,
  `court` varchar(40) DEFAULT NULL,
  `bb` varchar(40) DEFAULT NULL,
  `contact` varchar(40) DEFAULT NULL,
  `cf` varchar(40) DEFAULT NULL,
  `volantary` varchar(40) DEFAULT NULL,
  `opd` varchar(40) DEFAULT NULL,
  `ward` varchar(20) DEFAULT NULL,
  `other` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`patient_no`,`episode_no`),
  KEY `episode_no` (`episode_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `clinic_reason`
--

INSERT INTO `clinic_reason` (`patient_no`, `episode_no`, `gp`, `court`, `bb`, `contact`, `cf`, `volantary`, `opd`, `ward`, `other`) VALUES
('F000310', 3, NULL, NULL, NULL, NULL, NULL, 'Voluntary', NULL, NULL, NULL),
('F000410', 1, 'GP', NULL, 'Blood Bank', 'Contact', 'Clinic Followup', 'Voluntary', NULL, 'Ward', 'Other'),
('F001110', 1, NULL, 'court', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('M001310', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('M001410', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('M001510', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('M001610', 1, NULL, NULL, 'Blood Bank', NULL, NULL, NULL, NULL, NULL, NULL),
('M001710', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contact_mode`
--

CREATE TABLE IF NOT EXISTS `contact_mode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_no` varchar(20) NOT NULL,
  `email` smallint(1) DEFAULT NULL,
  `letter` smallint(1) DEFAULT NULL,
  `telephone` smallint(1) DEFAULT NULL,
  `visit` smallint(1) DEFAULT NULL,
  `no_contact` smallint(1) DEFAULT NULL,
  PRIMARY KEY (`id`,`patient_no`),
  KEY `patient_no` (`patient_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=94 ;

--
-- Dumping data for table `contact_mode`
--

INSERT INTO `contact_mode` (`id`, `patient_no`, `email`, `letter`, `telephone`, `visit`, `no_contact`) VALUES
(47, 'M000109', 0, 0, 0, 0, 0),
(48, 'M000209', 0, 0, 1, 0, 0),
(49, 'M000309', 0, 0, 1, 0, 0),
(50, 'F000109', 1, 0, 0, 0, 0),
(51, 'F000209', 1, 0, 0, 0, 0),
(53, 'F000409', 0, 0, 1, 1, 0),
(54, 'M000409', 0, 0, 1, 0, 0),
(55, 'M000509', 1, 0, 0, 0, 0),
(56, 'F000509', 1, 0, 0, 0, 0),
(57, 'M000609', 1, 0, 0, 0, 0),
(58, 'F000609', 0, 0, 1, 1, 0),
(59, 'F000709', 0, 0, 1, 1, 0),
(62, 'M000709', 0, 0, 1, 0, 0),
(63, 'M000809', 0, 0, 1, 0, 0),
(64, 'F001009', 0, 0, 0, 0, 0),
(65, 'M000909', 0, 0, 1, 0, 0),
(66, 'M001009', 0, 0, 1, 0, 0),
(67, 'F000110', 0, 0, 0, 0, 0),
(68, 'F000210', 1, 0, 1, 0, 0),
(69, 'F000310', 0, 0, 1, 0, 0),
(70, 'F000410', 1, 0, 0, 0, 0),
(71, 'F000510', 0, 0, 1, 0, 0),
(72, 'M000210', 0, 0, 1, 0, 0),
(73, 'M000310', 0, 0, 1, 1, 0),
(74, 'F000610', 0, 0, 1, 0, 0),
(75, 'F000710', 0, 0, 1, 0, 0),
(76, 'M000410', 0, 0, 1, 0, 0),
(77, 'M000510', 0, 0, 1, 0, 0),
(78, 'F000810', 0, 0, 0, 0, 0),
(79, 'F000910', 0, 0, 0, 0, 0),
(80, 'M000610', 0, 0, 0, 0, 0),
(81, 'M000710', 0, 0, 0, 0, 0),
(82, 'M000810', 0, 0, 0, 0, 0),
(83, 'M000910', 0, 0, 0, 0, 0),
(84, 'M001010', 0, 0, 0, 0, 0),
(85, 'M001110', 0, 0, 0, 0, 0),
(86, 'F001010', 0, 0, 0, 0, 0),
(87, 'M001210', 0, 0, 0, 0, 0),
(88, 'M001310', 0, 0, 0, 0, 0),
(89, 'F001110', 0, 0, 0, 0, 0),
(90, 'M001410', 0, 0, 0, 0, 0),
(91, 'M001510', 0, 0, 0, 0, 0),
(92, 'M001610', 0, 0, 0, 0, 0),
(93, 'M001710', 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `episode`
--

CREATE TABLE IF NOT EXISTS `episode` (
  `episode_no` int(11) NOT NULL AUTO_INCREMENT,
  `patient_no` varchar(20) NOT NULL,
  `status` smallint(1) NOT NULL DEFAULT '0',
  `Comment` varchar(60) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `std_positive` varchar(10) DEFAULT NULL,
  `hiv_positive` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`episode_no`,`patient_no`),
  KEY `patient_no` (`patient_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `episode`
--

INSERT INTO `episode` (`episode_no`, `patient_no`, `status`, `Comment`, `start_date`, `end_date`, `std_positive`, `hiv_positive`) VALUES
(1, 'F000109', 0, 'New Patient', '2009-01-01', NULL, NULL, NULL),
(1, 'F000110', 1, 'New Patient', '2010-07-27', '2010-09-09', 'Yes', 'Yes'),
(1, 'F000209', 0, 'New Patient', '2009-01-01', NULL, NULL, NULL),
(1, 'F000210', 0, 'New Patient', '2010-07-27', NULL, NULL, NULL),
(1, 'F000310', 0, 'New Patient', '2010-07-27', NULL, NULL, NULL),
(1, 'F000409', 0, 'New Patient', '2009-01-01', NULL, NULL, NULL),
(1, 'F000410', 0, 'New Patient', '2010-07-27', NULL, NULL, NULL),
(1, 'F000509', 0, 'New Patient', '2009-01-01', NULL, NULL, NULL),
(1, 'F000510', 1, 'New Patient', '2010-07-27', '2010-09-09', 'Yes', NULL),
(1, 'F000609', 0, 'New Patient', '2009-01-01', NULL, NULL, NULL),
(1, 'F000610', 1, 'New Patient', '2010-07-27', '2010-09-09', 'Yes', 'Yes'),
(1, 'F000709', 0, 'New Patient', '2009-01-01', NULL, NULL, NULL),
(1, 'F000710', 1, 'New Patient', '2010-07-27', '2010-09-09', 'Yes', NULL),
(1, 'F000810', 2, 'New Patient', '2010-08-03', '2010-09-09', 'Yes', NULL),
(1, 'F000910', 0, 'New Patient', '2010-08-03', NULL, NULL, NULL),
(1, 'F001009', 0, 'New Patient', '2009-01-01', NULL, NULL, NULL),
(1, 'F001010', 0, 'New Patient', '2010-08-19', NULL, NULL, NULL),
(1, 'F001110', 0, '', '2010-08-20', NULL, NULL, NULL),
(1, 'M000109', 0, 'New Patient', '2009-01-01', NULL, NULL, NULL),
(1, 'M000209', 0, 'New Patient', '2009-01-01', NULL, NULL, NULL),
(1, 'M000210', 0, 'New Patient', '2010-07-27', NULL, NULL, NULL),
(1, 'M000309', 0, 'New Patient', '2009-01-01', NULL, NULL, NULL),
(1, 'M000310', 0, 'New Patient', '2010-07-27', NULL, NULL, NULL),
(1, 'M000409', 0, 'New Patient', '2009-01-01', NULL, NULL, NULL),
(1, 'M000410', 3, 'New Patient', '2010-07-27', NULL, NULL, NULL),
(1, 'M000509', 0, 'New Patient', '2009-01-01', NULL, NULL, NULL),
(1, 'M000510', 0, 'New Patient', '2010-07-27', NULL, NULL, NULL),
(1, 'M000609', 0, 'New Patient', '2009-01-01', NULL, NULL, NULL),
(1, 'M000610', 0, 'New Patient', '2010-08-03', NULL, NULL, NULL),
(1, 'M000709', 0, 'New Patient', '2009-01-01', NULL, NULL, NULL),
(1, 'M000710', 0, 'New Patient', '2010-08-03', NULL, NULL, NULL),
(1, 'M000809', 0, 'New Patient', '2009-01-01', NULL, NULL, NULL),
(1, 'M000810', 0, 'New Patient', '2010-08-03', NULL, NULL, NULL),
(1, 'M000909', 0, 'New Patient', '2009-01-01', NULL, NULL, NULL),
(1, 'M000910', 0, 'New Patient', '2010-08-16', NULL, NULL, NULL),
(1, 'M001009', 0, 'New Patient', '2009-01-01', NULL, NULL, NULL),
(1, 'M001010', 0, 'New Patient', '2010-08-17', NULL, NULL, NULL),
(1, 'M001110', 0, 'New Patient', '2010-08-19', NULL, NULL, NULL),
(1, 'M001210', 0, 'New Patient', '2010-08-19', NULL, NULL, NULL),
(1, 'M001310', 1, '', '2010-08-19', '2010-09-09', 'Yes', NULL),
(1, 'M001410', 0, '', '2010-08-21', NULL, NULL, NULL),
(1, 'M001510', 1, '', '2010-08-21', '2010-11-09', 'Yes', 'Yes'),
(1, 'M001610', 0, '', '2010-09-09', NULL, NULL, NULL),
(1, 'M001710', 0, '', '2010-09-09', NULL, NULL, NULL),
(2, 'F000310', 0, 'Created a new episode', '2010-07-29', NULL, NULL, NULL),
(2, 'M000610', 0, 'Created a new episode', '2010-08-03', NULL, NULL, NULL),
(2, 'M001210', 0, '', '2010-08-19', NULL, NULL, NULL),
(2, 'M001310', 0, '', '2010-08-19', NULL, NULL, NULL),
(2, 'M001410', 0, '', '2010-08-21', NULL, NULL, NULL),
(3, 'F000310', 5, 'Created a new episode', '2010-07-30', NULL, 'Yes', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE IF NOT EXISTS `patient` (
  `patient_no` varchar(20) NOT NULL,
  `registered_date` date NOT NULL,
  `first_name` varchar(30) DEFAULT NULL,
  `last_name` varchar(30) NOT NULL,
  `current_address` varchar(50) DEFAULT NULL,
  `permanent_address` varchar(50) DEFAULT NULL,
  `contact_address` varchar(50) DEFAULT NULL,
  `telephone1` varchar(20) NOT NULL,
  `telephone2` varchar(20) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `nic_pp_no` varchar(20) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `sex` smallint(1) DEFAULT NULL,
  `marital_status` smallint(1) DEFAULT NULL,
  `nationality` varchar(30) DEFAULT NULL,
  `education` varchar(45) DEFAULT NULL,
  `occupation` varchar(30) NOT NULL,
  `category` varchar(20) NOT NULL,
  `deleted` smallint(6) NOT NULL,
  `comment` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`patient_no`),
  KEY `category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`patient_no`, `registered_date`, `first_name`, `last_name`, `current_address`, `permanent_address`, `contact_address`, `telephone1`, `telephone2`, `mobile`, `email`, `nic_pp_no`, `date_of_birth`, `sex`, `marital_status`, `nationality`, `education`, `occupation`, `category`, `deleted`, `comment`) VALUES
('F000109', '2009-04-15', 'S2FtYWxh', 'TWFyYXNpbmdoYSA=', 'MTI0LCBWaXNha2EgUm9hZGEsIENvbG9tYm8gMDQ=', '', '', 'MDM0ODk5NDU3OA==', '', 'MDc3NjI1ODk2', '', NULL, '1979-06-21', 2, 1, 'Srilankan', 'G.C.E O/L', 'CSW', 'PAC001', 0, NULL),
('F000110', '2010-07-27', 'TGFsYW5p', 'UHJpeWFudGhp', 'MjU2IEthZGF3YXRoYSBSb2FkICwgR2FuZW11bGxh', 'MjU2IEthZGF3YXRoYSBSb2FkICwgR2FuZW11bGxh', 'MjU2IEthZGF3YXRoYSBSb2FkICwgR2FuZW11bGxh', 'MDM4MjU2MjQ3OA==', '', 'MDc3NjI1ODk2', '', NULL, '1968-09-18', 2, 1, 'Srilankan', 'G.C.E A/L', 'Unemployed', 'PAC001', 0, NULL),
('F000209', '2009-08-12', 'U2hhbmk=', 'UGVyZXJh', '', '', '', 'MDExMjk1NjczNQ==', '', 'MDc3NjI1ODk2', '', NULL, '1973-09-20', 2, 1, 'Srilankan', '1-5 Grade', 'Unemployed', 'PAC001', 0, NULL),
('F000210', '2010-01-19', 'U2FyYW5nYQ==', 'SGFuc2FuaQ==', 'MjQgTWFpbiBTdHJlZXQgR2FuZW11bGxh', 'MjQgTWFpbiBTdHJlZXQgR2FuZW11bGxh', 'MjQgTWFpbiBTdHJlZXQgR2FuZW11bGxh', 'MDM4MjU2MjQ3OA==', '', 'MDc3NjA1ODk2Ng==', 'c2FyYW5nYUBnbWFpbC5jb20=', NULL, '1983-07-20', 2, 2, 'Srilankan', 'G.C.E A/L', 'Clerk', 'PAC002', 0, NULL),
('F000310', '2010-11-17', 'U2FjaGk=', 'TmlyYW5qYW5h', 'MTQ3LCBQZXJlcmEgUm9hZCwgR2FtcGFoYQ==', 'MTQ3LCBQZXJlcmEgUm9hZCwgR2FtcGFoYQ==', 'MTQ3LCBQZXJlcmEgUm9hZCwgR2FtcGFoYQ==', 'MDMzMjI2MjEwMA==', '', 'MDc3NjI1ODk2', '', '821401623V', '2000-07-12', 2, 2, 'Srilankan', 'CIMA', 'Student', 'PAC001', 0, '123'),
('F000409', '2009-11-11', 'S3VzdW0=', 'UmVudQ==', 'MjgsIERhbmRlbml5YXdhdHRhLCBLYXR1YmVkZGENCg==', 'MjgsIERhbmRlbml5YXdhdHRhLCBLYXR1YmVkZGENCg==', 'MjgsIERhbmRlbml5YXdhdHRhLCBLYXR1YmVkZGENCg==', 'MDM4MjU2MjQ3OA==', '', 'MDc3NjI1ODk2', '', NULL, '1978-04-20', 2, 1, 'Srilankan', '0', 'Unemployed', 'PAC001', 0, NULL),
('F000410', '2010-08-18', 'TGFsaXRoYQ==', 'SW5kZWV3YXJp', 'MjU2LCBNYWtvbGEgUm9hZCwgS2FkYXdhdGhh', 'MjU2LCBNYWtvbGEgUm9hZCwgS2FkYXdhdGhh', 'MjU2LCBNYWtvbGEgUm9hZCwgS2FkYXdhdGhh', 'MDExMjk1NjczNQ==', '', 'MDcyNTg5Nzg5', '', '', '1967-09-14', 2, 1, 'Srilankan', '6-10 Grade', 'CSW', 'PAC001', 0, ''),
('F000509', '2009-10-16', 'U2FtcGF0aCA=', 'QmFuZGFyYQ==', 'NzYsIFBhc2FsYSBJZGlyaXBpdGEsIE5pa2F3YXJhdGl5YQ0K', '', 'NzYsIFBhc2FsYSBJZGlyaXBpdGEsIE5pa2F3YXJhdGl5', 'MDM4MjU2MjQ3OA==', '', 'MDc3NjI1ODk2', '', NULL, '1979-03-14', 2, 4, '0', '6-10 Grade', 'CSW', 'PAC001', 0, NULL),
('F000510', '2010-07-27', 'U3VtdWR1', 'TGl5YW5hZ2U=', 'MTIgLCAxc3QgTGFuZSwgQ29sb21ibyAxMA==', 'MTIgLCAxc3QgTGFuZSwgQ29sb21ibyAxMA==', 'MTIgLCAxc3QgTGFuZSwgQ29sb21ibyAxMA==', 'MDExMjk1NjczNQ==', '', 'MDcyNTg5Nzg5', '', NULL, '0000-00-00', 2, 2, 'Srilankan', 'G.C.E A/L', 'Banking Officer', 'PAC001', 0, NULL),
('F000609', '2009-11-18', 'SGFyc2hhbmkg', 'U2FnYXJpa2Eg', 'NTk5LzEsIE1haGF3YWx1d2EsIFJhbHV3YSwgTWFkYW11bGFuYS', 'DQo1OTkvMSwgTWFoYXdhbHV3YSwgUmFsdXdhLCBNYWRhbXVsYW', '', 'MDMzMjI2MjEwMA==', '', 'MDc3NjI1ODk2', '', NULL, '0000-00-00', 2, 5, 'Srilankan', 'G.C.E A/L', 'CSW', 'PAC001', 0, NULL),
('F000610', '2010-07-27', 'U3VtYW5h', 'TWFkaXdha2E=', 'NzEsIEloYWxhIEJvbWlyaXlhLCBLYWR1d2VsYQ0K', 'NzEsIEloYWxhIEJvbWlyaXlhLCBLYWR1d2VsYQ0K', 'NzEsIEloYWxhIEJvbWlyaXlhLCBLYWR1d2VsYQ0K', 'MDExMjk1NjczNQ==', '', 'MDc3NjA1ODk2Ng==', '', NULL, '1977-10-19', 2, 3, 'Srilankan', '6-10 Grade', 'CSW', 'PAC001', 0, NULL),
('F000709', '2009-10-15', 'TmlsdWtzaGEg', 'UGVyZXJh', 'MzQvNSwgV2F0YXJha2EsIFBhZHVra2ENCg==', 'MzQvNSwgV2F0YXJha2EsIFBhZHVra2E=', 'MzQvNSwgV2F0YXJha2EsIFBhZHVra2E=', 'MDM4MjU2MjQ3OA==', '', 'MDc3NjA1ODk2Ng==', '', NULL, '1983-03-24', 2, 2, 'Srilankan', 'Degree', 'Student', 'PAC001', 0, NULL),
('F000710', '2010-03-09', 'SmF5YW5p', 'SGVtYWxhdGhh', 'MTE3LzczLCBBbW11bnVnb2RhLCBJbWJ1bGdvZGEsDQo=', 'MTE3LzczLCBBbW11bnVnb2RhLCBJbWJ1bGdvZGEs', 'MTE3LzczLCBBbW11bnVnb2RhLCBJbWJ1bGdvZGEs', 'MDM4MjU2MjQ3OA==', '', 'MDcyNTg5Nzg5', '', NULL, '1964-10-28', 2, 1, 'Srilankan', 'G.C.E A/L', 'CSW', 'PAC002', 0, NULL),
('F000810', '2010-08-03', 'TmF2b2Rh', 'UGVyZXJh', '', '', '', 'MDMzMjI2MjExMA==', '', '', '', NULL, '2010-08-26', 2, 4, 'Srilankan', 'G.C.E A/L', 'Unemployed', 'PAC001', 0, NULL),
('F000910', '2010-08-03', 'TGFzaXRo', 'TGl5YW5hZ2U=', '', '', '', 'MDMzMjI2MjExMA==', '', '', '', NULL, '1984-08-22', 2, 2, 'Srilankan', 'G.C.E O/L', 'Student', 'PAC001', 0, NULL),
('F001009', '2009-04-22', 'S00=', 'TWFsbGlrYQ==', 'MTk5LzEsIEt1dHRpd2lsYSwgS2lyaW5kaXdhbGENCg==', 'MTk5LzEsIEt1dHRpd2lsYSwgS2lyaW5kaXdhbGFhDQo=', 'MTk5LzEsIEt1dHRpd2lsYSwgS2lyaW5kaXdhbGE=', 'MDM4MjU2MjQ3OA==', '', 'MDcyNTg5Nzg5', '', NULL, '1977-07-21', 2, 1, 'Srilankan', 'G.C.E A/L', 'Unemployed', 'PAC001', 0, NULL),
('F001010', '2010-08-19', 'VGVzdA==', 'dGVzdA==', '', '', '', 'ZGZkZmQ=', '', '', 'c2FtZWVyYUBnbWFpbC5jb20=', '', '0000-00-00', 2, 0, 'Srilankan', '0', 'Student', 'PAC004', 0, ''),
('F001110', '2010-08-20', 'VGVzdA==', 'TGl5YW5hZ2U=', 'Z2c=', 'Z2c=', '', 'MDMzMjI2MDk0Mw==', '', '', '', '8888', '2010-08-10', 2, 0, 'Srilankan', '0', 'CSW', 'PAC001', 0, '123'),
('M000109', '2009-01-01', 'U2FtYW4=', 'U3VtYW5hc2lyaQ==', 'RCAxMjYvMSwgQmF0YWxhd2F0dGEsIEtlZ2FsbGUNCg==', 'RCAxMjYvMSwgQmF0YWxhd2F0dGEsIEtlZ2FsbGUNCg==', 'RCAxMjYvMSwgQmF0YWxhd2F0dGEsIEtlZ2FsbGUNCg==', 'MDExMjk1NjczNQ==', '', 'MDc3NjA1ODk2Ng==', '', NULL, '1976-01-14', 1, 1, 'Srilankan', 'G.C.E A/L', 'Business man', 'PAC001', 0, NULL),
('M000209', '2009-01-13', 'UmFuaWw=', 'RGhhcm1hc2lyaSA=', 'MTI1IFlha2FsYSBSb2FkLCBHYW1wYWhh', 'MTI1IFlha2FsYSBSb2FkLCBHYW1wYWhh', 'MTI1IFlha2FsYSBSb2FkLCBHYW1wYWhh', 'MDMzMjI2MjEwMA==', '', 'MDc3NjI1ODk2', '', NULL, '1974-02-20', 1, 1, 'Srilankan', 'G.C.E O/L', 'Police man', 'PAC001', 0, NULL),
('M000210', '2010-05-18', 'U2hhc2hpa2E=', 'TWFrYWR1cmE=', 'MjQ1LzEgVGVtcGxlIFJvYWQsIE1haGFyYWdhbWE=', 'MjQ1LzEgVGVtcGxlIFJvYWQsIE1haGFyYWdhbWE=', 'MjQ1LzEgVGVtcGxlIFJvYWQsIE1haGFyYWdhbWE=', 'MDExMjk1NjczNQ==', '', 'MDcyNTg5Nzg5', '', NULL, '0000-00-00', 1, 2, 'Srilankan', 'G.C.E A/L', 'Student', 'PAC001', 0, NULL),
('M000309', '2009-02-26', 'S2FtYWw=', 'UGVyZXJh', 'MjU2LzggUGFuYWxhIHJvYWQgLCBLdWxpeWFwaXRpeWE=', 'MjU2LzggUGFuYWxhIHJvYWQgLCBLdWxpeWFwaXRpeWE=', 'MjU2LzggUGFuYWxhIHJvYWQgLCBLdWxpeWFwaXRpeWE=', 'MDM4MjU2MjQ3OA==', '', 'MDcyNTg5Nzg5', '', NULL, '1982-03-10', 1, 2, 'Srilankan', 'Diploma', 'Student', 'PAC001', 0, NULL),
('M000310', '2010-07-27', 'U3VuaWw=', 'TmFuZGFzaXJp', 'MTczLCBIdWxtdWxsYSwgV2VsbGFtcGl0aXlhDQo=', 'MTczLCBIdWxtdWxsYSwgV2VsbGFtcGl0aXk=', 'MTczLCBIdWxtdWxsYSwgV2VsbGFtcGl0aXk=', 'MDMzMjI2MjEwMA==', '', 'MDc3NjA1ODk2Ng==', '', NULL, '1969-11-19', 1, 5, 'Srilankan', 'G.C.E A/L', 'Police man', 'PAC001', 0, NULL),
('M000409', '2009-07-15', 'VGhhcmFrYQ==', 'SmF5YXdhcmRhbmE=', 'MjcwLCBpaGFsYSBib21pcml5YSwga2FkdXdlbGEuDQo=', '', 'MjcwLCBpaGFsYSBib21pcml5YSwga2FkdXdlbGEuDQo=', 'MDM4MjU2MjQ3OA==', '', 'MDc3NjI1ODk2', '', NULL, '1966-03-23', 1, 1, 'Srilankan', 'G.C.E A/L', 'Engineer', 'PAC001', 0, NULL),
('M000410', '2010-08-26', 'R2F5YW4=', 'U3VkZXNo', 'MTMvMiwgUGF5YWxheWFnb2RhLCBHYW1wYWhh', 'MTMvMiwgUGF5YWxheWFnb2RhLCBHYW1wYWhh', 'MTMvMiwgUGF5YWxheWFnb2RhLCBHYW1wYWhh', 'MDMzMjI2MjEwMA==', '', 'MDcyNTg5Nzg5', '', '', '1982-11-25', 1, 2, 'Srilankan', 'G.C.E A/L', 'Business man', 'PAC001', 0, ''),
('M000509', '2009-07-16', '', 'U3VtYW5hZGFzYQ==', 'MTYxLzEsIE1pcmlzd2F0dGEsIFBlbGF3YXR0YQ0K', '', 'MTYxLzEsIE1pcmlzd2F0dGEsIFBlbGF3YXR0YQ==', 'MDMzMjI2MjEwMA==', '', 'MDc3NjA1ODk2Ng==', '', NULL, '1968-01-17', 1, 5, 'Srilankan', 'G.C.E A/L', 'Retired', 'PAC001', 0, NULL),
('M000510', '2010-08-17', 'VGhpbGluYQ==', 'TmlyYW5qYW4=', 'MjQ2LCBCYXNlIExpbmUgcmQsIERlbWF0YWdvZGENCg==', 'MjQ2LCBCYXNlIExpbmUgcmQsIERlbWF0YWdvZGE=', 'MjQ2LCBCYXNlIExpbmUgcmQsIERlbWF0YWdvZGE=', 'MDM0ODk5NDU3OA==', '', 'MDc3NjA1ODk2Ng==', '', NULL, '1986-10-15', 1, 5, 'Srilankan', 'G.C.E A/L', 'Clerk', 'PAC001', 0, NULL),
('M000609', '2009-04-08', 'Um9zaGFu', 'S3VtYXJh', 'MjU2LzUsIFNyaWRhbW1hIE13LCBDb2wtMDkuDQo=', '', 'MjU2LzUsIFNyaWRhbW1hIE13LCBDb2wtMDku', 'MDM0ODk5NDU3OA==', '', 'MDc3NjA1ODk2Ng==', '', NULL, '1976-05-27', 1, 1, 'Srilankan', 'G.C.E A/L', 'Army Officer', 'PAC001', 0, NULL),
('M000610', '2010-08-03', 'S2FtYWw=', 'UGVyZXJh', '', '', '', 'MDMzMjI2MDk0Mw==', '', '', '', NULL, '2010-08-18', 1, 2, 'Srilankan', '6-10 Grade', 'Student', 'PAC001', 0, NULL),
('M000709', '2009-07-16', 'TGFsaXRo', 'RGlzYW5heWFrYQ==', 'MzQvNSwgV2F0YXJha2EsIFBhZHVra2ENCg==', 'MzQvNSwgV2F0YXJha2EsIFBhZHVra2E=', 'MzQvNSwgV2F0YXJha2EsIFBhZHVra2E=', 'MDM4MjU2MjQ3OA==', '', 'MDcyNTg5Nzg5', '', NULL, '1967-08-24', 1, 1, 'Srilankan', 'G.C.E A/L', 'Accountant', 'PAC001', 0, NULL),
('M000710', '2010-08-03', 'S3VtdWR1', 'TGl5YW5hZ2U=', '', '', '', 'MDMzMjI2MDk0Mw==', '', '', '', NULL, '1986-08-20', 1, 2, 'Srilankan', 'No schooling / NA', 'Student', 'PAC005', 0, NULL),
('M000809', '2009-09-16', 'SmF5YW50aGE=', 'U2lsdmE=', 'MDMvMjgsIE1vZGFyYSwgTWF0dGFra3VsaXlhDQo=', 'MDMvMjgsIE1vZGFyYSwgTWF0dGFra3VsaXlhDQo=', 'MDMvMjgsIE1vZGFyYSwgTWF0dGFra3VsaXlh', 'MDExMjk1NjczNQ==', '', 'MDcyNTg5Nzg5', '', NULL, '1959-02-18', 1, 5, 'Srilankan', 'G.C.E O/L', 'Unemployed', 'PAC001', 0, NULL),
('M000810', '2010-08-03', 'Q2hhbWFyYQ==', 'VWR5YQ==', '', '', '', 'MDMzMjI2MDk0Mw==', '', '', '', NULL, '1974-08-22', 1, 1, '0', 'G.C.E A/L', 'Unemployed', 'PAC001', 0, NULL),
('M000909', '2009-03-18', 'V1Y=', 'V2lqZXRoaWxha2E=', 'Mi81MCwgWWFiYXJhbHV3YSwgTWFsd2FuYQ0K', 'Mi81MCwgWWFiYXJhbHV3YSwgTWFsd2FuYQ==', 'Mi81MCwgWWFiYXJhbHV3YSwgTWFsd2FuYQ0K', 'MDMzMjI2MjEwMA==', '', 'MDc3NjI1ODk2', '', NULL, '0000-00-00', 1, 1, 'Srilankan', 'G.C.E A/L', 'Driver', 'PAC001', 0, NULL),
('M000910', '2010-08-11', 'VGVzdA==', 'dGVzdA==', '', '', '', 'MDMzMjI2MjExMA==', '', '', '', NULL, '2010-08-18', 1, 1, 'Srilankan', '1-5 Grade', 'CSW', 'PAC001', 0, NULL),
('M001009', '2009-05-28', 'Q2hhbWFyYQ==', 'SmF5YXNvb3JpeWE=', 'MTE0LzY2LCBTaXdhbGkgUm9hZCwgQm9yZWxsYQ0K', 'MTE0LzY2LCBTaXdhbGkgUm9hZCwgQm9yZWxsYQ==', 'MTE0LzY2LCBTaXdhbGkgUm9hZCwgQm9yZWxsYQ==', 'MDM0ODk5NDU3OA==', '', 'MDc3NjI1ODk2', '', NULL, '1987-04-23', 1, 2, 'Srilankan', 'G.C.E A/L', 'Student', 'PAC001', 0, NULL),
('M001010', '2010-08-17', 'VGVzdA==', 'UGVyZXJh', '', '', '', 'MDMzMjI2MDk0Mw==', '', '', '', '8888', '2010-08-11', 1, 1, '', '1-5 Grade', 'Student', 'PAC001', 0, '77777777'),
('M001110', '2010-08-19', '', 'TGl5YW5hZ2U=', '', '', '', 'ZA==', '', '', 'c0BhLmNvbQ==', '', '0000-00-00', 1, 0, 'Srilankan', '0', 'Student', 'PAC003', 0, ''),
('M001210', '2010-08-19', '', 'TGl5YW5hZ2U=', '', '', '', 'MDMzMjI2MDk0Mw==', '', '', '', '', '0000-00-00', 1, 0, '0', '0', 'Student', 'PAC003', 0, ''),
('M001310', '2010-08-19', 'TGFzaXRo', 'MTIz', '', '', '', 'MDMzMjI2MjExMA==', '', '', '', '', '0000-00-00', 1, 0, 'Srilankan', '0', 'CSW', 'PAC002', 0, ''),
('M001410', '2010-08-21', '', 'VWR5YQ==', '', '', '', 'MDMzMjI2MDk0Mw==', '', '', '', '', '0000-00-00', 1, 0, 'Srilankan', '0', 'Student', 'PAC001', 0, ''),
('M001510', '2010-08-21', 'Q2hhbWFyYQ==', 'dGVzdA==', '', '', '', 'MDMzMjI2MDk0Mw==', '', '', '', '', '0000-00-00', 1, 0, 'Srilankan', '0', 'CSW', 'PAC005', 0, ''),
('M001610', '0000-00-00', 'UmFnYQ==', 'S2Fobg==', '', '', '', 'MDExMjc4OTc4OQ==', '', '', '', '', '0000-00-00', 1, 0, 'Srilankan', '0', 'Unemployed', 'PAC004', 0, ''),
('M001710', '2010-09-18', '', 'U2lyaXBhbGE=', '', '', '', '', '', '', '', '', '2010-09-08', 1, 0, 'Srilankan', '0', 'CSW', 'PAC009', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `patient_category`
--

CREATE TABLE IF NOT EXISTS `patient_category` (
  `category_id` varchar(20) NOT NULL,
  `patient_category` varchar(30) NOT NULL,
  `description` varchar(40) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `patient_category`
--

INSERT INTO `patient_category` (`category_id`, `patient_category`, `description`) VALUES
('PAC001', 'STD', 'STD'),
('PAC002', 'HIV', 'HIV'),
('PAC003', 'Completion of Episode of Care', 'Completion of Episode of Care'),
('PAC004', 'Examination', 'Examination'),
('PAC005', 'History', 'History'),
('PAC006', 'Summary Of Sexual History', 'Summary Of Sexual History'),
('PAC008', 'sdsdsd', 'sdsdsdsdsds'),
('PAC009', 'a', 'a');

-- --------------------------------------------------------

--
-- Table structure for table `patient_no_tracker`
--

CREATE TABLE IF NOT EXISTS `patient_no_tracker` (
  `year` year(4) NOT NULL,
  `male` int(11) NOT NULL,
  `female` int(11) NOT NULL,
  PRIMARY KEY (`year`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `patient_no_tracker`
--

INSERT INTO `patient_no_tracker` (`year`, `male`, `female`) VALUES
(2000, 0, 0),
(2009, 10, 10),
(2010, 17, 11);

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE IF NOT EXISTS `report` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `report`
--


-- --------------------------------------------------------

--
-- Table structure for table `rights`
--

CREATE TABLE IF NOT EXISTS `rights` (
  `user_group_id` varchar(20) NOT NULL,
  `module_id` varchar(20) NOT NULL,
  `adding` smallint(1) NOT NULL,
  `editing` smallint(1) NOT NULL,
  `deleting` smallint(1) NOT NULL,
  `viewing` smallint(1) NOT NULL,
  PRIMARY KEY (`user_group_id`,`module_id`),
  KEY `module_id` (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rights`
--

INSERT INTO `rights` (`user_group_id`, `module_id`, `adding`, `editing`, `deleting`, `viewing`) VALUES
('USG001', 'MOD001', 1, 1, 1, 1),
('USG001', 'MOD002', 1, 1, 1, 1),
('USG001', 'MOD003', 1, 1, 1, 1),
('USG001', 'MOD004', 1, 1, 1, 1),
('USG001', 'MOD005', 1, 1, 1, 1),
('USG002', 'MOD002', 1, 1, 1, 1),
('USG002', 'MOD003', 1, 1, 1, 1),
('USG002', 'MOD005', 1, 1, 1, 1),
('USG003', 'MOD002', 1, 1, 1, 1),
('USG003', 'MOD003', 1, 1, 1, 1),
('USG003', 'MOD005', 1, 1, 1, 1),
('USG004', 'MOD003', 1, 1, 1, 1),
('USG004', 'MOD004', 0, 0, 0, 1),
('USG004', 'MOD005', 1, 1, 1, 1),
('USG005', 'MOD005', 1, 1, 1, 1),
('USG006', 'MOD001', 0, 0, 0, 1),
('USG006', 'MOD002', 0, 0, 0, 1),
('USG006', 'MOD003', 0, 0, 0, 1),
('USG006', 'MOD005', 0, 0, 0, 1),
('USG009', 'MOD001', 0, 0, 0, 0),
('USG009', 'MOD002', 0, 0, 0, 1),
('USG009', 'MOD003', 0, 0, 0, 1),
('USG009', 'MOD005', 0, 0, 0, 1),
('USG011', 'MOD002', 0, 0, 0, 1),
('USG011', 'MOD003', 0, 0, 0, 1),
('USG011', 'MOD005', 0, 0, 0, 1),
('USG012', 'MOD001', 0, 0, 0, 0),
('USG012', 'MOD005', 0, 0, 0, 1),
('USG013', 'MOD003', 0, 0, 0, 1),
('USG014', 'MOD003', 0, 0, 0, 1),
('USG014', 'MOD005', 0, 0, 0, 1),
('USG015', 'MOD005', 0, 0, 0, 1),
('USG021', 'MOD002', 0, 0, 0, 1),
('USG021', 'MOD003', 0, 0, 0, 1),
('USG021', 'MOD005', 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `std_input`
--

CREATE TABLE IF NOT EXISTS `std_input` (
  `input_code` varchar(10) NOT NULL,
  `input_name` varchar(60) NOT NULL,
  `input_description` varchar(60) NOT NULL,
  `no_of_input` int(11) NOT NULL,
  `sex` smallint(1) NOT NULL,
  `input_category_code` varchar(10) NOT NULL,
  PRIMARY KEY (`input_code`),
  KEY `input_category_code` (`input_category_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `std_input`
--

INSERT INTO `std_input` (`input_code`, `input_name`, `input_description`, `no_of_input`, `sex`, `input_category_code`) VALUES
('INC001', '1. Existing Patient', '1. Existing Patient', 1, 1, 'SIC001'),
('INC002', '2. Highest Level Of Education', '2. Highest Level Of Education', 1, 1, 'SIC001'),
('INC003', '3. Occupation', '3. Occupation', 1, 3, 'SIC001'),
('INC004', '4. Reason For Attendence', '4. Reason For Attendence', 3, 3, 'SIC001'),
('INC005', '5. Symptoms', '5. Symptoms', 4, 3, 'SIC001'),
('INC006', '6. Duration of symptom/s (days)', '6. Duration of symptom/s (days)', 1, 3, 'SIC001'),
('INC007', '7. Medication (14 days)', '7. Medication (14 days)', 2, 3, 'SIC001'),
('INC008', '8. Contraception', '8. Contraception', 1, 2, 'SIC001'),
('INC009', '9. Menstrual Cycle', '9. Menstrual Cycle', 1, 2, 'SIC001'),
('INC010', '10. Pregnant', '10. Pregnant', 1, 2, 'SIC001'),
('INC011', '11. Miscarriage/ Still Birth', '11. Miscarriage/ Still Birth', 1, 2, 'SIC001'),
('INC012', '12. Termination of Pregnancy', '12. Termination of Pregnancy', 1, 2, 'SIC001'),
('INC013', '13. Sex contacts (12 months)', '13. Sex contacts (12 months)', 1, 3, 'SIC001'),
('INC014', '14. Type of partner (12 months)', '14. Type of partner (12 months)', 3, 3, 'SIC001'),
('INC015', '15. Sex of patner (12 months)', '15. Sex of patner (12 months)', 1, 3, 'SIC001'),
('INC016', '16. Number of partners (3 months)', '16. Number of partners (3 months)', 1, 3, 'SIC001'),
('INC017', '17. Condom Use at Last Sex', '17. Condom Use at Last Sex', 1, 3, 'SIC001'),
('INC018', '18. Condom Use (3m)', '18. Condom Use (3m)', 1, 3, 'SIC001'),
('INC019', '19. Substance Abuse (12m)', '19. Substance Abuse (12m)', 3, 3, 'SIC001'),
('INC020', '20. Previous STD', '20. Previous STD', 3, 3, 'SIC001'),
('INC021', '21. Blood Risk', '21. Blood Risk', 3, 3, 'SIC001'),
('INC022', '22. Ever had an HIV Test', '22. Ever had an HIV Test', 1, 3, 'SIC002'),
('INC023', '23. Age at First Sex', '23. Age at First Sex', 1, 3, 'SIC002'),
('INC024', '24. Signs', '24. Signs', 4, 3, 'SIC003'),
('INC025', '25. Circumcision', '25. Circumcision', 1, 1, 'SIC003'),
('INC026', '26. FPU Deposit Gram Stain', '26. FPU Deposit Gram Stain', 1, 1, 'SIC003'),
('INC027', '27. Dark Ground for TP', '27. Dark Ground for TP', 1, 3, 'SIC003'),
('INC028', '28. Giant Cells', '28. Giant Cells', 1, 3, 'SIC003'),
('INC029', '29. Urethral Smear', '29. Urethral Smear', 1, 3, 'SIC003'),
('INC030', '30. Urethral GC Culture', '30. Urethral GC Culture', 1, 3, 'SIC003'),
('INC031', '31. Urethral Chlamydia', '31. Urethral Chlamydia', 1, 3, 'SIC003'),
('INC032', '32. Vaginal Smear', '32. Vaginal Smear', 4, 2, 'SIC003'),
('INC033', '33. Cervical Smear', '33. Cervical Smear', 1, 2, 'SIC003'),
('INC034', '34. Cervical GC Culture', '34. Cervical GC Culture', 1, 2, 'SIC003'),
('INC035', '35. Cervical Chlamydia', '35. Cervical Chlamydia', 1, 2, 'SIC003'),
('INC036', '36. Pap Smear', '36. Pap Smear', 4, 2, 'SIC003'),
('INC037', '37. Throat GC Culture', '37. Throat GC Culture', 1, 3, 'SIC003'),
('INC038', '38. Rectal GC Culture', '38. Rectal GC Culture', 1, 3, 'SIC003'),
('INC039', '39. HSV Ag ELISA', '39. HSV Ag ELISA', 1, 3, 'SIC003'),
('INC040', '40. HSV Culture', '40. HSV Culture', 1, 3, 'SIC003'),
('INC041', '41. HSV Serology', '41. HSV Serology', 1, 3, 'SIC003'),
('INC042', '42. VDRL', '42. VDRL', 1, 3, 'SIC003'),
('INC043', '43. TPPA / TPHA', '43. TPPA / TPHA', 1, 3, 'SIC003'),
('INC044', '44. HIV Screening Test', '44. HIV Screening Test', 1, 3, 'SIC003'),
('INC045', '45. HIV Confirm. Test', '45. HIV Confirm. Test', 1, 3, 'SIC004'),
('INC046', '46. Hep Bs Ag', '46. Hep Bs Ag', 1, 3, 'SIC004'),
('INC047', '47. Hep C Ab', '47. Hep C Ab', 1, 3, 'SIC004'),
('INC048', '48. Etiological Diagnosis of Current Episode of Care', '48. Etiological Diagnosis of Current Episode of Care', 6, 3, 'SIC004'),
('INC049', '49. Syndrome', '49. Syndrome', 4, 3, 'SIC004'),
('INC050', '50. Treatment', '50. Treatment', 4, 3, 'SIC004'),
('INC051', '51. Status of Episode', '51. Status of Episode', 1, 1, 'SIC004'),
('INC052', '52. No of Visits', '52. No of Visits', 1, 3, 'SIC004'),
('INC053', 'g', 'ffgf', 2, 1, 'SIC002');

-- --------------------------------------------------------

--
-- Table structure for table `std_input_category`
--

CREATE TABLE IF NOT EXISTS `std_input_category` (
  `input_category_code` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(50) NOT NULL,
  PRIMARY KEY (`input_category_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `std_input_category`
--

INSERT INTO `std_input_category` (`input_category_code`, `name`, `description`) VALUES
('SIC001', 'Summary Of Sexual History', 'Summary Of Sexual History'),
('SIC002', 'Examination', 'Examination'),
('SIC003', 'Investigation', 'Investigation'),
('SIC004', 'Completion of Episode of Care', 'Completion of episode of care'),
('SIC008', 's', 's'),
('SIC009', '', ''),
('SIC010', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `std_input_result`
--

CREATE TABLE IF NOT EXISTS `std_input_result` (
  `input_result_code` int(11) NOT NULL AUTO_INCREMENT,
  `result_description` varchar(20) NOT NULL,
  `input_code` varchar(10) NOT NULL,
  PRIMARY KEY (`input_result_code`),
  KEY `input_code` (`input_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=296 ;

--
-- Dumping data for table `std_input_result`
--

INSERT INTO `std_input_result` (`input_result_code`, `result_description`, `input_code`) VALUES
(1, 'Yes', 'INC001'),
(2, '1. 1-5 grade', 'INC002'),
(3, '2. 6-10 grade', 'INC002'),
(4, '3.G.C.E O/L', 'INC002'),
(5, '4.G.C.E A/L', 'INC002'),
(6, '5. Dip / Degree', 'INC002'),
(7, '6. No Schooling / NA', 'INC002'),
(8, '1.U/E', 'INC003'),
(9, '2.Student', 'INC003'),
(10, '3.CSW', 'INC003'),
(11, '4.Retired', 'INC003'),
(12, '5.Employee As', 'INC003'),
(13, '1.Vaoluntary', 'INC004'),
(14, '2.ref. OPD', 'INC004'),
(15, '3.Ref. Ward', 'INC004'),
(16, '4.Ref. GP', 'INC004'),
(17, '5.Ref. Courts', 'INC004'),
(18, '6. Ref. Blood Bank', 'INC004'),
(19, '7. Contact', 'INC004'),
(20, '8. Clinic Followup', 'INC004'),
(21, '9. Medico - legal', 'INC004'),
(22, '10. Other', 'INC004'),
(23, '1.None', 'INC005'),
(24, '2. Genital Disch.', 'INC005'),
(25, '3.Dysuria', 'INC005'),
(26, '4.Warts', 'INC005'),
(27, '5.Genital ulcer', 'INC005'),
(28, '6.Rash', 'INC005'),
(29, '7.Scrotal swelling', 'INC005'),
(30, '8.Pelvic pain', 'INC005'),
(31, '9.Others', 'INC005'),
(32, '1.N/A', 'INC006'),
(33, '2.1-3', 'INC006'),
(34, '3.4-7', 'INC006'),
(35, '4.8-14', 'INC006'),
(36, '5.Over 14', 'INC006'),
(37, '6.Unknown', 'INC006'),
(38, '1.None', 'INC007'),
(39, '2.Antibiotic', 'INC007'),
(40, '3. Others/NK', 'INC007'),
(41, '1. None/NA', 'INC008'),
(42, '2.IUCD', 'INC008'),
(43, '3.Oral', 'INC008'),
(44, '4.Condom', 'INC008'),
(45, '5.Tubal ligation', 'INC008'),
(46, '6.Injection', 'INC008'),
(47, '7.Natural', 'INC008'),
(48, '8.Others', 'INC008'),
(49, '1.Regular LMP', 'INC009'),
(50, '2.Non regular Durati', 'INC009'),
(51, '3. NA', 'INC009'),
(52, '1. No/NA', 'INC010'),
(53, '2.Yes', 'INC010'),
(54, '3.Uncertain', 'INC010'),
(55, '1. No/NA', 'INC011'),
(56, '2.Yes', 'INC011'),
(57, '1. No/NA', 'INC012'),
(58, '2.Yes', 'INC012'),
(59, '1. None/NA', 'INC013'),
(60, '2.Sri Lanka', 'INC013'),
(61, '3.Overseas', 'INC013'),
(62, '4.(2 & 3)', 'INC013'),
(63, '1. None/NA', 'INC014'),
(64, '2. Marital/Regular P', 'INC014'),
(65, '3. Non-Regular P', 'INC014'),
(66, '4. Commercial Partne', 'INC014'),
(67, '1.Male only', 'INC015'),
(68, '2.Male & Female', 'INC015'),
(69, '3.Female only', 'INC015'),
(70, '4. None/NA', 'INC015'),
(71, '1.One', 'INC016'),
(72, '2.Two', 'INC016'),
(73, '3.Three', 'INC016'),
(74, '4.Four', 'INC016'),
(75, '5.Five or more', 'INC016'),
(76, '6. None/NA', 'INC016'),
(77, '1. NA', 'INC017'),
(78, '2. No', 'INC017'),
(79, '3. Yes', 'INC017'),
(80, '1. NA', 'INC018'),
(81, '2. Never', 'INC018'),
(82, '3. Sometimes', 'INC018'),
(83, '4. Always', 'INC018'),
(84, '1. None/NA', 'INC019'),
(85, '2. Nacotics (Inhalat', 'INC019'),
(86, '3. Alcohol', 'INC019'),
(87, '4. IDU', 'INC019'),
(88, '1. None', 'INC020'),
(89, '2. GC', 'INC020'),
(90, '3. Syphilis', 'INC020'),
(91, '4. Herpes', 'INC020'),
(92, '5. Chlamydia/NGC', 'INC020'),
(93, '6. Warts', 'INC020'),
(94, '7. Others/Not Sure', 'INC020'),
(95, '1. None', 'INC021'),
(96, '2. Blood / blood Pro', 'INC021'),
(97, '3. Needle Prick', 'INC021'),
(98, '4. Other', 'INC021'),
(99, '1. Never', 'INC022'),
(100, '2. Negative', 'INC022'),
(101, '3. Positive', 'INC022'),
(102, '4. Indeterminate', 'INC022'),
(103, '5. Tested but Result', 'INC022'),
(104, '6. Dont know', 'INC022'),
(105, 'Age in Years', 'INC023'),
(106, '1. None', 'INC024'),
(107, '2. Gen. Discharge', 'INC024'),
(108, '3. Inguinal LN', 'INC024'),
(109, '4. Genrtal Warts', 'INC024'),
(110, '5. Genital Ulcer', 'INC024'),
(111, '6. Rash', 'INC024'),
(112, '8. Scrotal Swelling', 'INC024'),
(113, '7. Pelvic', 'INC024'),
(114, '9. Others', 'INC024'),
(115, '10. Not Exam.', 'INC024'),
(116, '1. No', 'INC025'),
(117, '2. Yes', 'INC025'),
(118, '1. Not done/NA', 'INC026'),
(119, '2. Pus cells < 10', 'INC026'),
(120, '3. Pus cells >= 10', 'INC026'),
(121, '4. Other', 'INC026'),
(122, '1. Not Done', 'INC027'),
(123, '2. Negative', 'INC027'),
(124, '3. Positive', 'INC027'),
(125, '1. Not Done', 'INC028'),
(126, '2. Negative', 'INC028'),
(127, '3. Positive', 'INC028'),
(128, '1. Not Done', 'INC029'),
(129, '2. ICGND', 'INC029'),
(130, '3. <5p/hpf/NAD', 'INC029'),
(131, '4. 5-9p/hpf', 'INC029'),
(132, '5. 10+/hpf', 'INC029'),
(133, '6. Other', 'INC029'),
(134, '1. Not Done', 'INC030'),
(135, '2. Negative', 'INC030'),
(136, '3. Positive', 'INC030'),
(137, '4. Report NA', 'INC030'),
(138, '1. Not Done', 'INC031'),
(139, '2. Negative', 'INC031'),
(140, '3. Positive', 'INC031'),
(141, '4. Indeterminate', 'INC031'),
(142, '5. Report NA', 'INC031'),
(143, '1. Not Done', 'INC032'),
(144, '2. Negative', 'INC032'),
(145, '3. ICGND', 'INC032'),
(146, '4. Candida', 'INC032'),
(147, '5. Trich', 'INC032'),
(148, '6. Clue Cell', 'INC032'),
(149, '7. Lactobacilli not ', 'INC032'),
(150, '8. 6 & 7', 'INC032'),
(151, '9. Other not seen.', 'INC032'),
(152, '1. Not Done', 'INC033'),
(153, '2. ICGND', 'INC033'),
(154, '3. Pus cells<30', 'INC033'),
(155, '4. Pus cells >=30', 'INC033'),
(156, '5.Other', 'INC033'),
(157, '1. Not Done', 'INC034'),
(158, '2. Negative', 'INC034'),
(159, '3. Positive', 'INC034'),
(160, '4. Report NA', 'INC034'),
(161, '1. Not Done', 'INC035'),
(162, '2. Negative', 'INC035'),
(163, '3. Positive', 'INC035'),
(164, '4. Indeterminate', 'INC035'),
(165, '5. Report NA', 'INC035'),
(166, '1.Not done', 'INC036'),
(167, '2. Unsatisfactory', 'INC036'),
(168, '3. NILM', 'INC036'),
(169, '4. LSIL', 'INC036'),
(170, '5. HSIL', 'INC036'),
(171, '6. ASCUS', 'INC036'),
(172, '7. AGC', 'INC036'),
(173, '8. BEC>40 yrs', 'INC036'),
(174, '9. S/G M', 'INC036'),
(175, '10. Koilocytes.', 'INC036'),
(176, '11. TV.', 'INC036'),
(177, '12. Clue cells', 'INC036'),
(178, '13. Candida', 'INC036'),
(179, '14. NSI', 'INC036'),
(180, '15. Report NA', 'INC036'),
(181, '1.Not done', 'INC037'),
(182, '2.Negative', 'INC037'),
(183, '3. GC', 'INC037'),
(184, '4. Report NA', 'INC037'),
(185, '1.Not done', 'INC038'),
(186, '2.Negative', 'INC038'),
(187, '3. GC', 'INC038'),
(188, '4. Report NA', 'INC038'),
(189, '1.Not done', 'INC039'),
(190, '2.Negative', 'INC039'),
(191, '3. Positive', 'INC039'),
(192, '4. Report NA', 'INC039'),
(193, '1.Not done', 'INC040'),
(194, '2. Negative', 'INC040'),
(195, '3. Positive (type 1', 'INC040'),
(196, '4. Report NA', 'INC040'),
(197, '1.Not done', 'INC041'),
(198, '2. Negative', 'INC041'),
(199, '3. Positive (type 1 ', 'INC041'),
(200, '4. Report NA', 'INC041'),
(201, '1.Not done', 'INC042'),
(202, '2. Non Reactive', 'INC042'),
(203, '3. Previous Reactive', 'INC042'),
(204, '4. Reactive', 'INC042'),
(205, '5. Report NA', 'INC042'),
(206, '1.Not done', 'INC043'),
(207, '2. Non Reactive', 'INC043'),
(208, '3. Prev. reactive (t', 'INC043'),
(209, '4. Reactive', 'INC043'),
(210, '5. Equivocal', 'INC043'),
(211, '6. Report NA', 'INC043'),
(212, '1. Not Done', 'INC044'),
(213, '2. Negative', 'INC044'),
(214, '3. Previ. Positive', 'INC044'),
(215, '4. Positive', 'INC044'),
(216, '5. Inconclusive', 'INC044'),
(217, '6. Report NA', 'INC044'),
(218, '1. Not Done', 'INC045'),
(219, '2. Negative', 'INC045'),
(220, '3. Known Positive', 'INC045'),
(221, '4. Positive', 'INC045'),
(222, '5. Inconclusive', 'INC045'),
(223, '6. Report NA', 'INC045'),
(224, '1. Not Done', 'INC046'),
(225, '2. Negative', 'INC046'),
(226, '3. Prev. Positive', 'INC046'),
(227, '4. Positive', 'INC046'),
(228, '5 Report NA', 'INC046'),
(229, '1. Not Done', 'INC047'),
(230, '2. Negative', 'INC047'),
(231, '3. Prev. Positive', 'INC047'),
(232, '4. Positive', 'INC047'),
(233, '5 Report NA', 'INC047'),
(234, '1. No illness', 'INC048'),
(235, '2. HIV Positive', 'INC048'),
(236, '3. GC', 'INC048'),
(237, '4. Early Syphilis', 'INC048'),
(238, '5. Late Syphilis', 'INC048'),
(239, '6. Cong Syphilis', 'INC048'),
(240, '7. Herpes', 'INC048'),
(241, '8. Chlamydia', 'INC048'),
(242, '9. NGI/NSGI', 'INC048'),
(243, '10. Trichomoniasis', 'INC048'),
(244, '11. Warts', 'INC048'),
(245, '12. Pubic Lice', 'INC048'),
(246, '13. Scabies', 'INC048'),
(247, '14. Candida', 'INC048'),
(248, '15. Bacterial Vagino', 'INC048'),
(249, '16. Epididymitis', 'INC048'),
(250, '17. Molluscum', 'INC048'),
(251, '18. Opth. neonatorum', 'INC048'),
(252, '19. Other STD', 'INC048'),
(253, '21. Uncertain', 'INC048'),
(256, '1. NA', 'INC049'),
(257, '2. GUD - non vesicul', 'INC049'),
(258, '3. GUD - vesicular', 'INC049'),
(259, '4. Opth. neonatorum', 'INC049'),
(260, '5. Vaginal discharge', 'INC049'),
(261, '6. Lower abd. pain', 'INC049'),
(262, '7. Urethral discharg', 'INC049'),
(263, '8. Scrotal swelling', 'INC049'),
(264, '9. Other', 'INC049'),
(265, '1. None', 'INC050'),
(266, '2. Penicillin', 'INC050'),
(267, '3. Doxycycline', 'INC050'),
(268, '4. Cryotherapy', 'INC050'),
(269, '5. Podophyllin', 'INC050'),
(270, '6. TCA - Trichloroac', 'INC050'),
(271, '7. Metranidazole', 'INC050'),
(272, '8. Scabicides', 'INC050'),
(273, '9. Macrolides', 'INC050'),
(274, '10. Cephalosporins', 'INC050'),
(275, '11. Quinolones', 'INC050'),
(276, '12. Antifungals', 'INC050'),
(277, '13. Aciclovir', 'INC050'),
(278, '14. Cotrimoxazole', 'INC050'),
(279, '15. Others', 'INC050'),
(280, '1. Completed', 'INC051'),
(281, '2. Referred', 'INC051'),
(282, '3. Defaulted', 'INC051'),
(283, '4. Episode to be con', 'INC051'),
(284, '5. Other', 'INC051'),
(285, '1. One', 'INC052'),
(286, '2. Two', 'INC052'),
(287, '3. Three', 'INC052'),
(288, '4. Four', 'INC052'),
(289, '5. Five', 'INC052'),
(290, '6. Six', 'INC052'),
(291, '7. Seven', 'INC052'),
(292, 'ff', 'INC053'),
(294, 'dfdfdfdfdfd', 'INC053'),
(295, 'No', 'INC001');

-- --------------------------------------------------------

--
-- Table structure for table `std_result`
--

CREATE TABLE IF NOT EXISTS `std_result` (
  `patient_no` varchar(20) NOT NULL,
  `episode_no` int(11) NOT NULL,
  `input_code` varchar(10) NOT NULL,
  `result_code` int(11) NOT NULL,
  `input_category_code` varchar(10) NOT NULL,
  PRIMARY KEY (`patient_no`,`episode_no`,`input_code`,`result_code`),
  KEY `result_code` (`result_code`),
  KEY `input_code` (`input_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `std_result`
--

INSERT INTO `std_result` (`patient_no`, `episode_no`, `input_code`, `result_code`, `input_category_code`) VALUES
('F000109', 1, 'INC048', 237, 'SIC004'),
('F000109', 1, 'INC048', 239, 'SIC004'),
('F000110', 1, 'INC048', 235, 'SIC004'),
('F000110', 1, 'INC048', 237, 'SIC004'),
('F000110', 1, 'INC048', 238, 'SIC004'),
('F000110', 1, 'INC048', 239, 'SIC004'),
('F000110', 1, 'INC048', 244, 'SIC004'),
('F000110', 1, 'INC051', 280, 'SIC004'),
('F000209', 1, 'INC048', 237, 'SIC004'),
('F000209', 1, 'INC048', 238, 'SIC004'),
('F000209', 1, 'INC048', 239, 'SIC004'),
('F000210', 1, 'INC050', 266, 'SIC004'),
('F000210', 1, 'INC050', 270, 'SIC004'),
('F000210', 1, 'INC050', 274, 'SIC004'),
('F000210', 1, 'INC050', 278, 'SIC004'),
('F000310', 1, 'INC048', 234, 'SIC004'),
('F000310', 1, 'INC048', 235, 'SIC004'),
('F000310', 3, 'INC001', 1, 'SIC001'),
('F000310', 3, 'INC048', 247, 'SIC004'),
('F000410', 1, 'INC051', 284, 'SIC004'),
('F000510', 1, 'INC004', 15, 'SIC001'),
('F000510', 1, 'INC007', 40, 'SIC001'),
('F000510', 1, 'INC008', 42, 'SIC001'),
('F000510', 1, 'INC010', 53, 'SIC001'),
('F000510', 1, 'INC013', 60, 'SIC001'),
('F000510', 1, 'INC015', 68, 'SIC001'),
('F000510', 1, 'INC016', 72, 'SIC001'),
('F000510', 1, 'INC017', 78, 'SIC001'),
('F000510', 1, 'INC019', 84, 'SIC001'),
('F000510', 1, 'INC021', 96, 'SIC001'),
('F000510', 1, 'INC036', 167, 'SIC003'),
('F000510', 1, 'INC036', 171, 'SIC003'),
('F000510', 1, 'INC036', 174, 'SIC003'),
('F000510', 1, 'INC038', 185, 'SIC003'),
('F000510', 1, 'INC048', 240, 'SIC004'),
('F000510', 1, 'INC050', 270, 'SIC004'),
('F000510', 1, 'INC050', 271, 'SIC004'),
('F000510', 1, 'INC050', 272, 'SIC004'),
('F000510', 1, 'INC051', 280, 'SIC004'),
('F000510', 1, 'INC052', 290, 'SIC004'),
('F000610', 1, 'INC048', 235, 'SIC004'),
('F000610', 1, 'INC048', 236, 'SIC004'),
('F000610', 1, 'INC048', 239, 'SIC004'),
('F000610', 1, 'INC048', 243, 'SIC004'),
('F000610', 1, 'INC048', 247, 'SIC004'),
('F000610', 1, 'INC048', 251, 'SIC004'),
('F000610', 1, 'INC050', 265, 'SIC004'),
('F000610', 1, 'INC050', 266, 'SIC004'),
('F000610', 1, 'INC050', 267, 'SIC004'),
('F000610', 1, 'INC050', 268, 'SIC004'),
('F000610', 1, 'INC050', 269, 'SIC004'),
('F000610', 1, 'INC050', 270, 'SIC004'),
('F000610', 1, 'INC050', 271, 'SIC004'),
('F000610', 1, 'INC050', 272, 'SIC004'),
('F000610', 1, 'INC050', 273, 'SIC004'),
('F000610', 1, 'INC050', 274, 'SIC004'),
('F000610', 1, 'INC050', 275, 'SIC004'),
('F000610', 1, 'INC050', 276, 'SIC004'),
('F000610', 1, 'INC050', 277, 'SIC004'),
('F000610', 1, 'INC050', 278, 'SIC004'),
('F000610', 1, 'INC050', 279, 'SIC004'),
('F000610', 1, 'INC051', 280, 'SIC004'),
('F000710', 1, 'INC048', 239, 'SIC004'),
('F000710', 1, 'INC048', 240, 'SIC004'),
('F000710', 1, 'INC051', 280, 'SIC004'),
('F000710', 1, 'INC052', 287, 'SIC004'),
('F000810', 1, 'INC002', 2, 'SIC001'),
('F000810', 1, 'INC003', 10, 'SIC001'),
('F000810', 1, 'INC004', 18, 'SIC001'),
('F000810', 1, 'INC005', 24, 'SIC001'),
('F000810', 1, 'INC048', 238, 'SIC004'),
('F000810', 1, 'INC048', 239, 'SIC004'),
('F000810', 1, 'INC048', 240, 'SIC004'),
('F000810', 1, 'INC051', 281, 'SIC004'),
('F000810', 1, 'INC052', 287, 'SIC004'),
('M000109', 1, 'INC048', 240, 'SIC004'),
('M000210', 1, 'INC004', 13, 'SIC001'),
('M000210', 1, 'INC052', 291, 'SIC004'),
('M000310', 1, 'INC022', 99, 'SIC002'),
('M000310', 1, 'INC048', 237, 'SIC004'),
('M000310', 1, 'INC048', 238, 'SIC004'),
('M000310', 1, 'INC048', 239, 'SIC004'),
('M000409', 1, 'INC050', 265, 'SIC004'),
('M000409', 1, 'INC050', 266, 'SIC004'),
('M000409', 1, 'INC050', 267, 'SIC004'),
('M000409', 1, 'INC050', 268, 'SIC004'),
('M000409', 1, 'INC050', 269, 'SIC004'),
('M000409', 1, 'INC050', 270, 'SIC004'),
('M000409', 1, 'INC050', 271, 'SIC004'),
('M000409', 1, 'INC050', 272, 'SIC004'),
('M000409', 1, 'INC050', 273, 'SIC004'),
('M000409', 1, 'INC050', 274, 'SIC004'),
('M000409', 1, 'INC050', 275, 'SIC004'),
('M000409', 1, 'INC050', 276, 'SIC004'),
('M000409', 1, 'INC050', 277, 'SIC004'),
('M000409', 1, 'INC050', 278, 'SIC004'),
('M000409', 1, 'INC050', 279, 'SIC004'),
('M000410', 1, 'INC048', 237, 'SIC004'),
('M000410', 1, 'INC048', 247, 'SIC004'),
('M000410', 1, 'INC051', 282, 'SIC004'),
('M000410', 1, 'INC052', 286, 'SIC004'),
('M001310', 1, 'INC021', 95, 'SIC001'),
('M001310', 1, 'INC051', 280, 'SIC004'),
('M001310', 2, 'INC048', 234, 'SIC004'),
('M001310', 2, 'INC048', 238, 'SIC004'),
('M001310', 2, 'INC048', 242, 'SIC004'),
('M001310', 2, 'INC048', 246, 'SIC004'),
('M001410', 2, 'INC048', 240, 'SIC004'),
('M001510', 1, 'INC048', 235, 'SIC004'),
('M001510', 1, 'INC048', 238, 'SIC004'),
('M001510', 1, 'INC048', 239, 'SIC004'),
('M001510', 1, 'INC051', 280, 'SIC004'),
('M001510', 1, 'INC052', 287, 'SIC004');

-- --------------------------------------------------------

--
-- Table structure for table `sys_module`
--

CREATE TABLE IF NOT EXISTS `sys_module` (
  `module_id` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(20) NOT NULL,
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sys_module`
--

INSERT INTO `sys_module` (`module_id`, `name`, `description`) VALUES
('MOD001', 'Admin', 'Administration'),
('MOD002', 'Registration', 'Registration'),
('MOD003', 'Consultancy', 'Consultancy'),
('MOD004', 'Inquiry', 'Inquiry'),
('MOD005', 'Report', 'Report');

-- --------------------------------------------------------

--
-- Table structure for table `trace_contact`
--

CREATE TABLE IF NOT EXISTS `trace_contact` (
  `patient_no` varchar(40) NOT NULL,
  `slip_no` int(11) NOT NULL,
  `contact_details` text NOT NULL,
  PRIMARY KEY (`patient_no`,`slip_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `trace_contact`
--

INSERT INTO `trace_contact` (`patient_no`, `slip_no`, `contact_details`) VALUES
('F000310', 0, ''),
('F000310', 2, 'dfdddddddddddddd'),
('F000310', 3, 'sdfdfdfdfdfd');

-- --------------------------------------------------------

--
-- Table structure for table `uniqueid`
--

CREATE TABLE IF NOT EXISTS `uniqueid` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `last_id` int(10) NOT NULL,
  `tbl` varchar(50) NOT NULL,
  `tbl_column` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `uniqueid`
--

INSERT INTO `uniqueid` (`id`, `last_id`, `tbl`, `tbl_column`) VALUES
(1, 21, 'user_group', 'user_group_id'),
(2, 13, 'user', 'user_id'),
(3, 10, 'std_input_category', 'input_category_code'),
(4, 53, 'std_input', 'input_code'),
(5, 295, 'std_input_result', 'input_result_code'),
(6, 10, 'patient_category', 'category_id');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` varchar(20) NOT NULL,
  `user_name` varchar(45) NOT NULL,
  `user_password` varchar(45) NOT NULL,
  `is_admin` char(3) NOT NULL,
  `status` varchar(20) NOT NULL,
  `user_group_id` varchar(20) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `user_group_id` (`user_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_name`, `user_password`, `is_admin`, `status`, `user_group_id`) VALUES
('USR001', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'No', 'Enabled', 'USG001'),
('USR002', 'janitha', '4531e8924edde928f341f7df3ab36c70', 'No', 'Enabled', 'USG002'),
('USR003', 'kasun', '4531e8924edde928f341f7df3ab36c70', 'No', 'Enabled', 'USG021'),
('USR004', 'umesha', '4531e8924edde928f341f7df3ab36c70', 'No', 'Enabled', 'USG003'),
('USR005', 'banu', '4531e8924edde928f341f7df3ab36c70', 'No', 'Enabled', 'USG004'),
('USR006', 'dimuthu', '4531e8924edde928f341f7df3ab36c70', 'No', 'Enabled', 'USG005'),
('USR007', 'ravini', '4531e8924edde928f341f7df3ab36c70', 'No', 'Enabled', 'USG002'),
('USR008', 'sachini', '4531e8924edde928f341f7df3ab36c70', 'No', 'Enabled', 'USG004'),
('USR009', 'saman', '4531e8924edde928f341f7df3ab36c70', 'No', 'Enabled', 'USG003'),
('USR010', 'lahiru', '8d5e957f297893487bd98fa830fa6413', 'No', 'Enabled', 'USG003'),
('USR011', 'dsds', '437599f1ea3514f8969f161a6606ce18', 'No', 'Enabled', 'USG005'),
('USR012', 'adminff', '21232f297a57a5a743894a0e4a801fc3', 'No', 'Enabled', 'USG001'),
('USR013', 'hansi', '4531e8924edde928f341f7df3ab36c70', 'No', 'Enabled', 'USG006');

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

CREATE TABLE IF NOT EXISTS `user_group` (
  `user_group_id` varchar(20) NOT NULL,
  `user_group_name` varchar(30) NOT NULL,
  `description` varchar(60) NOT NULL,
  PRIMARY KEY (`user_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_group`
--

INSERT INTO `user_group` (`user_group_id`, `user_group_name`, `description`) VALUES
('USG001', 'Admin', 'All the administration tasks are handled by this group'),
('USG002', 'PHI', 'All the tasks related to PHI are done by this group'),
('USG003', 'Sister', 'All the tasks related to sisters are done by this group'),
('USG004', 'Doctor', 'Tasks related to the doctors are handled by this group'),
('USG005', 'Clerica Staff', 'Clerical works are handled by this group'),
('USG006', 'Admin Group 2', 'Admin Group 2'),
('USG009', 'PHI Group 2', 'PHI Group 2'),
('USG011', 'Sister Group 2', 'Sister Group 2'),
('USG012', 'Guest1', 'Guest1'),
('USG013', 'Guest2', 'Guest2'),
('USG014', 'Research 1', 'Research 1'),
('USG015', 'Research 2', 'Research 2'),
('USG016', 'Student', 'Student'),
('USG017', 'Public', 'Public'),
('USG018', 'Consulatnts', 'Consulatnts'),
('USG019', 'Consultants 2', 'Consultants 2'),
('USG020', 'PIMS1', 'PIMS Group 1'),
('USG021', 'PHI2', 'PHI2');

-- --------------------------------------------------------

--
-- Table structure for table `visit`
--

CREATE TABLE IF NOT EXISTS `visit` (
  `patient_no` varchar(20) NOT NULL,
  `episode_no` int(11) NOT NULL,
  `visit_no` int(11) NOT NULL,
  `appointed_date` date NOT NULL,
  `visited_date` date NOT NULL,
  `next_visit_date` date NOT NULL,
  PRIMARY KEY (`patient_no`,`episode_no`,`visit_no`),
  KEY `episode_no` (`episode_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `visit`
--

INSERT INTO `visit` (`patient_no`, `episode_no`, `visit_no`, `appointed_date`, `visited_date`, `next_visit_date`) VALUES
('F000310', 2, 1, '2010-07-29', '2010-07-29', '0000-00-00'),
('F000310', 3, 1, '2010-07-30', '2010-07-30', '0000-00-00'),
('F001110', 1, 1, '2010-08-20', '2010-08-20', '2010-08-24'),
('F001110', 1, 2, '2010-08-24', '2010-08-26', '2010-08-27'),
('F001110', 1, 3, '2010-08-27', '2010-08-27', '2010-08-30'),
('F001110', 1, 4, '2010-08-30', '2010-08-31', '2010-09-16'),
('F001110', 1, 5, '2010-09-16', '0000-00-00', '0000-00-00'),
('M000610', 2, 1, '2010-08-03', '2010-08-03', '0000-00-00'),
('M001210', 2, 1, '2010-08-19', '2010-08-19', '0000-00-00'),
('M001310', 1, 1, '2010-08-19', '2010-08-19', '2010-08-20'),
('M001310', 1, 2, '2010-08-20', '2010-08-30', '2010-08-31'),
('M001310', 1, 3, '2010-08-31', '2010-09-08', '2010-09-23'),
('M001310', 1, 4, '2010-09-23', '0000-00-00', '0000-00-00'),
('M001310', 2, 1, '2010-08-19', '2010-08-19', '2010-08-20'),
('M001310', 2, 2, '2010-08-20', '2010-08-20', '2010-08-21'),
('M001310', 2, 3, '2010-08-21', '2010-08-26', '2010-08-31'),
('M001310', 2, 4, '2010-08-31', '2010-09-01', '2010-09-16'),
('M001310', 2, 5, '2010-09-16', '0000-00-00', '0000-00-00'),
('M001410', 1, 1, '2010-08-21', '2010-08-21', '2010-08-24'),
('M001410', 1, 2, '2010-08-24', '2010-08-27', '2010-08-31'),
('M001410', 1, 3, '2010-08-31', '0000-00-00', '0000-00-00'),
('M001410', 2, 1, '2010-08-21', '2010-08-21', '2010-08-24'),
('M001410', 2, 2, '2010-08-24', '0000-00-00', '0000-00-00'),
('M001510', 1, 1, '2010-08-21', '2010-08-21', '2010-08-24'),
('M001510', 1, 2, '2010-08-24', '0000-00-00', '0000-00-00'),
('M001610', 1, 1, '2010-09-09', '2010-09-09', '0000-00-00'),
('M001710', 1, 1, '2010-09-09', '2010-09-09', '0000-00-00');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `clinic_reason`
--
ALTER TABLE `clinic_reason`
  ADD CONSTRAINT `clinic_reason_ibfk_1` FOREIGN KEY (`patient_no`) REFERENCES `patient` (`patient_no`) ON DELETE CASCADE,
  ADD CONSTRAINT `clinic_reason_ibfk_2` FOREIGN KEY (`episode_no`) REFERENCES `episode` (`episode_no`) ON DELETE CASCADE;

--
-- Constraints for table `contact_mode`
--
ALTER TABLE `contact_mode`
  ADD CONSTRAINT `contact_mode_ibfk_1` FOREIGN KEY (`patient_no`) REFERENCES `patient` (`patient_no`) ON DELETE CASCADE;

--
-- Constraints for table `episode`
--
ALTER TABLE `episode`
  ADD CONSTRAINT `episode_ibfk_1` FOREIGN KEY (`patient_no`) REFERENCES `patient` (`patient_no`) ON DELETE CASCADE;

--
-- Constraints for table `rights`
--
ALTER TABLE `rights`
  ADD CONSTRAINT `rights_ibfk_3` FOREIGN KEY (`user_group_id`) REFERENCES `user_group` (`user_group_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rights_ibfk_4` FOREIGN KEY (`module_id`) REFERENCES `sys_module` (`module_id`) ON DELETE CASCADE;

--
-- Constraints for table `std_input`
--
ALTER TABLE `std_input`
  ADD CONSTRAINT `std_input_ibfk_1` FOREIGN KEY (`input_category_code`) REFERENCES `std_input_category` (`input_category_code`) ON DELETE CASCADE;

--
-- Constraints for table `std_input_result`
--
ALTER TABLE `std_input_result`
  ADD CONSTRAINT `std_input_result_ibfk_1` FOREIGN KEY (`input_code`) REFERENCES `std_input` (`input_code`) ON DELETE CASCADE;

--
-- Constraints for table `std_result`
--
ALTER TABLE `std_result`
  ADD CONSTRAINT `std_result_ibfk_3` FOREIGN KEY (`input_code`) REFERENCES `std_input` (`input_code`) ON DELETE CASCADE,
  ADD CONSTRAINT `std_result_ibfk_4` FOREIGN KEY (`result_code`) REFERENCES `std_input_result` (`input_result_code`) ON DELETE CASCADE,
  ADD CONSTRAINT `std_result_ibfk_5` FOREIGN KEY (`patient_no`) REFERENCES `patient` (`patient_no`) ON DELETE CASCADE;

--
-- Constraints for table `trace_contact`
--
ALTER TABLE `trace_contact`
  ADD CONSTRAINT `trace_contact_ibfk_1` FOREIGN KEY (`patient_no`) REFERENCES `patient` (`patient_no`) ON DELETE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`user_group_id`) REFERENCES `user_group` (`user_group_id`) ON DELETE CASCADE;

--
-- Constraints for table `visit`
--
ALTER TABLE `visit`
  ADD CONSTRAINT `visit_ibfk_1` FOREIGN KEY (`patient_no`) REFERENCES `patient` (`patient_no`) ON DELETE CASCADE,
  ADD CONSTRAINT `visit_ibfk_2` FOREIGN KEY (`episode_no`) REFERENCES `episode` (`episode_no`) ON DELETE CASCADE;
