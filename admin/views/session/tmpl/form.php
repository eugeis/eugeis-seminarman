<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php
JHTML::_('behavior.tooltip');
JHTML::_('behavior.calendar');

$edit = JRequest::getVar('edit', true);
$text = !$edit ? JText::_('COM_SEMINARMAN_NEW') : JText::_('COM_SEMINARMAN_EDIT');
JToolBarHelper::title(JText::_('COM_SEMINARMAN_SESSION') . ': <span class="small">[ ' . $text . ' ]</span>', 'sitzung');
JToolBarHelper::apply();
JToolBarHelper::save();
if (!$edit)
    JToolBarHelper::cancel();
else
    JToolBarHelper::cancel('cancel', 'COM_SEMINARMAN_CLOSE');
?>

<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function(task){
		var form = document.adminForm;
		if (task == 'cancel') {
			Joomla.submitform( task );
			return;
		}

		// do field validation
		if (form.title.value == ""){
			alert( "<?php echo JText::_( 'COM_SEMINARMAN_MISSING_TITLE', true ); ?>" );
		} else if (form.courseid.value == "0"){
			alert( "<?php echo JText::_( 'COM_SEMINARMAN_SELECT_COURSE', true ); ?>" );
		} else if (form.session_date.value == ""){
			alert( "<?php echo JText::_( 'COM_SEMINARMAN_MISSING_DATE', true ); ?>" );
		} else {
			Joomla.submitform( task );
		}
	}
</script>

<style type="text/css">

fieldset.adminform label {
	min-width: 100px;
	text-align: right;
	padding-right: 10px;
	margin: 3px 0;
}

fieldset input, fieldset select, fieldset img, fieldset button {
    float: left;
    margin: 3px 5px 3px 0;
    width: auto;
    max-width: 200px;
}

fieldset.adminform {
	margin: 5px;
}

fieldset.radio {
    border: 0 none;
    float: left;
    margin: 0 0 5px;
    padding: 0 ! important;
}

fieldset.radio label {
    clear: none;
    display: inline;
    float: left;
    padding-left: 0;
    padding-right: 10px;
    min-width: 0 ! important;
}

</style>


<form action="index.php" method="post" name="adminForm" id="adminForm">

<div class="width-50 fltlft">
	<fieldset class="adminform">
		<ul class="adminformlist">
			<li>
				<label><?php echo JText::_('JPUBLISHED'); ?></label>
				<fieldset id="jform_type" class="radio inputbox"><?php echo $this->lists['published']; ?></fieldset>
			</li>
			<li>
				<label><?php echo JText::_('COM_SEMINARMAN_COURSE'); ?></label>
				<?php echo $this->lists['courseid']; ?>
			</li>
			<li>
				<label for="title"><?php echo JText::_('COM_SEMINARMAN_TITLE'); ?></label>
				<input class="inputbox required" type="text" name="title" id="title" size="32" maxlength="100" value="<?php echo $this->session->title; ?>" />
			</li>
			<li>
				<label for="alias"><?php echo JText::_('COM_SEMINARMAN_ALIAS'); ?></label>
				<input class="inputbox" type="text" name="alias" id="alias" size="32" maxlength="100" value="<?php echo $this->session->alias; ?>" />
			</li>
			<li>
				<label for="session_day"><?php echo JText::_( 'COM_SEMINARMAN_DAY' ); ?></label>
				<?php echo $this->lists['session_date'];?>
			</li>
			<li>
				<label for="start_time"><?php echo JText::_( 'COM_SEMINARMAN_SESSION_START_TIME' ); ?></label>
				<input class="text_area" type="text" name="start_time" id="start_time" size="10" maxlength="8" onChange="copyStartTime();" value="<?php echo date('H:i', strtotime($this->session->start_time));?>" />
			</li>
			<li>
				<label for="finish_time"><?php echo JText::_( 'COM_SEMINARMAN_SESSION_FINISH_TIME' ); ?></label>
				<input class="text_area" type="text" name="finish_time" id="finish_time" size="10" maxlength="8" value="<?php echo date('H:i', strtotime($this->session->finish_time));?>" />
			</li>
			<li>
				<label for="duration"><?php echo JText::_( 'COM_SEMINARMAN_TOTAL_DURATION' ); ?></label>
				<input class="text_area" type="text" name="duration" id="duration" size="10" maxlength="10" value="<?php echo $this->session->duration;?>" />
			</li>
			<li>
				<label for="session_location"><?php echo JText::_( 'COM_SEMINARMAN_ROOM' ); ?></label>
				<input class="text_area" type="text" name="session_location" id="session_location" size="32" maxlength="100" value="<?php echo $this->session->session_location;?>" />
			</li>
			<li>
				<label for="ordering"><?php echo JText::_('COM_SEMINARMAN_ORDERING'); ?></label>
				<?php echo $this->lists['ordering']; ?>
			</li>
		</ul>
		<label for="description"><?php echo JText::_('COM_SEMINARMAN_COMMENTS'); ?></label>
		<textarea class="text_area" cols="44" rows="12" name="description" id="description"><?php echo $this->session->description; ?></textarea>
	</fieldset>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="com_seminarman" />
<input type="hidden" name="controller" value="session" />
<input type="hidden" name="cid[]" value="<?php echo $this->session->id; ?>" />
<input type="hidden" name="task" value="" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
