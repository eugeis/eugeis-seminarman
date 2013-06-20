<?php
/**
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View class for a list of users.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_users
 * @since		1.6
 */
class SeminarmanViewUsers extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		
		require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_seminarman' . DS .
				'helpers' . DS . 'seminarman.php');
		
		if(JHTMLSeminarman::UserIsCourseManager()){
			
			$db = JFactory::getDBO();
			$uri = JFactory::getURI();
			$childviewname = 'Users';
			$document = JFactory::getDocument();
			$params = JComponentHelper::getParams('com_seminarman');
			$lang = JFactory::getLanguage();
			$requestURL = $uri->toString();
				
			$document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend.css');
			if ($lang->isRTL())
			{
				$document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend_rtl.css');
			}
			
			JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_HOME'), 'index.php?option=com_seminarman');
			JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_APPLICATIONS'), 'index.php?option=com_seminarman&view=applications');
			JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_USERS'), 'index.php?option=com_seminarman&view=users',true);
			JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_COURSES'), 'index.php?option=com_seminarman&view=courses');
			JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_TEMPLATES'),'index.php?option=com_seminarman&view=templates');
			JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_CATEGORIES'), 'index.php?option=com_seminarman&view=categories');
			JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_TAGS'), 'index.php?option=com_seminarman&view=tags');
			JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_TUTORS'), 'index.php?option=com_seminarman&view=tutors');
			JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_SETTINGS'),'index.php?option=com_seminarman&view=settings');
		
			$this->items		= $this->get('Items');
			$this->pagination	= $this->get('Pagination');
			$this->state		= $this->get('State');
	
			// Check for errors.
			if (count($errors = $this->get('Errors'))) {
				JError::raiseError(500, implode("\n", $errors));
				return false;
			}
	
			// Include the component HTML helpers.
			JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
			
			// get all templates
			$query = 'SELECT id AS value, CONCAT(title, \' (\', id, \')\') as text FROM #__seminarman_courses ORDER BY title';
			
			$db->setQuery($query);
			$courses = $db->loadObjectList();
			
			foreach ($courses as $course)
				$courseList[] = JHTML::_('select.option', $course->value, JText::_($course->text));
			
			// template aus post
			$courseId = JRequest::getVar('courseId', 0);
			if ($courseId == 0)
			if (!empty($row->courseId))
				$courseId = $row->courseId;
			
			$this->coursesList = $courseList;
			//$lists['coursesList'] = JHTML::_('select.genericlist', $types, 'courseId', 'class="inputbox" size="1" ', 'value', 'text', $courseId);
			
			$this->assignRef('requestURL', $requestURL);
				
			$this->addToolbar();
			parent::display($tpl);
		}else{
			 
			$app = JFactory::getApplication();
			$app->redirect('index.php?option=com_seminarman', 'Only seminar manager group can access templates.');
			 
		}
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		$canDo	= UsersHelper::getActions();

		JToolBarHelper::title(JText::_('COM_USERS_VIEW_USERS_TITLE'), 'user');

// 		if ($canDo->get('core.create')) {
// 			JToolBarHelper::addNew('user.add');
// 		}
// 		if ($canDo->get('core.edit')) {
// 			JToolBarHelper::editList('user.edit');
// 		}

// 		if ($canDo->get('core.edit.state')) {
// 			JToolBarHelper::divider();
// 			JToolBarHelper::publish('users.activate', 'COM_USERS_TOOLBAR_ACTIVATE', true);
// 			JToolBarHelper::unpublish('users.block', 'COM_USERS_TOOLBAR_BLOCK', true);
// 			JToolBarHelper::custom('users.unblock', 'unblock.png', 'unblock_f2.png', 'COM_USERS_TOOLBAR_UNBLOCK', true);
// 			JToolBarHelper::divider();
// 		}

// 		if ($canDo->get('core.delete')) {
// 			JToolBarHelper::deleteList('', 'users.delete');
// 			JToolBarHelper::divider();
// 		}

// 		if ($canDo->get('core.admin')) {
// 			JToolBarHelper::preferences('com_users');
// 			JToolBarHelper::divider();
// 		}

// 		JToolBarHelper::help('JHELP_USERS_USER_MANAGER');
	}
}
