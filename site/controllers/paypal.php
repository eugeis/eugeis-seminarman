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
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

/**
* seminarman course Controller
*
* @package Course Manager
* @subpackage seminarman
* @since 1.5.0
*/
class seminarmanControllerPaypal extends seminarmanController{

	
	function success() // Order was successful...
    {
        $mainframe = JFactory::getApplication();
        $params = $mainframe->getParams();
        
        // Setup class
        require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'classes' . DS . 'paypal.class.php'); // include the class file
        $p = new paypal_class; // initiate an instance of the class
        if ($params->get('enable_paypal') == 2) {
          $p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url
        } else {
          $p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr'; // paypal url
        }

        // setup a variable for this script (ie: 'http://www.micahcarrick.com/paypal.php')
        $this_script = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

        $model = $this->getModel('paypal');
        if (isset($_POST)) {
        	$mainframe->enqueueMessage(JText::_('COM_SEMINARMAN_THANKS_FOR_PAYMENT'));
        } else {
        	$mainframe->enqueueMessage(JText::_('COM_SEMINARMAN_PAYMENT_NOT_COMPLETED'));
        }
    }

    function cancel() // Order was canceled...
    {
    	$mainframe = JFactory::getApplication();
    	
        // The order was canceled before being completed.
        $mainframe->enqueueMessage(JText::_('COM_SEMINARMAN_PAYMENT_CANCELED'));
        
    }

    function ipn() // Paypal is calling page for IPN validation...
    {
        require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'classes' . DS . 'paypal.class.php'); // include the class file
        $p = new paypal_class; // initiate an instance of the class
        $mainframe = JFactory::getApplication();
        $params = $mainframe->getParams();
        if ($params->get('enable_paypal') == 2) {
          $p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr'; // testing paypal url
        } else {
          $p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';     // paypal url
        }
        $this_script = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

        if ($p->validate_ipn()){
            $config = JFactory::getConfig();

            $model = $this->getModel('paypal');
            if ($model->updatestatusIPN($p->ipn_data['item_number'], $p->ipn_data['txn_id'])){
                $mailSender = JFactory::getMailer();
                // $mailSender->addRecipient($config->getValue('mailfrom'));
                $mailSender->addRecipient($config->get('mailfrom'));
                $mailSender->addBCC($params->get('component_email'));
                // $mailSender->setSender(array($config->getValue('mailfrom') , $config->getValue('mailfrom')));
                $mailSender->setSender(array($config->get('mailfrom') , $config->get('mailfrom')));
                $mailSender->setSubject(JText::_('COM_SEMINARMAN_SEND_MSG_IPN_ADMIN_SUBJECT') . ' - ' . $p->ipn_data['transaction_subject'] . ' - ' . $p->ipn_data['last_name']);
                $email_body = sprintf (JText::_('COM_SEMINARMAN_SEND_MSG_IPN_ADMIN'));

                foreach ($p->ipn_data as $key => $value){
                    $body .= "\n$key: $value";
                }
                $email_body = $email_body . $body;
                $email_body = html_entity_decode($email_body, ENT_QUOTES);
                $mailSender->setBody($email_body);
                jimport('joomla.utilities.mail');
                if (!$mailSender->Send()){
                    $this->setError(JText::_('COM_SEMINARMAN_EMAIL_NOT_SENT'));
                }
            }
        }
    }

}

?>