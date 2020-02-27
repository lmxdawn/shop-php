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

 Date: 28/02/2020 00:14:20
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
  `jump_type` tinyint(4) UNSIGNED NOT NULL DEFAULT 0 COMMENT '跳转方式（0，web 页面，1：APP内链接，2：小程序，3：uniapp内部链接，4，uniapptabBar链接）',
  `jump_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '跳转的url路径',
  `ios_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'ios 的类名',
  `android_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'android 的类名',
  `wxa_appid` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '微信小程序的APPID（跳转类型为 2 时有效）',
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
) ENGINE = MyISAM AUTO_INCREMENT = 13 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '广告表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ad
-- ----------------------------
INSERT INTO `ad` VALUES (1, '商品广告', '', 'resources/20200223/22f113bb3179ba2df9bbd23fc8a04745.jpg', 3, '1', '', '', '', 0, '', 0, '', 0, '', 0, 0, 0, 0, NULL, NULL, '', 1, '2020-02-23 21:39:52', '2020-02-23 21:39:52');
INSERT INTO `ad` VALUES (2, '商品广告2', '呃呃呃呃', 'resources/20200223/a5ce46403b6a92cdb6eac3d5dca9768f.jpg', 3, '11', '', '', '', 0, '', 0, '', 0, '', 0, 0, 0, 0, NULL, NULL, '', 1, '2020-02-23 21:42:25', '2020-02-23 21:42:25');
INSERT INTO `ad` VALUES (3, '短袖T恤', '首页推荐分类', 'resources/20200223/2326221a548f26fd787ee5936e6b7e98.jpg', 3, 'category/category?=1', '', '', '', 0, '', 0, '', 0, '', 0, 0, 0, 0, NULL, NULL, '', 1, '2020-02-23 21:50:58', '2020-02-23 21:50:58');
INSERT INTO `ad` VALUES (4, '足球', '分类', 'resources/20200223/7831c755c050cbe4e7c3f0103b1d7392.jpg', 3, '111', '', '', '', 0, '', 0, '', 0, '', 0, 0, 0, 0, NULL, NULL, '', 1, '2020-02-23 22:01:25', '2020-02-23 22:01:25');
INSERT INTO `ad` VALUES (5, '运动鞋', '分类信息', 'resources/20200223/5da26da0070721cdd2bea30d813bed6b.jpg', 3, '2222', '', '', '', 0, '', 0, '', 0, '', 0, 0, 0, 0, NULL, NULL, '', 1, '2020-02-23 22:01:56', '2020-02-23 22:01:56');
INSERT INTO `ad` VALUES (6, '中老年', '分类信息', 'resources/20200223/0434c922cb73be03475be79fa792da78.png', 3, '11', '', '', '', 0, '', 0, '', 0, '', 0, 0, 0, 0, NULL, NULL, '', 1, '2020-02-23 22:02:26', '2020-02-23 22:02:26');
INSERT INTO `ad` VALUES (7, '甜美风', '分类信息', 'resources/20200223/b94803b7ac20dc5224fb2242c477048c.png', 3, '22', '', '', '', 0, '', 0, '', 0, '', 0, 0, 0, 0, NULL, NULL, '', 1, '2020-02-23 22:02:50', '2020-02-23 22:02:50');
INSERT INTO `ad` VALUES (8, '鱼尾裙', '分类信息', 'resources/20200223/11c1696a173d80baa6296f37ce374e3d.jpg', 3, '111', '', '', '', 0, '', 0, '', 0, '', 0, 0, 0, 0, NULL, NULL, '', 1, '2020-02-23 22:03:12', '2020-02-23 22:03:12');
INSERT INTO `ad` VALUES (9, '相机配件', '分类信息', 'resources/20200223/b25eba7c50cd2f55c2fcf50f890f800a.jpg', 3, '1111', '', '', '', 0, '', 0, '', 0, '', 0, 0, 0, 0, NULL, NULL, '', 1, '2020-02-23 22:03:36', '2020-02-23 22:03:36');
INSERT INTO `ad` VALUES (10, '护肤套装', '分类信息', 'resources/20200223/4ad077114a185897c2fce83668ce0eba.jpg', 3, '111', '', '', '', 0, '', 0, '', 0, '', 0, 0, 0, 0, NULL, NULL, '', 1, '2020-02-23 22:04:03', '2020-02-23 22:04:03');
INSERT INTO `ad` VALUES (11, '单肩包', '分类信息', 'resources/20200223/74aa14b5b06df1f60174360d86ad8996.jpg', 4, 'category/category?category_id=7', '', '', '', 0, '', 0, '', 0, '', 0, 0, 0, 0, NULL, NULL, '', 1, '2020-02-25 00:26:44', '2020-02-25 00:26:44');
INSERT INTO `ad` VALUES (12, '卫衣', '分类信息', 'resources/20200223/70012961c2603b591924564ebdf571d1.jpg', 0, 'https://www.baidu.com', '', '', '', 0, '', 0, '', 0, '', 0, 0, 0, 0, NULL, NULL, '', 1, '2020-02-24 22:56:26', '2020-02-24 22:56:26');

-- ----------------------------
-- Table structure for ad_site
-- ----------------------------
DROP TABLE IF EXISTS `ad_site`;
CREATE TABLE `ad_site`  (
  `site_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '广告位id',
  `site_key` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '广告位key',
  `site_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '广告位名称',
  `describe` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '广告位描述',
  `ad_ids` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '广告位的广告id（用 , 隔开）',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `modified_time` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`site_id`) USING BTREE,
  UNIQUE INDEX `uk_site_key`(`site_key`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '广告位' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ad_site
-- ----------------------------
INSERT INTO `ad_site` VALUES (1, 'index_top_banner', '首页顶部banner', '', '1,2', '2020-02-23 21:31:42', '2020-02-23 23:22:04');
INSERT INTO `ad_site` VALUES (2, 'index_cate_list', '首页分类列表', '', '3,4,5,6,7,8,9,10,11,12', '2020-02-23 22:18:48', '2020-02-23 22:18:48');

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
INSERT INTO `auth_admin` VALUES (1, 'admin', 'c3284d0f94606de1fd2af172aba15bf3', 'admin', 'lmxdawn@gmail.com', 'sssss', 0, '127.0.0.1', '2020-02-20 01:11:59', '2018-07-06 17:19:00', 1);
INSERT INTO `auth_admin` VALUES (2, 'test', 'd9b1d7db4cd6e70935368a1efb10e377', '', '', '', 0, '127.0.0.1', '2020-02-27 18:56:39', '2020-01-14 15:52:23', 1);

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
INSERT INTO `auth_permission` VALUES (1, 40, 'admin');
INSERT INTO `auth_permission` VALUES (1, 41, 'admin');
INSERT INTO `auth_permission` VALUES (1, 42, 'admin');
INSERT INTO `auth_permission` VALUES (1, 43, 'admin');
INSERT INTO `auth_permission` VALUES (1, 44, 'admin');
INSERT INTO `auth_permission` VALUES (1, 45, 'admin');
INSERT INTO `auth_permission` VALUES (1, 46, 'admin');
INSERT INTO `auth_permission` VALUES (1, 47, 'admin');
INSERT INTO `auth_permission` VALUES (1, 48, 'admin');
INSERT INTO `auth_permission` VALUES (1, 49, 'admin');
INSERT INTO `auth_permission` VALUES (1, 50, 'admin');
INSERT INTO `auth_permission` VALUES (1, 51, 'admin');
INSERT INTO `auth_permission` VALUES (1, 52, 'admin');
INSERT INTO `auth_permission` VALUES (1, 53, 'admin');
INSERT INTO `auth_permission` VALUES (1, 54, 'admin');
INSERT INTO `auth_permission` VALUES (1, 55, 'admin');

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
) ENGINE = InnoDB AUTO_INCREMENT = 56 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '规则表' ROW_FORMAT = Compact;

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
INSERT INTO `auth_permission_rule` VALUES (21, 16, 'admin/ad.ad/index', '广告管理', 1, '', 999, '2018-07-06 17:19:00', '2020-02-23 21:30:10');
INSERT INTO `auth_permission_rule` VALUES (22, 21, 'admin/ad.ad/save', '广告添加', 1, '', 999, '2018-07-06 17:19:00', '2020-02-23 21:30:28');
INSERT INTO `auth_permission_rule` VALUES (23, 21, 'admin/ad.ad/edit', '广告编辑', 1, '', 999, '2018-07-06 17:19:00', '2020-02-23 21:30:37');
INSERT INTO `auth_permission_rule` VALUES (24, 21, 'admin/ad.ad/delete', '广告删除', 1, '', 999, '2018-07-06 17:19:00', '2020-02-23 21:30:44');
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
INSERT INTO `auth_permission_rule` VALUES (40, 26, 'admin/good.good/index', '商品列表', 1, '', 999, '2020-02-18 16:49:51', '2020-02-20 01:17:25');
INSERT INTO `auth_permission_rule` VALUES (41, 40, 'admin/good.good/categorylist', '编辑商品时加载分类', 1, '', 999, '2020-02-18 16:50:54', '2020-02-20 01:17:32');
INSERT INTO `auth_permission_rule` VALUES (42, 40, 'admin/good.good/save', '添加商品', 1, '', 999, '2020-02-18 16:51:10', '2020-02-20 01:17:51');
INSERT INTO `auth_permission_rule` VALUES (43, 40, 'admin/good.good/edit', '修改商品', 1, '', 999, '2020-02-18 16:51:25', '2020-02-20 01:17:44');
INSERT INTO `auth_permission_rule` VALUES (44, 40, 'admin/good.good/delete', '删除商品', 1, '', 999, '2020-02-18 16:51:42', '2020-02-20 01:17:57');
INSERT INTO `auth_permission_rule` VALUES (45, 40, 'admin/good.good/attrlist', '分类的属性列表', 1, '', 999, '2020-02-20 01:13:26', '2020-02-20 01:18:05');
INSERT INTO `auth_permission_rule` VALUES (46, 40, 'admin/good.good/read', '商品详情', 1, '', 999, '2020-02-20 11:47:50', '2020-02-20 11:47:50');
INSERT INTO `auth_permission_rule` VALUES (47, 40, 'admin/good.good/speclist', '规格列表', 1, '', 999, '2020-02-20 14:18:04', '2020-02-20 14:18:04');
INSERT INTO `auth_permission_rule` VALUES (48, 40, 'admin/good.good/status', '修改状态', 1, '', 999, '2020-02-27 19:32:25', '2020-02-27 19:32:25');
INSERT INTO `auth_permission_rule` VALUES (49, 40, 'admin/good.good/is_new', '修改新品状态', 1, '', 999, '2020-02-27 19:32:44', '2020-02-27 19:32:44');
INSERT INTO `auth_permission_rule` VALUES (50, 40, 'admin/good.good/is_recommend', '修改推荐状态', 1, '', 999, '2020-02-27 19:33:03', '2020-02-27 19:33:03');
INSERT INTO `auth_permission_rule` VALUES (51, 40, 'admin/good.good/is_hot', '修改热卖状态', 1, '', 999, '2020-02-27 19:33:22', '2020-02-27 19:33:22');
INSERT INTO `auth_permission_rule` VALUES (52, 0, 'order', '订单管理', 1, '', 999, '2020-02-27 21:29:37', '2020-02-27 21:29:37');
INSERT INTO `auth_permission_rule` VALUES (53, 52, 'admin/order.order/index', '订单列表', 1, '', 999, '2020-02-27 21:29:54', '2020-02-27 21:29:54');
INSERT INTO `auth_permission_rule` VALUES (54, 53, 'admin/order.order/read', '订单详情', 1, '', 999, '2020-02-27 21:43:36', '2020-02-27 21:43:36');
INSERT INTO `auth_permission_rule` VALUES (55, 53, 'admin/order.order/push', '订单发货', 1, '', 999, '2020-02-27 21:43:50', '2020-02-27 21:43:50');

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
  `imgs` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '图片列表',
  `unit` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '计量单位',
  `weight` double UNSIGNED NULL DEFAULT NULL COMMENT '商品重量（克为单位）',
  `volume` double NULL DEFAULT NULL COMMENT '商品体积（立方米：m³为单位）',
  `store_count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '总库存',
  `virtual_sales_sum` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '虚拟销售量（销售量：虚拟销售量+真实销售量）',
  `details` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '详情',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态（0：下架，1：上架）',
  `is_new` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否是新品（0：否，1：是）',
  `new_sort` int(11) NOT NULL DEFAULT 0 COMMENT '新品权重',
  `is_recommend` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否推荐（0：否，1：是）',
  `recommend_sort` int(11) NOT NULL DEFAULT 0 COMMENT '推荐权重',
  `is_hot` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否热卖爆款（0：否，1：是）',
  `hot_sort` int(11) NOT NULL DEFAULT 0 COMMENT '热卖爆款权重',
  `sales_sum` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '实际销售量',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`good_id`) USING BTREE,
  INDEX `idx_status_isnew_new_sort_create_time`(`status`, `is_new`, `new_sort`, `create_time`) USING BTREE,
  INDEX `idx_status_ishot_hot_sort_create_time`(`status`, `is_hot`, `hot_sort`, `create_time`) USING BTREE,
  INDEX `idx_status_isrecommend_recommend_sort_create_time`(`status`, `is_recommend`, `recommend_sort`, `create_time`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 13 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of good
-- ----------------------------
INSERT INTO `good` VALUES (1, '1', '1', 1.00, 1.00, 1.00, 'resources/20200219/a5f029b7bdcfe189d8ac37e5aef9e4cc.jpg', 'resources/20200219/0406735325fb7dbf77baf967ebc780a2.png', '件', 1, 1, 0, 1, '发发发持续性1111<audio style=\"display: none;\" controls=\"controls\"></audio>', 1, 0, 0, 0, 0, 0, 0, 0, 0, '2020-02-19 00:03:09', '0000-00-00 00:00:00');
INSERT INTO `good` VALUES (2, '2', '2', 2.00, 2.00, 2.00, 'resources/20200219/343a16af35dafc69c6152617a0660f4f.png', 'resources/20200219/f6e96146e7cf188afdc7f555f7a3ee9b.png', '件', 2, 2, 0, 2, '22222222211111111<audio style=\"display: none;\" controls=\"controls\"></audio>', 1, 0, 0, 0, 0, 0, 0, 0, 0, '2020-02-19 00:18:05', '0000-00-00 00:00:00');
INSERT INTO `good` VALUES (4, '33', '3', 3.00, 3.00, 3.00, 'resources/20200219/96c555fc1e9a68db27dcdda9322ff565.jpg', 'resources/20200219/e584d41e85819667c474d395f80228ea.png', '件', 3, 3, 33, 3, '3333333333<audio style=\"display: none;\" controls=\"controls\"></audio>', 1, 0, 0, 0, 0, 0, 0, 0, 0, '2020-02-21 16:30:47', '0000-00-00 00:00:00');
INSERT INTO `good` VALUES (5, '55511', '1', 1.00, 1.00, 1.00, 'resources/20200220/9517eecbf61c9c8554d70b3795d47a93.jpg', 'resources/20200220/12a04a33da5360da47e342dba9c69cfc.png', '件', 1, 1, 0, 1, '111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111122131<audio style=\"display: none;\" controls=\"controls\"></audio>', 1, 0, 0, 0, 0, 0, 0, 0, 0, '2020-02-21 15:21:44', '0000-00-00 00:00:00');
INSERT INTO `good` VALUES (6, '产出', '才', 1.00, 1.00, 1.00, 'resources/20200220/669f669418ad3edcde424f037eb78708.png', 'resources/20200220/90ca03bf2cab02000451d75b3a300f4b.jpg', '件', 0, 0, 0, 0, '', 1, 0, 0, 0, 0, 0, 0, 0, 0, '2020-02-25 09:39:35', '0000-00-00 00:00:00');
INSERT INTO `good` VALUES (7, 'ii', 'ii', 11.00, 12.00, 10.00, 'resources/20200221/503db6341a11bb9f6693318c8dcac57b.png', 'resources/20200221/c22d504f2dad50de7ac6a02db6ca2579.jpg', '件', 1, 1, 1, 1, '', 1, 0, 0, 0, 0, 0, 0, 0, 0, '2020-02-21 16:49:18', '0000-00-00 00:00:00');
INSERT INTO `good` VALUES (8, 'rr', '11', 1.00, 1.00, 1.00, 'resources/20200221/b58928f7ad6ea827e1a263174e20635c.jpg', 'resources/20200221/5ea78f80180e2b4da5af5619266eef3c.png', '件', 1, 1, 1, 1, '', 1, 0, 0, 0, 0, 0, 0, 0, 0, '2020-02-21 16:49:54', '0000-00-00 00:00:00');
INSERT INTO `good` VALUES (9, 'rr', '11', 1.00, 1.00, 1.00, 'resources/20200221/b58928f7ad6ea827e1a263174e20635c.jpg', 'resources/20200221/5ea78f80180e2b4da5af5619266eef3c.png', '件', 1, 1, 1, 1, '', 1, 0, 0, 0, 0, 0, 0, 0, 0, '2020-02-21 16:51:27', '0000-00-00 00:00:00');
INSERT INTO `good` VALUES (10, 'rr', '11', 1.00, 1.00, 1.00, 'resources/20200221/b58928f7ad6ea827e1a263174e20635c.jpg', 'resources/20200221/5ea78f80180e2b4da5af5619266eef3c.png', '件', 1, 1, 1, 1, '', 1, 0, 0, 0, 0, 0, 0, 0, 0, '2020-02-21 16:51:52', '0000-00-00 00:00:00');
INSERT INTO `good` VALUES (11, 'rr', '11', 1.00, 1.00, 1.00, 'resources/20200221/b58928f7ad6ea827e1a263174e20635c.jpg', 'resources/20200221/5ea78f80180e2b4da5af5619266eef3c.png', '箱', 1, 1, 1, 1, '', 1, 1, 23, 1, 2, 0, 0, 0, 0, '2020-02-24 11:05:40', '0000-00-00 00:00:00');
INSERT INTO `good` VALUES (12, '键盘测试', '', 10000.00, 69111.90, 98.00, 'resources/20200224/70696ee44ba5163cdcec4920821f1e47.png', 'resources/20200224/15ea47088a130cb54f8c9d2f59eff108.png', '箱', 1, 1, 8, 1, '<img src=\"http://test-shop.lmx.cn/uploads/resources/20200225/e05f4b8c3bc95c8660d9aa540e79591d.jpg\" width=\"600\" height=\"154\" /><br /><img src=\"http://test-shop.lmx.cn/uploads/resources/20200225/3662d3807e9b1abced52334107320bd2.jpg\" width=\"600\" height=\"432\" /><br /><img src=\"http://test-shop.lmx.cn/uploads/resources/20200225/a059d585fba63c970a56454c63f13bf8.jpg\" width=\"600\" height=\"355\" /><br /><audio style=\"display: none;\" controls=\"controls\"></audio>', 0, 1, 0, 0, 0, 1, 0, 0, 0, '2020-02-25 17:42:39', '0000-00-00 00:00:00');

-- ----------------------------
-- Table structure for good_attr_list
-- ----------------------------
DROP TABLE IF EXISTS `good_attr_list`;
CREATE TABLE `good_attr_list`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `good_id` int(11) NULL DEFAULT NULL COMMENT '商品ID',
  `attr_id` int(10) UNSIGNED NOT NULL COMMENT '属性ID',
  `value` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '属性值',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_good_id_attr_id`(`good_id`, `attr_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 88 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品属性列表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of good_attr_list
-- ----------------------------
INSERT INTO `good_attr_list` VALUES (87, 6, 8, '吃串串', '2020-02-25 09:39:35');
INSERT INTO `good_attr_list` VALUES (72, 5, 8, '发货后', '2020-02-21 15:21:44');
INSERT INTO `good_attr_list` VALUES (71, 5, 2, '33', '2020-02-21 15:21:44');
INSERT INTO `good_attr_list` VALUES (70, 5, 1, '12', '2020-02-21 15:21:44');
INSERT INTO `good_attr_list` VALUES (86, 6, 2, '方法', '2020-02-25 09:39:35');
INSERT INTO `good_attr_list` VALUES (85, 6, 1, '实习生', '2020-02-25 09:39:35');

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
) ENGINE = MyISAM AUTO_INCREMENT = 29 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品分类表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of good_category
-- ----------------------------
INSERT INTO `good_category` VALUES (2, 0, 1, '电脑/办公', '', 0, 1, 0, '2020-01-15 11:05:03', '2020-01-15 11:05:03');
INSERT INTO `good_category` VALUES (3, 2, 2, '电脑整机', '', 0, 1, 0, '2020-01-15 11:11:55', '2020-01-15 11:11:55');
INSERT INTO `good_category` VALUES (4, 3, 3, '笔记本', '', 0, 1, 0, '2020-01-15 11:12:16', '2020-01-15 11:12:16');
INSERT INTO `good_category` VALUES (5, 2, 2, '电脑配件', '', 0, 1, 0, '2020-01-15 11:12:39', '2020-01-15 11:12:39');
INSERT INTO `good_category` VALUES (6, 5, 3, '显示器', '', 0, 1, 0, '2020-01-15 11:12:48', '2020-01-15 11:12:48');
INSERT INTO `good_category` VALUES (7, 0, 1, '男鞋', '', 0, 1, 0, '2020-01-15 11:13:12', '2020-02-24 11:41:37');
INSERT INTO `good_category` VALUES (8, 7, 2, '户外装备', '', 0, 1, 0, '2020-01-15 11:13:20', '2020-01-15 11:13:20');
INSERT INTO `good_category` VALUES (9, 8, 3, '帐篷', '', 0, 1, 0, '2020-01-15 11:13:28', '2020-01-15 11:13:28');
INSERT INTO `good_category` VALUES (10, 0, 1, '测试', 'resources/20200115/2177f79b98d64d016a5584f815436a0a.png', 0, 1, 0, '2020-01-15 16:11:17', '2020-01-15 16:11:17');
INSERT INTO `good_category` VALUES (20, 0, 1, '测试9', '', 0, 1, 0, '2020-02-24 13:00:04', '2020-02-24 13:00:04');
INSERT INTO `good_category` VALUES (11, 10, 2, '测试2', '', 0, 1, 0, '2020-01-15 16:57:12', '2020-01-15 16:57:12');
INSERT INTO `good_category` VALUES (12, 10, 2, '测试3', '', 0, 1, 0, '2020-01-15 16:58:14', '2020-01-15 16:58:14');
INSERT INTO `good_category` VALUES (13, 12, 3, '测试4', '', 0, 1, 0, '2020-01-15 16:58:22', '2020-01-15 16:58:22');
INSERT INTO `good_category` VALUES (14, 0, 1, '测试', '', 0, 1, 0, '2020-02-18 17:16:26', '2020-02-18 17:16:26');
INSERT INTO `good_category` VALUES (15, 0, 1, '测试2', '', 0, 1, 0, '2020-02-18 17:16:32', '2020-02-18 17:16:32');
INSERT INTO `good_category` VALUES (16, 0, 1, '测试3', '', 0, 1, 0, '2020-02-18 17:16:38', '2020-02-18 17:16:38');
INSERT INTO `good_category` VALUES (17, 0, 1, '测试4', '', 0, 1, 0, '2020-02-18 17:16:44', '2020-02-18 17:16:44');
INSERT INTO `good_category` VALUES (18, 0, 1, '测试5', '', 0, 1, 0, '2020-02-18 17:16:51', '2020-02-18 17:16:51');
INSERT INTO `good_category` VALUES (19, 0, 1, '测试6', '', 0, 1, 0, '2020-02-18 17:16:58', '2020-02-18 17:16:58');
INSERT INTO `good_category` VALUES (21, 0, 1, '测试10', '', 0, 1, 0, '2020-02-24 13:00:11', '2020-02-24 13:00:11');
INSERT INTO `good_category` VALUES (22, 0, 1, '测试11', '', 0, 1, 0, '2020-02-24 13:00:18', '2020-02-24 13:00:18');
INSERT INTO `good_category` VALUES (23, 3, 3, '键盘', '', 0, 1, 0, '2020-02-24 20:01:00', '2020-02-24 20:01:00');
INSERT INTO `good_category` VALUES (24, 3, 3, '鼠标', '', 0, 1, 0, '2020-02-24 20:01:08', '2020-02-24 20:01:08');
INSERT INTO `good_category` VALUES (25, 3, 3, '文件', '', 0, 1, 0, '2020-02-24 20:01:14', '2020-02-24 20:01:14');
INSERT INTO `good_category` VALUES (26, 3, 3, '台式', '', 0, 1, 0, '2020-02-24 20:01:20', '2020-02-24 20:01:29');
INSERT INTO `good_category` VALUES (27, 3, 3, '鼠标垫', '', 0, 1, 0, '2020-02-24 20:01:37', '2020-02-24 20:01:37');
INSERT INTO `good_category` VALUES (28, 8, 3, '袜子', '', 0, 1, 0, '2020-02-25 00:20:05', '2020-02-25 00:20:05');

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
) ENGINE = MyISAM AUTO_INCREMENT = 9 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品分类的属性' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of good_category_attr
-- ----------------------------
INSERT INTO `good_category_attr` VALUES (1, 4, ' 机身厚度', 0, '', 0, '2020-01-16 10:51:47', '0000-00-00 00:00:00');
INSERT INTO `good_category_attr` VALUES (2, 4, '系统', 0, '', 0, '2020-01-16 10:56:06', '0000-00-00 00:00:00');
INSERT INTO `good_category_attr` VALUES (8, 4, '发发发', 1, '发货后\n妇女拿\n事实上\n吃串串\n灌灌灌灌\ng\nh', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- ----------------------------
-- Table structure for good_category_list
-- ----------------------------
DROP TABLE IF EXISTS `good_category_list`;
CREATE TABLE `good_category_list`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `good_id` int(10) UNSIGNED NOT NULL COMMENT '商品ID',
  `category_id` int(10) UNSIGNED NOT NULL COMMENT '分类ID',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_good_id_category_id`(`good_id`, `category_id`) USING BTREE,
  INDEX `idx_category_id`(`category_id`, `good_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 175 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品所属的分类列表' ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of good_category_list
-- ----------------------------
INSERT INTO `good_category_list` VALUES (1, 1, 2, '2020-02-19 00:03:09');
INSERT INTO `good_category_list` VALUES (2, 1, 3, '2020-02-19 00:03:09');
INSERT INTO `good_category_list` VALUES (3, 1, 4, '2020-02-19 00:03:09');
INSERT INTO `good_category_list` VALUES (10, 2, 7, '2020-02-19 00:18:05');
INSERT INTO `good_category_list` VALUES (11, 2, 8, '2020-02-19 00:18:05');
INSERT INTO `good_category_list` VALUES (12, 2, 9, '2020-02-19 00:18:05');
INSERT INTO `good_category_list` VALUES (111, 4, 6, '2020-02-21 16:30:47');
INSERT INTO `good_category_list` VALUES (110, 4, 5, '2020-02-21 16:30:47');
INSERT INTO `good_category_list` VALUES (109, 4, 2, '2020-02-21 16:30:47');
INSERT INTO `good_category_list` VALUES (97, 5, 2, '2020-02-21 15:21:44');
INSERT INTO `good_category_list` VALUES (98, 5, 3, '2020-02-21 15:21:44');
INSERT INTO `good_category_list` VALUES (99, 5, 4, '2020-02-21 15:21:44');
INSERT INTO `good_category_list` VALUES (162, 6, 4, '2020-02-25 09:39:35');
INSERT INTO `good_category_list` VALUES (161, 6, 3, '2020-02-25 09:39:35');
INSERT INTO `good_category_list` VALUES (160, 6, 2, '2020-02-25 09:39:35');
INSERT INTO `good_category_list` VALUES (120, 7, 4, '2020-02-21 16:49:18');
INSERT INTO `good_category_list` VALUES (119, 7, 3, '2020-02-21 16:49:18');
INSERT INTO `good_category_list` VALUES (118, 7, 2, '2020-02-21 16:49:18');
INSERT INTO `good_category_list` VALUES (121, 8, 2, '2020-02-21 16:49:54');
INSERT INTO `good_category_list` VALUES (122, 8, 3, '2020-02-21 16:49:54');
INSERT INTO `good_category_list` VALUES (123, 8, 4, '2020-02-21 16:49:54');
INSERT INTO `good_category_list` VALUES (124, 9, 2, '2020-02-21 16:51:27');
INSERT INTO `good_category_list` VALUES (125, 9, 3, '2020-02-21 16:51:27');
INSERT INTO `good_category_list` VALUES (126, 9, 4, '2020-02-21 16:51:27');
INSERT INTO `good_category_list` VALUES (127, 10, 2, '2020-02-21 16:51:52');
INSERT INTO `good_category_list` VALUES (128, 10, 3, '2020-02-21 16:51:52');
INSERT INTO `good_category_list` VALUES (129, 10, 4, '2020-02-21 16:51:52');
INSERT INTO `good_category_list` VALUES (142, 11, 2, '2020-02-24 11:05:40');
INSERT INTO `good_category_list` VALUES (143, 11, 3, '2020-02-24 11:05:40');
INSERT INTO `good_category_list` VALUES (144, 11, 4, '2020-02-24 11:05:40');
INSERT INTO `good_category_list` VALUES (172, 12, 2, '2020-02-25 17:42:39');
INSERT INTO `good_category_list` VALUES (173, 12, 3, '2020-02-25 17:42:39');
INSERT INTO `good_category_list` VALUES (174, 12, 23, '2020-02-25 17:42:39');

-- ----------------------------
-- Table structure for good_category_spec
-- ----------------------------
DROP TABLE IF EXISTS `good_category_spec`;
CREATE TABLE `good_category_spec`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL COMMENT '分类ID',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '规格名称',
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '选项值列表',
  `is_add` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否支持新增（0：否，1：是）',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品分类的规格' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of good_category_spec
-- ----------------------------
INSERT INTO `good_category_spec` VALUES (1, 4, '颜色', '黑色\n白色\n蓝色', 1, 0, '2020-01-16 11:57:48', '0000-00-00 00:00:00');
INSERT INTO `good_category_spec` VALUES (2, 4, '版本1', '嘿嘿\n哈哈\n发放', 0, 0, '2020-01-16 11:58:15', '0000-00-00 00:00:00');
INSERT INTO `good_category_spec` VALUES (3, 4, '型号', '方法\nxxx\nggg', 1, 0, '2020-02-21 03:20:54', '0000-00-00 00:00:00');
INSERT INTO `good_category_spec` VALUES (4, 23, '颜色', '黑色\n蓝色', 1, 1, '2020-02-25 17:38:40', '0000-00-00 00:00:00');
INSERT INTO `good_category_spec` VALUES (5, 23, '大小', '16G\n32G', 1, 0, '2020-02-25 17:39:01', '0000-00-00 00:00:00');

-- ----------------------------
-- Table structure for good_comment
-- ----------------------------
DROP TABLE IF EXISTS `good_comment`;
CREATE TABLE `good_comment`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `good_id` int(10) UNSIGNED NOT NULL COMMENT '商品ID',
  `member_id` int(10) UNSIGNED NOT NULL COMMENT '用户ID',
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '评价内容',
  `rate` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '评价度',
  `rate_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '评价名称',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_good_id_ctime`(`good_id`, `create_time`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品评价表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of good_comment
-- ----------------------------
INSERT INTO `good_comment` VALUES (1, 12, 1, '按时发达撒大声地发送到发撒地方', 1, '一般', '2020-02-25 20:40:36');
INSERT INTO `good_comment` VALUES (2, 12, 2, '法发顺丰大多数', 2, '满意', '2020-02-25 20:40:50');
INSERT INTO `good_comment` VALUES (3, 12, 3, '范德萨范德萨发顺丰阿斯蒂芬范德萨', 5, '很满意', '2020-02-25 20:41:42');
INSERT INTO `good_comment` VALUES (4, 12, 4, '烦烦烦烦烦发发发', 4, '好', '2020-02-27 18:44:26');
INSERT INTO `good_comment` VALUES (5, 10, 4, '份饭才VB版本', 3, '一般', '2020-02-27 18:44:26');

-- ----------------------------
-- Table structure for good_new
-- ----------------------------
DROP TABLE IF EXISTS `good_new`;
CREATE TABLE `good_new`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `good_id` int(10) UNSIGNED NOT NULL COMMENT '商品ID',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '新品权重',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_good_id`(`good_id`) USING BTREE,
  INDEX `idx_sort_update_time`(`sort`, `update_time`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '新品商品表' ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of good_new
-- ----------------------------
INSERT INTO `good_new` VALUES (1, 2, 0, '2020-02-23 23:39:34', '2020-02-23 23:39:37');
INSERT INTO `good_new` VALUES (2, 5, 0, '2020-02-23 23:39:43', '2020-02-23 23:39:45');
INSERT INTO `good_new` VALUES (3, 6, 0, '2020-02-23 23:46:10', '2020-02-23 23:46:13');
INSERT INTO `good_new` VALUES (4, 7, 0, '2020-02-23 23:46:24', '2020-02-23 23:46:27');

-- ----------------------------
-- Table structure for good_recommend
-- ----------------------------
DROP TABLE IF EXISTS `good_recommend`;
CREATE TABLE `good_recommend`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `good_id` int(10) UNSIGNED NOT NULL COMMENT '商品ID',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '推荐权重',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_good_id`(`good_id`) USING BTREE,
  INDEX `idx_sort_update_time`(`sort`, `update_time`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '推荐商品表' ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of good_recommend
-- ----------------------------
INSERT INTO `good_recommend` VALUES (1, 2, 0, '2020-02-23 23:39:34', '2020-02-23 23:39:37');
INSERT INTO `good_recommend` VALUES (2, 5, 0, '2020-02-23 23:39:43', '2020-02-23 23:39:45');
INSERT INTO `good_recommend` VALUES (3, 6, 0, '2020-02-23 23:46:10', '2020-02-23 23:46:13');
INSERT INTO `good_recommend` VALUES (4, 7, 0, '2020-02-23 23:46:24', '2020-02-23 23:46:27');

-- ----------------------------
-- Table structure for good_sku
-- ----------------------------
DROP TABLE IF EXISTS `good_sku`;
CREATE TABLE `good_sku`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `good_id` int(10) UNSIGNED NOT NULL COMMENT '商品ID',
  `price` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '单价',
  `cost_price` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '成本价',
  `stock` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '库存',
  `spec_value_list` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '规格值列表',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 26 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品的sku表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of good_sku
-- ----------------------------
INSERT INTO `good_sku` VALUES (24, 12, 144.00, 443.00, 11, '蓝色,16G', '2020-02-25 17:42:39');
INSERT INTO `good_sku` VALUES (25, 12, 122.00, 44.00, 44, '蓝色,32G', '2020-02-25 17:42:39');
INSERT INTO `good_sku` VALUES (23, 12, 12.00, 554.00, 22, '黑色,32G', '2020-02-25 17:42:39');
INSERT INTO `good_sku` VALUES (22, 12, 133.00, 22.00, 33, '黑色,16G', '2020-02-25 17:42:39');
INSERT INTO `good_sku` VALUES (21, 11, 1.00, 1.00, 1, '蓝色,发放', '2020-02-21 16:53:35');

-- ----------------------------
-- Table structure for good_spec_list
-- ----------------------------
DROP TABLE IF EXISTS `good_spec_list`;
CREATE TABLE `good_spec_list`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `good_id` int(10) UNSIGNED NOT NULL COMMENT '商品ID',
  `spec_id` int(10) UNSIGNED NOT NULL COMMENT '规格ID',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '规格名称',
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '属性',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 35 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品规格列表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of good_spec_list
-- ----------------------------
INSERT INTO `good_spec_list` VALUES (23, 5, 2, '版本1', 'xxx', '2020-02-21 15:21:44');
INSERT INTO `good_spec_list` VALUES (22, 5, 1, '颜色', 'sss,ff,黑色', '2020-02-21 15:21:44');
INSERT INTO `good_spec_list` VALUES (24, 5, 3, '型号', '', '2020-02-21 15:21:44');
INSERT INTO `good_spec_list` VALUES (34, 12, 5, '大小', '16G,32G', '2020-02-25 17:42:39');
INSERT INTO `good_spec_list` VALUES (33, 12, 4, '颜色', '黑色,蓝色', '2020-02-25 17:42:39');
INSERT INTO `good_spec_list` VALUES (32, 11, 2, '版本1', '发放', '2020-02-21 16:53:35');
INSERT INTO `good_spec_list` VALUES (31, 11, 1, '颜色', '蓝色', '2020-02-21 16:53:35');

-- ----------------------------
-- Table structure for member
-- ----------------------------
DROP TABLE IF EXISTS `member`;
CREATE TABLE `member`  (
  `member_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '昵称',
  `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '头像',
  `sex` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '性别（0：未知，1：男，2：女）',
  `mobile` char(11) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '手机号',
  `has_we_chat` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否绑定微信（0：否，1：是）',
  `has_qq` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否绑定QQ（0：否，1：是）',
  `cart_count` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '购物车数量',
  `last_login_time` datetime NULL DEFAULT NULL COMMENT '最后登录时间',
  `last_login_ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '最后登录ip',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`member_id`) USING BTREE,
  UNIQUE INDEX `uk_mobile`(`mobile`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of member
-- ----------------------------
INSERT INTO `member` VALUES (1, '嘿嘿', 'resources/20200223/70012961c2603b591924564ebdf571d1.jpg', 0, '15213230873', 0, 0, 0, NULL, NULL, '2020-02-25 20:39:47', '2020-02-25 20:39:51');
INSERT INTO `member` VALUES (2, '哈哈反反复复', 'resources/20200223/70012961c2603b591924564ebdf571d1.jpg', 0, '15213230874', 0, 0, 0, NULL, NULL, '2020-02-25 20:40:13', '2020-02-25 20:40:16');
INSERT INTO `member` VALUES (3, '嘻嘻嘻嘻嘻', 'resources/20200223/70012961c2603b591924564ebdf571d1.jpg', 0, '15213230875', 0, 0, 0, NULL, NULL, '2020-02-25 20:41:25', '2020-02-25 20:41:28');
INSERT INTO `member` VALUES (4, '1111', 'resources/20200223/70012961c2603b591924564ebdf571d1.jpg', 1, NULL, 1, 0, 8, NULL, NULL, '2020-02-26 11:27:52', '0000-00-00 00:00:00');

-- ----------------------------
-- Table structure for member_we_chat
-- ----------------------------
DROP TABLE IF EXISTS `member_we_chat`;
CREATE TABLE `member_we_chat`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `unionid` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '微信标识',
  `member_id` int(10) UNSIGNED NOT NULL COMMENT '用户ID',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_unionid`(`unionid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '微信登录' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of member_we_chat
-- ----------------------------
INSERT INTO `member_we_chat` VALUES (1, '1111', 4, '2020-02-26 11:27:52');

-- ----------------------------
-- Table structure for order
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '订单ID',
  `order_num` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '订单号',
  `member_id` int(11) NOT NULL COMMENT '用户ID',
  `count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '合计数量',
  `money` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '合计金额',
  `pay_money` decimal(10, 2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '需要支付的金额',
  `pay_time` datetime NULL DEFAULT NULL COMMENT '付款时间',
  `pay_type` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '支付类型（0：未支付，1：微信，2：支付宝）',
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '收货人姓名',
  `tel` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '手机号',
  `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '详细地址',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态：（0：待付款，1：待发货，2：待收货，3：待评价，4：已取消，5：已完成）',
  `is_comment` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否评价（0：否，1：已评价）',
  `expire_time` datetime NOT NULL COMMENT '超时时间',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_order_num`(`order_num`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '订单表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of order
-- ----------------------------
INSERT INTO `order` VALUES (1, '113570322074004', 4, 1, 10000.00, 0.00, NULL, 0, '朗那些', '15213230873', '少时诵诗书所', '111222', 5, 0, '0000-00-00 00:00:00', '2020-02-26 13:57:03', '2020-02-26 13:57:03');
INSERT INTO `order` VALUES (2, '114431459035004', 4, 1, 10000.00, 0.00, NULL, 0, '兰茗翔', '15213230873', '少时诵诗书所', '', 4, 0, '2020-02-27 05:43:14', '2020-02-26 14:43:14', '2020-02-26 14:43:14');
INSERT INTO `order` VALUES (3, '116133088543004', 4, 1, 10000.00, 10000.00, NULL, 0, '兰茗翔', '15213230873', '少时诵诗书所', '', 3, 0, '2020-02-27 07:13:30', '2020-02-26 16:13:30', '2020-02-26 16:13:30');

-- ----------------------------
-- Table structure for order_address
-- ----------------------------
DROP TABLE IF EXISTS `order_address`;
CREATE TABLE `order_address`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_id` int(10) UNSIGNED NOT NULL COMMENT '用户ID',
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '收货人姓名',
  `tel` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '手机号',
  `province` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '省',
  `city` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '市',
  `area` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '区',
  `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '详细地址',
  `is_default` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否默认（0：否，1：是）',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_memeber_id_ctime`(`member_id`, `create_time`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '地址信息' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of order_address
-- ----------------------------
INSERT INTO `order_address` VALUES (1, 4, '兰茗翔', '15213230873', '重庆市', '重庆市', '渝北区', '少时诵诗书所', 1, '2020-02-26 11:47:27', '2020-02-26 11:47:29');
INSERT INTO `order_address` VALUES (3, 4, '反反复复', '15213230876', '22', '33', '44', '111111111', 1, '2020-02-26 12:37:41', '2020-02-26 12:37:41');

-- ----------------------------
-- Table structure for order_cart
-- ----------------------------
DROP TABLE IF EXISTS `order_cart`;
CREATE TABLE `order_cart`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_id` int(10) UNSIGNED NOT NULL COMMENT '用户ID',
  `good_id` int(10) UNSIGNED NOT NULL COMMENT '商品ID',
  `count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '购物车数量',
  `is_check` tinyint(3) UNSIGNED NOT NULL COMMENT '是否选中',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_mid_gid`(`member_id`, `good_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '购物车' ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of order_cart
-- ----------------------------
INSERT INTO `order_cart` VALUES (1, 4, 12, 8, 1, '2020-02-27 16:04:04', '2020-02-27 16:10:11');

-- ----------------------------
-- Table structure for order_good
-- ----------------------------
DROP TABLE IF EXISTS `order_good`;
CREATE TABLE `order_good`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_num` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '订单ID',
  `good_id` int(11) NOT NULL COMMENT '商品ID',
  `count` int(11) NOT NULL COMMENT '数量',
  `price` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '单价',
  `money` decimal(10, 2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '总价',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_onum_gid`(`order_num`, `good_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '订单商品表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of order_good
-- ----------------------------
INSERT INTO `order_good` VALUES (1, '113570322074004', 12, 1, 10000.00, 0.00, '2020-02-26 13:57:03');
INSERT INTO `order_good` VALUES (2, '114431459035004', 11, 1, 10000.00, 0.00, '2020-02-26 14:43:14');
INSERT INTO `order_good` VALUES (3, '116133088543004', 12, 1, 10000.00, 0.00, '2020-02-26 16:13:30');
INSERT INTO `order_good` VALUES (4, '113570322074004', 10, 5, 2000.00, 0.00, '2020-02-26 20:36:51');

SET FOREIGN_KEY_CHECKS = 1;
