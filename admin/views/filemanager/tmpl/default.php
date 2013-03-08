<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');

?>

<table width="100%" border="0" style="padding: 5px; margin-bottom: 10px;">
	<tr>
		<td>
		    <?php

if ($this->require_ftp):

?>
            <form action="index.php?option=com_seminarman&amp;controller=filemanager&amp;task=ftpValidate" name="ftpForm" id="ftpForm" method="post">
                <fieldset title="<?php

    echo JText::_('COM_SEMINARMAN_FTP_TITLE');

?>">
                    <legend><?php

    echo JText::_('COM_SEMINARMAN_FTP_TITLE');

?></legend>
                    <?php

    echo JText::_('COM_SEMINARMAN_FTP_DESC');

?>
                    <table class="adminform nospace">
                        <tbody>
                            <tr>
                                <td width="120">
                                    <label for="username"><?php

    echo JText::_('COM_SEMINARMAN_USERNAME');

?>:</label>
                                </td>
                                <td>
                                    <input type="text" id="username" name="username" class="input_box" size="70" value="" />
                                </td>
                            </tr>
                            <tr>
                                <td width="120">
                                    <label for="password"><?php

    echo JText::_('COM_SEMINARMAN_PASSWORD');

?>:</label>
                                </td>
                                <td>
                                    <input type="password" id="password" name="password" class="input_box" size="70" value="" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
            </form>
            <?php

endif;

?>

			<!-- File Upload Form -->
            <form action="<?php

echo JURI::base();

?>index.php?option=com_seminarman&amp;controller=filemanager&amp;task=upload&amp;<?php

echo $this->session->getName() . '=' . $this->session->getId();

?>&amp;<?php

echo JUtility::getToken();

?>=1" id="uploadForm" method="post" enctype="multipart/form-data">
                <fieldset>
                    <legend><?php

echo JText::_('COM_SEMINARMAN_UPLOAD');

?> [ <?php

echo JText::_('COM_SEMINARMAN_MAX');

?>&nbsp;<?php

echo ($this->params->get('upload_maxsize') / 1000000);

?>M ]</legend>
                    <fieldset class="actions">
                        <input type="file" id="file-upload2" name="Filedata" />
                        <input type="submit" id="file-upload-submit" value="<?php

echo JText::_('COM_SEMINARMAN_UPLOAD');

?>"/>
                        <span id="upload-clear"></span>
                    </fieldset>
                    <ul class="upload-queue" id="upload-queue">
                        <li style="display: none" />
                    </ul>
                </fieldset>
                <input type="hidden" name="return-url" value="<?php

echo base64_encode('index.php?option=com_seminarman&view=filemanager');

?>" />
            </form>
		</td>
	</tr>
</table>

<form action="index.php" method="post" name="adminForm">

	<table class="adminform">
		<tr>
			<td width="100%">
			  	<?php

echo JText::_('COM_SEMINARMAN_SEARCH');

?>
			  	<?php

echo $this->lists['filter'];

?>
				<input type="text" name="search" id="search" value="<?php

echo $this->lists['search'];

?>" class="text_area" onChange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php

echo JText::_('COM_SEMINARMAN_GO');

?></button>
				<button onclick="this.form.getElementById('search').value='';this.form.submit();"><?php

echo JText::_('COM_SEMINARMAN_RESET');

?></button>
			</td>
			<td nowrap="nowrap">
			 	<?php

echo $this->lists['assigned'];

?>
			</td>
		</tr>
	</table>

	<table class="adminlist" cellspacing="1">
	<thead>
		<tr>
			<th width="5"><?php

echo JText::_('COM_SEMINARMAN_NUM');

?></th>
			<th width="5"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php

echo count($this->rows);

?>);" /></th>
			<th class="title"><?php

echo JHTML::_('grid.sort', 'COM_SEMINARMAN_FILENAME', 'f.filename', $this->lists['order_Dir'],
    $this->lists['order']);

?></th>
			<th width="20%"><?php

echo JHTML::_('grid.sort', 'COM_SEMINARMAN_DISPLAY_NAME', 'f.altname', $this->lists['order_Dir'],
    $this->lists['order']);

?></th>
			<th width="7%"><?php

echo JText::_('COM_SEMINARMAN_SIZE');

?></th>
			<th width="15"><?php

echo JHTML::_('grid.sort', 'COM_SEMINARMAN_HITS', 'f.hits', $this->lists['order_Dir'], $this->
    lists['order']);

?></th>
			<th width="60"><?php

echo JHTML::_('grid.sort', 'COM_SEMINARMAN_ASSIGNED_TO', 'nrassigned', $this->lists['order_Dir'],
    $this->lists['order']);

?></th>
			<th width="10%"><?php

echo JHTML::_('grid.sort', 'COM_SEMINARMAN_UPLOADER', 'uploader', $this->lists['order_Dir'], $this->
    lists['order']);

?></th>
			<th width="10%"><?php

echo JHTML::_('grid.sort', 'COM_SEMINARMAN_UPLOAD_TIME', 'f.uploaded', $this->lists['order_Dir'],
    $this->lists['order']);

?></th>
			<th width="1%" nowrap="nowrap"><?php

echo JHTML::_('grid.sort', 'COM_SEMINARMAN_ID', 'f.id', $this->lists['order_Dir'], $this->
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
$i = 0;
$n = count($this->rows);
foreach ($this->rows as $row)
{
    $checked = JHTML::_('grid.checkedout', $row, $i);

?>
		<tr class="<?php

    echo "row$k";

?>">
			<td><?php

    echo $this->pageNav->getRowOffset($i);

?></td>
			<td width="7">
   				<?php

    echo $checked;

?>
   			</td>
			<td align="<?php

    echo $this->direction ? 'left' : 'right';

?>">
				<?php

    echo JHTML::image($row->icon, '') .
        ' <a href="index.php?option=com_seminarman&amp;controller=filemanager&amp;task=edit&amp;cid[]=' .
        $row->id . '">' . htmlspecialchars($row->filename, ENT_QUOTES, 'UTF-8') . '</a>';

?>
			</td>
			<td>
				<?php

    if (JString::strlen($row->altname) > 25)
    {
        echo JString::substr(htmlspecialchars($row->altname, ENT_QUOTES, 'UTF-8'), 0, 25) .
            '...';
    } else
    {
        echo htmlspecialchars($row->altname, ENT_QUOTES, 'UTF-8');
    }

?>
			</td>
			<td align="center"><?php

    echo $row->size;

?></td>
			<td align="center"><?php

    echo $row->hits;

?></td>
			<td align="center"><?php

    echo $row->nrassigned;

?></td>
			<td align="center">
				<a href="<?php

    echo 'index.php?option=com_users&amp;task=edit&amp;hidemainmenu=1&amp;cid[]=' .
        $row->uploaded_by;

?>">
					<?php

    echo $row->uploader;

?>
				</a>
			</td>
			<td align="center"><?php

    echo JHTML::Date($row->uploaded, JText::_('COM_SEMINARMAN_DATE_FORMAT1'));
    ;

?></td>
			<td align="center"><?php

    echo $row->id;

?></td>
		</tr>
		<?php

    $k = 1 - $k;
    $i++;
}

?>
	</tbody>

	</table>

	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_seminarman" />
	<input type="hidden" name="controller" value="filemanager" />
	<input type="hidden" name="view" value="filemanager" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_order" value="<?php

echo $this->lists['order'];

?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<?php

echo JHTML::_('form.token');

?>
</form>