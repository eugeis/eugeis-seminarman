ALTER IGNORE TABLE `#__seminarman_courses` ADD `start_time` TIME DEFAULT NULL AFTER `finish_date`;
ALTER IGNORE TABLE `#__seminarman_courses` ADD `finish_time` TIME DEFAULT NULL AFTER `start_time`;