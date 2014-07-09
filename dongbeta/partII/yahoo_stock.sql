-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014 年 07 月 09 日 12:43
-- 服务器版本: 5.5.29
-- PHP 版本: 5.4.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `yahoo_stock`
--

-- --------------------------------------------------------

--
-- 表的结构 `stock_data`
--

CREATE TABLE IF NOT EXISTS `stock_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `code` varchar(255) NOT NULL COMMENT '股票编号',
  `date` int(11) NOT NULL COMMENT '日期',
  `open` float unsigned NOT NULL DEFAULT '0' COMMENT '开盘价',
  `high` float unsigned NOT NULL DEFAULT '0' COMMENT '最高价',
  `low` float unsigned NOT NULL DEFAULT '0' COMMENT '最低价',
  `close` float unsigned NOT NULL DEFAULT '0' COMMENT '收盘价',
  `volume` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '成交量',
  `adj_close` float unsigned NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL COMMENT '入库时间',
  `updated_at` int(11) NOT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`,`date`),
  KEY `code_2` (`code`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='股票历史数据表' AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
