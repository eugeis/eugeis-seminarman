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

<h2 class="seminarman favourites">
	<?php

echo JText::_('COM_SEMINARMAN_FAVOURED_COURSES') . ': ';

?>
</h2>

<?php

if (!count($this->courses)):

?>

	<div class="note">
		<?php

    echo JText::_('COM_SEMINARMAN_NO_FAVOURED_COURSES');

?>
	</div>

<?php

else:

?>

<?php

if ($this->params->get('filter') || $this->params->get('display')):

?>

<form action="<?php

echo $this->action;

?>" method="post" id="adminForm">

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
<?php if ( $this->params->get( 'show_icons' ) ) : ?>
   <td class="proc2 centered sectiontableheader<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
      <?php echo JText::_('#'); ?>
   </td>
   <?php else: ?>
  <td class="pix3 centered">
  </td>
   <?php endif; ?>

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
				<th id="qf_price" class="sectiontableheader"><?php

echo JHTML::_('grid.sort', 'COM_SEMINARMAN_PRICE', 'i.price', $this->lists['filter_order_Dir'],
	$this->lists['filter_order']);

				?>*</th>

				<?php

				if ($this->params->get('enable_bookings')):

				?>
					<th id="qf_application" class="sectiontableheader"><?php

					echo JText::_('COM_SEMINARMAN_APPLY_ONLINE');

					?></th>
				<?php

				endif;

				?>
			</tr>
	</thead>

	<tbody>

	<?php

	foreach ($this->courses as $course):
	//$htmlclassnumber = $course->odd + 1;
	$itemParams = new JParameter($course->attribs);

	?>
  			<tr class="sectiontableentry" >
    			 <?php if ( $itemParams->get('show_icons', $this->params->get( 'show_icons' ))) : ?>
   <td headers="qf_publish_up">
         <?php
         echo $this->pageNav->getRowOffset( $course->count ); ?>
   </td>
 <?php else: ?> <td headers="qf_publish_up"></td>
 <?php endif; ?>
				<td headers="qf_title">
    				<strong><a href="<?php

    				echo JRoute::_('index.php?view=courses&cid=' . $this->category->slug . '&id=' . $course->
    				    slug);

    				?>"><?php

    				echo $this->escape($course->title);
    				?></a></strong><?php
    				echo $course->show_new_icon;
    				echo $course->show_sale_icon;
    				?>


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
    			<td headers="qf_price">
    				<?php

    				echo $course->price . '&nbsp;'. $course->currency_price.' ' . $course->price_type;

    				?>
				</td>
 				<?php

 				if ($this->params->get('enable_bookings')):

 				?>
					<td headers="qf_book"><?php echo $course->book_link; ?>


					</td>
				<?php

				endif;

				?>

			</tr>
<?php if ($course->introtext):?>
			<tr><td colspan="7"><?php echo ($course->introtext); ?></td></tr>
<?php endif; ?>
			<tr><td colspan="7">
			<div class="tabulka">
<div class="radek">
<?php if ($itemParams->get('show_hits', $this->params->get('show_hits'))): ?>
  	<div class="bunka hlavicka"><div class="matrjoska">
<?php echo JText::_('COM_SEMINARMAN_HITS').': '.$course->hits; ?>
  	</div></div>
<?php endif; ?>
<?php if ($itemParams->get('show_hyperlink', $this->params->get('show_hyperlink'))&& $course->url<>"http://"):?>
  	<div class="bunka hlavicka"><div class="matrjoska">
<?php echo $course->link;?>
  	</div></div>
<?php endif; ?>
<?php if ($itemParams->get('show_tutor', $this->params->get('show_tutor'))):?>
  	<div class="bunka hlavicka"><div class="matrjoska">
<?php echo JText::_('COM_SEMINARMAN_TUTOR').': '.$course->tutor;?>
  	</div></div>
<?php endif; ?>
<?php if ($itemParams->get('show_location', $this->params->get('show_location'))):?>
<div class="bunka hlavicka"><div class="matrjoska">
<?php echo JText::_('COM_SEMINARMAN_LOCATION').': '.$course->location;?>
  	</div></div>
<?php endif; ?>
<?php if ($itemParams->get('show_group', $this->params->get('show_group'))):?>
<div class="bunka hlavicka"><div class="matrjoska">
<?php echo JText::_('COM_SEMINARMAN_GROUP').': '.$course->cgroup;?>
  	</div></div>
<?php endif; ?>
<?php if ($itemParams->get('show_experience_level', $this->params->get('show_experience_level'))):?>
<div class="bunka hlavicka"><div class="matrjoska">
<?php echo JText::_('COM_SEMINARMAN_LEVEL').': '.$course->level;?>
  	</div></div>
<?php endif; ?>
<?php if ($itemParams->get('show_capacity', $this->params->get('show_capacity'))):?>
	<div class="bunka hlavicka"><div class="matrjoska">
	<?php if ( $itemParams->get('current_capacity', $this->params->get( 'current_capacity' ))) : ?>
	      <?php echo JText::_('COM_SEMINARMAN_FREE_SEATS') .': '. $course->currentBookings .' '. JText::_( 'COM_SEMINARMAN_OF' ) .' '. $course->capacity; ?>
	<?php else : ?>
		  <?php echo JText::_('COM_SEMINARMAN_SEATS') .': '. $course->capacity; ?>
	<?php endif; ?>
	</div></div>
<?php endif; ?>
</div>
<div class="cl"></div>
</div>



			</td></tr>
	<?php

	endforeach;

	?>
<tr class="sectiontableentry" >
	<td colspan="6" class="right">*<?php echo ($this->params->get('show_gross_price') == 1) ? JText::_('COM_SEMINARMAN_WITH_VAT') : JText::_('COM_SEMINARMAN_WITHOUT_VAT'); ?></td>
</tr>
	</tbody>
</table>

<?php

if ($this->params->get('filter') || $this->params->get('display')):

?>

	<p>
	<input type="hidden" name="option" value="com_seminarman" />
	<input type="hidden" name="filter_order" value="<?php

        echo $this->lists['filter_order'];

?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<input type="hidden" name="view" value="favourites" />
	<input type="hidden" name="task" value="" />
	</p>
	</form>

	<?php

    endif;

?>

<?php

endif;

?>

</div>
