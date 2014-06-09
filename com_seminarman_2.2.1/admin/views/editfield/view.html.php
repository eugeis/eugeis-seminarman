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

jimport('joomla.application.component.view');

class SeminarmanViewEditfield extends JView
{

    function display($tpl = null)
    {
        $mainframe = JFactory::getApplication();

        $document = JFactory::getDocument();
        $user = JFactory::getUser();
        $lang = JFactory::getLanguage();
    	JHTML::_('behavior.tooltip');

        $cid = JRequest::getVar('cid');

        $document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend.css');
        if ($lang->isRTL())
        {
            $document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend_rtl.css');
        }

        if ($cid)
        {
            if (JRequest::setVar('layout')=='editgroup') {
            	JToolBarHelper::title(JText::_('COM_SEMINARMAN_EDIT_GROUP'), 'config');
            } else {
            	JToolBarHelper::title(JText::_('COM_SEMINARMAN_EDIT_CUSTOM_FIELD'), 'config');
            }

        } else
        {
        	if (JRequest::setVar('layout')=='editgroup') {
        		JToolBarHelper::title(JText::_('COM_SEMINARMAN_ADD_GROUP'), 'config');
        	} else {
        		JToolBarHelper::title(JText::_('COM_SEMINARMAN_ADD_CUSTOM_FIELD'), 'config');        		
        	}
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_HOME'), 'index.php?option=com_seminarman');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_APPLICATIONS'),'index.php?option=com_seminarman&view=applications');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_COURSES'),'index.php?option=com_seminarman&view=courses');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_CATEGORIES'),'index.php?option=com_seminarman&view=categories', true);
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_TAGS'),'index.php?option=com_seminarman&view=tags');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_TUTORS'),'index.php?option=com_seminarman&view=tutors');
        }
        JToolBarHelper::apply();
        JToolBarHelper::save();
        JToolBarHelper::cancel();

        $model = $this->getModel();
        $row = $this->get('Tag');

        if ($row->id)
        {
            if ($model->isCheckedOut($user->get('id')))
            {
                JError::raiseWarning('SOME_ERROR_CODE', $row->name . ' ' . JText::_('COM_SEMINARMAN_RECORD_EDITED'));
                $mainframe->redirect('index.php?option=com_seminarman&view=editfields');
            }
        }

    	$fieldGroups	= $model->getGroups();
    	$group			= $model->getFieldGroup( $row->ordering );

    	$cft = $model->getCustomfieldsTypes();
        $this->assignRef('row', $row);
    	$this->assignRef('fieldGroups', $fieldGroups);
    	$this->assignRef('group', $group);
    	$this->assignRef('customFieldTypes', $cft );

        parent::display($tpl);
    }

}

?>