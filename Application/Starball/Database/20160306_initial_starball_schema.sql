-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 年 03 月 06 日 08:26
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
-- 表的结构 `t_brand`
--

CREATE TABLE IF NOT EXISTS `t_brand` (
  `brandId` int(32) NOT NULL AUTO_INCREMENT,
  `brandName` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  PRIMARY KEY (`brandId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `t_category`
--

CREATE TABLE IF NOT EXISTS `t_category` (
  `categoryId` int(32) NOT NULL AUTO_INCREMENT,
  `parentCategoryId` int(32) NOT NULL,
  `categoryName` varchar(50) NOT NULL,
  `targetCustomerType` varchar(50) NOT NULL,
  PRIMARY KEY (`categoryId`),
  KEY `parentCategoryId` (`parentCategoryId`),
  KEY `targetCustomerType` (`targetCustomerType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `t_hotitem`
--

CREATE TABLE IF NOT EXISTS `t_hotitem` (
  `hotId` int(32) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL COMMENT 'H, F, MLH, MLF, MR',
  `image` varchar(200) NOT NULL COMMENT '图片链接',
  `title` varchar(100) NOT NULL,
  `subtitle` varchar(100) NOT NULL,
  `targetItemLink` varchar(200) NOT NULL COMMENT '点击后跳到的目标页面',
  `addtionalLink` varchar(200) NOT NULL COMMENT '底部的区域有一个统一购买的链接，或者留做其它的需要',
  `lastUpdatedDate` datetime NOT NULL,
  `active` varchar(10) NOT NULL COMMENT '0或1，表明这条记录是否有效',
  `sequence` int(10) NOT NULL,
  PRIMARY KEY (`hotId`),
  KEY `type` (`type`,`active`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=43 ;

--
-- 转存表中的数据 `t_hotitem`
--

INSERT INTO `t_hotitem` (`hotId`, `type`, `image`, `title`, `subtitle`, `targetItemLink`, `addtionalLink`, `lastUpdatedDate`, `active`, `sequence`) VALUES
(22, 'H', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/little-eleven-paris-slide-spring-summer-2016.jpg', 'LITTLE ELEVEN PARIS', '秋冬系列', '', '', '2016-03-06 16:03:06', '1', 1),
(23, 'H', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/scotch-soda-slide-spring-summer-2016.jpg', 'SCOTCH&SODA', '春夏系列', '', '', '2016-03-06 16:03:06', '1', 2),
(24, 'H', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/slide2-kenzo-kids-printemps-ete.jpg', 'KENZO KIDS', '春夏系列', '', '', '2016-03-06 16:03:06', '1', 3),
(25, 'H', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/monnalisa-slide-spring-summer-2016.jpg', 'MONNALISA', '春夏系列', '', '', '2016-03-06 16:03:06', '1', 4),
(26, 'H', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/magazine-botanique2-slide-spring-summer-2016.jpg', 'BONTNIC', '春夏系列', '', '', '2016-03-06 16:03:06', '1', 5),
(27, 'MLH', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/bar-jeans-405-spring-summer-2016-2.jpg', '牛仔系列', '单宁世界', '', '', '2016-03-06 16:03:06', '1', 7),
(28, 'MLH', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/gucci-405-spring-summer-2016.jpg', 'GUCCI', '单宁世界', '', '', '2016-03-06 16:03:06', '1', 8),
(29, 'MLF', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/archimede-998-spring-summer-2016.jpg', 'ARCHIMEDE', '单宁世界', '', '', '2016-03-06 16:03:06', '1', 9),
(30, 'MR', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/marguerite-style-spring-summer-2016.jpg', 'LE MEGAZINE', 'THE HISTORY OF DAISIES', '', '', '2016-03-06 16:03:06', '1', 10),
(31, 'MR', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/botanique-cabinet-curiosites-spring-summer-2016.jpg', 'LE MEGAZINE', 'THE CABINET OF CURIOSITIES', '', '', '2016-03-06 16:03:06', '1', 11),
(32, 'MR', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/barrio-latino-style-spring-summer-2016.jpg', 'LE MEGAZINE', 'BARRIO LATINO', '', '', '2016-03-06 16:03:06', '1', 12),
(38, 'F', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/coup-de-coeur-botany1.jpg', '一见钟情', 'BOTANY', 'http://s.amazeui.org/media/i/demos/bing-1.jpg', '', '2016-03-06 16:17:18', '1', 13),
(39, 'F', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/coup-de-coeur-botany2.jpg', '一见钟情', 'BOTANY', 'http://s.amazeui.org/media/i/demos/bing-2.jpg', '', '2016-03-06 16:17:18', '1', 14),
(40, 'F', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/coup-de-coeur-botany3.jpg', '一见钟情', 'BOTANY', 'http://s.amazeui.org/media/i/demos/bing-3.jpg', '', '2016-03-06 16:17:18', '1', 15),
(41, 'F', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/coup-de-coeur-botany4.jpg', '一见钟情', 'BOTANY', 'http://s.amazeui.org/media/i/demos/bing-4.jpg', '', '2016-03-06 16:17:18', '1', 16),
(42, 'F', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/coup-de-coeur-botany5.jpg', '一见钟情', 'BOTANY', 'http://s.amazeui.org/media/i/demos/bing-4.jpg', '', '2016-03-06 16:17:18', '1', 17);

-- --------------------------------------------------------

--
-- 表的结构 `t_image`
--

CREATE TABLE IF NOT EXISTS `t_image` (
  `imageId` int(32) NOT NULL AUTO_INCREMENT,
  `itemId` int(32) NOT NULL,
  `image` varchar(200) NOT NULL,
  `type` varchar(50) NOT NULL COMMENT '图片类型，normal,big,small',
  `sequence` int(2) NOT NULL COMMENT '如为小图，有多张，图片的展示顺序',
  PRIMARY KEY (`imageId`),
  KEY `itemId` (`itemId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `t_inventory`
--

CREATE TABLE IF NOT EXISTS `t_inventory` (
  `itemId` int(32) NOT NULL AUTO_INCREMENT,
  `price` float NOT NULL,
  `size` varchar(50) NOT NULL,
  PRIMARY KEY (`itemId`),
  KEY `size` (`size`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `t_item`
--

CREATE TABLE IF NOT EXISTS `t_item` (
  `itemId` int(32) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `color` varchar(100) NOT NULL,
  `detailDescription` varchar(1000) NOT NULL,
  `component` varchar(1000) NOT NULL,
  `brandId` int(32) NOT NULL,
  `categoryId` int(32) NOT NULL,
  `lastUpdatedDate` datetime NOT NULL,
  `lowestPrice` float NOT NULL,
  `isAvailable` varchar(2) NOT NULL,
  `link` varchar(200) NOT NULL,
  `season` varchar(50) NOT NULL,
  PRIMARY KEY (`itemId`),
  KEY `brandId` (`brandId`,`categoryId`),
  KEY `season` (`season`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `t_size`
--

CREATE TABLE IF NOT EXISTS `t_size` (
  `sizeId` int(32) NOT NULL AUTO_INCREMENT,
  `age` varchar(10) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `targetCustomerType` varchar(50) NOT NULL,
  `height` varchar(50) NOT NULL,
  PRIMARY KEY (`sizeId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `t_tag`
--

CREATE TABLE IF NOT EXISTS `t_tag` (
  `itemId` int(32) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `tagName` varchar(100) NOT NULL,
  PRIMARY KEY (`itemId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `t_user`
--

CREATE TABLE IF NOT EXISTS `t_user` (
  `userId` int(32) NOT NULL AUTO_INCREMENT,
  `userName` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `lastUpdatedDate` datetime NOT NULL,
  `lastIp` varchar(50) NOT NULL,
  PRIMARY KEY (`userId`),
  UNIQUE KEY `userName` (`userName`,`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
