CREATE TABLE IF NOT EXISTS `#__seminarman_usergroups` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `jm_id` int(10) NOT NULL,
  `sm_id` int(10) NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT IGNORE INTO `#__seminarman_usergroups` (`id`, `jm_id`, `sm_id`, `title`) VALUES  (1, 0, 1, 'Seminar Manager'),  (2, 0, 2, 'Seminar Trainer');

ALTER IGNORE TABLE `#__seminarman_courses` ADD `min_attend` INT( 11 ) NOT NULL DEFAULT '0' AFTER `currency_price`;
ALTER IGNORE TABLE `#__seminarman_courses` ADD `price2` DOUBLE AFTER `price`;
ALTER IGNORE TABLE `#__seminarman_courses` ADD `price3` DOUBLE AFTER `price2`;
ALTER IGNORE TABLE `#__seminarman_templates` ADD `min_attend` INT( 11 ) NOT NULL DEFAULT '0' AFTER `currency_price`;
ALTER IGNORE TABLE `#__seminarman_templates` ADD `price2` DOUBLE AFTER `price`;
ALTER IGNORE TABLE `#__seminarman_templates` ADD `price3` DOUBLE AFTER `price2`;
ALTER IGNORE TABLE `#__seminarman_courses` ADD `theme_points` INT( 11 ) NOT NULL DEFAULT '0' AFTER `id_experience_level`;
ALTER IGNORE TABLE `#__seminarman_templates` ADD `theme_points` INT( 11 ) NOT NULL DEFAULT '0' AFTER `id_experience_level`;

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

INSERT IGNORE INTO `#__seminarman_pricegroups` (`id`, `gid`, `jm_groups`, `reg_group`, `title`, `calc_mathop`, `calc_value`) VALUES  (1, 2, '', 0, 'Price 2', '-%', 0),  (2, 3, '', 0, 'Price 3', '-%', 0);

ALTER IGNORE TABLE `#__seminarman_application` ADD `pricegroup` varchar(100) AFTER `attendees`;

