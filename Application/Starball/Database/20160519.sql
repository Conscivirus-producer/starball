-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 年 05 月 19 日 13:28
-- 服务器版本: 5.5.20
-- PHP 版本: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `starball_schema`
--

-- --------------------------------------------------------

--
-- 表的结构 `t_user_socialmedia`
--

CREATE TABLE IF NOT EXISTS `t_user_socialmedia` (
  `socialMediaId` int(32) NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `userId` int(32) NOT NULL COMMENT 'reference user Id',
  `type` varchar(50) NOT NULL COMMENT 'social media name, like WEIXIN,SINA,FACEBOOK',
  `openid` varchar(50) NOT NULL COMMENT 'id provided by the channel',
  `name` varchar(50) NOT NULL COMMENT 'user name',
  `nick` varchar(100) NOT NULL COMMENT 'user nick name',
  `headImgUrl` varchar(200) NOT NULL,
  `reference` varchar(500) NOT NULL COMMENT 'other information if need, stored in json format',
  PRIMARY KEY (`socialMediaId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='third party login information' AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
