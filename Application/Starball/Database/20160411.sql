ALTER TABLE  `t_order` CHANGE  `status`  `status` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'B-backlog(此状态为购物车), N-New新订单(确定下单，未支付), P-Paid(已支付，未发货), C-Cancelled(已取消), D-deliveried(已发货), V-Verified(已收货),';
ALTER TABLE  `t_orderitem` CHANGE  `status`  `status` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'B-backlog,未发货;D-Deliveried已发货;C-Cancel申请退货;R-Returned,已收到退货';
-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 年 04 月 11 日 07:52
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
-- 表的结构 `t_orderbill`
--

CREATE TABLE IF NOT EXISTS `t_orderbill` (
  `billId` int(32) NOT NULL AUTO_INCREMENT,
  `orderNumber` varchar(100) NOT NULL,
  `createdDate` datetime NOT NULL,
  `title` varchar(200) NOT NULL,
  `totalAmount` varchar(50) NOT NULL,
  `channel` varchar(20) NOT NULL,
  `subChannel` varchar(20) NOT NULL,
  `type` varchar(10) NOT NULL COMMENT 'PAY-支付;REFUND-退款',
  `status` varchar(10) NOT NULL COMMENT 'S-success;F-failed',
  `lastUpdatedDate` datetime NOT NULL,
  PRIMARY KEY (`billId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
