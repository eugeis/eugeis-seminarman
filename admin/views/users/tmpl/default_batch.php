<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Create the copy/move options.
$options = array(
	JHtml::_('select.option', 'add', JText::_('COM_USERS_BATCH_ADD')),
	JHtml::_('select.option', 'del', JText::_('COM_USERS_BATCH_DELETE')),
	JHtml::_('select.option', 'set', JText::_('COM_USERS_BATCH_SET'))
);

// Create the copy/move options.
$course_options = array(
		JHtml::_('select.option', 'bookCourse', JText::_('COM_SEMINARMAN_BOOK_COURSE')),
		JHtml::_('select.option', 'unbookCourse', JText::_('COM_SEMINARMAN_CANCEL_COURSE')),
);

?>
<fieldset class="batch">
	<legend><?php echo JText::_('COM_USERS_BATCH_OPTIONS');?></legend>

	<table style="margin-right:10px">
		<tr>
			<td><?php echo JText::_('COM_SEMINARMAN_SELECT_COURSE');?></td>
			<td>
				<select name="batch[course_id]" class="inputbox" id="batch-course-id">
					<option value=""><?php echo JText::_('JSELECT') ?></option>
					<?php echo JHtml::_('select.options', $this->lists['courses']); ?>
				</select>
			</td>
			<td>
				<?php echo JHtml::_('select.radiolist', $course_options, 'batch[course_action]', '', 'value', 'text', 'bookCourse') ?>
			</td>
			<td>
				<button type="submit" onclick="Joomla.submitbutton('course.batch');"><?php echo JText::_('JGLOBAL_BATCH_PROCESS'); ?></button>
			</td>
		</tr>
		<tr>
			<td><?php echo JText::_('COM_USERS_BATCH_GROUP');?></td>
			<td>
				<select name="batch[group_id]" class="inputbox" id="batch-group-id">
					<option value=""><?php echo JText::_('JSELECT') ?></option>
					<?php echo JHtml::_('select.options', JHtml::_('user.groups', JFactory::getUser()->get('isRoot'))); ?>
				</select>
			</td>
			<td>
				<?php echo JHtml::_('select.radiolist', $options, 'batch[group_action]', '', 'value', 'text', 'add') ?>
			</td>
			<td>
				<button type="submit" onclick="Joomla.submitbutton('user.batch');"><?php echo JText::_('JGLOBAL_BATCH_PROCESS'); ?></button>
			</td>
		</tr>		
	</table>
</fieldset>
