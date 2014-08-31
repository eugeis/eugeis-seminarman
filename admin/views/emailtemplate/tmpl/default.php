<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.pane');

$editor = JFactory::getEditor();
JHTML::_('behavior.calendar');
JHTMLBehavior::formvalidation();

$ADMINPATH = JPATH_BASE . '\components\com_seminarman';

?>
<?php

JToolBarHelper::title(JText::_('COM_SEMINARMAN_EMAIL_TEMPLATE'), 'config');
JToolBarHelper::apply();
JToolBarHelper::save();
JToolBarHelper::cancel();

?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function(task){
		if (task == 'cancel') {
			Joomla.submitform( task );
			return;
		}
		
		var form = document.adminForm;

		// do field validation
		if (form.title.value == ""){
			alert( "<?php echo JText::_('COM_SEMINARMAN_MISSING_NAME', true); ?>" );
		} else {
			Joomla.submitform( task );
		}
	}
</script>

<?php
if(!isset($this->emailtemplate->recipient))
	$this->emailtemplate->recipient = "{EMAIL}";
if(!isset($this->emailtemplate->bcc))
	$this->emailtemplate->bcc = "{ADMIN_CUSTOM_RECIPIENT}";
?>

<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" >
<div class="width-60 fltlft">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_SEMINARMAN_EMAIL_TEMPLATE'); ?></legend>
		<ul class="adminformlist">
			<li>
				<label for="title"><?php echo JText::_('COM_SEMINARMAN_NAME'); ?></label>
				<input class="inputbox required" type="text" name="title" id="title" size="60" maxlength="255" value="<?php if (isset($this->emailtemplate)) echo htmlspecialchars($this->emailtemplate->title, ENT_QUOTES, 'UTF-8'); ?>" />
			</li>
			<li>
				<label for="recipient"><?php echo JText::_('COM_SEMINARMAN_SUBJECT'); ?></label>
				<input class="inputbox" type="text" name="subject" id="subject" size="60" maxlength="255" value="<?php if (isset($this->emailtemplate)) echo htmlspecialchars($this->emailtemplate->subject, ENT_QUOTES, 'UTF-8'); ?>" />
			</li>
			<li>
				<label for="subject"><?php echo JText::_('COM_SEMINARMAN_RECIPIENT');?></label>
				<input class="inputbox" type="text" name="recipient" id="recipient" size="60" maxlength="255" value="<?php echo $this->emailtemplate->recipient; ?>" />
			</li>
			<li>
				<label for="bcc">BCC</label>
				<input class="inputbox" type="text" name="bcc" id="bcc" size="60" maxlength="255" value="<?php echo $this->emailtemplate->bcc; ?>" />
			</li>
			<li>
				<label for="templatefor"><?php echo JText::_('COM_SEMINARMAN_USE_FOR');?></label>
				<?php echo $this->templateforSelect; ?>
			</li>
		</ul>
		<div class="clr"></div>
		<label for="html"><?php echo JText::_('COM_SEMINARMAN_BODY');?></label>
		<div class="clr"></div>
<?php
$editor = JFactory::getEditor();
echo $editor->display('body', $this->emailtemplate->body, '100%', '500', '60', '20', false);
?>
	</fieldset>	
</div>

<div class="width-40 fltrt">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_SEMINARMAN_PARAMETERS'); ?></legend>
		<ul>
			<li>{ADMIN_CUSTOM_RECIPIENT}: <? echo JText::_('COM_SEMINARMAN_RECIPIENT_FROM_CONFIGURATION'); ?></li>
			<li>{ATTENDEES}: <? echo JText::_('COM_SEMINARMAN_NUMBER_OF_ATTENDEES'); ?></li>
			<li>{SALUTATION}: <? echo JText::_('COM_SEMINARMAN_SALUTATION'); ?></li>
			<li>{TITLE}: <? echo JText::_('COM_SEMINARMAN_TITLE'); ?> (<?php echo JText::_('COM_SEMINARMAN_AUTO_SPACE'); ?>)</li>
			<li>{FIRSTNAME}: <? echo JText::_('COM_SEMINARMAN_FIRST_NAME'); ?></li>
			<li>{LASTNAME}: <? echo JText::_('COM_SEMINARMAN_LAST_NAME'); ?></li>
			<li>{EMAIL}: <? echo JText::_('COM_SEMINARMAN_EMAIL'); ?></li>
<?php
foreach ($this->fields as $field)
	if ($field->type != 'checkboxtos')
		echo '			<li>{'. strtoupper($field->fieldcode) .'}: '. $field->name .'</li>';
?>
			<li>{COURSE_ID}: <? echo JText::_('COM_SEMINARMAN_COURSE_ID'); ?></li>
			<li>{COURSE_TITLE}: <? echo JText::_('COM_SEMINARMAN_COURSE_TITLE'); ?></li>
			<li>{COURSE_CODE}: <? echo JText::_('COM_SEMINARMAN_COURSE_CODE'); ?></li>
			<li>{COURSE_INTROTEXT}: <? echo JText::_('COM_SEMINARMAN_COURSE_INTROTEXT'); ?></li>
			<li>{COURSE_FULLTEXT}: <? echo JText::_('COM_SEMINARMAN_COURSE_FULLTEXT'); ?></li>
			<li>{COURSE_CAPACITY}: <? echo JText::_('COM_SEMINARMAN_CAPACITY'); ?></li>
			<li>{COURSE_LOCATION}: <? echo JText::_('COM_SEMINARMAN_LOCATION'); ?></li>
			<li>{COURSE_URL}: <? echo JText::_('COM_SEMINARMAN_HYPERLINK'); ?></li>
			<li>{COURSE_START_DATE}: <? echo JText::_('COM_SEMINARMAN_START_DATE'); ?></li>
			<li>{COURSE_FINISH_DATE}: <? echo JText::_('COM_SEMINARMAN_FINISH_DATE'); ?></li>
			<li>{COURSE_START_TIME}: <? echo JText::_('COM_SEMINARMAN_START_TIME'); ?></li>
			<li>{COURSE_FINISH_TIME}: <? echo JText::_('COM_SEMINARMAN_FINISH_TIME'); ?></li>
			<li>{COURSE_START_WEEKDAY}: <? echo JText::_('COM_SEMINARMAN_START_WEEKDAY'); ?></li>
			<li>{COURSE_FIRST_SESSION_TITLE}: <? echo JText::_('COM_SEMINARMAN_COURSE_FIRST_SESSION_TITLE'); ?></li>
			<li>{COURSE_FIRST_SESSION_CLOCK}: <? echo JText::_('COM_SEMINARMAN_COURSE_FIRST_SESSION_CLOCK'); ?></li>
			<li>{COURSE_FIRST_SESSION_DURATION}: <? echo JText::_('COM_SEMINARMAN_COURSE_FIRST_SESSION_DURATION'); ?></li>
			<li>{COURSE_FIRST_SESSION_ROOM}: <? echo JText::_('COM_SEMINARMAN_COURSE_FIRST_SESSION_ROOM'); ?></li>
			<li>{COURSE_FIRST_SESSION_COMMENT}: <? echo JText::_('COM_SEMINARMAN_COURSE_FIRST_SESSION_COMMENT'); ?></li>
			<li>{PRICE_PER_ATTENDEE}: <? echo JText::_('COM_SEMINARMAN_PRICE_PER_ATTENDEE'); ?></li>
			<li>{PRICE_PER_ATTENDEE_VAT}: <? echo JText::_('COM_SEMINARMAN_PRICE_PER_ATTENDEE_VAT'); ?></li>
			<li>{PRICE_TOTAL}: <? echo JText::_('COM_SEMINARMAN_TOTAL_PRICE'); ?></li>
			<li>{PRICE_TOTAL_VAT}: <? echo JText::_('COM_SEMINARMAN_TOTAL_PRICE_VAT'); ?></li>
			<li>{PRICE_VAT_PERCENT}: <? echo JText::_('COM_SEMINARMAN_VAT'); ?></li>
			<li>{PRICE_VAT}: <? echo JText::_('COM_SEMINARMAN_VAT_ABS'); ?></li>
			<li>{PRICE_TOTAL_DISCOUNT}: <? echo JText::_('COM_SEMINARMAN_TOTAL_DISCOUNT'); ?></li>
			<li>{PRICE_TOTAL_ORIG_VAT}: <? echo JText::_('COM_SEMINARMAN_TOTAL_ORIG_VAT'); ?></li>
			<li>{PRICE_REAL_BOOKING_SINGLE}: <? echo JText::_('COM_SEMINARMAN_REAL_BOOKING_SINGLE'); ?></li>
			<li>{PRICE_REAL_BOOKING_TOTAL}: <? echo JText::_('COM_SEMINARMAN_REAL_BOOKING_TOTAL'); ?></li>
			<li>{PRICE_GROUP_ORDERED}: <? echo JText::_('COM_SEMINARMAN_PRICE_GROUP_ORDERED'); ?></li>
			<li>{TUTOR}: <? echo JText::_('COM_SEMINARMAN_DISPLAY_NAME'); ?></li>
			<li>{TUTOR_FIRSTNAME}: <? echo JText::_('COM_SEMINARMAN_FIRST_NAME'); ?></li>
			<li>{TUTOR_LASTNAME}: <? echo JText::_('COM_SEMINARMAN_LAST_NAME'); ?></li>
			<li>{TUTOR_SALUTATION}: <? echo JText::_('COM_SEMINARMAN_SALUTATION'); ?></li>
			<li>{TUTOR_OTHER_TITLE}: <? echo JText::_('COM_SEMINARMAN_OTHER_TITLE'); ?></li>
			<li>{GROUP}: <? echo JText::_('COM_SEMINARMAN_GROUP'); ?></li>
			<li>{GROUP_DESC}: <? echo JText::_('COM_SEMINARMAN_GROUP').' '.JText::_('COM_SEMINARMAN_DESCRIPTION'); ?></li>
			<li>{EXPERIENCE_LEVEL}: <? echo JText::_('COM_SEMINARMAN_EXPERIENCE_LEVEL'); ?></li>
			<li>{EXPERIENCE_LEVEL_DESC}: <? echo JText::_('COM_SEMINARMAN_EXPERIENCE_LEVEL').' '.JText::_('COM_SEMINARMAN_DESCRIPTION'); ?></li>
		</ul>
	</fieldset>
</div>


<input type="hidden" name="check" value="post"/>
<input type="hidden" name="id" value="<?php echo $this->emailtemplate->id; ?>" />
<input type="hidden" name="option" value="com_seminarman" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="emailtemplate" />
<?php echo JHTML::_('form.token');?>
</form>
