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

class seminarmanViewsalesprospects extends JViewLegacy
{
    function display($tpl = null)
    {
        $mainframe = JFactory::getApplication();

        $db = JFactory::getDBO();
        $uri = JFactory::getURI();
        $childviewname = 'salesprospect';
        $document = JFactory::getDocument();
        $lang = JFactory::getLanguage();

        $document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend.css');
        if ($lang->isRTL())
        {
            $document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend_rtl.css');
        }

        require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_seminarman' . DS .
            'helpers' . DS . 'seminarman.php');

        if(JHTMLSeminarman::UserIsCourseManager()){        
        JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_HOME'), 'index.php?option=com_seminarman');
        JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_APPLICATIONS'), 'index.php?option=com_seminarman&view=applications');
        JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_LST_OF_SALES_PROSPECTS'), 'index.php?option=com_seminarman&view=salesprospects', true);
        JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_COURSES'), 'index.php?option=com_seminarman&view=courses');
       	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_TEMPLATES'),'index.php?option=com_seminarman&view=templates');
        JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_CATEGORIES'), 'index.php?option=com_seminarman&view=categories');
        JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_TAGS'), 'index.php?option=com_seminarman&view=tags');
        JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_TUTORS'), 'index.php?option=com_seminarman&view=tutors');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_PERIODS'),'index.php?option=com_seminarman&view=periods');
        JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_SETTINGS'),'index.php?option=com_seminarman&view=settings');
        }else{
        JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_HOME'), 'index.php?option=com_seminarman');
        JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_APPLICATIONS'), 'index.php?option=com_seminarman&view=applications');
        JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_LST_OF_SALES_PROSPECTS'), 'index.php?option=com_seminarman&view=salesprospects', true);
        JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_COURSES'), 'index.php?option=com_seminarman&view=courses');        	
        JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_TAGS'), 'index.php?option=com_seminarman&view=tags');
        }
        JToolBarHelper::title(JText::_('COM_SEMINARMAN_LST_OF_SALES_PROSPECTS'), 'salesprospects');
        $bar = JToolBar::getInstance('toolbar');
        $bar->appendButton('Standard', 'envelope', JText::_('COM_SEMINARMAN_NOTIFY'), 'notify', true);
        JToolBarHelper::divider();
        JToolBarHelper::editList();
        JToolBarHelper::divider();
        JToolBarHelper::deleteList();

        $filter_order = $mainframe->getUserStateFromRequest('com_seminarman'. $childviewname . '.filter_order', 'filter_order', 'a.id', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest('com_seminarman' . $childviewname . '.filter_order_Dir', 'filter_order_Dir', 'desc', 'word');
    	$filter_templateid = $mainframe->getUserStateFromRequest('com_seminarman'. $childviewname . '.filter_templateid', 'filter_templateid', 0, 'int' );
    	$filter_courseid = $mainframe->getUserStateFromRequest('com_seminarman'. $childviewname . '.filter_courseid', 'filter_courseid', 0, 'int' );
    	$filter_search = $mainframe->getUserStateFromRequest('com_seminarman'. $childviewname . '.filter_search', 'filter_search', '', 'int' );

        $search = $mainframe->getUserStateFromRequest('com_seminarman'. $childviewname .'.search', 'search', '', 'string');
        $search = JString::strtolower($search);

        $model = $this->getModel();
        $salesprospects = $this->get('Data');
        foreach ($salesprospects as $row)
        {
        	$select = array();
        	$select[] = JHTML::_('select.option', 0, '- ' . JText::_('COM_SEMINARMAN_CHOOSE_PLEASE') . ' -');
        	$courses = $model->getCourses();
        	foreach ($courses as $course)
        	{
        		$select[] = JHTML::_('select.option', $course->id, $course->text );
        	}
        	
        	$db->setQuery('SELECT id FROM #__seminarman_courses WHERE templateId = '. (int)$row->template_id .' LIMIT 1');
        	$result = $db->loadObject();
        	
        	if ($row->notified_course != 0)
        		$selected = $row->notified_course;
        	elseif(!empty($result))
        		$selected = (int)$result->id;
        	else
        		$selected = 0;
        	
        	$row->select_course_notify = JHTML::_('select.genericlist', $select, 'select_course_notify'.$row->id, 'size="1" class="inputbox"', 'value', 'text', $selected);
        }
        
        $total = $this->get('Total');
        $pagination = $this->get('Pagination');

        $requestURL = $uri->toString();

        // build list of courses
        $titles = $this->get('Courses');
        $catlist[] = JHTML::_('select.option',  '0', '- '. JText::_( 'COM_SEMINARMAN_SELECT_COURSE' ). ' -', 'id');
        $catlist = array_merge( $catlist, $titles );
        $lists['courseid'] = JHTML::_('select.genericlist', $catlist, 'filter_courseid', 'class="inputbox" size="1" onchange="submitform( );"','id', 'text', $filter_courseid );

        $catlist = array();
    	// build list of templates
        $titles = $this->get( 'titles' );
    	$catlist[] = JHTML::_('select.option',  '0', '- '. JText::_( 'COM_SEMINARMAN_SELECT_TEMPLATE' ). ' -', 'id', 'title' );
    	$catlist = array_merge( $catlist, $titles );
    	$lists['templateid'] = JHTML::_('select.genericlist', $catlist, 'filter_templateid', 'class="inputbox" size="1" onchange="submitform( );"','id', 'title', $filter_templateid );
    	
    	//search filter - what field to use for search
    	$filters = array();
    	$filters[] = JHTML::_('select.option', '1', JText::_( 'COM_SEMINARMAN_LAST_NAME' ) );
    	$filters[] = JHTML::_('select.option', '2', JText::_( 'COM_SEMINARMAN_FIRST_NAME' ) );
    	$filters[] = JHTML::_('select.option', '3', JText::_( 'COM_SEMINARMAN_EMAIL' ) );
    	$lists['filter_search'] = JHTML::_('select.genericlist', $filters, 'filter_search', 'size="1" class="inputbox"', 'value', 'text', $filter_search );

        $lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;

        $lists['search'] = $search;
        
        $user = JFactory::getUser();
        $nulldate = $db->getNullDate();
        $this->assignRef('user', $user);
        $this->assignRef('lists', $lists);
        $this->assignRef('salesprospects', $salesprospects);
        $this->assignRef('pagination', $pagination);
        $this->assignRef('requestURL', $requestURL);
        $this->assignRef('nullDate', $nulldate);

        parent::display($tpl);
    }
}
