<?php
/**
 * @version $Id: 1.5.4 2009-10-15
 * @package			Course Manager
 * @subpackage		Component
 * @author			Profinvent {@link http://www.profinvent.com}
 * @copyright 		(C) Profinvent - Joomla Experts
 * @license   		GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.controller' );

/**
 * seminarman session Controller
 *
 * @package    Joomla
 * @subpackage seminarman
 * @since 1.5
 */

	class seminarmanControllersession extends seminarmanController{

		function __construct($config = array())   {
			parent::__construct($config);
			// Register Extra tasks
			$this->registerTask( 'add',  'display' );
			$this->registerTask( 'edit', 'display' );
			$this->registerTask('apply', 'save');
				
		}


		function display($cachable = false, $urlparams = false)   {
			switch($this->getTask())      {
				case 'add'     :
					{
						JRequest::setVar( 'hidemainmenu', 1 );
						JRequest::setVar( 'layout', 'form'  );
						JRequest::setVar( 'view'  , 'session');
						JRequest::setVar( 'edit', false );
						// Checkout the session
						$model = $this->getModel('session');
						$model->checkout();
					}

					break;
				case 'edit'    :
					{
						JRequest::setVar( 'hidemainmenu', 1 );
						JRequest::setVar( 'layout', 'form'  );
						JRequest::setVar( 'view'  , 'session');
						JRequest::setVar( 'edit', true );
						// Checkout the session
						$model = $this->getModel('session');
						$model->checkout();
					}

					break;
			}

		parent::display();
	}


	function save()   {
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$post = JRequest::get('post');
		$cid  = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$post['id'] = (int) $cid[0];
		$courseid = JRequest::getVar( 'courseid', 0, 'post', 'int' );
		$model = $this->getModel('session');

		if ($id = $model->store($post)) {
			$msg = JText::_( 'COM_SEMINARMAN_RECORD_SAVED' );
		} else {
			$msg = JText::_( 'COM_SEMINARMAN_ERROR_SAVING' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
		if ($this->getTask() == 'apply')
			$link = 'index.php?option=com_seminarman&controller=session&task=edit&cid[]='.(int)$id;
		else
			$link = 'index.php?option=com_seminarman&view=sessions';
		$this->setRedirect($link, $msg);
	}


	function remove()   {
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'COM_SEMINARMAN_SELECT_ITEM' ) );
		}

		$model = $this->getModel('session');

		if(!$model->delete($cid)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
        $msg = JText::_( 'COM_SEMINARMAN_OPERATION_SUCCESSFULL' );
		$this->setRedirect( 'index.php?option=com_seminarman&view=sessions', $msg );
	}

	//method for removing record drectly from course details screen

	function remove_session()   {
		// Check for request forgeries
		JRequest::checkToken( 'get') or jexit( 'Invalid Token' );
		$courseid = JRequest::getVar( 'courseid' );
		$sessionid = JRequest::getVar( 'sessionid' );
		//if (count( $sessionid ) < 1) {
		//   JError::raiseError(500, JText::_( 'No item to delete' ) );
		//}
		$model = $this->getModel('session');

		if(!$model->deleteone($sessionid)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_( 'COM_SEMINARMAN_OPERATION_SUCCESSFULL' );
		$this->setRedirect( 'index.php?option=com_seminarman&controller=course&view=course&task=edit&cid[]='. $courseid, $msg );
	}


	function publish()   {
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'COM_SEMINARMAN_SELECT_ITEM' ) );
		}

		$model = $this->getModel('session');

		if(!$model->publish($cid, 1)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_seminarman&view=sessions' );
	}


	function unpublish()   {
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'COM_SEMINARMAN_SELECT_ITEM' ) );
		}

		$model = $this->getModel('session');

		if(!$model->publish($cid, 0)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_seminarman&view=sessions' );
	}


	function cancel()   {
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		// Checkin the session
		$model = $this->getModel('session');
		$model->checkin();
		$courseid = JRequest::getVar( 'courseid', 0, 'post', 'int' );
		$this->setRedirect( 'index.php?option=com_seminarman&&view=sessions' );
	}
		function goback()   {
			// Check for request forgeries
			JRequest::checkToken() or jexit( 'Invalid Token' );
		$this->setRedirect( 'index.php?option=com_seminarman&view=courses' );
		}

	function orderup()   {
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$model = $this->getModel('session');
		$model->move(-1);
		$this->setRedirect( 'index.php?option=com_seminarman&view=sessions');
	}


	function orderdown()   {
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$model = $this->getModel('session');
		$model->move(1);
		$this->setRedirect( 'index.php?option=com_seminarman&view=sessions');
	}


	function saveorder()   {
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$cid  = JRequest::getVar( 'cid', array(), 'post', 'array' );
		$order   = JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);
		$model = $this->getModel('session');
		$model->saveorder($cid, $order);
		$msg = 'COM_SEMINARMAN_OPERATION_SUCCESSFULL';
		$this->setRedirect( 'index.php?option=com_seminarman&view=sessions', $msg );
	}

}