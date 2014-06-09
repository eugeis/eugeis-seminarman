<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.html.parameter' );

$colspan = ($this->params->get('show_location')) ? 4 : 3;
$Itemid = JRequest::getInt('Itemid');
?>

<form action="<?php echo $this->action;?>" method="post" id="adminForm2">
<?php if ($this->params->get('filter2') || $this->params->get('display')):?>
<div id="qf_filter2" class="floattext">
		<?php if ($this->params->get('filter')):?>
		<div class="qf_fleft"><?php echo JText::_('COM_SEMINARMAN_COURSE'). ':';?>
			<input type="text" name="filter2" id="filter2" value="<?php echo $this->lists['filter2']; ?>" class="text_area" size="15"/>
			<label for="filter_experience_level2"><?php echo JText::_('COM_SEMINARMAN_LEVEL') ?></label>&nbsp;<?php echo $this->lists['filter_experience_level2'];?>
			<button onclick="document.getElementById('adminForm2').submit();"><?php echo JText::_('COM_SEMINARMAN_GO');?></button>
		</div>
		<?php endif;?>
		<?php if ($this->params->get('display')):?>
		<div class="qf_fright">
			<label for="tmpl_limit"><?php echo JText::_('COM_SEMINARMAN_DISPLAY_NUM') ?><?php echo $this->pageNav2->getLimitBox(); ?></label>
		</div>
		<?php endif;?>
</div>
<?php endif;?>

<table class="seminarmancoursetable" summary="seminarman">
<thead>
<tr>
	<th id="qf_code2" class="sectiontableheader" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_COURSE_CODE', 'i.code', $this->lists['filter_order_Dir2'], $this->lists['filter_order2'], 'il'); ?></th>
	<th id="qf_title2" class="sectiontableheader"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_COURSE_TITLE', 'i.title', $this->lists['filter_order_Dir2'], $this->lists['filter_order2'], 'il'); ?></th>
<?php if ($this->params->get('show_location')): ?>
	<th id="qf_location2" class="sectiontableheader"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_LOCATION', 'i.location', $this->lists['filter_order_Dir2'],	$this->lists['filter_order2'], 'il'); ?></th>
<?php endif;?>
	<th id="qf_price2" class="sectiontableheader"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_PRICE', 'i.price', $this->lists['filter_order_Dir2'], $this->lists['filter_order2'], 'il'); ?>*</th>
</tr>
</thead>

<tbody>

<?php

$i=0;
foreach ($this->templates as $template):
	$itemParams = new JParameter($template->attribs);
?>
<tr class="sectiontableentry" >
	<td headers="qf_code" nowrap="nowrap"><?php echo $this->escape($template->code); ?></td>
	<td headers="qf_title"><strong><a href="<?php echo JRoute::_('index.php?view=templates&cid=' . $this->category->slug . '&id=' . $template->slug . '&Itemid=' . $Itemid); ?>"><?php echo $this->escape($template->title); ?></a></strong></td>
<?php if ($this->params->get('show_location')): ?>
	<td headers="qf_location"><?php echo $template->location; ?></td>
<?php endif;?>	
	<td headers="qf_price"><?php echo $template->price . '&nbsp;'. $template->currency_price.' ' . $template->price_type; ?></td>
</tr>


<?php
$i++;
endforeach;
?>
<tr class="sectiontableentry" >
	<td colspan="<?php echo $colspan; ?>" class="right">*<?php echo ($this->params->get('show_gross_price') == 1) ? JText::_('COM_SEMINARMAN_WITH_VAT') : JText::_('COM_SEMINARMAN_WITHOUT_VAT'); ?></td>
</tr>
</tbody>
</table>


<input type="hidden" name="option" value="com_seminarman" />
<input type="hidden" name="filter_order2" value="<?php echo $this->lists['filter_order2'];?>" />
<input type="hidden" name="filter_order_Dir2" value="" />
<input type="hidden" name="view" value="category" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo $this->category->id;?>" />
</form>

<div class="pagination"><?php echo $this->pageNav2->getPagesLinks(); ?></div>