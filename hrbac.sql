/*
 Navicat Premium Data Transfer

 Source Server         : laradock
 Source Server Type    : MySQL
 Source Server Version : 50725
 Source Host           : 127.0.0.1:3306
 Source Schema         : hrbac

 Target Server Type    : MySQL
 Target Server Version : 50725
 File Encoding         : 65001

 Date: 18/09/2019 15:52:01
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for files
-- ----------------------------
DROP TABLE IF EXISTS `files`;
CREATE TABLE `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `original_name` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `filename` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `ext` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `size` double DEFAULT NULL,
  `path` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of permissions
-- ----------------------------
BEGIN;
INSERT INTO `permissions` VALUES (27, 0, 'menu system', '系统管理', '2019-07-18 06:20:01', '2019-09-17 17:06:50');
INSERT INTO `permissions` VALUES (28, 27, 'menu users', '用户管理', '2019-07-18 06:20:01', NULL);
INSERT INTO `permissions` VALUES (29, 28, 'view users', '浏览用户', '2019-07-18 06:20:01', NULL);
INSERT INTO `permissions` VALUES (30, 28, 'delete users', '删除用户', '2019-07-18 06:20:01', NULL);
INSERT INTO `permissions` VALUES (31, 28, 'edit users', '编辑用户', '2019-07-18 06:20:01', NULL);
INSERT INTO `permissions` VALUES (32, 28, 'add users', '增加用户', '2019-07-18 06:20:01', '2019-09-17 14:15:54');
INSERT INTO `permissions` VALUES (33, 27, 'menu roles', '角色管理', '2019-07-18 06:20:01', NULL);
INSERT INTO `permissions` VALUES (34, 33, 'view roles', '浏览角色', '2019-07-18 06:20:01', NULL);
INSERT INTO `permissions` VALUES (35, 33, 'delete roles', '删除角色', '2019-07-18 06:20:01', NULL);
INSERT INTO `permissions` VALUES (36, 33, 'edit roles', '编辑角色', '2019-07-18 06:20:01', NULL);
INSERT INTO `permissions` VALUES (37, 33, 'add roles', '增加角色', '2019-07-18 06:20:01', NULL);
INSERT INTO `permissions` VALUES (64, 27, 'manage permissions', '权限管理', '2019-09-17 17:06:19', '2019-09-17 17:06:19');
INSERT INTO `permissions` VALUES (65, 27, 'system config', '系统配置', '2019-09-17 17:07:33', '2019-09-17 17:07:33');
COMMIT;

-- ----------------------------
-- Table structure for role_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE `role_has_permissions` (
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_ibfk_1` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of role_has_permissions
-- ----------------------------
BEGIN;
INSERT INTO `role_has_permissions` VALUES (27, 9);
INSERT INTO `role_has_permissions` VALUES (28, 9);
INSERT INTO `role_has_permissions` VALUES (29, 9);
INSERT INTO `role_has_permissions` VALUES (30, 9);
INSERT INTO `role_has_permissions` VALUES (31, 9);
INSERT INTO `role_has_permissions` VALUES (32, 9);
INSERT INTO `role_has_permissions` VALUES (33, 9);
INSERT INTO `role_has_permissions` VALUES (34, 9);
INSERT INTO `role_has_permissions` VALUES (35, 9);
INSERT INTO `role_has_permissions` VALUES (36, 9);
INSERT INTO `role_has_permissions` VALUES (37, 9);
INSERT INTO `role_has_permissions` VALUES (64, 9);
INSERT INTO `role_has_permissions` VALUES (65, 9);
INSERT INTO `role_has_permissions` VALUES (27, 12);
INSERT INTO `role_has_permissions` VALUES (28, 12);
INSERT INTO `role_has_permissions` VALUES (29, 12);
INSERT INTO `role_has_permissions` VALUES (30, 12);
INSERT INTO `role_has_permissions` VALUES (31, 12);
INSERT INTO `role_has_permissions` VALUES (32, 12);
COMMIT;

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of roles
-- ----------------------------
BEGIN;
INSERT INTO `roles` VALUES (9, '超级管理员', 'super_admin', NULL, NULL);
INSERT INTO `roles` VALUES (12, '管理员', 'admin', NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` text COLLATE utf8mb4_unicode_ci,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '密码',
  `nickname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '昵称',
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin' COMMENT '角色',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '状态',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
BEGIN;
INSERT INTO `users` VALUES (1, 'admin', '<?xml version=\"1.0\" encoding=\"utf-8\"?><svg width=\"40\" height=\"40\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\"><rect x=\"0\" y=\"0\" width=\"40\" height=\"40\" style=\"fill: #f0e9e9\" /><text x=\"50%\" y=\"50%\" dy=\".1em\" fill=\"#8b5d5d\" text-anchor=\"middle\" dominant-baseline=\"middle\" style=\"font-family: OpenSans, sans-serif; font-size: 48; line-height: 1\">AD</text></svg>', '$2y$10$kSmuzZoj23uA2jrlp6vJZOTQ939XZRUpR/KVEPT4W1iYXlni1Y946', '管理员', 'super_admin', 1, '2019-08-27 18:15:48', '2019-09-18 15:50:38');
INSERT INTO `users` VALUES (8, 'manage', '<?xml version=\"1.0\" encoding=\"utf-8\"?><svg width=\"40\" height=\"40\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\"><rect x=\"0\" y=\"0\" width=\"40\" height=\"40\" style=\"fill: #f0e9e9\" /><text x=\"50%\" y=\"50%\" dy=\".1em\" fill=\"#8b5d5d\" text-anchor=\"middle\" dominant-baseline=\"middle\" style=\"font-family: OpenSans, sans-serif; font-size: 20; line-height: 1\">MA</text></svg>', '$2y$10$cSyQtkCIkn0Kt3QiEAsq9.W/oYFZYOU54naaBgaPsKnMYimzfn2m2', '斯大林', 'admin', 1, '2019-09-18 14:38:26', '2019-09-18 14:38:26');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
