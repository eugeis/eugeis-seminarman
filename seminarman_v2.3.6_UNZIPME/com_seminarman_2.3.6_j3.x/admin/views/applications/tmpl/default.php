<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');
jimport('joomla.utilities.date');

$params = JComponentHelper::getParams('com_seminarman');
?>


<form action="<?php echo $this->requestURL; ?>" method="post" name="adminForm" id="adminForm">
<table class="adminform">
<tr>
   <td class="proc100 left">
      <?php echo JText::_('Filter'); ?>:
      <?php echo $this->lists['filter_search']; ?>
      <input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
      <input type="button" onclick="this.form.submit();" value="<?php echo JText::_('COM_SEMINARMAN_GO');?>" />
      <input type="button" onclick="document.getElementById('search').value='';this.form.getElementById('filter_statusid').value='0';this.form.getElementById('filter_courseid').value='0';this.form.getElementById('filter_state').value='';this.form.submit();" value="<?php echo JText::_('COM_SEMINARMAN_RESET'); ?>" />
   </td>
   <td nowrap="nowrap">
      <?php echo $this->lists['statusid']; echo $this->lists['courseid']; echo $this->lists['state']; ?>
   </td>
</tr>
</table>
<div id="editcell">
   <table class="adminlist">
   <thead>
      <tr>
         <th width="5"><?php echo JText::_('COM_SEMINARMAN_NUM'); ?></th>
         <th width="20"><?php echo JHtml::_('grid.checkall'); ?></th>
         <th width="12%" class="title"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_LAST_NAME', 'a.last_name', $this->lists['order_Dir'], $this->lists['order']); ?></th>
         <th width="12%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_FIRST_NAME', 'a.first_name', $this->lists['order_Dir'], $this->lists['order']); ?></th>
         <th width="12%"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_EMAIL', 'a.email', $this->lists['order_Dir'], $this->lists['order']); ?></th>
         <th width="12%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_COURSE', 'j.title', $this->lists['order_Dir'], $this->lists['order']); ?></th>
         <th width="12%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_COURSE_CODE', 'j.code', $this->lists['order_Dir'], $this->lists['order']); ?></th>
         <th width="5%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'COM_SEMINARMAN_ATTENDEES', 'a.attendees', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
         <th width="5%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'COM_SEMINARMAN_STATUS', 'a.status', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
<?php if ($params->get('invoice_generate') == 1): ?>
         <th width="10%" nowrap="nowrap"><?php echo JText::_('COM_SEMINARMAN_INVOICE'); ?></th>
<?php endif; ?>
         <th width="5%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'JPUBLISHED', 'a.published', $this->lists['order_Dir'], $this->lists['order']); ?></th>
         <th width="5%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_DATE', 'a.date', $this->lists['order_Dir'], $this->lists['order']); ?></th>
         <th width="8%" nowrap="nowrap"><span style="float:left"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_ORDER', 'a.ordering', $this->lists['order_Dir'], $this->lists['order']); ?></span><?php echo JHTML::_('grid.order', $this->applications); ?></th>
         <th width="1%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_ID', 'a.id', $this->lists['order_Dir'], $this->lists['order']); ?></th>
      </tr>
   </thead>
   <tfoot>
      <tr>
         <td colspan="<?php echo ($params->get('invoice_generate') == 1) ? 13 : 12; ?>"><?php echo $this->pagination->getListFooter(); ?></td>
      </tr>
   </tfoot>
   <tbody>
   <?php

$k = 0;
for ($i = 0, $n = count($this->applications); $i < $n; $i++)
{
    $row = &$this->applications[$i];
    $ordering = ($this->lists['order'] == 'a.ordering');

    switch ($row->status) {
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
    }
    
    if ((!empty($row->invoice_filename_prefix)) && ($row->price_per_attendee > 0))
    {
    	$invoiceLink = '<a href="'. JRoute::_('index.php?option=com_seminarman&view=application&layout=invoicepdf&cid[]='. $row->id ) .'">'.
    		'<img style="vertical-align: middle;" alt="'.JText::_('COM_SEMINARMAN_INVOICE').' '.$row->invoice_number.'" src="../components/com_seminarman/assets/images/mime-icon-16/pdf.png" >'.
    		' '.$row->invoice_number.'</a>';
    }
    else
    	$invoiceLink = '-';
    
?>
      <tr class="<?php echo "row$k"; ?>">
         <td><?php echo $this->pagination->getRowOffset($i); ?></td>
         <td><?php echo JHTML::_('grid.checkedout', $row, $i); ?></td>
         <td>
<?php
	$result = 0;
	if ($row instanceof JTable)
	{
		$result = $row->isCheckedOut( $this->user->get( 'id' ) );
	}
	
	if ( $result )
		echo $this->escape($row->title);
	else {
?>
            <span class="editlinktip hasTip" title="<?php echo JText::_('COM_SEMINARMAN_EDIT_APPLICATION'); ?>::<?php echo $this->escape($row->salutation) . " " . $this->escape($row->first_name) . " " . $this->escape($row->last_name); ?>">
               <a href="<?php echo JRoute::_('index.php?option=com_seminarman&controller=application&task=edit&cid[]='. $row->id); ?>"><?php echo $this->escape($row->last_name); ?></a>
            </span>
<?php    } ?>
         </td>
         <td class="centered"><?php echo $row->first_name; ?></td>
         <td class="centered"><?php echo ('<a style="font-size: 1em;" href="mailto:'.$row->email.'">'.$row->email.'</a>');?></td>
         <td class="centered"><span class="editlinktip hasTip" title="<?php echo JText::_('COM_SEMINARMAN_VIEW_COURSE_DETAILS'); ?>::<?php

    echo $this->escape($row->title).'<br />';
    echo JText::_('COM_SEMINARMAN_START_DATE') .  ': '.$this->escape($row->start_date).'<br />';
    echo JText::_('COM_SEMINARMAN_FINISH_DATE') . ': '.$this->escape($row->finish_date).'<br />';

?>">
               <a href="<?php echo JRoute::_('index.php?option=com_seminarman&controller=courses&task=edit&cid[]='. $row->courseid); ?>"><?php echo $this->escape($row->title); ?></a></span>
         </td>
         <td class="centered"><?php echo $row->code; ?></td>
         <td class="centered"><?php echo $row->attendees; ?></td>
         <td class="centered">
         <span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_SEMINARMAN_CHANGE_STATUS' );?>::<?php echo JText::_( 'COM_SEMINARMAN_CHANGE_STATUS_DESC' ) ?>">
            <a href="<?php echo JRoute::_( 'index.php?option=com_seminarman&controller=application&task=changestatus&status='. $row->status .'&cid='. $row->id .'&'.JSession::getFormToken() .'=1' ); ?>"><?php echo $status_text; ?></a>
         </span>
         </td>
<?php if ($params->get('invoice_generate') == 1): ?>
         <td class="centered"><?php echo $invoiceLink; ?></td>
<?php endif; ?>
         <td class="centered"><?php echo JHTML::_('jgrid.published', $row->published, $i); ?></td>
         <td class="centered"><?php echo JHTML::date($row->date, JText::_('COM_SEMINARMAN_DATETIME_FORMAT1')); ?></td>
         <td class="order">
          	<span><?php echo $this->pagination->orderUpIcon($i, (true), 'orderup', 'Move Up', $ordering); ?></span>
          	<span><?php echo $this->pagination->orderDownIcon($i, $n, (true), 'orderdown', 'Move Down', $ordering); ?></span>
            <?php $disabled = $ordering ? '' : 'disabled="disabled"'; ?>
            <input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" <?php echo $disabled; ?> class="text_area centered" />
         </td>
         <td class="centered"><?php echo $row->id; ?>
         </td>
      </tr>
<?php
    $k = 1 - $k;
}
?>
   </tbody>
   </table>
</div>

   <input type="hidden" name="option" value="com_seminarman" />
   <input type="hidden" name="task" value="" />
   <input type="hidden" name="controller" value="application" />
   <input type="hidden" name="view" value="applications" />
   <input type="hidden" name="boxchecked" value="0" />
   <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
   <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
   <?php echo JHTML::_('form.token'); ?>
</form>