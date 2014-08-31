<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');

?>
<?php $infoimage = JHTML::image('components/com_seminarman/assets/images/icon-16-hint.png',JText::_('NOTES'));?>
<script language="javascript" type="text/javascript">
Joomla.submitbutton = function(task){
	var form = document.adminForm;
	if (task == 'cancel') {
		Joomla.submitform( task );
		return;
	}

	if (form.name.value == ""){
		alert( "<?php echo JText::_('COM_SEMINARMAN_MISSING_NAME', true); ?>" );
	} else {
		Joomla.submitform( task );
	}
};

function showOptions() {
	switch(document.adminForm.type.value) {
	case 'text':
	case 'textarea':
	case '':
		document.getElementById('li_min').style.display='block';
		document.getElementById('li_max').style.display='block';
		document.getElementById('li_options').style.display='none';
		break;
	case 'select':
	case 'singleselect':
	case 'radio':
	case 'list':
	case 'checkbox':
		document.getElementById('li_min').style.display='none';
		document.getElementById('li_max').style.display='none';
		document.getElementById('li_options').style.display='block';
		document.getElementById('li_options').getElementsByTagName('label')[0].innerHTML = '<?php echo JText::_('COM_SEMINARMAN_OPTIONS_HELP'); ?>';
		break;
	case 'checkboxtos':
		document.getElementById('li_min').style.display='none';
		document.getElementById('li_max').style.display='none';
		document.getElementById('li_options').style.display='block';
		document.getElementById('li_options').getElementsByTagName('label')[0].innerHTML = '<?php echo JText::_('COM_SEMINARMAN_OPTIONS_HELP_TOS'); ?>';
		break;
	default:
		document.getElementById('li_min').style.display='none';
		document.getElementById('li_max').style.display='none';
		document.getElementById('li_options').style.display='none';
	}
}

</script>

<?php

// build html code for group list
if (empty($this->fieldGroups))
	$htmlGroupOptions = '<option value="">- '. JText::_('COM_SEMINARMAN_NO_GROUPS_DEFINED') .' -</option>';
else
{
	$htmlGroupOptions = '';
	for( $i = 0; $i < count( $this->fieldGroups ); $i++ )
	{
		if (isset($this->group->id) && $this->group->id == $this->fieldGroups[$i]->id )
			$selected = ' selected="selected"';
		else
			$selected = '';
		$htmlGroupOptions .= '<option value="'. $this->fieldGroups[$i]->ordering .'"'. $selected .'>'. $this->fieldGroups[$i]->name .'</option>';
	}
}


// build html code for field types
if (empty($this->customFieldTypes))
	$htmlFieldTypeOptions = '<option value="">-</option>';
else
{
	$htmlFieldTypeOptions = '';
	foreach( $this->customFieldTypes as $type => $value)
	{
		$selected = trim($type) == $this->row->type ? ' selected="selected"' : '';
		$htmlFieldTypeOptions .= '<option value="'. $type .'"'. $selected .'>'. $value .'</option>';
	}
}

global $showMinMax, $showOptions;
$showOptions = False;
$showMinMax = False;
$optionsHelpText = JText::_('COM_SEMINARMAN_OPTIONS_HELP');
switch ($this->row->type)
{
	case 'text':
	case 'textarea':
	case '':
		$showMinMax = True;
		break;
	case 'select':
	case 'singleselect':
	case 'radio':
	case 'list':
	case 'checkbox':
		$showOptions = True;
		break;
	case 'checkboxtos':
		$showOptions = True;
		$optionsHelpText = JText::_('COM_SEMINARMAN_OPTIONS_HELP_TOS');
		break;
	default:
		break;
}

?>

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
				<label><?php echo JText::_('COM_SEMINARMAN_REQUIRED');?></label>
				<fieldset class="radio"><?php echo JHTML::_('select.booleanlist', 'required', '', $this->row->required); ?></fieldset>
			</li>
			<li>
				<label for="name"><?php echo JText::_('COM_SEMINARMAN_NAME'); ?></label>
				<input name="name" type="text" size="50" value="<?php echo $this->row->name; ?>" />
			</li>
			<li>
				<label for="fieldcode"><?php echo JText::_('COM_SEMINARMAN_FIELD_CODE'); ?></label>
				<input class="hasTip" title="<?php echo JText::_('COM_SEMINARMAN_FIELDCODE_NOTES'); ?>" name="fieldcode" type="text" size="50" value="<?php echo $this->row->fieldcode; ?>" /><?php echo $infoimage; ?>
			</li>
			<li>
				<label for="paypalcode"><?php echo JText::_('COM_SEMINARMAN_PAYPAL_CODE'); ?></label>
				<input class="hasTip" title="<?php echo JText::_('COM_SEMINARMAN_PAYPALCODE_NOTES'); ?>" name="paypalcode" type="text" size="50" value="<?php echo $this->row->paypalcode; ?>" /><?php echo $infoimage; ?>
			</li>
			<li>
				<label for="tips"><?php echo JText::_('COM_SEMINARMAN_TOOLTIP'); ?></label>
				<input class="editlinktip" name="tips" type="text" size="50" value="<?php echo $this->row->tips; ?>" />
			</li>
			<li>
				<label for="group"><?php echo JText::_('COM_SEMINARMAN_GROUP'); ?></label>
				<select name="group"><?php echo $htmlGroupOptions; ?></select>
			</li>
			<li>
				<label for="type"><?php echo JText::_('COM_SEMINARMAN_TYPE'); ?></label>
				<select name="type" onchange="javascript:showOptions();" onkeyup="javascript:showOptions();"><?php echo $htmlFieldTypeOptions; ?></select>
			</li>
			<li<?php if (!$showMinMax) echo ' style="display: none;"'?> id="li_min">
				<label><?php echo JText::_('COM_SEMINARMAN_MINIMUM_CHARACTERS'); ?></label>
				<input type="text" name="min" size="5" value="<?php echo $this->row->min; ?>" />
			</li>
			<li<?php if (!$showMinMax) echo ' style="display: none;"'?> id="li_max">
				<label><?php echo JText::_('COM_SEMINARMAN_MAXIMUM_CHARACTERS'); ?></label>
				<input type="text" name="max" size="5" value="<?php echo $this->row->max; ?>" />
			</li>
			<li<?php if (!$showOptions) echo ' style="display: none;"'?> id="li_options">
				<label><?php echo $optionsHelpText; ?></label>
				<textarea name="options" rows="4" cols="50"><?php echo $this->row->options; ?></textarea>
			</li>
		</ul>
	</fieldset>
</div>


<?php echo JHTML::_('form.token'); ?>
<input type="hidden" name="option" value="com_seminarman" />
<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
<input type="hidden" name="controller" value="editfield" />
<input type="hidden" name="view" value="editfields" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="ordering" value="<?php echo $this->row->ordering; ?>" />
</form>

<?php JHTML::_('behavior.keepalive'); ?>