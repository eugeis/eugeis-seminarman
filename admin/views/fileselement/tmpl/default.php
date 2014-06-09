<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');

?>

<form action="index.php?option=com_seminarman&amp;view=fileselement&amp;tmpl=component" method="post" name="adminForm" id="adminForm">

	<table class="adminform">
		<tr>
			<td class="proc100">
			  	<?php echo JText::_('COM_SEMINARMAN_SEARCH'); ?>
			  	<?php echo $this->lists['filter']; ?>
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search']; ?>" class="text_area" onChange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_('COM_SEMINARMAN_GO'); ?></button>
				<button onclick="this.form.getElementById('search').value='';this.form.submit();"><?php echo JText::_('COM_SEMINARMAN_RESET'); ?></button>
			</td>
		</tr>
	</table>

	<table class="adminlist cellspace1">
	<thead>
		<tr>
			<th class="pix5"><?php echo JText::_('COM_SEMINARMAN_NUM'); ?></th>
			<th class="title"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_FILENAME', 'f.filename', $this->lists['order_Dir'], $this->lists['order']); ?></th>
			<th class="proc20"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_DISPLAY_NAME', 'f.altname', $this->lists['order_Dir'], $this->lists['order']); ?></th>
			<th class="proc7"><?php echo JText::_('COM_SEMINARMAN_SIZE'); ?></th>
			<th class="pix15"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_HITS', 'f.hits', $this->lists['order_Dir'], $this->lists['order']); ?></th>
			<th width="60"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_ASSIGNED_TO', 'nrassigned', $this->lists['order_Dir'], $this->lists['order']); ?></th>
			<th class="proc10"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_UPLOADER', 'uploader', $this->lists['order_Dir'], $this->lists['order']); ?></th>
			<th class="proc10"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_UPLOAD_TIME', 'f.uploaded', $this->lists['order_Dir'], $this->lists['order']); ?></th>
			<th class="proc1" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_ID', 'f.id', $this->lists['order_Dir'], $this->lists['order']); ?></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<td colspan="10"><?php echo $this->pageNav->getListFooter(); ?></td>
		</tr>
	</tfoot>

	<tbody>
		<?php

$index = JRequest::getInt('index', 0);
$k = 0;
$i = 0;
$n = count($this->rows);
foreach ($this->rows as $row)
{

?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $this->pageNav->getRowOffset($i); ?></td>
			<td class="<?php echo $this->direction ? 'left' : 'right'; ?>">
				<span class="editlinktip hasTip" title="<?php echo JText::_('COM_SEMINARMAN_SELECT'); ?>::<?php echo $row->filename; ?>">
				<a style="cursor:pointer" onclick="qffileselementadd('<?php echo $row->id; ?>', '<?php echo str_replace(array("'", "\""), array("\\'", ""), $row->filename); ?>');">
					<?php echo JHTML::image($row->icon, '') . ' ' . htmlspecialchars($row->filename, ENT_QUOTES, 'UTF-8'); ?>
				</a>
				</span>
			</td>
			<td>
				<?php
    if (JString::strlen($row->altname) > 25)
        echo JString::substr(htmlspecialchars($row->altname, ENT_QUOTES, 'UTF-8'), 0, 25) . '...';
    else
        echo htmlspecialchars($row->altname, ENT_QUOTES, 'UTF-8');
?>
			</td>
			<td class="centered"><?php echo $row->size; ?></td>
			<td class="centered"><?php echo $row->hits; ?></td>
			<td class="centered"><?php echo $row->nrassigned; ?></td>
			<td class="centered"><?php echo $row->uploader; ?></td>
			<td class="centered"><?php echo JHTML::Date($row->uploaded, JText::_('COM_SEMINARMAN_DATE_FORMAT1'));  ?></td>
			<td class="centered"><?php echo $row->id; ?></td>
		</tr>
<?php
    $k = 1 - $k;
    $i++;
}
?>
	</tbody>

	</table>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="file" value="" />
	<input type="hidden" name="files" value="<?php echo $this->files; ?>" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />	
</form>
