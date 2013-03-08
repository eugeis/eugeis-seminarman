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

class seminarmanViewatgroups extends JView
{
    function display($tpl = null)
    {
        $mainframe = JFactory::getApplication();

        $db = &JFactory::getDBO();
        $uri = &JFactory::getURI();
        $childviewname = 'atgroups';
        $document = &JFactory::getDocument();
        $lang = &JFactory::getLanguage();
        
        $document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend.css');
        if ($lang->isRTL())
        {
        	$document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend_rtl.css');
        }
		//hide submenu
        JRequest::setVar('hidemainmenu', 1);
        $filter_state = $mainframe->getUserStateFromRequest('com_seminarman' . $childviewname .
            '.filter_state', 'filter_state', '', 'word');
        $filter_order = $mainframe->getUserStateFromRequest('com_seminarman' . $childviewname .
            '.filter_order', 'filter_order', 'a.ordering', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest('com_seminarman' . $childviewname .
            '.filter_order_Dir', 'filter_order_Dir', '', 'word');
        $search = $mainframe->getUserStateFromRequest('com_seminarman' . $childviewname .
            '.search', 'search', '', 'string');
        $search = JString::strtolower($search);

        $courses = &$this->get('Data');
        $total = &$this->get('Total');
        $pagination = &$this->get('Pagination');

        $requestURL = &$uri->toString();

        $lists['state'] = JHTML::_('grid.state', $filter_state);

        $lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;

        $lists['search'] = $search;

        $this->assignRef('user', JFactory::getUser());
        $this->assignRef('lists', $lists);
        $this->assignRef('courses', $courses);
        $this->assignRef('pagination', $pagination);
        $this->assignRef('requestURL', $requestURL);

        parent::display($tpl);
    }
}