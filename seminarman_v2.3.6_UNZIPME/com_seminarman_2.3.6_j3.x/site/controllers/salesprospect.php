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

jimport('joomla.application.component.controller');

class seminarmanControllerSalesProspect extends seminarmanController
{
    function save()
    {
    	JRequest::checkToken() or jexit('Invalid Token');
    
    	$db = JFactory::getDBO();
    	$user = JFactory::getUser();
    	$mainframe = JFactory::getApplication();
    	$params = $mainframe->getParams();
    	
    	if ($params->get('enable_salesprospects', 0) == 0){
    		$mainframe->redirect('index.php', '');
    	}
    	
    	$post = JRequest::get('post');
    	$post['user_id'] = $user->get('id');
    	$post['published'] = 1;
    
    	switch ($params->get('enable_bookings')) {
    		case 3:
    			// ok, everyone is allowed to book
    			break;
    		case 2:
    			// ok, everyone is allowed to book
    			break;
    		case 1:
    			// only registered useres are allowed
    			if ($user->get('guest'))
    			$mainframe->redirect('index.php?option=com_users&view=login&return='. base64_encode(JRoute::_('index.php?option=com_seminarman', false)), JText::_('COM_SEMINARMAN_PLEASE_LOGIN_FIRST'));
    			break;
    		default:
    			// booking disabled
    			$mainframe->redirect("index.php", JText::_('COM_SEMINARMAN_BOOKINGS_DISABLED'));
    	}
    	
    	CMFactory::load( 'helpers' , 'emails' );
    	if (!$email_arr = isValidInetAddress($post['email']))
    	{
    		$mainframe->enqueueMessage(JText::_('COM_SEMINARMAN_NO_VALID_EMAIL'), 'error');
    		return;
    	}
    	$post['email'] = $email_arr[0].'@'.$email_arr[1];
    	 
    	
    	$values = array();
    	$model = $this->getModel('templates');
    	$editfields = $model->getEditableCustomfields($post['user_id']);
    	$model = $this->getModel('salesprospect');
    	$mainframe = JFactory::getApplication();
    	CMFactory::load('libraries', 'customfields');
    
    	foreach ($editfields['fields'] as $group => $fields) {
    		foreach ($fields as $data) {
    			// Get value from submitted form = post data.
    			$postData = JRequest::getVar('field' . $data['id'], '', 'POST');
    			$values[$data['id']] = SeminarmanCustomfieldsLibrary::formatData($data['type'], $postData);
    			//  @rule : Validate custom customfields if necessary
    			if (!SeminarmanCustomfieldsLibrary::validateField($data['type'], $values[$data['id']],
    			$data['required'])) {
    				// If there are errors on the form, display to the user.
    				if ($data['type'] == 'checkboxtos')
    					$message =  JText::sprintf('COM_SEMINARMAN_ACCEPT_TOS', $data['name']);
    				else
    					$message = JText::sprintf('COM_SEMINARMAN_FIELD_N_CONTAINS_IMPROPER_VALUES', $data['name']);
    				$mainframe->enqueueMessage($message, 'error');
    				return;
    			}
    		}
    	}
    	// list of templates
    	$query = 'SELECT *' . ' FROM #__seminarman_templates' . ' WHERE id =' . $post['template_id'];
    	$db->setQuery($query);
    	if (!$db->query()) {
    		JError::raiseError(500, $db->stderr(true));
    		return;
    	}
    	$templateRows = $db->loadObject();

    	if (!$params->get('enable_num_of_attendees')) {
    		$post['attendees'] = 1;
    	}
    	$post['price_per_attendee'] = $templateRows->price;
    	$post['price_total'] = $post['price_per_attendee'] * $post['attendees'];
    	$post['price_vat'] = $templateRows->vat;
    	$post['code'] = $templateRows->code;
    	$post['course_title'] = $templateRows->title;
    	
    	$usersConfig = JComponentHelper::getParams('com_users');
    	
    	// register user
    	if ($post['user_id'] == 0 && $usersConfig->get('allowUserRegistration') != '0' && ($params->get('enable_bookings') != '3'))
    	{
    		// is there alread a joomla user with the same email address?
    		$db = JFactory::getDBO();
    		$db->setQuery('SELECT id FROM #__users WHERE email =' . $db->Quote($post['email']));
    		if (!$db->query()) {
    			JError::raiseError(500, $db->stderr(true));
    			return;
    		}
    		$existing_uid = $db->loadResult();
    		
    		if (!empty($existing_uid))
    		{
    			// yes. set user_id of this application to this user.
    			$uid = $existing_uid;
    		}
    		else
    		{
    			// no. create a new joomla user
    			jimport('joomla.user.helper');
    			
    			$data = array();
    			$data['id'] = 0;
    			$data['name'] = $post['first_name'].' '.$post['last_name'];
    			$data['username'] = $post['email'];
    			$data['email'] = $post['email'];
    			$data['groups'] = array(2); // 2: Registered
    			$data['block'] = $usersConfig->get('useractivation') > 0 ? 1 : 0;
    			// $data['activation'] = JUtility::getHash(JUserHelper::genRandomPassword());
    			$data['activation'] = JApplication::getHash(JUserHelper::genRandomPassword());
    			 
    			$password = JUserHelper::genRandomPassword();
    			$salt = JUserHelper::genRandomPassword(32);
    			$crypted = JUserHelper::getCryptedPassword($password, $salt);
    			 
    			$usern = JUser::getInstance();
    			$usern->bind($data);
    			$usern->set('password', $crypted.':'.$salt);
    			$usern->save();
    			 
    			$uid = $usern->id;
	    		$model->sendRegistrationEmail($usern, $password);
    		}
    		$post['user_id'] = $uid;
    	}
    	
   		// save data in salesprospects table
   		if (!$requestid = $model->store($post))
   			$this->setRedirect(JRoute::_($params->get('application_landingpage')), JText::_('COM_SEMINARMAN_ERROR_PROCESSING_REGISTRATION'));
   		
   		// save custom fields
   		$model->saveCustomfields($requestid, $post['user_id'], $values);
   		
   		if (!$model->sendemail($post))
   			return $this->setRedirect(JRoute::_($params->get('application_landingpage')), JText::_('COM_SEMINARMAN_ERROR_SENDING_EMAILS'));
   		
   		$this->setRedirect(JRoute::_($params->get('application_landingpage')), JText::_('COM_SEMINARMAN_THANK_YOU_FOR_YOUR_INTEREST').'!');
    }
}

?>