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

class seminarmanViewEmailtemplate extends JView
{
	function display($tpl = null)
	{
		$lists = array();
		$emailtemplate = $this->get('data');
		$fields = $this->get('fields');
		$templateforSelect = JHTML::_('select.genericlist', array(JText::_('COM_SEMINARMAN_TEMPLATE_FOR_0'), JText::_('COM_SEMINARMAN_TEMPLATE_FOR_1'), JText::_('COM_SEMINARMAN_TEMPLATE_FOR_2'), JText::_('COM_SEMINARMAN_TEMPLATE_FOR_3'), JText::_('COM_SEMINARMAN_TEMPLATE_FOR_4')), 'templatefor', null, 'value', 'text', $emailtemplate->templatefor);
		
		$this->assignRef('lists', $lists);
		$this->assignRef('emailtemplate', $emailtemplate);
		$this->assignRef('fields', $fields);
		$this->assignRef('templateforSelect', $templateforSelect);
		parent::display($tpl);
    }
}