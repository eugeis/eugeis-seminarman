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
Joomla.submitbutton = function(pressbutton) {
	var form = document.adminForm;
	if (pressbutton == 'cancel') {
		Joomla.submitform( pressbutton );
		return;
	}

	// do field validation
	if (form.altname.value == ""){
		alert( "<?php echo JText::_('COM_SEMINARMAN_DISPLAY_NAME_EMPTY'); ?>" );
	} else {
		submitform( pressbutton );
	}
}
</script>


<form action="index.php" method="post" name="adminForm" id="adminForm">

	<table class="no_space_table">
		<tr>
			<td><label for="filename"><?php echo JText::_('COM_SEMINARMAN_FILENAME') . ':'; ?></label></td>
			<td><?php echo htmlspecialchars($this->row->filename, ENT_QUOTES, 'UTF-8'); ?></td>
		</tr>
		<tr>
			<td><label for="altname"><?php echo JText::_('COM_SEMINARMAN_DISPLAY_NAME') . ':'; ?></label></td>
			<td><input name="altname" value="<?php echo $this->row->altname; ?>" size="50" maxlength="100" /></td>
		</tr>
	</table>

	<?php echo JHTML::_('form.token'); ?>
	
	<input type="hidden" name="option" value="com_seminarman" />
	<input type="hidden" name="hits" value="<?php echo $this->row->hits; ?>" />
	<input type="hidden" name="filename" value="<?php echo $this->row->filename; ?>" />
	<input type="hidden" name="uploaded" value="<?php echo $this->row->uploaded; ?>" />
	<input type="hidden" name="uploaded_by" value="<?php echo $this->row->uploaded_by; ?>" />
	<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
	<input type="hidden" name="controller" value="filemanager" />
	<input type="hidden" name="view" value="file" />
	<input type="hidden" name="task" value="" />
	
</form>

<?php JHTML::_('behavior.keepalive'); ?>