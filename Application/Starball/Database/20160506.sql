-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- 主機: localhost
-- 產生時間： 2016 年 05 月 06 日 09:42
-- 伺服器版本: 10.1.10-MariaDB
-- PHP 版本： 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `starball_schema`
--

-- --------------------------------------------------------

--
-- 資料表結構 `t_blog`
--

CREATE TABLE `t_blog` (
  `blogId` int(32) NOT NULL COMMENT '博客ID',
  `title` text NOT NULL COMMENT '博客标题',
  `abstract` text NOT NULL COMMENT '博客摘要',
  `content` text NOT NULL COMMENT '博客内容',
  `status` varchar(1) NOT NULL,
  `createdDt` datetime NOT NULL,
  `lastUpdatedDt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `t_blog`
--

INSERT INTO `t_blog` (`blogId`, `title`, `abstract`, `content`, `status`, `createdDt`, `lastUpdatedDt`) VALUES
(7, 'wdwd', 'asdasdas', '&amp;lt;p style=&quot;text-align: center;&quot;&amp;gt;&amp;lt;img src=&quot;http://7xp6oq.com2.z0.glb.qiniucdn.com/test.png&quot;/&amp;gt;&amp;lt;/p&amp;gt;&amp;lt;p&amp;gt;asdasdasd&amp;lt;/p&amp;gt;&amp;lt;p style=&quot;text-align: center;&quot;&amp;gt;asdasdasd&amp;lt;/p&amp;gt;', '0', '2016-05-06 13:35:01', '2016-05-06 13:35:01'),
(8, 'test', 'test', '&amp;lt;p style=&amp;quot;text-align: center;&amp;quot;&amp;gt;&amp;lt;img src=&amp;quot;http://7xp6oq.com2.z0.glb.qiniucdn.com/test.png&amp;quot;/&amp;gt;&amp;lt;/p&amp;gt;&amp;lt;p&amp;gt;asdasdasd&amp;lt;/p&amp;gt;&amp;lt;p style=&amp;quot;text-align: center;&amp;quot;&amp;gt;asdasdasd&amp;lt;/p&amp;gt;&amp;lt;p&amp;gt;&amp;lt;br/&amp;gt;&amp;lt;/p&amp;gt;&amp;lt;p&amp;gt;kkkkkkkkkkkkkkk&amp;lt;/p&amp;gt;&amp;lt;p&amp;gt;&amp;lt;br/&amp;gt;&amp;lt;/p&amp;gt;&amp;lt;p&amp;gt;asdasdasd&amp;lt;/p&amp;gt;&amp;lt;p&amp;gt;&amp;lt;strong&amp;gt;&amp;lt;em&amp;gt;asdfasfdvvvvsuyyyyuuiiikmmmm&amp;lt;/em&amp;gt;&amp;lt;/strong&amp;gt;&amp;lt;/p&amp;gt;&amp;lt;p&amp;gt;&amp;lt;strong&amp;gt;&amp;lt;em&amp;gt;asdasdadawdqweqweqweqwe&amp;lt;/em&amp;gt;&amp;lt;/strong&amp;gt;&amp;lt;/p&amp;gt;&amp;lt;p&amp;gt;&amp;lt;span style=&amp;quot;text-decoration: underline;&amp;quot;&amp;gt;&amp;lt;strong&amp;gt;&amp;lt;em&amp;gt;qweqwe&amp;lt;/em&amp;gt;&amp;lt;/strong&amp;gt;&amp;lt;strong&amp;gt;&amp;lt;em&amp;gt;&amp;lt;span style=&amp;quot;text-decoration: underline; border: 1px solid rgb(0, 0, 0);&amp;quot;&amp;gt;qweqweqweqweqwe&amp;lt;/span&amp;gt;&amp;lt;/em&amp;gt;&amp;lt;/strong&amp;gt;&amp;lt;/span&amp;gt;&amp;lt;/p&amp;gt;', '0', '2016-05-06 13:47:05', '2016-05-06 15:17:52'),
(9, '', '', '&amp;lt;p&amp;gt;asdasdasd&amp;lt;/p&amp;gt;&amp;lt;p style=&quot;text-align: center;&quot;&amp;gt;asdasdasd&amp;lt;/p&amp;gt;', '1', '2016-05-06 14:04:03', '2016-05-06 14:04:03'),
(10, 'asdasd', 'asdasdasd', '&amp;amp;lt;p&amp;amp;gt;asdasdasdasd&amp;amp;lt;/p&amp;amp;gt;&amp;amp;lt;p&amp;amp;gt;asdasd&amp;amp;lt;/p&amp;amp;gt;&amp;amp;lt;p&amp;amp;gt;asdasd&amp;amp;lt;/p&amp;amp;gt;&amp;amp;lt;p&amp;amp;gt;&amp;amp;lt;br/&amp;amp;gt;&amp;amp;lt;/p&amp;amp;gt;&amp;amp;lt;p&amp;amp;gt;asdasdasd&amp;amp;lt;img src=&amp;quot;http://7xp6oq.com2.z0.glb.qiniucdn.com/test.png&amp;quot;/&amp;amp;gt;&amp;amp;lt;/p&amp;amp;gt;', '0', '2016-05-06 14:21:42', '2016-05-06 14:21:42'),
(11, 'asdasd', 'asdasdasd', '&lt;p&gt;&amp;lt;p&amp;gt;asdasdasdasd&amp;lt;/p&amp;gt;&amp;lt;p&amp;gt;asdasd&amp;lt;/p&amp;gt;&amp;lt;p&amp;gt;asdasd&amp;lt;/p&amp;gt;&amp;lt;p&amp;gt;&amp;lt;br/&amp;gt;&amp;lt;/p&amp;gt;&amp;lt;p&amp;gt;asdasdasd&amp;lt;img src=&amp;quot;http://7xp6oq.com2.z0.glb.qiniucdn.com/test.png&amp;quot;/&amp;gt;&amp;lt;/p&amp;gt;&lt;/p&gt;', '0', '2016-05-06 14:42:07', '2016-05-06 15:28:11'),
(12, 'asdasd', 'asdasdasd', '&lt;p&gt;&amp;lt;p&amp;gt;asdasdasdasd&amp;lt;/p&amp;gt;&amp;lt;p&amp;gt;asdasd&amp;lt;/p&amp;gt;&amp;lt;p&amp;gt;asdasd&amp;lt;/p&amp;gt;&amp;lt;p&amp;gt;&amp;lt;br/&amp;gt;&amp;lt;/p&amp;gt;&amp;lt;p&amp;gt;asdasda&amp;lt;/p&amp;gt;&amp;lt;p&amp;gt;&amp;lt;br/&amp;gt;&amp;lt;/p&amp;gt;&amp;lt;p&amp;gt;&amp;lt;br/&amp;gt;&amp;lt;/p&amp;gt;&amp;lt;p style=&amp;quot;text-align: right;&amp;quot;&amp;gt;asdasdasdasd&amp;lt;/p&amp;gt;&amp;lt;p style=&amp;quot;text-align: right;&amp;quot;&amp;gt;&amp;lt;br/&amp;gt;&amp;lt;/p&amp;gt;&amp;lt;p style=&amp;quot;text-align: right;&amp;quot;&amp;gt;&amp;lt;br/&amp;gt;&amp;lt;/p&amp;gt;&amp;lt;p style=&amp;quot;text-align: center;&amp;quot;&amp;gt;&amp;lt;img src=&amp;quot;http://7xp6oq.com2.z0.glb.qiniucdn.com/test.png&amp;quot;/&amp;gt;&amp;lt;/p&amp;gt;&lt;/p&gt;', '0', '2016-05-06 15:28:30', '2016-05-06 15:28:50'),
(13, 'adad', 'aDSadaDadsaSas', '&lt;p&gt;aSadaDsdsadsdfsfd&lt;/p&gt;&lt;p&gt;asfsad&lt;/p&gt;&lt;p&gt;asdasdasdasd&lt;/p&gt;', '0', '2016-05-06 15:29:59', '2016-05-06 15:29:59');

--
-- 已匯出資料表的索引
--

--
-- 資料表索引 `t_blog`
--
ALTER TABLE `t_blog`
  ADD PRIMARY KEY (`blogId`);

--
-- 在匯出的資料表使用 AUTO_INCREMENT
--

--
-- 使用資料表 AUTO_INCREMENT `t_blog`
--
ALTER TABLE `t_blog`
  MODIFY `blogId` int(32) NOT NULL AUTO_INCREMENT COMMENT '博客ID', AUTO_INCREMENT=14;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
