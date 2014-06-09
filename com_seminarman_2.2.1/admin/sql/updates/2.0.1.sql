ALTER IGNORE TABLE `#__seminarman_courses` ADD `price4` DOUBLE AFTER `price3`;
ALTER IGNORE TABLE `#__seminarman_courses` ADD `price5` DOUBLE AFTER `price4`;
ALTER IGNORE TABLE `#__seminarman_templates` ADD `price4` DOUBLE AFTER `price3`;
ALTER IGNORE TABLE `#__seminarman_templates` ADD `price5` DOUBLE AFTER `price4`;

INSERT IGNORE INTO `#__seminarman_pricegroups` (`id`, `gid`, `jm_groups`, `reg_group`, `title`, `calc_mathop`, `calc_value`) VALUES  (3, 4, '', 0, 'Price 4', '-%', 0),  (4, 5, '', 0, 'Price 5', '-%', 0);