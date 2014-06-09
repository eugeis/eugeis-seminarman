<?php
/**
 * @version		$Id: default.php 22338 2011-11-04 17:24:53Z github_bot $
 * @package		Joomla.Administrator
 * @subpackage	com_seminarman
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$script = "\t".'Joomla.submitbutton = function(pressbutton) {'."\n";
$script .= "\t\t".'var form = document.adminForm;'."\n";
$script .= "\t\t".'if (pressbutton == \'cancel\') {'."\n";
$script .= "\t\t\t".'Joomla.submitform(pressbutton);'."\n";
$script .= "\t\t\t".'return;'."\n";
$script .= "\t\t".'}'."\n";
$script .= "\t\t".'// do field validation'."\n";
$script .= "\t\t".'if (form.jform_subject.value == ""){'."\n";
$script .= "\t\t\t".'alert("'.JText::_('COM_SEMINARMAN_MAIL_PLEASE_FILL_IN_THE_SUBJECT', true).'");'."\n";
$script .= "\t\t".'} else if (getSelectedValue(\'adminForm\',\'jform[receipt][]\') < 0){'."\n";
$script .= "\t\t\t".'alert("'.JText::_('COM_SEMINARMAN_MAIL_PLEASE_SELECT_A_GROUP', true).'");'."\n";
$script .= "\t\t".'} else if (form.jform_message.value == ""){'."\n";
$script .= "\t\t\t".'alert("'.JText::_('COM_SEMINARMAN_MAIL_PLEASE_FILL_IN_THE_MESSAGE', true).'");'."\n";
$script .= "\t\t".'} else {'."\n";
$script .= "\t\t\t".'Joomla.submitform(pressbutton);'."\n";
$script .= "\t\t".'}'."\n";
$script .= "\t\t".'}'."\n";

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');

JFactory::getDocument()->addScriptDeclaration($script);

$app = JFactory::getApplication();
?>

<form action="<?php echo JRoute::_('index.php?option=com_seminarman&view=mail'); ?>" name="adminForm" method="post" id="adminForm">

	<div class="width-30 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_SEMINARMAN_RECIPIENTS'); ?></legend>
			<ul class="adminformlist">

			<li><?php echo $this->getModel()->form->getLabel('mode'); ?>
			<?php echo $this->getModel()->form->getInput('mode'); ?></li>

			<li><?php echo $this->getModel()->form->getLabel('receipt'); ?>
			<?php echo $this->getModel()->buildReceiptList(); ?></li>

			<li><?php echo $this->getModel()->form->getLabel('bcc'); ?>
			<?php echo $this->getModel()->form->getInput('bcc'); ?></li>
			
			<li><?php echo $this->getModel()->form->getLabel('cc'); ?>
			<?php echo $this->getModel()->form->getInput('cc'); ?></li>
			
			<li><?php if ($app->getUserState('com_seminarman.call.mail.from')=='application') echo $this->getModel()->form->getLabel('attach'); ?>
			<?php if ($app->getUserState('com_seminarman.call.mail.from')=='application') echo $this->getModel()->form->getInput('attach'); ?></li>
			</ul>
		</fieldset>
	</div>

	<div class="width-70 fltrt">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_SEMINARMAN_MSG_BLOCK'); ?></legend>
			<ul class="adminformlist">
			<li><?php echo $this->getModel()->form->getLabel('subject'); ?>
			<?php
                 if (empty($this->getModel()->subject)) {
			         echo $this->getModel()->form->getInput('subject');
                 } else {
                 	 echo $this->getModel()->form->getInput('subject', '', $this->getModel()->subject);
                 }
			?>
			</li>

			<li><?php echo $this->getModel()->form->getLabel('message'); ?>
			<?php echo $this->getModel()->form->getInput('message'); ?></li>
			</ul>
		</fieldset>
   <input type="hidden" name="jform[bill_file]" value="<?php if ($app->getUserState('com_seminarman.call.mail.from')=='application') echo $this->getModel()->attach; ?>" />
   <input type="hidden" name="option" value="com_seminarman" />
   <input type="hidden" name="task" value="" />
   <input type="hidden" name="controller" value="mail" />

		<?php echo JHtml::_('form.token'); ?>
	</div>

	<div class="clr"></div>
</form>



