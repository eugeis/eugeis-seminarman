CREATE TABLE IF NOT EXISTS `#__seminarman_fields_values_tutors` (
  `tutor_id` int(11) NOT NULL,
  `field_id` int(10) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`tutor_id`,`field_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;