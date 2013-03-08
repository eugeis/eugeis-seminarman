<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');

?>
<?php

$config = &JFactory::getConfig();
$now = &JFactory::getDate();
$db = &JFactory::getDBO();
$user = &JFactory::getUser();


?>

<div class="qickseminarman">
<form action="index.php" method="post" name="adminForm">

<table class="adminform">
<tr>
   <td class="proc100 left">
      <?php echo JText::_('Filter'); ?>:
      <input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
      <input type="button" onclick="this.form.submit();" value="<?php echo JText::_('COM_SEMINARMAN_GO');?>" />
      <input type="button" onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='';this.form.getElementById('filter_category').value='0';this.form.submit();" value="<?php echo JText::_('COM_SEMINARMAN_RESET'); ?>" />
   </td>
   <td nowrap="nowrap">
      <?php echo $this->lists['category']; echo $this->lists['state']; ?>
   </td>
</tr>
</table>


	<table class="adminlist cellspace1">
	<thead>
		<tr>
			<th width="5"><?php

echo JText::_('COM_SEMINARMAN_NUM');

?></th>
			<th width="5"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php

echo count($this->rows);

?>);" /></th>
			<th width="20%"><?php

echo JHTML::_('grid.sort', 'COM_SEMINARMAN_TITLE', 'i.title', $this->lists['order_Dir'], $this->
    lists['order']);

?></th>
			
<th width="10"><?php

echo JText::_('COM_SEMINARMAN_COURSE_CODE');

?></th>
			<th width="17%"><?php

echo JText::_('COM_SEMINARMAN_CATEGORY');

?></th>
			<th width="10%" nowrap="nowrap"><?php

echo JHTML::_('grid.sort', JText::_('COM_SEMINARMAN_START_DATE'), 'i.start_date', $this->lists['order_Dir'], $this->
    lists['order']);

			?></th>
			<th width="10%" nowrap="nowrap"><?php

echo JHTML::_('grid.sort', JText::_('COM_SEMINARMAN_FINISH_DATE'), 'i.finish_date', $this->lists['order_Dir'], $this->
    lists['order']);

						?></th>
			<th width="10"><?php

			echo JText::_('COM_SEMINARMAN_SESSIONS');

			?></th>
			<th width="5" nowrap="nowrap"><?php echo JText::_('JPUBLISHED'); ?></th>
			<th width="5" nowrap="nowrap"><?php echo JText::_('COM_SEMINARMAN_NEW'); ?></th>
			<th width="5" nowrap="nowrap"><?php echo JText::_('COM_SEMINARMAN_COURSE_CANCELED'); ?></th>
			<th width="5" nowrap="nowrap"><?php echo JText::_('COM_SEMINARMAN_BOOKINGS'); ?></th>
			<th width="5" nowrap="nowrap"><?php

echo JHTML::_('grid.sort', 'COM_SEMINARMAN_HITS', 'i.hits', $this->lists['order_Dir'], $this->
    lists['order']);

?></th>
			<th width="8%"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_ORDER', 'i.ordering', $this->lists['order_Dir'], $this->lists['order']); ?></th>
			<th width="1%"><?php echo JHTML::_('grid.order', $this->rows, 'filesave.png', 'saveorder'); ?></th>
			<th width="3" nowrap="nowrap"><?php

echo JHTML::_('grid.sort', 'COM_SEMINARMAN_ID', 'i.id', $this->lists['order_Dir'], $this->
    lists['order']);

?></th>
		</tr>
	</thead>

	<tfoot>
		<tr><td colspan="16"><?php echo $this->pageNav->getListFooter(); ?></td></tr>
	</tfoot>

	<tbody>
		<?php

$k = 0;
$nullDate = $db->getNullDate();
for ($i = 0, $n = count($this->rows); $i < $n; $i++)
{
    $row = $this->rows[$i];

    $link = 'index.php?option=com_seminarman&amp;controller=courses&amp;task=edit&amp;cid[]=' .
        $row->id;
    $checked = JHTML::_('grid.checkedout', $row, $i);

    $publish_up = &JFactory::getDate($row->publish_up);
    $publish_down = &JFactory::getDate($row->publish_down);
    $publish_up->setOffset($config->getValue('config.offset'));
    $publish_down->setOffset($config->getValue('config.offset'));

    $times = '';
    if (isset($row->publish_up))
    {
        if ($row->publish_up == $nullDate)
            $times .= JText::_('COM_SEMINARMAN_START_ALWAYS');
        else
            $times .= JText::_('COM_SEMINARMAN_START') . ": " . $publish_up->toFormat($format=JText::_('COM_SEMINARMAN_DATE_FORMAT1_ALT'));
    }
    if (isset($row->publish_down))
    {
        if ($row->publish_down == $nullDate)
            $times .= "<br />" . JText::_('COM_SEMINARMAN_FIISH_NO_EXPIRY');
        else
            $times .= "<br />" . JText::_('COM_SEMINARMAN_FINISH') . ": " . $publish_down->toFormat($format=JText::_('COM_SEMINARMAN_DATE_FORMAT1_ALT'));
    }

?>
<tr class="<?php echo "row$k"; ?>">
	<td><?php echo $this->pageNav->getRowOffset($i); ?></td>
	<td width="7"><?php echo $checked; ?></td>
	<td align="<?php echo $this->direction ? 'left' : 'right'; ?>">
		<?php
		if ($row->checked_out && ($row->checked_out != $this->user->get('id')))
			echo htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8');
		else { ?>
			<span class="editlinktip hasTip" title="<?php echo JText::_('COM_SEMINARMAN_EDIT_COURSE') . '::' . $row->title; ?>">
				<a href="<?php echo $link; ?>"><?php echo htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8'); ?></a>
			</span>
	        <p class="smallsub">(<span><?php echo JText::_('COM_SEMINARMAN_ALIAS'); ?></span>:<?php
	        	if (JString::strlen($row->alias) > 25)
	        		echo JString::substr(htmlspecialchars($row->alias, ENT_QUOTES, 'UTF-8'), 0, 25) . '...';
	        	else
	        		echo htmlspecialchars($row->alias, ENT_QUOTES, 'UTF-8'); ?>)
	        </p><?php
		} ?>
	</td>
	<td><?php echo htmlspecialchars($row->code, ENT_QUOTES, 'UTF-8');?></td>
	<td>
		<?php
	    $nr = count($row->categories);
	    $ix = 0;
	    foreach ($row->categories as $key => $category):
	        if(JHTMLSeminarman::UserIsCourseManager()){
	        $catlink = 'index.php?option=com_seminarman&amp;controller=categories&amp;task=edit&amp;cid[]=' . $category->id;
	        }else{
	        $catlink = '#';	
	        }
	        $title = htmlspecialchars($category->title, ENT_QUOTES, 'UTF-8');
		?>
		<span class="editlinktip hasTip" title="<?php echo JText::_('COM_SEMINARMAN_EDIT_CATEGORY'); ?>::<?php echo $title; ?>">
			<a href="<?php echo $catlink; ?>">
			<?php
	        if (JString::strlen($title) > 20)
	            echo JString::substr($title, 0, 20) . '...';
	        else
	        	echo $title;
	        ?>
	        </a>
	    </span>
	    <?php
	        $ix++;
	        if ($ix != $nr):
	            echo ', ';
	        endif;
	    endforeach;
	    ?>
	</td>
	<td align="center"><?php echo $row->start_date; ?></td>
	<td align="center"><?php echo $row->finish_date; ?></td>
	<td align="center">
		<?php $rownumber = "cb".$i; ?>
		<a href="javascript:void(0);" onclick="return listItemTask('<?php echo $rownumber ?>','showSessions')"><img src="../administrator/components/com_seminarman/assets/images/copy_f2.png" alt="" width="15" border="0" height="15"></a>
		(<?php echo $row->count_sessions;?>)
	</td>
	<td align="center">
		<span class="editlinktip hasTip" title="<?php echo JText::_('COM_SEMINARMAN_PUBLISH_INFORMATION'); ?>::<?php echo $times; ?>">
		<a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $row->state ? 'unpublish' : 'publish'?>')">
		<?php echo JHtml::_('jgrid.published', $row->state, $i, 'seminarman_courses.', '', 'cb', $row->publish_up, $row->publish_down); ?>
		</a>
		</span>
	</td>
	<td align="center">
		<?php
			$states = array(
				0 => array ('task' => 'setNew', 'active_class'=>'unpublish'),
				1 => array ('task' => 'unsetNew', 'active_class'=>'publish'),
			);
			echo JHtml::_('jgrid.state', $states, $row->new, $i);
		?>
	</td>
	<td align="center">
			<?php
			$states = array(
				0 => array ('task' => 'setCanceled', 'active_class'=>'unpublish'),
				1 => array ('task' => 'unsetCanceled', 'active_class'=>'publish'),
			);
			echo JHtml::_('jgrid.state', $states, $row->canceled, $i);
		?>
	</td>
	<td align="center"><?php echo $row->currentBookings . "(" . $row->min_attend . ") " . JText::_( 'COM_SEMINARMAN_OF' ) . " "; echo $row->capacity; ?></td>
	<td align="center"><?php echo $row->hits == 0 ? '-' : $row->hits; ?></td>
	<td class="order" colspan="2">
		<span><?php echo $this->pageNav->orderUpIcon($i, true, 'orderup', 'Move Up', $this->ordering); ?></span>
		<span><?php echo $this->pageNav->orderDownIcon($i, $n, true, 'orderdown', 'Move Down', $this->ordering); ?></span>
		<?php $disabled = $this->ordering ? '' : '"disabled=disabled"'; ?>
		<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" <?php echo $disabled; ?> class="text_area" style="text-align: center" />
	</td>
	<td align="center"><?php echo $row->id; ?></td>
</tr>
		<?php

    $k = 1 - $k;
}

?>
	</tbody>

	</table>

	
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_seminarman" />
	<input type="hidden" name="controller" value="courses" />
	<input type="hidden" name="view" value="courses" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_order" value="<?php

echo $this->lists['order'];

?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<?php

echo JHTML::_('form.token');

?>
</form>
</div>
