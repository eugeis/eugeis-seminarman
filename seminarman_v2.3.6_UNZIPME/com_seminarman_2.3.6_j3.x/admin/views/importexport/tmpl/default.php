<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');
?>
<script type="text/javascript">

	Joomla.submitbutton = function(task) {
		var form = document.adminForm;
		if (task == 'cancel') {
			Joomla.submitform( task );
			return;
		}
			Joomla.submitform( task );
	};

	function showOptions() {
		switch(document.adminForm2.datatype.value) {
		case 'courses':
		case 'sessions':
		case 'applications':
			document.getElementById('li_course').style.display='block';
			document.getElementById('li_date').style.display='block';
			document.getElementById('li_template').style.display='none';
			break;
		case 'salesprospects':
		case 'templates':
			document.getElementById('li_course').style.display='none';
			document.getElementById('li_date').style.display='none';
			document.getElementById('li_template').style.display='block';
			break;
		case 'tutors':
			document.getElementById('li_course').style.display='none';
			document.getElementById('li_date').style.display='none';
			document.getElementById('li_template').style.display='none';
			break;
		}
	}
</script>

<style type="text/css">
div.current label, div.current {
    min-width: 200px;
}
</style>
<?php

jimport('joomla.html.pane');

$pane = JPaneOSG::getInstance('tabs', array('startOffset' => 0));
echo $pane->startPane('pane');
?>

<form id ="adminForm" name="adminForm" action="<?php echo $this->path; ?>" method="post" enctype="multipart/form-data">
<input type="hidden" name="option" value="com_seminarman" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="importexport" />
<input type="hidden" name="view" value="" />
<input type="hidden" name="boxchecked" value="0" />
<?php echo JHTML::_('form.token'); ?>
</form>

<?php echo $pane->startPanel(JText::_('COM_SEMINARMAN_EXPORT_DATA'), 'panel1'); ?>
<form id ="adminForm2" name="adminForm2" action="index.php?option=com_seminarman&controller=importexport&task=exportcsv" method="post" enctype="multipart/form-data">
	<fieldset>
		<ul class="adminformlist">
			<li>
				<label><?php echo JText::_('COM_SEMINARMAN_EXPORT_DATA'); ?></label>
				<?php echo $this->expselect; ?>
			</li>
			<li id="li_course">
				<label><?php echo JText::_('COM_SEMINARMAN_EXPORT_ONLY_COURSE'); ?></label>
				<?php echo $this->expcourse; ?>
			</li>
			<li id="li_template" style="display: none;">
				<label><?php echo JText::_('COM_SEMINARMAN_EXPORT_ONLY_TEMPLATE'); ?></label>
				<?php echo $this->exptemplate; ?>
			</li>
			<li id="li_date">
				<label><?php echo JText::_('COM_SEMINARMAN_EXPORT_ONLY_BETWEEN'); ?></label>
				<?php echo $this->expfromdate; ?><?php echo $this->exptodate; ?>
			</li>
		</ul>
		<div class="clr"></div>
		<input type="submit" value="<?php echo JText::_('COM_SEMINARMAN_EXPORT_DATA');?>"/>
	</fieldset>
<?php $pane->endPanel();?>

<?php echo $pane->endPane('pane');?>
</form>