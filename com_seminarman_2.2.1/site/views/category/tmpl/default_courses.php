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
	<th id="qf_code" class="sectiontableheader" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_COURSE_CODE', 'i.code', $this->lists['filter_order_Dir'], $this->lists['filter_order']); ?></th>
	<th id="qf_title" class="sectiontableheader"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_COURSE_TITLE', 'i.title', $this->lists['filter_order_Dir'], $this->lists['filter_order']); ?></th>
	<th id="qf_start_date" class="sectiontableheader" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_START_DATE', 'i.start_date', $this->lists['filter_order_Dir'], $this->lists['filter_order']); ?></th>
	<th id="qf_finish_date" class="sectiontableheader" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_FINISH_DATE', 'i.finish_date', $this->lists['filter_order_Dir'],	$this->lists['filter_order']); ?></th>
<?php if ($this->params->get('show_location')): ?>
	<th id="qf_location" class="sectiontableheader"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_LOCATION', 'i.location', $this->lists['filter_order_Dir'],	$this->lists['filter_order']); ?></th>
<?php endif; ?>
	<th id="qf_price" class="sectiontableheader"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_PRICE', 'i.price', $this->lists['filter_order_Dir'], $this->lists['filter_order']); ?>*</th>
<?php if ($this->params->get('enable_bookings')): ?>
	<th id="qf_application" class="sectiontableheader"></th>
<?php endif; ?>
</tr>
</thead>

<tbody>

<?php
if (($this->params->get('second_currency') != 'NONE') && ($this->params->get('second_currency') != $this->params->get('currency'))){
   	if (doubleval($this->params->get('factor')) > 0) {
        $show_2_price = true;
        $sec_currency = $this->params->get('second_currency');
        $factor = doubleval(str_replace(",", ".", $this->params->get('factor')));		
    } else {
        $show_2_price = false;		
    }
} else {
    $show_2_price = false;    	
}
$i=0;
foreach ($this->courses as $course):
	$itemParams = new JParameter($course->attribs);
?>
<tr class="sectiontableentry" >
	<td headers="qf_code" nowrap="nowrap"><?php echo $this->escape($course->code); ?></td>
	<td headers="qf_title"><strong><a href="<?php echo JRoute::_('index.php?view=courses&cid=' . $this->category->slug . '&id=' . $course->slug . '&Itemid=' . $Itemid); ?>"><?php echo $this->escape($course->title); ?></a></strong><?php echo $course->show_new_icon; echo $course->show_sale_icon; ?></td>
	<td headers="qf_start_date" nowrap="nowrap"><?php echo $course->start_date; ?></td>
	<td headers="qf_finish_date" nowrap="nowrap"><?php echo $course->finish_date; ?></td>
<?php if ($this->params->get('show_location')): ?>
	<td headers="qf_location">
        <?php
    if ( empty( $course->location ) ) {
            echo JText::_('COM_SEMINARMAN_NOT_SPECIFIED');
    }
    else {
                if ( empty( $course->url ) || $course->url == "http://" ) {
                        echo $course->location;
                }
                else {?>
                        <a href='<?php echo $course->url; ?>' target="_blank"><?php echo $course->location; ?></a>
                        <?php
                }
    }
    ?>	
	</td>
<?php endif; ?>
	<td headers="qf_price">
<?php
    if ($show_2_price) {
    	echo $course->price.'&nbsp;'.$course->currency_price.' ('. JText::sprintf('%.2f', round(doubleval($factor*(doubleval(str_replace(",", ".", $course->price)))), 2)) . '&nbsp;' . $sec_currency . ') ' .$course->price_type;
    } else {
        echo $course->price.'&nbsp;'.$course->currency_price.' '.$course->price_type; 
    }
?>
    </td>
<?php if ($this->params->get('enable_bookings')): ?>
	<td class="centered" headers="qf_book"><?php echo $course->book_link; ?></td>
<?php endif; ?>
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
<div class="right"></div>

<input type="hidden" name="option" value="com_seminarman" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['filter_order'];?>" />
<input type="hidden" name="filter_order_Dir" value="" />
<input type="hidden" name="view" value="category" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo $this->category->id;?>" />
</form>

<div class="pagination"><?php echo $this->pageNav->getPagesLinks(); ?></div>
