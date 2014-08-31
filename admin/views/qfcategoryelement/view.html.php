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

class SeminarmanViewQfcategoryelement extends JViewLegacy
{

    function display($tpl = null)
    {
        $mainframe = JFactory::getApplication();

        $db       = JFactory::getDBO();
        $document = JFactory::getDocument();
        $lang     = JFactory::getLanguage();
        $template = $mainframe->getTemplate();

        JHTML::_('behavior.tooltip');
        JHTML::_('behavior.modal');

        $filter_order     = $mainframe->getUserStateFromRequest('com_seminarman.categories.filter_order', 'filter_order', 'c.title', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest('com_seminarman.categories.filter_order_Dir', 'filter_order_Dir', '', 'word');
        $search           = $mainframe->getUserStateFromRequest('com_seminarman.categories.search', 'search', '', 'string');
        $search           = $db->escape(trim(JString::strtolower($search)));

        $rows = $this->get('Data');
        $pageNav = $this->get('Pagination');

        $lists['search']    = $search;
        $lists['order_Dir'] = $filter_order_Dir;
        $lists['order']     = $filter_order;

        $ordering = ($lists['order'] == 'c.ordering');

        $this->assignRef('lists', $lists);
        $this->assignRef('rows', $rows);
        $this->assignRef('pageNav', $pageNav);
        $this->assignRef('ordering', $ordering);
        $this->assignRef('direction', $lang);

        parent::display($tpl);
    }

}

?>