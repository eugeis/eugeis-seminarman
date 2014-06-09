<?php
/**
 * @version		$Id: mail.php 22315 2011-11-01 11:12:41Z github_bot $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Users mail model.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_seminarman
 * @since	1.6
 */
class SeminarmanModelMail extends JModelAdmin
{
	/**
	 * Method to get the row form.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	var $receipts;
	var $subject;
	var $form;
	var $attach;
	
    function __construct()
    {
        parent::__construct();
        
        $this->form = $this->getForm();

        if (!empty($receipts) && is_array($receipts)) {
        	
        } else {
        	$this->receipts = array();
        }
    }
	
	function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_seminarman.mail', 'mail', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}
	
    function buildReceiptList(){
    	
		$listReceipts = '<select size="10" multiple="multiple" name="jform[receipt][]" id="jform_receipt" style="clear: left; min-width: 160px;">';
		foreach ($this->receipts as $receipt) {
			$listReceipts .= '<option selected="selected" value="' . $receipt . '">' . $receipt . '</option>';
		}
		$listReceipts .= '</select>';
		return $listReceipts;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_seminarman.display.mail.data', array());

		return $data;
	}

	/**
	 * Override preprocessForm to load the user plugin group instead of content.
	 *
	 * @param	object	A form object.
	 * @param	mixed	The data expected for the form.
	 * @throws	Exception if there is an error in the form event.
	 * @since	1.6
	 */
	protected function preprocessForm(JForm $form, $data, $group = 'user')
	{
		parent::preprocessForm($form, $data, $group);
	}

	public function send()
	{
		// Initialise variables.
		$data	= JRequest::getVar('jform', array(), 'post', 'array');
		$app	= JFactory::getApplication();
		$user	= JFactory::getUser();
		$db		= $this->getDbo();


		$mode		= array_key_exists('mode',$data) ? intval($data['mode']) : 0;
		$subject	= array_key_exists('subject',$data) ? $data['subject'] : '';
		$receipt		= array_key_exists('receipt',$data) ? $data['receipt'] : 0;
		// $recurse	= array_key_exists('recurse',$data) ? intval($data['recurse']) : 0;
		$bcc		= array_key_exists('bcc',$data) ? intval($data['bcc']) : 0;
		// $disabled	= array_key_exists('disabled',$data) ? intval($data['disabled']) : 0;
		$message_body = array_key_exists('message',$data) ? $data['message'] : '';
		
		$cc = array_key_exists('cc',$data) ? trim($data['cc']) : '';
		
		$attach	= array_key_exists('attach',$data) ? intval($data['attach']) : 0;
		
		$attach_file = $data['bill_file'];
		
		if ($cc <> '') {
			$cc = str_replace(';', ',', $cc);
			$cc_arr = array_filter(explode(',', $cc));
			$hasCC = true;
			jimport('joomla.mail.helper');
			foreach ($cc_arr as $cc_add) {
 			    if(!JMailHelper::isEmailAddress($cc_add)) {
 				    $app->enqueueMessage(JText::_('Invalid Email Address (CC)'),'warning');
                    $hasCC = false;
 			    }
			}
		}else {
			$hasCC = false;
		}

		// automatically removes html formatting
		if (!$mode) {
			$message_body = JFilterInput::getInstance()->clean($message_body, 'string');
		}

		// Check for a message body and subject
		if (!$message_body || !$subject) {
			$app->setUserState('com_seminarman.display.mail.data', $data);
			$this->setError(JText::_('COM_SEMINARMAN_MAIL_PLEASE_FILL_IN_THE_FORM_CORRECTLY'));
			return false;
		}

		$rows = $receipt;

		// Check to see if there are any users in this group before we continue
		if (!count($rows)) {
			$app->setUserState('com_seminarman.display.mail.data', $data);			
			$this->setError(JText::_('COM_SEMINARMAN_MAIL_NO_USERS_COULD_BE_FOUND_IN_THIS_GROUP'));
			return false;
		}

		// Get the Mailer
		$mailer = JFactory::getMailer();
		$params = JComponentHelper::getParams('com_users');

		// Build email message format.
		$mailer->setSender(array($user->email, $user->name));
		$mailer->setSubject($params->get('mailSubjectPrefix') . stripslashes($subject));
		$mailer->setBody($message_body . $params->get('mailBodySuffix'));
		$mailer->IsHTML($mode);

		// Add recipients
		if ($bcc) {
			$mailer->addBCC($rows);
			// $mailer->addRecipient($app->getCfg('mailfrom'));
		} else {
			$mailer->addRecipient($rows);
		}
		
		if($hasCC){
			$mailer->addCC($cc_arr);
		}
		
		if($attach){
			$mailer->addAttachment($attach_file);
		}

		// Send the Mail
		$rs	= $mailer->Send();

		// Check for an error
		if (JError::isError($rs)) {
			$app->setUserState('com_seminarman.display.mail.data', $data);
			$this->setError($rs->getError());
			return false;
		} elseif (empty($rs)) {
			$app->setUserState('com_seminarman.display.mail.data', $data);
			$this->setError(JText::_('COM_SEMINARMAN_MAIL_THE_MAIL_COULD_NOT_BE_SENT'));
			return false;
		} else {
			// Fill the data (specially for the 'mode', 'group' and 'bcc': they could not exist in the array
			// when the box is not checked and in this case, the default value would be used instead of the '0'
			// one)
			$data['mode']=$mode;
			$data['subject']=$subject;
			$data['receipt']=$receipt;
			// $data['recurse']=$recurse;
			$data['bcc']=$bcc;
			$data['message']=$message_body;
			$app->setUserState('com_seminarman.display.mail.data', array());
			$app->enqueueMessage(JText::plural('COM_SEMINARMAN_MAIL_EMAIL_SENT_TO_N_USERS', count($rows)),'message');
			return true;
		}
	}
}
