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

class SeminarmanViewEditcss extends JView
{

    function display($tpl = null)
    {

        $mainframe = JFactory::getApplication();

        $document = &JFactory::getDocument();
        $user = &JFactory::getUser();
        $lang = &JFactory::getLanguage();

        $option = JRequest::getVar('option');
        $filename = 'seminarman.css';
        $path = JPATH_SITE . DS . 'components' . DS . 'com_seminarman' . DS . 'assets' . DS .
            'css';
        $css_path = $path . DS . $filename;

        JToolBarHelper::title(JText::_('COM_SEMINARMAN_EDIT_CSS'), 'qfeditcss');
        JToolBarHelper::apply('applycss');
        JToolBarHelper::save('savecss');
        JToolBarHelper::cancel();

        JRequest::setVar('hidemainmenu', 1);

        $document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend.css');
        if ($lang->isRTL())
        {
            $document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend_rtl.css');
        }

        jimport('joomla.filesystem.file');
        $content = JFile::read($css_path);

        jimport('joomla.client.helper');
        $ftp = &JClientHelper::setCredentialsFromRequest('ftp');

        if ($content !== false)
        {
            $content = htmlspecialchars($content, ENT_COMPAT, 'UTF-8');
        } else
        {
            $msg = JText::sprintf('COM_SEMINARMAN_FAILED_TO_OPEN_FILE_FOR_WRITING', $css_path);
            $mainframe->redirect('index.php?option=' . $option, $msg);
        }

        $this->assignRef('css_path', $css_path);
        $this->assignRef('content', $content);
        $this->assignRef('filename', $filename);
        $this->assignRef('ftp', $ftp);


        parent::display($tpl);
    }
}