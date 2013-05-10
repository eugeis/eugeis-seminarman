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

class SeminarmanViewTemplates extends JView
{

    function display($tpl = null)
    {
    	require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_seminarman' . DS .
            'helpers' . DS . 'seminarman.php');
        
        if(JHTMLSeminarman::UserIsCourseManager()){     	
    	
        $mainframe = JFactory::getApplication();

        $user = JFactory::getUser();
        $db = JFactory::getDBO();
        $document = JFactory::getDocument();
        $lang = JFactory::getLanguage();
    	$params = JComponentHelper::getParams( 'com_seminarman' );


        JHTML::_('behavior.tooltip');

        $filter_order = $mainframe->getUserStateFromRequest('com_seminarman.templates.filter_order', 'filter_order', '', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest('com_seminarman.templates.filter_order_Dir', 'filter_order_Dir', '', 'word');
        $filter_state = $mainframe->getUserStateFromRequest('com_seminarman.templates.filter_state', 'filter_state', '*', 'word');
        $filter_category = $mainframe->getUserStateFromRequest('com_seminarman.templates.filter_category', 'filter_category', '*');
        $search = $mainframe->getUserStateFromRequest('com_seminarman.templates.search', 'search', '', 'string');
        $search = $db->getEscaped(trim(JString::strtolower($search)));

        $document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend.css');
        if ($lang->isRTL())
        {
            $document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend_rtl.css');
        }

    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_HOME'), 'index.php?option=com_seminarman');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_APPLICATIONS'),'index.php?option=com_seminarman&view=applications');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_LST_OF_SALES_PROSPECTS'), 'index.php?option=com_seminarman&view=salesprospects');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_COURSES'),'index.php?option=com_seminarman&view=courses');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_TEMPLATES'),'index.php?option=com_seminarman&view=templates', true);
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_CATEGORIES'),'index.php?option=com_seminarman&view=categories');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_TAGS'),'index.php?option=com_seminarman&view=tags');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_TUTORS'),'index.php?option=com_seminarman&view=tutors');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_SETTINGS'),'index.php?option=com_seminarman&view=settings');

        JToolBarHelper::title(JText::_('COM_SEMINARMAN_TEMPLATES'), 'templates');
        JToolBarHelper::addNew();
        JToolBarHelper::editList();
        JToolBarHelper::divider();
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();
        JToolBarHelper::divider();
        JToolBarHelper::deleteList();
        $rows = $this->get('Data');
        $pageNav = $this->get('Pagination');


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
        
        }else{
        	
$app = JFactory::getApplication();
$app->redirect('index.php?option=com_seminarman', 'Only seminar manager group can access templates.');	
        	
        }
        
    }
}

?>
