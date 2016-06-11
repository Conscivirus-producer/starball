drop table t_coupon;
ALTER TABLE  `t_orderitem` ADD  `brandId` INT( 32 ) NOT NULL COMMENT  '用来获取品牌打折信息' AFTER  `itemName`;

-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 年 06 月 11 日 04:42
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
-- 表的结构 `t_coupon`
--

CREATE TABLE IF NOT EXISTS `t_coupon` (
  `couponId` int(32) NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL COMMENT '代码,可以有相同的代码,不同的记录.',
  `type` varchar(10) NOT NULL COMMENT '1-打折卡;2-满减金额CNY;3-满减金额HKD;',
  `condition` varchar(100) NOT NULL COMMENT '优惠的条件值,如果为打折卡,必须为brandId等于这个值;如果为满減金额,必须为购买商品totalAmount大于等于这个值',
  `discount` varchar(50) NOT NULL COMMENT '如果为打折卡,为打折百分数,比如70代表打7折;如果为满减金额,',
  `startDate` datetime NOT NULL,
  `endDate` datetime NOT NULL,
  PRIMARY KEY (`couponId`),
  KEY `code` (`code`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='优惠码信息表' AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


ALTER TABLE  `t_order` ADD  `couponCode` VARCHAR( 100 ) NOT NULL COMMENT  '优惠码,参考数据表t_coupon->code,没使用则为空' AFTER  `orderNumber`;