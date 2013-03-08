ALTER TABLE `#__seminarman_courses` ADD `pdf_template` int(11) unsigned NOT NULL DEFAULT '0' AFTER `email_template`;
ALTER TABLE `#__seminarman_templates` ADD `pdf_template` int(11) unsigned NOT NULL DEFAULT '0' AFTER `email_template`;

ALTER TABLE `#__seminarman_emailtemplate`
  DROP `uid`,
  DROP `created`,
  DROP `checked_out`,
  DROP `checked_out_time`,
  DROP `params`;

ALTER TABLE `#__seminarman_pdftemplate`
  DROP `created`,
  DROP `checked_out`,
  DROP `checked_out_time`;
  
UPDATE `#__seminarman_emailtemplate` SET `isdefault`=1 WHERE `templatefor`=0 LIMIT 1;