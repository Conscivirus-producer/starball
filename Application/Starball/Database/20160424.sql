ALTER TABLE  `t_hotitem` CHANGE  `type`  `type` VARCHAR( 20 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT  '首页：H, F, MLH, MLF, MR 精选：S';
ALTER TABLE  `t_hotitem` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 年 04 月 21 日 14:19
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
-- 表的结构 `t_supportingdata`
--

CREATE TABLE IF NOT EXISTS `t_supportingdata` (
  `key` varchar(50) NOT NULL,
  `value` varchar(500) NOT NULL,
  `remark` varchar(500) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='存放公共的配置,key/value的开工';

--
-- 转存表中的数据 `t_supportingdata`
--

INSERT INTO `t_supportingdata` (`key`, `value`, `remark`) VALUES
('GIFT_PACKAGE_PRICE_CNY', '20', '礼品包装费用人民币价格'),
('GIFT_PACKAGE_PRICE_HKD', '25', '礼品包装费用港币价格');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

ALTER TABLE  `t_order` ADD  `giftPackageFee` VARCHAR( 50 ) NOT NULL COMMENT  '礼品包装费用' AFTER  `shippingFee`;
ALTER TABLE `t_order` DROP `isGiftPackage`;