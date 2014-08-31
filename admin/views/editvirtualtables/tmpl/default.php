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

		<?php

if ($this->ftp):

?>
				<fieldset class="adminform">
					<legend><?php

    echo JText::_('COM_SEMINARMAN_FTP_TITLE');

?></legend>

					<?php

    echo JText::_('COM_SEMINARMAN_FTP_DESC');

?>
					
					<?php

    if (JError::isError($this->ftp)):

?>
						<p><?php

        echo JText::_($this->ftp->message);

?></p>
					<?php

    endif;

?>

					<table class="adminform nospace">
						<tbody>
							<tr>
								<td class="pix120">
									<label for="username"><?php

    echo JText::_('COM_SEMINARMAN_USERNAME');

?>:</label>
								</td>
								<td>
									<input type="text" id="username" name="username" class="input_box" size="70" value="" />
								</td>
							</tr>
							<tr>
								<td class="pix120">
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
		<?php

endif;

?>

		<table class="adminform">
		<tr>
			<th>
				<?php

echo $this->xml_path;

?>
			</th>
		</tr>
		<tr>
			<td>
				<textarea cols="110" rows="25" name="filecontent" class="inputbox editcss_textarea"><?php

echo $this->content;

?></textarea>
			</td>
		</tr>
		</table>

		<?php

echo JHTML::_('form.token');

?>
		<input type="hidden" name="filename" value="<?php

echo $this->filename;

?>" />
		<input type="hidden" name="option" value="com_seminarman" />
        <input type="hidden" name="controller" value="settings" />
		<input type="hidden" name="task" value="" />
</form>
		
<?php


JHTML::_('behavior.keepalive');

?>