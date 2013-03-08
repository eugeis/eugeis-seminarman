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

class seminarmanViewPdfTemplate extends JView
{
    function display($tpl = null)
    {
        $document = &JFactory::getDocument();
        $document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend.css');
        
        $db = &JFactory::getDBO();

        $template = &$this->get('data');
    	$fields = &$this->get('fields');

    	JToolBarHelper::title(JText::_('COM_SEMINARMAN_PDF_TEMPLATE'), 'config');
    	
    	JToolBarHelper::custom('pdf_preview', 'preview.png', 'preview.png', 'COM_SEMINARMAN_PDF_PREVIEW', false);
    	JToolBarHelper::divider();
    	JToolBarHelper::apply();
    	JToolBarHelper::save();
    	JToolBarHelper::cancel();
    	
    	$paperformats = array('A4','LETTER','LEGAL','JLEGAL','LEDGER','TABLOID');
    	$orientations = array('P' => JText::_('COM_SEMINARMAN_PDF_PORTRAIT'), 'L' => JText::_('COM_SEMINARMAN_PDF_LANDSCAPE'));

    	$templateforSelect = JHTML::_('select.genericlist', array(JText::_('COM_SEMINARMAN_INVOICES'), JText::_('COM_SEMINARMAN_ATTENDANCE_LIST')), 'templatefor', 'onchange="javascript:toggleParams()"', 'value', 'text', $template->templatefor);
    	$paperformatSelect = JHTML::_('select.genericlist', array_combine($paperformats, $paperformats), 'paperformat', null, 'value', 'text', $template->paperformat);
    	$orientationSelect = JHTML::_('select.genericlist', $orientations, 'orientation', null, 'value', 'text', $template->orientation);
    	
    	$linkfsel = JRoute::_('index.php?option=com_seminarman&amp;view=fileselement&amp;tmpl=component');
    	$this->assignRef('linkfsel', $linkfsel);
		$this->assignRef('template', $template);
		$this->assignRef('fields', $fields);
		$this->assignRef('templateforSelect', $templateforSelect);
		$this->assignRef('paperformatSelect', $paperformatSelect);
		$this->assignRef('orientationSelect', $orientationSelect);
        parent::display($tpl);
    }
}