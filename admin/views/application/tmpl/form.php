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
$params = JComponentHelper::getParams('com_seminarman');
$pane	= JPaneOSG::getInstance('Tabs');
?>


<?php


$edit = JRequest::getVar('edit', true);
$text = !$edit ? JText::_('New') : JText::_('COM_SEMINARMAN_EDIT');
JToolBarHelper::title(JText::_('COM_SEMINARMAN_APPLICATION') . ': <span class="small">[ ' . $text .
    ' ]</span>', 'applications');
        $alt = JText::_('COM_SEMINARMAN_NOTIFY');
        $bar = JToolBar::getInstance( 'toolbar' );
        $bar->appendButton( 'Standard', 'send', $alt, 'notify_booking', false );
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

<script type="text/javascript">
	Joomla.submitbutton = function(pressbutton) {
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
echo $pane->startPanel( JText::_('COM_SEMINARMAN_APPLICATION') , 'details-page' );
?>
	<table class="paramlist admintable" style="width: 100%;">
	<tbody><tr>
<td>
	<fieldset class="adminform">
		<legend><?php echo $this->application->course_title . ' ( ' . JHTML::Date($this->application->start_date, JText::_('COM_SEMINARMAN_DATE_FORMAT1')) . ' - ' . JHTML::Date($this->application->finish_date, JText::_('COM_SEMINARMAN_DATE_FORMAT1')) . ' )'; ?></legend>
		<table class="admintable">
			<tr>
				<td><label for="title"><?php echo JText::_('COM_SEMINARMAN_FIRST_NAME'); ?>:</label></td>
				<td><input class="text_area" type="text" name="first_name" id="first_name" size="32" maxlength="100" value="<?php echo $this->application->first_name; ?>" /></td>
			</tr>
			<tr>
				<td><label for="alias"><?php echo JText::_('COM_SEMINARMAN_LAST_NAME'); ?>:</label></td>
				<td><input class="text_area" type="text" name="last_name" id="last_name" size="32" maxlength="100" value="<?php echo $this->application->last_name; ?>" /></td>
			</tr>
			<tr>
				<td><label for="note"><?php echo JText::_('COM_SEMINARMAN_COURSE_NOTE'); ?>:</label></td>
				<td><input class="text_area" type="text" name="note" id="note" size="32" maxlength="3" value="<?php echo $this->application->note; ?>"/></td>
			</tr>
	        <tr>
				<td><label for="attendance"><?php echo JText::_('COM_SEMINARMAN_COURSE_ATTENDANCE'); ?>:</label></td>
				<td><input class="text_area" type="text" name="attendance" id="attendance" size="32" maxlength="2" value="<?php echo $this->application->attendance; ?>"/></td>
			</tr>
			<tr>
				<td><label for="status"><?php echo JText::_('COM_SEMINARMAN_STATUS'); ?>:</label></td>
				<td><fieldset id="jform_type" class="radio inputbox"><?php echo $this->lists['status']; ?></fieldset></td>
			</tr>
<?php if ($params->get('enable_paypal') == 1): ?>
			<tr>
				<td><label><?php echo JText::_('COM_SEMINARMAN_PAYPAL_TXNID'); ?>:</label></td>
				<td><label><?php if (empty($this->application->transaction_id)) echo '-'; else echo $this->application->transaction_id; ?></label></td>
			</tr>
<?php endif; ?>
		<tr>
			<td><label for="name"><?php echo JText::_('COM_SEMINARMAN_USER_NAME'); ?>:</label></td>
			<td ><?php echo $this->lists['username']; ?></td>
		</tr>		
		<tr>
			<td>
				<div class="button2-left">
				<div class="blank">
					<a title="<?php echo JText::_('COM_SEMINARMAN_VIEW_COURSE_DETAILS'); ?>" href="<?php echo "index.php?option=com_seminarman&controller=courses&task=edit&cid[]=". $this->application->course_id;?>" target="_self"><?php echo JText::_('COM_SEMINARMAN_VIEW_COURSE_DETAILS'); ?></a>
				</div>
				</div>
			</td>
		</tr>		
		</table>
	</fieldset>
</td>
</tr>
</tbody>
</table>

<?php echo $pane->endPanel();
// Create custom tabs and display custom fields and data
foreach( $this->user->customfields->fields as $group => $groupFields )
{
	echo $pane->startPanel( $group , $group . '-page' );
?>
	<table class="paramlist admintable" style="width: 100%;">
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
<table class="paramlist admintable" style="width: 100%;">
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
    <input type="hidden" name="controller" value="application" />
	<input type="hidden" name="cid[]" value="<?php echo $this->application->id; ?>" />
    <input type="hidden" name="user_id" value="<?php echo $this->application->user_id; ?>" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_('form.token'); ?>
</form>
