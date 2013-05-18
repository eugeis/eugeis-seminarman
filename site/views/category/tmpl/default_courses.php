<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.html.parameter' );

$colspan = ($this->params->get('show_location')) ? 7 : 6;
$Itemid = JRequest::getInt('Itemid');

?>

<form action="<?php echo $this->action;?>" method="post" id="adminForm">
<?php if ($this->params->get('filter') || $this->params->get('display')):?>
<div id="qf_filter" class="floattext">
		<?php if ($this->params->get('filter')):?>
		<div class="qf_fleft"><?php echo JText::_('COM_SEMINARMAN_COURSE'). ':';?>
			<input type="text" name="filter" id="filter" value="<?php echo $this->lists['filter']; ?>" class="text_area" size="15"/>
			<label for="filter_experience_level"><?php echo JText::_('COM_SEMINARMAN_LEVEL') ?></label>&nbsp;<?php echo $this->lists['filter_experience_level'];?>
			<button onclick="document.getElementById('adminForm').submit();"><?php echo JText::_('COM_SEMINARMAN_GO');?></button>
		</div>
		<?php endif;?>
		<?php if ($this->params->get('display')):?>
		<div class="qf_fright">
			<label for="limit"><?php echo JText::_('COM_SEMINARMAN_DISPLAY_NUM') ?></label><?php echo $this->pageNav->getLimitBox(); ?>
		</div>
		<?php endif;?>
</div>
<?php endif;?>

<table class="seminarmancoursetable" summary="seminarman">
<thead>
<tr>
	<th id="qf_title" class="sectiontableheader"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_COURSE_TITLE', 'i.title', $this->lists['filter_order_Dir'], $this->lists['filter_order']); ?></th>
	<th id="qf_start_date" class="sectiontableheader" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_START_DATE', 'i.start_date', $this->lists['filter_order_Dir'], $this->lists['filter_order']); ?></th>
	<th id="qf_finish_date" class="sectiontableheader" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_FINISH_DATE', 'i.finish_date', $this->lists['filter_order_Dir'],	$this->lists['filter_order']); ?></th>
<?php if ($this->params->get('show_location')): ?>
	<th id="qf_location" class="sectiontableheader"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_LOCATION', 'i.location', $this->lists['filter_order_Dir'],	$this->lists['filter_order']); ?></th>
<?php endif; ?>
<?php if ($this->params->get('enable_bookings')): ?>
	<th id="qf_application" class="sectiontableheader"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_STATUS', 'i.status', $this->lists['filter_order_Dir'],	$this->lists['filter_order']); ?></th>
<?php endif; ?>
</tr>
</thead>

<tbody>

<?php

$i=0;
foreach ($this->courses as $course):
	$itemParams = new JParameter($course->attribs);
?>
<tr class="sectiontableentry" >
	<td headers="qf_title"><strong><a href="<?php echo JRoute::_('index.php?view=courses&cid=' . $this->category->slug . '&id=' . $course->slug); ?>"><?php echo $this->escape($course->title); ?></a></strong><?php echo $course->show_new_icon; echo $course->show_sale_icon; ?></td>
	<td headers="qf_start_date" nowrap="nowrap"><?php echo $course->start_date; ?></td>
	<td headers="qf_finish_date" nowrap="nowrap"><?php echo $course->finish_date; ?></td>
<?php if ($this->params->get('show_location')): ?>
	<td headers="qf_location"><?php echo $course->location; ?></td>
<?php endif; ?>
<?php if ($this->params->get('enable_bookings')): ?>
	<td class="centered" headers="qf_book"><?php echo $course->book_link; ?></td>
<?php endif; ?>
</tr>


<?php
$i++;
endforeach;
?>
</tbody>
</table>
<div class="right"></div>

<input type="hidden" name="option" value="com_seminarman" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['filter_order'];?>" />
<input type="hidden" name="filter_order_Dir" value="" />
<input type="hidden" name="view" value="category" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo $this->category->id;?>" />
</form>

<div class="pagination"><?php echo $this->pageNav->getPagesLinks(); ?></div>
