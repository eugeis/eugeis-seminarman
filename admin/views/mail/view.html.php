<?php
/**
 * @version		$Id: view.html.php 21655 2011-06-23 05:43:24Z chdemko $
 * @package		Joomla.Administrator
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * Users mail view.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_seminarman
 */
class SeminarmanViewMail extends JView
{
	/**
	 * @var object form object
	 */

	/**
	 * Display the view
	 */
	function display($tpl = null)
	{
		// Get data from the model

		$this->addToolbar();		
		
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		JRequest::setVar('hidemainmenu', 1);

		JToolBarHelper::title(JText::_('COM_SEMINARMAN_SEND_EMAIL'), 'massmail.png');
		JToolBarHelper::custom('send', 'send.png', 'send_f2.png', 'COM_SEMINARMAN_EMAIL_SEND', false);
		JToolBarHelper::cancel();
	}
}
