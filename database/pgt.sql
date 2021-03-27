/*
Navicat MySQL Data Transfer

Source Server         : mysqli
Source Server Version : 80020
Source Host           : localhost:3306
Source Database       : pgt

Target Server Type    : MYSQL
Target Server Version : 80020
File Encoding         : 65001

Date: 2020-06-24 22:43:48
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for events
-- ----------------------------
DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `date` int NOT NULL,
  `location` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for gallery
-- ----------------------------
DROP TABLE IF EXISTS `gallery`;
CREATE TABLE `gallery` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text CHARACTER SET cp1251 COLLATE cp1251_bulgarian_ci NOT NULL,
  `img_path` text CHARACTER SET cp1251 COLLATE cp1251_bulgarian_ci NOT NULL,
  `category` text CHARACTER SET cp1251 COLLATE cp1251_bulgarian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=cp1251 COLLATE=cp1251_bulgarian_ci;

-- ----------------------------
-- Table structure for ip
-- ----------------------------
DROP TABLE IF EXISTS `ip`;
CREATE TABLE `ip` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ip_adress` varchar(15) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `time` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for news
-- ----------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `date` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for personel
-- ----------------------------
DROP TABLE IF EXISTS `personel`;
CREATE TABLE `personel` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `lastname` text NOT NULL,
  `category` text NOT NULL,
  `sub_category` text,
  `biography` text,
  `image_path` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for posts
-- ----------------------------
DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `img_path` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `title` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for projects
-- ----------------------------
DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text CHARACTER SET cp1251 COLLATE cp1251_bulgarian_ci NOT NULL,
  `content` text CHARACTER SET cp1251 COLLATE cp1251_bulgarian_ci NOT NULL,
  `category` text CHARACTER SET cp1251 COLLATE cp1251_bulgarian_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=cp1251 COLLATE=cp1251_bulgarian_ci;

-- ----------------------------
-- Table structure for regulations
-- ----------------------------
DROP TABLE IF EXISTS `regulations`;
CREATE TABLE `regulations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text CHARACTER SET cp1251 COLLATE cp1251_bulgarian_ci NOT NULL,
  `content` text CHARACTER SET cp1251 COLLATE cp1251_bulgarian_ci NOT NULL,
  `category` text CHARACTER SET cp1251 COLLATE cp1251_bulgarian_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=cp1251 COLLATE=cp1251_bulgarian_ci;

-- ----------------------------
-- Table structure for specialties
-- ----------------------------
DROP TABLE IF EXISTS `specialties`;
CREATE TABLE `specialties` (
  `id` int NOT NULL AUTO_INCREMENT,
  `specialty_name` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `specialty_info_short` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `specialty_info_long` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `image` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Event structure for Clean_Older_Than_90_days_logs
-- ----------------------------
DROP EVENT IF EXISTS `Clean_Older_Than_90_days_logs`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` EVENT `Clean_Older_Than_90_days_logs` ON SCHEDULE EVERY '0 1' DAY_HOUR STARTS '2020-06-24 22:37:41' ON COMPLETION NOT PRESERVE ENABLE COMMENT 'Clean up log connections at 1 AM.' DO DELETE FROM ip
    WHERE time < DATE_SUB(NOW(), INTERVAL 90 DAY)
;;
DELIMITER ;
