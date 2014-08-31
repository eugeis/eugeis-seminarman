<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');

?>

<form action="index.php" method="post" name="adminForm" id="adminForm">

	<table class="adminform">
		<tr>
			<td class="proc100 left">
			  	<?php echo JText::_('Filter'); ?>:
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search']; ?>" class="text_area" onChange="document.adminForm.submit();" />
				<input type="button" onclick="this.form.submit();" value="<?php echo JText::_('COM_SEMINARMAN_GO');?>" />
				<input type="button" onclick="document.getElementById('search').value='';this.form.getElementById('filter_assigned').value='0';this.form.getElementById('filter_state').value='';this.form.submit();" value="<?php echo JText::_('COM_SEMINARMAN_RESET'); ?>" />
			</td>
			<td nowrap="nowrap">
				<?php echo $this->lists['assigned']; echo $this->lists['state']; ?>
			</td>
		</tr>
	</table>

	<table class="adminlist" cellspacing="1">
	<thead>
		<tr>
			<th width="5"><?php

echo JText::_('COM_SEMINARMAN_NUM');

?></th>
			<th width="5"><?php echo JHtml::_('grid.checkall'); ?></th>
			<th class="title"><?php

echo JHTML::_('grid.sort', 'COM_SEMINARMAN_TITLE', 't.name', $this->lists['order_Dir'], $this->
    lists['order']);

?></th>
			<th width="30%"><?php

echo JHTML::_('grid.sort', 'COM_SEMINARMAN_ALIAS', 't.alias', $this->lists['order_Dir'], $this->
    lists['order']);

?></th>
			<th width="7%"><?php

echo JHTML::_('grid.sort', 'COM_SEMINARMAN_ASSIGNED_TO', 'nrassigned', $this->lists['order_Dir'],
    $this->lists['order']);

?></th>
			<th width="1%" nowrap="nowrap"><?php

echo JText::_('JPUBLISHED');

?></th>
			<th width="1%" nowrap="nowrap"><?php

echo JHTML::_('grid.sort', 'ID', 't.id', $this->lists['order_Dir'], $this->
    lists['order']);

?></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<td colspan="10">
				<?php

echo $this->pageNav->getListFooter();

?>
			</td>
		</tr>
	</tfoot>
	<tbody>
		<?php

$k = 0;
for ($i = 0, $n = count($this->rows); $i < $n; $i++)
{
    $row = $this->rows[$i];

    $link = 'index.php?option=com_seminarman&amp;controller=tags&amp;task=edit&amp;cid[]=' .
        $row->id;
    $published = JHTML::_('jgrid.published', $row->published, $i);
    $checked = JHTML::_('grid.checkedout', $row, $i);

?>
		<tr class="<?php

    echo "row$k";

?>">
			<td><?php

    echo $this->pageNav->getRowOffset($i);

?></td>
			<td width="7"><?php

    echo $checked;

?></td>
			<td align="<?php

    echo $this->direction ? 'left' : 'right';

?>">
				<?php

    if ($row->checked_out && ($row->checked_out != $this->user->get('id')))
    {
        echo htmlspecialchars($row->name, ENT_QUOTES, 'UTF-8');
    } else
    {

?>
					<span class="editlinktip hasTip" title="<?php

        echo JText::_('COM_SEMINARMAN_EDIT');

?>::<?php

        echo $row->name;

?>">
					<a href="<?php

        echo $link;

?>">
					<?php

        echo htmlspecialchars($row->name, ENT_QUOTES, 'UTF-8');

?>
					</a></span>
				<?php

    }

?>
			</td>
			<td>
				<?php

    if (JString::strlen($row->alias) > 25)
    {
        echo JString::substr(htmlspecialchars($row->alias, ENT_QUOTES, 'UTF-8'), 0, 25) .
            '...';
    } else
    {
        echo htmlspecialchars($row->alias, ENT_QUOTES, 'UTF-8');
    }

?>
			</td>
			<td align="center"><?php

    echo $row->nrassigned

?></td>
			<td align="center">
				<?php

    echo $published;

?>
			</td>
			<td align="center"><?php

    echo $row->id;

?></td>
		</tr>
		<?php

    $k = 1 - $k;
}

?>
	</tbody>

	</table>

	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_seminarman" />
	<input type="hidden" name="controller" value="tags" />
	<input type="hidden" name="view" value="tags" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_order" value="<?php

echo $this->lists['order'];

?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<?php

echo JHTML::_('form.token');

?>
</form>