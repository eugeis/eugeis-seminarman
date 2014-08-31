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

class SeminarmanControllerTags extends SeminarmanController
{
    function __construct()
    {
        parent::__construct();

        $this->registerTask('add', 'edit');
        $this->registerTask('apply', 'save');
    }

    function save()
    {

        JRequest::checkToken() or jexit('Invalid Token');

        $task = JRequest::getVar('task');

        $post = JRequest::get('post');

        $model = $this->getModel('tag');

        if ($model->store($post))
        {

            switch ($task)
            {
                case 'apply':
                    $link = 'index.php?option=com_seminarman&view=tag&cid[]=' . (int)$model->get('id');
                    break;

                default:
                    $link = 'index.php?option=com_seminarman&view=tags';
                    break;
            }
            $msg = JText::_('COM_SEMINARMAN_RECORD_SAVED');

            $cache = JFactory::getCache('com_seminarman');
            $cache->clean();

        } else
        {

            $msg = JText::_('COM_SEMINARMAN_ERROR_SAVING');

            $link = 'index.php?option=com_seminarman&view=tag';
        }

        $model->checkin();

        $this->setRedirect($link, $msg);
    }

    function publish()
    {
        $cid = JRequest::getVar('cid', array(0), 'post', 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            $msg = '';
            JError::raiseWarning(500, JText::_('COM_SEMINARMAN_SELECT_ITEM'));
        } else
        {
            $model = $this->getModel('tags');

            if (!$model->publish($cid, 1))
            {
                JError::raiseError(500, $model->getError());
            }

            $total = count($cid);
            $msg = $total . ' ' . JText::_('COM_SEMINARMAN_OPERATION_SUCCESSFULL');
        }

        $this->setRedirect('index.php?option=com_seminarman&view=tags', $msg);
    }

    function unpublish()
    {
        $cid = JRequest::getVar('cid', array(0), 'post', 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            $msg = '';
            JError::raiseWarning(500, JText::_('COM_SEMINARMAN_SELECT_ITEM'));
        } else
        {
            $model = $this->getModel('tags');

            if (!$model->publish($cid, 0))
            {
                JError::raiseError(500, $model->getError());
            }

            $total = count($cid);
            $msg = $total . ' ' . JText::_('COM_SEMINARMAN_OPERATION_SUCCESSFULL');
            $cache = JFactory::getCache('com_seminarman');
            $cache->clean();
        }

        $this->setRedirect('index.php?option=com_seminarman&view=tags', $msg);
    }

    function remove()
    {
        $cid = JRequest::getVar('cid', array(0), 'post', 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            $msg = '';
            JError::raiseWarning(500, JText::_('COM_SEMINARMAN_SELECT_ITEM'));
        } else
        {
            $model = $this->getModel('tags');

            if (!$model->delete($cid))
            {
                JError::raiseError(500, JText::_('COM_SEMINARMAN_OPERATION_FAILED'));
            }

            $msg = JText::_('COM_SEMINARMAN_OPERATION_SUCCESSFULL');
            $cache = JFactory::getCache('com_seminarman');
            $cache->clean();
        }

        $this->setRedirect('index.php?option=com_seminarman&view=tags', $msg);
    }

    function cancel()
    {
        JRequest::checkToken() or jexit('Invalid Token');

        $tag = JTable::getInstance('seminarman_tags', '');
        $tag->bind(JRequest::get('post'));
        $tag->checkin();

        $this->setRedirect('index.php?option=com_seminarman&view=tags');
    }

    function edit()
    {
        JRequest::setVar('view', 'tag');
        JRequest::setVar('hidemainmenu', 1);

        $model = $this->getModel('tag');
        $user = JFactory::getUser();

        if ($model->isCheckedOut($user->get('id')))
        {
            $this->setRedirect('index.php?option=com_seminarman&view=tags', JText::_('COM_SEMINARMAN_RECORD_EDITED'));
        }

        $model->checkout($user->get('id'));

        parent::display();
    }

    function addtag()
    {
        $name = JRequest::getString('name', '');
        $model = $this->getModel('tag');
        $model->addtag($name);
    }
}