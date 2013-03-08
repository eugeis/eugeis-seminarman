<?php
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');
?>


<form action="<?php echo JURI::base();?>index.php?option=com_seminarman" method="post" name="adminForm">
<table class="adminlist" cellspacing="1">
	<thead>
		<tr class="title">
			<th width="1%">
				<?php echo JText::_('COM_SEMINARMAN_NUM'); ?>
			</th>
			<th width="1%">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->fields ); ?>);" />
			</th>
			<th>
				<?php echo JText::_('COM_SEMINARMAN_NAME'); ?>
			</th>
			<th>
				<?php echo JText::_('COM_SEMINARMAN_USE_FOR'); ?>
			</th>
			<th width="10%">
				<?php echo JText::_('COM_SEMINARMAN_FIELD_CODE'); ?>
			</th>
			<th width="10%">
				<?php echo JText::_('COM_SEMINARMAN_PAYPAL_CODE'); ?>
			</th>
			<th align="center" width="10%">
				<?php echo JText::_('COM_SEMINARMAN_TYPE'); ?>
			</th>
			<th width="1%">
				<?php echo JText::_('JPUBLISHED'); ?>
			</th>
			<th width="7%" align="center">
				<?php echo JText::_('COM_SEMINARMAN_ORDER'); ?>
			</th>
			<th width="2%" align="center">
				<?php echo JText::_('COM_SEMINARMAN_ID'); ?>
			</th>
		</tr>
	</thead>
<?php
	$count	= 0;
	$i		= 0;

	foreach($this->fields as $field)
	{
		$input	= JHTML::_('grid.id', $count, $field->id);

		if($field->type == 'group')
		{
?>
		<tr>
			<td  style="background-color: #EEEEEE;">&nbsp;</td>
			<td  style="background-color: #EEEEEE;">
				<?php echo $input; ?>
			</td>
			<td style="background-color: #EEEEEE;">
				<strong><?php echo JText::_('COM_SEMINARMAN_GROUP');?>
					<span id="name<?php echo $field->id; ?>">
					<?php echo JHTML::_('link','index.php?option=com_seminarman&view=editfield&cid='. $field->id . '&layout=editgroup',' - '.$field->name); ?>
					</span>
				</strong>
				<div style="clear: both;"></div>
			</td>
			<td style="background-color: #EEEEEE;"><?php echo $field->purpose == 1 ? JText::_('COM_SEMINARMAN_LST_OF_SALES_PROSPECTS') : JText::_('COM_SEMINARMAN_BOOKINGS'); ?></td>
			<td colspan="3" style="background-color: #EEEEEE;"></td>
			<td align="center" id="published<?php echo $field->id;?>" style="background-color: #EEEEEE;">
				<?php echo $published = JHTML::_('grid.published', $field, $i); ?>
			</td>
			<td align="right" style="background-color: #EEEEEE;" class="order">
				<span><?php echo $this->pagination->orderUpIcon( $count, true, 'orderup', 'Move Up'); ?></span>
				<span><?php echo $this->pagination->orderDownIcon( $count, count($this->fields) , true , 'orderdown', 'Move Down', true ); ?></span>
			</td>
			<td align="right">
			<?php echo $field->id;?>
			</td>
		</tr>
<?php
			$i	= 0;	// Reset count
		}
		else if($field->type != 'group')
		{

			// Process publish / unpublish images
			++$i;
?>
		<tr class="row<?php echo $i%2;?>" id="rowid<?php echo $field->id;?>">
			<td><?php echo $i;?></td>
			<td>
				<?php echo $input; ?>
			</td>
			<td>
				<span class="editlinktip hasTip" title="<?php echo $field->name; ?>:: <?php echo $field->tips; ?>" id="name<?php echo $field->id;?>">
				<?php echo JHTML::_('link','index.php?option=com_seminarman&view=editfield&cid='. $field->id . '',$field->name); ?>
				</span>
			</td>
			<td></td>
			<td align="center">
				<?php echo $field->fieldcode; ?>
			</td>
			<td align="center">
				<?php echo $field->paypalcode; ?>
			</td>
			<td align="center">
				<span id="type<?php echo $field->id;?>" onclick="$('typeOption').style.display = 'block';$(this).style.display = 'none';">
				<?php echo $this->getFieldText( $field->type ); ?>
				</span>
			</td>
			<td align="center" id="published<?php echo $field->id;?>">
            <?php echo $published = JHTML::_('grid.published', $field, $i); ?>
			</td>
			<td align="right" class="order">
				<span><?php echo $this->pagination->orderUpIcon( $count , true, 'orderup', 'Move Up'); ?></span>
				<span><?php echo $this->pagination->orderDownIcon( $count , count($this->fields), true , 'orderdown', 'Move Down', true ); ?></span>
			</td>
			<td align="right">
			<?php echo $field->id;?>
			</td>
		</tr>
<?php
		}
		$count++;
	}
?>
	<tfoot>
	<tr>
		<td colspan="15">
			<?php echo $this->pagination->getListFooter(); ?>
		</td>
	</tr>
	</tfoot>
</table>
<input type="hidden" name="view" value="editfields" />
<input type="hidden" name="controller" value="editfields" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="option" value="com_seminarman" />
<input type="hidden" name="boxchecked" value="0" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>