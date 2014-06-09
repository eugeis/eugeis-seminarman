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

class seminarmanViewtutors extends JViewLegacy
{
    function display($tpl = null)
    {
    	require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_seminarman' . DS .
            'helpers' . DS . 'seminarman.php');
        
        if(JHTMLSeminarman::UserIsCourseManager()){ 
        	
        $mainframe = JFactory::getApplication();

        $db = JFactory::getDBO();
        $uri = JFactory::getURI();
        $document = JFactory::getDocument();
        $lang = JFactory::getLanguage();

        $document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend.css');
        if ($lang->isRTL())
            $document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend_rtl.css');

    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_HOME'), 'index.php?option=com_seminarman');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_APPLICATIONS'),'index.php?option=com_seminarman&view=applications');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_USERS'), 'index.php?option=com_seminarman&view=users');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_COURSES'),'index.php?option=com_seminarman&view=courses');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_TEMPLATES'),'index.php?option=com_seminarman&view=templates');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_CATEGORIES'),'index.php?option=com_seminarman&view=categories');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_TAGS'),'index.php?option=com_seminarman&view=tags');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_TUTORS'),'index.php?option=com_seminarman&view=tutors', true);
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_PERIODS'),'index.php?option=com_seminarman&view=periods');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_SETTINGS'),'index.php?option=com_seminarman&view=settings');
    	 

    	JToolBarHelper::title(JText::_('COM_SEMINARMAN_TUTORS'), 'tutors');
        JToolBarHelper::addNew();
        JToolBarHelper::editList();
        JToolBarHelper::divider();
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();
        JToolBarHelper::divider();
        JToolBarHelper::deleteList();
        
        $filter_state = $mainframe->getUserStateFromRequest('com_seminarman.tutors.filter_state', 'filter_state', '', 'word');
        $filter_order = $mainframe->getUserStateFromRequest('com_seminarman.tutors.filter_order', 'filter_order', 'a.ordering', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest('com_seminarman.tutors.filter_order_Dir', 'filter_order_Dir', '', 'word');
        $search = $mainframe->getUserStateFromRequest('com_seminarman.tutors.search', 'search', '', 'string');
        $search = JString::strtolower($search);

        $courses = $this->get('Data');
        $total = $this->get('Total');
        $pagination = $this->get('Pagination');

        $requestURL = $uri->toString();
        
        $lists['state'] = JHTML::_('grid.state', $filter_state, JText::_('JPUBLISHED'), JText::_('JUNPUBLISHED'));

        $lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;

        $lists['search'] = $search;

        $user = JFactory::getUser();
        $this->assignRef('user', $user);
        $this->assignRef('lists', $lists);
        $this->assignRef('courses', $courses);
        $this->assignRef('pagination', $pagination);
        $this->assignRef('requestURL', $requestURL);

        parent::display($tpl);
        
        }else{
        	
$app = JFactory::getApplication();
$app->redirect('index.php?option=com_seminarman', 'Only seminar manager group can access trainers.');	
        	
        }
    }
}
