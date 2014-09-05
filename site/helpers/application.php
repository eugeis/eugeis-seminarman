<?php
/**
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

class ApplicationHelper
{

	public static function setStatus($cid, $status){
		$user = JFactory::getUser();
		if ( $cid ){

			if ($status == 3)  {
				$status = 1;
			}else if ($status == 1)  {
				$status = 3;
			} else {
				$status = $status + 1;
			}

			$query = 'UPDATE #__seminarman_application'
			.' SET status = '
			. (int)$status.
			' WHERE id = '.(int)$cid.' AND ( checked_out = 0 OR ( checked_out = '. (int) $user->get('id'). ' ) )';
			$db = JFactory::getDBO();
			$db->setQuery( $query );

			if ($db->query()) {
				return $db->getErrorMsg();
			}

		}
		return null;
	}
	
	//course
	//`id`, `reference_number`, `code`, `title`, `alias`, `introtext`, `fulltext`, `plus`, `minus`, `hits`, `version`,
	//`meta_keywords`, `meta_description`, `metadata`, `created`, `created_by`, `created_by_alias`, `modified`, `modified_by`,
	//`attribs`, `checked_out`, `checked_out_time`, `state`, `ordering`, `tutor_id`, `id_group`, `id_experience_level`,
	//`price_type`, `job_experience`, `price`, `vat`, `currency_price`, `capacity`, `location`, `publish_up`, `publish_down`,
	//`url`, `image`, `email_template`, `invoice_template`, `attlst_template`, `start_date`, `finish_date`, `access`, `templateId`,
	//`new`, `canceled`, `certificate_text`
	
	
	//tutor
	//`id`, `user_id`, `title`, `alias`, `code`, `firstname`, `lastname`, `salutation`, `other_title`, `comp_name`, `primary_phone`,
	//`fax_number`, `url`, `street`, `id_country`, `state`, `city`, `zip`, `id_comp_type`, `industry`, `description`, `logofilename`,
	//`bill_addr`, `bill_addr_cont`, `bill_id_country`, `bill_state`, `bill_city`, `bill_zip`, `bill_phone`, `metadescription`,
	//`metakeywords`, `status`, `date`, `hits`, `published`, `checked_out`, `checked_out_time`, `ordering`, `archived`, `approved`, `params
	
	public static function sendemailToUserByCourseAndTemplate(&$user, &$course, &$catTitles, &$fillErrors, &$emailTemplate = 0, &$attachment = '')
	{
		$db = JFactory::getDBO();
	
		if ($emailTemplate == 0) {
			array_push($fillErrors,$course->title.', '.$user->name.' template ist nicht definiert.');
			return false;
		}
		
		$query = "SELECT * FROM " . $db->quoteName('#__seminarman_emailtemplate') . " WHERE ( id=" . $emailTemplate . ")";
		$db->setQuery($query);
		$template = $db->loadObject();
		if ($template) {
			$msgSubject = $template->subject;
			$msgBody = $template->body;
			$msgRecipient = $template->recipient;
			$msgRecipientBCC = $template->bcc;
	
			if (!ApplicationHelper::sendEmailToUserByCourse($user, $course, $catTitles, $msgSubject, $msgBody, $msgRecipient, $msgRecipientBCC, $attachment)){
				array_push($fillErrors,$course->title.', '.$user->name.' Problem beim Email senden.');
				return false;
			}
			return true;
		}else {
			array_push($fillErrors,'Email template mit id='.$emailTemplate.' nicht gefunden. Kurse='.$course->title.', Benuter: '.$user->name);
		}
		return false;
	}
	
	public static function sendEmailToUserByCourse(&$user, &$course, &$catTitles, &$msgSubject, &$msgBody, &$msgRecipient, &$msgRecipientBCC, &$attachment='')
	{
		global $ueConfig;
		if (empty($msgRecipient))
			return false;
			
		$db = JFactory::getDBO();
		$message = JFactory::getMailer();
		$config = JFactory::getConfig();
		$params = JComponentHelper::getParams('com_seminarman');
	
		// calculate and format price
		$lang = JFactory::getLanguage();
		$old_locale = setlocale(LC_NUMERIC, NULL);
		setlocale(LC_NUMERIC, $lang->getLocale());
		setlocale(LC_NUMERIC, $old_locale);
	
		ApplicationHelper::fillSubject($msgSubject, $user, $course, $catTitles, $params);
		ApplicationHelper::fillBody($msgBody, $user, $course, $catTitles, $params);
		
		$msgRecipient = str_replace('{EMAIL}', $user->email, $msgRecipient);
		$msgRecipient = str_replace('{ADMIN_CUSTOM_RECIPIENT}', $params->get('component_email'), $msgRecipient);
	
		$msgRecipients = explode(",", $msgRecipient);
	
		$senderEmail = $config->get('mailfrom');
		$senderName = $config->get('fromname');
		$message->addRecipient($msgRecipients);
		if (!empty($msgRecipientBCC))
		{
			$msgRecipientBCC = str_replace('{EMAIL}', $user->email, $msgRecipientBCC);
			$msgRecipientBCC = str_replace('{ADMIN_CUSTOM_RECIPIENT}', $params->get('component_email'), $msgRecipientBCC);
			$message->addBCC(explode(",", $msgRecipientBCC));
		}
	
		$message->setSubject($msgSubject);
		$message->setBody($msgBody);
		$sender = array($senderEmail, $senderName);
		$message->setSender($sender);
		$message->IsHTML(true);
	
		if (! empty($attachment))
			$message->addAttachment($attachment);
	
		$sent = $message->send();
		//$sent = true;
		return $sent;
	}

	public static function sendemail($application_id, &$user, &$course, &$catTitles, &$fillErrors, $emailTemplate = 0, $attachment = '')
	{
		$mainframe = JFactory::getApplication();
		$db = JFactory::getDBO();

		if ($emailTemplate != 0) {
			$emailCond = " AND id=" . $emailTemplate;
		} else {
			$emailCond = " AND isdefault=1";
		}

		$query = "SELECT * FROM " . $db->quoteName('#__seminarman_emailtemplate') . " WHERE (templatefor=0".  $emailCond . ")";
		$db->setQuery($query);
		$template = $db->loadObject();
		if ($template) {
			$msgSubject = $template->subject;
			$msgBody = $template->body;
			$msgRecipient = $template->recipient;
			$msgRecipientBCC = $template->bcc;

			if (!ApplicationHelper::sendEmailToUserApplication($application_id, $user, $course, $catTitles, $msgSubject, $msgBody, $msgRecipient, $msgRecipientBCC, $attachment))
				return false;
			return true;
		}
		return false;
	}

	public static function sendEmailToUserApplication($application_id, &$user, &$course, &$catTitles, &$msgSubject, &$msgBody, &$msgRecipient, &$msgRecipientBCC, &$attachment='')
	{
		global $ueConfig;
		if (empty($msgRecipient))
			return False;
			
		$mainframe = JFactory::getApplication();
		$db = JFactory::getDBO();
		$message = JFactory::getMailer();
		$config = JFactory::getConfig();
		$params = JComponentHelper::getParams('com_seminarman');

		$query = 'SELECT field.*, value.value ' . 'FROM ' . $db->quoteName('#__seminarman_fields') .
		' AS field ' . 'LEFT JOIN ' . $db->quoteName('#__seminarman_fields_values') .
		' AS value ' . 'ON field.id=value.field_id AND value.applicationid=' . $application_id .
		' ' . 'WHERE field.published=' . $db->Quote('1') . ' ' .
		'ORDER BY field.ordering';
		$db->setQuery($query);
		$fields = $db->loadAssocList();

		for ($i = 0; $i < count($fields); $i++) {
			$msgSubject = str_replace('{' . strtoupper($fields[$i]['fieldcode']) . '}', $fields[$i]['value'],
					$msgSubject);
			$msgBody = str_replace('{' . strtoupper($fields[$i]['fieldcode']) . '}', $fields[$i]['value'],
					$msgBody);
		}

		// calculate and format price
		$lang = JFactory::getLanguage();
		$old_locale = setlocale(LC_NUMERIC, NULL);
		setlocale(LC_NUMERIC, $lang->getLocale());
		setlocale(LC_NUMERIC, $old_locale);

		ApplicationHelper::fillSubject($msgSubject, $user, $course, $catTitles, $params);
		ApplicationHelper::fillBody($msgBody, $user, $course, $catTitles, $params);
		
		$conf_hash = floatval($ueConfig['reg_confirmation_hash']);
		$confirmLink = substr_replace(JURI::root(), '', -1, 1).'/index.php?option=com_seminarman&controller=application&task=changestatus&cid='.$application_id.'&cidtoken='.md5($application_id+$conf_hash).'&status=';

		$msgBody = str_replace('{PRESENCE_LINK}',$confirmLink.'0', $msgBody);
		$msgBody = str_replace('{ABSENCE_LINK}',$confirmLink.'1' , $msgBody);

		$msgRecipient = str_replace('{EMAIL}', $user->email, $msgRecipient);
		$msgRecipient = str_replace('{ADMIN_CUSTOM_RECIPIENT}', $params->get('component_email'), $msgRecipient);

		$msgRecipients = explode(",", $msgRecipient);

		$senderEmail = $config->get('mailfrom');
		$senderName = $config->get('fromname');
		$message->addRecipient($msgRecipients);
		if (!empty($msgRecipientBCC))
		{
			$msgRecipientBCC = str_replace('{EMAIL}', $user->email, $msgRecipientBCC);
			$msgRecipientBCC = str_replace('{ADMIN_CUSTOM_RECIPIENT}', $params->get('component_email'), $msgRecipientBCC);
			$message->addBCC(explode(",", $msgRecipientBCC));
		}

		$message->setSubject($msgSubject);
		$message->setBody($msgBody);
		$sender = array($senderEmail, $senderName);
		$message->setSender($sender);
		$message->IsHTML(true);

		if (! empty($attachment))
			$message->addAttachment($attachment);

		$sent = $message->send();
		//$sent = true;
		return $sent;
	}

	private static function fillSubject(&$msgSubject, &$user, &$course, &$catTitles, &$params) {
		$msgSubject = str_replace('{ADMIN_CUSTOM_RECIPIENT}', $params->get('component_email'), $msgSubject);
// 		$msgSubject = str_replace('{TITLE}', $user->title, $msgSubject);
// 		$msgSubject = str_replace('{SALUTATION}', $user->salutation, $msgSubject);
		$msgSubject = str_replace('{NAME}', $user->name, $msgSubject);
		$msgSubject = str_replace('{EMAIL}', $user->email, $msgSubject);
		$msgSubject = str_replace('{COURSE_TITLE}', $course->title, $msgSubject);
		$msgSubject = str_replace('{COURSE_CODE}', $course->code, $msgSubject);
		$msgSubject = str_replace('{COURSE_INTROTEXT}', $course->introtext, $msgSubject);
		$msgSubject = str_replace('{COURSE_FULLTEXT}', $course->fulltext, $msgSubject);
		$msgSubject = str_replace('{COURSE_CAPACITY}', $course->capacity, $msgSubject);
		$msgSubject = str_replace('{COURSE_LOCATION}', $course->location, $msgSubject);
		$msgSubject = str_replace('{COURSE_URL}', $course->url, $msgSubject);
		$msgSubject = str_replace('{COURSE_START_DATE}', JFactory::getDate($course->start_date)->format(JText::_('COM_SEMINARMAN_DATE_FORMAT1')), $msgSubject);
		$msgSubject = str_replace('{COURSE_FINISH_DATE}', JFactory::getDate($course->finish_date)->format(JText::_('COM_SEMINARMAN_DATE_FORMAT1')), $msgSubject);
		$msgSubject = str_replace('{TUTOR}', $course->TUTOR, $msgSubject);
		$msgSubject = str_replace('{TUTOR_FIRSTNAME}', $course->TUTOR_FIRSTNAME, $msgSubject);
		$msgSubject = str_replace('{TUTOR_LASTNAME}', $course->TUTOR_LASTNAME, $msgSubject);
		$msgSubject = str_replace('{CATEGORIES}', join(', ',$catTitles), $msgSubject);
	}
	
	private static function fillBody(&$msgBody, &$user, &$course, &$catTitles, &$params) {
		$msgBody = str_replace('{ADMIN_CUSTOM_RECIPIENT}', $params->get('component_email'), $msgBody);
// 		$msgBody = str_replace('{TITLE}', $user->title, $msgBody);
// 		$msgBody = str_replace('{SALUTATION}', $user->salutation, $msgBody);
		$msgBody = str_replace('{NAME}', $user->name, $msgBody);
		$msgBody = str_replace('{EMAIL}', $user->email, $msgBody);
		$msgBody = str_replace('{COURSE_TITLE}', $course->title, $msgBody);
		$msgBody = str_replace('{COURSE_CODE}', $course->code, $msgBody);
		$msgBody = str_replace('{COURSE_INTROTEXT}', $course->introtext, $msgBody);
		$msgBody = str_replace('{COURSE_FULLTEXT}', $course->fulltext, $msgBody);
		$msgBody = str_replace('{COURSE_CAPACITY}', $course->capacity, $msgBody);
		$msgBody = str_replace('{COURSE_LOCATION}', $course->location, $msgBody);
		$msgBody = str_replace('{COURSE_URL}', $course->url, $msgBody);
		$msgBody = str_replace('{COURSE_START_DATE}', JFactory::getDate($course->start_date)->format(JText::_('COM_SEMINARMAN_DATE_FORMAT1')), $msgBody);
		$msgBody = str_replace('{COURSE_FINISH_DATE}', JFactory::getDate($course->finish_date)->format(JText::_('COM_SEMINARMAN_DATE_FORMAT1')), $msgBody);
		$msgBody = str_replace('{TUTOR}', $course->TUTOR, $msgBody);
		$msgBody = str_replace('{TUTOR_FIRSTNAME}', $course->TUTOR_FIRSTNAME, $msgBody);
		$msgBody = str_replace('{TUTOR_LASTNAME}', $course->TUTOR_LASTNAME, $msgBody);
		$msgBody = str_replace('{CATEGORIES}', join(', ',$catTitles), $msgBody);
	}
	
	public static function saveCustomfields($applicationId, $userId, &$fields)
	{
		$db = JFactory::getDBO();

		foreach ($fields as $id => $value) {

			$q = 'REPLACE INTO `#__seminarman_fields_values`'.
					' (applicationid, user_id, field_id, value)'.
					' VALUES ('.(int)$applicationId.','.(int)$userId.','.(int)$id.','.$db->quote($value).')';
			$db->setQuery($q);
			$db->query();

			$user = JFactory::getUser();
			$q = 'REPLACE INTO `#__seminarman_fields_values_users`'.
					' (user_id, fieldcode, value)'.
					' VALUES ('.(int)$userId.',(SELECT fieldcode FROM `#__seminarman_fields` WHERE id='.(int)$id.'),'.$db->quote($value).')';

			$db->setQuery($q);
			$db->query();
		}
	}

	public static function getAdminEmails()
	{
		$emails = '';
		$db = JFactory::getDBO();

		$query = 'SELECT ' . $db->quoteName('email') . ' FROM ' . $db->quoteName('#__users') .
		' WHERE ' . $db->quoteName('gid') . '=' . $db->quote(24) . ' OR ' . $db->
		quoteName('gid') . '=' . $db->Quote(25);

		$db->setQuery($query);
		$emails = $db->loadColumn();

		return $emails;
	}

	public static function getCurrentBookings()
	{
		$mainframe = JFactory::getApplication();
		$params = $mainframe->getParams();
		if ($params->get('current_capacity') == 1) {
			$statusTopLimit = 3;
			$statusBottomLimit = -1;
		}
		if ($params->get('current_capacity') == 2) {
			$statusTopLimit = 3;
			$statusBottomLimit = 0;
		}

		$db = JFactory::getDBO();
		$sql = 'SELECT SUM(b.attendees)' . ' FROM #__seminarman_application AS b' .
				' WHERE b.published = 1' . ' AND b.course_id = ' . $this->_course_id .
				' AND b.status <' . $statusTopLimit = 3 . ' AND b.status >' . $statusBottomLimit;
		$db->setQuery($sql);
		$current_bookings = $db->loadResult();
		return $current_bookings;
	}


	public static function sendRegistrationEmail(&$user, &$password)
	{
		$config	= JFactory::getConfig();
		$params = JComponentHelper::getParams('com_users');


		$subject = JText::sprintf('COM_SEMINARMAN_EMAIL_ACCOUNT_DETAILS',
				$user->name,
				$config->get('sitename')
		);

		if ($params->get('useractivation') == 2)
			$body = JText::sprintf('COM_SEMINARMAN_EMAIL_REGISTERED_WITH_ADMIN_ACTIVATION_BODY',
					$user->name,
					$config->get('sitename'),
					JUri::base().'index.php?option=com_users&task=registration.activate&token='.$user->activation,
					JUri::base(),
				 $user->username,
				 $password
			);
		else if ($params->get('useractivation') == 1)
			$body = JText::sprintf('COM_SEMINARMAN_EMAIL_REGISTERED_WITH_ACTIVATION_BODY',
					$user->name,
					$config->get('sitename'),
					JUri::base().'index.php?option=com_users&task=registration.activate&token='.$user->activation,
					JUri::base(),
					$user->username,
					$password
			);
		else
			$body = JText::sprintf('COM_SEMINARMAN_EMAIL_REGISTERED_BODY',
					$user->name,
					$config->get('sitename'),
					JUri::base()
			);

		$message = JFactory::getMailer();
		$message->addRecipient($user->email);
		$message->addBcc($config->get('mailfrom'));
		$message->setSubject(html_entity_decode($subject, ENT_QUOTES));
		$message->setBody($body);
		$message->setSender(array($config->get('mailfrom'), $config->get('fromname')));
		$message->IsHTML(false);

		return $message->send();
	}

	/**
	 * returns an key value array that can be used to replace
	 * the fields in a pdf template with actual values
	 * @param $applicationid (int)
	 */
	public static function getFieldValuesForTemplate($applicationid)
	{
		$db = JFactory::getDBO();
		$data = array();
			
		// application data
		$db->setQuery('SELECT'.
				' a.invoice_number AS `INVOICE_NUMBER`,'.
				' a.date AS `INVOICE_DATE`,'.
				' a.attendees AS `ATTENDEES_TOTAL`,'.
				' a.salutation AS `SALUTATION`,'.
				' a.title AS `TITLE`,'.
				' a.first_name AS `FIRSTNAME`,'.
				' a.last_name AS `LASTNAME`,'.
				' a.email AS `EMAIL`,'.
				' a.price_per_attendee AS `PRICE_PER_ATTENDEE`,'.
				' a.price_total AS `PRICE_TOTAL`,'.
				' a.price_vat AS `PRICE_VAT_PERCENT`,'.
				' c.code AS `COURSE_CODE`,'.
				' c.title AS `COURSE_TITLE`,'.
				' c.capacity AS `COURSE_CAPACITY`,'.
				' c.location AS `COURSE_LOCATION`,'.
				' c.url AS `COURSE_URL`,'.
				' c.start_date AS `COURSE_START_DATE`,'.
				' c.finish_date AS `COURSE_FINISH_DATE`,'.
				' t.title AS `TUTOR`,'.
				' t.firstname AS `TUTOR_FIRSTNAME`,'.
				' t.lastname AS `TUTOR_LASTNAME`,'.
				' g.title AS `GROUP`,'.
				' l.title AS `EXPERIENCE_LEVEL`'.
				' FROM `#__seminarman_application` AS a'.
				' LEFT JOIN `#__seminarman_courses` AS c ON a.course_id = c.id'.
				' LEFT JOIN `#__seminarman_tutor` AS t ON c.tutor_id = t.id'.
				' LEFT JOIN `#__seminarman_atgroup` AS g ON c.id_group = g.id'.
				' LEFT JOIN `#__seminarman_experience_level` AS l ON c.id_experience_level = l.id'.
				' WHERE a.id = '. (int) $applicationid );
		$data = $db->loadAssoc();
			
		// custom fields
		$db->setQuery('SELECT fieldcode, value FROM `#__seminarman_fields_values` AS v'.
				' LEFT JOIN `#__seminarman_fields` AS f ON v.field_id = f.id'.
				' WHERE applicationid = '. (int) $applicationid );
		foreach ($db->loadRowList() as $row)
			$data[$row[0]] = $row[1];
			
		// calculate and format prices
		$lang = JFactory::getLanguage();
		$old_locale = setlocale(LC_NUMERIC, NULL);
		setlocale(LC_NUMERIC, $lang->getLocale());
		$data['PRICE_PER_ATTENDEE'] = JText::sprintf('%.2f', $data['PRICE_PER_ATTENDEE']);
		$data['PRICE_TOTAL'] = JText::sprintf('%.2f', $data['PRICE_TOTAL']);
		$data['PRICE_PER_ATTENDEE_VAT'] = JText::sprintf('%.2f', (($data['PRICE_PER_ATTENDEE'] / 100.0) * $data['PRICE_VAT_PERCENT']) + $data['PRICE_PER_ATTENDEE']);
		$data['PRICE_TOTAL_VAT'] = JText::sprintf('%.2f', (($data['PRICE_TOTAL'] / 100.0) * $data['PRICE_VAT_PERCENT']) + $data['PRICE_TOTAL']);
		$data['PRICE_VAT'] = JText::sprintf('%.2f', ($data['PRICE_TOTAL'] / 100.0) * $data['PRICE_VAT_PERCENT']);
		setlocale(LC_NUMERIC, $old_locale);

		// format date
		$data['INVOICE_DATE'] = JFactory::getDate($data['INVOICE_DATE'])->format(JText::_('COM_SEMINARMAN_DATE_FORMAT1'));
		$data['COURSE_START_DATE'] = JFactory::getDate($data['COURSE_START_DATE'])->format(JText::_('COM_SEMINARMAN_DATE_FORMAT1'));
		$data['COURSE_FINISH_DATE'] = JFactory::getDate($data['COURSE_FINISH_DATE'])->format(JText::_('COM_SEMINARMAN_DATE_FORMAT1'));

		return $data;
	}

	public static function getInvoiceNumber()
	{
		$db = JFactory::getDBO();
		$params = JComponentHelper::getParams('com_seminarman');

		$db->setQuery('LOCK TABLES `#__seminarman_invoice_number` WRITE');
		$db->query();

		$db->setQuery('UPDATE `#__seminarman_invoice_number` SET number=GREATEST(number+1,'.(int)$params->get('invoice_number_start').')');
		$db->query();
			
		$db->setQuery('SELECT number FROM `#__seminarman_invoice_number`');
		$db->query();
		$next = $db->loadResult();
			
		$db->setQuery('UNLOCK TABLES');
		$db->query();
			
		return $next;
	}
	
	public static function cancel($appId)
	{
		// Get a database object.
		$db = JFactory::getDbo();
		$db->setQuery('DELETE FROM #__seminarman_application WHERE id=' . $appId);
		return $db->query();
	}	
	
	public static function book(&$user, &$course, &$fillErrors) {
		$db = JFactory::getDBO();
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_seminarman'.DS.'tables');
		$row = JTable::getInstance('application', 'Table');

		$data = array();

		//`id`, `invoice_filename_prefix`, `course_id`,
		//`user_id`, `status`, `first_name`, `last_name`, `salutation`, `title`, `email`,
		//`attendees`, `price_per_attendee`, `price_total`, `price_vat`, `comments`, `date`, `hits`,
		//`checked_out`, `checked_out_time`, `ordering`, `archived`, `approved`, `params`, `published`, `transaction_id`


		$data['user_id'] = $user->id;
		//$data['salutation'] = $user->salution;
		$name = explode(' ',$user->name,2);
		$data['first_name'] = $name[0];
		$data['last_name'] = $name[1];
		$data['email'] = $user->email;
		$data['title'] = $course->title;
		$data['course_id'] = $course->id;
		$data['attendees'] = 1;
		$data['status'] = 0;
		$data['published'] = 1;
		$data['invoice_number'] = ApplicationHelper::getInvoiceNumber();

		if (!$row->bind($data)) {
			array_push($fillErrors,$user->name . ' - ' . $db->getErrorMsg());
			return null;
		}
			
		$row->date = gmdate('Y-m-d H:i:s');
			
		if (!$row->id) {
			$is_insert = 1;
			$where = 'course_id = ' . (int)$row->course_id;
			$row->ordering = $row->getNextOrder($where);
		}
		else
			$is_insert = 0;
			
		if (!$row->check()) {
			array_push($fillErrors,$user->name . ' - ' . $db->getErrorMsg());
			return null;
		}
			
		if (!$row->store()) {
			array_push($fillErrors,$user->name . ' - ' . $db->getErrorMsg());
			return null;
		}
			
// 		$title = $db->quote($data['title']);
// 		$salutation = $db->quote($data['salutation']);
// 		$db->setQuery('REPLACE INTO `#__seminarman_fields_values_users_static` (user_id, salutation, title) VALUES ('.$uid.','.$salutation.','.$title.')');
// 		$db->query();
		return $row->id;
	}

	public static function getIdByCourseAndUser($course_id, $user_id)
	{
		$db = JFactory::getDBO();
		$q = 'SELECT id FROM `#__seminarman_application`'.
				' WHERE user_id = '. (int)$user_id.
				' AND course_id = ' . $course_id .
				' AND published = 1';
		$db->setQuery($q);
		return $db->loadResult();
	}
	
	public static function isCourseOldByApplicationId($application_id){
		$db = JFactory::getDBO();
	
		$query = 'SELECT c.finish_date ' .
				' FROM #__seminarman_courses AS c' .
				' INNER JOIN #__seminarman_application as a ON a.course_id = c.id' .
				' WHERE a.id = ' . $application_id;
	
		$db->setQuery($query);
		$finish_date = $db->loadResult();
		return ApplicationHelper::isCourseOldByFinishDate($finish_date);
	}
	
	public static function loadCourse($course_id){
		$db = JFactory::getDBO();

		$query = 'SELECT c.*, t.user_id as TUTOR_USER_ID, t.title AS `TUTOR`, t.firstname AS `TUTOR_FIRSTNAME`, t.lastname AS `TUTOR_LASTNAME` ' .
				' FROM #__seminarman_courses AS c' .
				' INNER JOIN #__seminarman_tutor AS t ON t.id = c.tutor_id' .
				' WHERE c.id = ' . $course_id;

		$db->setQuery($query);
		$queryResult = $db->loadObject();
		return $queryResult;
	}

	public static function getCourseCategoryTitles($course_id){
		$db = JFactory::getDbo();
		$db->setQuery(
				'SELECT c.title FROM #__seminarman_categories AS c ' .
				'INNER JOIN #__seminarman_cats_course_relations as cc ON cc.catid = c.id ' .
				'WHERE cc.courseid = ' . $course_id
		);
		$cats = $db->loadColumn();
		return $cats;
	}
	
	public static function isCourseOldByFinishDate($finish_date){
		if(strtotime($finish_date) < time()) {
			return true;
		}else{
			return false;
		}
	}
	
	public static function isCourseOld(&$course){
		return ApplicationHelper::isCourseOldByFinishDate($course->finish_date);
	}
	
	public static function getStatusText($status){
		$status_text = '';
		switch ($status) {
			case 0:
				$status_text = JText::_( 'COM_SEMINARMAN_SUBMITTED' );
				break;
			case 1:
				$status_text =JText::_( 'COM_SEMINARMAN_PENDING' );
				break;
			case 2:
				$status_text = JText::_( 'COM_SEMINARMAN_PAID' );
				break;
			case 3:
				$status_text = JText::_( 'COM_SEMINARMAN_CANCELED' );
				break;
		}
		return $status_text;
	}
}