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

class SeminarmanControllerTemplates extends SeminarmanController
{
    function __construct()
    {
        parent::__construct();

        $this->registerTask('add', 'edit');
        $this->registerTask('apply', 'save');
        
        $task = JRequest::getCmd('task');
        switch (strtolower($task))
        {
        	case 'publish':
        		$this->changeContent(1);
        		break;
        
        	case 'unpublish':
        		$this->changeContent(0);
        		break;
        
        	default:
        		break;
        }
    }
    
    function orderup()
    {
    
    	JRequest::checkToken() or jexit('Invalid Token');
    
    	$model = $this->getModel('templates');
    	$model->move(-1);
    
    	$this->setRedirect('index.php?option=com_seminarman&view=templates');
    }
    
    function orderdown()
    {
    
    	JRequest::checkToken() or jexit('Invalid Token');
    
    	$model = $this->getModel('templates');
    	$model->move(1);
    
    	$this->setRedirect('index.php?option=com_seminarman&view=templates');
    }
    
    function saveorder()
    {
    
    	JRequest::checkToken() or jexit('Invalid Token');
    
    	$cid = JRequest::getVar('cid', array(0), 'post', 'array');
    	$order = JRequest::getVar('order', array(0), 'post', 'array');
    
    	$model = $this->getModel('templates');
    	if (!$model->saveorder($cid, $order))
    	{
    		$msg = '';
    		JError::raiseError(500, $model->getError());
    	} else
    	{
    		$msg = JText::_('COM_SEMINARMAN_OPERATION_SUCCESSFULL');
    	}
    
    	$this->setRedirect('index.php?option=com_seminarman&view=templates', $msg);
    }

    function save()
    {

        JRequest::checkToken() or jexit('Invalid Token');

        $task = JRequest::getVar('task');

        $post = JRequest::get('post');

        $model = $this->getModel('template');

        if ($model->store($post))
        {

            switch ($task)
            {
                case 'apply':
                    $link = 'index.php?option=com_seminarman&controller=templates&task=edit&cid[]=' . (int)$model->get('id');
                    break;

                default:
                    $link = 'index.php?option=com_seminarman&view=templates';
                    break;
            }
            $msg = JText::_('COM_SEMINARMAN_RECORD_SAVED');

            $cache = JFactory::getCache('com_seminarman');
            $cache->clean();

        } else
        {
            $msg = JText::_('COM_SEMINARMAN_ERROR_SAVING');
            JError::raiseError(500, $model->getError());
            $link = 'index.php?option=com_seminarman&view=templates';
        }

        $model->checkin();

        $this->setRedirect($link, $msg);
    }

    function remove()
    {

        JRequest::checkToken() or jexit('Invalid Token');

        $cid = JRequest::getVar('cid', array(0), 'post', 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            JError::raiseError(500, JText::_('COM_SEMINARMAN_SELECT_ITEM'));
        }

        $model = $this->getModel('templates');

        if (!$model->delete($cid))
        {
            $msg = '';
            JError::raiseError(500, $model->getError());
        } else
        {
            $msg = JText::_('COM_SEMINARMAN_OPERATION_SUCCESSFULL');

            $cache = JFactory::getCache('com_seminarman');
            $cache->clean();
        }

        $this->setRedirect('index.php?option=com_seminarman&view=templates', $msg);
    }

    function edit()
    {
        JRequest::setVar('view', 'template');
        JRequest::setVar('hidemainmenu', 1);

        $model = $this->getModel('template');
        $user = JFactory::getUser();

        if ($model->isCheckedOut($user->get('id')))
        {
            $this->setRedirect('index.php?option=com_seminarman&view=templates', JText::_('COM_SEMINARMAN_EDITED_BY_ANOTHER_ADMIN'));
        }

        $model->checkout($user->get('id'));

        parent::display();
    }

    function gettags()
    {
        $id = JRequest::getInt('id', 0);
        $model = $this->getModel('template');
        $tags = $model->gettags();

        $used = null;

        if ($id)
        {
            $used = $model->getusedtags($id);
        }
        if (!is_array($used))
        {
            $used = array();
        }

        $rsp = '<div class="qf_tagbox">';
        $n = count($tags);
        for ($i = 0, $n; $i < $n; $i++)
        {
            $tag = $tags[$i];

            if (($i % 5) == 0)
            {
                if ($i != 0)
                {
                    $rsp .= '</div>';
                }
                $rsp .= '<div class="qf_tagline">';
            }
            $rsp .= '<span class="qf_tag"><span class="qf_tagidbox"><input type="checkbox" name="tag[]" value="' .
                $tag->id . '"' . (in_array($tag->id, $used) ? 'checked="checked"' : '') .
                ' /></span>' . $tag->name . '</span>';
        }
        $rsp .= '</div>';
        $rsp .= '</div>';
        $rsp .= '<div class="clear"></div>';
        $rsp .= '<div class="qf_addtag">';
        $rsp .= '<label for="addtags">' . JText::_('ADD TAG') . '</label>';
        $rsp .= '<input type="text" id="tagname" class="inputbox" size="30" />';
        $rsp .= '<input type="button" class="button" value="' . JText::_('ADD') .
            '" onclick="addtag()" />';
        $rsp .= '</div>';

        echo $rsp;
    }

    function changeContent($state = 0)
    {
        $mainframe = JFactory::getApplication();
        $model = $this->getModel('templates');

        JRequest::checkToken() or jexit('Invalid Token');

        $db = JFactory::getDBO();
        $user = JFactory::getUser();

        $cid = JRequest::getVar('cid', array(), 'post', 'array');
        JArrayHelper::toInteger($cid);
        $option = JRequest::getCmd('option');
        $task = JRequest::getCmd('task');
        $rtask = JRequest::getCmd('returntask', '', 'post');
        if ($rtask)
        {
            $rtask = '&task=' . $rtask;
        }

        if (count($cid) < 1)
        {
            $redirect = JRequest::getVar('redirect', '', 'post', 'int');            
            $msg = JText::_('COM_SEMINARMAN_SELECT_ITEM');
            $mainframe->redirect('index.php?option=' . $option . $rtask . '&sectionid=' . $redirect,
                $msg, 'error');
        }

        $uid = $user->get('id');
        $total = count($cid);
        $cids = implode(',', $cid);

        $query = 'UPDATE #__seminarman_templates' . ' SET state = ' . (int)$state .
            ' WHERE id IN ( ' . $cids . ' ) AND ( checked_out = 0 OR (checked_out = ' . (int)
            $uid . ' ) )';
        $db->setQuery($query);
        if (!$db->query())
        {
            JError::raiseError(500, $db->getErrorMsg());
            return false;
        }

        if (count($cid) == 1)
        {
            $row = JTable::getInstance('seminarman_templates', '');

        }

        switch ($state)
        {
            case - 1:
                $msg = JText::sprintf('COM_SEMINARMAN_OPERATION_SUCCESSFULL');
                break;

            case 1:
                $msg = JText::sprintf('COM_SEMINARMAN_OPERATION_SUCCESSFULL');
                break;

            case 0:
            default:
                break;
        }

        $cache = JFactory::getCache('com_seminarman');
        $cache->clean();


        $mainframe->redirect('index.php?option=' . $option . "&view=templates", $msg);
    }
    
    function cancel()
    {
    
    	JRequest::checkToken() or jexit('Invalid Token');
    
    	$course = JTable::getInstance('seminarman_templates', '');
    	$course->bind(JRequest::get('post'));
    	$course->checkin();
    
    	$this->setRedirect('index.php?option=com_seminarman&view=templates');
    }

}
