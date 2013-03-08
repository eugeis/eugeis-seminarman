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

defined('_JEXEC') or die();


class JFormFieldTag extends JFormField
{
	public  $type = 'Tag';

	protected function getInput()
	{
		JHtml::_('behavior.modal', 'a.modal');
		 
		$js = "
	function qfSelectTag(id, title) {
		document.getElementById('a_id').value = id;
		document.getElementById('a_name').value = title;
		SqueezeBox.close();
    }";
		
		JFactory::getDocument()->addScriptDeclaration($js);

		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_seminarman'.DS.'tables');

		$course = &JTable::getInstance('seminarman_tags', '');
		if ($this->value)
		{
			$course->load($this->value);
		} else
		{
			$course->name = JText::_('COM_SEMINARMAN_SELECT_TAG');
		}

		$link = 'index.php?option=com_seminarman&amp;view=tagelement&amp;tmpl=component';
		$html = "\n<div style=\"float: left;\"><input style=\"background: #ffffff;\" type=\"text\" id=\"a_name\" value=\"$course->name\" disabled=\"disabled\" /></div>";
		$html .= "<div class=\"button2-left\"><div class=\"blank\"><a class=\"modal\" title=\"" .
		JText::_('Select') . "\"  href=\"$link\" rel=\"{handler: 'iframe', size: {x: 650, y: 375}}\">" .
		JText::_('Select') . "</a></div></div>\n";
		$html .= "\n<input type=\"hidden\" id=\"a_id\" name=\"$this->name\" value=\"$this->value\" />";

		return $html;
	}
}

?>
