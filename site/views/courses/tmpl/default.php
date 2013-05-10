<?php
/**
*
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

require_once JPATH_ROOT.'/components/com_seminarman/helpers/application.php';

JHTML::_('behavior.formvalidation');
// JHtml::register('behavior.tooltip', $this->clau_tooltip());
$mainframe = JFactory::getApplication();
$params = $mainframe->getParams('com_seminarman');

$Itemid = JRequest::getInt('Itemid');
?>


<script type="text/javascript">
function submitbuttonSeminarman(task)
{
	var form = document.adminForm;

	if (document.formvalidator.isValid(form))
		Joomla.submitform( task );
	else
		alert("<?php echo JText::_('COM_SEMINARMAN_VALUES_NOT_ACCEPTABLE'); ?>");

};
</script>

<div
	id="seminarman" class="seminarman">

	<p class="buttons">
		<?php echo seminarman_html::favouritesbutton($this->params); echo seminarman_html::printbutton($this->print_link, $this->params); ?>
	</p>

	<?php if ($this->params->get('show_page_title', 1) && $this->params->get('page_title') != $this->course->title): ?>
	<h1 class="componentheading">
		<?php echo $this->params->get('page_title'); ?>
	</h1>
	<?php endif; ?>
	<h1 class="seminarman course<?php echo $this->course->id; ?>"><?php echo $this->escape($this->course->title); ?></h1>
	<?php if(($params->get('enable_bookings')>0) && ($this->attendeedata->id > 0) && (!$this->attendeedata->jusertype) && !ApplicationHelper::isCourseOld($item)) { ?>
		<div style="width: 99%;" align="right">
			<?php $back = substr(JURI::current(), strlen(JURI::base())); ?>
					<a href="<?php echo JRoute::_('index.php?option=com_seminarman&controller=application&task=save&course_id='. $this->course->id.'&'.JUtility::getToken() .'=1&back='.$back); ?>">
					<?php echo JText::_('COM_SEMINARMAN_BOOK_NOW'); ?></a>
		</div>
	<?php }?>
	<table style="width: 99%;">
		<tr>
			<td style="vertical-align: top;">
				<dl>
					<dt class="start_date">
						<?php echo JText::_('COM_SEMINARMAN_DATE') . ':'; ?>
					</dt>
					<dd class="start_date">
						<?php echo $this->course->start_date . ' - ' . $this->course->finish_date; ?>
					</dd>
					<?php if ($this->params->get('show_modify_date')): ?>
					<dt class="modified">
						<?php echo JText::_('COM_SEMINARMAN_LAST_REVISED') . ':'; ?>
					</dt>
					<dd class="modified">
						<?php echo $this->course->modified ? JFactory::getDate($this->course->modified)->format("j. F Y") : JText::_('COM_SEMINARMAN_NEVER'); ?>
					</dd>
					<?php endif; ?>
					<?php if ($this->params->get('show_hits')):?>
					<dt class="hits">
						<?php echo JText::_('COM_SEMINARMAN_HITS') . ':'; ?>
					</dt>
					<dd class="hits">
						<?php echo $this->course->hits; ?>
					</dd>
					<?php endif; ?>
					<?php if ($this->params->get('show_favourites')): ?>
					<dt class="favourites">
						<?php echo JText::_('COM_SEMINARMAN_FAVOURED') . ':'; ?>
					</dt>
					<dd class="favourites">
						<?php echo $this->favourites . ' ' . seminarman_html::favoure($this->course, $this->params, $this->favoured); ?>
					</dd>
					<?php endif; ?>
					<?php if ($this->params->get('show_tutor')): ?>
					<dt class="tutor">
						<?php echo JText::_('COM_SEMINARMAN_TUTOR') . ':'; ?>
					</dt>
					<dd class="tutor">
						<?php echo $this->course->tutor; ?>
					</dd>
					<?php endif; ?>
					<dd>
						<span class="centered"> <?php if ( $this->params->get('image') ) : ?>
							<img
							src="<?php $baseurl = JURI::base(); echo $baseurl; ?>/images/<?php echo $this->params->get('image'); ?>"
							alt="<?php echo $this->params->get('image'); ?>"> <?php endif; ?>
						</span>
					</dd>
				</dl>

			</td>
			<td style="vertical-align: top;">
				<dl>
					<?php if ($this->params->get('show_location')): ?>
					<dt class="location">
						<?php echo JText::_('COM_SEMINARMAN_LOCATION') . ':'; ?>
					</dt>
					<dd class="location">
						<?php echo empty($this->course->location) ? JText::_('COM_SEMINARMAN_NOT_SPECIFIED') : $this->course->location; ?>
					</dd>
					<?php endif; ?>
					<!--categories-->
				    <?php
				
				    if ($this->params->get('show_categories')):
				
				    ?>
				    
				    <dt class="seminarman course_categories"><?php echo JText::_('COM_SEMINARMAN_CATEGORY') . ':'; ?></dt>
				    
				    <?php
				
				    $n = count($this->categories);
				    $i = 0;
				
				    ?>
				    <dd class="seminarman categorylist">
				        <?php
				
				        foreach ($this->categories as $category):
				
				        ?>
				        <?php echo $this->escape($category->title); ?>
				        <?php
				
				        $i++;
				        if ($i != $n):
				        echo ',';
				        endif;
				        endforeach;
				
				        ?>
				    </dd>
				    <?php
				
				    endif;
				
				    ?>
					<?php if ($this->params->get('show_group')): ?>
					<dt class="group">
						<?php echo JText::_('COM_SEMINARMAN_GROUP') . ':'; ?>
					</dt>
					<dd class="group">
						<?php echo empty($this->course->cgroup) ? JText::_('COM_SEMINARMAN_NOT_SPECIFIED') : $this->course->cgroup; ?>
					</dd>
					<?php endif; ?>

					<?php if ($this->params->get('show_experience_level')): ?>
					<dt class="level">
						<?php echo JText::_('COM_SEMINARMAN_LEVEL') . ':'; ?>
					</dt>
					<dd class="level">
						<?php $level = $this->escape($this->course->level); echo empty($level) ? JText::_('COM_SEMINARMAN_NOT_SPECIFIED') : $level; ?>
					</dd>
					<?php endif; ?>
					<?php if ($this->params->get('show_capacity')): ?>
					<dt class="capacity">
						<?php

						if ($this->params->get('current_capacity'))
							echo JText::_('COM_SEMINARMAN_FREE_SEATS') . ':';
						else
							echo JText::_('COM_SEMINARMAN_SEATS') . ':';

						?>
					</dt>
					<dd class="capacity">
						<?php
						if ($this->params->get('current_capacity')) : ?>
						<?php echo $this->course->currentAvailability . " " . JText::_('COM_SEMINARMAN_OF') . " "; ?>
						<?php endif; ?>
						<?php echo $this->course->capacity; ?>
					</dd>
					<?php endif; ?>
				</dl>
			</td>
		</tr>
	</table>


	<?php if (($this->course->count_sessions > 0) &&  ($this->params->get('show_sessions'))) :?>
	<div class="course_details floattext">

		<table class="proc100">
			<tr>
				<td class="sectiontableheader centered proc20 hepix20"><?php echo JText::_('COM_SEMINARMAN_DATE'); ?>
				</td>
				<td class="sectiontableheader centered proc20 hepix20"><?php echo JText::_('COM_SEMINARMAN_START_TIME'); ?>
				</td>
				<td class="sectiontableheader centered proc20 hepix20"><?php echo JText::_('COM_SEMINARMAN_FINISH_TIME'); ?>
				</td>
				<td class="sectiontableheader centered proc20 hepix20"><?php echo JText::_('COM_SEMINARMAN_DURATION'); ?>
				</td>
				<td class="sectiontableheader centered proc20 hepix20"><?php echo JText::_('COM_SEMINARMAN_ROOM'); ?>
				</td>
			</tr>

			<?php foreach ($this->course_sessions as $course_session):
			echo '<tr>';
			echo '<td class="centered">' . $course_session->session_date . '</td>';
			echo '<td class="centered">' . date('H:i', strtotime($course_session->start_time)) . '</td>';
			echo '<td class="centered">' . date('H:i', strtotime($course_session->finish_time)) . '</td>';
			echo '<td class="centered">' . $course_session->duration . '</td>';
			echo '<td class="centered">' . $course_session->session_location . '</td>';
			echo '</tr>';
			endforeach;
			?>

		</table>

	</div>
	<?php endif; ?>

	<h2 class="description">
		<?php echo JText::_('COM_SEMINARMAN_DESCRIPTION'); ?>
	</h2>
	<div class="description course_text">
		<?php echo $this->course->text; ?>
	</div>

	<!--files-->
	<?php

	$n = count($this->files);
	$i = 0;
	if ($n != 0):

	?>
	<h2 class="seminarman course_files">
		<?php echo JText::_('COM_SEMINARMAN_FILES_FOR_DOWNLOAD'); ?>
	</h2>

	<div class="filelist">
		<?php
		foreach ($this->files as $file):
		echo JHTML::image($file->icon, '') . ' ';
		?>
		<strong><a
			href="<?php echo JRoute::_('index.php?fileid=' . $file->fileid . '&task=download'); ?>"><?php echo $file->altname ? $this->escape($file->altname) : $this->escape($file->filename); ?>
		</a> </strong>
		<?php

		$i++;
		if ($i != $n):
		echo ',';
		endif;
		endforeach;

		?>
	</div>
	<?php 
	endif;
	?>
	<br />
	<!--application form-->

	<!--tags-->
	<?php

	if ($this->params->get('show_tags')):
	$n = count($this->tags);
	$i = 0;
	if ($n != 0):

	?>
	<h2 class="seminarman course_tags">
		<?php echo JText::_('COM_SEMINARMAN_ASSIGNED_TAGS'); ?>
	</h2>
	<div class="taglist">
		<?php foreach ($this->tags as $tag): ?>
		<strong><a
			href="<?php echo JRoute::_('index.php?view=tags&id=' . $tag->slug); ?>"><?php echo $this->escape($tag->name); ?>
		</a> </strong>
		<?php $i++; if ($i != $n) echo ','; ?>
		<?php endforeach; ?>
	</div>
	<?php

	endif;

	?>
	<?php

	endif;

	?>


	<!--comments-->
	<?php

	if ($this->params->get('show_jcomments') || $this->params->get('show_jomcomments')):

	?>
	<div class="qf_comments">
		<?php

		if ($this->params->get('show_jcomments')):
		if (file_exists(JPATH_SITE . DS . 'components' . DS . 'com_jcomments' . DS .
				'jcomments.php')):
				require_once (JPATH_SITE . DS . 'components' . DS . 'com_jcomments' . DS .
						'jcomments.php');
		echo JComments::showComments($this->course->id, 'com_seminarman', $this->escape($this->course->title));
		endif;
		endif;

		if ($this->params->get('show_jomcomments')):
		if (file_exists(JPATH_SITE . DS . 'plugins' . DS . 'content' . DS .
				'jom_comment_bot.php')):
				require_once (JPATH_SITE . DS . 'plugins' . DS . 'content' . DS .
						'jom_comment_bot.php');
		echo jomcomment($this->course->id, 'com_seminarman');
		endif;
		endif;

		?>
	</div>
	<?php

    endif;

     ?>

</div>
