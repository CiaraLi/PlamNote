/*
 Navicat Premium Data Transfer

 Source Server         : Localhost
 Source Server Type    : MySQL
 Source Server Version : 50717
 Source Host           : localhost
 Source Database       : cAuth

 Target Server Type    : MySQL
 Target Server Version : 50717
 File Encoding         : utf-8

 Date: 08/10/2017 22:22:52 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `cSessionInfo`
-- ----------------------------
DROP TABLE IF EXISTS `cSessionInfo`;
CREATE TABLE `cSessionInfo` (
  `open_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uuid` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `skey` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_visit_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `session_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_info` varchar(2048) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`open_id`),
  KEY `openid` (`open_id`) USING BTREE,
  KEY `skey` (`skey`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='会话管理用户信息';

-- ----------------------------
--  Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
 `user_id` int(11) NOT NULL AUTO_INCREMENT,
 `open_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
 `nick_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `user_head` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `user_phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `create_time` int(11) NOT NULL,
 `country` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `city` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `gender` tinyint(2) NOT NULL,
 PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  COMMENT='用户表';

-- ----------------------------
--  Table structure for `note_group`
-- ----------------------------
DROP TABLE IF EXISTS `note_group`;
CREATE TABLE `note_group` (
 `group_id` int(11) NOT NULL AUTO_INCREMENT,
 `user_id` int(11) NOT NULL COMMENT '用户',
 `group_name` varchar(50) NOT NULL COMMENT '分组名称',
 `create_time` int(11) NOT NULL COMMENT '创建时间',
 `group_status` tinyint(4) NOT NULL COMMENT '状态',
 PRIMARY KEY (`group_id`)
) ENGINE=InnoDB  COMMENT='分组表';

-- ----------------------------
--  Table structure for `notes`
-- ----------------------------
DROP TABLE IF EXISTS `notes`;
CREATE TABLE `notes` (
 `note_id` int(11) NOT NULL AUTO_INCREMENT,
 `user_id` int(11) NOT NULL COMMENT '用户',
 `group_id` int(11) NOT NULL COMMENT '分组',
 `note_title` varchar(50) NOT NULL COMMENT '标题',
 `note_lable` varchar(50) NOT NULL DEFAULT '' COMMENT '标签',
 `note_checkbox` tinyint(4) NOT NULL COMMENT '可选择',
 `create_time` int(11) NOT NULL COMMENT '创建时间',
 `update_time` int(11) NOT NULL COMMENT '修改时间时间',
 `note_status` tinyint(4) NOT NULL COMMENT '状态',
 PRIMARY KEY (`note_id`)
) ENGINE=InnoDB   COMMENT='note表';

-- ----------------------------
--  Table structure for `note_list`
-- ----------------------------
DROP TABLE IF EXISTS `note_list`;
CREATE TABLE `note_list` (
 `list_id` int(11) NOT NULL AUTO_INCREMENT,
 `user_id` int(11) NOT NULL COMMENT '用户',
 `note_id` int(11) NOT NULL COMMENT '清单', 
 `list_content` varchar(500) NOT NULL  COMMENT '清单内容',
 `list_type` varchar(50) NOT NULL DEFAULT '' COMMENT '类型1文本 2清单 3网址 ',
 `list_checked` varchar(50) NOT NULL DEFAULT '' COMMENT '选中状态',
 `list_color` tinyint(4) NOT NULL   COMMENT '颜色 1white  2yellow 3red  4green  5blue 6black ',
 `create_time` int(11) NOT NULL COMMENT '创建时间',
 `update_time` int(11) NOT NULL COMMENT '修改时间时间',
 `list_status` tinyint(4) NOT NULL COMMENT '状态',
 PRIMARY KEY (`list_id`)
) ENGINE=InnoDB COMMENT='笔记清单表';

