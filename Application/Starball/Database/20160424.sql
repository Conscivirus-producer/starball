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
ALTER TABLE  `t_itemprice` ADD  `inventoryId` INT( 32 ) NOT NULL COMMENT  '参考t_inventory->inventoryId,对应尺码' AFTER  `itemId` ,
ADD INDEX (  `inventoryId` );

DELETE FROM `t_itemprice` WHERE 1;
ALTER TABLE  `t_itemprice` CHANGE  `price`  `price` FLOAT NOT NULL;

INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-1,-1,'99.99','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-1,-1,'83.9916','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-1,-2,'100','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-1,-2,'84','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-1,-3,'101','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-1,-3,'84.84','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-1,-4,'102','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-1,-4,'85.68','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-2,-5,'103','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-2,-5,'86.52','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-2,-6,'104','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-2,-6,'87.36','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-2,-7,'105','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-2,-7,'88.2','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-2,-8,'106','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-2,-8,'89.04','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-4,-9,'107','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-4,-9,'89.88','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-4,-10,'108','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-4,-10,'90.72','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-4,-11,'109','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-4,-11,'91.56','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-4,-12,'110','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-4,-12,'92.4','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-6,-13,'111','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-6,-13,'93.24','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-6,-14,'112','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-6,-14,'94.08','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-6,-15,'113','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-6,-15,'94.92','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-6,-16,'114','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-6,-16,'95.76','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-7,-17,'115','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-7,-17,'96.6','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-7,-18,'116','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-7,-18,'97.44','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-7,-19,'117','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-7,-19,'98.28','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-7,-20,'118','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-7,-20,'99.12','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-9,-21,'119','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-9,-21,'99.96','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-9,-22,'120','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-9,-22,'100.8','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-9,-23,'121','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-9,-23,'101.64','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-9,-24,'122','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-9,-24,'102.48','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-10,-25,'123','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-10,-25,'103.32','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-10,-26,'124','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-10,-26,'104.16','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-10,-27,'125','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-10,-27,'105','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-10,-28,'126','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-10,-28,'105.84','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-11,-29,'127','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-11,-29,'106.68','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-11,-30,'128','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-11,-30,'107.52','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-11,-31,'129','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-11,-31,'108.36','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-11,-32,'130','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-11,-32,'109.2','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-14,-33,'131','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-14,-33,'110.04','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-14,-34,'132','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-14,-34,'110.88','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-14,-35,'133','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-14,-35,'111.72','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-14,-36,'134','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-14,-36,'112.56','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-15,-37,'135','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-15,-37,'113.4','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-15,-38,'136','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-15,-38,'114.24','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-15,-39,'137','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-15,-39,'115.08','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-15,-40,'138','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-15,-40,'115.92','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-18,-41,'139','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-18,-41,'116.76','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-18,-42,'140','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-18,-42,'117.6','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-18,-43,'141','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-18,-43,'118.44','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-18,-44,'142','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-18,-44,'119.28','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-19,-45,'143','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-19,-45,'120.12','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-19,-46,'144','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-19,-46,'120.96','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-19,-47,'145','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-19,-47,'121.8','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-19,-48,'146','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-19,-48,'122.64','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-20,-49,'147','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-20,-49,'123.48','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-20,-50,'148','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-20,-50,'124.32','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-20,-51,'149','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-20,-51,'125.16','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-20,-52,'150','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-20,-52,'126','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-21,-53,'151','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-21,-53,'126.84','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-21,-54,'152','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-21,-54,'127.68','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-21,-55,'153','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-21,-55,'128.52','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-21,-56,'154','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-21,-56,'129.36','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-22,-57,'155','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-22,-57,'130.2','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-22,-58,'156','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-22,-58,'131.04','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-22,-59,'157','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-22,-59,'131.88','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-22,-60,'158','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-22,-60,'132.72','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-25,-61,'159','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-25,-61,'133.56','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-25,-62,'160','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-25,-62,'134.4','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-25,-63,'161','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-25,-63,'135.24','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-25,-64,'162','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-25,-64,'136.08','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-27,-65,'163','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-27,-65,'136.92','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-27,-66,'164','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-27,-66,'137.76','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-27,-67,'165','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-27,-67,'138.6','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-27,-68,'166','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-27,-68,'139.44','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-28,-69,'167','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-28,-69,'140.28','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-28,-70,'168','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-28,-70,'141.12','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-28,-71,'169','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-28,-71,'141.96','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-28,-72,'170','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-28,-72,'142.8','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-29,-73,'171','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-29,-73,'143.64','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-29,-74,'172','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-29,-74,'144.48','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-29,-75,'173','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-29,-75,'145.32','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-29,-76,'174','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-29,-76,'146.16','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-30,-77,'175','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-30,-77,'147','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-30,-78,'176','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-30,-78,'147.84','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-30,-79,'177','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-30,-79,'148.68','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-30,-80,'178','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-30,-80,'149.52','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-31,-81,'179','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-31,-81,'150.36','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-31,-82,'180','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-31,-82,'151.2','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-31,-83,'181','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-31,-83,'152.04','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-31,-84,'182','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-31,-84,'152.88','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-32,-85,'183','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-32,-85,'153.72','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-32,-86,'184','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-32,-86,'154.56','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-32,-87,'185','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-32,-87,'155.4','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-32,-88,'186','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-32,-88,'156.24','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-33,-89,'187','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-33,-89,'157.08','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-33,-90,'188','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-33,-90,'157.92','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-33,-91,'189','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-33,-91,'158.76','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-33,-92,'190','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-33,-92,'159.6','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-34,-93,'191','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-34,-93,'160.44','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-34,-94,'192','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-34,-94,'161.28','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-34,-95,'193','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-34,-95,'162.12','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-34,-96,'194','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-34,-96,'162.96','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-36,-97,'195','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-36,-97,'163.8','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-36,-98,'196','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-36,-98,'164.64','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-36,-99,'197','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-36,-99,'165.48','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-36,-100,'198','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-36,-100,'166.32','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-37,-101,'199','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-37,-101,'167.16','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-37,-102,'200','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-37,-102,'168','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-37,-103,'201','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-37,-103,'168.84','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-37,-104,'202','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-37,-104,'169.68','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-38,-105,'203','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-38,-105,'170.52','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-38,-106,'204','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-38,-106,'171.36','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-38,-107,'205','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-38,-107,'172.2','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-38,-108,'206','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-38,-108,'173.04','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-40,-109,'207','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-40,-109,'173.88','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-40,-110,'208','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-40,-110,'174.72','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-40,-111,'209','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-40,-111,'175.56','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-40,-112,'210','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-40,-112,'176.4','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-41,-113,'211','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-41,-113,'177.24','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-41,-114,'212','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-41,-114,'178.08','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-41,-115,'213','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-41,-115,'178.92','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-41,-116,'214','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-41,-116,'179.76','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-43,-117,'215','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-43,-117,'180.6','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-43,-118,'216','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-43,-118,'181.44','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-43,-119,'217','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-43,-119,'182.28','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-43,-120,'218','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-43,-120,'183.12','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-44,-121,'219','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-44,-121,'183.96','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-44,-122,'220','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-44,-122,'184.8','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-44,-123,'221','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-44,-123,'185.64','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-44,-124,'222','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-44,-124,'186.48','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-46,-125,'223','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-46,-125,'187.32','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-46,-126,'224','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-46,-126,'188.16','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-46,-127,'225','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-46,-127,'189','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-46,-128,'226','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-46,-128,'189.84','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-47,-129,'227','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-47,-129,'190.68','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-47,-130,'228','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-47,-130,'191.52','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-47,-131,'229','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-47,-131,'192.36','CNY');
INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-47,-132,'230','HKD');	INSERT INTO `t_itemprice`(`itemId`,`inventoryId`, `price`, `currency`) VALUES (-47,-132,'193.2','CNY');

ALTER TABLE `t_inventory` DROP INDEX `size`;