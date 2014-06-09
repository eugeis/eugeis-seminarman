﻿<?php defined('_JEXEC') or die('Restricted access'); ?>
<script language="javascript" type="text/javascript">
   function tableOrdering( order, dir, task ) {
   var form = document.adminForm;

   form.filter_order.value    = order;
   form.filter_order_Dir.value   = dir;
   document.adminForm.submit( task );
}
</script>
<?php JHTML::_('behavior.tooltip'); ?>
<?php $columns = 7; ?>
<form action="<?php echo JFilterOutput::ampReplace($this->action); ?>" method="post" name="adminForm">
<table class="bookings_default_item">
<tr>
   <td class="right" colspan="<?php echo $columns; ?>">
   <?php
      echo JText::_('COM_SEMINARMAN_DISPLAY_NUM') .' ';
      echo $this->pagination->getLimitBox();
   ?>
   </td>
</tr>
<?php if ( $this->params->def( 'show_headings', 1 ) ) : ?>
<tr>
 <?php if ( $this->params->get( 'bookings_icons' ) ) : ?> 
   <td class="pix8 centered sectiontableheader<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
      <?php echo JText::_('#'); ?>
   </td>
   <?php else: ?>
  <td class="pix3 centered">
  </td>
   <?php endif; ?>
   
   <td class="proc15 hepix20 centered sectiontableheader<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
      <?php echo JHTML::_('grid.sort',  'COM_SEMINARMAN_START_DATE', 'start_date', $this->lists['order_Dir'], $this->lists['order'] ); ?>
   </td>
    <td class="proc15 hepix20 centered sectiontableheader<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
      <?php echo JHTML::_('grid.sort',  'COM_SEMINARMAN_FINISH_DATE', 'finish_date', $this->lists['order_Dir'], $this->lists['order'] ); ?>
   </td>
    <td class="proc30 hepix20 centered sectiontableheader<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
      <?php echo JHTML::_('grid.sort',  'COM_SEMINARMAN_LOCATION', 'course_location', $this->lists['order_Dir'], $this->lists['order'] ); ?>
   </td>
   <td class="heproc15 centered sectiontableheader<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
      <?php echo JHTML::_('grid.sort',  'COM_SEMINARMAN_PRICE', 'bookedprice', $this->lists['order_Dir'], $this->lists['order'] ); ?>
   </td>
    <td class="proc13 hepix20 centered sectiontableheader<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
      <?php echo JHTML::_('grid.sort',  'COM_SEMINARMAN_STATUS', 'status', $this->lists['order_Dir'], $this->lists['order'] ); ?>
   </td>
    <td class="proc12 hepix20 centered sectiontableheader<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
   </td>
</tr>
<?php endif; ?>
<?php foreach ($this->items as $item) : ?>
<?php $htmlclassnumber = $item->odd + 1; ?>

<tr class="sectiontableentry<?php echo $htmlclassnumber; ?>">
 <?php if ( $this->params->get( 'bookings_icons' ) ) : ?>
   <td class="right">
      <?php if ( $item->image ) : ?>
         <?php echo $item->image;?>  
      <?php else: ?>
         <?php echo $this->pagination->getRowOffset( $item->count ); ?>
      <?php endif; ?>
   </td>
 <?php else: ?> <td class="right"></td>
 <?php endif; ?>
   <td class="hepix20" colspan=<?php echo $columns-1 ?>>
      <?php echo $item->title; ?> 
       - <?php echo $item->grouptitle; ?> 
   </td>
</tr>
<tr class="sectiontableentry<?php echo $htmlclassnumber; ?>">
   <td class="right">
   </td>
   <td class="centered">
   <?php if (($item->start_date)==0) {
   	echo JText::_( 'COM_SEMINARMAN_NOT_SPECIFIED' );
   	} else {
      echo date('j M y', strtotime($item->start_date));
	}?>
   </td>
   <td class="centered">
      <?php if (($item->finish_date)==0) {
   	echo JText::_( 'COM_SEMINARMAN_NOT_SPECIFIED' );
   	} else {
      echo date('j M y', strtotime($item->finish_date));
	}?>
   </td>
   <td class="centered">
      <?php echo $item->course_location; ?>
   </td>
   <td class="centered">
      <?php echo (number_format($item->bookedprice, 2)); ?>
   </td>
   <td class="centered">
   
   	<?php  switch ($item->status) {
    case 0:
        $status_text = JText::_( 'COM_SEMINARMAN_SUBMITTED' );
	    break;
    case 1:
        $status_text =JText::_( 'COM_SEMINARMAN_PENDING' );
        break;
    case 2:
        $status_text = JText::_( 'COM_SEMINARMAN_PAID' );
        break;
    case 3:
    	$status_text = JText::_( 'COM_SEMINARMAN_CANCELED' ); 
        break; 
		}?>
      <?php echo $status_text; ?>
   </td>
   <?php if ( $this->params->get( 'enable_paypal' ) && (($item->start_date) > (gmdate('Y-m-d H:i:s')) )&& (($item->bookedprice)>0)) : ?> 
   <td class="right">
  		<?php if ( ($item->status)<2 ) : ?> 
                     <?php echo $item->paypal_link; ?>
      <?php else: ?>
		<?php echo JText::_( 'COM_SEMINARMAN_NA' ); ?>
	   <?php endif; ?>
   </td>
         <?php else: ?><td class="right"></td>
   <?php endif; ?>
</tr>

<?php endforeach; ?>
<tr>
   <td colspan="<?php echo $columns; ?>" class="centered sectiontablefooter<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
   <?php echo $this->pagination->getPagesLinks(); ?>
   </td>
</tr>
<tr>
   <td colspan="6" class="right pagecounter">
      <?php echo $this->pagination->getPagesCounter(); ?>
   </td>
</tr>
</table>
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
</form>