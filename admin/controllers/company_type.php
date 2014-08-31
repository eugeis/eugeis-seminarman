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

class seminarmanControllercompany_type extends seminarmanController
{

    function __construct($config = array())
    {
        parent::__construct($config);

        $this->registerTask('add', 'display');
        $this->registerTask('edit', 'display');
        $this->registerTask('apply', 'save');
        $this->childviewname = 'company_type';
        $this->parentviewname = 'company_types';
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
        $post = JRequest::get('post');
        $cid = JRequest::getVar('cid', array(0), 'post', 'array');
        $post['id'] = (int)$cid[0];
        $model = $this->getModel($this->childviewname);

        if ($id = $model->store($post))
        {
            $msg = JText::_('COM_SEMINARMAN_RECORD_SAVED');
        } else
        {
            $msg = JText::_('COM_SEMINARMAN_ERROR_SAVING');
        }

        $model->checkin();
        if ($this->getTask() == 'apply')
        {
        	$link = 'index.php?option=com_seminarman&controller=company_type&task=edit&cid[]='.(int)$id;
        }
        else
	        $link = 'index.php?option=com_seminarman&view=' . $this->parentviewname;
        $this->setRedirect($link, $msg);
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

        if (!$model->delete($cid))
        {
            echo "<script> alert('" . $model->getError(true) .
                "'); window.history.go(-1); </script>\n";
        }
        $msg = JText::_('COM_SEMINARMAN_OPERATION_SUCCESSFULL');
        $this->setRedirect('index.php?option=com_seminarman&view=' . $this->parentviewname,
            $msg);
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
        $this->setRedirect('index.php?option=com_seminarman&view=' . $this->parentviewname,
            $msg);
    }

    function goback()
    {

        JRequest::checkToken() or jexit('Invalid Token');

        $this->setRedirect('index.php?option=com_seminarman&view=settings');
    }


}

?>