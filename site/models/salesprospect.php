<?php

/**
 *
 * @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
 * @website http://www.profinvent.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class seminarmanModelSalesProspect extends JModel
{
	var $_id = null;

	var $_data = null;

	function __construct()
	{
		parent::__construct();

		$id = JRequest::getVar('id', 0, '', 'int');
		$this->_template_id = JRequest::getVar('template_id', 0, '', 'int');
		$this->setId((int)$id);
	}

	function setId($id)
	{
		$this->_id = $id;
		$this->_data = null;
	}
	
	function getData()
	{
		$q = 'SELECT w.*, j.reference_number, j.title AS course_title, j.price, j.currency_price,j.price_type,j.code'.
		     ' FROM #__seminarman_salesprospect AS w'.
		     ' LEFT JOIN #__seminarman_templates AS j ON j.id = w.template_id'.
		     ' WHERE w.id = ' . (int)$this->_id;
		$this->_db->setQuery($q);
		return $this->_db->loadObject();
	}
	
	function store($data)
	{
		JTable::addIncludePath(JPATH_ADMINISTRATOR . DS .'components'. DS .'com_seminarman'. DS .'tables');

		$row = JTable::getInstance('SalesProspect', 'Table');
		$db = $this->getDBO();

		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		$row->date = gmdate('Y-m-d H:i:s');

		if (!$row->id) {
			$where = 'template_id = ' . (int)$row->template_id;
			$row->ordering = $row->getNextOrder($where);
		}

		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		$user = JFactory::getUser();
		if ($user->guest)
			$uid = $data['user_id'];
		else
			$uid = $user->id;

		$title = $db->quote($data['title']);
		$salutation = $db->quote($data['salutation']);
		$db->setQuery('REPLACE INTO `#__seminarman_fields_values_users_static` (user_id, salutation, title) VALUES ('.$uid.','.$salutation.','.$title.')');
		$db->query();
		
		return ($row->id);
	}


	function saveCustomfields($requestid, $userId, $fields)
	{
		$db = $this->getDBO();
	
		foreach ($fields as $id => $value) {
			
			$q = 'REPLACE INTO `#__seminarman_fields_values_salesprospect`'.
		        	     ' (requestid, user_id, field_id, value)'.
		        	     ' VALUES ('.(int)$requestid.','.(int)$userId.','.(int)$id.','.$db->quote($value).')';
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

	function sendRegistrationEmail($user, $password)
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
	
}