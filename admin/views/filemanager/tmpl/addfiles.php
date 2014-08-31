<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');

?>
<table class="no_space_table">
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

// echo JUtility::getToken();
echo JSession::getFormToken();

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
                    	<?php

echo JText::_('COM_SEMINARMAN_DISPLAY_NAME') . ': ';

?><input type="text" id="file-upload-name" name="altname" />
                    	<br /><br />
                        <input type="file" id="file-upload" name="Filedata" />                        
                        <input type="submit" id="file-upload-submit" value="<?php

echo JText::_('COM_SEMINARMAN_UPLOAD');

?>"/>
                        <span id="upload-clear"></span>
                    </fieldset>
                    <ul class="upload-queue" id="upload-queue">
                        <li class="nothing" />
                    </ul>
                </fieldset>
                <input type="hidden" name="return-url" value="<?php

echo base64_encode('index.php?option=com_seminarman&view=filemanager&layout=addfiles&tmpl=component');

?>" />
            </form>
		</td>
	</tr>
</table>