ALTER TABLE  `t_order` CHANGE  `status`  `status` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'N-New新订单(新订单，等同于购物车状态，未支付), P-Paid(已支付，未发货), D-Deliveried已发货(已取消的商品不能继续发货); V-Verified(客户已收货); C1-申请退款(未发货，只有在P的状态下才可以) C2-退款成功(卖方同意退款)';
ALTER TABLE  `t_orderitem` CHANGE  `status`  `status` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'N-New,等待发货; P-Paid(已支付，未发货), D-Deliveried已发货; V-Verified(客户已收货); C1-申请退款(未发货，只有在P的状态下才可以) C2-退款成功(卖方同意退款)';

-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 年 04 月 28 日 04:41
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
-- 表的结构 `t_ordercancel`
--

CREATE TABLE IF NOT EXISTS `t_ordercancel` (
  `cancelId` int(32) NOT NULL AUTO_INCREMENT,
  `orderNumber` varchar(50) NOT NULL,
  `orderItemId` int(32) NOT NULL,
  `quantity` varchar(10) NOT NULL,
  `amount` varchar(50) NOT NULL,
  `status` varchar(10) NOT NULL COMMENT 'B-Backlog,发起退货; N-New,卖家同意退货; V-Verified,卖家已收到退货; D-Done,退款成功 C-取消退货',
  `createdDate` datetime NOT NULL,
  `lastUpdatedDate` datetime NOT NULL,
  PRIMARY KEY (`cancelId`),
  UNIQUE KEY `orderNumber` (`orderNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
