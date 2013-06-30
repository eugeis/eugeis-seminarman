<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

//jimport('joomla.application.component.controlleradmin');
jimport('joomla.application.component.controller');

require_once JPATH_ROOT.'/components/com_seminarman/helpers/application.php';

/**
 * Users list controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_users
 * @since       1.6
 */
class seminarmanControllerUsers extends seminarmanController
//class SeminarmanControllerUsers extends JControllerAdmin
{
	/**
	 * @var    string  The prefix to use with controller messages.
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_USERS_USERS';

	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @return  UsersControllerUsers
	 *
	 * @since   1.6
	 * @see     JController
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask('block',		'changeBlock');
		$this->registerTask('unblock',		'changeBlock');
	}

	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since	1.6
	 */
	public function getModel($name = 'User', $prefix = 'UsersModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}


	public function changeCourses()   {
		$userIds	= JRequest::getVar('cid', array(), '', 'array');
		if($userIds){
			$courseId = JRequest::getVar('selectCourse', '', 'POST');
			$course = ApplicationHelper::loadCourse($courseId);
			$users = $this->getUsersByIds($userIds);
			$fillErrors = array();
			$catTitles = array();
			$changeCourses = JRequest::getVar('changeCourses', '', 'POST');;
			if($changeCourses == 'bookCourse') {
				$this->bookCourseForUsers($course, $catTitles, $users, $fillErrors);
				$msg = JText::_( 'COM_SEMINARMAN_BOOKED_USERS' ) . ' ' . join(', ', $fillErrors);
			} else {
				$this->cancelCourseForUsers($course, $catTitles, $users, $fillErrors);							
				$msg = JText::_( 'COM_SEMINARMAN_CANCELED_USERS' );
			}
		}else{
			$msg = JText::_( 'COM_SEMINARMAN_NO_USERS_IN_USERGROUPS' );
		}
		$this->setRedirect('index.php?option=com_seminarman&view=users', $msg);
	}
	
	private function cancelCourseForUsers($course, $catTitles, $users, $fillErrors)
	{
		$msgEmailError = JText::_( 'COM_SEMINARMAN_CANCELING_EMAIL_ERROR' );
		$sendEmail = !ApplicationHelper::isCourseOld($course);
		$model = $this->getModel( 'application' );
		foreach($users as $user) {
			$userId = $user->id;
			// did this user already book that course?
			$applicationId = ApplicationHelper::getIdByCourseAndUser($course->id, $userId);
			if ($applicationId) {
				if($sendEmail){
					ApplicationHelper::sendemail($application_id, $user, $course, $catTitles, $fillErrors, 3);
				}
				ApplicationHelper::cancel($applicationId);
			}
		}
	}	
	
	private function bookCourseForUsers($course, $catTitles, $users, $fillErrors)
	{
		$msgEmailError = JText::_( 'COM_SEMINARMAN_BOOKING_EMAIL_ERROR' );
		$sendEmail = !ApplicationHelper::isCourseOld($course);
		foreach($users as $user) {
			$userId = $user->id;
			// did this user already book that course?
			$applicationId = ApplicationHelper::getIdByCourseAndUser($course->id, $userId);
			if (!$applicationId) {
				$application_id = ApplicationHelper::book($user, $course, $fillErrors);
				if($application_id != null ){
					if($sendEmail){
						ApplicationHelper::sendemail($application_id, $user, $course, $catTitles, $fillErrors, $course->email_template);
					}
				}
			}
		}
	}
	
	private function getUsersByIds($userIds)
	{
		// Get a database object.
		$db = JFactory::getDbo();

		$db->setQuery('SELECT u.* FROM #__users AS u WHERE u.id IN ('.join(",",$userIds).')');

		$users = $db->loadObjectList();

		return $users;
	}
	
	/**
	 * Method to change the block status on a record.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public function changeBlock()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$ids	= JRequest::getVar('cid', array(), '', 'array');
		$values	= array('block' => 1, 'unblock' => 0);
		$task	= $this->getTask();
		$value	= JArrayHelper::getValue($values, $task, 0, 'int');

		if (empty($ids))
		{
			JError::raiseWarning(500, JText::_('COM_USERS_USERS_NO_ITEM_SELECTED'));
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			// Change the state of the records.
			if (!$model->block($ids, $value))
			{
				JError::raiseWarning(500, $model->getError());
			}
			else
			{
				if ($value == 1)
				{
					$this->setMessage(JText::plural('COM_USERS_N_USERS_BLOCKED', count($ids)));
				}
				elseif ($value == 0)
				{
					$this->setMessage(JText::plural('COM_USERS_N_USERS_UNBLOCKED', count($ids)));
				}
			}
		}

		$this->setRedirect('index.php?option=com_users&view=users');
	}

	/**
	 * Method to activate a record.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public function activate()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$ids	= JRequest::getVar('cid', array(), '', 'array');

		if (empty($ids))
		{
			JError::raiseWarning(500, JText::_('COM_USERS_USERS_NO_ITEM_SELECTED'));
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			// Change the state of the records.
			if (!$model->activate($ids))
			{
				JError::raiseWarning(500, $model->getError());
			}
			else
			{
				$this->setMessage(JText::plural('COM_USERS_N_USERS_ACTIVATED', count($ids)));
			}
		}

		$this->setRedirect('index.php?option=com_users&view=users');
	}
}
