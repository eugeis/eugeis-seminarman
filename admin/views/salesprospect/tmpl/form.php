<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');

jimport('joomla.html.pane');

$pane	=& JPane::getInstance('Tabs');
?>


<?php


$edit = JRequest::getVar('edit', true);
$text = !$edit ? JText::_('New') : JText::_('COM_SEMINARMAN_EDIT');
JToolBarHelper::title(JText::_('COM_SEMINARMAN_SALES_PROSPECT_REQUEST') . ': <span class="small">[ ' . $text .
    ' ]</span>', 'applications');
JToolBarHelper::apply();
JToolBarHelper::save();
if (!$edit)
{
    JToolBarHelper::cancel();
} else
{

    JToolBarHelper::cancel('cancel', 'COM_SEMINARMAN_CLOSE');
}

?>

<style type="text/css">
select {
    margin-bottom: 0 !important;
}
</style>

<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}

		// do field validation
		if (form.first_name.value == ""){
			alert( "<?php

echo JText::_('COM_SEMINARMAN_MISSING_FIRST_NAME', true);

?>" );
		} else {
			submitform( pressbutton );
		}
	}
</script>
<style type="text/css">
	table.paramlist td.paramlist_key {
		width: 92px;
		text-align: left;
		height: 30px;
	}
</style>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<?php
echo $pane->startPane( 'customfields-fields' );
echo $pane->startPanel( JText::_('COM_SEMINARMAN_SALES_PROSPECT_REQUEST') , 'details-page' );
?>
	<table class="paramlist admintable" cellspacing="1" style="width: 100%;">
	<tbody><tr><td>

<div class="width-60 fltlft">
	<fieldset class="adminform">
	<legend><?php echo JText::_('COM_SEMINARMAN_ACCOUNT_DETAILS'); ?></legend>
	<table class="admintable">
		<tr>
			<td><label for="name"><?php echo JText::_('COM_SEMINARMAN_USER_NAME'); ?>:</label></td>
			<td ><?php echo $this->lists['username']; ?></td>
		</tr>
		<tr>
			<td class="key"><label for="name"><?php echo JText::_('COM_SEMINARMAN_SALUTATION'); ?>:</label></td>
			<td><?php echo $this->lists['salutation']; ?></td>
		</tr> 
		<tr>
			<td><label for="title"><?php echo JText::_('COM_SEMINARMAN_TITLE'); ?>:</label></td>
			<td><input class="text_area" type="text" name="title" id="title" size="32" maxlength="100" value="<?php echo $this->application->title; ?>" /></td>
		</tr>
		<tr>
			<td><label for="title"><?php echo JText::_('COM_SEMINARMAN_FIRST_NAME'); ?>:</label></td>
			<td><input class="text_area" type="text" name="first_name" id="first_name" size="32" maxlength="100" value="<?php echo $this->application->first_name; ?>" /></td>
		</tr>
		<tr>
			<td><label for="alias"><?php echo JText::_('COM_SEMINARMAN_LAST_NAME'); ?>:</label></td>
			<td><input class="text_area" type="text" name="last_name" id="last_name" size="32" maxlength="100" value="<?php echo $this->application->last_name; ?>" /></td>
		</tr>
        <tr>
			<td><label for="email"><?php echo JText::_('COM_SEMINARMAN_EMAIL'); ?>:</label></td>
			<td><input class="text_area" type="text" name="email" id="email" size="32" maxlength="100" value="<?php echo $this->application->email; ?>" /></td>
		</tr>
		
	</table>
	</fieldset>
</div>

<div class="width-60 fltlft">
	<fieldset class="adminform">
	<legend><?php echo JText::_('COM_SEMINARMAN_TEMPLATE_DETAILS'); ?></legend>
	<table class="admintable">
		<tr>
			<td><label for="attendees"><?php echo JText::_('COM_SEMINARMAN_NUMBER_OF_ATTENDEES'); ?>:</label></td>
			<td><input class="text_area" type="text" name="attendees" id="attendees" size="4" maxlength="3" value="<?php echo $this->application->attendees; ?>" disabled="disabled" /></td>
		</tr>
		<tr>
			<td><label for="template_id"><?php echo JText::_('COM_SEMINARMAN_ID'); ?>:</label></td>
			<td><input class="text_area" type="text" name="template_id" id="template_id" size="32" maxlength="100" value="<?php echo $this->application->template_id; ?>" disabled="disabled" /></td>
		</tr>
		<tr>
			<td><label for="code"><?php echo JText::_('COM_SEMINARMAN_COURSE_CODE'); ?>:</label></td>
			<td><input class="text_area" type="text" name="code" id="code" size="32" maxlength="100" value="<?php echo $this->application->code; ?>" disabled="disabled" /></td>
		</tr>
        <tr>
			<td><label for="course_title"><?php echo JText::_('COM_SEMINARMAN_COURSE_TITLE'); ?>:</label></td>
			<td><input class="text_area" type="text" name="course_title" id="course_title" size="32" maxlength="100" value="<?php echo $this->application->course_title; ?>" disabled="disabled" /></td>
		</tr>
        <tr>
			<td><label for="course_price"><?php echo JText::_('COM_SEMINARMAN_PRICE') .' ('. JText::_('COM_SEMINARMAN_NET') .')'; ?>:</label></td>
			<td><input class="text_area" type="text" name="course_price" id="course_price" size="32" maxlength="100" value="<?php echo $this->application->price . " " . $this->application->currency_price . " " . $this->application->price_type; ?>" disabled="disabled" /></td>
		</tr>
        <tr>
			<td><label for="course_price_total"><?php echo JText::_('COM_SEMINARMAN_TOTAL_PRICE') .' ('. JText::_('COM_SEMINARMAN_NET') .')'; ?>:</label></td>
			<td><input class="text_area" type="text" name="course_price_total" id="course_price_total" size="32" maxlength="100" value="<?php echo $this->application->price_total . " " . $this->application->currency_price ." " . $this->application->price_type; ?>" disabled="disabled" /></td>
		</tr>
		<tr>
			<td><label for="course_price_vat"><?php echo JText::_('COM_SEMINARMAN_VAT'); ?>:</label></td>
			<td><input class="text_area" type="text" name="course_price_vat" id="course_price_vat" size="32" maxlength="100" value="<?php echo $this->application->price_vat; ?>%" disabled="disabled" /></td>
		</tr>
		<tr>
			<td>
				<div class="button2-left">
				<div class="blank">
					<a title="<?php echo JText::_('COM_SEMINARMAN_VIEW_TEMPLATE'); ?>" href="<?php echo "index.php?option=com_seminarman&controller=templates&task=edit&cid[]=". $this->application->template_id;?>" target="_self"><?php echo JText::_('COM_SEMINARMAN_VIEW_TEMPLATE'); ?></a>
				</div>
				</div>
			</td>
		</tr>
	</table>
	</fieldset>
</div>

<div class="width-60 fltlft">
	<fieldset class="adminform">
	<legend><?php echo JText::_('COM_SEMINARMAN_SALES_PROSPECT_NOTIFICATION'); ?></legend>
	<table class="admintable">
		<tr>
			<td><?php echo JText::_('COM_SEMINARMAN_NOTIFY_SEND'); ?>:&nbsp;&nbsp;&nbsp;</td>
			<td><?php echo $this->application->notified == $this->nullDate ? JText::_('COM_SEMINARMAN_NOT_NOTIFIED') : JHTML::date($this->application->notified, JText::_('COM_SEMINARMAN_DATETIME_FORMAT1')) .'&nbsp;&nbsp(<a href="index.php?option=com_seminarman&controller=courses&task=edit&cid[]='. $this->application->notified_course .'">'. JText::_('COM_SEMINARMAN_COURSE') .' '. $this->application->notified_course . '</a>)'; ?></td>
		</tr>
	</table>
	</fieldset>
</div>

</tr></td>
</tbody>
</table>

<?php echo $pane->endPanel();
// Create custom tabs and display custom fields and data
foreach( $this->user->customfields->fields as $group => $groupFields )
{
	echo $pane->startPanel( $group , $group . '-page' );
?>
	<table class="paramlist admintable" cellspacing="1" style="width: 100%;">
	<tbody>
<?php
foreach( $groupFields as $field )
{
$field	= JArrayHelper::toObject ( $field );
$field->value = $this->escape( $field->value );
//$field->options = array('yes','no');
?>
		<tr>
			<td class="paramlist_key" id="lblfield<?php echo $field->id;?>"><?php if($field->required == 1) echo '*'; ?><?php echo JText::_( $field->name );?></td>
			<td class="paramlist_value">
<?php
if ($field->type == 'checkboxtos')
{
	if ($field->value == '1')
		echo JText::_('COM_SEMINARMAN_ACCEPTED');
	else
		echo 'unknown';
	echo '<input type="hidden" name="field'. $field->id .'" value="'. $field->value .'" />';
	
}
else
	echo SeminarmanCustomfieldsLibrary::getFieldHTML( $field , '' );
?>
			</td>
		</tr>
<?php
}
?>
	</tbody>
	</table>
<?php
echo $pane->endPanel();

}

echo $pane->startPanel( JText::_('COM_SEMINARMAN_COMMENTS') , 'details-page' );
?>
<table class="paramlist admintable" cellspacing="1" style="width: 100%;">
<tbody>
	<tr>
		<td><?php echo JText::_('COM_SEMINARMAN_COMMENTS'); ?></td>
		<td class="paramlist_value"><textarea class="text_area" cols="64" rows="12" name="comments" id="comments"><?php echo $this->application->comments; ?></textarea></td>
	</tr>
</tbody>
</table>
<?php
echo $pane->endPanel();
echo $pane->endPane();
?>
<div class="clr"></div>
	<input type="hidden" name="option" value="com_seminarman" />
    <input type="hidden" name="controller" value="salesprospect" />
	<input type="hidden" name="cid[]" value="<?php echo $this->application->id; ?>" />
    <input type="hidden" name="user_id" value="<?php echo $this->application->user_id; ?>" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_('form.token'); ?>
</form>
