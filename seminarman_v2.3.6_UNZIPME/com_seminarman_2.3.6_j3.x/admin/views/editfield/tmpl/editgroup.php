<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');

?>

<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function(task){
		var form = document.adminForm;
		if (task == 'cancel') {
			Joomla.submitform( task );
			return;
		}

		// do field validation
		if (form.name.value == ""){
			alert( "<?php echo JText::_('COM_SEMINARMAN_MISSING_NAME', true); ?>" );
		} else {
			Joomla.submitform( task );
		}
	};
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="width-60 fltlft">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_SEMINARMAN_FIELD'); ?></legend>
		<ul class="adminformlist">
			<li>
				<label><?php echo JText::_('JPUBLISHED');?></label>
				<fieldset class="radio"><?php echo JHTML::_('select.booleanlist', 'published', '', $this->row->published); ?></fieldset>
			</li>
			<li>
				<label><?php echo JText::_('COM_SEMINARMAN_VISIBLE');?></label>
				<fieldset class="radio"><?php echo JHTML::_('select.booleanlist', 'visible', '', $this->row->visible); ?></fieldset>
			</li>
			<li>
				<label for="name"><?php echo JText::_('COM_SEMINARMAN_NAME'); ?></label>
				<input name="name" type="text" size="50" value="<?php echo $this->row->name; ?>" />
			</li>
			<li>
				<label for="purpose"><?php echo JText::_('COM_SEMINARMAN_USE_FOR'); ?></label>
				<select name="purpose">
					<option value="0"<?php if ($this->row->purpose == 0) echo ' selected="selected"'?>><?php echo JText::_('COM_SEMINARMAN_BOOKINGS'); ?></option>
					<option value="1"<?php if ($this->row->purpose == 1) echo ' selected="selected"'?>><?php echo JText::_('COM_SEMINARMAN_LST_OF_SALES_PROSPECTS'); ?></option>
					<option value="2"<?php if ($this->row->purpose == 2) echo ' selected="selected"'?>><?php echo JText::_('COM_SEMINARMAN_TUTOR_PROFILE'); ?></option>
				</select>
			</li>
		</ul>
	</fieldset>
</div>



<?php

echo JHTML::_('form.token');

?>
<input type="hidden" name="option" value="com_seminarman" />
<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
<input type="hidden" name="controller" value="editfield" />
<input type="hidden" name="layout" value="editgroup" />
<input type="hidden" name="view" value="editfields" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="type" value="group" />
</form>

<?php


JHTML::_('behavior.keepalive');

?>