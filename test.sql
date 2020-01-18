/*
 Navicat Premium Data Transfer

 Source Server         : 本地
 Source Server Type    : MySQL
 Source Server Version : 50553
 Source Host           : localhost:3306
 Source Schema         : test

 Target Server Type    : MySQL
 Target Server Version : 50553
 File Encoding         : 65001

 Date: 18/01/2020 11:00:26
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ad
-- ----------------------------
DROP TABLE IF EXISTS `ad`;
CREATE TABLE `ad`  (
  `ad_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '广告ID',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '广告标题',
  `describe` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `pic` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '图片的地址',
  `jump_type` tinyint(4) UNSIGNED NOT NULL DEFAULT 0 COMMENT '跳转方式（0，web 页面，1：APP内链接，2：小程序）',
  `jump_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '跳转的url路径',
  `ios_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'ios 的类名',
  `android_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'android 的类名',
  `wxa_appid` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '微信小程序的APPID（跳转类型为 1 时有效）',
  `channel_type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '渠道名单类型（0：不做处理，1：白名单，2：黑名单）',
  `channel_list` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '渠道黑名单',
  `android_version_type` tinyint(4) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'android 版本名单类型（0：不做处理，1：白名单，2：黑名单）',
  `android_version_list` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'android 版本黑名单',
  `ios_version_type` tinyint(4) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'ios 版本名单类型（0：不做处理，1：白名单，2：黑名单）',
  `ios_version_list` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'ios 版本黑名单',
  `new_show_start_num` int(11) NOT NULL DEFAULT 0 COMMENT '新用户从第几次开始展示',
  `new_show_max_num` int(11) NOT NULL DEFAULT 0 COMMENT '新用户最大展示几次',
  `old_show_start_num` int(11) NOT NULL DEFAULT 0 COMMENT '老用户第几次开始展示',
  `old_show_max_num` int(11) NOT NULL DEFAULT 0 COMMENT '老用户最大展示几次',
  `start_time` datetime NULL DEFAULT NULL COMMENT '开始时间',
  `end_time` datetime NULL DEFAULT NULL COMMENT '结束时间',
  `event_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '统计事件名称',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '广告状态（0：禁用，1：正常）',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `modified_time` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`ad_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '广告表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ad_site
-- ----------------------------
DROP TABLE IF EXISTS `ad_site`;
CREATE TABLE `ad_site`  (
  `site_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '广告位id',
  `site_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '广告位名称',
  `describe` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '广告位描述',
  `ad_ids` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '广告位的广告id（用 , 隔开）',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `modified_time` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`site_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '广告位' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for auth_admin
-- ----------------------------
DROP TABLE IF EXISTS `auth_admin`;
CREATE TABLE `auth_admin`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '登录密码；sp_password加密',
  `tel` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户手机号',
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '登录邮箱',
  `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户头像',
  `sex` smallint(1) NOT NULL DEFAULT 0 COMMENT '性别；0：保密，1：男；2：女',
  `last_login_ip` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '最后登录ip',
  `last_login_time` datetime NOT NULL COMMENT '最后登录时间',
  `create_time` datetime NOT NULL COMMENT '注册时间',
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '用户状态 0：禁用； 1：正常 ；2：未验证',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_login_key`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of auth_admin
-- ----------------------------
INSERT INTO `auth_admin` VALUES (1, 'admin', 'c3284d0f94606de1fd2af172aba15bf3', 'admin', 'lmxdawn@gmail.com', 'sssss', 0, '127.0.0.1', '2020-01-14 16:10:48', '2018-07-06 17:19:00', 1);
INSERT INTO `auth_admin` VALUES (2, 'test', '63ee451939ed580ef3c4b6f0109d1fd0', '', '', '', 0, '127.0.0.1', '2020-01-14 16:16:47', '2020-01-14 15:52:23', 1);

-- ----------------------------
-- Table structure for auth_permission
-- ----------------------------
DROP TABLE IF EXISTS `auth_permission`;
CREATE TABLE `auth_permission`  (
  `role_id` int(11) UNSIGNED NOT NULL COMMENT '角色',
  `permission_rule_id` int(11) NOT NULL DEFAULT 0 COMMENT '权限id',
  `type` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '权限规则分类，请加应用前缀,如admin_'
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '权限授权表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of auth_permission
-- ----------------------------
INSERT INTO `auth_permission` VALUES (1, 1, 'admin');
INSERT INTO `auth_permission` VALUES (1, 2, 'admin');
INSERT INTO `auth_permission` VALUES (1, 3, 'admin');
INSERT INTO `auth_permission` VALUES (1, 4, 'admin');
INSERT INTO `auth_permission` VALUES (1, 5, 'admin');
INSERT INTO `auth_permission` VALUES (1, 6, 'admin');
INSERT INTO `auth_permission` VALUES (1, 7, 'admin');
INSERT INTO `auth_permission` VALUES (1, 8, 'admin');
INSERT INTO `auth_permission` VALUES (1, 9, 'admin');
INSERT INTO `auth_permission` VALUES (1, 10, 'admin');
INSERT INTO `auth_permission` VALUES (1, 11, 'admin');
INSERT INTO `auth_permission` VALUES (1, 27, 'admin');
INSERT INTO `auth_permission` VALUES (1, 12, 'admin');
INSERT INTO `auth_permission` VALUES (1, 13, 'admin');
INSERT INTO `auth_permission` VALUES (1, 14, 'admin');
INSERT INTO `auth_permission` VALUES (1, 15, 'admin');
INSERT INTO `auth_permission` VALUES (1, 16, 'admin');
INSERT INTO `auth_permission` VALUES (1, 17, 'admin');
INSERT INTO `auth_permission` VALUES (1, 18, 'admin');
INSERT INTO `auth_permission` VALUES (1, 19, 'admin');
INSERT INTO `auth_permission` VALUES (1, 20, 'admin');
INSERT INTO `auth_permission` VALUES (1, 25, 'admin');
INSERT INTO `auth_permission` VALUES (1, 21, 'admin');
INSERT INTO `auth_permission` VALUES (1, 22, 'admin');
INSERT INTO `auth_permission` VALUES (1, 23, 'admin');
INSERT INTO `auth_permission` VALUES (1, 24, 'admin');
INSERT INTO `auth_permission` VALUES (1, 26, 'admin');
INSERT INTO `auth_permission` VALUES (1, 28, 'admin');
INSERT INTO `auth_permission` VALUES (1, 29, 'admin');
INSERT INTO `auth_permission` VALUES (1, 30, 'admin');
INSERT INTO `auth_permission` VALUES (1, 31, 'admin');
INSERT INTO `auth_permission` VALUES (1, 32, 'admin');
INSERT INTO `auth_permission` VALUES (1, 33, 'admin');
INSERT INTO `auth_permission` VALUES (1, 34, 'admin');
INSERT INTO `auth_permission` VALUES (1, 35, 'admin');
INSERT INTO `auth_permission` VALUES (1, 36, 'admin');
INSERT INTO `auth_permission` VALUES (1, 37, 'admin');
INSERT INTO `auth_permission` VALUES (1, 38, 'admin');
INSERT INTO `auth_permission` VALUES (1, 39, 'admin');

-- ----------------------------
-- Table structure for auth_permission_rule
-- ----------------------------
DROP TABLE IF EXISTS `auth_permission_rule`;
CREATE TABLE `auth_permission_rule`  (
  `id` int(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '规则编号',
  `pid` int(11) NOT NULL DEFAULT 0 COMMENT '父级id',
  `name` char(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '规则唯一标识',
  `title` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '规则中文名称',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态：为1正常，为0禁用',
  `condition` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '规则表达式，为空表示存在就验证，不为空表示按照条件验证',
  `listorder` int(10) NOT NULL DEFAULT 0 COMMENT '排序，优先级，越小优先级越高',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `name`(`name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 40 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '规则表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of auth_permission_rule
-- ----------------------------
INSERT INTO `auth_permission_rule` VALUES (1, 0, 'user_manage', '用户管理', 1, '', 999, '2018-07-06 17:19:00', '2018-07-06 17:19:00');
INSERT INTO `auth_permission_rule` VALUES (2, 1, 'user_manage/admin_manage', '管理组', 1, '', 999, '2018-07-06 17:19:00', '2018-07-06 17:19:00');
INSERT INTO `auth_permission_rule` VALUES (3, 2, 'admin/auth.admin/index', '管理员管理', 1, '', 999, '2018-07-06 17:19:00', '2018-07-06 17:19:00');
INSERT INTO `auth_permission_rule` VALUES (4, 3, 'admin/auth.admin/save', '添加管理员', 1, '', 999, '2018-07-06 17:19:00', '2018-07-06 17:19:00');
INSERT INTO `auth_permission_rule` VALUES (5, 3, 'admin/auth.admin/edit', '编辑管理员', 1, '', 999, '2018-07-06 17:19:00', '2018-07-06 17:19:00');
INSERT INTO `auth_permission_rule` VALUES (6, 3, 'admin/auth.admin/delete', '删除管理员', 1, '', 999, '2018-07-06 17:19:00', '2018-07-06 17:19:00');
INSERT INTO `auth_permission_rule` VALUES (7, 2, 'admin/auth.role/index', '角色管理', 1, '', 999, '2018-07-06 17:19:00', '2018-07-06 17:19:00');
INSERT INTO `auth_permission_rule` VALUES (8, 7, 'admin/auth.role/save', '添加角色', 1, '', 999, '2018-07-06 17:19:00', '2018-07-06 17:19:00');
INSERT INTO `auth_permission_rule` VALUES (9, 7, 'admin/auth.role/edit', '编辑角色', 1, '', 999, '2018-07-06 17:19:00', '2018-07-06 17:19:00');
INSERT INTO `auth_permission_rule` VALUES (10, 7, 'admin/auth.role/delete', '删除角色', 1, '', 999, '2018-07-06 17:19:00', '2018-07-06 17:19:00');
INSERT INTO `auth_permission_rule` VALUES (11, 7, 'admin/auth.role/auth', '角色授权', 1, '', 999, '2018-07-06 17:19:00', '2018-07-06 17:19:00');
INSERT INTO `auth_permission_rule` VALUES (12, 2, 'admin/auth.permission_rule/index', '权限管理', 1, '', 999, '2018-07-06 17:19:00', '2018-07-06 17:19:00');
INSERT INTO `auth_permission_rule` VALUES (13, 12, 'admin/auth.permission_rule/save', '添加权限', 1, '', 999, '2018-07-06 17:19:00', '2018-07-06 17:19:00');
INSERT INTO `auth_permission_rule` VALUES (14, 12, 'admin/auth.permission_rule/edit', '编辑权限', 1, '', 999, '2018-07-06 17:19:00', '2018-07-06 17:19:00');
INSERT INTO `auth_permission_rule` VALUES (15, 12, 'admin/auth.permission_rule/delete', '删除权限', 1, '', 999, '2018-07-06 17:19:00', '2018-07-06 17:19:00');
INSERT INTO `auth_permission_rule` VALUES (16, 0, 'ad_manage', '广告相关', 1, '', 999, '2018-07-06 17:19:00', '2018-07-06 17:19:00');
INSERT INTO `auth_permission_rule` VALUES (17, 16, 'admin/ad.site/index', '广告位管理', 1, '', 999, '2018-07-06 17:19:00', '2018-07-06 17:19:00');
INSERT INTO `auth_permission_rule` VALUES (18, 17, 'admin/ad.site/save', '广告位添加', 1, '', 999, '2018-07-06 17:19:00', '2018-07-06 17:19:00');
INSERT INTO `auth_permission_rule` VALUES (19, 17, 'admin/ad.site/edit', '广告位编辑', 1, '', 999, '2018-07-06 17:19:00', '2018-07-06 17:19:00');
INSERT INTO `auth_permission_rule` VALUES (20, 17, 'admin/ad.site/delete', '广告位删除', 1, '', 999, '2018-07-06 17:19:00', '2018-07-06 17:19:00');
INSERT INTO `auth_permission_rule` VALUES (21, 16, 'admin/ad/index', '广告管理', 1, '', 999, '2018-07-06 17:19:00', '2018-07-06 17:19:00');
INSERT INTO `auth_permission_rule` VALUES (22, 21, 'admin/ad/save', '广告添加', 1, '', 999, '2018-07-06 17:19:00', '2018-07-06 17:19:00');
INSERT INTO `auth_permission_rule` VALUES (23, 21, 'admin/ad/edit', '广告编辑', 1, '', 999, '2018-07-06 17:19:00', '2018-07-06 17:19:00');
INSERT INTO `auth_permission_rule` VALUES (24, 21, 'admin/ad/delete', '广告删除', 1, '', 999, '2018-07-06 17:19:00', '2018-07-06 17:19:00');
INSERT INTO `auth_permission_rule` VALUES (25, 17, 'admin/ad.site/adlist', '广告位选择时的广告列表', 1, '', 999, '2018-07-06 17:19:00', '2018-07-06 17:19:00');
INSERT INTO `auth_permission_rule` VALUES (26, 0, 'good', '商品管理', 1, '', 999, '2020-01-14 16:09:09', '2020-01-14 16:09:09');
INSERT INTO `auth_permission_rule` VALUES (27, 7, 'admin/auth.role/authlist', '授权列表', 1, '', 999, '2020-01-14 16:13:02', '2020-01-14 16:13:02');
INSERT INTO `auth_permission_rule` VALUES (28, 26, 'admin/good.category/index', '商品分类', 1, '', 999, '2020-01-14 16:37:57', '2020-01-14 16:37:57');
INSERT INTO `auth_permission_rule` VALUES (29, 28, 'admin/good.category/save', '添加商品分类', 1, '', 999, '2020-01-14 16:57:27', '2020-01-14 16:57:27');
INSERT INTO `auth_permission_rule` VALUES (30, 28, 'admin/good.category/edit', '编辑商品分类', 1, '', 999, '2020-01-14 16:57:45', '2020-01-14 16:57:45');
INSERT INTO `auth_permission_rule` VALUES (31, 28, 'admin/good.category/delete', '删除商品分类', 1, '', 999, '2020-01-14 16:57:58', '2020-01-14 16:57:58');
INSERT INTO `auth_permission_rule` VALUES (32, 26, 'admin/good.category_attr/index', '属性管理', 1, '', 999, '2020-01-16 10:49:08', '2020-01-16 10:49:08');
INSERT INTO `auth_permission_rule` VALUES (33, 32, 'admin/good.category_attr/save', '添加属性', 1, '', 999, '2020-01-16 10:49:21', '2020-01-16 10:51:44');
INSERT INTO `auth_permission_rule` VALUES (34, 32, 'admin/good.category_attr/edit', '编辑属性', 1, '', 999, '2020-01-16 10:49:32', '2020-01-16 10:49:32');
INSERT INTO `auth_permission_rule` VALUES (35, 32, 'admin/good.category_attr/delete', '删除属性', 1, '', 999, '2020-01-16 10:49:47', '2020-01-16 10:49:47');
INSERT INTO `auth_permission_rule` VALUES (36, 26, 'admin/good.category_spec/index', '规格管理', 1, '', 999, '2020-01-16 11:48:38', '2020-01-16 11:48:38');
INSERT INTO `auth_permission_rule` VALUES (37, 36, 'admin/good.category_spec/save', '添加规格', 1, '', 999, '2020-01-16 11:48:50', '2020-01-16 11:48:50');
INSERT INTO `auth_permission_rule` VALUES (38, 36, 'admin/good.category_spec/edit', '编辑规格', 1, '', 999, '2020-01-16 11:49:01', '2020-01-16 11:49:01');
INSERT INTO `auth_permission_rule` VALUES (39, 36, 'admin/good.category_spec/delete', '删除规格', 1, '', 999, '2020-01-16 11:49:14', '2020-01-16 11:49:14');

-- ----------------------------
-- Table structure for auth_role
-- ----------------------------
DROP TABLE IF EXISTS `auth_role`;
CREATE TABLE `auth_role`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '角色名称',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父角色ID',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  `listorder` int(3) NOT NULL DEFAULT 0 COMMENT '排序，优先级，越小优先级越高',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '角色表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of auth_role
-- ----------------------------
INSERT INTO `auth_role` VALUES (1, '超级管理员', 0, 1, '拥有网站最高管理员权限！', '2018-07-06 17:19:00', '2018-07-06 17:19:00', 0);

-- ----------------------------
-- Table structure for auth_role_admin
-- ----------------------------
DROP TABLE IF EXISTS `auth_role_admin`;
CREATE TABLE `auth_role_admin`  (
  `role_id` int(11) UNSIGNED NULL DEFAULT 0 COMMENT '角色 id',
  `admin_id` int(11) NULL DEFAULT 0 COMMENT '管理员id'
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户角色对应表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of auth_role_admin
-- ----------------------------
INSERT INTO `auth_role_admin` VALUES (1, 2);

-- ----------------------------
-- Table structure for file_resource
-- ----------------------------
DROP TABLE IF EXISTS `file_resource`;
CREATE TABLE `file_resource`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '资源id',
  `tag_id` int(11) NOT NULL DEFAULT 0 COMMENT '资源分组id',
  `type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '资源的类型（0：图片）',
  `filename` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '资源的原名',
  `path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '资源的路径（不加 域名的地址）',
  `size` int(11) NOT NULL DEFAULT 0 COMMENT '大小',
  `ext` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '资源的文件后缀',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '资源表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of file_resource
-- ----------------------------
INSERT INTO `file_resource` VALUES (1, 1, 0, 'Group 5.png', 'resources/image/20180530/854ae62758c585be5128cf344a511242.png', 7539, 'png', '2018-05-30 20:41:54');
INSERT INTO `file_resource` VALUES (2, 0, 0, '643353_sdfaf123.png', 'resources/image/20180823/c356ca140f631a512f1c3a5e37a15dc1.png', 11507, 'png', '2018-08-23 13:38:42');
INSERT INTO `file_resource` VALUES (3, 0, 0, '643353_sdfaf123.png', 'resources/image/20180823/4549c39e9c07c35681ee9fa94e0fc07e.png', 11507, 'png', '2018-08-23 14:05:18');
INSERT INTO `file_resource` VALUES (4, 0, 0, '', '', 0, '', '2018-08-23 15:45:21');
INSERT INTO `file_resource` VALUES (5, 0, 0, '', '', 2000000, '', '2018-08-23 15:45:21');
INSERT INTO `file_resource` VALUES (6, 0, 0, '', '', 0, '', '2018-08-23 15:45:21');
INSERT INTO `file_resource` VALUES (7, 0, 0, '', '', 0, '', '2018-08-23 15:45:21');
INSERT INTO `file_resource` VALUES (8, 0, 0, '643353_sdfaf123.png', 'resources/image/20180823/0c424412b231eb8cb969377e15dbb812.png', 11507, 'png', '2018-08-23 15:53:32');
INSERT INTO `file_resource` VALUES (9, 0, 0, '232826334630444283.png', 'FjBRVPOPF9gLeNBCAvK7jbif4yg8', 9668, 'png', '2018-08-23 16:08:13');
INSERT INTO `file_resource` VALUES (10, 0, 0, '232826334630444283.png', 'FjBRVPOPF9gLeNBCAvK7jbif4yg8', 9668, 'png', '2018-08-23 16:09:07');
INSERT INTO `file_resource` VALUES (11, 0, 0, '643353_sdfaf123.png', 'resources/image/20180823/52af5f8556a3af84cee696972b61baf4.png', 11507, 'png', '2018-08-23 17:06:05');

-- ----------------------------
-- Table structure for file_resource_tag
-- ----------------------------
DROP TABLE IF EXISTS `file_resource_tag`;
CREATE TABLE `file_resource_tag`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '资源分组的id',
  `tag` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '资源分组的tag',
  `create_time` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '资源的分组表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of file_resource_tag
-- ----------------------------
INSERT INTO `file_resource_tag` VALUES (1, '测试', '2018-05-30 20:41:48');

-- ----------------------------
-- Table structure for good
-- ----------------------------
DROP TABLE IF EXISTS `good`;
CREATE TABLE `good`  (
  `good_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '商品ID',
  `good_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '商品名称',
  `good_remark` varchar(140) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品简介',
  `shop_price` decimal(10, 2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '售价',
  `market_price` decimal(10, 2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '市场价',
  `cost_price` decimal(10, 2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '成本价',
  `original_img` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '商品主图',
  `weight` double UNSIGNED NULL DEFAULT NULL COMMENT '商品重量（克为单位）',
  `volume` double NULL DEFAULT NULL COMMENT '商品体积（立方米：m³为单位）',
  `store_count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '总库存',
  `virtual_sales_sum` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '虚拟销售量（销售量：虚拟销售量+真实销售量）',
  PRIMARY KEY (`good_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for good_category
-- ----------------------------
DROP TABLE IF EXISTS `good_category`;
CREATE TABLE `good_category`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上级分类ID',
  `level` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '层级',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '名称',
  `pic` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT '封面图',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '权重（越大越靠前）',
  `is_show` tinyint(3) UNSIGNED NOT NULL COMMENT '是否显示（导航栏中）',
  `is_recommend` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否推荐（首页中显示）',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 14 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品分类表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of good_category
-- ----------------------------
INSERT INTO `good_category` VALUES (2, 0, 1, '电脑/办公', '', 0, 1, 0, '2020-01-15 11:05:03', '2020-01-15 11:05:03');
INSERT INTO `good_category` VALUES (3, 2, 2, '电脑整机', '', 0, 1, 0, '2020-01-15 11:11:55', '2020-01-15 11:11:55');
INSERT INTO `good_category` VALUES (4, 3, 3, '笔记本', '', 0, 1, 0, '2020-01-15 11:12:16', '2020-01-15 11:12:16');
INSERT INTO `good_category` VALUES (5, 2, 2, '电脑配件', '', 0, 1, 0, '2020-01-15 11:12:39', '2020-01-15 11:12:39');
INSERT INTO `good_category` VALUES (6, 5, 3, '显示器', '', 0, 1, 0, '2020-01-15 11:12:48', '2020-01-15 11:12:48');
INSERT INTO `good_category` VALUES (7, 0, 1, '男鞋/运动/户外', '', 0, 1, 0, '2020-01-15 11:13:12', '2020-01-15 11:13:12');
INSERT INTO `good_category` VALUES (8, 7, 2, '户外装备', '', 0, 1, 0, '2020-01-15 11:13:20', '2020-01-15 11:13:20');
INSERT INTO `good_category` VALUES (9, 8, 3, '帐篷', '', 0, 1, 0, '2020-01-15 11:13:28', '2020-01-15 11:13:28');
INSERT INTO `good_category` VALUES (10, 0, 1, '测试', 'resources/20200115/2177f79b98d64d016a5584f815436a0a.png', 0, 1, 0, '2020-01-15 16:11:17', '2020-01-15 16:11:17');
INSERT INTO `good_category` VALUES (11, 10, 2, '测试2', '', 0, 1, 0, '2020-01-15 16:57:12', '2020-01-15 16:57:12');
INSERT INTO `good_category` VALUES (12, 10, 2, '测试3', '', 0, 1, 0, '2020-01-15 16:58:14', '2020-01-15 16:58:14');
INSERT INTO `good_category` VALUES (13, 12, 3, '测试4', '', 0, 1, 0, '2020-01-15 16:58:22', '2020-01-15 16:58:22');

-- ----------------------------
-- Table structure for good_category_attr
-- ----------------------------
DROP TABLE IF EXISTS `good_category_attr`;
CREATE TABLE `good_category_attr`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL COMMENT '分类ID',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '属性名称',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '类型（0：手动输入，1：下拉选择）',
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '属性可选值',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序（由大到小）',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品分类的属性' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of good_category_attr
-- ----------------------------
INSERT INTO `good_category_attr` VALUES (1, 4, ' 机身厚度', 0, '', 0, '2020-01-16 10:51:47', '0000-00-00 00:00:00');
INSERT INTO `good_category_attr` VALUES (2, 4, '系统', 0, '', 0, '2020-01-16 10:56:06', '0000-00-00 00:00:00');
INSERT INTO `good_category_attr` VALUES (4, 4, '发发发', 1, '发货后\n妇女拿\n事实上\n吃串串\n灌灌灌灌\n事实上\ng\ng\nh\nh', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- ----------------------------
-- Table structure for good_category_spec
-- ----------------------------
DROP TABLE IF EXISTS `good_category_spec`;
CREATE TABLE `good_category_spec`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL COMMENT '分类ID',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '规格名称',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序（由大到小）',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品分类的规格' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of good_category_spec
-- ----------------------------
INSERT INTO `good_category_spec` VALUES (1, 4, '颜色', 0, '2020-01-16 11:57:48', '0000-00-00 00:00:00');
INSERT INTO `good_category_spec` VALUES (2, 4, '版本', 0, '2020-01-16 11:58:15', '0000-00-00 00:00:00');

-- ----------------------------
-- Table structure for good_type
-- ----------------------------
DROP TABLE IF EXISTS `good_type`;
CREATE TABLE `good_type`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '模型名称',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品模型表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for good_type_attr
-- ----------------------------
DROP TABLE IF EXISTS `good_type_attr`;
CREATE TABLE `good_type_attr`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL COMMENT '模型ID',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '属性名称',
  `value` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '属性可选值',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序（由大到小）',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品类型的属性' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for good_type_spec
-- ----------------------------
DROP TABLE IF EXISTS `good_type_spec`;
CREATE TABLE `good_type_spec`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL COMMENT '模型ID',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '规格名称',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序（由大到小）',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品类型的规格' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for good_type_spec_exist
-- ----------------------------
DROP TABLE IF EXISTS `good_type_spec_exist`;
CREATE TABLE `good_type_spec_exist`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `spec_id` int(11) NOT NULL COMMENT '规格ID',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '名称',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci COMMENT = '商品类型的规格已存在的表' ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
