<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');

?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
var form = document.adminForm;
	if (task == 'cancel') {
		Joomla.submitform( task );
		return;
	}

	// do field validation
	if (form.name.value == ""){
		alert( "<?php

echo JText::_('COM_SEMINARMAN_MISSING_TITLE');

?>" );
	} else {
		Joomla.submitform( task );
	}
	
	}
</script>



<form action="index.php" method="post" name="adminForm" id="adminForm">

	<table class="no_space_table">
		<tr>
			<td>
				<label for="title">
					<?php

echo JText::_('COM_SEMINARMAN_TITLE') . ':';

?>
				</label>
			</td>
			<td>
				<input name="name" value="<?php

echo $this->row->name;

?>" size="50" maxlength="100" />
			</td>
			<td>
				<label for="published">
					<?php

echo JText::_('JPUBLISHED') . ':';

?>
				</label>
			</td>
			<td>
				<?php

$html = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $this->
    row->published);
echo $html;

?>
			</td>
		</tr>
	</table>

<?php

echo JHTML::_('form.token');

?>
<input type="hidden" name="option" value="com_seminarman" />
<input type="hidden" name="id" value="<?php

echo $this->row->id;

?>" />
<input type="hidden" name="controller" value="tags" />
<input type="hidden" name="view" value="tag" />
<input type="hidden" name="task" value="save" />
</form>

<?php


JHTML::_('behavior.keepalive');

?>