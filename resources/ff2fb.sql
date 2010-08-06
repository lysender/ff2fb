-- MySQL dump 10.10
--
-- Host: localhost    Database: dc_project
-- ------------------------------------------------------
-- Server version	5.0.15-nt-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `blog`
--

DROP TABLE IF EXISTS `blog`;
CREATE TABLE `blog` (
  `id` smallint(6) NOT NULL auto_increment,
  `title` varchar(64) NOT NULL,
  `body` text NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `dc_acl_privilege`
--

DROP TABLE IF EXISTS `dc_acl_privilege`;
CREATE TABLE `dc_acl_privilege` (
  `privilege_id` smallint(6) NOT NULL auto_increment,
  `role_id` smallint(6) NOT NULL,
  `resource_id` smallint(6) NOT NULL,
  `privilege_name` varchar(20) NOT NULL,
  `privilege_description` varchar(128) NOT NULL,
  `allow` tinyint(4) NOT NULL,
  PRIMARY KEY  (`privilege_id`),
  UNIQUE KEY `privilege_key` (`role_id`,`resource_id`,`privilege_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `dc_acl_resource`
--

DROP TABLE IF EXISTS `dc_acl_resource`;
CREATE TABLE `dc_acl_resource` (
  `resource_id` smallint(6) NOT NULL auto_increment,
  `resource_name` varchar(20) NOT NULL,
  `resource_description` varchar(128) NOT NULL,
  PRIMARY KEY  (`resource_id`),
  UNIQUE KEY `resource_name` (`resource_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `dc_acl_role`
--

DROP TABLE IF EXISTS `dc_acl_role`;
CREATE TABLE `dc_acl_role` (
  `role_id` smallint(6) NOT NULL auto_increment,
  `role_name` varchar(20) NOT NULL,
  `role_description` varchar(128) NOT NULL,
  PRIMARY KEY  (`role_id`),
  UNIQUE KEY `role_name` (`role_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `dc_config`
--

DROP TABLE IF EXISTS `dc_config`;
CREATE TABLE `dc_config` (
  `id` smallint(6) NOT NULL,
  `description` varchar(50) NOT NULL,
  `date_modified` datetime NOT NULL,
  `content_serialized` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `dc_ff2fb_facebook`
--

DROP TABLE IF EXISTS `dc_ff2fb_facebook`;
CREATE TABLE `dc_ff2fb_facebook` (
  `fb_user_id` binary(16) NOT NULL,
  PRIMARY KEY  (`fb_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `dc_ff2fb_feed`
--

DROP TABLE IF EXISTS `dc_ff2fb_feed`;
CREATE TABLE `dc_ff2fb_feed` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `feed_id` binary(16) NOT NULL,
  `date_posted` datetime NOT NULL,
  `like_flag` tinyint(4) NOT NULL default '0',
  `content_serialized` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `feed_user_unique` (`user_id`,`feed_id`),
  KEY `date_posted` (`date_posted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Feeds from any source';

--
-- Table structure for table `dc_ff2fb_friendfeed`
--

DROP TABLE IF EXISTS `dc_ff2fb_friendfeed`;
CREATE TABLE `dc_ff2fb_friendfeed` (
  `user_id` int(11) NOT NULL,
  `friendfeed_id` varchar(20) NOT NULL,
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `friendfeed_id` (`friendfeed_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `dc_ff2fb_job`
--

DROP TABLE IF EXISTS `dc_ff2fb_job`;
CREATE TABLE `dc_ff2fb_job` (
  `id` mediumint(9) NOT NULL auto_increment,
  `job_id` binary(16) NOT NULL,
  `user_id` binary(16) NOT NULL,
  `content_serialized` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `job_id` (`job_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `dc_user`
--

DROP TABLE IF EXISTS `dc_user`;
CREATE TABLE `dc_user` (
  `user_id` int(6) NOT NULL auto_increment,
  `username` varchar(16) NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `date_joined` datetime NOT NULL,
  `last_login` datetime default NULL,
  `active` tinyint(4) NOT NULL default '1',
  `banned` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `dc_user_role`
--

DROP TABLE IF EXISTS `dc_user_role`;
CREATE TABLE `dc_user_role` (
  `user_id` int(11) NOT NULL,
  `role_id` smallint(6) NOT NULL,
  PRIMARY KEY  (`user_id`,`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

