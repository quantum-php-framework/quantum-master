# ************************************************************
# Sequel Pro SQL dump
# Version 5446
#
# https://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: localhost (MySQL 5.6.38)
# Database: quantum_dev
# Generation Time: 2019-11-20 10:47:21 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table accounts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `accounts`;

CREATE TABLE `accounts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` text,
  `uri` text,
  `is_active` tinyint(4) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table app_settings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_settings`;

CREATE TABLE `app_settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `app_uri` varchar(255) DEFAULT NULL,
  `key` text,
  `value` mediumtext,
  `uuid` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table auth_clients
# ------------------------------------------------------------

DROP TABLE IF EXISTS `auth_clients`;

CREATE TABLE `auth_clients` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uri` text,
  `name` text,
  `client_id` text,
  `client_secret` text,
  `encryption_key` text,
  `public_key` text,
  `private_key` text,
  `dev_auth_redirect_url` text,
  `production_auth_redirect_url` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table auth_user_tokens
# ------------------------------------------------------------

DROP TABLE IF EXISTS `auth_user_tokens`;

CREATE TABLE `auth_user_tokens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `client_id` bigint(20) DEFAULT NULL,
  `token` text,
  `signature` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table user_activities
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_activities`;

CREATE TABLE `user_activities` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `uuid` text,
  `type` text,
  `data` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table user_statuses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_statuses`;

CREATE TABLE `user_statuses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uri` text,
  `name` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `access_level_id` int(11) DEFAULT NULL,
  `title` text,
  `account_id` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `name` text,
  `lastname` text,
  `username` text,
  `email` text,
  `password` text,
  `quick_login_code` text,
  `hash` text,
  `last_login` datetime DEFAULT NULL,
  `last_known_ip` text,
  `auto_login_token` text,
  `password_recovery_token` text,
  `uuid` text,
  `phone` text,
  `hashid` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
