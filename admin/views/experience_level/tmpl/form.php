<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');

$edit = JRequest::getVar('edit', true);
$text = !$edit ? JText::_('COM_SEMINARMAN_NEW') : JText::_('COM_SEMINARMAN_EDIT');
JToolBarHelper::title(JText::_('COM_SEMINARMAN_EXPERIENCE_LEVEL') . ': <span class="small">[ ' . $text . ' ]</span>');
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
			alert( "<?php echo JText::_('COM_SEMINARMAN_MISSING_TITLE', true); ?>" );
		} else {
			Joomla.submitform( task );
		}
	}
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="width-50 fltlft">
	<fieldset class="adminform">
		<ul class="adminformlist">
			<li>
				<label><?php echo JText::_('JPUBLISHED'); ?></label>
				<fieldset id="jform_type" class="radio inputbox"><?php echo $this->lists['published']; ?></fieldset>
			</li>
			<li>
				<label for="title"><?php echo JText::_('COM_SEMINARMAN_TITLE'); ?></label>
				<input class="inputbox required" type="text" name="title" id="title" size="32" maxlength="100" value="<?php echo $this->experience_level->title; ?>" />
			</li>
			<li>
				<label for="alias"><?php echo JText::_('COM_SEMINARMAN_ALIAS'); ?></label>
				<input class="inputbox" type="text" name="alias" id="alias" size="32" maxlength="100" value="<?php echo $this->experience_level->alias; ?>" />
			</li>
			<li>
				<label for="code"><?php echo JText::_('COM_SEMINARMAN_CODE'); ?></label>
				<input class="inputbox" type="text" name="code" id="code" size="3" maxlength="2" value="<?php echo $this->experience_level->code; ?>" />
			</li>
			<li>
				<label for="ordering"><?php echo JText::_('COM_SEMINARMAN_ORDERING'); ?></label>
				<?php echo $this->lists['ordering']; ?>
			</li>
		</ul>
		<label for="description"><?php echo JText::_('COM_SEMINARMAN_DESCRIPTION'); ?></label>
		<textarea class="text_area" cols="44" rows="12" name="description" id="description"><?php echo $this->experience_level->description; ?></textarea>
	</fieldset>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="com_seminarman" />
<input type="hidden" name="controller" value="experience_level" />
<input type="hidden" name="cid[]" value="<?php echo $this->experience_level->id; ?>" />
<input type="hidden" name="task" value="" />
<?php echo JHTML::_('form.token'); ?>
</form>