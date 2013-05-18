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

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

require_once JPATH_ROOT.'/components/com_seminarman/helpers/application.php';

class seminarmanControllerapplication extends seminarmanController
{

    function __construct($config = array())
    {
        parent::__construct($config);

        $this->registerTask('add', 'display');
        $this->registerTask('edit', 'display');
        $this->registerTask('apply', 'save');
        $this->childviewname = 'application';
        $this->parentviewname = 'applications';
    }

    function display( $cachable = false, $urlparams = false )
    {
        switch ($this->getTask())
        {
            case 'add':
                {
                    JRequest::setVar('hidemainmenu', 1);
                    JRequest::setVar('layout', 'form');
                    JRequest::setVar('view', $this->childviewname);
                    JRequest::setVar('edit', false);

                    $model = $this->getModel($this->childviewname);
                    $model->checkout();
                }

                break;
            case 'edit':
                {
                    JRequest::setVar('hidemainmenu', 1);
                    JRequest::setVar('layout', 'form');
                    JRequest::setVar('view', $this->childviewname);
                    JRequest::setVar('edit', true);

                    $model = $this->getModel($this->childviewname);
                    $model->checkout();
                }

                break;
        }

        parent::display();
    }


    function save()
    {

		JRequest::checkToken() or jexit('Invalid Token');
        $post       = JRequest::get('post');
        $cid        = JRequest::getVar('cid', array(0), 'post', 'array');
        $post['id'] = (int)$cid[0];
        $model      = $this->getModel($this->childviewname);
        $userId     = JRequest::getVar( 'user_id' , '' , 'POST' );

        if ($applicationid = $model->store($post))
        {
            $msg = JText::_('COM_SEMINARMAN_RECORD_SAVED');
        } else
        {
            $msg = JText::_('ECOM_SEMINARMAN_ERROR_SAVING');
        }

    	// Process and save custom fields
    	$model = $this->getModel( 'application' );
    	$values	= array();
    	$customfields	= $model->getEditableCustomfields( $applicationid );

    	CMFactory::load( 'libraries' , 'customfields' );


    	foreach( $customfields->fields as $group => $fields )
    	{
    		foreach( $fields as $data )
    		{
    			// Get value from posted data and map it to the field.
    			// Here we need to prepend the 'field' before the id because in the form, the 'field' is prepended to the id.
    			$postData				= JRequest::getVar( 'field' . $data['id'] , '' , 'POST' );
    			$values[ $data['id'] ]	= SeminarmanCustomfieldsLibrary::formatData( $data['type']  , $postData );

    			// @rule: Validate custom customfields if necessary
    			if( !SeminarmanCustomfieldsLibrary::validateField( $data['type'] , $values[ $data['id'] ] , $data['required'] ) )
    			{
    				// If there are errors on the form, display to the user.
    				$message	= JText::sprintf('COM_SEMINARMAN_FIELD_N_CONTAINS_IMPROPER_VALUES' ,  $data['name'] );
    				$this->setredirect( 'index.php?option=com_seminarman&controller=application&task=edit&cid[]=' . $post['id'] , $message , 'error' );

					return;
    			}
    		}
    	}
    	//save data from custom fields
    	$model->saveCustomfields($applicationid, $userId, $values);

        $model->checkin();
        if ($this->getTask() == 'apply')
        {
        	$link = 'index.php?option=com_seminarman&controller=application&task=edit&cid[]='.$applicationid;
        }
        else
        	$link = 'index.php?option=com_seminarman&view=' . $this->parentviewname;
        $this->setRedirect($link, $msg);
    }


    function remove()
    {
        JRequest::checkToken() or jexit('Invalid Token');
        
        $params = JComponentHelper::getParams('com_seminarman');
        if ($params->get('enable_bookings_deletable') == 1)
        {
	        $cid = JRequest::getVar('cid', array(), 'post', 'array');
	        JArrayHelper::toInteger($cid);
	
	        if (count($cid) < 1)
	            JError::raiseError(500, JText::_('COM_SEMINARMAN_SELECT_ITEM'));
	
	        $model = $this->getModel($this->childviewname);
	
	        if (!$model->delete($cid))
	            echo "<script> alert('" . $model->getError(true) ."'); window.history.go(-1); </script>\n";

	        $msg = JText::_('COM_SEMINARMAN_OPERATION_SUCCESSFULL');
        }
        else
        	$msg = '';
        
        $this->setRedirect('index.php?option=com_seminarman&view=' . $this->parentviewname, $msg);
    }


    function publish()
    {
        JRequest::checkToken() or jexit('Invalid Token');
        $cid = JRequest::getVar('cid', array(), 'post', 'array');
        JArrayHelper::toInteger($cid);

        if (count($cid) < 1)
            JError::raiseError(500, JText::_('SCOM_SEMINARMAN_SELECT_ITEM'));

        $model = $this->getModel($this->childviewname);

        if (!$model->publish($cid, 1))
            echo "<script> alert('" . $model->getError(true) ."'); window.history.go(-1); </script>\n";

        $this->setRedirect('index.php?option=com_seminarman&view=' . $this->parentviewname);
    }


    function unpublish()
    {
        JRequest::checkToken() or jexit('Invalid Token');
        $cid = JRequest::getVar('cid', array(), 'post', 'array');
        JArrayHelper::toInteger($cid);

        if (count($cid) < 1)
            JError::raiseError(500, JText::_('COM_SEMINARMAN_SELECT_ITEM'));

        $model = $this->getModel($this->childviewname);

        if (!$model->publish($cid, 0))
            echo "<script> alert('" . $model->getError(true) ."'); window.history.go(-1); </script>\n";

        $this->setRedirect('index.php?option=com_seminarman&view=' . $this->parentviewname);
    }
    
    function trash()
    {
    	JRequest::checkToken() or jexit('Invalid Token');
    	$cid = JRequest::getVar('cid', array(), 'post', 'array');
    	JArrayHelper::toInteger($cid);
    
    	if (count($cid) < 1)
    		JError::raiseError(500, JText::_('COM_SEMINARMAN_SELECT_ITEM'));
    
    	$model = $this->getModel($this->childviewname);
    
    	if (!$model->publish($cid, -2))
    		echo "<script> alert('" . $model->getError(true) ."'); window.history.go(-1); </script>\n";
    
    	$this->setRedirect('index.php?option=com_seminarman&view=' . $this->parentviewname, JText::_('COM_SEMINARMAN_OPERATION_SUCCESSFULL'));
    }


    function cancel()
    {
        JRequest::checkToken() or jexit('Invalid Token');
        $model = $this->getModel($this->childviewname);
        $model->checkin();
        $this->setRedirect('index.php?option=com_seminarman&view=' . $this->parentviewname);
    }


    function orderup()
    {
        JRequest::checkToken() or jexit('Invalid Token');
        $model = $this->getModel($this->childviewname);
        $model->move(-1);
        $this->setRedirect('index.php?option=com_seminarman&view=' . $this->parentviewname);
    }


    function orderdown()
    {
        JRequest::checkToken() or jexit('Invalid Token');
        $model = $this->getModel($this->childviewname);
        $model->move(1);
        $this->setRedirect('index.php?option=com_seminarman&view=' . $this->parentviewname);
    }


    function saveorder()
    {
        JRequest::checkToken() or jexit('Invalid Token');
        $cid = JRequest::getVar('cid', array(), 'post', 'array');
        $order = JRequest::getVar('order', array(), 'post', 'array');
        JArrayHelper::toInteger($cid);
        JArrayHelper::toInteger($order);
        $model = $this->getModel($this->childviewname);
        $model->saveorder($cid, $order);
        $this->setRedirect('index.php?option=com_seminarman&view=' . $this->parentviewname, JText::_('COM_SEMINARMAN_OPERATION_SUCCESSFULL'));
    }

	function changestatus()   {
		// Check for request forgeries
		JRequest::checkToken( 'get' ) or jexit( 'Invalid Token' );
		$cid  = JRequest::getVar( 'cid' );
		//JArrayHelper::toInteger($cid);
		$status = JRequest::getVar('status');

		$msg = ApplicationHelper::setStatus($cid, $status);
		if($msg == null) {
		$msg = JText::_( 'COM_SEMINARMAN_STATUS_UPDATED' );
		}
		$this->setRedirect( 'index.php?option=com_seminarman&view=applications', $msg  );
	}

	function changeMultiAttributtes()   {
		JRequest::checkToken() or jexit('Invalid Token');
		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		JArrayHelper::toInteger($cid);
	
		if (count($cid) < 1)
			JError::raiseError(500, JText::_('SCOM_SEMINARMAN_SELECT_ITEM'));
	
		$model = $this->getModel($this->childviewname);
	
		JArrayHelper::toInteger($cid);
		
		if(JRequest::getVar( 'changeNote' , false , 'POST' )) {
			$ok = $model->changeMultiNote($cid, JRequest::getVar( 'note' , null, 'POST' ));
		}

		if(JRequest::getVar( 'changeAttendance' , false , 'POST' )) {
			$ok = $model->changeMultiAttendance($cid, JRequest::getVar( 'attendance' , null, 'POST' ));
		}
		
		if(JRequest::getVar( 'changeStatus' , false , 'POST' )) {
			$ok = $model->changeMultiStatus($cid, JRequest::getVar( 'status' , 0, 'POST' ));
		}
		
	    if ($ok) {
            $msg = count($cid) . ' ' . JText::_('COM_SEMINARMAN_RECORDS_SAVED');
        } else {
            $msg = JText::_('ECOM_SEMINARMAN_ERROR_SAVING');
        }
		
		$this->setRedirect('index.php?option=com_seminarman&view=' . $this->parentviewname, $msg);
	}
	
	function changecustomfields()   {
		JRequest::checkToken() or jexit('Invalid Token');
		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		JArrayHelper::toInteger($cid);
	
		if (count($cid) < 1)
			JError::raiseError(500, JText::_('SCOM_SEMINARMAN_SELECT_ITEM'));
	
		$model = $this->getModel($this->childviewname);
	
		JArrayHelper::toInteger($cid);
	
		$this->saveCustomFields($cid, true);
	
		$this->setRedirect('index.php?option=com_seminarman&view=' . $this->parentviewname);
	}
	
	function saveCustomFields($applicationids, $considerChangeField=false)
	{
		// Process and save custom fields
		$model =& $this->getModel( 'application' );
	
		CMFactory::load( 'libraries' , 'customfields' );
	
		//get first application id to get fields names and values
		$values = $this->findCommonCustomFieldsValues($model, $applicationids[0], $considerChangeField);
	
		foreach( $applicationids as $applicationid )
		{
			$user_id = $model->getUserId($applicationid);
			$model->saveCustomfields($applicationid, $user_id, $values);
		}
	
		if ($this->getTask() == 'apply')
		{
			$link = 'index.php?option=com_seminarman&controller=application&task=edit&cid[]='.$applicationid;
		}
		else
			$link = 'index.php?option=com_seminarman&view=' . $this->parentviewname;
		$this->setRedirect($link, $msg);
	}
	
	function findCommonCustomFieldsValues($model, $applicationid, $considerChangeField = false)
	{
		$values	= array();
	
		$customfields	= $model->getEditableCustomfields( $applicationid );
	
		foreach( $customfields->fields as $group => $fields )
		{
			foreach( $fields as $data )
			{
				// Get value from posted data and map it to the field.
				// Here we need to prepend the 'field' before the id because in the form, the 'field' is prepended to the id.
				if( !$considerChangeField || JRequest::getVar( 'change_field' . $data['id'] , false , 'POST' ) )
				{
					$postData				= JRequest::getVar( 'field' . $data['id'] , '' , 'POST' );
					$values[ $data['id'] ]	= SeminarmanCustomfieldsLibrary::formatData( $data['type']  , $postData );
	
					// @rule: Validate custom customfields if necessary
					if( !SeminarmanCustomfieldsLibrary::validateField( $data['type'] , $values[ $data['id'] ] , $data['required'] ) )
					{
						// If there are errors on the form, display to the user.
						$message	= JText::sprintf('COM_SEMINARMAN_FIELD_N_CONTAINS_IMPROPER_VALUES' ,  $data['name'] );
						$this->setredirect( 'index.php?option=com_seminarman&controller=application&task=edit&cid[]=' . $post['id'] , $message , 'error' );
	
						return;
					}
				}
			}
		}
		return $values;
	}
		
	function notify()
	{

        // JModel::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_users' . DS . 'models' , 'UsersModel');
    	
        // $mailmodel = JModel::getInstance( 'mail', 'UsersModel' );		

		// $this->addViewPath( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_users' . DS . 'views' );
		// $mailview = $this->getView('Mail', 'html', 'UsersView'); 
		// $mailview->addTemplatePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_users' . DS . 'views' . DS . 'mail' . DS . 'tmpl');
        // $mailview->setModel($mailmodel);
        // $mailview->form = $mailmodel->get('Form');
		// $mailview->display();
		// JRequest::setVar('view', 'mail');
        $cid = JRequest::getVar('cid', array(), 'post', 'array');
        JArrayHelper::toInteger($cid);

        if (count($cid) < 1)
            JError::raiseError(500, JText::_('SCOM_SEMINARMAN_SELECT_ITEM'));

        $cids = implode(',', $cid);

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('a.email')
              ->from('#__seminarman_application AS a')
              ->where('a.id IN ( '. $cids .' )');
        $db->setQuery($query);
        $result = $db->loadColumn(); 

        $app = JFactory::getApplication();
        $app->setUserState('com_seminarman.call.mail.from', 'applications');
		
		$mailmodel = $this->getModel('Mail');
		// $mailmodel->start =  'application';
		$mailmodel->receipts = $result;
		// $state = $mailmodel->getState();
		$mailview = $this->getView('mail', 'html', 'SeminarmanView');
		$mailview->setModel($mailmodel, true);
		$mailview->display();
		// parent::display();
	}
	
	function notify_booking()
	{

        // JModel::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_users' . DS . 'models' , 'UsersModel');
    	
        // $mailmodel = JModel::getInstance( 'mail', 'UsersModel' );		

		// $this->addViewPath( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_users' . DS . 'views' );
		// $mailview = $this->getView('Mail', 'html', 'UsersView'); 
		// $mailview->addTemplatePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_users' . DS . 'views' . DS . 'mail' . DS . 'tmpl');
        // $mailview->setModel($mailmodel);
        // $mailview->form = $mailmodel->get('Form');
		// $mailview->display();
		// JRequest::setVar('view', 'mail');
        $cid = JRequest::getVar('cid', array(), 'post', 'array');
        $cid = Intval($cid[0]);
        // JArrayHelper::toInteger($cid);

        if (empty($cid))
            JError::raiseError(500, JText::_('SCOM_SEMINARMAN_SELECT_ITEM'));

        // $cids = implode(',', $cid);

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('a.email AS email, a.status AS status, a.invoice_filename_prefix AS prefix, a.invoice_number AS number, c.title AS title')
              ->from('#__seminarman_application AS a LEFT JOIN #__seminarman_courses AS c ON c.id = a.course_id')
              ->where('a.id = '. $cid);
        $db->setQuery($query);
        $result = $db->loadAssoc(); 
        
        $app = JFactory::getApplication();
        $app->setUserState('com_seminarman.call.mail.from', 'application');
        
        $params = JComponentHelper::getParams('com_seminarman');;
        $bill_file = JPATH_ROOT.DS.$params->get('invoice_save_dir').DS.$result['prefix'] . $result['number'] . '.pdf';
		
		$mailmodel = $this->getModel('Mail');
		// $mailmodel->start =  'application';
		$mailmodel->receipts = Array('0' => $result['email']);
		$mailmodel->attach = $bill_file;
		
		$stati = intval($result['status']);
		if ($stati == 0) {
			$stati_text = JText::_( 'COM_SEMINARMAN_SUBMITTED' );
		} elseif ($stati == 1) {
			$stati_text = JText::_( 'COM_SEMINARMAN_PENDING' );
//		} elseif ($stati == 2) {
//			$stati_text = JText::_( 'COM_SEMINARMAN_PAID' );
		} elseif ($stati == 3) {
			$stati_text = JText::_( 'COM_SEMINARMAN_CANCELED' );
		}

		$course_title = $result['title'];
		$bill_number = $result['number'];
		
		$mailmodel->subject = JText::_( 'COM_SEMINARMAN_YOUR_BILL' ) . ' ' . $bill_number . ' ' .  JText::_( 'COM_SEMINARMAN_FOR_BOOKING' ) . ' "' . $course_title . '": ' . $stati_text;
		
		// $state = $mailmodel->getState();
		$mailview = $this->getView('mail', 'html', 'SeminarmanView');
		$mailview->setModel($mailmodel, true);
		$mailview->display();
		// parent::display();
	}	
}