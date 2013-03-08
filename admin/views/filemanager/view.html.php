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

class SeminarmanViewFilemanager extends JView
{
    function display($tpl = null)
    {
        $mainframe = JFactory::getApplication();

        $document = &JFactory::getDocument();
        $db = &JFactory::getDBO();
        $params = &JComponentHelper::getParams('com_seminarman');
        $lang = &JFactory::getLanguage();
        $option = JRequest::getVar('option');
        
        $filter_order = $mainframe->getUserStateFromRequest($option .
            '.filemanager.filter_order', 'filter_order', 'f.filename', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($option .
            '.filemanager.filter_order_Dir', 'filter_order_Dir', '', 'word');
        $filter = $mainframe->getUserStateFromRequest($option . '.filemanager.filter',
            'filter', '', 'int');
        $filter_assigned = $mainframe->getUserStateFromRequest($option .
            '.filemanager.filter_assigned', 'filter_assigned', '*', 'word');
        $search = $mainframe->getUserStateFromRequest($option . '.filemanager.search',
            'search', '', 'string');
        $search = $db->getEscaped(trim(JString::strtolower($search)));

        $document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend.css');
        if ($lang->isRTL())
        {
            $document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend_rtl.css');
        }

        JToolBarHelper::title(JText::_('COM_SEMINARMAN_FILEMANAGER'), 'qffilemanager');
        JToolBarHelper::customX('goback', 'back.png', 'back_f2.png', 'COM_SEMINARMAN_GO_BACK', false, true);
        JToolBarHelper::deleteList();

        $rows = &$this->get('Data');
        $pageNav = &$this->get('Pagination');

        $lists = array();
        $lists['search'] = $search;

        $filters = array();
        $filters[] = JHTML::_('select.option', '1', JText::_('COM_SEMINARMAN_FILENAME'));
        $filters[] = JHTML::_('select.option', '2', JText::_('COM_SEMINARMAN_DISPLAY_NAME'));
        $lists['filter'] = JHTML::_('select.genericlist', $filters, 'filter',
            'size="1" class="inputbox"', 'value', 'text', $filter);

        $assigned = array();
        $assigned[] = JHTML::_('select.option', '', '- ' . JText::_('COM_SEMINARMAN_ALL_FILES') . ' -');
        $assigned[] = JHTML::_('select.option', 'O', JText::_('COM_SEMINARMAN_ORPHANED'));
        $assigned[] = JHTML::_('select.option', 'A', JText::_('COM_SEMINARMAN_ASSIGNED'));

        $lists['assigned'] = JHTML::_('select.genericlist', $assigned, 'filter_assigned',
            'class="inputbox" size="1" onchange="submitform( );"', 'value', 'text', $filter_assigned);

        $lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;

        if ($params->get('enable_flash', 1))
        {
            JHTML::_('behavior.uploader', 'file-upload', array('onAllComplete' =>
                'function(){ window.location.reload(); }'));
        }

        jimport('joomla.client.helper');
        $ftp = !JClientHelper::hasCredentials('ftp');

        $this->assign('require_ftp', $ftp);

        $this->assignRef('session', JFactory::getSession());
        $this->assignRef('params', $params);
        $this->assignRef('lists', $lists);
        $this->assignRef('rows', $rows);
        $this->assignRef('pageNav', $pageNav);
        $this->assignRef('direction', $lang);

        parent::display($tpl);

    }
}

?>