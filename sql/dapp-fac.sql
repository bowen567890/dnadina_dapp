/*
 Navicat Premium Dump SQL

 Source Server         : dapp-fac
 Source Server Type    : MySQL
 Source Server Version : 80035 (8.0.35)
 Source Host           : 118.107.19.34:33069
 Source Schema         : dapp-fac

 Target Server Type    : MySQL
 Target Server Version : 80035 (8.0.35)
 File Encoding         : 65001

 Date: 25/07/2025 23:42:14
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_extension_histories
-- ----------------------------
DROP TABLE IF EXISTS `admin_extension_histories`;
CREATE TABLE `admin_extension_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `type` tinyint NOT NULL DEFAULT '1',
  `version` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '0',
  `detail` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `admin_extension_histories_name_index` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of admin_extension_histories
-- ----------------------------
BEGIN;
INSERT INTO `admin_extension_histories` (`id`, `name`, `type`, `version`, `detail`, `created_at`, `updated_at`) VALUES (2, 'ycookies.morepanel', 2, '1.0.0', '2024_08_25_220101_create_morepanel_list_table.php', '2025-02-09 08:13:48', '2025-02-09 08:13:48');
INSERT INTO `admin_extension_histories` (`id`, `name`, `type`, `version`, `detail`, `created_at`, `updated_at`) VALUES (7, 'zwping.operation-log', 2, '1.0.0', 'create_opration_log_table.php', '2025-02-10 01:51:46', '2025-02-10 01:51:46');
INSERT INTO `admin_extension_histories` (`id`, `name`, `type`, `version`, `detail`, `created_at`, `updated_at`) VALUES (8, 'zwping.operation-log', 1, '1.0.0', 'Initialize extension.', '2025-02-10 01:51:46', '2025-02-10 01:51:46');
INSERT INTO `admin_extension_histories` (`id`, `name`, `type`, `version`, `detail`, `created_at`, `updated_at`) VALUES (9, 'zwping.operation-log', 2, '1.0.1', 'update_opration_log_table_2023-12-11.php', '2025-02-10 01:51:46', '2025-02-10 01:51:46');
INSERT INTO `admin_extension_histories` (`id`, `name`, `type`, `version`, `detail`, `created_at`, `updated_at`) VALUES (10, 'zwping.operation-log', 1, '1.0.1', 'add SoftDeletes', '2025-02-10 01:51:46', '2025-02-10 01:51:46');
INSERT INTO `admin_extension_histories` (`id`, `name`, `type`, `version`, `detail`, `created_at`, `updated_at`) VALUES (11, 'asundust.dcat-auth-google-2fa', 2, '1.0.0', '2021_08_21_120702_add_google_two_fa_secret_to_admin_users_table.php', '2025-02-10 01:54:55', '2025-02-10 01:54:55');
INSERT INTO `admin_extension_histories` (`id`, `name`, `type`, `version`, `detail`, `created_at`, `updated_at`) VALUES (12, 'asundust.dcat-auth-google-2fa', 1, '1.0.0', 'Dcat-Admin 登录 Google 2FA两步验证第一版', '2025-02-10 01:54:55', '2025-02-10 01:54:55');
COMMIT;

-- ----------------------------
-- Table structure for admin_extensions
-- ----------------------------
DROP TABLE IF EXISTS `admin_extensions`;
CREATE TABLE `admin_extensions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `version` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `is_enabled` tinyint NOT NULL DEFAULT '0',
  `options` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `admin_extensions_name_unique` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of admin_extensions
-- ----------------------------
BEGIN;
INSERT INTO `admin_extensions` (`id`, `name`, `version`, `is_enabled`, `options`, `created_at`, `updated_at`) VALUES (3, 'zwping.operation-log', '1.0.1', 1, NULL, '2025-02-10 01:51:46', '2025-02-10 01:51:53');
INSERT INTO `admin_extensions` (`id`, `name`, `version`, `is_enabled`, `options`, `created_at`, `updated_at`) VALUES (4, 'asundust.dcat-auth-google-2fa', '1.0.0', 1, NULL, '2025-02-10 01:54:55', '2025-02-10 01:54:58');
COMMIT;

-- ----------------------------
-- Table structure for admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `admin_menu`;
CREATE TABLE `admin_menu` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint NOT NULL DEFAULT '0',
  `order` int NOT NULL DEFAULT '0',
  `title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `icon` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `uri` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `extension` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `show` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of admin_menu
-- ----------------------------
BEGIN;
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (1, 0, 1, '主页', 'feather icon-bar-chart-2', '/', '', 1, '2025-02-09 06:27:58', '2025-02-13 00:16:24');
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (2, 0, 37, 'Admin', 'feather icon-settings', '', '', 1, '2025-02-09 06:27:58', '2025-07-11 11:02:35');
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (3, 2, 38, '用户管理', NULL, 'auth/users', '', 1, '2025-02-09 06:27:58', '2025-07-11 11:02:35');
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (4, 2, 39, '角色管理', NULL, 'auth/roles', '', 1, '2025-02-09 06:27:58', '2025-07-11 11:02:35');
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (5, 2, 40, '权限管理', NULL, 'auth/permissions', '', 1, '2025-02-09 06:27:58', '2025-07-11 11:02:35');
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (6, 2, 41, '菜单管理', NULL, 'auth/menu', '', 1, '2025-02-09 06:27:58', '2025-07-11 11:02:35');
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (14, 2, 43, '多应用后台', 'feather icon-layout', 'morepanel/list', 'ycookies.morepanel', 0, '2025-02-09 08:13:48', '2025-07-11 11:02:35');
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (51, 2, 42, '日志管理', 'fa-barcode', 'auth/operation-logs', 'zwping.operation-log', 1, '2025-02-10 01:51:46', '2025-07-11 11:02:35');
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (52, 2, 44, '队列监控', NULL, 'https://backend-frontend.naaidepin.com/mm3pU5xw8sIZEw1erc5NTSnINaJixk/horizon', '', 1, '2025-02-10 22:54:15', '2025-07-11 11:02:35');
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (53, 0, 27, '应用', 'fa-android', NULL, '', 1, '2025-02-13 00:15:54', '2025-07-11 11:02:34');
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (54, 0, 2, '用户', 'fa-address-book', '', '', 1, '2025-02-13 00:16:38', '2025-02-13 00:17:06');
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (55, 53, 28, '配置', NULL, 'configs/1/edit', '', 1, '2025-02-13 00:17:40', '2025-07-11 11:02:34');
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (57, 53, 29, '语言', NULL, 'language', '', 1, '2025-02-13 00:23:43', '2025-07-11 11:02:34');
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (60, 54, 7, '用户等级', NULL, 'level_config', '', 1, '2025-02-13 10:32:21', '2025-07-11 11:02:34');
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (61, 0, 32, '内容', 'fa-bug', NULL, '', 1, '2025-02-14 01:58:20', '2025-07-11 11:02:34');
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (62, 61, 33, '资讯', NULL, 'cms-news', '', 1, '2025-02-14 02:04:08', '2025-07-11 11:02:34');
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (63, 61, 35, '公告', NULL, 'cms-notice', '', 1, '2025-02-14 05:51:59', '2025-07-11 11:02:34');
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (65, 54, 3, '用户列表', NULL, 'users-list', '', 1, '2025-02-14 20:18:05', '2025-02-19 06:59:11');
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (75, 0, 22, '订单', 'fa-cart-plus', NULL, '', 1, '2025-02-16 08:57:06', '2025-07-11 11:02:34');
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (76, 75, 23, '充值订单', NULL, 'recharge', '', 1, '2025-02-16 08:58:52', '2025-07-11 11:02:34');
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (77, 75, 24, '提现订单', NULL, 'withdraw', '', 1, '2025-02-16 08:59:02', '2025-07-11 11:02:34');
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (84, 54, 4, '推荐树', NULL, 'users-tree', '', 1, '2025-02-21 12:14:48', '2025-05-23 22:05:44');
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (85, 61, 36, '翻译', NULL, 'cms-languages', '', 1, '2025-02-21 12:34:48', '2025-07-11 11:02:34');
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (95, 61, 34, '轮播图', NULL, 'banner', '', 1, '2025-04-11 23:41:15', '2025-07-11 11:02:34');
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (96, 54, 6, '用户算力', NULL, 'user_power', '', 1, '2025-04-17 17:04:48', '2025-05-15 18:21:27');
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (103, 0, 16, '节点', 'fa-android', NULL, '', 1, '2025-05-15 21:21:48', '2025-07-11 11:02:34');
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (104, 103, 17, '配置', NULL, 'node-config', '', 1, '2025-05-15 21:22:22', '2025-07-11 11:02:34');
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (105, 103, 18, '节点记录', NULL, 'node-list', '', 1, '2025-05-15 21:33:13', '2025-07-11 11:02:34');
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (109, 53, 30, '币种', NULL, 'currency', '', 1, '2025-05-25 17:13:52', '2025-07-11 11:02:34');
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (110, 53, 31, '币种兑换', NULL, 'currency_exchange', '', 1, '2025-05-25 17:14:13', '2025-07-11 11:02:34');
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `extension`, `show`, `created_at`, `updated_at`) VALUES (112, 54, 8, '用户限制', NULL, 'users_limit', '', 1, '2025-06-12 02:22:01', '2025-07-11 11:02:34');
COMMIT;

-- ----------------------------
-- Table structure for admin_operation_log
-- ----------------------------
DROP TABLE IF EXISTS `admin_operation_log`;
CREATE TABLE `admin_operation_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `path` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `method` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `ip` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `input` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `admin_operation_log_user_id_index` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=566 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of admin_operation_log
-- ----------------------------
BEGIN;
INSERT INTO `admin_operation_log` (`id`, `user_id`, `path`, `method`, `ip`, `input`, `created_at`, `updated_at`, `deleted_at`) VALUES (557, 1, 'OZGpzjud1MPHQDeTUKoY/users-list', 'GET', '104.245.43.162', '[]', '2025-07-25 22:45:55', '2025-07-25 22:45:55', NULL);
INSERT INTO `admin_operation_log` (`id`, `user_id`, `path`, `method`, `ip`, `input`, `created_at`, `updated_at`, `deleted_at`) VALUES (558, 1, 'OZGpzjud1MPHQDeTUKoY/dcat-api/render', 'GET', '104.245.43.162', '{\"_current_\":\"https:\\/\\/backend-frontend.facpower.com\\/OZGpzjud1MPHQDeTUKoY\\/users-list?\",\"rid\":\"13\",\"renderable\":\"App_Admin_Forms_User_UserFinanceForm\",\"_trans_\":\"user\"}', '2025-07-25 22:45:58', '2025-07-25 22:45:58', NULL);
INSERT INTO `admin_operation_log` (`id`, `user_id`, `path`, `method`, `ip`, `input`, `created_at`, `updated_at`, `deleted_at`) VALUES (559, 1, 'OZGpzjud1MPHQDeTUKoY/dcat-api/form', 'POST', '104.245.43.162', '{\"amount_type\":\"1\",\"operator_type\":\"1\",\"operator_num\":\"10000\",\"operator_remark\":\"后台操作\",\"_form_\":\"App\\\\Admin\\\\Forms\\\\User\\\\UserFinanceForm\",\"_current_\":\"https:\\/\\/backend-frontend.facpower.com\\/OZGpzjud1MPHQDeTUKoY\\/users-list?\",\"_payload_\":\"{\\\"_current_\\\":\\\"https:\\\\\\/\\\\\\/backend-frontend.facpower.com\\\\\\/OZGpzjud1MPHQDeTUKoY\\\\\\/users-list?\\\",\\\"rid\\\":\\\"13\\\",\\\"renderable\\\":\\\"App_Admin_Forms_User_UserFinanceForm\\\",\\\"_trans_\\\":\\\"user\\\"}\",\"_token\":\"xAOjRA59RCvRAOrj34ujlirkbKE838vwJMF5BS8z\"}', '2025-07-25 22:46:03', '2025-07-25 22:46:03', NULL);
INSERT INTO `admin_operation_log` (`id`, `user_id`, `path`, `method`, `ip`, `input`, `created_at`, `updated_at`, `deleted_at`) VALUES (560, 1, 'OZGpzjud1MPHQDeTUKoY/users-list', 'GET', '104.245.43.162', '{\"_pjax\":\"#pjax-container\"}', '2025-07-25 22:46:03', '2025-07-25 22:46:03', NULL);
INSERT INTO `admin_operation_log` (`id`, `user_id`, `path`, `method`, `ip`, `input`, `created_at`, `updated_at`, `deleted_at`) VALUES (561, 1, 'OZGpzjud1MPHQDeTUKoY/users-list', 'GET', '104.245.43.162', '[]', '2025-07-25 22:52:07', '2025-07-25 22:52:07', NULL);
INSERT INTO `admin_operation_log` (`id`, `user_id`, `path`, `method`, `ip`, `input`, `created_at`, `updated_at`, `deleted_at`) VALUES (562, 1, 'OZGpzjud1MPHQDeTUKoY/dcat-api/render', 'GET', '104.245.43.162', '{\"_current_\":\"https:\\/\\/backend-frontend.facpower.com\\/OZGpzjud1MPHQDeTUKoY\\/users-list?\",\"rid\":\"14\",\"renderable\":\"App_Admin_Forms_User_UserFinanceForm\",\"_trans_\":\"user\"}', '2025-07-25 22:52:59', '2025-07-25 22:52:59', NULL);
INSERT INTO `admin_operation_log` (`id`, `user_id`, `path`, `method`, `ip`, `input`, `created_at`, `updated_at`, `deleted_at`) VALUES (563, 1, 'OZGpzjud1MPHQDeTUKoY/dcat-api/form', 'POST', '104.245.43.162', '{\"amount_type\":\"1\",\"operator_type\":\"1\",\"operator_num\":\"100000\",\"operator_remark\":\"后台操作\",\"_form_\":\"App\\\\Admin\\\\Forms\\\\User\\\\UserFinanceForm\",\"_current_\":\"https:\\/\\/backend-frontend.facpower.com\\/OZGpzjud1MPHQDeTUKoY\\/users-list?\",\"_payload_\":\"{\\\"_current_\\\":\\\"https:\\\\\\/\\\\\\/backend-frontend.facpower.com\\\\\\/OZGpzjud1MPHQDeTUKoY\\\\\\/users-list?\\\",\\\"rid\\\":\\\"14\\\",\\\"renderable\\\":\\\"App_Admin_Forms_User_UserFinanceForm\\\",\\\"_trans_\\\":\\\"user\\\"}\",\"_token\":\"xAOjRA59RCvRAOrj34ujlirkbKE838vwJMF5BS8z\"}', '2025-07-25 22:53:04', '2025-07-25 22:53:04', NULL);
INSERT INTO `admin_operation_log` (`id`, `user_id`, `path`, `method`, `ip`, `input`, `created_at`, `updated_at`, `deleted_at`) VALUES (564, 1, 'OZGpzjud1MPHQDeTUKoY/users-list', 'GET', '104.245.43.162', '{\"_pjax\":\"#pjax-container\"}', '2025-07-25 22:53:04', '2025-07-25 22:53:04', NULL);
INSERT INTO `admin_operation_log` (`id`, `user_id`, `path`, `method`, `ip`, `input`, `created_at`, `updated_at`, `deleted_at`) VALUES (565, 1, 'OZGpzjud1MPHQDeTUKoY/dcat-api/render', 'GET', '104.245.43.162', '{\"user_id\":\"13\",\"_simple_\":\"1\",\"renderable\":\"App_Admin_Renderable_IncomeLogTable\",\"_trans_\":\"user\"}', '2025-07-25 22:53:13', '2025-07-25 22:53:13', NULL);
COMMIT;

-- ----------------------------
-- Table structure for admin_permission_menu
-- ----------------------------
DROP TABLE IF EXISTS `admin_permission_menu`;
CREATE TABLE `admin_permission_menu` (
  `permission_id` bigint NOT NULL,
  `menu_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `admin_permission_menu_permission_id_menu_id_unique` (`permission_id`,`menu_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of admin_permission_menu
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for admin_permissions
-- ----------------------------
DROP TABLE IF EXISTS `admin_permissions`;
CREATE TABLE `admin_permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `slug` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `http_method` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `http_path` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `order` int NOT NULL DEFAULT '0',
  `parent_id` bigint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `admin_permissions_slug_unique` (`slug`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of admin_permissions
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for admin_role_menu
-- ----------------------------
DROP TABLE IF EXISTS `admin_role_menu`;
CREATE TABLE `admin_role_menu` (
  `role_id` bigint NOT NULL,
  `menu_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `admin_role_menu_role_id_menu_id_unique` (`role_id`,`menu_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of admin_role_menu
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for admin_role_permissions
-- ----------------------------
DROP TABLE IF EXISTS `admin_role_permissions`;
CREATE TABLE `admin_role_permissions` (
  `role_id` bigint NOT NULL,
  `permission_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `admin_role_permissions_role_id_permission_id_unique` (`role_id`,`permission_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of admin_role_permissions
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for admin_role_users
-- ----------------------------
DROP TABLE IF EXISTS `admin_role_users`;
CREATE TABLE `admin_role_users` (
  `role_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `admin_role_users_role_id_user_id_unique` (`role_id`,`user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of admin_role_users
-- ----------------------------
BEGIN;
INSERT INTO `admin_role_users` (`role_id`, `user_id`, `created_at`, `updated_at`) VALUES (1, 1, '2025-02-09 14:27:59', '2025-02-09 14:27:59');
COMMIT;

-- ----------------------------
-- Table structure for admin_roles
-- ----------------------------
DROP TABLE IF EXISTS `admin_roles`;
CREATE TABLE `admin_roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `slug` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `admin_roles_slug_unique` (`slug`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of admin_roles
-- ----------------------------
BEGIN;
INSERT INTO `admin_roles` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES (1, 'Administrator', 'administrator', '2025-02-09 14:27:58', '2025-02-09 14:27:59');
COMMIT;

-- ----------------------------
-- Table structure for admin_settings
-- ----------------------------
DROP TABLE IF EXISTS `admin_settings`;
CREATE TABLE `admin_settings` (
  `slug` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `value` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`slug`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of admin_settings
-- ----------------------------
BEGIN;
INSERT INTO `admin_settings` (`slug`, `value`, `created_at`, `updated_at`) VALUES ('zwping:operation-log', '{\"allowed_methods\":[],\"except\":\"auth\\/operation-logs\",\"secret_fields\":null}', '2025-02-10 03:09:27', '2025-02-10 03:09:27');
COMMIT;

-- ----------------------------
-- Table structure for admin_users
-- ----------------------------
DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE `admin_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint NOT NULL DEFAULT '0',
  `username` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `password` varchar(80) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `email` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `wx_openid` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `google_two_fa_secret` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `google_two_fa_enable` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `admin_users_username_unique` (`username`) USING BTREE,
  KEY `admin_users_google_two_fa_enable_index` (`google_two_fa_enable`) USING BTREE,
  KEY `admin_users_status_index` (`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of admin_users
-- ----------------------------
BEGIN;
INSERT INTO `admin_users` (`id`, `parent_id`, `username`, `password`, `name`, `avatar`, `order`, `email`, `wx_openid`, `remember_token`, `google_two_fa_secret`, `google_two_fa_enable`, `status`, `created_at`, `updated_at`) VALUES (1, 0, 'dapp-fac', '$2y$10$cqN0LVjdCQyJNFFV8eTCYOkeEMdso2YTX0aJRH57qHmH.DgCeKKbO', 'Administrator', NULL, 0, NULL, NULL, '3uxZmTFHnw7HQqwJXs4QKkVLOP1sRayxd4f14jHM4Jnb5uLoMnzEvDPOwVVk', '', 0, 1, '2025-02-09 06:27:58', '2025-06-03 18:19:12');
COMMIT;

-- ----------------------------
-- Table structure for banner
-- ----------------------------
DROP TABLE IF EXISTS `banner`;
CREATE TABLE `banner` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `banner_type` tinyint(1) NOT NULL COMMENT '1-图片 2-视频',
  `banner` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL COMMENT 'Banner',
  `status` tinyint(1) NOT NULL COMMENT '状态 1-有效 0-无效',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

-- ----------------------------
-- Records of banner
-- ----------------------------
BEGIN;
INSERT INTO `banner` (`id`, `banner_type`, `banner`, `status`, `created_at`, `updated_at`) VALUES (1, 1, 'images/8f24b2ce8e7ad3fe13e98c1b90d323a0.jpg', 1, '2025-04-12 00:04:24', '2025-07-24 00:47:40');
COMMIT;

-- ----------------------------
-- Table structure for currency
-- ----------------------------
DROP TABLE IF EXISTS `currency`;
CREATE TABLE `currency` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '币种名称',
  `contract_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '币种合约地址',
  `decimals` int NOT NULL COMMENT '精度',
  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '币种图标',
  `st` int unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `chain_id` int unsigned NOT NULL DEFAULT '0' COMMENT '链ID',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of currency
-- ----------------------------
BEGIN;
INSERT INTO `currency` (`id`, `name`, `contract_address`, `decimals`, `img`, `st`, `chain_id`) VALUES (1, 'USDT', '0x55d398326f99059fF775485246999027B3197955', 18, '/storage/images/2025/05/07/26b02ca8-64bf-44f8-81a8-d706fb214b0c.png', 0, 56);
INSERT INTO `currency` (`id`, `name`, `contract_address`, `decimals`, `img`, `st`, `chain_id`) VALUES (2, 'FAC', '0x1000000000000000000000000000000000000000', 18, '/storage/images/2025/05/07/95ffaf0b-3f98-4b81-bc71-30f9231ca308.png', 0, 56);
COMMIT;

-- ----------------------------
-- Table structure for currency_exchange
-- ----------------------------
DROP TABLE IF EXISTS `currency_exchange`;
CREATE TABLE `currency_exchange` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `currency_id` int NOT NULL COMMENT '币种',
  `other_id` int NOT NULL COMMENT '其他币种',
  `currency_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '币种名称',
  `other_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '其他币种名称',
  `price` decimal(30,18) NOT NULL COMMENT '价格',
  `back_price` decimal(30,18) NOT NULL COMMENT '反向价格',
  `is_search` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '是否自动更新价格 1-是 2-否',
  `last_time` datetime NOT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of currency_exchange
-- ----------------------------
BEGIN;
INSERT INTO `currency_exchange` (`id`, `currency_id`, `other_id`, `currency_name`, `other_name`, `price`, `back_price`, `is_search`, `last_time`) VALUES (1, 1, 2, 'USDT', 'FAC', 2.000000000000000000, 0.500000000000000000, 0, '2025-04-15 20:57:04');
COMMIT;

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for language_config
-- ----------------------------
DROP TABLE IF EXISTS `language_config`;
CREATE TABLE `language_config` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `slug` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `group` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `content` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `type` enum('default','client','serve') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'default',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `language_config_slug_unique` (`slug`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of language_config
-- ----------------------------
BEGIN;
INSERT INTO `language_config` (`id`, `name`, `slug`, `group`, `content`, `created_at`, `updated_at`, `type`) VALUES (1, '不允许多次购买', '不允许多次购买', '自动生成', '{\"CN\": \"不允许多次购买\", \"EN\": \"不允许多次购买\", \"ID\": \"不允许多次购买\", \"JP\": \"不允许多次购买\", \"KO\": \"不允许多次购买\", \"MS\": \"不允许多次购买\", \"PT\": \"不允许多次购买\", \"TH\": \"不允许多次购买\", \"TW\": \"不允许多次购买\"}', '2025-07-24 18:45:05', '2025-07-24 18:45:05', 'serve');
INSERT INTO `language_config` (`id`, `name`, `slug`, `group`, `content`, `created_at`, `updated_at`, `type`) VALUES (2, '请输入提现金额', '请输入提现金额', '自动生成', '{\"CN\": \"请输入提现金额\", \"EN\": \"请输入提现金额\", \"ID\": \"请输入提现金额\", \"JP\": \"请输入提现金额\", \"KO\": \"请输入提现金额\", \"MS\": \"请输入提现金额\", \"PT\": \"请输入提现金额\", \"TH\": \"请输入提现金额\", \"TW\": \"请输入提现金额\"}', '2025-07-25 02:09:49', '2025-07-25 02:09:49', 'serve');
INSERT INTO `language_config` (`id`, `name`, `slug`, `group`, `content`, `created_at`, `updated_at`, `type`) VALUES (3, '请输入正确的提现金额', '请输入正确的提现金额', '自动生成', '{\"CN\": \"请输入正确的提现金额\", \"EN\": \"请输入正确的提现金额\", \"ID\": \"请输入正确的提现金额\", \"JP\": \"请输入正确的提现金额\", \"KO\": \"请输入正确的提现金额\", \"MS\": \"请输入正确的提现金额\", \"PT\": \"请输入正确的提现金额\", \"TH\": \"请输入正确的提现金额\", \"TW\": \"请输入正确的提现金额\"}', '2025-07-25 02:09:49', '2025-07-25 02:09:49', 'serve');
INSERT INTO `language_config` (`id`, `name`, `slug`, `group`, `content`, `created_at`, `updated_at`, `type`) VALUES (4, '单笔最低提现金额为:', '单笔最低提现金额为:', '自动生成', '{\"CN\": \"单笔最低提现金额为:---{0}\", \"EN\": \"单笔最低提现金额为:---{0}\", \"ID\": \"单笔最低提现金额为:---{0}\", \"JP\": \"单笔最低提现金额为:---{0}\", \"KO\": \"单笔最低提现金额为:---{0}\", \"MS\": \"单笔最低提现金额为:---{0}\", \"PT\": \"单笔最低提现金额为:---{0}\", \"TH\": \"单笔最低提现金额为:---{0}\", \"TW\": \"单笔最低提现金额为:---{0}\"}', '2025-07-25 02:23:47', '2025-07-25 02:23:47', 'serve');
INSERT INTO `language_config` (`id`, `name`, `slug`, `group`, `content`, `created_at`, `updated_at`, `type`) VALUES (5, '未找到邀请码', '未找到邀请码', '自动生成', '{\"CN\": \"未找到邀请码\", \"EN\": \"未找到邀请码\", \"ID\": \"未找到邀请码\", \"JP\": \"未找到邀请码\", \"KO\": \"未找到邀请码\", \"MS\": \"未找到邀请码\", \"PT\": \"未找到邀请码\", \"TH\": \"未找到邀请码\", \"TW\": \"未找到邀请码\"}', '2025-07-25 09:34:15', '2025-07-25 09:34:15', 'serve');
INSERT INTO `language_config` (`id`, `name`, `slug`, `group`, `content`, `created_at`, `updated_at`, `type`) VALUES (6, '余额不足', '余额不足', '自动生成', '{\"CN\": \"余额不足\", \"EN\": \"余额不足\", \"ID\": \"余额不足\", \"JP\": \"余额不足\", \"KO\": \"余额不足\", \"MS\": \"余额不足\", \"PT\": \"余额不足\", \"TH\": \"余额不足\", \"TW\": \"余额不足\"}', '2025-07-25 17:10:41', '2025-07-25 17:10:41', 'serve');
COMMIT;

-- ----------------------------
-- Table structure for languages
-- ----------------------------
DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `value` char(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `slug` char(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `order` smallint NOT NULL DEFAULT '0' COMMENT '排序',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '启用-前台',
  `show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '显示-后台',
  `required` tinyint(1) DEFAULT '0' COMMENT '是否必填',
  `icon` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL COMMENT '图标',
  `color` char(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `languages_value_unique` (`value`) USING BTREE,
  UNIQUE KEY `slug` (`slug`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of languages
-- ----------------------------
BEGIN;
INSERT INTO `languages` (`id`, `name`, `value`, `slug`, `order`, `created_at`, `updated_at`, `status`, `show`, `required`, `icon`, `color`) VALUES (2, 'English', 'en_US', 'EN', 1, '2021-02-01 10:45:04', '2025-03-03 10:30:45', 1, 1, 1, 'images/6b6cf29104aad3a91c825ac4b41a402e.png', '#60AA78');
INSERT INTO `languages` (`id`, `name`, `value`, `slug`, `order`, `created_at`, `updated_at`, `status`, `show`, `required`, `icon`, `color`) VALUES (11, '繁體中文', 'tw_TW', 'TW', 3, '2022-04-27 08:07:17', '2024-10-30 21:04:09', 1, 1, 0, 'images/8bfa5226ab78b3999a3db5f1174e9488.png', '#2769F5');
INSERT INTO `languages` (`id`, `name`, `value`, `slug`, `order`, `created_at`, `updated_at`, `status`, `show`, `required`, `icon`, `color`) VALUES (29, '简体中文', 'zh_CN', 'CN', 5, '2024-08-15 18:50:20', '2025-03-03 10:30:37', 1, 1, 0, NULL, '#20DDC7');
INSERT INTO `languages` (`id`, `name`, `value`, `slug`, `order`, `created_at`, `updated_at`, `status`, `show`, `required`, `icon`, `color`) VALUES (31, '日本語', 'jp_JP', 'JP', 10, '2022-05-20 18:40:49', '2025-02-13 08:26:08', 1, 1, 0, 'images/ae35af7f6b0f295acaca56e423e11e79.png', '#B95959');
INSERT INTO `languages` (`id`, `name`, `value`, `slug`, `order`, `created_at`, `updated_at`, `status`, `show`, `required`, `icon`, `color`) VALUES (32, '한국어', 'ko_KO', 'KO', 100, '2022-06-25 20:53:32', '2025-07-24 20:19:59', 0, 0, 0, NULL, '#B95959');
INSERT INTO `languages` (`id`, `name`, `value`, `slug`, `order`, `created_at`, `updated_at`, `status`, `show`, `required`, `icon`, `color`) VALUES (33, 'ภาษาไทย', 'th_TH', 'TH', 100, '2022-06-25 20:53:32', '2025-07-24 20:19:59', 0, 0, 0, NULL, '#B95959');
INSERT INTO `languages` (`id`, `name`, `value`, `slug`, `order`, `created_at`, `updated_at`, `status`, `show`, `required`, `icon`, `color`) VALUES (34, 'Português', 'pt_PT', 'PT', 100, '2022-06-25 20:53:32', '2025-07-24 20:20:01', 0, 0, 0, NULL, '#B95959');
INSERT INTO `languages` (`id`, `name`, `value`, `slug`, `order`, `created_at`, `updated_at`, `status`, `show`, `required`, `icon`, `color`) VALUES (35, 'Melayu', 'ms_MS', 'MS', 100, '2022-06-25 20:53:32', '2025-07-24 20:20:02', 0, 0, 0, NULL, '#B95959');
INSERT INTO `languages` (`id`, `name`, `value`, `slug`, `order`, `created_at`, `updated_at`, `status`, `show`, `required`, `icon`, `color`) VALUES (36, 'Indonesia', 'id_ID', 'ID', 100, '2022-06-25 20:53:32', '2025-07-24 20:20:03', 0, 0, 0, NULL, '#B95959');
COMMIT;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
BEGIN;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2, '2014_10_12_100000_create_password_reset_tokens_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3, '2016_01_04_173148_create_admin_tables', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5, '2019_12_14_000001_create_personal_access_tokens_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6, '2020_09_07_090635_create_admin_settings_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7, '2020_09_22_015815_create_admin_extensions_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8, '2020_11_01_083237_update_admin_menu_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9, '2024_10_26_122054_create_sku_attribute_table', 1);
COMMIT;

-- ----------------------------
-- Table structure for news
-- ----------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `title` json NOT NULL COMMENT '标题',
  `describe` json NOT NULL COMMENT '描述',
  `cover` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL COMMENT '封面',
  `content` json NOT NULL COMMENT '内容',
  `order` smallint NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `is_top` tinyint(1) DEFAULT '0' COMMENT '状态',
  `read_min` tinyint(1) NOT NULL DEFAULT '5' COMMENT '阅读分钟',
  `fake_read_nums` int NOT NULL DEFAULT '1' COMMENT '假阅读数',
  `read_nums` int NOT NULL DEFAULT '1' COMMENT '真实阅读数',
  `pushd_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '发布时间',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of news
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for node
-- ----------------------------
DROP TABLE IF EXISTS `node`;
CREATE TABLE `node` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` json NOT NULL COMMENT '节点名称',
  `price` decimal(15,2) NOT NULL COMMENT '节点价格',
  `total` int NOT NULL COMMENT '名额',
  `gift_power` decimal(10,2) NOT NULL COMMENT '赠送多少G',
  `withdraw_rate` decimal(15,2) NOT NULL COMMENT '提现手续费分润',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态 1-上架 2-下架',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id` DESC) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of node
-- ----------------------------
BEGIN;
INSERT INTO `node` (`id`, `name`, `price`, `total`, `gift_power`, `withdraw_rate`, `status`, `created_at`, `updated_at`) VALUES (3, '{\"CN\": \"创世节点\", \"EN\": \"Genesis nodes\", \"ID\": \"創世節點\", \"JP\": \"ジェネシスノード\", \"KO\": \"創世節點\", \"MS\": \"創世節點\", \"PT\": \"創世節點\", \"TH\": \"創世節點\", \"TW\": \"創世節點\"}', 3000.00, 200, 30.00, 40.00, 1, '2025-05-11 01:36:32', '2025-07-25 15:03:20');
INSERT INTO `node` (`id`, `name`, `price`, `total`, `gift_power`, `withdraw_rate`, `status`, `created_at`, `updated_at`) VALUES (2, '{\"CN\": \"核心节点\", \"EN\": \"Core nodes\", \"ID\": \"Core nodes\", \"JP\": \"エリートノード\", \"KO\": \"Core nodes\", \"MS\": \"Core nodes\", \"PT\": \"Core nodes\", \"TH\": \"Core nodes\", \"TW\": \"核心節點\"}', 2000.00, 300, 20.00, 30.00, 1, '2025-05-11 01:36:00', '2025-07-25 15:03:15');
INSERT INTO `node` (`id`, `name`, `price`, `total`, `gift_power`, `withdraw_rate`, `status`, `created_at`, `updated_at`) VALUES (1, '{\"CN\": \"精英节点\", \"EN\": \"Elite nodes\", \"ID\": \"精英節點\", \"JP\": \"エリートノード\", \"KO\": \"精英節點\", \"MS\": \"精英節點\", \"PT\": \"精英節點\", \"TH\": \"精英節點\", \"TW\": \"精英節點\"}', 1000.00, 500, 10.00, 30.00, 1, '2025-05-11 01:35:25', '2025-07-25 14:59:50');
COMMIT;

-- ----------------------------
-- Table structure for node_log
-- ----------------------------
DROP TABLE IF EXISTS `node_log`;
CREATE TABLE `node_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT '用户ID',
  `node_id` int NOT NULL COMMENT '节点ID',
  `price` decimal(15,4) NOT NULL COMMENT '支付金额',
  `hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '支付Hash',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of node_log
-- ----------------------------
BEGIN;
INSERT INTO `node_log` (`id`, `user_id`, `node_id`, `price`, `hash`, `created_at`, `updated_at`) VALUES (5, 13, 1, 1000.0000, '余额购买175345527641151', '2025-07-25 22:54:36', '2025-07-25 22:54:36');
INSERT INTO `node_log` (`id`, `user_id`, `node_id`, `price`, `hash`, `created_at`, `updated_at`) VALUES (6, 13, 2, 2000.0000, '余额购买175345548777511', '2025-07-25 22:58:07', '2025-07-25 22:58:07');
COMMIT;

-- ----------------------------
-- Table structure for notices
-- ----------------------------
DROP TABLE IF EXISTS `notices`;
CREATE TABLE `notices` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `title` json NOT NULL COMMENT '标题',
  `content` json NOT NULL COMMENT '内容',
  `order` int DEFAULT NULL COMMENT '排序',
  `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '是否使用该公告',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ispop` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci COMMENT='公告表';

-- ----------------------------
-- Records of notices
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for price_log
-- ----------------------------
DROP TABLE IF EXISTS `price_log`;
CREATE TABLE `price_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL COMMENT '日期',
  `datetime` datetime NOT NULL COMMENT '当前时间',
  `price` decimal(15,4) NOT NULL COMMENT '价格',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `date` (`date`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of price_log
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for settings
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `channel_id` int unsigned NOT NULL DEFAULT '1' COMMENT 'channel_id',
  `default_lang` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT '默认语言',
  `money_decimal` tinyint(1) NOT NULL COMMENT '余额显示小数点',
  `usdt_withdraw_enable` tinyint unsigned DEFAULT '0' COMMENT 'USDT提现开关',
  `fac_withdraw_enable` tinyint(1) DEFAULT NULL COMMENT 'FAC提现开关',
  `usdt_withdraw_rate` decimal(10,2) unsigned DEFAULT '0.00' COMMENT 'USDT提现手续费率',
  `fac_withdraw_rate` decimal(10,2) DEFAULT NULL COMMENT 'FAC提现手续费率',
  `usdt_min_withdraw` decimal(10,2) unsigned DEFAULT '0.00' COMMENT 'USDT单笔最低提现金额',
  `usdt_max_withdraw` decimal(10,2) DEFAULT NULL COMMENT 'USDT单笔最高提现金额',
  `usdt_daily_max_withdraw` decimal(10,2) DEFAULT NULL COMMENT 'USDT单日最高提现金额',
  `fac_min_withdraw` decimal(10,2) DEFAULT NULL COMMENT 'FAC单笔最低提现数量',
  `fac_max_withdraw` decimal(10,2) DEFAULT NULL COMMENT 'FAC单日最高提现数量',
  `fac_daily_max_withdraw` decimal(10,2) DEFAULT NULL COMMENT 'FAC单日最高提现数量',
  `lock_address` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL COMMENT '锁仓合约地址',
  `power_price` decimal(15,4) DEFAULT NULL COMMENT '算力每G单价',
  `zhi_rate` decimal(10,2) DEFAULT NULL COMMENT '直推奖励',
  `ceng_rate` decimal(10,2) DEFAULT NULL COMMENT '层级奖励',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of settings
-- ----------------------------
BEGIN;
INSERT INTO `settings` (`id`, `channel_id`, `default_lang`, `money_decimal`, `usdt_withdraw_enable`, `fac_withdraw_enable`, `usdt_withdraw_rate`, `fac_withdraw_rate`, `usdt_min_withdraw`, `usdt_max_withdraw`, `usdt_daily_max_withdraw`, `fac_min_withdraw`, `fac_max_withdraw`, `fac_daily_max_withdraw`, `lock_address`, `power_price`, `zhi_rate`, `ceng_rate`) VALUES (1, 1, 'TW', 6, 1, 1, 0.00, 5.00, 1.00, 1000.00, 5000.00, 100.00, 10000.00, 10000.00, '0xx', 100.0000, 10.00, 2.00);
COMMIT;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `address` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT '地址',
  `zhi_num` int unsigned NOT NULL DEFAULT '0' COMMENT '直推人数',
  `team_num` int unsigned NOT NULL DEFAULT '0' COMMENT '团队人数',
  `parent_id` int unsigned NOT NULL DEFAULT '0' COMMENT '上级ID',
  `deep` int unsigned NOT NULL DEFAULT '1' COMMENT '深度',
  `path` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci COMMENT '推荐路径',
  `code` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT '邀请码',
  `me_performance` decimal(15,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '个人业绩',
  `team_performance` decimal(15,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '团队业绩',
  `total_performance` decimal(15,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '累计业绩',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态 1-启用 0-禁用',
  `ip` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL COMMENT '注册IP',
  `level_id` int unsigned NOT NULL DEFAULT '1' COMMENT '等级',
  `level_name` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'V0' COMMENT '等级名称',
  `backend_level_id` int unsigned NOT NULL DEFAULT '1' COMMENT '后台设置等级',
  `node_id` int unsigned NOT NULL DEFAULT '0' COMMENT '节点ID',
  `valid_status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '是否有效 0-否 1-是',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `code_index` (`code`) USING BTREE COMMENT '邀请码',
  UNIQUE KEY `address_index` (`address`) USING BTREE,
  KEY `idx_users_parent_id` (`parent_id`) USING BTREE,
  KEY `idx_users_level_id` (`level_id`) USING BTREE,
  KEY `idx_users_path` (`path`(50)) USING BTREE,
  KEY `idx_users_path_level` (`path`(50),`level_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
BEGIN;
INSERT INTO `users` (`id`, `address`, `zhi_num`, `team_num`, `parent_id`, `deep`, `path`, `code`, `me_performance`, `team_performance`, `total_performance`, `status`, `ip`, `level_id`, `level_name`, `backend_level_id`, `node_id`, `valid_status`, `created_at`, `updated_at`) VALUES (12, 'admin', 2, 2, 0, 1, NULL, 'abcdef', 0.0000, 113.0000, 113.0000, 1, NULL, 1, 'V0', 1, 0, 0, '2025-07-25 22:45:45', '2025-07-25 22:58:52');
INSERT INTO `users` (`id`, `address`, `zhi_num`, `team_num`, `parent_id`, `deep`, `path`, `code`, `me_performance`, `team_performance`, `total_performance`, `status`, `ip`, `level_id`, `level_name`, `backend_level_id`, `node_id`, `valid_status`, `created_at`, `updated_at`) VALUES (13, '0xf0ac8690f70f68b330d0e2802c22f2af6d9bc229', 0, 0, 12, 2, '-12-', 'AIDZIB5ME6JY', 10.0000, 0.0000, 10.0000, 1, '104.245.43.162', 1, 'V0', 1, 2, 1, '2025-07-25 22:45:51', '2025-07-25 22:58:07');
INSERT INTO `users` (`id`, `address`, `zhi_num`, `team_num`, `parent_id`, `deep`, `path`, `code`, `me_performance`, `team_performance`, `total_performance`, `status`, `ip`, `level_id`, `level_name`, `backend_level_id`, `node_id`, `valid_status`, `created_at`, `updated_at`) VALUES (14, '0x3a815ca0b53067fd0df02cb0693c585771392d26', 0, 0, 12, 2, '-12-', 'BT3Z5PCRYCDG', 103.0000, 0.0000, 103.0000, 1, '45.78.36.103', 1, 'V0', 1, 0, 1, '2025-07-25 22:46:44', '2025-07-25 22:58:52');
COMMIT;

-- ----------------------------
-- Table structure for users_coin
-- ----------------------------
DROP TABLE IF EXISTS `users_coin`;
CREATE TABLE `users_coin` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT '用户ID',
  `type` int NOT NULL COMMENT '钱包类型 1-资金账户USDT 2-资金账户NAAI 3-交易账户USDT 4-交易账户NAAI',
  `amount` decimal(20,6) unsigned NOT NULL DEFAULT '0.000000' COMMENT '累计资产',
  `lock_amount` decimal(20,6) unsigned NOT NULL DEFAULT '0.000000' COMMENT '锁定资产',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `search_index` (`user_id`,`type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COMMENT='用户余额';

-- ----------------------------
-- Records of users_coin
-- ----------------------------
BEGIN;
INSERT INTO `users_coin` (`id`, `user_id`, `type`, `amount`, `lock_amount`) VALUES (6, 13, 1, 6000.000000, 0.000000);
INSERT INTO `users_coin` (`id`, `user_id`, `type`, `amount`, `lock_amount`) VALUES (7, 14, 1, 89700.000000, 0.000000);
COMMIT;

-- ----------------------------
-- Table structure for users_income_log_0
-- ----------------------------
DROP TABLE IF EXISTS `users_income_log_0`;
CREATE TABLE `users_income_log_0` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `amount_type` tinyint NOT NULL,
  `before` decimal(20,6) NOT NULL,
  `total` decimal(20,6) NOT NULL,
  `after` decimal(20,6) NOT NULL,
  `type` tinyint NOT NULL,
  `remark` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `add_type` tinyint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `users_income_log_0_user_id_index` (`user_id`) USING BTREE,
  KEY `users_income_log_0_amount_type_index` (`amount_type`) USING BTREE,
  KEY `users_income_log_0_type_index` (`type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users_income_log_0
-- ----------------------------
BEGIN;
INSERT INTO `users_income_log_0` (`id`, `user_id`, `amount_type`, `before`, `total`, `after`, `type`, `remark`, `add_type`, `created_at`, `updated_at`) VALUES (17, 13, 1, 0.000000, 10000.000000, 10000.000000, 1, '后台操作', 1, '2025-07-25 22:46:03', '2025-07-25 22:46:03');
INSERT INTO `users_income_log_0` (`id`, `user_id`, `amount_type`, `before`, `total`, `after`, `type`, `remark`, `add_type`, `created_at`, `updated_at`) VALUES (19, 13, 1, 10000.000000, -1000.000000, 9000.000000, 5, '购买矿机', 2, '2025-07-25 22:52:12', '2025-07-25 22:52:12');
INSERT INTO `users_income_log_0` (`id`, `user_id`, `amount_type`, `before`, `total`, `after`, `type`, `remark`, `add_type`, `created_at`, `updated_at`) VALUES (20, 14, 1, 0.000000, 100000.000000, 100000.000000, 1, '后台操作', 1, '2025-07-25 22:53:04', '2025-07-25 22:53:04');
INSERT INTO `users_income_log_0` (`id`, `user_id`, `amount_type`, `before`, `total`, `after`, `type`, `remark`, `add_type`, `created_at`, `updated_at`) VALUES (21, 14, 1, 100000.000000, -10000.000000, 90000.000000, 5, '购买矿机', 2, '2025-07-25 22:53:07', '2025-07-25 22:53:07');
INSERT INTO `users_income_log_0` (`id`, `user_id`, `amount_type`, `before`, `total`, `after`, `type`, `remark`, `add_type`, `created_at`, `updated_at`) VALUES (22, 14, 1, 90000.000000, -100.000000, 89900.000000, 5, '购买矿机', 2, '2025-07-25 22:53:23', '2025-07-25 22:53:23');
INSERT INTO `users_income_log_0` (`id`, `user_id`, `amount_type`, `before`, `total`, `after`, `type`, `remark`, `add_type`, `created_at`, `updated_at`) VALUES (23, 14, 1, 89900.000000, -100.000000, 89800.000000, 5, '购买矿机', 2, '2025-07-25 22:53:51', '2025-07-25 22:53:51');
INSERT INTO `users_income_log_0` (`id`, `user_id`, `amount_type`, `before`, `total`, `after`, `type`, `remark`, `add_type`, `created_at`, `updated_at`) VALUES (24, 13, 1, 9000.000000, -1000.000000, 8000.000000, 4, '购买节点', 2, '2025-07-25 22:54:36', '2025-07-25 22:54:36');
INSERT INTO `users_income_log_0` (`id`, `user_id`, `amount_type`, `before`, `total`, `after`, `type`, `remark`, `add_type`, `created_at`, `updated_at`) VALUES (25, 13, 1, 8000.000000, -2000.000000, 6000.000000, 4, '购买节点', 2, '2025-07-25 22:58:07', '2025-07-25 22:58:07');
INSERT INTO `users_income_log_0` (`id`, `user_id`, `amount_type`, `before`, `total`, `after`, `type`, `remark`, `add_type`, `created_at`, `updated_at`) VALUES (26, 14, 1, 89800.000000, -100.000000, 89700.000000, 5, '购买矿机', 2, '2025-07-25 22:58:50', '2025-07-25 22:58:50');
COMMIT;

-- ----------------------------
-- Table structure for users_level
-- ----------------------------
DROP TABLE IF EXISTS `users_level`;
CREATE TABLE `users_level` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '等级名称',
  `me_power` decimal(10,2) NOT NULL COMMENT '个人算力',
  `team_power` decimal(10,2) NOT NULL COMMENT '团队算力',
  `team_valid_count` int NOT NULL COMMENT '团队有效人数',
  `team_num` int unsigned NOT NULL DEFAULT '0' COMMENT '团队等级人数',
  `team_level_id` int unsigned NOT NULL DEFAULT '1' COMMENT '团队等级',
  `rate1` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '收益率',
  `rate2` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '平级收益率',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='等级配置';

-- ----------------------------
-- Records of users_level
-- ----------------------------
BEGIN;
INSERT INTO `users_level` (`id`, `name`, `me_power`, `team_power`, `team_valid_count`, `team_num`, `team_level_id`, `rate1`, `rate2`) VALUES (1, 'V0', 0.00, 0.00, 0, 0, 0, 0.00, 0.00);
INSERT INTO `users_level` (`id`, `name`, `me_power`, `team_power`, `team_valid_count`, `team_num`, `team_level_id`, `rate1`, `rate2`) VALUES (2, 'V1', 1.00, 100.00, 3, 0, 0, 10.00, 5.00);
INSERT INTO `users_level` (`id`, `name`, `me_power`, `team_power`, `team_valid_count`, `team_num`, `team_level_id`, `rate1`, `rate2`) VALUES (3, 'V2', 3.00, 500.00, 0, 2, 2, 20.00, 5.00);
INSERT INTO `users_level` (`id`, `name`, `me_power`, `team_power`, `team_valid_count`, `team_num`, `team_level_id`, `rate1`, `rate2`) VALUES (4, 'V3', 10.00, 2000.00, 0, 2, 3, 30.00, 5.00);
INSERT INTO `users_level` (`id`, `name`, `me_power`, `team_power`, `team_valid_count`, `team_num`, `team_level_id`, `rate1`, `rate2`) VALUES (5, 'V4', 20.00, 10000.00, 0, 2, 4, 40.00, 5.00);
INSERT INTO `users_level` (`id`, `name`, `me_power`, `team_power`, `team_valid_count`, `team_num`, `team_level_id`, `rate1`, `rate2`) VALUES (6, 'V5', 50.00, 30000.00, 0, 2, 5, 50.00, 5.00);
INSERT INTO `users_level` (`id`, `name`, `me_power`, `team_power`, `team_valid_count`, `team_num`, `team_level_id`, `rate1`, `rate2`) VALUES (7, 'V6', 100.00, 50000.00, 0, 2, 6, 60.00, 5.00);
COMMIT;

-- ----------------------------
-- Table structure for users_limit
-- ----------------------------
DROP TABLE IF EXISTS `users_limit`;
CREATE TABLE `users_limit` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL COMMENT '用户ID',
  `withdraw_usdt_status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '提现USDT开关 0-未限制 1-限制提现',
  `withdraw_nadi_status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '提现NADI开关 0-未限制 1-限制提现',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `user_index` (`user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户功能限制';

-- ----------------------------
-- Records of users_limit
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for users_machine
-- ----------------------------
DROP TABLE IF EXISTS `users_machine`;
CREATE TABLE `users_machine` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT '用户ID',
  `num` int NOT NULL COMMENT '购买了多少G',
  `total_amount` decimal(20,6) NOT NULL COMMENT '总金额',
  `is_settlement` tinyint(1) NOT NULL COMMENT '状态 1-待结算  2-已结算',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户矿机';

-- ----------------------------
-- Records of users_machine
-- ----------------------------
BEGIN;
INSERT INTO `users_machine` (`id`, `user_id`, `num`, `total_amount`, `is_settlement`, `created_at`, `updated_at`) VALUES (1, 13, 10, 1000.000000, 1, '2025-07-25 22:52:12', '2025-07-25 22:52:12');
INSERT INTO `users_machine` (`id`, `user_id`, `num`, `total_amount`, `is_settlement`, `created_at`, `updated_at`) VALUES (2, 14, 100, 10000.000000, 1, '2025-07-25 22:53:07', '2025-07-25 22:53:07');
INSERT INTO `users_machine` (`id`, `user_id`, `num`, `total_amount`, `is_settlement`, `created_at`, `updated_at`) VALUES (3, 14, 1, 100.000000, 1, '2025-07-25 22:53:23', '2025-07-25 22:53:23');
INSERT INTO `users_machine` (`id`, `user_id`, `num`, `total_amount`, `is_settlement`, `created_at`, `updated_at`) VALUES (4, 14, 1, 100.000000, 1, '2025-07-25 22:53:51', '2025-07-25 22:53:51');
INSERT INTO `users_machine` (`id`, `user_id`, `num`, `total_amount`, `is_settlement`, `created_at`, `updated_at`) VALUES (5, 14, 1, 100.000000, 1, '2025-07-25 22:58:50', '2025-07-25 22:58:50');
COMMIT;

-- ----------------------------
-- Table structure for users_power
-- ----------------------------
DROP TABLE IF EXISTS `users_power`;
CREATE TABLE `users_power` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL COMMENT '用户ID',
  `total_power` decimal(20,6) unsigned NOT NULL DEFAULT '0.000000' COMMENT '累计算力',
  `valid_power` decimal(20,6) unsigned NOT NULL DEFAULT '0.000000' COMMENT '有效算力',
  `expired_power` decimal(20,6) unsigned NOT NULL DEFAULT '0.000000' COMMENT '失效算力',
  `machine_power` decimal(20,6) unsigned NOT NULL DEFAULT '0.000000' COMMENT '矿机算力',
  `node_power` decimal(20,6) unsigned NOT NULL DEFAULT '0.000000' COMMENT '节点算力',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `user_index` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户算力';

-- ----------------------------
-- Records of users_power
-- ----------------------------
BEGIN;
INSERT INTO `users_power` (`id`, `user_id`, `total_power`, `valid_power`, `expired_power`, `machine_power`, `node_power`) VALUES (4, 13, 40.000000, 40.000000, 0.000000, 10.000000, 30.000000);
INSERT INTO `users_power` (`id`, `user_id`, `total_power`, `valid_power`, `expired_power`, `machine_power`, `node_power`) VALUES (5, 14, 103.000000, 103.000000, 0.000000, 103.000000, 0.000000);
COMMIT;

-- ----------------------------
-- Table structure for users_power_log_0
-- ----------------------------
DROP TABLE IF EXISTS `users_power_log_0`;
CREATE TABLE `users_power_log_0` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `power_type` tinyint NOT NULL,
  `before` decimal(20,6) NOT NULL,
  `after` decimal(20,6) NOT NULL,
  `power` decimal(20,6) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `users_power_log_0_user_id_index` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users_power_log_0
-- ----------------------------
BEGIN;
INSERT INTO `users_power_log_0` (`id`, `user_id`, `power_type`, `before`, `after`, `power`, `created_at`, `updated_at`) VALUES (31, 13, 2, 0.000000, 10.000000, 10.000000, '2025-07-25 22:52:12', '2025-07-25 22:52:12');
INSERT INTO `users_power_log_0` (`id`, `user_id`, `power_type`, `before`, `after`, `power`, `created_at`, `updated_at`) VALUES (32, 14, 2, 0.000000, 100.000000, 100.000000, '2025-07-25 22:53:07', '2025-07-25 22:53:07');
INSERT INTO `users_power_log_0` (`id`, `user_id`, `power_type`, `before`, `after`, `power`, `created_at`, `updated_at`) VALUES (33, 14, 2, 100.000000, 101.000000, 1.000000, '2025-07-25 22:53:23', '2025-07-25 22:53:23');
INSERT INTO `users_power_log_0` (`id`, `user_id`, `power_type`, `before`, `after`, `power`, `created_at`, `updated_at`) VALUES (34, 14, 2, 101.000000, 102.000000, 1.000000, '2025-07-25 22:53:51', '2025-07-25 22:53:51');
INSERT INTO `users_power_log_0` (`id`, `user_id`, `power_type`, `before`, `after`, `power`, `created_at`, `updated_at`) VALUES (35, 13, 1, 10.000000, 20.000000, 10.000000, '2025-07-25 22:54:36', '2025-07-25 22:54:36');
INSERT INTO `users_power_log_0` (`id`, `user_id`, `power_type`, `before`, `after`, `power`, `created_at`, `updated_at`) VALUES (36, 13, 1, 20.000000, 40.000000, 20.000000, '2025-07-25 22:58:07', '2025-07-25 22:58:07');
INSERT INTO `users_power_log_0` (`id`, `user_id`, `power_type`, `before`, `after`, `power`, `created_at`, `updated_at`) VALUES (37, 14, 2, 102.000000, 103.000000, 1.000000, '2025-07-25 22:58:50', '2025-07-25 22:58:50');
COMMIT;

-- ----------------------------
-- Table structure for users_recharge
-- ----------------------------
DROP TABLE IF EXISTS `users_recharge`;
CREATE TABLE `users_recharge` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT '用户ID',
  `order_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '订单号',
  `type` tinyint(1) NOT NULL COMMENT '充值类型 1-购买矿机 2-矿机销毁 3-联合储蓄 4-销毁挖矿  5-节点充值 6-跨链充值 7-联合挖矿',
  `nums` decimal(15,4) NOT NULL COMMENT '充值数量',
  `other_nums` decimal(15,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '币种2支付数量',
  `coin` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '充值币种',
  `other_coin` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '充值币种2',
  `hash` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '充值hash',
  `detail` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT '充值详情',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `hash_index` (`hash`) USING BTREE,
  KEY `user_id_index` (`user_id`,`type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='充值表';

-- ----------------------------
-- Records of users_recharge
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for users_recharge_order
-- ----------------------------
DROP TABLE IF EXISTS `users_recharge_order`;
CREATE TABLE `users_recharge_order` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `order_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '订单号',
  `user_id` int NOT NULL COMMENT '用户ID',
  `type` tinyint(1) NOT NULL COMMENT '类型 1-购买节点',
  `coin_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '合约地址',
  `total_amount` decimal(20,6) NOT NULL COMMENT '需要支付金额',
  `coin1_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '币种2合约地址',
  `total1_amount` decimal(20,6) unsigned NOT NULL DEFAULT '0.000000' COMMENT '需要支付币种2数量',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态 1-待支付 2-支付完成',
  `extend` json DEFAULT NULL COMMENT '详细信息',
  `hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '充值hash',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `order_index` (`order_no`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='充值订单';

-- ----------------------------
-- Records of users_recharge_order
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for users_withdraw
-- ----------------------------
DROP TABLE IF EXISTS `users_withdraw`;
CREATE TABLE `users_withdraw` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `no` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT '订单号',
  `coin_id` tinyint unsigned NOT NULL COMMENT '提现到账币种 1-NAAI',
  `user_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '用户ID',
  `num` decimal(15,2) NOT NULL COMMENT '出金数量',
  `fee` decimal(15,2) NOT NULL COMMENT '手续费比例',
  `fee_amount` decimal(15,2) NOT NULL COMMENT '手续费金额',
  `ac_amount` decimal(15,2) NOT NULL COMMENT '实际到账金额',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态 0-待审核 1-待通过 2-已通过 3-已退还',
  `finsh_time` datetime DEFAULT NULL COMMENT '到账时间',
  `is_push` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '是否推送给钱包系统 0-否 1-是',
  `is_settlement` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '是否结算 0-否 1-是',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1635 DEFAULT CHARSET=utf8mb3;

-- ----------------------------
-- Records of users_withdraw
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for website_analyze
-- ----------------------------
DROP TABLE IF EXISTS `website_analyze`;
CREATE TABLE `website_analyze` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL COMMENT '日期',
  `register_num` int unsigned NOT NULL DEFAULT '0' COMMENT '今日注册人数',
  `backend_recharge_usdt` decimal(20,6) NOT NULL DEFAULT '0.000000' COMMENT '后台加款USDT',
  `backend_recharge_coin` decimal(20,6) NOT NULL DEFAULT '0.000000' COMMENT '后台加款代币',
  `recharge_usdt_num` decimal(20,6) unsigned NOT NULL DEFAULT '0.000000' COMMENT '今日入金量',
  `recharge_usdt_count` int unsigned NOT NULL DEFAULT '0' COMMENT '入金笔数',
  `recharge_coin_num` decimal(20,6) unsigned NOT NULL DEFAULT '0.000000' COMMENT '代币入金量',
  `recharge_coin_count` int unsigned NOT NULL DEFAULT '0' COMMENT '代币入金笔数',
  `withdraw_num` decimal(20,6) unsigned NOT NULL DEFAULT '0.000000' COMMENT '提现量',
  `withdraw_count` int unsigned NOT NULL DEFAULT '0' COMMENT '提现笔数',
  `withdraw_fee` decimal(20,6) unsigned NOT NULL DEFAULT '0.000000' COMMENT '提现累计手续费',
  `power_income` decimal(20,6) unsigned NOT NULL DEFAULT '0.000000' COMMENT '算力收益量',
  `equipment_income` decimal(20,6) unsigned NOT NULL DEFAULT '0.000000' COMMENT '矿机收益量',
  `node_income` decimal(20,6) unsigned NOT NULL DEFAULT '0.000000' COMMENT '节点收益量',
  `node_withdraw_income` decimal(20,6) unsigned NOT NULL DEFAULT '0.000000' COMMENT '节点提现分红量',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='站点统计';

-- ----------------------------
-- Records of website_analyze
-- ----------------------------
BEGIN;
INSERT INTO `website_analyze` (`id`, `date`, `register_num`, `backend_recharge_usdt`, `backend_recharge_coin`, `recharge_usdt_num`, `recharge_usdt_count`, `recharge_coin_num`, `recharge_coin_count`, `withdraw_num`, `withdraw_count`, `withdraw_fee`, `power_income`, `equipment_income`, `node_income`, `node_withdraw_income`) VALUES (4, '2025-07-25', 2, 110000.000000, 0.000000, 0.000000, 0, 0.000000, 0, 0.000000, 0, 0.000000, 0.000000, 0.000000, 0.000000, 0.000000);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
