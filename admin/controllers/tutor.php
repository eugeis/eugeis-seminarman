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

class seminarmanControllertutor extends seminarmanController
{

    function __construct($config = array())
    {
        parent::__construct($config);

        $this->registerTask('add', 'display');
        $this->registerTask('edit', 'display');
        $this->registerTask('apply', 'save');
        $this->childviewname = 'tutor';
        $this->parentviewname = 'tutors';
    }

    function display()
    {
    	
    	JRequest::setVar('layout', 'form');
 	
    	if($this->getTask() == ''){
    		JRequest::setVar('hidemainmenu', 0);
    		JRequest::setVar('view', $this->parentviewname);
    	 } else {
    	 	JRequest::setVar('hidemainmenu', 1);
    	 	JRequest::setVar('view', $this->childviewname);
    	 }
    	JRequest::setVar('edit', ($this->getTask() == 'edit'));
    	
    	$model = $this->getModel($this->childviewname);
    	$model->checkout();
        parent::display();
    }
    
    
    function save()
    {
    	JRequest::checkToken() or jexit('Invalid Token');
    
    	$model   = $this->getModel($this->childviewname);
    	$row     = JTable::getInstance('tutor', 'Table');
    	$post    = JRequest::get('post');
    	$id      = JRequest::getInt('id', 0, 'post');
    	$tmpl_id = JRequest::getInt('template_id', 0, 'post');
    	$task    = JRequest::getVar('task');
    	$file    = JRequest::getVar('logofilename', null, 'files', 'array');
    	
    	$link_save   = JRoute::_('index.php?option=com_seminarman&view=tutors', false);
    
    	if (!$row->load($id)) {
    		$this->setMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $row->getError()), 'error');
    		$this->setRedirect($link_save);
    		return false;
    	}
    
    	$row->logofilename = $model->UploadImage($file);
    	
    	
    	if (!$row->id){
    		$post['ordering'] = $row->getNextOrder();
    	}
    	
    	if (($post['juserstate'] == 1) && ($post['user_id'] == 0)) {   		
    		
            JModel::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_users' . DS . 'models' , 'UsersModel');
    	
            $modeljuser = JModel::getInstance( 'user', 'UsersModel' );
            
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('*')
                  ->from('#__seminarman_usergroups AS g')
                  ->where('g.sm_id = 2');
            $db->setQuery($query);
            $result = $db->loadAssoc();
          // get trainer group id
            $trainer_id = $result["jm_id"];
         
            if ($post['juser_option'] == 0) {
            // if create a new joomla user for trainer  
                $juser = Array();
             // $juser['isjuser'] = $post['juserstate'];
                $juser['name'] = $post['firstname'] . ' ' . $post['lastname'];
                $juser['username'] = $post['user_name'];
                $juser['password'] = $post['jpassword1'];
                $juser['password2'] = $post['jpassword2'];
                $juser['email'] = $post['jemail'];
                $juser['sendEmail'] = '0';
                $juser['block'] = '0';
                $juser['id'] = 0; 
            
                // put the new joomla user into trainer group
                $juser['groups'] = array($trainer_id);
            
                $jstate = $modeljuser->getState();
                $jstate->set('user.id', 0);
                // var_dump($modeljuser);
                // exit;
                if($modeljuser->save($juser)){
            	    jimport('joomla.user.helper');
                    $joomid = JUserHelper::getUserId($juser['username']);
            	    $post['user_id'] = $joomid;
                }else{
            	    JError::raiseNotice('SOME_ERROR_CODE', JText::_('COM_SEMINARMAN_ERROR_SAVE_JOOMLA_ACC'));
            	    $this->setRedirect(JRoute::_('index.php?option=com_seminarman&controller=tutor&task=edit&cid[]='.(int)$row->id, false));
            	    return false;
                }
            } elseif($post['juser_option'] == 1){
            // if select a joomla user for trainer
                $juser_select = $post['juser_id'];
	            jimport('joomla.user.helper');
	            // add the selected joomla user to trainer group
	            if($juser_select > 0) {
	                if(JUserHelper::addUserToGroup($juser_select, $trainer_id)){
	            	    $post['user_id'] = $juser_select;
	                } else {
            	        JError::raiseNotice('SOME_ERROR_CODE', JText::_('COM_SEMINARMAN_ERROR_ASSIGN_JOOMLA_ACC'));
            	        $this->setRedirect(JRoute::_('index.php?option=com_seminarman&controller=tutor&task=edit&cid[]='.(int)$row->id, false));
            	        return false;	            	
	                }
	            } else {
            	    JError::raiseNotice('SOME_ERROR_CODE', JText::_('COM_SEMINARMAN_ERROR_INVALID_JOOMLA_ACC'));
            	    $this->setRedirect(JRoute::_('index.php?option=com_seminarman&controller=tutor&task=edit&cid[]='.(int)$row->id, false));
            	    return false;	            	
	            }
	            
            }
            
            // var_dump($juser);
            // exit;

    	} 
    	
        if (!$row->save($post)) {
    		$this->setMessage(JText::_('JLIB_APPLICATION_ERROR_SAVE_FAILED', $row->getError()), 'error');
    		$this->setRedirect($link_save);
    		return false;
    	} 

        $dispatcher = JDispatcher::getInstance();
        JPluginHelper::importPlugin('seminarman');
        
        // fire vmengine
        $results = $dispatcher->trigger('onProcessTrainer', array($post));
    	
    	$template_remove = JRequest::getVar('template_remove', array(), 'post', 'array');
    	if (!empty($template_remove)) {
    		$model->removeTemplates($template_remove, (int)$row->id);
    	}
    
    	if ($tmpl_id) {
    		$template_prio = JRequest::getInt('template_prio', 0, 'post');
    		$model->addTemplate($tmpl_id, $template_prio, (int)$row->id);
    	}
    
    	$model->checkin();
    	
    	$this->setMessage(JText::_('COM_SEMINARMAN_RECORD_SAVED'));
    	if ($task == 'apply')
    		$this->setRedirect(JRoute::_('index.php?option=com_seminarman&controller=tutor&task=edit&cid[]='.(int)$row->id, false));
    	else
    		$this->setRedirect($link_save);
    
    	return true;
    }


    function remove()
    {
        JRequest::checkToken() or jexit('Invalid Token');
        
        $cid = JRequest::getVar('cid', array(), 'post', 'array');
        JArrayHelper::toInteger($cid);

        if (count($cid) < 1)
        {
            JError::raiseError(500, JText::_('COM_SEMINARMAN_SELECT_ITEM'));
        }

        $model = $this->getModel($this->childviewname);

        if ($model->delete($cid)) {
        	$msg = JText::_('COM_SEMINARMAN_OPERATION_SUCCESSFULL');
        } else {
        	$msg = $model->getError();
        }

        $this->setRedirect('index.php?option=com_seminarman&view=' . $this->parentviewname, $msg);
    }


    function publish()
    {

        JRequest::checkToken() or jexit('Invalid Token');
        $cid = JRequest::getVar('cid', array(), 'post', 'array');
        JArrayHelper::toInteger($cid);

        if (count($cid) < 1)
        {
            JError::raiseError(500, JText::_('COM_SEMINARMAN_SELECT_ITEM'));
        }

        $model = $this->getModel($this->childviewname);

        if (!$model->publish($cid, 1))
        {
            echo "<script> alert('" . $model->getError(true) .
                "'); window.history.go(-1); </script>\n";
        }

        $this->setRedirect('index.php?option=com_seminarman&view=' . $this->parentviewname);
    }


    function unpublish()
    {

        JRequest::checkToken() or jexit('Invalid Token');
        $cid = JRequest::getVar('cid', array(), 'post', 'array');
        JArrayHelper::toInteger($cid);

        if (count($cid) < 1)
        {
            JError::raiseError(500, JText::_('COM_SEMINARMAN_SELECT_ITEM'));
        }

        $model = $this->getModel($this->childviewname);

        if (!$model->publish($cid, 0))
        {
            echo "<script> alert('" . $model->getError(true) .
                "'); window.history.go(-1); </script>\n";
        }

        $this->setRedirect('index.php?option=com_seminarman&view=' . $this->parentviewname);
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
        $msg = 'COM_SEMINARMAN_OPERATION_SUCCESSFULL';
        $this->setRedirect('index.php?option=com_seminarman&view=' . $this->parentviewname, $msg);
    }

    function goback()
    {
        JRequest::checkToken() or jexit('Invalid Token');
        $this->setRedirect('index.php?option=com_seminarman&view=settings');
    }
}

?>