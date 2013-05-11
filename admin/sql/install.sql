CREATE TABLE IF NOT EXISTS `#__seminarman_application` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_filename_prefix` varchar(255) NOT NULL DEFAULT '',
  `invoice_number` int(11) NOT NULL DEFAULT '0',
  `course_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL DEFAULT '',
  `last_name` varchar(255) NOT NULL DEFAULT '',
  `salutation` varchar(11) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL,
  `attendees` double NOT NULL,
  `note` double,
  `attendance` int(11),
  `pricegroup` varchar(100) DEFAULT NULL,
  `price_per_attendee` double NOT NULL,
  `price_total` double NOT NULL,
  `price_vat` DOUBLE NOT NULL DEFAULT '0',
  `comments` text NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `hits` int(11) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `archived` tinyint(1) NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '0',
  `transaction_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_number` (`invoice_number`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__seminarman_atgroup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `alias` varchar(100) NOT NULL,
  `code` char(2) DEFAULT NULL,
  `color` varchar(7) NOT NULL,
  `description` text,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `hits` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `archived` tinyint(1) NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__seminarman_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `text` mediumtext NOT NULL,
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  `image` text NOT NULL,
  `icon` text NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `access` int(11) unsigned NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__seminarman_cats_course_relations` (
  `catid` int(11) NOT NULL DEFAULT '0',
  `courseid` int(11) NOT NULL DEFAULT '0',
  `ordering` tinyint(11) NOT NULL,
  PRIMARY KEY (`catid`,`courseid`),
  KEY `catid` (`catid`),
  KEY `itemid` (`courseid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__seminarman_cats_template_relations` (
  `catid` int(11) NOT NULL DEFAULT '0',
  `templateid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`catid`,`templateid`),
  KEY `catid` (`catid`),
  KEY `itemid` (`templateid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__seminarman_company_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `alias` varchar(100) NOT NULL,
  `code` char(2) DEFAULT NULL,
  `description` text,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `hits` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `archived` tinyint(1) NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__seminarman_country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loc` char(2) DEFAULT NULL,
  `code` char(2) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `alias` varchar(100) NOT NULL,
  `description` text,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `hits` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `language` char(7) NOT NULL,
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `access` int(11) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`title`),
  KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__seminarman_courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference_number` varchar(255) NOT NULL,
  `code` varchar(20) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `introtext` mediumtext NOT NULL,
  `fulltext` mediumtext NOT NULL,
  `plus` int(11) DEFAULT '0',
  `minus` int(11) DEFAULT '0',
  `hits` int(11) unsigned NOT NULL DEFAULT '0',
  `version` int(11) unsigned NOT NULL DEFAULT '0',
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  `metadata` text NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) unsigned NOT NULL DEFAULT '0',
  `created_by_alias` text NOT NULL,
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) unsigned NOT NULL DEFAULT '0',
  `attribs` text NOT NULL,
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `ordering` int(11) DEFAULT '0',
  `tutor_id` int(11) DEFAULT NULL,
  `id_group` int(11) NOT NULL,
  `id_experience_level` int(11) NOT NULL DEFAULT '0',
  `theme_points` INT( 11 ) NOT NULL DEFAULT '0',
  `price_type` varchar(100) NOT NULL,
  `job_experience` varchar(100) NOT NULL,
  `price` double DEFAULT NULL,
  `price2` double DEFAULT NULL,
  `price3` double DEFAULT NULL,
  `price4` double DEFAULT NULL,
  `price5` double DEFAULT NULL,
  `vat` DOUBLE NOT NULL DEFAULT '0',
  `currency_price` char(10) DEFAULT NULL,
  `min_attend` INT( 11 ) NOT NULL DEFAULT '0',
  `capacity` int(11) NOT NULL DEFAULT '0',
  `location` varchar(100) NOT NULL,
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `url` varchar(250) NOT NULL,
  `image` varchar(255) NOT NULL,
  `email_template` int(11) unsigned NOT NULL DEFAULT '0',
  `email_template_cancel` int(11) unsigned NOT NULL DEFAULT '0',
  `email_template_trainer` int(11) unsigned NOT NULL DEFAULT '0',
  `email_template_trainer_cancel` int(11) unsigned NOT NULL DEFAULT '0',
  `invoice_template` int(11) unsigned NOT NULL DEFAULT '0',
  `attlst_template` int(11) unsigned NOT NULL DEFAULT '0',
  `start_date` date NOT NULL DEFAULT '0000-00-00',
  `finish_date` date NOT NULL DEFAULT '0000-00-00',
  `access` int(10) unsigned NOT NULL,
  `templateId` int(11) NOT NULL DEFAULT '0',
  `new` TINYINT(1) NOT NULL DEFAULT '1',
  `canceled` TINYINT(1) NOT NULL DEFAULT '0',
  `certificate_text` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__seminarman_emailtemplate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `templatefor` INT( 1 ) NULL DEFAULT '0',
  `title` varchar(50) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `body` text,
  `recipient` varchar(255) NOT NULL,
  `bcc` varchar(255) NOT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `isdefault` INT( 1 ) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__seminarman_experience_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `alias` varchar(100) NOT NULL,
  `code` char(2) DEFAULT NULL,
  `color` varchar(7) NOT NULL,
  `description` text,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `hits` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `archived` tinyint(1) NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`title`),
  KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__seminarman_favourites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `courseid` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`courseid`,`userid`),
  KEY `id` (`id`),
  KEY `itemid` (`courseid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__seminarman_fields` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `purpose` INT NOT NULL DEFAULT '0' COMMENT '0: application, 1: sales prospect. only relevant if type = ''group''',
  `ordering` int(11) DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `min` int(5) NOT NULL,
  `max` int(5) NOT NULL,
  `name` varchar(255) NOT NULL,
  `tips` text NOT NULL,
  `visible` tinyint(1) DEFAULT '0',
  `required` tinyint(1) DEFAULT '0',
  `searchable` tinyint(1) DEFAULT '1',
  `registration` tinyint(1) DEFAULT '1',
  `options` text,
  `fieldcode` varchar(255) NOT NULL,
  `paypalcode` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fieldcode` (`fieldcode`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__seminarman_fields_values` (
  `applicationid` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `field_id` int(10) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`applicationid`,`user_id`,`field_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__seminarman_fields_values_salesprospect` (
  `requestid` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `field_id` int(10) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`requestid`,`user_id`,`field_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__seminarman_fields_values_users` (
  `user_id` int(11) NOT NULL,
  `fieldcode` VARCHAR( 255 ) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`user_id`, `fieldcode`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__seminarman_fields_values_users_static` (
  `user_id` int(11) NOT NULL,
  `salutation` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__seminarman_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `altname` varchar(255) NOT NULL,
  `hits` int(11) unsigned NOT NULL DEFAULT '0',
  `uploaded` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `uploaded_by` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__seminarman_files_course_relations` (
  `fileid` int(11) NOT NULL DEFAULT '0',
  `courseid` int(11) NOT NULL DEFAULT '0',
  `ordering` tinyint(11) NOT NULL,
  PRIMARY KEY (`fileid`,`courseid`),
  KEY `fileid` (`fileid`),
  KEY `itemid` (`courseid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__seminarman_files_template_relations` (
  `fileid` int(11) NOT NULL DEFAULT '0',
  `templateid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`fileid`,`templateid`),
  KEY `fileid` (`fileid`),
  KEY `itemid` (`templateid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__seminarman_industry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `industry` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__seminarman_invoice_number` (
`number` INT NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT IGNORE INTO `#__seminarman_invoice_number` (`number`) VALUES (1);


CREATE TABLE IF NOT EXISTS `#__seminarman_pdftemplate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `templatefor` INT( 1 ) NULL DEFAULT '0',
  `html` text,
  `srcpdf` VARCHAR( 255 ) DEFAULT NULL,
  `isdefault` int(1) NOT NULL DEFAULT '0',
  `margin_left` double NOT NULL DEFAULT '0',
  `margin_right` double NOT NULL DEFAULT '0',
  `margin_top` double NOT NULL DEFAULT '0',
  `margin_bottom` double NOT NULL DEFAULT '0',
  `paperformat` VARCHAR( 32 ) NOT NULL,
  `orientation` VARCHAR( 1 ) NOT NULL DEFAULT 'P',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__seminarman_pricegroups` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `gid` int(10) NOT NULL,
  `jm_groups` varchar(5120) NOT NULL,
  `reg_group` int(10) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `calc_mathop` varchar(8) NOT NULL,
  `calc_value` float NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__seminarman_salesprospect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `first_name` varchar(255) NOT NULL DEFAULT '',
  `last_name` varchar(255) NOT NULL DEFAULT '',
  `salutation` varchar(11) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL,
  `attendees` double NOT NULL,
  `price_per_attendee` double NOT NULL,
  `price_total` double NOT NULL,
  `price_vat` DOUBLE NOT NULL DEFAULT '0',
  `comments` text NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `transaction_id` varchar(32) NOT NULL,
  `notified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `notified_course` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__seminarman_sessions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `courseid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(250) NOT NULL DEFAULT '',
  `alias` varchar(250) NOT NULL DEFAULT '',
  `session_date` date NOT NULL DEFAULT '0000-00-00',
  `start_time` time NOT NULL DEFAULT '00:00:00',
  `finish_time` time NOT NULL DEFAULT '00:00:00',
  `duration` double NOT NULL,
  `description` text NOT NULL,
  `session_location` varchar(250) NOT NULL DEFAULT '',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `hits` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `archived` tinyint(1) NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `courseid` (`courseid`,`published`,`archived`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__seminarman_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__seminarman_tags_course_relations` (
  `tid` int(11) NOT NULL DEFAULT '0',
  `courseid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tid`,`courseid`),
  KEY `tid` (`tid`),
  KEY `itemid` (`courseid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__seminarman_tags_template_relations` (
  `tid` int(11) NOT NULL DEFAULT '0',
  `templateid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tid`,`templateid`),
  KEY `tid` (`tid`),
  KEY `itemid` (`templateid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__seminarman_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference_number` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `code` varchar(20) NOT NULL DEFAULT '',
  `price` double DEFAULT NULL,
  `price2` double DEFAULT NULL,
  `price3` double DEFAULT NULL,
  `price4` double DEFAULT NULL,
  `price5` double DEFAULT NULL,
  `vat` DOUBLE NOT NULL DEFAULT '0',
  `introtext` mediumtext NOT NULL,
  `fulltext` mediumtext NOT NULL,
  `version` int(11) unsigned NOT NULL DEFAULT '0',
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  `metadata` text NOT NULL,
  `price_type` varchar(100) NOT NULL,
  `currency_price` char(10) DEFAULT NULL,
  `min_attend` INT( 11 ) NOT NULL DEFAULT '0',
  `location` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `email_template` int(11) NOT NULL DEFAULT '0',
  `email_template_trainer` int(11) NOT NULL DEFAULT '0',
  `email_template_cancel` int(11) NOT NULL DEFAULT '0',
  `email_template_trainer_cancel` int(11) NOT NULL,
  `invoice_template` int(11) unsigned NOT NULL DEFAULT '0',
  `attlst_template` int(11) unsigned NOT NULL DEFAULT '0',
  `start_date` date NOT NULL,
  `finish_date` date NOT NULL,
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL,
  `attribs` text NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `ordering` int(11) DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_by_alias` text NOT NULL,
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `id_group` int(11) NOT NULL,
  `id_experience_level` int(11) NOT NULL DEFAULT '0',
  `theme_points` INT( 11 ) NOT NULL DEFAULT '0',
  `job_experience` varchar(100) NOT NULL,
  `capacity` int(11) NOT NULL DEFAULT '0',
  `certificate_text` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__seminarman_tutor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(100) DEFAULT NULL,
  `alias` varchar(100) NOT NULL,
  `code` char(2) DEFAULT NULL,
  `firstname` varchar(100) NOT NULL DEFAULT '',
  `lastname` varchar(100) NOT NULL DEFAULT '',
  `salutation` varchar(11) NOT NULL,
  `other_title` varchar(100) NOT NULL DEFAULT '',
  `comp_name` varchar(100) NOT NULL DEFAULT '',
  `primary_phone` varchar(50) NOT NULL DEFAULT '',
  `fax_number` varchar(50) NOT NULL DEFAULT '',
  `url` varchar(250) NOT NULL DEFAULT '',
  `street` varchar(100) NOT NULL DEFAULT '',
  `id_country` int(11) NOT NULL DEFAULT '0',
  `state` varchar(50) NOT NULL DEFAULT '',
  `city` varchar(50) NOT NULL DEFAULT '',
  `zip` varchar(30) NOT NULL DEFAULT '',
  `id_comp_type` int(11) NOT NULL DEFAULT '0',
  `industry` varchar(100) NOT NULL,
  `description` text,
  `logofilename` varchar(100) DEFAULT NULL,
  `bill_addr` varchar(255) DEFAULT NULL,
  `bill_addr_cont` varchar(255) DEFAULT NULL,
  `bill_id_country` int(11) DEFAULT NULL,
  `bill_state` varchar(50) DEFAULT NULL,
  `bill_city` varchar(100) DEFAULT NULL,
  `bill_zip` varchar(25) DEFAULT NULL,
  `bill_phone` varchar(50) DEFAULT NULL,
  `metadescription` text,
  `metakeywords` text,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `hits` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `archived` tinyint(1) NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL DEFAULT '1',
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__seminarman_tutor_templates_relations` (
  `tutorid` int(11) NOT NULL DEFAULT '0',
  `templateid` int(11) NOT NULL DEFAULT '0',
  `priority` INT NOT NULL DEFAULT '0',
  PRIMARY KEY (`tutorid`,`templateid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__seminarman_usergroups` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `jm_id` int(10) NOT NULL,
  `sm_id` int(10) NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__seminarman_fields_values_tutors` (
  `tutor_id` int(11) NOT NULL,
  `field_id` int(10) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`tutor_id`,`field_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT IGNORE INTO `#__seminarman_categories` (`id`, `parent_id`, `title`, `alias`, `text`, `meta_keywords`, `meta_description`, `image`, `icon`, `published`, `checked_out`, `checked_out_time`, `access`, `ordering`) VALUES
(1, 0, '1. Klasse (Süd)', '1-klasse-sued', '', '', '', '', '', 1, 0, '0000-00-00 00:00:00', 1, 1);
(2, 0, '1. Klasse (Nord)', '1-klasse-nord', '', '', '', '', '', 1, 0, '0000-00-00 00:00:00', 1, 1);
(3, 0, '2. Klasse', '2-klasse', '', '', '', '', '', 1, 0, '0000-00-00 00:00:00', 1, 1);
(4, 0, '3. Klasse', '3-klasse', '', '', '', '', '', 1, 0, '0000-00-00 00:00:00', 1, 1);
(5, 0, '3. Klasse', '4-klasse', '', '', '', '', '', 1, 0, '0000-00-00 00:00:00', 1, 1);


INSERT IGNORE INTO `#__seminarman_company_type` (`id`, `title`, `alias`, `code`, `description`, `date`, `hits`, `published`, `checked_out`, `checked_out_time`, `ordering`, `archived`, `approved`, `params`) VALUES
(1, 'Default', 'default', 'D', 'Default company type', '2011-05-27 09:58:42', 0, 1, 0, '0000-00-00 00:00:00', 1, 0, 0, '');


INSERT IGNORE INTO `#__seminarman_country` (`id`, `loc`, `code`, `title`, `alias`, `description`, `date`, `hits`, `published`, `language`, `checked_out`, `checked_out_time`, `ordering`, `access`, `params`) VALUES
(158, 'CE', 'DE', 'Germany', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 23, 0, ''),

INSERT IGNORE INTO `#__seminarman_emailtemplate` (`id`, `templatefor`, `title`, `subject`, `body`, `recipient`, `bcc`, `status`, `isdefault`) VALUES
(1, 0, 'Teilnahmebestätigung für Kurstermin', 'Bibelschule: Kurs "{COURSE_TITLE}" in {CATEGORIES} von {COURSE_START_DATE} bis {COURSE_FINISH_DATE}', '<p>Hallo {NAME},<br />du hast dich für diesen Kurs angemeldet:</p>\r\n<table>\r\n<tbody>\r\n<tr>\r\n<td><strong>Kurs</strong>:</td>\r\n<td>{COURSE_TITLE}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Dozent</strong>:</td>\r\n<td>{TUTOR}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Klasse</strong>:</td>\r\n<td>{CATEGORIES}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Ort</strong>:</td>\r\n<td>{COURSE_LOCATION}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Datum</strong>:</td>\r\n<td>{COURSE_START_DATE} - {COURSE_FINISH_DATE}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p> </p>\r\n<table border="1">\r\n<tbody>\r\n<tr>\r\n<td colspan="2">\r\n<p>Um eine optimale Organisation und Verflegung zu ermöglichen, bitte die Teilnahme an dem Kurstermin durch Klicken auf diese Links verbindlich zu bestätigen oder zu stornieren.</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td><a href="{PRESENCE_LINK}">Teilnahme bestätigen</a></td>\r\n<td><a href="{ABSENCE_LINK}">Teilname stornieren</a></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p> </p>\r\n<p>{COURSE_INTROTEXT}</p>\r\n<p> </p>\r\n<p>Weitere Informationen, findest du auf unserer Webseite.</p>\r\n<p> </p>\r\n<p>Gottes Segen,</p>\r\n<p>Bibelschule Stephanus</p>', '{EMAIL}', '{ADMIN_CUSTOM_RECIPIENT}', NULL, 0),
(2, 2, 'Teilnehmerliste für Kurstermin', 'Bibelschule: Teilnehmerliste für Kurs "{COURSE_TITLE}"', '<p>Hallo {NAME},</p>\r\n<p>im Anhang ist die aktuelle Teilnehmerliste für deinen Kurs '{COURSE_TITLE}'.</p>\r\n<p>In der Liste sind alle Stundenten eingetragen, die zu der Klasse gehören, sowie evtl. Studenten aus höheren Klassen, die auch an dem Fach teilnehmen möchten. Außerdem wurden die Studenten aufgefordert derren Teilnahme zu bestätigen bzw. (falls verhindert) zu stornieren. Dieser Status ist in der Liste jeweils pro Student sowie ingesammt aufsummiert (bestätigt/alle) ersichtlich. </p>\r\n<p>Bitte vor dem Unterricht ausdrucken und von den anwesenden Studenten unterschreiben und die Anwesenheit in Stunden eintragen lassen.</p>\r\n<p>Nach dem Kurs bitte die Liste eingescannt (als pdf, tif, jpg oder png) an info@bibelschule-stephanus.de schicken. Die Anwesenheiten werden dann in das System eingepflegt und die Liste archiviert.</p>\r\n<p> </p>\r\n<p>Folgende Informationen über den Kurs haben die Studenten per Email empfangen.</p>\r\n<table>\r\n<tbody>\r\n<tr>\r\n<td><strong>Kurs</strong>:</td>\r\n<td>{COURSE_TITLE}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Dozent</strong>:</td>\r\n<td>{TUTOR}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Klasse</strong>:</td>\r\n<td>{CATEGORIES}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Ort</strong>:</td>\r\n<td>{COURSE_LOCATION}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Datum</strong>:</td>\r\n<td>{COURSE_START_DATE} - {COURSE_FINISH_DATE}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p> </p>\r\n<p>{COURSE_INTROTEXT}</p>\r\n<p> </p>\r\n<p>Gottes Segen,</p>\r\n<p>Bibelschule Stephanus</p>', '{EMAIL}', '{ADMIN_CUSTOM_RECIPIENT}', NULL, 1);
(3, 3, 'Schüler: Kurs abgesagt', 'Bibelschule: ABSAGE des Kurses "{COURSE_TITLE}" von {COURSE_START_DATE} bis {COURSE_FINISH_DATE}', '<p>Hallo {NAME},<br />dieser Kurs wurde abgesagt und findet nicht statt:</p>\r\n<table>\r\n<tbody>\r\n<tr>\r\n<td><strong>Kurs</strong>:</td>\r\n<td>{COURSE_TITLE}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Dozent</strong>:</td>\r\n<td>{TUTOR}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Klasse</strong>:</td>\r\n<td>{CATEGORIES}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Ort</strong>:</td>\r\n<td>{COURSE_LOCATION}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Datum</strong>:</td>\r\n<td>{COURSE_START_DATE} - {COURSE_FINISH_DATE}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><span style="font-size: 13px;">Weitere Informationen, findest du auf unserer Webseite.</span></p>\r\n<p> </p>\r\n<p>Gottes Segen,</p>\r\n<p>Bibelschule Stephanus</p>', '{EMAIL}', '{ADMIN_CUSTOM_RECIPIENT}', NULL, 1);
(4, 4, 'Dozent: Kurs abgesagt', 'Bibelschule: ABSAGE des Kurses "{COURSE_TITLE}" von {COURSE_START_DATE} bis {COURSE_FINISH_DATE}', '<p>Hallo {NAME},<br />dieser Kurs wurde abgesagt und findet nicht statt:</p>\r\n<table>\r\n<tbody>\r\n<tr>\r\n<td><strong>Kurs</strong>:</td>\r\n<td>{COURSE_TITLE}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Dozent</strong>:</td>\r\n<td>{TUTOR}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Klasse</strong>:</td>\r\n<td>{CATEGORIES}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Ort</strong>:</td>\r\n<td>{COURSE_LOCATION}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Datum</strong>:</td>\r\n<td>{COURSE_START_DATE} - {COURSE_FINISH_DATE}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><span style="font-size: 13px;">Weitere Informationen, findest du auf unserer Webseite.</span></p>\r\n<p> </p>\r\n<p>Gottes Segen,</p>\r\n<p>Bibelschule Stephanus</p>', '{EMAIL}', '{ADMIN_CUSTOM_RECIPIENT}', NULL, 1);

INSERT IGNORE INTO `#__seminarman_tags` (`id`, `name`, `alias`, `published`, `checked_out`, `checked_out_time`) VALUES
(7, 'Default tag', 'default-tag', 1, 0, '0000-00-00 00:00:00');

INSERT IGNORE INTO `#__seminarman_fields` (`id`, `type`, `purpose`, `ordering`, `published`, `min`, `max`, `name`, `tips`, `visible`, `required`, `searchable`, `registration`, `options`, `fieldcode`, `paypalcode`) VALUES

INSERT IGNORE INTO `#__seminarman_pdftemplate` (`id`, `templatefor`, `name`, `html`, `srcpdf`, `isdefault`, `margin_left`, `margin_right`, `margin_top`, `margin_bottom`, `paperformat`, `orientation`) VALUES
(1, 0, 'Rechnungsvorlage', '<p>{CUSTOM_COMPANY}<br />{TITLE}{FIRSTNAME} {LASTNAME}<br /> {CUSTOM_STREET}<br /> {CUSTOM_ZIP} {CUSTOM_CITY}</p>\r\n<p> </p>\r\n<p> </p>\r\n<p style="text-align: right;"><strong>Datum</strong> {INVOICE_DATE}</p>\r\n<p> </p>\r\n<p><span style="font-size: x-large;"><strong>Ihre Rechnung {INVOICE_NUMBER}</strong></span></p>\r\n<p> </p>\r\n<table style="width: 100%; font-size: small;" border="1" cellpadding="5" align="center">\r\n<tbody>\r\n<tr>\r\n<td style="width: 10%; text-align: left;">Pos.</td>\r\n<td style="width: 10%; text-align: left;">Menge</td>\r\n<td style="width: 50%; text-align: left;">Text</td>\r\n<td style="width: 15%; text-align: right;">Einzelpreis EUR</td>\r\n<td style="width: 15%; text-align: right;">Gesamtpreis EUR</td>\r\n</tr>\r\n<tr>\r\n<td style="text-align: left;">1</td>\r\n<td style="text-align: left;">{ATTENDEES}</td>\r\n<td style="text-align: left;">{COURSE_TITLE}<br />Kursnummer: {COURSE_CODE}<br />Vom {COURSE_START_DATE} bis {COURSE_FINISH_DATE} in {COURSE_LOCATION}</td>\r\n<td style="text-align: right;">{PRICE_PER_ATTENDEE}</td>\r\n<td style="text-align: right;">{PRICE_TOTAL}</td>\r\n</tr>\r\n<tr>\r\n<td style="text-align: right;" colspan="3">Gesamt Netto</td>\r\n<td style="text-align: right;" colspan="2">{PRICE_TOTAL}</td>\r\n</tr>\r\n<tr>\r\n<td style="text-align: right;" colspan="3">zzgl. {PRICE_VAT_PERCENT}% MwSt.</td>\r\n<td style="text-align: right;" colspan="2">{PRICE_VAT} </td>\r\n</tr>\r\n<tr>\r\n<td style="text-align: right;" colspan="3"><strong>Gesamtbetrag</strong></td>\r\n<td style="text-align: right;" colspan="2"><strong>{PRICE_TOTAL_VAT}</strong></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p> </p>\r\n<p>Der Betrag muss 3 Werktage vor Kursbeginn auf unser Konto eingegangen sein.</p>\r\n<p></p>', '', 1, 20, 20, 70, 20, 'A4', 'P'),
(2, 0, 'Test aller Felder', '<ul>\r\n<li>{INVOICE_NUMBER}: Rechnungsnummer</li>\r\n<li>{INVOICE_DATE}: Rechnungsdatum</li>\r\n<li>{ATTENDEES}: Anzahl Teilnehmer</li>\r\n<li>{SALUTATION}: Anrede</li>\r\n<li>{TITLE}: Titel</li>\r\n<li>{FIRSTNAME}: Vorname</li>\r\n<li>{LASTNAME}: Nachname</li>\r\n<li>{EMAIL}: E-Mail</li>\r\n<li>{CUSTOM_COMPANY}: Firma/Organisation</li>\r\n<li>{CUSTOM_STREET}: Strasse</li>\r\n<li>{CUSTOM_ZIP}: PLZ</li>\r\n<li>{CUSTOM_CITY}: Ort</li>\r\n<li>{CUSTOM_COUNTRY}: Land</li>\r\n<li>{CUSTOM_PHONE}: Telefon</li>\r\n<li>{COURSE_TITLE}: Kurstitel</li>\r\n<li>{COURSE_CODE}: Kursnr.</li>\r\n<li>{COURSE_CAPACITY}: Kapazität</li>\r\n<li>{COURSE_LOCATION}: Ort</li>\r\n<li>{COURSE_URL}: URL</li>\r\n<li>{COURSE_START_DATE}: Beginn</li>\r\n<li>{COURSE_FINISH_DATE}: Ende</li>\r\n<li>{PRICE_PER_ATTENDEE}: Preis pro Teilnehmer</li>\r\n<li>{PRICE_PER_ATTENDEE_VAT}: Preis pro Teilnehmer inkl. Steuern</li>\r\n<li>{PRICE_TOTAL}: Gesamtpreis</li>\r\n<li>{PRICE_TOTAL_VAT}: Gesamtpreis inkl. Steuern</li>\r\n<li>{PRICE_VAT_PERCENT}: Mwst. Satz</li>\r\n<li>{PRICE_VAT}: Mwst. Betrag</li>\r\n<li>{TUTOR_FIRSTNAME}: Vorname</li>\r\n<li>{TUTOR_LASTNAME}: Nachname</li>\r\n<li>{GROUP}: Gruppe</li>\r\n<li>{EXPERIENCE_LEVEL}: Erfahrungslevel</li>\r\n</ul>', '', 0, 0, 0, 0, 0, 'A4', 'P'),
(4, 1, 'Teilnehmerliste 1', '<table style="width: 100%;" cellspacing="0" cellpadding="0">\r\n<tbody>\r\n<tr>\r\n<td style="width: 7%;"><strong>Kurs</strong></td>\r\n<td style="width: 28%;">{COURSE_TITLE}</td>\r\n<td style="width: 7%;"><strong>Ort</strong></td>\r\n<td style="width: 29%;">{COURSE_LOCATION}</td>\r\n<td style="width: 10%;"><strong>Dozent</strong></td>\r\n<td style="width: 20%;">{TUTOR}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Datum</strong></td>\r\n<td>{COURSE_START_DATE} - {COURSE_FINISH_DATE}</td>\r\n<td><strong>Klasse</strong></td>\r\n<td>{CATEGORIES}</td>\r\n<td><strong>Stand</strong></td>\r\n<td>{CURRENT_DATE} {ATTENDEES_STATUS_1}/{ATTENDEES}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table style="width: 100%;" border="1">\r\n<tbody>\r\n<tr><th style="width: 20%; text-align: left;"><span style="color: #000080;"><strong>Name</strong></span></th><th style="width: 20%; text-align: left;"><span style="color: #000080;"><strong>Vorname</strong></span></th><th style="width: 20%; text-align: left;"><span style="color: #000080;"><strong>Teilnahmestatus</strong></span></th><th style="width: 40%; text-align: left;" colspan="2"><span style="color: #000080;"><strong>Anwenheit in Std./Unterschrift</strong></span></th></tr>\r\n<tr class="{LOOP}">\r\n<td style="text-align: left;">{LASTNAME}</td>\r\n<td style="text-align: left;">{FIRSTNAME}</td>\r\n<td style="text-align: left;">{STATUS}</td>\r\n<td style="width: 10%; text-align: left;">{ANWESENHEIT}</td>\r\n<td style="width: 30%;"> </td>\r\n</tr>\r\n</tbody>\r\n</table>', '', 1, 10, 10, 15, 15, 'A4', 'P');

INSERT IGNORE INTO `#__seminarman_experience_level` (`id`, `title`, `alias`, `code`, `color`, `description`, `date`, `hits`, `published`, `checked_out`, `checked_out_time`, `ordering`, `archived`, `approved`, `params`) VALUES

INSERT IGNORE INTO `#__seminarman_atgroup` (`id`, `title`, `alias`, `code`, `color`, `description`, `date`, `hits`, `published`, `checked_out`, `checked_out_time`, `ordering`, `archived`, `approved`, `params`) VALUES

INSERT IGNORE INTO `#__seminarman_pricegroups` (`id`, `gid`, `jm_groups`, `reg_group`, `title`, `calc_mathop`, `calc_value`) VALUES  (1, 2, '', 0, 'Price 2', '-%', 0), (2, 3, '', 0, 'Price 3', '-%', 0), (3, 4, '', 0, 'Price 4', '-%', 0),  (4, 5, '', 0, 'Price 5', '-%', 0);

INSERT IGNORE INTO `#__seminarman_usergroups` (`id`, `jm_id`, `sm_id`, `title`) VALUES  (1, 0, 1, 'Seminar Manager'),  (2, 0, 2, 'Seminar Trainer');
