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

class seminarmanControllerEmailtemplate extends seminarmanController
{
    function __construct($config = array())
    {
        parent::__construct($config);

        $this->registerTask('add', 'display');
        $this->registerTask('edit', 'display');
        $this->registerTask('apply', 'save');
        $this->childviewname = 'emailtemplate';
        $this->parentviewname = 'settings';
    }


    function display($cachable = false, $urlparams = false)
    {
    	JRequest::setVar('hidemainmenu', 1);
    	JRequest::setVar('layout', 'form');
    	JRequest::setVar('view', $this->childviewname);
    	JRequest::setVar('edit', ($this->getTask() == 'edit'));
    	 
    	$model = $this->getModel($this->childviewname);
        parent::display();
    }

    
    function save()
    {
        $model = $this->getModel('emailtemplate', 'seminarmanModel');
        $data = JRequest::get('post');
        
    	if ($id = $model->storeEmailTemplate()) {
    		$msg = JText::_('COM_SEMINARMAN_RECORD_SAVED');
    	} else {
    		$msg = JText::_('COM_SEMINARMAN_ERROR_SAVING');
    	}
        
        if ($this->getTask() == 'apply') {
        	$link = 'index.php?option=com_seminarman&view=emailtemplate&layout=default&id='.(int)$id;
        } else {
        	$link = 'index.php?option=com_seminarman&view=settings';
        }
        
        $this->setRedirect($link, $msg);
    }
    

    function remove()
    {
        JRequest::checkToken() or jexit('Invalid Token');
        $cid = JRequest::getVar('cid', array(), 'post', 'array');
        JArrayHelper::toInteger($cid);

        if (count($cid) < 1) {
            JError::raiseError(500, JText::_('COM_SEMINARMAN_SELECT_ITEM'));
        }

        $model = $this->getModel($this->childviewname);

        if ($model->delete($cid)) {
        	$msg = JText::_('COM_SEMINARMAN_OPERATION_SUCCESSFULL');
        } else {
        	$msg = $model->getError();
        }
        
        $this->setRedirect('index.php?option=com_seminarman&view=settings', $msg);
    }
    

    function cancel()
    {
        JRequest::checkToken() or jexit('Invalid Token');

        $model = $this->getModel($this->childviewname);
        $this->setRedirect('index.php?option=com_seminarman&view=' . $this->parentviewname);
    }


    function setDefault()
    {
    	JRequest::checkToken() or jexit('Invalid Token');
    	$id = JRequest::getVar('id', 0, 'post', 'int');
    	$model = $this->getModel($this->childviewname);
    	$model->setDefault($id);
    	$this->setRedirect('index.php?option=com_seminarman&view=' . $this->parentviewname );
    }
}

?>