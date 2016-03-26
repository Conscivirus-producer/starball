-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 年 03 月 24 日 15:40
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

--
-- 转存表中的数据 `t_brand`
--

INSERT INTO `t_brand` (`brandId`, `brandName`, `description`) VALUES
(-8, 'ARMANI JUNIOR 阿玛尼少年', 'Launched in 1975, Armani made his mark on the fashion world with a recognizable elegance and simplicity that goes hand in hand with the designer’s aesthetic. Synonymous with Italian savoir-faire, understated chic and revolutionary design, Armani embodies a lifestyle with several sub-brands for adults and children. Pulling design inspiration from the menswear collections, Armani Junior takes on the brand’s iconic codes for boy’s collections filled with classic, easy style.'),
(-6, 'PEPE JEANS', 'Straight from London, British brand Pepe Jeans rose to stardom in the 1970s with jeans that were anything but the norm. Back then, the young and fashion conscious were lining up under a railway bridge to get their hands on a pair of unique, urban jeans. Today, the brand has risen to the top of the global fashion pack with denim styles for adults and children that are classic, cool and casual. Season after season, Pepe Jeans keeps kids and teens stocked with all the basics they need, for a signature and coveted British edge.'),
(-5, 'MOLO', '从0岁到14岁，小潮人们与Molo一起成长。自2003年起，这个丹麦品牌就用令成人们嫉妒的出色单品占领了孩子们的衣柜。女孩，男孩和婴儿都能够在这个实用又有趣，富有特色和T台风格的品牌中找到他们最爱的单品。从夏季街头服饰到冬季滑雪系列，这个潮流先锋引领了一场又一场时尚革命。出街最佳伙伴？就是Molo。'),
(-4, 'IKKS', '由Gérard Legoff成立于1987年，法式品牌IKKS在进军童装界十年后才发展出成人产品线。由于将运动风格带入最新系列，IKKS赢得了好口碑，并成为舒适又时尚的国际范例品牌，特质就是：很好穿！最新的印花基础款：t-shirts, 毛衣，牛仔裤，夹克和鞋... 男孩，女孩和baby要和IKKS一起紧跟潮流。'),
(-3, 'CATIMINI', '由Monique和Paul Salmon创立于1972年，法国品牌Catimini就是为趣味、创意、色彩和好奇心而生。孩子们总是热爱探索并充满想象力，Catimini着力于用精品服饰陪伴孩子们成长，并让他们的独特个性得到完美彰显。对于女孩和男孩，Catimini的设计就是充满青春活力的衣橱的完美注脚。'),
(-2, 'REPLAY&SONS', 'Established in 1981 and belonging to the Fashion Box Corporation, Replay & Sons is one of the leading international companies in the denim sector. An Italian company, Replay & Sons creates casual, everyday wear for boys and girls. Filled with the Replay & Sons’ recognizable urban-chic aesthetic, from boy’s and girl’s denim and reworked outdoors wear, to classic t-shirts and more, Replay & Sons stands by a single quote: Jeans are more than just a piece of clothing because every pair exclusively mirrors and represents the wearer – just like a strand of DNA.'),
(-1, 'GUCCI', '1921年，Guccio Gucci在意大利佛罗伦萨创立了Gucci，这个时代最举世瞩目的品牌之一。除了主打制作精良的男装和女装，Gucci在2011年开始发行童装线。以马术装为灵感，并将其标志性双G标志完美演绎，Gucci的童装线绝对是给小淑女和小绅士的衣柜锦上添花。');

-- --------------------------------------------------------

--
-- 表的结构 `t_category`
--

CREATE TABLE IF NOT EXISTS `t_category` (
  `categoryId` int(32) NOT NULL AUTO_INCREMENT,
  `parentCategoryId` int(32) NOT NULL,
  `categoryName` varchar(50) NOT NULL,
  `type` varchar(10) NOT NULL COMMENT '1-普通商品; 2-鞋子; 3-配饰',
  PRIMARY KEY (`categoryId`),
  KEY `parentCategoryId` (`parentCategoryId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `t_category`
--

INSERT INTO `t_category` (`categoryId`, `parentCategoryId`, `categoryName`, `type`) VALUES
(-14, -2, '运动鞋', '1'),
(-13, 0, '开襟衫', '1'),
(-12, 0, '连衣裙', '1'),
(-11, 0, '浴用品', '1'),
(-10, 0, '婴儿房', '1'),
(-9, 0, '婴儿床', '1'),
(-8, 0, '睡衣', '1'),
(-7, 0, '泳装', '1'),
(-6, 0, '套装', '1'),
(-5, 0, '上衣和T恤', '1'),
(-4, -1, '系带鞋', '1'),
(-3, -1, '芭蕾舞鞋', '1'),
(-2, 0, '童鞋', '1'),
(-1, 0, '鞋', '1');

-- --------------------------------------------------------

--
-- 表的结构 `t_coupon`
--

CREATE TABLE IF NOT EXISTS `t_coupon` (
  `couponId` int(32) NOT NULL AUTO_INCREMENT,
  `itemId` int(32) NOT NULL,
  `userId` int(32) NOT NULL,
  `code` varchar(50) NOT NULL,
  `discountRate` int(3) NOT NULL,
  `expirationDate` datetime NOT NULL,
  PRIMARY KEY (`couponId`),
  KEY `itemId` (`itemId`,`code`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='优惠券' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `t_favoriteitem`
--

CREATE TABLE IF NOT EXISTS `t_favoriteitem` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `itemId` int(32) NOT NULL,
  `userId` int(32) NOT NULL,
  `updatedDate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `itemId` (`itemId`,`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='我的收藏' AUTO_INCREMENT=1 ;

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
  `sequence` int(2) NOT NULL COMMENT '如为小图，有多张，图片的展示顺序',
  PRIMARY KEY (`imageId`),
  KEY `itemId` (`itemId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `t_image`
--

INSERT INTO `t_image` (`imageId`, `itemId`, `image`, `sequence`) VALUES
(-150, -51, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 150),
(-149, -51, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 149),
(-148, -51, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 148),
(-147, -50, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 147),
(-146, -50, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 146),
(-145, -50, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 145),
(-144, -49, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 144),
(-143, -49, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 143),
(-142, -49, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 142),
(-141, -48, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 141),
(-140, -48, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 140),
(-139, -48, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 139),
(-138, -47, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 138),
(-137, -47, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 137),
(-136, -47, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 136),
(-135, -46, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 135),
(-134, -46, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 134),
(-133, -46, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 133),
(-132, -45, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 132),
(-131, -45, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 131),
(-130, -45, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 130),
(-129, -44, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 129),
(-128, -44, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 128),
(-127, -44, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 127),
(-126, -43, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 126),
(-125, -43, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 125),
(-124, -43, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 124),
(-123, -42, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 123),
(-122, -42, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 122),
(-121, -42, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 121),
(-120, -41, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 120),
(-119, -41, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 119),
(-118, -41, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 118),
(-117, -40, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 117),
(-116, -40, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 116),
(-115, -40, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 115),
(-114, -39, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 114),
(-113, -39, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 113),
(-112, -39, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 112),
(-111, -38, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 111),
(-110, -38, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 110),
(-109, -38, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 109),
(-108, -37, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 108),
(-107, -37, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 107),
(-106, -37, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 106),
(-105, -36, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 105),
(-104, -36, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 104),
(-103, -36, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 103),
(-102, -35, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 102),
(-101, -35, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 101),
(-100, -35, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 100),
(-99, -34, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 99),
(-98, -34, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 98),
(-97, -34, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 97),
(-96, -33, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 96),
(-95, -33, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 95),
(-94, -33, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 94),
(-93, -32, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 93),
(-92, -32, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 92),
(-91, -32, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 91),
(-90, -31, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 90),
(-89, -31, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 89),
(-88, -31, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 88),
(-87, -30, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 87),
(-86, -30, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 86),
(-85, -30, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 85),
(-84, -29, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 84),
(-83, -29, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 83),
(-82, -29, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 82),
(-81, -28, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 81),
(-80, -28, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 80),
(-79, -28, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 79),
(-78, -27, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 78),
(-77, -27, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 77),
(-76, -27, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 76),
(-75, -26, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 75),
(-74, -26, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 74),
(-73, -26, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 73),
(-72, -25, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 72),
(-71, -25, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 71),
(-70, -25, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 70),
(-69, -24, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 69),
(-68, -24, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 68),
(-67, -24, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 67),
(-66, -23, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 66),
(-65, -23, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 65),
(-64, -23, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 64),
(-63, -22, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 63),
(-62, -22, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 62),
(-61, -22, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 61),
(-60, -21, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 60),
(-59, -21, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 59),
(-58, -21, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 58),
(-57, -20, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 57),
(-56, -20, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 56),
(-55, -20, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 55),
(-54, -19, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 54),
(-53, -19, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 53),
(-52, -19, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 52),
(-51, -18, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 51),
(-50, -18, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 50),
(-49, -18, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 49),
(-48, -17, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 48),
(-47, -17, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 47),
(-46, -17, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 46),
(-45, -16, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 45),
(-44, -16, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 44),
(-43, -16, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 43),
(-42, -15, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 42),
(-41, -15, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 41),
(-40, -15, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 40),
(-39, -14, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 39),
(-38, -14, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 38),
(-37, -14, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 37),
(-36, -13, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 36),
(-35, -13, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 35),
(-34, -13, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 34),
(-33, -12, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 33),
(-32, -12, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 32),
(-31, -12, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 31),
(-30, -11, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 30),
(-29, -11, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 29),
(-28, -11, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 28),
(-27, -10, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 27),
(-26, -10, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 26),
(-25, -10, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 25),
(-24, -9, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 24),
(-23, -9, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 23),
(-22, -9, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 22),
(-21, -8, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 21),
(-20, -8, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 20),
(-19, -8, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 19),
(-18, -7, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 18),
(-17, -7, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 17),
(-16, -7, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 16),
(-15, -6, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 15),
(-14, -6, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 14),
(-13, -6, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 13),
(-12, -5, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 12),
(-11, -5, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 11),
(-10, -5, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 10),
(-9, -4, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 9),
(-8, -4, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 8),
(-7, -4, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 7),
(-6, -3, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 6),
(-5, -3, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 5),
(-4, -3, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 4),
(-3, -1, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', 3),
(-2, -1, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', 2),
(-1, -1, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', 1);

-- --------------------------------------------------------

--
-- 表的结构 `t_inventory`
--

CREATE TABLE IF NOT EXISTS `t_inventory` (
  `itemId` int(32) NOT NULL,
  `age` varchar(20) NOT NULL COMMENT '有岁和月二种,2y表示2岁,18m，表示18个月',
  `inventory` int(10) NOT NULL DEFAULT '0' COMMENT '库存数量',
  `footSize` varchar(100) NOT NULL,
  KEY `size` (`age`),
  KEY `age` (`age`),
  KEY `itemId` (`itemId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `t_inventory`
--

INSERT INTO `t_inventory` (`itemId`, `age`, `inventory`, `footSize`) VALUES
(-1, '2', 1, ''),
(-1, '3', 2, ''),
(-1, '4', 6, ''),
(-1, '5', 0, ''),
(-2, '2', 1, ''),
(-2, '3', 2, ''),
(-2, '4', 6, ''),
(-2, '5', 0, ''),
(-4, '2', 1, ''),
(-4, '3', 2, ''),
(-4, '4', 6, ''),
(-4, '5', 0, ''),
(-6, '2', 1, ''),
(-6, '3', 2, ''),
(-6, '4', 6, ''),
(-6, '5', 0, ''),
(-7, '2', 1, ''),
(-7, '3', 2, ''),
(-7, '4', 6, ''),
(-7, '5', 0, ''),
(-9, '2', 1, ''),
(-9, '3', 2, ''),
(-9, '4', 6, ''),
(-9, '5', 0, ''),
(-10, '2', 1, ''),
(-10, '3', 2, ''),
(-10, '4', 6, ''),
(-10, '5', 0, ''),
(-11, '2', 1, ''),
(-11, '3', 2, ''),
(-11, '4', 6, ''),
(-11, '5', 0, ''),
(-14, '2', 1, ''),
(-14, '3', 2, ''),
(-14, '4', 6, ''),
(-14, '5', 0, ''),
(-15, '2', 1, ''),
(-15, '3', 2, ''),
(-15, '4', 6, ''),
(-15, '5', 0, ''),
(-18, '2', 1, ''),
(-18, '3', 2, ''),
(-18, '4', 6, ''),
(-18, '5', 0, ''),
(-19, '2', 1, ''),
(-19, '3', 2, ''),
(-19, '4', 6, ''),
(-19, '5', 0, ''),
(-20, '2', 1, ''),
(-20, '3', 2, ''),
(-20, '4', 6, ''),
(-20, '5', 0, ''),
(-21, '2', 1, ''),
(-21, '3', 2, ''),
(-21, '4', 6, ''),
(-21, '5', 0, ''),
(-22, '2', 1, ''),
(-22, '3', 2, ''),
(-22, '4', 6, ''),
(-22, '5', 0, ''),
(-25, '2', 1, ''),
(-25, '3', 2, ''),
(-25, '4', 6, ''),
(-25, '5', 0, ''),
(-27, '2', 1, ''),
(-27, '3', 2, ''),
(-27, '4', 6, ''),
(-27, '5', 0, ''),
(-28, '2', 1, ''),
(-28, '3', 2, ''),
(-28, '4', 6, ''),
(-28, '5', 0, ''),
(-29, '2', 1, ''),
(-29, '3', 2, ''),
(-29, '4', 6, ''),
(-29, '5', 0, ''),
(-30, '2', 1, ''),
(-30, '3', 2, ''),
(-30, '4', 6, ''),
(-30, '5', 0, ''),
(-31, '8,9,10', 1, ''),
(-31, '13', 2, ''),
(-31, '18', 6, ''),
(-31, '20', 0, ''),
(-32, '8,9,10', 1, ''),
(-32, '13', 2, ''),
(-32, '18', 6, ''),
(-32, '20', 0, ''),
(-33, '8,9,10', 1, ''),
(-33, '13', 2, ''),
(-33, '18', 6, ''),
(-33, '20', 0, ''),
(-34, '8,9,10', 1, ''),
(-34, '13', 2, ''),
(-34, '18', 6, ''),
(-34, '20', 0, ''),
(-36, '8,9,10', 1, ''),
(-36, '13', 2, ''),
(-36, '18', 6, ''),
(-36, '20', 0, ''),
(-37, '8,9,10', 1, ''),
(-37, '13', 2, ''),
(-37, '18', 6, ''),
(-37, '20', 0, ''),
(-38, '8,9,10', 1, ''),
(-38, '13', 2, ''),
(-38, '18', 6, ''),
(-38, '20', 0, ''),
(-40, '8,9,10', 1, ''),
(-40, '13', 2, ''),
(-40, '18', 6, ''),
(-40, '20', 0, ''),
(-41, '8,9,10', 1, ''),
(-41, '13', 2, ''),
(-41, '18', 6, ''),
(-41, '20', 0, ''),
(-43, '8,9,10', 1, ''),
(-43, '13', 2, ''),
(-43, '18', 6, ''),
(-43, '20', 0, ''),
(-44, '8,9,10', 1, ''),
(-44, '13', 2, ''),
(-44, '18', 6, ''),
(-44, '20', 0, ''),
(-46, '8,9,10', 1, ''),
(-46, '13', 2, ''),
(-46, '18', 6, ''),
(-46, '20', 0, ''),
(-47, '8,9,10', 1, ''),
(-47, '13', 2, ''),
(-47, '18', 6, ''),
(-47, '20', 0, '');

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
  `grade` varchar(50) NOT NULL COMMENT '1-Bady; 2-Child',
  `gender` varchar(2) NOT NULL COMMENT 'M-male;F-female',
  `lastUpdatedDate` datetime NOT NULL,
  `priceHKD` varchar(100) NOT NULL,
  `isAvailable` varchar(2) NOT NULL,
  `link` varchar(200) NOT NULL,
  `season` varchar(50) NOT NULL,
  `discount` int(3) NOT NULL,
  `appendWords` varchar(500) NOT NULL,
  PRIMARY KEY (`itemId`),
  KEY `brandId` (`brandId`,`categoryId`),
  KEY `season` (`season`),
  KEY `targetCustomerType` (`grade`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `t_item`
--

INSERT INTO `t_item` (`itemId`, `name`, `color`, `detailDescription`, `component`, `brandId`, `categoryId`, `grade`, `gender`, `lastUpdatedDate`, `priceHKD`, `isAvailable`, `link`, `season`, `discount`, `appendWords`) VALUES
(-48, 'Graphic slubbed jersey T-shirt-48', '红色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -6, -8, '2', 'F', '2016-03-17 22:05:19', '147', '1', '', '', 100, ''),
(-47, 'Graphic slubbed jersey T-shirt-47', '红色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -6, -8, '2', 'F', '2016-03-17 22:05:19', '146', '1', '', '', 100, ''),
(-46, 'Graphic slubbed jersey T-shirt-46', '红色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -6, -8, '2', 'F', '2016-03-17 22:05:19', '145', '1', '', '', 100, ''),
(-45, 'Graphic slubbed jersey T-shirt-45', '红色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -6, -8, '2', 'F', '2016-03-17 22:05:19', '144', '1', '', '', 100, ''),
(-44, 'Graphic slubbed jersey T-shirt-44', '红色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -6, -8, '2', 'F', '2016-03-17 22:05:19', '143', '1', '', '', 100, ''),
(-43, 'Graphic slubbed jersey T-shirt-43', '红色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -6, -8, '2', 'F', '2016-03-17 22:05:19', '142', '1', '', '', 100, ''),
(-42, 'Graphic slubbed jersey T-shirt-42', '紫色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -6, -8, '2', 'F', '2016-03-17 22:05:19', '141', '1', '', '', 100, ''),
(-41, 'Graphic slubbed jersey T-shirt-41', '紫色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -6, -9, '2', 'F', '2016-03-17 22:05:19', '140', '1', '', '', 100, ''),
(-40, 'Graphic slubbed jersey T-shirt-40', '紫色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -6, -9, '2', 'F', '2016-03-17 22:05:19', '139', '1', '', '', 100, ''),
(-39, 'Graphic slubbed jersey T-shirt-39', '紫色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -6, -9, '2', 'M', '2016-03-17 22:05:19', '138', '1', '', '', 100, ''),
(-38, 'Graphic slubbed jersey T-shirt-38', '紫色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -6, -9, '2', 'M', '2016-03-17 22:05:19', '137', '1', '', '', 100, ''),
(-37, 'Graphic slubbed jersey T-shirt-37', '紫色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -6, -9, '2', 'M', '2016-03-17 22:05:19', '136', '1', '', '', 100, ''),
(-36, 'Graphic slubbed jersey T-shirt-36', '紫色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -6, -9, '2', 'M', '2016-03-17 22:05:18', '135', '1', '', '', 100, ''),
(-35, 'Graphic slubbed jersey T-shirt-35', '紫色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -6, -9, '2', 'M', '2016-03-17 22:05:18', '134', '1', '', '', 100, ''),
(-34, 'Graphic slubbed jersey T-shirt-34', '紫色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -6, -9, '2', 'M', '2016-03-17 22:05:18', '133', '1', '', '', 100, ''),
(-33, 'Graphic slubbed jersey T-shirt-33', '紫色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -6, -10, '2', 'M', '2016-03-17 22:05:18', '132', '1', '', '', 100, ''),
(-32, 'Graphic slubbed jersey T-shirt-32', '紫色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -6, -10, '2', 'M', '2016-03-17 22:05:18', '131', '1', '', '', 100, ''),
(-31, 'Graphic slubbed jersey T-shirt-31', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -6, -10, '2', 'M', '2016-03-17 22:05:18', '130', '1', '', '', 100, ''),
(-30, 'Graphic slubbed jersey T-shirt-30', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -10, '2', 'M', '2016-03-17 22:05:18', '129', '1', '', '', 100, ''),
(-29, 'Graphic slubbed jersey T-shirt-29', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -10, '1', 'M', '2016-03-17 22:05:18', '128', '1', '', '', 100, ''),
(-28, 'Graphic slubbed jersey T-shirt-28', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -10, '1', 'M', '2016-03-17 22:05:18', '127', '1', '', '', 100, ''),
(-27, 'Graphic slubbed jersey T-shirt-27', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -10, '1', 'M', '2016-03-17 22:05:18', '126', '1', '', '', 100, ''),
(-26, 'Graphic slubbed jersey T-shirt-26', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -10, '1', 'M', '2016-03-17 22:05:18', '125', '1', '', '', 100, ''),
(-25, 'Graphic slubbed jersey T-shirt-25', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -10, '1', 'M', '2016-03-17 22:05:18', '124', '1', '', '', 100, ''),
(-24, 'Graphic slubbed jersey T-shirt-24', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -10, '1', 'M', '2016-03-17 22:05:18', '123', '1', '', '', 100, ''),
(-23, 'Graphic slubbed jersey T-shirt-23', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -11, '1', 'M', '2016-03-17 22:05:18', '122', '1', '', '', 100, ''),
(-22, 'Graphic slubbed jersey T-shirt-22', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -11, '1', 'M', '2016-03-17 22:05:18', '121', '1', '', '', 100, ''),
(-21, 'Graphic slubbed jersey T-shirt-21', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -11, '1', 'M', '2016-03-17 22:05:18', '120', '1', '', '', 100, ''),
(-20, 'Graphic slubbed jersey T-shirt-20', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -11, '1', 'M', '2016-03-17 22:05:18', '119', '1', '', '', 100, ''),
(-19, 'Graphic slubbed jersey T-shirt-19', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -11, '1', 'M', '2016-03-17 22:05:18', '118', '1', '', '', 100, ''),
(-18, 'Graphic slubbed jersey T-shirt-18', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -11, '1', 'M', '2016-03-17 22:05:18', '117', '1', '', '', 100, ''),
(-17, 'Graphic slubbed jersey T-shirt-17', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -11, '1', 'M', '2016-03-17 22:05:18', '116', '1', '', '', 100, ''),
(-16, 'Graphic slubbed jersey T-shirt-16', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -11, '1', 'M', '2016-03-17 22:05:18', '115', '1', '', '', 100, ''),
(-15, 'Graphic slubbed jersey T-shirt-15', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -11, '1', 'M', '2016-03-17 22:05:18', '114', '1', '', '', 100, ''),
(-14, 'Graphic slubbed jersey T-shirt-14', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -11, '1', 'M', '2016-03-17 22:05:18', '113', '1', '', '', 100, ''),
(-13, 'Graphic slubbed jersey T-shirt-13', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -11, '1', 'M', '2016-03-17 22:05:18', '112', '1', '', '', 100, ''),
(-12, 'Graphic slubbed jersey T-shirt-12', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -11, '1', 'M', '2016-03-17 22:05:18', '111', '1', '', '', 100, ''),
(-11, 'Graphic slubbed jersey T-shirt-11', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -12, '1', 'M', '2016-03-17 22:05:18', '110', '1', '', '', 100, ''),
(-10, 'Graphic slubbed jersey T-shirt-10', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -12, '1', 'F', '2016-03-17 22:05:18', '109', '1', '', '', 100, ''),
(-9, 'Graphic slubbed jersey T-shirt-9', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -12, '1', 'F', '2016-03-17 22:05:18', '108', '1', '', '', 100, ''),
(-8, 'Graphic slubbed jersey T-shirt-8', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -12, '1', 'F', '2016-03-17 22:05:18', '107', '1', '', '', 100, ''),
(-7, 'Graphic slubbed jersey T-shirt-7', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -12, '1', 'F', '2016-03-17 22:05:18', '106', '1', '', '', 100, ''),
(-6, 'Graphic slubbed jersey T-shirt-6', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -13, '1', 'F', '2016-03-17 22:05:18', '105', '1', '', '', 100, ''),
(-5, 'Graphic slubbed jersey T-shirt-5', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -13, '1', 'F', '2016-03-17 22:05:18', '104', '1', '', '', 100, ''),
(-4, 'Graphic slubbed jersey T-shirt-4', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -13, '1', 'F', '2016-03-17 22:05:18', '103', '1', '', '', 100, ''),
(-3, 'Graphic slubbed jersey T-shirt-3', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -14, '1', 'F', '2016-03-17 22:05:18', '102', '1', '', '', 100, ''),
(-2, 'Graphic slubbed jersey T-shirt-2', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -14, '1', 'F', '2016-03-17 22:05:18', '101', '1', '', '', 100, ''),
(-1, 'Graphic slubbed jersey T-shirt-1', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -14, '1', 'F', '2016-03-17 22:05:18', '100', '1', '', '', 100, '');

-- --------------------------------------------------------

--
-- 表的结构 `t_itemprice`
--

CREATE TABLE IF NOT EXISTS `t_itemprice` (
  `itemId` int(32) NOT NULL,
  `price` varchar(100) NOT NULL,
  `currency` varchar(10) NOT NULL COMMENT '目前支持HKD, CNY',
  `autoAssign` tinyint(1) NOT NULL COMMENT '是否自动根据当前currency对换港币的汇率转换',
  KEY `itemId` (`itemId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `t_order`
--

CREATE TABLE IF NOT EXISTS `t_order` (
  `orderId` int(32) NOT NULL AUTO_INCREMENT,
  `orderNumber` varchar(100) NOT NULL,
  `totalItemCount` int(3) NOT NULL,
  `totalAmount` varchar(50) NOT NULL,
  `shippingAddress` varchar(500) NOT NULL,
  `contactName` varchar(50) NOT NULL,
  `contactPhone` varchar(50) NOT NULL,
  `userAvailabeTime` varchar(100) NOT NULL,
  `userId` int(32) NOT NULL,
  `updatedDate` datetime NOT NULL,
  `status` varchar(20) NOT NULL,
  `isGiftPackage` tinyint(1) NOT NULL COMMENT '是否需要使用礼品包装，如需要，要额外付15',
  PRIMARY KEY (`orderId`),
  UNIQUE KEY `orderNumber` (`orderNumber`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单主表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `t_orderitem`
--

CREATE TABLE IF NOT EXISTS `t_orderitem` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `orderNumber` varchar(100) NOT NULL,
  `itemId` int(32) NOT NULL,
  `itemName` varchar(100) NOT NULL,
  `itemSize` varchar(100) NOT NULL,
  `price` varchar(100) NOT NULL,
  `quantity` int(10) NOT NULL COMMENT '货品数量',
  `unshippedQuantity` int(10) NOT NULL COMMENT '尚未发货的货品数量',
  `status` varchar(20) NOT NULL COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `orderNumber` (`orderNumber`,`itemId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `t_shippingaddress`
--

CREATE TABLE IF NOT EXISTS `t_shippingaddress` (
  `addressId` int(32) NOT NULL AUTO_INCREMENT,
  `userId` int(32) NOT NULL,
  `name` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `default` tinyint(1) NOT NULL,
  PRIMARY KEY (`addressId`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='发货地址' AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- 转存表中的数据 `t_user`
--

INSERT INTO `t_user` (`userId`, `userName`, `email`, `password`, `mobile`, `lastUpdatedDate`, `lastIp`) VALUES
(10, 'zheng', 'onealzhh@163.com', 'e10adc3949ba59abbe56e057f20f883e', '', '2016-03-22 10:58:59', '127.0.0.1'),
(11, 'lao', 'lao@163.com', 'e10adc3949ba59abbe56e057f20f883e', '3233', '2016-03-22 12:14:20', '127.0.0.1');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
