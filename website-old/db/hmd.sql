-- phpMyAdmin SQL Dump
-- version 3.2.0
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 12, 2010 at 06:58 PM
-- Server version: 5.1.45
-- PHP Version: 5.3.2
--
-- howmanydead.org database schema
--

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: 'hmd'
--

-- --------------------------------------------------------

--
-- Table structure for table 'user'
--

CREATE TABLE `user` (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `password` varchar(40) NOT NULL,
  full_name varchar(255) NOT NULL,
  email varchar(255) NOT NULL,
  PRIMARY KEY (id)
);

