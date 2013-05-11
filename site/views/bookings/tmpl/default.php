<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');

require_once JPATH_ROOT.'/components/com_seminarman/helpers/application.php';

$params = JComponentHelper::getParams('com_seminarman');

$Itemid = JRequest::getInt('Itemid');
?>
<script type="text/javascript">

	function tableOrdering( order, dir, task )
	{
		var form = document.getElementById("adminForm");

		form.filter_order.value 	= order;
		form.filter_order_Dir.value	= dir;
		document.getElementById("adminForm").submit( task );
	}
</script>

<div id="seminarman" class="seminarman">



<?php

if ($this->params->def('show_page_title', 1)):

?>

    <h1 class="componentheading">
		<?php

    echo $this->params->get('page_title');

?>
	</h1>

<?php

endif;

?>

<?php

if (!count($this->courses)):

?>

	<div class="note">
		<?php

    echo JText::_('COM_SEMINARMAN_NO_CURRENT_BOOKINGS');

?>
	</div>

<?php

else:

?>

<form action="<?php

echo $this->action;

?>" method="post" id="adminForm">

<?php

if ($this->params->get('filter') || $this->params->get('display')):

?>

<div id="qf_filter" class="floattext">
		<?php

		if ($this->params->get('filter')):

		?>
		<div class="qf_fleft"><?php

		echo JText::_('COM_SEMINARMAN_COURSE'). ':';

		?>
			<input type="text" name="filter" id="filter" value="<?php

			echo $this->lists['filter'];

			?>" class="text_area" size="15"/>
<?php

echo $this->lists['filter_experience_level'];

?>
			<button onclick="document.getElementById('adminForm').submit();"><?php

			echo JText::_('COM_SEMINARMAN_GO');

			?></button>
			<button onclick="document.getElementById('filter').value='';document.getElementById('filter_experience_level').value=0;document.getElementById('adminForm').submit();"><?php

			echo JText::_('COM_SEMINARMAN_RESET');

			?></button>
		</div>
		<?php

		endif;

		?>
		<?php

		if ($this->params->get('display')):

		?>
		<div class="qf_fright">
			<?php

			echo '<label for="limit">' . JText::_('COM_SEMINARMAN_DISPLAY_NUM') . '</label>&nbsp;';
			echo $this->pageNav->getLimitBox();

			?>
		</div>
		<?php

		endif;

		?>
</div>
<?php

endif;

?>

<table class="seminarmancoursetable" summary="seminarman">
	<thead>
			<tr>
				<th id="qf_title" class="sectiontableheader"><?php

echo JHTML::_('grid.sort', 'COM_SEMINARMAN_COURSE_TITLE', 'i.title', $this->lists['filter_order_Dir'],
	$this->lists['filter_order']);

				?></th>
				<th id="qf_start_date" class="sectiontableheader"><?php

echo JHTML::_('grid.sort', 'COM_SEMINARMAN_START_DATE', 'i.start_date', $this->lists['filter_order_Dir'],
	$this->lists['filter_order']);

				?></th>
				<th id="qf_finish_date" class="sectiontableheader"><?php

echo JHTML::_('grid.sort', 'COM_SEMINARMAN_FINISH_DATE', 'i.finish_date', $this->lists['filter_order_Dir'],
	$this->lists['filter_order']);

				?></th>
				<?php if ($this->params->get('show_location')): ?>
				<th id="qf_location" class="sectiontableheader"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_LOCATION', 'i.location', $this->lists['filter_order_Dir'],	$this->lists['filter_order']); ?></th>
				<?php endif; ?>
				<th id="qf_status" class="sectiontableheader"><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_STATUS', 'i.status', $this->lists['filter_order_Dir'],	$this->lists['filter_order']); ?></th>
				
<?php if ($params->get('invoice_generate') == 1): ?>
<th id="qf_invoice" class="sectiontableheader"><?php echo JText::_('COM_SEMINARMAN_INVOICE'); ?></th>
<?php endif; ?>
				
				<?php

				if ($this->params->get('enable_paypal')):

				?>
					<th id="qf_application" class="sectiontableheader"><?php

					echo JText::_('COM_SEMINARMAN_PAY_ONLINE');

					?></th>
				<?php

				endif;

				?>
				<th id="qf_note" class="sectiontableheader"><?php

echo JHTML::_('grid.sort', 'COM_SEMINARMAN_COURSE_NOTE', 'i.title', $this->lists['filter_order_Dir'],
	$this->lists['filter_order']);

				?></th>
				<th id="qf_attendance" class="sectiontableheader"><?php

echo JHTML::_('grid.sort', 'COM_SEMINARMAN_COURSE_ATTENDANCE', 'i.title', $this->lists['filter_order_Dir'],
	$this->lists['filter_order']);

				?></th>
			</tr>
	</thead>

	<tbody>

	<?php

	foreach ($this->courses as $course):
	
	//$htmlclassnumber = $course->odd + 1;
	$itemParams = new JParameter($course->attribs);
	
	?>
  			<tr class="sectiontableentry" >
				<td headers="qf_title">
    				<strong><a href="<?php

    				echo JRoute::_('index.php?view=courses&cid=' . $this->category->slug . '&id=' . $course->
    				    slug);

    				?>"><?php

    				echo $this->escape($course->title);
    				?></a></strong>


				</td>
		    			<td headers="qf_start_date">
    				<?php

    				//echo date('d-M-y', strtotime($course->start_date));
    				echo $course->start_date;

    				?>
				</td>
    			<td headers="qf_finish_date">
    				<?php

    				//echo date('d-M-y', strtotime($course->finish_date));
    				echo $course->finish_date;

    				?>
				</td>
				<?php if ($this->params->get('show_location')): ?>
				<td headers="qf_location"><?php echo $course->location; ?></td>
				<?php endif; ?>
				<td headers="qf_status">
    				<?php
    				$status_text = ApplicationHelper::getStatusText($course->status);
    				if(!ApplicationHelper::isCourseOldByFinishDate($course->finishDateAsDate)){
    					$back = substr(JURI::current(), strlen(JURI::base()));
    					$url = substr_replace(JURI::root(), '', -1, 1).'/index.php?option=com_seminarman&controller=application&task=changestatus&status='. $course->status .'&cid='. $course->applicationid .'&'.JUtility::getToken().'=1&back='.$back;
  					?>
    					<a href="<?php echo $url ?>"><?php echo $status_text; ?></a>
    				<?php 
    				}else {
    					echo $status_text;
    				}
    				?>
				</td>

 <?php
if ($params->get('invoice_generate') == 1)
{
	if (!empty($course->invoice_filename_prefix) && ($course->price > 0))
	{
		echo '<td class="centered"><a href="'. JRoute::_('index.php?option=com_seminarman&view=bookings&layout=invoicepdf&appid=' . $course->applicationid) .'"><img alt="'.JText::_('COM_SEMINARMAN_INVOICE').'_'.$course->applicationid.'.pdf" src="components/com_seminarman/assets/images/mime-icon-16/pdf.png" /></a></td>';
	}
	else
		echo '<td class="centered">-</td>';
}
?>				<?php

 				if ($this->params->get('enable_paypal')):

 				?>
					<td headers="qf_book"><?php if ($course->price > 0) echo $course->book_link; ?>


					</td>
				<?php

				endif;

				?>
				<td headers="qf_note"><?php $course->note; ?>
				</td>
				<td headers="qf_attendance"><?php echo $course->attendance; ?>
				</td>				
			</tr>
	<?php

	endforeach;

	?>
	</tbody>
</table>

<p>
	<input type="hidden" name="option" value="com_seminarman" />
	<input type="hidden" name="filter_order" value="<?php

        echo $this->lists['filter_order'];

?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<input type="hidden" name="view" value="bookings" />
	<input type="hidden" name="task" value="" />
	</p>
	</form>
	
<?php
if ($this->params->get('filter') || $this->params->get('display')):
?>
	<div class="pagination"><?php echo $this->pageNav->getPagesLinks(); ?></div>
<?php
endif;
?>

<?php

endif;

?>

</div>
