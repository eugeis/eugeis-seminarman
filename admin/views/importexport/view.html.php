<?php
/**
 * @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
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
include_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_seminarman' . DS .
            'helpers' . DS . 'seminarman.php');

class SeminarmanViewImportexport extends JViewLegacy
{

	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();
		JRequest::setVar('hidemainmenu', 1);
		JToolBarHelper::title(JText::_('COM_SEMINARMAN_CSV_EXPORT'), 'generic.png');
		JToolBarHelper::cancel();

		$document = JFactory::getDocument();
		$uri = JFactory::getURI();

		$document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend.css');

		// Export
		$expoptions =  array (
        	'courses' => JText::_('COM_SEMINARMAN_COURSES'),
        	'sessions' => JText::_('COM_SEMINARMAN_SESSIONS'),
        	'applications' => JText::_('COM_SEMINARMAN_APPLICATIONS'),
        	'salesprospects' => JText::_('COM_SEMINARMAN_SALES_PROSPECTS'),
        	'templates' => JText::_('COM_SEMINARMAN_TEMPLATES'),
        	'tutors' => JText::_('COM_SEMINARMAN_TUTORS'),
		);
		
		$expselect = JHTML::_('select.genericlist', $expoptions, 'datatype', 'onchange="javascript:showOptions();" onkeyup="javascript:showOptions();"', 'value', 'text', 'courses');
		$expfromdate = JHTML::calendar('', 'from_date', 'from_date', '%Y-%m-%d');
		$exptodate = JHTML::calendar('', 'to_date', 'to_date', '%Y-%m-%d');
		$expcourse = $this->_getSelectCourse();
		$exptemplate = $this->_getSelectTemplate();
		$path = $uri->toString();
		
		$this->assignRef('expselect', $expselect);
		$this->assignRef('expfromdate', $expfromdate);
		$this->assignRef('exptodate', $exptodate);
		$this->assignRef('expcourse', $expcourse);
		$this->assignRef('exptemplate', $exptemplate);

		$this->assignRef('path', $path);

		parent::display($tpl);
	}

	function _getSelectCourse()
	{
		$db = JFactory::getDBO();
		$query = 'SELECT id AS value, CONCAT(id, ": ", title) AS text FROM #__seminarman_courses ORDER BY title';
		$db->setQuery($query);
		$courses = $db->loadObjectList();

		$types[] = JHTML::_('select.option', '0', '- '. JText::_('COM_SEMINARMAN_ALL') .' -');

		foreach ($courses as $course)
			$types[] = JHTML::_('select.option', $course->value, JText::_($course->text));

		return JHTML::_('select.genericlist', $types, 'course', 'class="inputbox" size="1" ', 'value', 'text', 0);
	}
	
	function _getSelectTemplate()
	{
		$db = JFactory::getDBO();
		$query = 'SELECT id AS value, CONCAT(id, ": ", title) AS text FROM #__seminarman_templates ORDER BY title';
		$db->setQuery($query);
		$templates = $db->loadObjectList();
	
		$types[] = JHTML::_('select.option', '0', '- '. JText::_('COM_SEMINARMAN_ALL') .' -');
	
		foreach ($templates as $template)
			$types[] = JHTML::_('select.option', $template->value, JText::_($template->text));
	
		return JHTML::_('select.genericlist', $types, 'template', 'class="inputbox" size="1" ', 'value', 'text', 0);
	}
}

?>
