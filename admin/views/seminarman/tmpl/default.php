<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');

?>

	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td class="vtop">
			<div class="adminlist">
            <div class="cpanel-left">
						<div id="cpanel">
						<?php

global $option;

if(JHTMLSeminarman::UserIsCourseManager()){

$link = 'index.php?option=com_seminarman&amp;view=applications';
SeminarmanViewSeminarman::quickiconButton($link, 'icon-48-applications.png', JText::_('COM_SEMINARMAN_APPLICATION'));

$link = 'index.php?option=com_seminarman&amp;view=salesprospects';
SeminarmanViewSeminarman::quickiconButton($link, 'icon-48-inter.png', JText::_('COM_SEMINARMAN_LST_OF_SALES_PROSPECTS'));

$link = 'index.php?option=com_seminarman&amp;view=courses';
SeminarmanViewSeminarman::quickiconButton($link, 'icon-48-courses.png', JText::_('COM_SEMINARMAN_COURSES'));

$link = 'index.php?option=com_seminarman&amp;view=course';
SeminarmanViewSeminarman::quickiconButton($link, 'icon-48-courseedit.png', JText::_('COM_SEMINARMAN_NEW_COURSE'));

$link = 'index.php?option=com_seminarman&amp;view=templates';
SeminarmanViewSeminarman::quickiconButton($link, 'icon-48-templates.png', JText::_('COM_SEMINARMAN_TEMPLATES'));

$link = 'index.php?option=com_seminarman&amp;view=templates';
SeminarmanViewSeminarman::quickiconButton($link, 'icon-48-templateedit.png', JText::_('COM_SEMINARMAN_NEW_TEMPLATE'));

$link = 'index.php?option=com_seminarman&amp;view=tags';
SeminarmanViewSeminarman::quickiconButton($link, 'icon-48-tags.png', JText::_('COM_SEMINARMAN_TAGS'));

$link = 'index.php?option=com_seminarman&amp;view=categories';
SeminarmanViewSeminarman::quickiconButton($link, 'icon-48-categories.png', JText::_('COM_SEMINARMAN_CATEGORIES'));

$link = 'index.php?option=com_seminarman&amp;view=tutors';
SeminarmanViewSeminarman::quickiconButton($link, 'icon-48-tutors.png', JText::_('COM_SEMINARMAN_TUTORS'));

$link = 'index.php?option=com_seminarman&amp;view=settings';
SeminarmanViewSeminarman::quickiconButton($link, 'icon-48-config.png', JText::_('COM_SEMINARMAN_SETTINGS'));

} else {
	
$link = 'index.php?option=com_seminarman&amp;view=applications';
SeminarmanViewSeminarman::quickiconButton($link, 'icon-48-applications.png', JText::_('COM_SEMINARMAN_APPLICATION'));

$link = 'index.php?option=com_seminarman&amp;view=salesprospects';
SeminarmanViewSeminarman::quickiconButton($link, 'icon-48-inter.png', JText::_('COM_SEMINARMAN_LST_OF_SALES_PROSPECTS'));

$link = 'index.php?option=com_seminarman&amp;view=courses';
SeminarmanViewSeminarman::quickiconButton($link, 'icon-48-courses.png', JText::_('COM_SEMINARMAN_COURSES'));

$link = 'index.php?option=com_seminarman&amp;view=course';
SeminarmanViewSeminarman::quickiconButton($link, 'icon-48-courseedit.png', JText::_('COM_SEMINARMAN_NEW_COURSE'));	

$link = 'index.php?option=com_seminarman&amp;view=tags';
SeminarmanViewSeminarman::quickiconButton($link, 'icon-48-tags.png', JText::_('COM_SEMINARMAN_TAGS'));
}
?>
						</div>
                        </div>
                    <div class="cpanel-right">
<?php

$title = JText::_('COM_SEMINARMAN_LATEST_APPLICATIONS');
echo $this->pane->startPane('stat-pane');
echo $this->pane->startPanel($title, 'latestApplications');

?>
				<table class="adminlist">
				<?php

$k = 0;
$n = count($this->latestApplications);
for ($i = 0, $n; $i < $n; $i++)
{
    $row = $this->latestApplications[$i];
    $link = 'index.php?option=com_seminarman&amp;controller=application&amp;task=edit&amp;cid[]=' .
        $row->id;

?>
					<tr>
						<td>
								<a href="<?php

    echo $link;

?>">
									<?php

    echo htmlspecialchars($row->title . " - " . $row->first_name . " " . $row->
        last_name . " " . JHTML::date($row->date), ENT_QUOTES, 'UTF-8');

?>
								</a>
						</td>
					</tr>
					<?php

    $k = 1 - $k;
}

?>
				</table>
				<?php

$title = JText::_('COM_SEMINARMAN_ADDED_COURSES');
echo $this->pane->endPanel();
echo $this->pane->startPanel($title, 'latestJobs');

?>
				<table class="adminlist">
				<?php

$k = 0;
$n = count($this->latestJobs);
for ($i = 0, $n; $i < $n; $i++)
{
    $row = $this->latestJobs[$i];
    $link = 'index.php?option=com_seminarman&amp;controller=courses&amp;task=edit&amp;cid[]=' .
        $row->id;

?>
					<tr>
						<td>
								<a href="<?php

    echo $link;

?>">
									<?php

    echo htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8');

?>
								</a>
						</td>
					</tr>
					<?php

    $k = 1 - $k;
}

?>
				</table>
				<?php

echo $this->pane->endPane();
?>
                    
                    </div>

                    </div>                        

			</div>

			</td>
		</tr>
		<tr><td colspan="2"><center>OSG Seminar Manager 2.1.0</center></td></tr>
	</table>