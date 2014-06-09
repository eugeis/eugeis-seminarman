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

require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_seminarman'.DS.'classes'.DS.'pdfdocument.php';

class seminarmanControllerApplication extends seminarmanController
{
	function save()
	{
		JRequest::checkToken() or jexit('Invalid Token');

		$user = JFactory::getUser();
		$mainframe = JFactory::getApplication();
		$params = $mainframe->getParams();
		$post = JRequest::get('post');
		$post['user_id'] = $user->get('id');
		$post['published'] = 1;

        $dispatcher = JDispatcher::getInstance();
        JPluginHelper::importPlugin('seminarman');
	    // $course = $this->getModel('courses');
	    // $course_id = $course->_id;
		$course_id = intval($post['course_id']);
        // fire vmengine
        $results = $dispatcher->trigger('onProcessBooking', array($course_id));	
        if(!empty($results)){	
		    $vmlink = $results[0];
        }else{
        	$vmlink = null;
        }
		
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

		$courseModel = $this->getModel('courses');
		$editfields = $courseModel->getEditableCustomfields($post['user_id']);

		CMFactory::load('libraries', 'customfields');

		$customFields = array();
		foreach ($editfields['fields'] as $group => $fields) {
			foreach ($fields as $data) {
				$postData = JRequest::getVar('field' . $data['id'], '', 'POST');
				$customFields[$data['id']] = SeminarmanCustomfieldsLibrary::formatData($data['type'], $postData);
				if (!SeminarmanCustomfieldsLibrary::validateField($data['type'], $customFields[$data['id']], $data['required'])) {
					if ($data['type'] == 'checkboxtos')
						$message =  JText::sprintf('COM_SEMINARMAN_ACCEPT_TOS', $data['name']);
					else
						$message = JText::sprintf('COM_SEMINARMAN_FIELD_N_CONTAINS_IMPROPER_VALUES', $data['name']);
					$mainframe->enqueueMessage($message, 'error');
					return $this->redirect();
				}
			}
		}

		// list of courses
		$db = JFactory::getDBO();
		$db->setQuery('SELECT * FROM #__seminarman_courses WHERE id ='. $post['course_id']);
		if (!$db->query()) {
			JError::raiseError(500, $db->stderr(true));
			return;
		}

		$courseRows = $db->loadObject();

		if (!$params->get('enable_num_of_attendees')) {
			$post['attendees'] = 1;
		}
		$post['start_date'] = $courseRows->start_date;
		$post['finish_date'] = $courseRows->finish_date;
		$post['start_time'] = $courseRows->start_time;
		$post['finish_time'] = $courseRows->finish_time;
		$post['price_per_attendee'] = $courseRows->price;
		$post['price_total'] = $post['price_per_attendee'] * $post['attendees'];
		$post['price_vat'] = $courseRows->vat;
		
		
		// capacity check
		$applicationModel = $this->getModel('application');
	                if ( $params->get('current_capacity') > 0 ) {
                        $freespaces = $courseRows->capacity - (int)$applicationModel->getCurrentBookings();
                        if ( $freespaces < 1 ) {
                                //$mainframe->redirect(JRoute::_("index.php"), JText::_('COM_SEMINARMAN_COURSE_IS_FULL'));
                                $bgf = JText::_( 'COM_SEMINARMAN_COURSE_IS_FULL' );
                                $mainframe->enqueueMessage($bgf, 'error');
                                $mainframe->redirect(JRoute::_("index.php"));
                        }
                        else if ( $freespaces < $post['attendees']) {
                                $freespaces = $courseRows->capacity - (int)$applicationModel->getCurrentBookings();

                                $bgf = JText::sprintf('COM_SEMINARMAN_BOOKING_GREATER_FREESPACES',
                                        $post['attendees'],
                                        $freespaces
                                );
                                $mainframe->enqueueMessage($bgf, 'error');
                                $mainframe->redirect(JRoute::_("index.php"));
                        }
                }			
			
		// did this user already book that course?
		if (!$params->get('enable_multiple_bookings_per_user') && $applicationModel->getCurrentBookingsForUser($post['user_id']) > 0)
			$mainframe->redirect(JRoute::_("index.php"), JText::_('COM_SEMINARMAN_ALREADY_BOOKED'));
		
		$usersConfig = JComponentHelper::getParams('com_users');

$query_pricegroup2 = $db->getQuery(true);
$query_pricegroup2->select('*')
                  ->from('#__seminarman_pricegroups')
                  ->where('gid=2');
$db->setQuery($query_pricegroup2);
$priceg2 = $db->loadAssoc();
$priceg2_name = $priceg2['title'];
$priceg2_usg = json_decode($priceg2['jm_groups']);
$priceg2_reg = $priceg2['reg_group'];

$query_pricegroup3 = $db->getQuery(true);
$query_pricegroup3->select('*')
                  ->from('#__seminarman_pricegroups')
                  ->where('gid=3');
$db->setQuery($query_pricegroup3);
$priceg3 = $db->loadAssoc();
$priceg3_name = $priceg3['title'];
$priceg3_usg = json_decode($priceg3['jm_groups']);
$priceg3_reg = $priceg3['reg_group'];

$query_pricegroup4 = $db->getQuery(true);
$query_pricegroup4->select('*')
->from('#__seminarman_pricegroups')
->where('gid=4');
$db->setQuery($query_pricegroup4);
$priceg4 = $db->loadAssoc();
$priceg4_name = $priceg4['title'];
$priceg4_usg = json_decode($priceg4['jm_groups']);
$priceg4_reg = $priceg4['reg_group'];

$query_pricegroup5 = $db->getQuery(true);
$query_pricegroup5->select('*')
->from('#__seminarman_pricegroups')
->where('gid=5');
$db->setQuery($query_pricegroup5);
$priceg5 = $db->loadAssoc();
$priceg5_name = $priceg5['title'];
$priceg5_usg = json_decode($priceg5['jm_groups']);
$priceg5_reg = $priceg5['reg_group'];
		
		// register user
		if (($post['user_id'] == 0) && ($usersConfig->get('allowUserRegistration') != '0') && ($params->get('enable_bookings') != '3'))
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
    			if ($post['booking_price'][0] == 1) { // 2. price group
    			    if ($priceg2_reg != 0) $data['groups'] = array($priceg2_reg);	
    			}
    			if ($post['booking_price'][0] == 2) { // 3. price group
    				if ($priceg3_reg != 0) $data['groups'] = array($priceg3_reg);
    			}
    			if ($post['booking_price'][0] == 3) { // 4. price group
    				if ($priceg4_reg != 0) $data['groups'] = array($priceg4_reg);
    			}
    			if ($post['booking_price'][0] == 4) { // 5. price group
    				if ($priceg5_reg != 0) $data['groups'] = array($priceg5_reg);
    			}
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
    			if ($applicationModel->sendRegistrationEmail($usern, $password)) {
    				if (($params->get('trigger_virtuemart') == 1) && (!is_null($vmlink))) {
    					$mainframe->enqueueMessage(JText::_('COM_SEMINARMAN_REGISTRATION_MAIL_SENT'));
    				}
    			}
			}
			$post['user_id'] = $uid;
		}		
		
		if ($post['booking_price'][0] == 66) { // if booking in virtuemart, we gonna create a dumy booking in seminar manager first
			$post['price_per_attendee'] = 0;
			$post['price_total'] = 0;
			$post['status'] = 3;
		} elseif ($post['booking_price'][0] == 1) { // 2. price group
			$post['price_per_attendee'] = $courseRows->price2;
			$post['price_total'] = $post['price_per_attendee'] * $post['attendees'];
			$post['pricegroup'] = $priceg2_name;			
		} elseif ($post['booking_price'][0] == 2) { // 3. price group
			$post['price_per_attendee'] = $courseRows->price3;
			$post['price_total'] = $post['price_per_attendee'] * $post['attendees'];
			$post['pricegroup'] = $priceg3_name;			
		} elseif ($post['booking_price'][0] == 3) { // 4. price group
			$post['price_per_attendee'] = $courseRows->price4;
			$post['price_total'] = $post['price_per_attendee'] * $post['attendees'];
			$post['pricegroup'] = $priceg4_name;			
		} elseif ($post['booking_price'][0] == 4) { // 5. price group
			$post['price_per_attendee'] = $courseRows->price5;
			$post['price_total'] = $post['price_per_attendee'] * $post['attendees'];
			$post['pricegroup'] = $priceg5_name;			
		}

		// new: if not bill after paypal
		if ((($params->get('invoice_generate') == 1) && ($post['price_per_attendee'] > 0) && (!($params->get('enable_paypal') && $params->get('invoice_after_pay')))) || ($post['booking_price'][0] == 66)) {
			$post['invoice_filename_prefix'] = strtolower(str_replace(' ', '_', JText::_('COM_SEMINARMAN_INVOICE'))) . '_';
			$post['invoice_number'] = $applicationModel->getInvoiceNumber();
		} else {
			$post['invoice_number'] = 0-time();
		}		
		
		// save data in application table 
		if (!$applicationid = $applicationModel->store($post))
			return $this->setRedirect(JRoute::_($params->get('application_landingpage')), JText::_('COM_SEMINARMAN_ERROR_PROCESSING_APPLICATION'));
		
		$post['applicationid'] = $applicationid;

		// save custom fields
		$applicationModel->saveCustomfields($applicationid, $post['user_id'], $customFields);
		
		// create and save invoice (new: if not bill after paypal)
		if ($params->get('invoice_generate') == 1 && ($post['price_per_attendee'] > 0) && (!($params->get('enable_paypal') && $params->get('invoice_after_pay'))) && !($post['booking_price'][0] == 66))
		{
			if (!$template = $this->getModel('pdftemplate')->getTemplate($courseRows->invoice_template))
				return $this->setRedirect(JRoute::_($params->get('application_landingpage')), JText::_('COM_SEMINARMAN_ERROR_PROCESSING_APPLICATION'));
			
			$templateData = $applicationModel->getFieldValuesForTemplate($applicationid);
			$pdf = new PdfInvoice($template, $templateData);
			$pdf->store(JPATH_ROOT.DS.$params->get('invoice_save_dir').DS.$post['invoice_filename_prefix'].$post['invoice_number'].'.pdf');
		}

		if (($params->get('trigger_virtuemart') == 1) && (!is_null($vmlink))) {
			// JRequest::setVar('dummy_booking_' . $courseRows->id, $applicationid);
            $mainframe->setUserState('dummy_booking_' . $courseRows->id, $applicationid);
			
            // redirect to VirtueMart
            $this->setRedirect($vmlink);
			
		} else {
		
		    // send confimation mail with invoice to applicant (new: if not bill after paypal)
			if (($params->get('invoice_generate') == 1 && $params->get('invoice_attach') == 1 && ($post['price_per_attendee'] > 0)) && !($params->get('enable_paypal') && $params->get('invoice_after_pay')))
			    $attachment = $pdf->getFile();
		    else
			    $attachment = '';
		
		    // in all cases the confirmation email will be sent except one case: paypal enabled && price > 0 && bill after pay
		    if (!(($params->get('enable_paypal')) && (($post['price_per_attendee']) > 0) && ($params->get('invoice_after_pay')))) {
		        if (!$applicationModel->sendemail($post, $courseRows->email_template, $attachment))
			       return $this->setRedirect(JRoute::_($params->get('application_landingpage')), JText::_('COM_SEMINARMAN_ERROR_SENDING_EMAILS'));
		    }

		    // redirect to paypal view, if paypal is enabled
		    if (($params->get('enable_paypal')) && (($post['price_per_attendee']) > 0))
			    return $this->setRedirect(JRoute::_('index.php?option=com_seminarman&view=paypal&bookingid=' . $applicationid, false), JText::_('COM_SEMINARMAN_THANK_YOU_FOR_YOUR_APPLICATION').'!');

		    $this->setRedirect(JRoute::_($params->get('application_landingpage'),false), JText::_('COM_SEMINARMAN_THANK_YOU_FOR_YOUR_APPLICATION').'!');
		}
	}
	
	function cart() {
		
		$view = $this->getView('Courses', 'html');
		$model = $this->getModel('Courses');		
		$view->setModel($model, true);		
		// $view->setLayout('cart');		
		$view->display('cart');
		
	}
	
	function cancel() {
		
		$view = $this->getView('Courses', 'html');
		$model = $this->getModel('Courses');
		$view->setModel($model, true);
		$view->display();
	}

}

?>