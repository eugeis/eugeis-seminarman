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

class SeminarmanControllerCategories extends SeminarmanController
{
    function __construct()
    {
        parent::__construct();

        $this->registerTask('add', 'edit');
        $this->registerTask('apply', 'save');
        $this->registerTask('accesspublic', 'access');
        $this->registerTask('accessregistered', 'access');
        $this->registerTask('accessspecial', 'access');
    }

    function save()
    {

        JRequest::checkToken() or jexit('Invalid Token');

        $task = JRequest::getVar('task');

        $post = JRequest::get('post');
        $post['text'] = JRequest::getVar('text', '', 'post', 'string', JREQUEST_ALLOWRAW);
       

        $model = $this->getModel('category');

        if ($model->store($post))
        {

            switch ($task)
            {
                case 'apply':
                    $link = 'index.php?option=com_seminarman&view=category&cid[]=' . (int)$model->get('id');
                    break;

                default:
                    $link = 'index.php?option=com_seminarman&view=categories';
                    break;
            }
            $msg = JText::_('COM_SEMINARMAN_RECORD_SAVED');

 //           $categoriesmodel = &$this->getModel('categories');
//            $categoriesmodel->access($model->get('id'), $model->get('access'));
//
//            $pubid = array();
//            $pubid[] = $model->get('id');
//            if ($model->get('published') == 1)
//            {
//                $categoriesmodel->publish($pubid, 1);
//            } else
//            {
//                $categoriesmodel->publish($pubid, 0);
//            }

            $cache = JFactory::getCache('com_seminarman');
            $cache->clean();

        } else
        {

            $msg = JText::_('COM_SEMINARMAN_ERROR_SAVING');

            $link = 'index.php?option=com_seminarman&view=category';
        }

        $model->checkin();

        $this->setRedirect($link, $msg);
    }

    function publish()
    {

        JRequest::checkToken() or jexit('Invalid Token');

        $cid = JRequest::getVar('cid', array(0), 'post', 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            $msg = '';
            JError::raiseWarning(500, JText::_('COM_SEMINARMAN_SELECT_ITEM'));
        } else
        {

            $model = $this->getModel('categories');

            if (!$model->publish($cid, 1))
            {
                JError::raiseError(500, $model->getError());
            }

            $msg = JText::_('COM_SEMINARMAN_OPERATION_SUCCESSFULL');

            $cache = JFactory::getCache('com_seminarman');
            $cache->clean();
        }

        $this->setRedirect('index.php?option=com_seminarman&view=categories', $msg);
    }

    function unpublish()
    {

        JRequest::checkToken() or jexit('Invalid Token');

        $cid = JRequest::getVar('cid', array(0), 'post', 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            $msg = '';
            JError::raiseWarning(500, JText::_('COM_SEMINARMAN_SELECT_ITEM'));
        } else
        {

            $model = $this->getModel('categories');

            if (!$model->publish($cid, 0))
            {
                JError::raiseError(500, $model->getError());
            }

            $msg = JText::_('COM_SEMINARMAN_OPERATION_SUCCESSFULL');

            $cache = JFactory::getCache('com_seminarman');
            $cache->clean();
        }

        $this->setRedirect('index.php?option=com_seminarman&view=categories', $msg);
    }

    function orderup()
    {

        JRequest::checkToken() or jexit('Invalid Token');

        $model = $this->getModel('categories');
        $model->move(-1);

        $cache = JFactory::getCache('com_seminarman');
        $cache->clean();

        $this->setRedirect('index.php?option=com_seminarman&view=categories');
    }

    function orderdown()
    {

        JRequest::checkToken() or jexit('Invalid Token');

        $model = $this->getModel('categories');
        $model->move(1);

        $cache = JFactory::getCache('com_seminarman');
        $cache->clean();

        $this->setRedirect('index.php?option=com_seminarman&view=categories');
    }

    function saveorder()
    {

        JRequest::checkToken() or jexit('Invalid Token');

        $cid = JRequest::getVar('cid', array(0), 'post', 'array');
        $order = JRequest::getVar('order', array(0), 'post', 'array');

        $model = $this->getModel('categories');
        if (!$model->saveorder($cid, $order))
        {
            $msg = '';
            JError::raiseWarning(500, $model->getError());
        }

        $cache = JFactory::getCache('com_seminarman');
        $cache->clean();

        $msg = JText::_('COM_SEMINARMAN_OPERATION_SUCCESSFULL');
        $this->setRedirect('index.php?option=com_seminarman&view=categories', $msg);
    }

    function remove()
    {

        JRequest::checkToken() or jexit('Invalid Token');

        $cid = JRequest::getVar('cid', array(0), 'post', 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            $msg = '';
            JError::raiseWarning(500, JText::_('COM_SEMINARMAN_SELECT_ITEM'));
        } else
        {

            $model = $this->getModel('categories');

            $msg = $model->delete($cid);

            $cache = JFactory::getCache('com_seminarman');
            $cache->clean();
        }

        $this->setRedirect('index.php?option=com_seminarman&view=categories', $msg);
    }

    function cancel()
    {

        JRequest::checkToken() or jexit('Invalid Token');

        $category = JTable::getInstance('seminarman_categories', '');
        $category->bind(JRequest::get('post'));
        $category->checkin();

        $this->setRedirect('index.php?option=com_seminarman&view=categories');
    }

    function access()
    {

        JRequest::checkToken() or jexit('Invalid Token');

        $cid = JRequest::getVar('cid', array(0), 'post', 'array');
        $id = (int)$cid[0];
        $task = JRequest::getVar('task');

        if ($task == 'accesspublic')
        {
            $access = 0;
        } elseif ($task == 'accessregistered')
        {
            $access = 1;
        } else
        {
            $access = 2;
        }

        $model = $this->getModel('categories');

        if (!$model->access($id, $access))
        {
            JError::raiseError(500, $model->getError());
        } else
        {
            $cache = JFactory::getCache('com_seminarman');
            $cache->clean();
        }

        $this->setRedirect('index.php?option=com_seminarman&view=categories');
    }

    function edit()
    {



        JRequest::setVar('view', 'category');
        JRequest::setVar('hidemainmenu', 1);

        $model = $this->getModel('category');
        $user = JFactory::getUser();

        if ($model->isCheckedOut($user->get('id')))
        {
            $this->setRedirect('index.php?option=com_seminarman&view=categories', JText::_('COM_SEMINARMAN_RECORD_EDITED'));
        }

        $model->checkout($user->get('id'));

        parent::display();
    }
}