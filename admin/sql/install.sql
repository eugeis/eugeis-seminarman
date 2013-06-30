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
  `note_reading` varchar(3),
  `note_test` varchar(3),
  `note_work` varchar(3),
  `note` varchar(3),
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

INSERT IGNORE INTO `#__seminarman_country` (`id`, `loc`, `code`, `title`, `alias`, `description`, `date`, `hits`, `published`, `language`, `checked_out`, `checked_out_time`, `ordering`, `access`, `params`) VALUES
(3, 'AF', 'BF', 'Burkina Faso', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 151, 0, ''),
(5, 'AF', 'CM', 'Cameroon', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 137, 0, ''),
(6, 'AF', 'CV', 'Cape Verde', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 136, 0, ''),
(7, 'AF', 'CF', 'Central African Republic', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 135, 0, ''),
(8, 'AF', 'TD', 'Chad', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 134, 0, ''),
(9, 'AF', 'KM', 'Comoros', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 133, 0, ''),
(10, 'AF', 'CG', 'Congo', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 132, 0, ''),
(12, 'AF', 'BJ', 'Benin', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 131, 0, ''),
(14, 'AF', 'BI', 'Burundi', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 130, 0, ''),
(21, 'AF', 'CI', 'Cote Divorie', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 129, 0, ''),
(22, 'AF', 'DJ', 'Djibouti', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 128, 0, ''),
(23, 'AF', 'GQ', 'Equatorial Guinea', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 138, 0, ''),
(24, 'AF', 'ER', 'Eritrea', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 139, 0, ''),
(25, 'AF', 'ET', 'Ethiopia', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 140, 0, ''),
(26, 'AF', 'EG', 'Egypt', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 150, 0, ''),
(27, 'AF', 'GA', 'Gabon', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 149, 0, ''),
(28, 'AF', 'GH', 'Ghana', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 148, 0, ''),
(29, 'AF', 'GN', 'Guinea', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 147, 0, ''),
(30, 'AF', 'GM', 'Gambia', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 146, 0, ''),
(31, 'AF', 'GW', 'Guinea-Bissau', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 145, 0, ''),
(32, 'AF', 'KE', 'Kenya', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 144, 0, ''),
(33, 'AF', 'LS', 'Lesotho', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 143, 0, ''),
(34, 'AF', 'LR', 'Liberia', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 142, 0, ''),
(35, 'AF', 'MG', 'Madagascar', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 141, 0, ''),
(36, 'AF', 'ML', 'Mali', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 127, 0, ''),
(37, 'AF', 'MR', 'Mauritania', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 126, 0, ''),
(38, 'AF', 'YT', 'Mayotte', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 112, 0, ''),
(39, 'AF', 'MA', 'Morocco', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 111, 0, ''),
(40, 'AF', 'MZ', 'Mozambique', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 110, 0, ''),
(41, 'AF', 'MW', 'Malawi', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 109, 0, ''),
(42, 'AF', 'NA', 'Namibia', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 108, 0, ''),
(43, 'AF', 'NE', 'Niger', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 107, 0, ''),
(44, 'AF', 'NG', 'Nigeria', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 106, 0, ''),
(45, 'AF', 'RE', 'Reunion', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 105, 0, ''),
(46, 'AF', 'SH', 'St. Helena', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 104, 0, ''),
(47, 'AF', 'ST', 'Sao Tome and Principe', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 103, 0, ''),
(48, 'AF', 'SN', 'Senegal', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 113, 0, ''),
(49, 'AF', 'SL', 'Sierra Leone', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 114, 0, ''),
(50, 'AF', 'SO', 'Somalia', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 115, 0, ''),
(51, 'AF', 'ZA', 'South Africa', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 125, 0, ''),
(52, 'AF', 'SD', 'Sudan', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 124, 0, ''),
(53, 'AF', 'SZ', 'Swaziland', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 123, 0, ''),
(54, 'AF', 'TZ', 'Tanzania', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 122, 0, ''),
(55, 'AF', 'TG', 'Togo', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 121, 0, ''),
(56, 'AF', 'UG', 'Uganda', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 120, 0, ''),
(57, 'AF', 'EH', 'Western Sahara', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 119, 0, ''),
(58, 'AF', 'ZR', 'Zaire', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 118, 0, ''),
(59, 'AF', 'ZM', 'Zambia', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 117, 0, ''),
(60, 'AF', 'ZW', 'Zimbabwe', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 116, 0, ''),
(62, 'AS', 'AF', 'Afghanistan', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 102, 0, ''),
(63, 'AS', 'BD', 'Bangladesh', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 152, 0, ''),
(64, 'AS', 'BT', 'Bhutan', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 202, 0, ''),
(65, 'AS', 'BN', 'Brunei', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 188, 0, ''),
(66, 'AS', 'KH', 'Cambodia', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 187, 0, ''),
(67, 'AS', 'CN', 'China', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 186, 0, ''),
(68, 'AS', 'HK', 'Hong Kong', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 185, 0, ''),
(69, 'AS', 'IN', 'India', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 184, 0, ''),
(70, 'AS', 'ID', 'Indonesia', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 183, 0, ''),
(71, 'AS', 'JP', 'Japan', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 182, 0, ''),
(72, 'AS', 'KZ', 'Kazakhstan', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 181, 0, ''),
(73, 'AS', 'KG', 'Kyrgyzstan', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 180, 0, ''),
(74, 'AS', 'LA', 'Laos', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 179, 0, ''),
(75, 'AS', 'MO', 'Macau', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 189, 0, ''),
(76, 'AS', 'MY', 'Malaysia', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 190, 0, ''),
(77, 'AS', 'MV', 'Maldives', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 191, 0, ''),
(78, 'AS', 'MN', 'Mongolia', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 201, 0, ''),
(79, 'AS', 'NP', 'Nepal', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 200, 0, ''),
(80, 'AS', 'PK', 'Pakistan', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 199, 0, ''),
(81, 'AS', 'PH', 'Philippines', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 198, 0, ''),
(82, 'AS', 'KR', 'Republic of Korea', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 197, 0, ''),
(83, 'AS', 'RU', 'Russia', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 196, 0, ''),
(84, 'AS', 'SC', 'Seychelles', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 195, 0, ''),
(85, 'AS', 'SG', 'Singapore', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 194, 0, ''),
(86, 'AS', 'LK', 'Sri Lanka', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 193, 0, ''),
(87, 'AS', 'TW', 'Taiwan', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 192, 0, ''),
(88, 'AS', 'TJ', 'Tajikistan', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 178, 0, ''),
(89, 'AS', 'TH', 'Thailand', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 177, 0, ''),
(90, 'AS', 'TM', 'Turkmenistan', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 163, 0, ''),
(91, 'AS', 'UZ', 'Uzbekistan', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 162, 0, ''),
(92, 'AS', 'VN', 'Vietnam', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 161, 0, ''),
(94, 'AU', 'AU', 'Australia', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 160, 0, ''),
(95, 'AU', 'FM', 'Federated States of Micronesia', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 159, 0, ''),
(96, 'AU', 'FJ', 'Fiji', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 158, 0, ''),
(97, 'AU', 'PF', 'French Polynesia', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 157, 0, ''),
(98, 'AU', 'GU', 'Guam', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 156, 0, ''),
(99, 'AU', 'KI', 'Kiribati', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 155, 0, ''),
(100, 'AU', 'MH', 'Marshall Islands', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 154, 0, ''),
(101, 'AU', 'NR', 'Nauru', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 164, 0, ''),
(102, 'AU', 'NC', 'New Caledonia', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 165, 0, ''),
(103, 'AU', 'NZ', 'New Zealand', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 166, 0, ''),
(104, 'AU', 'MP', 'Northern Mariana Islands', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 176, 0, ''),
(105, 'AU', 'PW', 'Palau', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 175, 0, ''),
(106, 'AU', 'PG', 'Papua New Guinea', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 174, 0, ''),
(107, 'AU', 'PN', 'Pitcairn', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 173, 0, ''),
(108, 'AU', 'SB', 'Solomon Islands', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 172, 0, ''),
(109, 'AU', 'TO', 'Tonga', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 171, 0, ''),
(110, 'AU', 'TV', 'Tuvalu', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 170, 0, ''),
(111, 'AU', 'VU', 'Vanuatu', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 169, 0, ''),
(112, 'CA', 'AI', 'Anguilla', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 168, 0, ''),
(114, 'CA', 'AW', 'Aruba', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 167, 0, ''),
(115, 'CA', 'BS', 'Bahamas', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 153, 0, ''),
(116, 'CA', 'BB', 'Barbados', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 50, 0, ''),
(117, 'CA', 'BM', 'Bermuda', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 36, 0, ''),
(118, 'CA', 'VI', 'British Virgin Islands', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 35, 0, ''),
(119, 'CA', 'KY', 'Cayman Islands', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 34, 0, ''),
(120, 'CA', 'DM', 'Dominica', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 33, 0, ''),
(121, 'CA', 'DO', 'Dominican Republic', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 32, 0, ''),
(122, 'CA', 'GD', 'Grenada', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 31, 0, ''),
(123, 'CA', 'GP', 'Guadeloupe', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 30, 0, ''),
(124, 'CA', 'HT', 'Haiti', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 29, 0, ''),
(125, 'CA', 'JM', 'Jamaica', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 28, 0, ''),
(126, 'CA', 'MQ', 'Martinique', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 27, 0, ''),
(127, 'CA', 'AN', 'Neterlands Antilles', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 37, 0, ''),
(128, 'CA', 'PR', 'Puerto Rico', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 38, 0, ''),
(129, 'CA', 'KN', 'St. Kitts and Nevis', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 39, 0, ''),
(130, 'CA', 'LC', 'St. Lucia', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 49, 0, ''),
(131, 'CA', 'VC', 'St. Vincent and the Grenadines', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 48, 0, ''),
(132, 'CA', 'TT', 'Trinidad and Tobago', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 47, 0, ''),
(133, 'CA', 'TC', 'Turks and Caicos Islands', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 46, 0, ''),
(134, 'CE', 'BZ', 'Belize', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 45, 0, ''),
(135, 'CE', 'CR', 'Costa Rica', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 44, 0, ''),
(136, 'CE', 'SV', 'El Salvador', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 43, 0, ''),
(137, 'CE', 'GT', 'Guatemala', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 42, 0, ''),
(138, 'CE', 'HN', 'Honduras', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 41, 0, ''),
(139, 'CE', 'NI', 'Nicaragua', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 40, 0, ''),
(140, 'CE', 'PA', 'Panama', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 26, 0, ''),
(143, 'CE', 'AM', 'Armenia', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 25, 0, ''),
(144, 'CE', 'AT', 'Austria', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 11, 0, ''),
(145, 'CE', 'AZ', 'Azerbaijan', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 10, 0, ''),
(146, 'CE', 'BY', 'Belarus', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 9, 0, ''),
(147, 'CE', 'BE', 'Belgium', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 8, 0, ''),
(148, 'CE', 'BG', 'Bulgaria', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 7, 0, ''),
(149, 'CE', 'HR', 'Croatia', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 6, 0, ''),
(150, 'CE', 'CY', 'Cyprus', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 5, 0, ''),
(151, 'CE', 'CZ', 'Czech Republic', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 4, 0, ''),
(152, 'CE', 'DK', 'Denmark', 'denmark', '', '2009-11-22 02:08:00', 0, 1, '', 0, '0000-00-00 00:00:00', 3, 0, ''),
(153, 'CE', 'EE', 'Estonia', 'estonia', '', '2009-11-22 02:08:09', 0, 1, '', 0, '0000-00-00 00:00:00', 2, 0, ''),
(154, 'CE', 'FO', 'Faroe Islands', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 12, 0, ''),
(155, 'CE', 'FI', 'Finland', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 13, 0, ''),
(156, 'CE', 'FR', 'France', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 14, 0, ''),
(157, 'CE', 'GE', 'Georgia', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 24, 0, ''),
(158, 'CE', 'DE', 'Germany', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 23, 0, ''),
(159, 'CE', 'GI', 'Gibraltar', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 22, 0, ''),
(160, 'CE', 'GR', 'Greece', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 21, 0, ''),
(161, 'CE', 'GL', 'Greenland', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 20, 0, ''),
(162, 'CE', 'HU', 'Hungary', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 19, 0, ''),
(163, 'CE', 'IS', 'Iceland', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 18, 0, ''),
(164, 'CE', 'IE', 'Ireland', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 17, 0, ''),
(165, 'CE', 'IT', 'Italy', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 16, 0, ''),
(166, 'CE', 'LV', 'Latvia', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 15, 0, ''),
(167, 'CE', 'LI', 'Liechtenstein', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 1, 0, ''),
(168, 'CE', 'LT', 'Lithuania', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 51, 0, ''),
(169, 'CE', 'LU', 'Luxembourg', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 101, 0, ''),
(170, 'CE', 'MT', 'Malta', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 87, 0, ''),
(171, 'CE', 'FX', 'Metropolitan France', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 86, 0, ''),
(172, 'CE', 'MD', 'Moldova', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 85, 0, ''),
(173, 'CE', 'NL', 'Netherlands', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 84, 0, ''),
(174, 'CE', 'NO', 'Norway', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 83, 0, ''),
(175, 'CE', 'PL', 'Poland', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 82, 0, ''),
(176, 'CE', 'PT', 'Portugal', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 81, 0, ''),
(177, 'CE', 'RO', 'Romania', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 80, 0, ''),
(178, 'CE', 'SK', 'Slovakia', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 79, 0, ''),
(179, 'CE', 'SI', 'Slovenia', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 78, 0, ''),
(180, 'CE', 'ES', 'Spain', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 88, 0, ''),
(181, 'CE', 'SJ', 'Svalbard and Jan Mayen Islands', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 89, 0, ''),
(182, 'CE', 'SE', 'Sweden', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 90, 0, ''),
(183, 'CE', 'CH', 'Switzerland', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 100, 0, ''),
(184, 'CE', 'MK', 'Republic of Macedonia', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 99, 0, ''),
(185, 'CE', 'TR', 'Turkey', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 98, 0, ''),
(186, 'CE', 'UA', 'Ukraine', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 97, 0, ''),
(187, 'CE', 'GB', 'United Kingdom', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 96, 0, ''),
(188, 'CE', 'VA', 'Vatican City', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 95, 0, ''),
(189, 'CE', 'YU', 'Yugoslavia', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 94, 0, ''),
(190, 'ME', 'IL', 'Israel', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 93, 0, ''),
(191, 'ME', 'JO', 'Jordan', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 92, 0, ''),
(192, 'ME', 'KW', 'Kuwait', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 91, 0, ''),
(193, 'ME', 'LB', 'Lebanon', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 77, 0, ''),
(194, 'ME', 'OM', 'Oman', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 76, 0, ''),
(195, 'ME', 'QA', 'Qatar', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 62, 0, ''),
(196, 'ME', 'SA', 'Saudi Arabia', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 61, 0, ''),
(197, 'ME', 'SY', 'Syria', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 60, 0, ''),
(198, 'ME', 'AE', 'United Arab Emirates', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 59, 0, ''),
(199, 'ME', 'YE', 'Yemen', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 58, 0, ''),
(200, 'NA', 'CA', 'Canada', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 57, 0, ''),
(201, 'NA', 'MX', 'Mexico', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 56, 0, ''),
(202, 'NA', 'US', 'United States', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 55, 0, ''),
(204, 'SA', 'BO', 'Bolivia', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 54, 0, ''),
(205, 'SA', 'BR', 'Brazil', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 53, 0, ''),
(206, 'SA', 'CL', 'Chile', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 63, 0, ''),
(207, 'SA', 'CO', 'Colombia', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 64, 0, ''),
(208, 'SA', 'EC', 'Equador', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 65, 0, ''),
(209, 'SA', 'FK', 'Falkland Islands', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 75, 0, ''),
(210, 'SA', 'GF', 'French Guiana', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 74, 0, ''),
(211, 'SA', 'GY', 'Guyana', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 73, 0, ''),
(212, 'SA', 'PY', 'Paraguay', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 72, 0, ''),
(213, 'SA', 'PE', 'Peru', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 71, 0, ''),
(214, 'SA', 'SR', 'Suriname', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 70, 0, ''),
(215, 'SA', 'UY', 'Uruguay', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 69, 0, ''),
(216, 'SA', 'VE', 'Venezuela', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 68, 0, ''),
(217, 'OT', 'BH', 'Bahrain', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 67, 0, ''),
(218, 'OT', 'BV', 'Bouvet Islands', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 66, 0, ''),
(219, 'OT', 'IO', 'British Indian Ocean Territory', '', NULL, '0000-00-00 00:00:00', 0, 1, '', 0, '0000-00-00 00:00:00', 52, 0, ''),
(222, '', '', 'Zakladka', 'zakladka', '', '2009-11-22 17:31:14', 0, 1, '', 0, '0000-00-00 00:00:00', 203, 0, '');

INSERT IGNORE INTO `#__seminarman_categories` (`id`, `parent_id`, `title`, `alias`, `text`, `meta_keywords`, `meta_description`, `image`, `icon`, `published`, `checked_out`, `checked_out_time`, `access`, `ordering`) VALUES
(1, 0, 'Default', 'default', '', '', '', '', '', 1, 962, '0000-00-00 00:00:00', 1, 1),
(2, 0, '1. Klasse (Süd)', '1-klasse-sued', '', '', '', '', '', 1, 962, '0000-00-00 00:00:00', 1, 1),
(3, 0, '1. Klasse (Nord)', '1-klasse-nord', '', '', '', '', '', 1, 0, '0000-00-00 00:00:00', 1, 2),
(4, 0, '2. Klasse', '2-klasse', '', '', '', '', '', 1, 0, '0000-00-00 00:00:00', 1, 3),
(5, 0, '3. Klasse', '3-klasse', '', '', '', '', '', 1, 0, '0000-00-00 00:00:00', 1, 4),
(6, 0, '4. Klasse', '4-klasse', '', '', '', '', '', 1, 0, '0000-00-00 00:00:00', 1, 5);

INSERT IGNORE INTO `#__seminarman_company_type` (`id`, `title`, `alias`, `code`, `description`, `date`, `hits`, `published`, `checked_out`, `checked_out_time`, `ordering`, `archived`, `approved`, `params`) VALUES
(1, 'Default', 'default', 'D', 'Default company type', '2011-05-27 09:58:42', 0, 1, 0, '0000-00-00 00:00:00', 1, 0, 0, '');

INSERT IGNORE INTO `#__seminarman_tags` (`id`, `name`, `alias`, `published`, `checked_out`, `checked_out_time`) VALUES
(7, 'Default tag', 'default-tag', 1, 0, '0000-00-00 00:00:00');

INSERT IGNORE INTO `#__seminarman_pricegroups` (`id`, `gid`, `jm_groups`, `reg_group`, `title`, `calc_mathop`, `calc_value`) VALUES  (1, 2, '', 0, 'Price 2', '-%', 0), (2, 3, '', 0, 'Price 3', '-%', 0), (3, 4, '', 0, 'Price 4', '-%', 0),  (4, 5, '', 0, 'Price 5', '-%', 0);

INSERT IGNORE INTO `#__seminarman_usergroups` (`id`, `jm_id`, `sm_id`, `title`) VALUES  (1, 0, 1, 'Seminar Manager'),  (2, 0, 2, 'Seminar Trainer');

INSERT IGNORE INTO `#__seminarman_pdftemplate` (`id`, `templatefor`, `name`, `html`, `srcpdf`, `isdefault`, `margin_left`, `margin_right`, `margin_top`, `margin_bottom`, `paperformat`, `orientation`) VALUES
(1, 0, 'Rechnungsvorlage', '<p>{CUSTOM_COMPANY}<br />{TITLE}{FIRSTNAME} {LASTNAME}<br /> {CUSTOM_STREET}<br /> {CUSTOM_ZIP} {CUSTOM_CITY}</p>\r\n<p> </p>\r\n<p> </p>\r\n<p style="text-align: right;"><strong>Datum</strong> {INVOICE_DATE}</p>\r\n<p> </p>\r\n<p><span style="font-size: x-large;"><strong>Ihre Rechnung {INVOICE_NUMBER}</strong></span></p>\r\n<p> </p>\r\n<table style="width: 100%; font-size: small;" border="1" cellpadding="5" align="center">\r\n<tbody>\r\n<tr>\r\n<td style="width: 10%; text-align: left;">Pos.</td>\r\n<td style="width: 10%; text-align: left;">Menge</td>\r\n<td style="width: 50%; text-align: left;">Text</td>\r\n<td style="width: 15%; text-align: right;">Einzelpreis EUR</td>\r\n<td style="width: 15%; text-align: right;">Gesamtpreis EUR</td>\r\n</tr>\r\n<tr>\r\n<td style="text-align: left;">1</td>\r\n<td style="text-align: left;">{ATTENDEES}</td>\r\n<td style="text-align: left;">{COURSE_TITLE}<br />Kursnummer: {COURSE_CODE}<br />Vom {COURSE_START_DATE} bis {COURSE_FINISH_DATE} in {COURSE_LOCATION}</td>\r\n<td style="text-align: right;">{PRICE_PER_ATTENDEE}</td>\r\n<td style="text-align: right;">{PRICE_TOTAL}</td>\r\n</tr>\r\n<tr>\r\n<td style="text-align: right;" colspan="3">Gesamt Netto</td>\r\n<td style="text-align: right;" colspan="2">{PRICE_TOTAL}</td>\r\n</tr>\r\n<tr>\r\n<td style="text-align: right;" colspan="3">zzgl. {PRICE_VAT_PERCENT}% MwSt.</td>\r\n<td style="text-align: right;" colspan="2">{PRICE_VAT} </td>\r\n</tr>\r\n<tr>\r\n<td style="text-align: right;" colspan="3"><strong>Gesamtbetrag</strong></td>\r\n<td style="text-align: right;" colspan="2"><strong>{PRICE_TOTAL_VAT}</strong></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p> </p>\r\n<p>Der Betrag muss 3 Werktage vor Kursbeginn auf unser Konto eingegangen sein.</p>\r\n<p></p>', '', 1, 20, 20, 70, 20, 'A4', 'P'),
(2, 0, 'Test aller Felder', '<ul>\r\n<li>{INVOICE_NUMBER}: Rechnungsnummer</li>\r\n<li>{INVOICE_DATE}: Rechnungsdatum</li>\r\n<li>{ATTENDEES}: Anzahl Teilnehmer</li>\r\n<li>{SALUTATION}: Anrede</li>\r\n<li>{TITLE}: Titel</li>\r\n<li>{FIRSTNAME}: Vorname</li>\r\n<li>{LASTNAME}: Nachname</li>\r\n<li>{EMAIL}: E-Mail</li>\r\n<li>{CUSTOM_COMPANY}: Firma/Organisation</li>\r\n<li>{CUSTOM_STREET}: Strasse</li>\r\n<li>{CUSTOM_ZIP}: PLZ</li>\r\n<li>{CUSTOM_CITY}: Ort</li>\r\n<li>{CUSTOM_COUNTRY}: Land</li>\r\n<li>{CUSTOM_PHONE}: Telefon</li>\r\n<li>{COURSE_TITLE}: Kurstitel</li>\r\n<li>{COURSE_CODE}: Kursnr.</li>\r\n<li>{COURSE_CAPACITY}: Kapazität</li>\r\n<li>{COURSE_LOCATION}: Ort</li>\r\n<li>{COURSE_URL}: URL</li>\r\n<li>{COURSE_START_DATE}: Beginn</li>\r\n<li>{COURSE_FINISH_DATE}: Ende</li>\r\n<li>{PRICE_PER_ATTENDEE}: Preis pro Teilnehmer</li>\r\n<li>{PRICE_PER_ATTENDEE_VAT}: Preis pro Teilnehmer inkl. Steuern</li>\r\n<li>{PRICE_TOTAL}: Gesamtpreis</li>\r\n<li>{PRICE_TOTAL_VAT}: Gesamtpreis inkl. Steuern</li>\r\n<li>{PRICE_VAT_PERCENT}: Mwst. Satz</li>\r\n<li>{PRICE_VAT}: Mwst. Betrag</li>\r\n<li>{TUTOR_FIRSTNAME}: Vorname</li>\r\n<li>{TUTOR_LASTNAME}: Nachname</li>\r\n<li>{GROUP}: Gruppe</li>\r\n<li>{EXPERIENCE_LEVEL}: Erfahrungslevel</li>\r\n</ul>', '', 0, 0, 0, 0, 0, 'A4', 'P'),
(4, 1, 'Teilnehmerliste 1', '<table style="width: 100%;" cellspacing="0" cellpadding="0">\r\n<tbody>\r\n<tr>\r\n<td style="width: 7%;"><strong>Kurs</strong></td>\r\n<td style="width: 28%;">{COURSE_TITLE}</td>\r\n<td style="width: 7%;"><strong>Ort</strong></td>\r\n<td style="width: 29%;">{COURSE_LOCATION}</td>\r\n<td style="width: 10%;"><strong>Dozent</strong></td>\r\n<td style="width: 20%;">{TUTOR}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Datum</strong></td>\r\n<td>{COURSE_START_DATE} - {COURSE_FINISH_DATE}</td>\r\n<td><strong>Klasse</strong></td>\r\n<td>{CATEGORIES}</td>\r\n<td><strong>Stand</strong></td>\r\n<td>{CURRENT_DATE} {ATTENDEES_STATUS_1}/{ATTENDEES}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table style="width: 100%;" border="1">\r\n<tbody>\r\n<tr><th style="width: 20%; text-align: left;"><span style="color: #000080;"><strong>Name</strong></span></th><th style="width: 20%; text-align: left;"><span style="color: #000080;"><strong>Vorname</strong></span></th><th style="width: 20%; text-align: left;"><span style="color: #000080;"><strong>Teilnahmestatus</strong></span></th><th style="width: 40%; text-align: left;" colspan="2"><span style="color: #000080;"><strong>Anwenheit in Std./Unterschrift</strong></span></th></tr>\r\n<tr class="{LOOP}">\r\n<td style="text-align: left;">{LASTNAME}</td>\r\n<td style="text-align: left;">{FIRSTNAME}</td>\r\n<td style="text-align: left;">{STATUS}</td>\r\n<td style="width: 10%; text-align: left;">{ANWESENHEIT}</td>\r\n<td style="width: 30%;"> </td>\r\n</tr>\r\n</tbody>\r\n</table>', '', 1, 10, 10, 15, 15, 'A4', 'P');

INSERT IGNORE INTO `#__seminarman_emailtemplate` (`id`, `templatefor`, `title`, `subject`, `body`, `recipient`, `bcc`, `status`, `isdefault`) VALUES
(1, 0, 'Teilnahmebestätigung für Kurstermin', 'Bibelschule: Kurs "{COURSE_TITLE}" in {CATEGORIES} von {COURSE_START_DATE} bis {COURSE_FINISH_DATE}', '<p>Hallo {NAME},<br />du hast dich für diesen Kurs angemeldet:</p>\r\n<table>\r\n<tbody>\r\n<tr>\r\n<td><strong>Kurs</strong>:</td>\r\n<td>{COURSE_TITLE}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Dozent</strong>:</td>\r\n<td>{TUTOR}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Klasse</strong>:</td>\r\n<td>{CATEGORIES}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Ort</strong>:</td>\r\n<td>{COURSE_LOCATION}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Datum</strong>:</td>\r\n<td>{COURSE_START_DATE} - {COURSE_FINISH_DATE}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p> </p>\r\n<table border="1">\r\n<tbody>\r\n<tr>\r\n<td colspan="2">\r\n<p>Um eine optimale Organisation und Verflegung zu ermöglichen, bitte die Teilnahme an dem Kurstermin durch Klicken auf diese Links verbindlich zu bestätigen oder zu stornieren.</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td><a href="{PRESENCE_LINK}">Teilnahme bestätigen</a></td>\r\n<td><a href="{ABSENCE_LINK}">Teilname stornieren</a></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p> </p>\r\n<p>{COURSE_INTROTEXT}</p>\r\n<p> </p>\r\n<p>Weitere Informationen, findest du auf unserer Webseite.</p>\r\n<p> </p>\r\n<p>Gottes Segen,</p>\r\n<p>Bibelschule Stephanus</p>', '{EMAIL}', '{ADMIN_CUSTOM_RECIPIENT}', NULL, 1),
(2, 2, 'Teilnehmerliste für Kurstermin', 'Bibelschule: Teilnehmerliste für Kurs "{COURSE_TITLE}"', '<p>Hallo {NAME},</p>\r\n<p>im Anhang ist die aktuelle Teilnehmerliste für deinen Kurs "{COURSE_TITLE}".</p>\r\n<p>In der Liste sind alle Stundenten eingetragen, die zu der Klasse gehören, sowie evtl. Studenten aus höheren Klassen, die auch an dem Fach teilnehmen möchten. Außerdem wurden die Studenten aufgefordert derren Teilnahme zu bestätigen bzw. (falls verhindert) zu stornieren. Dieser Status ist in der Liste jeweils pro Student sowie ingesammt aufsummiert (bestätigt/alle) ersichtlich. </p>\r\n<p>Bitte vor dem Unterricht ausdrucken und von den anwesenden Studenten unterschreiben und die Anwesenheit in Stunden eintragen lassen.</p>\r\n<p>Nach dem Kurs bitte die Liste eingescannt (als pdf, tif, jpg oder png) an info@bibelschule-stephanus.de schicken. Die Anwesenheiten werden dann in das System eingepflegt und die Liste archiviert.</p>\r\n<p> </p>\r\n<p>Folgende Informationen über den Kurs haben die Studenten per Email empfangen.</p>\r\n<table>\r\n<tbody>\r\n<tr>\r\n<td><strong>Kurs</strong>:</td>\r\n<td>{COURSE_TITLE}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Dozent</strong>:</td>\r\n<td>{TUTOR}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Klasse</strong>:</td>\r\n<td>{CATEGORIES}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Ort</strong>:</td>\r\n<td>{COURSE_LOCATION}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Datum</strong>:</td>\r\n<td>{COURSE_START_DATE} - {COURSE_FINISH_DATE}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p> </p>\r\n<p>{COURSE_INTROTEXT}</p>\r\n<p> </p>\r\n<p>Gottes Segen,</p>\r\n<p>Bibelschule Stephanus</p>', '{EMAIL}', '{ADMIN_CUSTOM_RECIPIENT}', NULL, 1),
(3, 3, 'Teilnehmer: Kurs abgesagt', 'Bibelschule: ABSAGE des Kurses "{COURSE_TITLE}" von {COURSE_START_DATE} bis {COURSE_FINISH_DATE}', '<p>Hallo {NAME},<br />dieser Kurs wurde abgesagt und findet nicht statt:</p>\r\n<table>\r\n<tbody>\r\n<tr>\r\n<td><strong>Kurs</strong>:</td>\r\n<td>{COURSE_TITLE}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Dozent</strong>:</td>\r\n<td>{TUTOR}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Klasse</strong>:</td>\r\n<td>{CATEGORIES}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Ort</strong>:</td>\r\n<td>{COURSE_LOCATION}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Datum</strong>:</td>\r\n<td>{COURSE_START_DATE} - {COURSE_FINISH_DATE}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><span style="font-size: 13px;">Weitere Informationen, findest du auf unserer Webseite.</span></p>\r\n<p> </p>\r\n<p>Gottes Segen,</p>\r\n<p>Bibelschule Stephanus</p>', '{EMAIL}', '{ADMIN_CUSTOM_RECIPIENT}', NULL, 1),
(4, 4, 'Dozent: Kurs abgesagt', 'Bibelschule: ABSAGE des Kurses "{COURSE_TITLE}" von {COURSE_START_DATE} bis {COURSE_FINISH_DATE}', '<p>Hallo {NAME},<br />dieser Kurs wurde abgesagt und findet nicht statt:</p>\r\n<table>\r\n<tbody>\r\n<tr>\r\n<td><strong>Kurs</strong>:</td>\r\n<td>{COURSE_TITLE}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Dozent</strong>:</td>\r\n<td>{TUTOR}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Klasse</strong>:</td>\r\n<td>{CATEGORIES}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Ort</strong>:</td>\r\n<td>{COURSE_LOCATION}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Datum</strong>:</td>\r\n<td>{COURSE_START_DATE} - {COURSE_FINISH_DATE}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><span style="font-size: 13px;">Weitere Informationen, findest du auf unserer Webseite.</span></p>\r\n<p> </p>\r\n<p>Gottes Segen,</p>\r\n<p>Bibelschule Stephanus</p>', '{EMAIL}', '{ADMIN_CUSTOM_RECIPIENT}', NULL, 1);