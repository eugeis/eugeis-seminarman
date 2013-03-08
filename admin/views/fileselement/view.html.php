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

class SeminarmanViewFileselement extends JView
{
    function display($tpl = null)
    {
        $mainframe = JFactory::getApplication();

        $document = &JFactory::getDocument();
        $db = &JFactory::getDBO();
        $lang = &JFactory::getLanguage();

        $filter_order = $mainframe->getUserStateFromRequest('com_seminarman' .
            '.fileselement.filter_order', 'filter_order', 'f.filename', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest('com_seminarman' .
            '.fileselement.filter_order_Dir', 'filter_order_Dir', '', 'word');
        $filter = $mainframe->getUserStateFromRequest('com_seminarman' . '.fileselement.filter',
            'filter', '', 'int');
        $search = $mainframe->getUserStateFromRequest('com_seminarman' . '.fileselement.search',
            'search', '', 'string');
        $search = $db->getEscaped(trim(JString::strtolower($search)));

        $document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend.css');

        $document->addScript(JURI::base() .
            'components/com_seminarman/assets/js/fileselement.js');
        if ($lang->isRTL())
        {
            $document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend_rtl.css');
        }

        $rows = &$this->get('Data');
        $pageNav = &$this->get('Pagination');

        $filters = array();
        $filters[] = JHTML::_('select.option', '1', JText::_('COM_SEMINARMAN_FILENAME'));
        $filters[] = JHTML::_('select.option', '2', JText::_('COM_SEMINARMAN_DISPLAY_NAME'));
        $lists['filter'] = JHTML::_('select.genericlist', $filters, 'filter',
            'size="1" class="inputbox"', 'value', 'text', $filter);

        $lists['search'] = $search;

        $lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;

        $filelist = JRequest::getString('files');
        $file = JRequest::getInt('file');

        $filelist = explode(',', $filelist);
        $files = array();
        foreach ($filelist as $fileid)
        {

            if ($fileid && $fileid != $file)
            {
                $files[] = (int)$fileid;
            }

        }

        $files = implode(',', $files);
        if (strlen($files) > 0)
        {

            $files .= ',';

        }
        $files .= $file;

        $this->assignRef('lists', $lists);
        $this->assignRef('rows', $rows);
        $this->assignRef('pageNav', $pageNav);
        $this->assignRef('files', $files);
        $this->assignRef('direction', $lang);

        parent::display($tpl);

    }
}

?>