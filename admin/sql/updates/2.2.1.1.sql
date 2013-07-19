CREATE TABLE IF NOT EXISTS `#__seminarman_period` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `alias` varchar(100) NOT NULL,
  `code` char(2) DEFAULT NULL,
  `color` varchar(7) NOT NULL,
  `description` text,
  `start_date` date NOT NULL DEFAULT '0000-00-00',
  `finish_date` date NOT NULL DEFAULT '0000-00-00',
  `isdefault` INT( 1 ) NOT NULL DEFAULT '0',
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

ALTER IGNORE TABLE `#__seminarman_application` ADD `note_reading` varchar(3) AFTER `attendees`;
ALTER IGNORE TABLE `#__seminarman_application` ADD `note_test` varchar(3) AFTER `note_reading`;
ALTER IGNORE TABLE `#__seminarman_application` ADD `note_work` varchar(3) AFTER `note_test`;
ALTER IGNORE TABLE `#__seminarman_application` ADD `note` varchar(3) AFTER `note_work`;
ALTER IGNORE TABLE `#__seminarman_application` ADD `attendance` INTEGER(11) AFTER `note`;

ALTER IGNORE TABLE `#__seminarman_courses` ADD `email_template_cancel` INTEGER(11) NOT NULL DEFAULT '0' AFTER `email_template`;
ALTER IGNORE TABLE `#__seminarman_courses` ADD `email_template_trainer` INTEGER(11) NOT NULL DEFAULT '0' AFTER `email_template_cancel`;
ALTER IGNORE TABLE `#__seminarman_courses` ADD `email_template_trainer_cancel` INTEGER(11) NOT NULL DEFAULT '0' AFTER `email_template_trainer`;

INSERT IGNORE INTO `#__seminarman_categories` (`id`, `parent_id`, `title`, `alias`, `text`, `meta_keywords`, `meta_description`, `image`, `icon`, `published`, `checked_out`, `checked_out_time`, `access`, `ordering`) VALUES
(2, 0, '1. Klasse (S�d)', '1-klasse-sued', '', '', '', '', '', 1, 0, '0000-00-00 00:00:00', 1, 1),
(3, 0, '1. Klasse (Nord)', '1-klasse-nord', '', '', '', '', '', 1, 0, '0000-00-00 00:00:00', 1, 2),
(4, 0, '2. Klasse', '2-klasse', '', '', '', '', '', 1, 0, '0000-00-00 00:00:00', 1, 3),
(5, 0, '3. Klasse', '3-klasse', '', '', '', '', '', 1, 0, '0000-00-00 00:00:00', 1, 4),
(6, 0, '4. Klasse', '4-klasse', '', '', '', '', '', 1, 0, '0000-00-00 00:00:00', 1, 5);

UPDATE `#__seminarman_emailtemplate`
SET templatefor = 0, title = 'Sch�ler: Teilnahmebest�tigung f�r Kurstermin', subject = 'Bibelschule: Kurs "{COURSE_TITLE}" in {CATEGORIES} von {COURSE_START_DATE} bis {COURSE_FINISH_DATE}', body = '<p>Hallo {NAME},<br />du hast dich f�r diesen Kurs angemeldet:</p>\r\n<table>\r\n<tbody>\r\n<tr>\r\n<td><strong>Kurs</strong>:</td>\r\n<td>{COURSE_TITLE}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Dozent</strong>:</td>\r\n<td>{TUTOR}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Klasse</strong>:</td>\r\n<td>{CATEGORIES}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Ort</strong>:</td>\r\n<td>{COURSE_LOCATION}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Datum</strong>:</td>\r\n<td>{COURSE_START_DATE} - {COURSE_FINISH_DATE}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>�</p>\r\n<table border="1">\r\n<tbody>\r\n<tr>\r\n<td colspan="2">\r\n<p>Um eine optimale Organisation und Verflegung zu erm�glichen, bitte die Teilnahme an dem Kurstermin durch Klicken auf diese Links verbindlich zu best�tigen oder zu stornieren.</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td><a href="{PRESENCE_LINK}">Teilnahme best�tigen</a></td>\r\n<td><a href="{ABSENCE_LINK}">Teilname stornieren</a></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>�</p>\r\n<p>{COURSE_INTROTEXT}</p>\r\n<p>�</p>\r\n<p>Weitere Informationen, findest du auf unserer Webseite.</p>\r\n<p>�</p>\r\n<p>Gottes Segen,</p>\r\n<p>Bibelschule Stephanus</p>', recipient = '{EMAIL}', bcc = '{ADMIN_CUSTOM_RECIPIENT}', status = NULL, isdefault = 1
WHERE id = 1;

UPDATE `#__seminarman_emailtemplate`
SET templatefor = 0, title = 'Dozent: Teilnehmerliste f�r Kurstermin', subject = 'Bibelschule: Teilnehmerliste f�r Kurs "{COURSE_TITLE}"', body = '<p>Hallo {NAME},</p>\r\n<p>im Anhang ist die aktuelle Teilnehmerliste f�r deinen Kurs '{COURSE_TITLE}'.</p>\r\n<p>In der Liste sind alle Stundenten eingetragen, die zu der Klasse geh�ren, sowie evtl. Studenten aus h�heren Klassen, die auch�an dem Fach teilnehmen m�chten. Au�erdem wurden die Studenten aufgefordert derren Teilnahme zu best�tigen bzw. (falls verhindert) zu stornieren. Dieser Status ist in der Liste jeweils pro Student sowie ingesammt aufsummiert (best�tigt/alle) ersichtlich.�</p>\r\n<p>Bitte vor dem Unterricht ausdrucken und von den anwesenden Studenten unterschreiben und die Anwesenheit in Stunden eintragen lassen.</p>\r\n<p>Nach dem Kurs bitte die Liste eingescannt (als pdf, tif, jpg oder�png)�an info@bibelschule-stephanus.de schicken. Die Anwesenheiten werden dann in das System eingepflegt und die Liste archiviert.</p>\r\n<p>�</p>\r\n<p>Folgende Informationen �ber den Kurs haben die Studenten per Email empfangen.</p>\r\n<table>\r\n<tbody>\r\n<tr>\r\n<td><strong>Kurs</strong>:</td>\r\n<td>{COURSE_TITLE}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Dozent</strong>:</td>\r\n<td>{TUTOR}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Klasse</strong>:</td>\r\n<td>{CATEGORIES}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Ort</strong>:</td>\r\n<td>{COURSE_LOCATION}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Datum</strong>:</td>\r\n<td>{COURSE_START_DATE} - {COURSE_FINISH_DATE}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>�</p>\r\n<p>{COURSE_INTROTEXT}</p>\r\n<p>�</p>\r\n<p>Gottes Segen,</p>\r\n<p>Bibelschule Stephanus</p>', recipient = '{EMAIL}', bcc = '{ADMIN_CUSTOM_RECIPIENT}', status = NULL, isdefault = 1
WHERE id = 2;

INSERT IGNORE INTO `#__seminarman_emailtemplate` (`id`, `templatefor`, `title`, `subject`, `body`, `recipient`, `bcc`, `status`, `isdefault`) VALUES
(3, 3, 'Sch�ler: Kurs abgesagt', 'Bibelschule: ABSAGE des Kurses "{COURSE_TITLE}" von {COURSE_START_DATE} bis {COURSE_FINISH_DATE}', '<p>Hallo {NAME},<br />dieser Kurs wurde abgesagt und findet nicht statt:</p>\r\n<table>\r\n<tbody>\r\n<tr>\r\n<td><strong>Kurs</strong>:</td>\r\n<td>{COURSE_TITLE}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Dozent</strong>:</td>\r\n<td>{TUTOR}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Klasse</strong>:</td>\r\n<td>{CATEGORIES}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Ort</strong>:</td>\r\n<td>{COURSE_LOCATION}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Datum</strong>:</td>\r\n<td>{COURSE_START_DATE} - {COURSE_FINISH_DATE}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><span style="font-size: 13px;">Weitere Informationen, findest du auf unserer Webseite.</span></p>\r\n<p>�</p>\r\n<p>Gottes Segen,</p>\r\n<p>Bibelschule Stephanus</p>', '{EMAIL}', '{ADMIN_CUSTOM_RECIPIENT}', NULL, 1),
(4, 4, 'Dozent: Kurs abgesagt', 'Bibelschule: ABSAGE des Kurses "{COURSE_TITLE}" von {COURSE_START_DATE} bis {COURSE_FINISH_DATE}', '<p>Hallo {NAME},<br />dieser Kurs wurde abgesagt und findet nicht statt:</p>\r\n<table>\r\n<tbody>\r\n<tr>\r\n<td><strong>Kurs</strong>:</td>\r\n<td>{COURSE_TITLE}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Dozent</strong>:</td>\r\n<td>{TUTOR}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Klasse</strong>:</td>\r\n<td>{CATEGORIES}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Ort</strong>:</td>\r\n<td>{COURSE_LOCATION}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Datum</strong>:</td>\r\n<td>{COURSE_START_DATE} - {COURSE_FINISH_DATE}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><span style="font-size: 13px;">Weitere Informationen, findest du auf unserer Webseite.</span></p>\r\n<p>�</p>\r\n<p>Gottes Segen,</p>\r\n<p>Bibelschule Stephanus</p>', '{EMAIL}', '{ADMIN_CUSTOM_RECIPIENT}', NULL, 1);

UPDATE `#__seminarman_pdftemplate`
SET templatefor = 1, name = 'Teilnehmerliste 1', html = '<table style="width: 100%;" cellspacing="0" cellpadding="0">\r\n<tbody>\r\n<tr>\r\n<td style="width: 7%;"><strong>Kurs</strong></td>\r\n<td style="width: 28%;">{COURSE_TITLE}</td>\r\n<td style="width: 7%;"><strong>Ort</strong></td>\r\n<td style="width: 29%;">{COURSE_LOCATION}</td>\r\n<td style="width: 10%;"><strong>Dozent</strong></td>\r\n<td style="width: 20%;">{TUTOR}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Datum</strong></td>\r\n<td>{COURSE_START_DATE} - {COURSE_FINISH_DATE}</td>\r\n<td><strong>Klasse</strong></td>\r\n<td>{CATEGORIES}</td>\r\n<td><strong>Stand</strong></td>\r\n<td>{CURRENT_DATE} {ATTENDEES_STATUS_1}/{ATTENDEES}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table style="width: 100%;" border="1">\r\n<tbody>\r\n<tr><th style="width: 20%; text-align: left;"><span style="color: #000080;"><strong>Name</strong></span></th><th style="width: 20%; text-align: left;"><span style="color: #000080;"><strong>Vorname</strong></span></th><th style="width: 20%; text-align: left;"><span style="color: #000080;"><strong>Teilnahmestatus</strong></span></th><th style="width: 40%; text-align: left;" colspan="2"><span style="color: #000080;"><strong>Anwenheit in Std./Unterschrift</strong></span></th></tr>\r\n<tr class="{LOOP}">\r\n<td style="text-align: left;">{LASTNAME}</td>\r\n<td style="text-align: left;">{FIRSTNAME}</td>\r\n<td style="text-align: left;">{STATUS}</td>\r\n<td style="width: 10%; text-align: left;">{ANWESENHEIT}</td>\r\n<td style="width: 30%;">�</td>\r\n</tr>\r\n</tbody>\r\n</table>', srcpdf = '', isdefault = 1, margin_left = 10, margin_right = 10, margin_top = 15, margin_bottom = 15, paperformat = 'A4', orientation = 'P'
WHERE id = 4;
