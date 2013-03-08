<?php
/**
 * @version		$Id: default.php 22338 2011-11-04 17:24:53Z github_bot $
 * @package		Joomla.Administrator
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$script = "\t".'Joomla.submitbutton = function(pressbutton) {'."\n";
$script .= "\t\t".'var form = document.adminForm;'."\n";
$script .= "\t\t".'if (pressbutton == \'mail.cancel\') {'."\n";
$script .= "\t\t\t".'Joomla.submitform(pressbutton);'."\n";
$script .= "\t\t\t".'return;'."\n";
$script .= "\t\t".'}'."\n";
$script .= "\t\t".'// do field validation'."\n";
$script .= "\t\t".'if (form.jform_subject.value == ""){'."\n";
$script .= "\t\t\t".'alert("'.JText::_('COM_USERS_MAIL_PLEASE_FILL_IN_THE_SUBJECT', true).'");'."\n";
$script .= "\t\t".'} else if (getSelectedValue(\'adminForm\',\'jform[group]\') < 0){'."\n";
$script .= "\t\t\t".'alert("'.JText::_('COM_USERS_MAIL_PLEASE_SELECT_A_GROUP', true).'");'."\n";
$script .= "\t\t".'} else if (form.jform_message.value == ""){'."\n";
$script .= "\t\t\t".'alert("'.JText::_('COM_USERS_MAIL_PLEASE_FILL_IN_THE_MESSAGE', true).'");'."\n";
$script .= "\t\t".'} else {'."\n";
$script .= "\t\t\t".'Joomla.submitform(pressbutton);'."\n";
$script .= "\t\t".'}'."\n";
$script .= "\t\t".'}'."\n";

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');

JFactory::getDocument()->addScriptDeclaration($script);
?>

<form action="#" name="adminForm" method="post" id="adminForm">

	<div class="width-30 fltlft">
		<fieldset class="adminform">
			<legend>Liste von Empfängern</legend>
			<ul class="adminformlist">
			<li>Teilnehmer 1</li>
			<li>Teilnehmer 2</li>
			</ul>
		</fieldset>
	</div>

	<div class="width-70 fltrt">
		<fieldset class="adminform">
			<legend><?php echo 'Nachricht'; ?></legend>
			<ul class="adminformlist">
			<li><?php echo 'Betreff'; ?>
			<input class="inputbox" type="text" name="title" id="title" size="32" maxlength="254" value="" /></li>

			<li><?php echo 'Nachricht'; ?>
			<textarea class="inputbox" id="text" style="width: 100%; height: 250px;" rows="15" cols="50" name="text"></textarea></li>
			</ul>
		</fieldset>
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</div>

	<div class="clr"></div>
</form>
