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

class SeminarmanViewCourses extends JViewLegacy
{

    function display($tpl = null)
    {
        $mainframe = JFactory::getApplication();

        $user = JFactory::getUser();
        $db = JFactory::getDBO();
        $document = JFactory::getDocument();
        $lang = JFactory::getLanguage();
    	$params = JComponentHelper::getParams( 'com_seminarman' );


        JHTML::_('behavior.tooltip');

        $filter_order = $mainframe->getUserStateFromRequest('com_seminarman.courses.filter_order', 'filter_order', 'i.ordering', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest('com_seminarman.courses.filter_order_Dir', 'filter_order_Dir', '', 'word');
        $filter_state = $mainframe->getUserStateFromRequest('com_seminarman.courses.filter_state', 'filter_state', '*', 'word');
		$filter_category = $mainframe->getUserStateFromRequest('com_seminarman.courses.filter_category', 'filter_category', '*');
		$filter_search = $mainframe->getUserStateFromRequest('com_seminarman'.'.applications.filter_search', 'filter_search', '', 'int' );
    	$search = $mainframe->getUserStateFromRequest('com_seminarman.courses.search', 'search', '', 'string');
        $search = $db->escape(trim(JString::strtolower($search)));

        $document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend.css');
        if ($lang->isRTL())
        {
            $document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend_rtl.css');
        }
        
        require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_seminarman' . DS .
        		'helpers' . DS . 'seminarman.php');
    	
        if(JHTMLSeminarman::UserIsCourseManager()){
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_HOME'), 'index.php?option=com_seminarman');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_APPLICATIONS'),'index.php?option=com_seminarman&view=applications');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_LST_OF_SALES_PROSPECTS'), 'index.php?option=com_seminarman&view=salesprospects');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_COURSES'),'index.php?option=com_seminarman&view=courses', true);
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_TEMPLATES'),'index.php?option=com_seminarman&view=templates');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_CATEGORIES'),'index.php?option=com_seminarman&view=categories');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_TAGS'),'index.php?option=com_seminarman&view=tags');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_TUTORS'),'index.php?option=com_seminarman&view=tutors');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_SETTINGS'),'index.php?option=com_seminarman&view=settings');
        } else {
  	    JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_HOME'), 'index.php?option=com_seminarman');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_APPLICATIONS'),'index.php?option=com_seminarman&view=applications');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_LST_OF_SALES_PROSPECTS'), 'index.php?option=com_seminarman&view=salesprospects');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_COURSES'),'index.php?option=com_seminarman&view=courses', true);        	
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_TAGS'),'index.php?option=com_seminarman&view=tags');
        }

        JToolBarHelper::title(JText::_('COM_SEMINARMAN_COURSES'), 'courses');
        JToolBarHelper::addNew();
        JToolBarHelper::editList();
        JToolBarHelper::divider();
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();
        JToolBarHelper::divider();
        JToolBarHelper::deleteList();
        JToolBarHelper::divider();
        JToolBarHelper::custom('attendancelist','file-2','file-2', JText::_('COM_SEMINARMAN_ATTENDANCE_LIST').' (PDF)');
        
        if (SeminarmanFunctions::isSmanpdflistPlgEnabled() && $params->get('alt_pdflist')) {
        	$plugin_pdflist = JPluginHelper::getPlugin('seminarman', 'smanpdflist');
        	$pdflist_params = new JRegistry($plugin_pdflist->params);
        	if (($pdflist_params->get('template_1_id') > 0)) {
        	    JToolBarHelper::custom('attendancelist_alt', 'file-2','file-2', $pdflist_params->get('template_1_title'), true);
        	}
        }
        
        if (SeminarmanFunctions::isSmanpdflistPlgEnabled() && $params->get('alt_pdflist')) {
        	$plugin_pdflist = JPluginHelper::getPlugin('seminarman', 'smanpdflist');
        	$pdflist_params = new JRegistry($plugin_pdflist->params);
        	if (($pdflist_params->get('template_2_id') > 0)) {
        		JToolBarHelper::custom('attendancelist_alt_three', 'file-2','file-2', $pdflist_params->get('template_2_title'), true);
        	}
        }
        
        JToolBarHelper::custom('certificatelist','file-2','file-2', JText::_('COM_SEMINARMAN_CERTIFICATES').' (PDF)', true);
        
        $rows = $this->get('Data');
        $pageNav = $this->get('Pagination');
        
        //search filter - what field to use for search
        $filters = array();
        $filters[] = JHTML::_('select.option', '1', JText::_( 'COM_SEMINARMAN_COURSE_TITLE' ) );
        $filters[] = JHTML::_('select.option', '2', JText::_( 'COM_SEMINARMAN_COURSE_CODE' ) );
        $lists['filter_search'] = JHTML::_('select.genericlist', $filters, 'filter_search', 'size="1" class="inputbox"', 'value', 'text', $filter_search );

    	foreach ($rows as $row):
    	$db = JFactory::getDBO();

    	$sql = 'SELECT * FROM #__seminarman_sessions'
    			. ' WHERE published = 1'
    			. ' AND courseid = '.$row->id
    			. ' ORDER BY ordering';
    		$db->setQuery($sql);
    		$course_sessions = $db->loadObjectList();
		$row->count_sessions = count($course_sessions);

    	//set start and finish dates
    	if ($row->start_date != '0000-00-00')
    		$row->start_date = JHTML::_('date', $row->start_date, JText::_('COM_SEMINARMAN_DATE_FORMAT1'));
        else
        	$row->start_date = JText::_('COM_SEMINARMAN_NOT_SPECIFIED');

        if ($row->finish_date != '0000-00-00')
        	$row->finish_date = JHTML::_('date', $row->finish_date, JText::_('COM_SEMINARMAN_DATE_FORMAT1'));
        else
        	$row->finish_date = JText::_('COM_SEMINARMAN_NOT_SPECIFIED');

    		//capacity check
    		switch ($params->get('current_capacity'))
    		{
    			// cases for a parameter
    			case 1:
    				$current_capacity_setting=-1;
    				break;

    			case 2:
    				$current_capacity_setting=0;
    				break;

    			default:
    				$current_capacity_setting=-1;
    				break;
    		}
    		//add currentbookings information
    		$db = JFactory::getDBO();
    		$sql = 'SELECT SUM(b.attendees)'
    			. ' FROM #__seminarman_application AS b'
    			. ' WHERE b.published = 1'
    			. ' AND b.course_id = '.$row->id
    			. ' AND b.status > '.$current_capacity_setting
    			. ' AND b.status < 3';
    		$db->setQuery($sql);
			$row->currentBookings = $db->loadResult();
			if (empty($row->currentBookings)) $row->currentBookings = 0;
    		$row->currentAvailability = ($row->capacity)-($row->currentBookings);
		endforeach;

        $lists['state'] = JHTML::_('grid.state', $filter_state, JText::_('JPUBLISHED'), JText::_('JUNPUBLISHED'));
        $lists['search'] = $search;
        $lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;
        $ordering = ($lists['order'] == 'i.ordering');
        $categories = seminarman_cats::getCategoriesTree(0);
        $lists['category'] = seminarman_cats::buildcatselect($categories, 'filter_category', $filter_category, true, 'class="inputbox" onchange="submitform( );"');

        $this->assignRef('lists', $lists);
        $this->assignRef('rows', $rows);
        $this->assignRef('pageNav', $pageNav);
        $this->assignRef('ordering', $ordering);
        $this->assignRef('user', $user);
        $this->assignRef('direction', $lang);

        parent::display($tpl);
    }
}

?>
