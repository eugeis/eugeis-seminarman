<?php
/**
 * @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
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

class SeminarmanControllerEditfield extends SeminarmanController
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
        if ($post['type'] == 'checkboxtos')
        	$post['options'] = JRequest::getVar('options', '', 'post', 'string', JREQUEST_ALLOWHTML);

        $model = $this->getModel('editfield');
        
        $post['fieldcode']=strtoupper($post['fieldcode']);
        
        if (empty($post['name']))
        {
        	$msg = JText::_('COM_SEMINARMAN_MISSING_NAME');
        	$link = 'index.php?option=com_seminarman&view=editfields';
        }
        else if ($fieldid = $model->store($post))
        {
        	$row	= JTable::getInstance( 'editfield' , 'table' );
        	$row->load( $fieldid);
        	$groupOrdering	= isset($post['group']) ? $post['group'] : '';
        	$row->store( $groupOrdering );

            switch ($task)
            {
                case 'apply':
                    $link = 'index.php?option=com_seminarman&view=editfield&cid[]=' . (int)$model->get('id');
                    if (JRequest::getString('layout', '') == 'editgroup')
                    	$link .= '&layout=editgroup'; 
                    break;

                default:
                    $link = 'index.php?option=com_seminarman&view=editfields';
                    break;
            }
            $msg = JText::_('COM_SEMINARMAN_FIELD_SAVED');

            $cache = JFactory::getCache('com_seminarman');
            $cache->clean();

        } else
        {

            $msg = JText::_('COM_SEMINARMAN_ERROR_SAVING');

            $link = 'index.php?option=com_seminarman&view=editfields';
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
            $model = $this->getModel('editfields');

            if (!$model->publish($cid, 1))
            {
                JError::raiseError(500, $model->getError());
            }

            $total = count($cid);
            $msg = $total . ' ' . JText::_('COM_SEMINARMAN_OPERATION_SUCCESSFULL');
        }

        $this->setRedirect('index.php?option=com_seminarman&view=editfields', $msg);
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
            $model = $this->getModel('editfields');

            if (!$model->publish($cid, 0))
            {
                JError::raiseError(500, $model->getError());
            }

            $total = count($cid);
            $msg = $total . ' ' . JText::_('COM_SEMINARMAN_OPERATION_SUCCESSFULL');
            $cache = JFactory::getCache('com_seminarman');
            $cache->clean();
        }

        $this->setRedirect('index.php?option=com_seminarman&view=editfields', $msg);
    }

    function remove()
    {
        $cid = JRequest::getVar('cid', array(0), 'post', 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            $msg = '';
            JError::raiseWarning(500, JText::_('SELECT course DELETE'));
        } else
        {
            $model = $this->getModel('editfields');

            if (!$model->delete($cid))
            {
                JError::raiseError(500, JText::_('COM_SEMINARMAN_OPERATION_FAILED'));
            }

            $msg = JText::_('COM_SEMINARMAN_OPERATION_SUCCESSFULL');
            $cache = JFactory::getCache('com_seminarman');
            $cache->clean();
        }

        $this->setRedirect('index.php?option=com_seminarman&view=editfields', $msg);
    }


	function cancel()
	{

		JRequest::checkToken() or jexit('Invalid Token');

		$tag = JTable::getInstance('editfield', 'table');
		$tag->bind(JRequest::get('post'));
		$tag->checkin();

		$this->setRedirect('index.php?option=com_seminarman&view=editfields');
	}

    function edit()
    {
        JRequest::setVar('view', 'editfield');
        JRequest::setVar('hidemainmenu', 1);

        $model = $this->getModel('editfield');
        $user = JFactory::getUser();

        if ($model->isCheckedOut($user->get('id')))
        {
            $this->setRedirect('index.php?option=com_seminarman&view=editfields', JText::_('COM_SEMINARMAN_EDITED_BY_ANOTHER_ADMIN'));
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
