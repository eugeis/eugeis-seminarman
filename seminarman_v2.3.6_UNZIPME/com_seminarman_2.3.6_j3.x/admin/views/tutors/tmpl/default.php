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

?>

<form action="<?php echo $this->requestURL; ?>" method="post" name="adminForm" id="adminForm">
	<table class="adminform">
		<tr>
			<td class="proc100 left">
			  	<?php echo JText::_('Filter'); ?>:
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search']; ?>" class="text_area" onChange="document.adminForm.submit();" />
				<input type="button" onclick="this.form.submit();" value="<?php echo JText::_('COM_SEMINARMAN_GO');?>" />
				<input type="button" onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='';this.form.submit();" value="<?php echo JText::_('COM_SEMINARMAN_RESET'); ?>" />
			</td>
			<td nowrap="nowrap">
				<?php echo $this->lists['state']; ?>
			</td>
		</tr>
	</table>
<div id="editcell">
   <table class="adminlist">
   <thead>
      <tr>
         <th width="5">
            <?php

echo JText::_('COM_SEMINARMAN_NUM');

?>
         </th>
         <th width="20">
            <?php echo JHtml::_('grid.checkall'); ?>
         </th>
         <th class="title">
            <?php

echo JHTML::_('grid.sort', 'COM_SEMINARMAN_DISPLAY_NAME', 'a.title', $this->lists['order_Dir'], $this->
    lists['order']);

?>
         </th>
         <th width="10%" nowrap="nowrap">
            <?php

echo JHTML::_('grid.sort', 'COM_SEMINARMAN_CITY', 'a.city', $this->lists['order_Dir'], $this->
    lists['order']);

?>
         </th>
         <th width="5%" nowrap="nowrap">
            <?php

echo JHTML::_('grid.sort', 'COM_SEMINARMAN_COUNTRY', 'cc.code', $this->lists['order_Dir'],
    $this->lists['order']);

?>
         </th>
         <th width="10%" nowrap="nowrap">
            <?php

echo JHTML::_('grid.sort', 'COM_SEMINARMAN_PRIMARY_PHONE', 'a.primary_phone', $this->lists['order_Dir'],
    $this->lists['order']);

?>
         </th>
         <th width="5%" nowrap="nowrap">
<?php echo JText::_('JPUBLISHED'); ?>
         </th>
         <th width="10%" nowrap="nowrap">
            <span style="float:left"><?php

echo JHTML::_('grid.sort', 'COM_SEMINARMAN_ORDER', 'a.ordering', $this->lists['order_Dir'], $this->
    lists['order']);

?></span>
            <?php

echo JHTML::_('grid.order', $this->courses);

?>
         </th>
         <th width="1%" nowrap="nowrap">
            <?php

echo JHTML::_('grid.sort', 'COM_SEMINARMAN_ID', 'a.id', $this->lists['order_Dir'], $this->
    lists['order']);

?>
         </th>
      </tr>
   </thead>
   <tfoot>
      <tr>
         <td colspan="9">
            <?php

echo $this->pagination->getListFooter();

?>
         </td>
      </tr>
   </tfoot>
   <tbody>
   <?php

$k = 0;
for ($i = 0, $n = count($this->courses); $i < $n; $i++)
{
    $row = &$this->courses[$i];

    $link = JRoute::_('index.php?option=com_seminarman&controller=tutor&task=edit&cid[]=' .
        $row->id);

    $checked = JHTML::_('grid.checkedout', $row, $i);
    $published = JHTML::_('jgrid.published', $row->published, $i);

    $ordering = ($this->lists['order'] == 'a.ordering');

?>
      <tr class="<?php

    echo "row$k";

?>">
         <td>
            <?php

    echo $this->pagination->getRowOffset($i);

?>
         </td>
         <td>
            <?php

    echo $checked;

?>
         </td>
         <td>
            <?php

    $result = 0;
	if ($row instanceof JTable)
    {
    	$result = $row->isCheckedOut( $this->user->get( 'id' ) );
	}
            
            
    if ( $result )
    {
        echo $this->escape($row->title);
    } 
    else
    {

?>
            <span class="editlinktip hasTip" title="<?php

        echo JText::_('COM_SEMINARMAN_EDIT');

?>::<?php

        echo $this->escape($row->title);

?>">
               <a href="<?php

        echo $link;

?>">
                  <?php

        echo $this->escape($row->title);

?></a></span>
            <?php

    }

?>
         </td>
         <td align="center">
            <?php

    echo $row->city;

?>
         </td>
         <td align="center">
            <?php

    echo $row->country_code;

?>
         </td>
         <td align="center">
            <?php

    echo $row->primary_phone;

?>
         </td>
         <td align="center">
            <?php

    echo $published;

?>
         </td>
         <td class="order">
            <span><?php

    echo $this->pagination->orderUpIcon($i, (true), 'orderup', 'Move Up', $ordering);

?></span>
            <span><?php

    echo $this->pagination->orderDownIcon($i, $n, (true), 'orderdown', 'Move Down',
        $ordering);

?></span>
            <?php

    $disabled = $ordering ? '' : 'disabled="disabled"';

?>
            <input type="text" name="order[]" size="5" value="<?php

    echo $row->ordering;

?>" <?php

    echo $disabled

?> class="text_area" style="text-align: center" />
         </td>

         <td align="center">
            <?php

    echo $row->id;

?>
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
   <input type="hidden" name="controller" value="tutor" />
   <input type="hidden" name="view" value="tutors" />
   <input type="hidden" name="boxchecked" value="0" />
   <input type="hidden" name="filter_order" value="<?php

echo $this->lists['order'];

?>" />
   <input type="hidden" name="filter_order_Dir" value="<?php

echo $this->lists['order_Dir'];

?>" />
   <?php

echo JHTML::_('form.token');

?>
</form>