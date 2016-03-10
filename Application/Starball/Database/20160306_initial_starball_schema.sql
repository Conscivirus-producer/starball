-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 年 03 月 10 日 09:43
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
  `targetCustomerType` varchar(50) NOT NULL,
  PRIMARY KEY (`categoryId`),
  KEY `parentCategoryId` (`parentCategoryId`),
  KEY `targetCustomerType` (`targetCustomerType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `t_category`
--

INSERT INTO `t_category` (`categoryId`, `parentCategoryId`, `categoryName`, `targetCustomerType`) VALUES
(-24, -3, '系带鞋', '4'),
(-23, -3, '运动鞋', '4'),
(-22, 0, '开襟衫', '4'),
(-21, 0, '上衣和T恤', '4'),
(-20, -2, '系带鞋', '3'),
(-19, -2, '芭蕾舞鞋', '3'),
(-18, 0, '开襟衫', '3'),
(-17, 0, '上衣和T恤', '3'),
(-16, 0, '开襟衫', '2'),
(-15, 0, '上衣和T恤', '2'),
(-14, 0, '上衣和T恤', '1'),
(-13, 0, '连衣裙', '1'),
(-12, 0, '浴用品', '0'),
(-11, 0, '婴儿房', '0'),
(-10, 0, '婴儿床', '0'),
(-9, 0, '睡衣', '0'),
(-8, 0, '泳装', '0'),
(-7, 0, '套装', '0'),
(-6, 0, '上衣和T恤', '0'),
(-5, -1, '系带鞋', '0'),
(-4, -1, '芭蕾舞鞋', '0'),
(-3, 0, '童鞋', '4'),
(-2, 0, '童鞋', '3'),
(-1, 0, '鞋', '0');

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
  `imageSmall` varchar(200) NOT NULL,
  `imageMiddle` varchar(200) NOT NULL,
  `imageBig` varchar(200) NOT NULL,
  `type` varchar(50) NOT NULL COMMENT '图片类型，normal,big,small',
  `sequence` int(2) NOT NULL COMMENT '如为小图，有多张，图片的展示顺序',
  PRIMARY KEY (`imageId`),
  KEY `itemId` (`itemId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `t_image`
--

INSERT INTO `t_image` (`imageId`, `itemId`, `imageSmall`, `imageMiddle`, `imageBig`, `type`, `sequence`) VALUES
(-150, -51, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 150),
(-149, -51, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 149),
(-148, -51, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 148),
(-147, -50, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 147),
(-146, -50, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 146),
(-145, -50, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 145),
(-144, -49, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 144),
(-143, -49, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 143),
(-142, -49, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 142),
(-141, -48, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 141),
(-140, -48, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 140),
(-139, -48, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 139),
(-138, -47, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 138),
(-137, -47, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 137),
(-136, -47, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 136),
(-135, -46, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 135),
(-134, -46, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 134),
(-133, -46, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 133),
(-132, -45, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 132),
(-131, -45, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 131),
(-130, -45, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 130),
(-129, -44, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 129),
(-128, -44, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 128),
(-127, -44, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 127),
(-126, -43, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 126),
(-125, -43, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 125),
(-124, -43, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 124),
(-123, -42, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 123),
(-122, -42, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 122),
(-121, -42, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 121),
(-120, -41, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 120),
(-119, -41, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 119),
(-118, -41, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 118),
(-117, -40, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 117),
(-116, -40, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 116),
(-115, -40, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 115),
(-114, -39, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 114),
(-113, -39, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 113),
(-112, -39, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 112),
(-111, -38, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 111),
(-110, -38, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 110),
(-109, -38, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 109),
(-108, -37, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 108),
(-107, -37, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 107),
(-106, -37, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 106),
(-105, -36, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 105),
(-104, -36, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 104),
(-103, -36, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 103),
(-102, -35, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 102),
(-101, -35, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 101),
(-100, -35, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 100),
(-99, -34, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 99),
(-98, -34, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 98),
(-97, -34, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 97),
(-96, -33, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 96),
(-95, -33, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 95),
(-94, -33, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 94),
(-93, -32, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 93),
(-92, -32, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 92),
(-91, -32, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 91),
(-90, -31, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 90),
(-89, -31, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 89),
(-88, -31, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 88),
(-87, -30, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 87),
(-86, -30, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 86),
(-85, -30, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 85),
(-84, -29, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 84),
(-83, -29, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 83),
(-82, -29, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 82),
(-81, -28, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 81),
(-80, -28, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 80),
(-79, -28, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 79),
(-78, -27, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 78),
(-77, -27, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 77),
(-76, -27, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 76),
(-75, -26, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 75),
(-74, -26, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 74),
(-73, -26, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 73),
(-72, -25, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 72),
(-71, -25, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 71),
(-70, -25, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 70),
(-69, -24, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 69),
(-68, -24, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 68),
(-67, -24, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 67),
(-66, -23, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 66),
(-65, -23, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 65),
(-64, -23, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 64),
(-63, -22, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 63),
(-62, -22, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 62),
(-61, -22, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 61),
(-60, -21, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 60),
(-59, -21, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 59),
(-58, -21, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 58),
(-57, -20, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 57),
(-56, -20, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 56),
(-55, -20, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 55),
(-54, -19, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 54),
(-53, -19, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 53),
(-52, -19, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 52),
(-51, -18, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 51),
(-50, -18, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 50),
(-49, -18, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 49),
(-48, -17, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 48),
(-47, -17, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 47),
(-46, -17, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 46),
(-45, -16, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 45),
(-44, -16, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 44),
(-43, -16, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 43),
(-42, -15, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 42),
(-41, -15, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 41),
(-40, -15, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 40),
(-39, -14, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 39),
(-38, -14, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 38),
(-37, -14, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 37),
(-36, -13, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 36),
(-35, -13, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 35),
(-34, -13, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 34),
(-33, -12, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 33),
(-32, -12, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 32),
(-31, -12, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 31),
(-30, -11, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 30),
(-29, -11, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 29),
(-28, -11, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 28),
(-27, -10, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 27),
(-26, -10, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 26),
(-25, -10, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 25),
(-24, -9, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 24),
(-23, -9, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 23),
(-22, -9, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 22),
(-21, -8, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 21),
(-20, -8, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 20),
(-19, -8, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 19),
(-18, -7, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 18),
(-17, -7, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 17),
(-16, -7, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 16),
(-15, -6, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 15),
(-14, -6, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 14),
(-13, -6, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 13),
(-12, -5, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 12),
(-11, -5, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 11),
(-10, -5, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 10),
(-9, -4, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 9),
(-8, -4, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 8),
(-7, -4, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 7),
(-6, -3, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 6),
(-5, -3, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 5),
(-4, -3, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 4),
(-3, -1, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_C.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_C.jpg', '', 'S', 3),
(-2, -1, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_B.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_B.jpg', '', 'S', 2),
(-1, -1, 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_t_151859_A.jpg', 'http://7xr7p7.com2.z0.glb.qiniucdn.com/armani-junior--t-1446690839-p_z_151859_A.jpg', '', 'S', 1);

-- --------------------------------------------------------

--
-- 表的结构 `t_inventory`
--

CREATE TABLE IF NOT EXISTS `t_inventory` (
  `itemId` int(32) NOT NULL AUTO_INCREMENT,
  `price` float NOT NULL,
  `sizeId` int(32) NOT NULL,
  `inventory` int(10) NOT NULL DEFAULT '0' COMMENT '库存数量',
  PRIMARY KEY (`itemId`),
  KEY `size` (`sizeId`)
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

--
-- 转存表中的数据 `t_item`
--

INSERT INTO `t_item` (`itemId`, `name`, `color`, `detailDescription`, `component`, `brandId`, `categoryId`, `lastUpdatedDate`, `lowestPrice`, `isAvailable`, `link`, `season`) VALUES
(-48, 'Graphic slubbed jersey T-shirt-48', '红色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 6, 4, '2016-03-10 11:59:31', 147, '', '', ''),
(-47, 'Graphic slubbed jersey T-shirt-47', '红色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 6, 4, '2016-03-10 11:59:31', 146, '', '', ''),
(-46, 'Graphic slubbed jersey T-shirt-46', '红色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 6, 4, '2016-03-10 11:59:31', 145, '', '', ''),
(-45, 'Graphic slubbed jersey T-shirt-45', '红色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 6, 4, '2016-03-10 11:59:31', 144, '', '', ''),
(-44, 'Graphic slubbed jersey T-shirt-44', '红色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 6, 4, '2016-03-10 11:59:31', 143, '', '', ''),
(-43, 'Graphic slubbed jersey T-shirt-43', '红色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 6, 4, '2016-03-10 11:59:31', 142, '', '', ''),
(-42, 'Graphic slubbed jersey T-shirt-42', '紫色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 6, 4, '2016-03-10 11:59:31', 141, '', '', ''),
(-41, 'Graphic slubbed jersey T-shirt-41', '紫色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 6, 11, '2016-03-10 11:59:31', 140, '', '', ''),
(-40, 'Graphic slubbed jersey T-shirt-40', '紫色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 6, 11, '2016-03-10 11:59:31', 139, '', '', ''),
(-39, 'Graphic slubbed jersey T-shirt-39', '紫色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 6, 11, '2016-03-10 11:59:31', 138, '', '', ''),
(-38, 'Graphic slubbed jersey T-shirt-38', '紫色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 6, 11, '2016-03-10 11:59:31', 137, '', '', ''),
(-37, 'Graphic slubbed jersey T-shirt-37', '紫色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 6, 11, '2016-03-10 11:59:31', 136, '', '', ''),
(-36, 'Graphic slubbed jersey T-shirt-36', '紫色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 6, 11, '2016-03-10 11:59:31', 135, '', '', ''),
(-35, 'Graphic slubbed jersey T-shirt-35', '紫色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 6, 11, '2016-03-10 11:59:31', 134, '', '', ''),
(-34, 'Graphic slubbed jersey T-shirt-34', '紫色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 6, 11, '2016-03-10 11:59:31', 133, '', '', ''),
(-33, 'Graphic slubbed jersey T-shirt-33', '紫色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 6, 14, '2016-03-10 11:59:31', 132, '', '', ''),
(-32, 'Graphic slubbed jersey T-shirt-32', '紫色', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 6, 14, '2016-03-10 11:59:31', 131, '', '', ''),
(-31, 'Graphic slubbed jersey T-shirt-31', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 6, 14, '2016-03-10 11:59:31', 130, '', '', ''),
(-30, 'Graphic slubbed jersey T-shirt-30', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 8, 14, '2016-03-10 11:59:31', 129, '', '', ''),
(-29, 'Graphic slubbed jersey T-shirt-29', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 8, 14, '2016-03-10 11:59:31', 128, '', '', ''),
(-28, 'Graphic slubbed jersey T-shirt-28', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 8, 14, '2016-03-10 11:59:31', 127, '', '', ''),
(-27, 'Graphic slubbed jersey T-shirt-27', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 8, 14, '2016-03-10 11:59:31', 126, '', '', ''),
(-26, 'Graphic slubbed jersey T-shirt-26', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 8, 14, '2016-03-10 11:59:31', 125, '', '', ''),
(-25, 'Graphic slubbed jersey T-shirt-25', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 8, 14, '2016-03-10 11:59:31', 124, '', '', ''),
(-24, 'Graphic slubbed jersey T-shirt-24', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 8, 14, '2016-03-10 11:59:31', 123, '', '', ''),
(-23, 'Graphic slubbed jersey T-shirt-23', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 8, 16, '2016-03-10 11:59:31', 122, '', '', ''),
(-22, 'Graphic slubbed jersey T-shirt-22', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 8, 16, '2016-03-10 11:59:31', 121, '', '', ''),
(-21, 'Graphic slubbed jersey T-shirt-21', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 8, 16, '2016-03-10 11:59:31', 120, '', '', ''),
(-20, 'Graphic slubbed jersey T-shirt-20', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 8, 16, '2016-03-10 11:59:31', 119, '', '', ''),
(-19, 'Graphic slubbed jersey T-shirt-19', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 8, 16, '2016-03-10 11:59:31', 118, '', '', ''),
(-18, 'Graphic slubbed jersey T-shirt-18', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 8, 16, '2016-03-10 11:59:31', 117, '', '', ''),
(-17, 'Graphic slubbed jersey T-shirt-17', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 8, 16, '2016-03-10 11:59:31', 116, '', '', ''),
(-16, 'Graphic slubbed jersey T-shirt-16', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 8, 16, '2016-03-10 11:59:31', 115, '', '', ''),
(-15, 'Graphic slubbed jersey T-shirt-15', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 8, 16, '2016-03-10 11:59:30', 114, '', '', ''),
(-14, 'Graphic slubbed jersey T-shirt-14', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 8, 16, '2016-03-10 11:59:30', 113, '', '', ''),
(-13, 'Graphic slubbed jersey T-shirt-13', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 8, 16, '2016-03-10 11:59:30', 112, '', '', ''),
(-12, 'Graphic slubbed jersey T-shirt-12', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 8, 16, '2016-03-10 11:59:30', 111, '', '', ''),
(-11, 'Graphic slubbed jersey T-shirt-11', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 8, 17, '2016-03-10 11:59:30', 110, '', '', ''),
(-10, 'Graphic slubbed jersey T-shirt-10', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 8, 17, '2016-03-10 11:59:30', 109, '', '', ''),
(-9, 'Graphic slubbed jersey T-shirt-9', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 8, 17, '2016-03-10 11:59:30', 108, '', '', ''),
(-8, 'Graphic slubbed jersey T-shirt-8', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 8, 17, '2016-03-10 11:59:30', 107, '', '', ''),
(-7, 'Graphic slubbed jersey T-shirt-7', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 8, 17, '2016-03-10 11:59:30', 106, '', '', ''),
(-6, 'Graphic slubbed jersey T-shirt-6', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 8, 21, '2016-03-10 11:59:30', 105, '', '', ''),
(-5, 'Graphic slubbed jersey T-shirt-5', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 8, 21, '2016-03-10 11:59:30', 104, '', '', ''),
(-4, 'Graphic slubbed jersey T-shirt-4', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 8, 21, '2016-03-10 11:59:30', 103, '', '', ''),
(-3, 'Graphic slubbed jersey T-shirt-3', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 8, 24, '2016-03-10 11:59:30', 102, '', '', ''),
(-2, 'Graphic slubbed jersey T-shirt-2', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', 8, 24, '2016-03-10 11:59:30', 101, '', '', ''),
(-1, 'Graphic slubbed jersey T-shirt-1', '电光蓝', '棉质竹节针织布\n触感舒适\n圆领\n弹力侧边\n短袖\n丝印标识\n背部品牌标签\n参考 CXH16-KA-63', '100% 棉。\nMachine washable at 30°C', -8, -24, '2016-03-10 11:59:30', 100, '', '', '');

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
