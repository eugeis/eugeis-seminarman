<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
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
**/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

class SeminarmanControllerMail extends SeminarmanController
{
    function __construct()
    {
        parent::__construct();
    }

    function display()
    {
        parent::display();
    }

	
	public function send()
	{
		// Check for request forgeries.
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		$app = JFactory::getApplication();

		$model = $this->getModel('Mail');
		
		if ($model->send()) {
			$type = 'message';
		} else {
			$type = 'error';
		}

		$msg = $model->getError();
		
		if (($app->getUserState('com_seminarman.call.mail.from')=='application') || ($app->getUserState('com_seminarman.call.mail.from')=='applications')) {
		    $this->setredirect('index.php?option=com_seminarman&view=applications', $msg, $type);
		} else {
			$app->enqueueMessage('Hola Claudy!', 'message');
		}
	}
	
	function cancel()
	{
		// Check for request forgeries.
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		$app = JFactory::getApplication();
		// if(JRequest::getVar('start') == 'application') {
		if (($app->getUserState('com_seminarman.call.mail.from')=='application') || ($app->getUserState('com_seminarman.call.mail.from')=='applications')) {
            $this->setRedirect('index.php?option=com_seminarman&view=applications');
		} else {
    	    $app->enqueueMessage('Hola Claudy!', 'message');			
		}
	}
}
