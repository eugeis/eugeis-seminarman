<?php
/**
*
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2012 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.formvalidation');
// JHtml::register('behavior.tooltip', $this->clau_tooltip());
$mainframe = JFactory::getApplication();
$params = $mainframe->getParams('com_seminarman');
$Itemid = JRequest::getInt('Itemid');
?>

<div id="seminarman" class="seminarman">
<div class="tutor_block">
<div class="tutor_block_left">
<img src="<?php echo $this->siteurl . 'images/' . $this->tutor->tutor_photo; ?>">
</div>
<div class="tutor_block_right">
<span class="tutor_label"><?php echo $this->tutor->tutor_label; ?></span><br><br>
<span><?php echo $this->tutor->tutor_desc; ?></span>

    <?php
    // custom fields
    foreach ($this->fields as $name => $this->fieldGroup){
    if ($name != 'ungrouped'){?>

    <?php
    }

    ?>

            <?php

            foreach ($this->fieldGroup as $f){
            $f = JArrayHelper::toObject ($f);
            $f->value = $this->escape($f->value);
              if (!empty($f->value)) {
            ?>
            <div class="custom_fld">
                <h3><?php if ($f->type != "checkboxtos") { if ($f->required == 1) echo ''; echo JText::_($f->name); } ?></h3>
                <div class="custom_fld_value">
                <?php 
                    if ($f->type == "date") {
                       echo JFactory::getDate($f->value)->format("j. M Y");                   		
                    } else {
                        echo SeminarmanCustomfieldsLibrary::getFieldData($f->type , $f->value); 
                    }
                ?>
                </div>
            </div>
            <?php
              }
            }

            ?>
    <?php
    }

    ?>

<br><br>
</div>
</div>
<table class="seminarmancoursetable" summary="seminarman">
<thead>
<tr>
	<th id="qf_code" class="sectiontableheader" nowrap="nowrap"><?php echo JText::_( 'COM_SEMINARMAN_COURSE_CODE' ); ?></th>
	<th id="qf_title" class="sectiontableheader"><?php echo JText::_( 'COM_SEMINARMAN_COURSE_TITLE' ); ?></th>
	<th id="qf_start_date" class="sectiontableheader" nowrap="nowrap"><?php echo JText::_( 'COM_SEMINARMAN_START_DATE' ); ?></th>
	<th id="qf_finish_date" class="sectiontableheader" nowrap="nowrap"><?php echo JText::_( 'COM_SEMINARMAN_FINISH_DATE' ); ?></th>
<?php if ($params->get('show_location')): ?>
	<th id="qf_location" class="sectiontableheader"><?php echo JText::_( 'COM_SEMINARMAN_LOCATION' ); ?></th>
<?php endif; ?>
</tr>
</thead>

<tbody>

<?php
$i=0;
if(!empty($this->courses)){
foreach ($this->courses as $course):
	$itemParams = new JParameter($course->attribs);
?>
<tr class="sectiontableentry" >
	<td headers="qf_code" nowrap="nowrap"><?php echo $this->escape($course->code); ?></td>
	<td headers="qf_title"><strong><a href="<?php echo JRoute::_('index.php?view=courses&cid=' . $course->cat . '&id=' . $course->slug . '&Itemid=' . $Itemid); ?>"><?php echo $this->escape($course->title); ?></a></strong></td>
	<td headers="qf_start_date" nowrap="nowrap"><?php echo JFactory::getDate($course->start_date)->format("j. M Y"); ?></td>
	<td headers="qf_finish_date" nowrap="nowrap"><?php echo JFactory::getDate($course->finish_date)->format("j. M Y"); ?></td>
<?php if ($params->get('show_location')): ?>
	<td headers="qf_location">
        <?php
    if ( empty( $course->location ) ) {
            echo JText::_('COM_SEMINARMAN_NOT_SPECIFIED');
    }
    else {
                if ( empty( $course->url ) || $course->url == "http://" ) {
                        echo $course->location;
                }
                else {?>
                        <a href='<?php echo $course->url; ?>' target="_blank"><?php echo $course->location; ?></a>
                        <?php
                }
    }
    ?>	
	</td>
<?php endif; ?>
</tr>


<?php
$i++;
endforeach;
}
?>
</tbody>
</table>
</div>
