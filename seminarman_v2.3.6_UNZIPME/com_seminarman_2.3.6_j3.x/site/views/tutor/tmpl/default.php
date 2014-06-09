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
$comp_data = $params->get('tutor_company_data');
$db = JFactory::getDBO();
jimport('joomla.mail.helper');
?>

<div id="seminarman" class="seminarman">
<div class="tutor_block">
<div class="tutor_block_left">
<img src="<?php echo $this->siteurl . $params->get('image_path', 'images'). '/' . $this->tutor->tutor_photo; ?>">
</div>
<div class="tutor_block_right">
<span class="tutor_label"><?php echo $this->tutor->tutor_label; ?></span>
<?php if (($params->get('show_company_data')) && !empty($comp_data)): ?>
<br /><br />
<dl class="tutor_company_info">
    <?php if ((in_array("tutor_comp_name", $comp_data)) && !empty($this->tutor->comp_name)): ?>
    <dt><?php echo JText::_('COM_SEMINARMAN_COMPANY_NAME'); ?>:</dt>
    <dd><?php echo $this->tutor->comp_name; ?></dd>
    <?php endif; ?>
    <?php if ((in_array("tutor_primary_phone", $comp_data)) && !empty($this->tutor->primary_phone)): ?>
    <dt><?php echo JText::_('COM_SEMINARMAN_PRIMARY_PHONE'); ?>:</dt>
    <dd><?php echo $this->tutor->primary_phone; ?></dd>
    <?php endif; ?>
    <?php if ((in_array("tutor_fax_number", $comp_data)) && !empty($this->tutor->fax_number)): ?>
    <dt><?php echo JText::_('COM_SEMINARMAN_FAX_NUMBER'); ?>:</dt>
    <dd><?php echo $this->tutor->fax_number; ?></dd>
    <?php endif; ?>
    <?php if ((in_array("tutor_email", $comp_data)) && !empty($this->tutor->email) && (JMailHelper::isEmailAddress($this->tutor->email))): ?>
    <dt><?php echo JText::_('COM_SEMINARMAN_EMAIL'); ?>:</dt>
    <dd><?php echo '<a href="mailto:' . $this->tutor->email . '">' . $this->tutor->email . '</a>'; ?></dd>
    <?php endif; ?>
    <?php if ((in_array("tutor_url", $comp_data)) && !empty($this->tutor->url)): ?>
    <dt><?php echo JText::_('COM_SEMINARMAN_WEBSITE'); ?>:</dt>
    <dd><?php echo $this->tutor->url; ?></dd>
    <?php endif; ?>
    <?php if ((in_array("tutor_street", $comp_data)) && !empty($this->tutor->street)): ?>
    <dt><?php echo JText::_('COM_SEMINARMAN_STREET'); ?>:</dt>
    <dd><?php echo $this->tutor->street; ?></dd>
    <?php endif; ?>
    <?php if ((in_array("tutor_zip", $comp_data)) && !empty($this->tutor->zip)): ?>
    <dt><?php echo JText::_('COM_SEMINARMAN_ZIP'); ?>:</dt>
    <dd><?php echo $this->tutor->zip; ?></dd>
    <?php endif; ?>
    <?php if ((in_array("tutor_city", $comp_data)) && !empty($this->tutor->city)): ?>
    <dt><?php echo JText::_('COM_SEMINARMAN_CITY'); ?>:</dt>
    <dd><?php echo $this->tutor->city; ?></dd>
    <?php endif; ?>
    <?php if ((in_array("tutor_state", $comp_data)) && !empty($this->tutor->state)): ?>
    <dt><?php echo JText::_('COM_SEMINARMAN_STATE'); ?>:</dt>
    <dd><?php echo $this->tutor->state; ?></dd>
    <?php endif; ?>
    <?php if ((in_array("tutor_country", $comp_data)) && !empty($this->tutor->id_country)): ?>
    <?php 
        $query = 'SELECT title FROM #__seminarman_country WHERE id=' . $this->tutor->id_country;
        $db->setQuery($query);
        $tutor_country = $db->loadResult();
    ?>
    <dt><?php echo JText::_('COM_SEMINARMAN_COUNTRY'); ?>:</dt>
    <dd><?php echo $tutor_country; ?></dd>
    <?php endif; ?>
    <?php if ((in_array("tutor_company_type", $comp_data)) && !empty($this->tutor->id_comp_type)): ?>
    <?php 
        $query = 'SELECT title FROM #__seminarman_company_type WHERE id=' . $this->tutor->id_comp_type;
        $db->setQuery($query);
        $tutor_comp_type = $db->loadResult();
    ?>
    <dt><?php echo JText::_('COM_SEMINARMAN_COMPANY_TYPE'); ?>:</dt>
    <dd><?php echo $tutor_comp_type; ?></dd>
    <?php endif; ?>
    <?php if ((in_array("tutor_industry", $comp_data)) && !empty($this->tutor->industry)): ?>
    <dt><?php echo JText::_('COM_SEMINARMAN_INDUSTRY'); ?>:</dt>
    <dd><?php echo $this->tutor->industry; ?></dd>
    <?php endif; ?>
</dl>
<?php endif; ?>
<div class="clear"></div><br />
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
?>
<tr class="sectiontableentry" >
	<td headers="qf_code" nowrap="nowrap"><?php echo $this->escape($course->code); ?></td>
	<td headers="qf_title"><strong><a href="<?php echo JRoute::_('index.php?option=com_seminarman&view=courses&cid=' . $course->cat . '&id=' . $course->slug . '&Itemid=' . $Itemid); ?>"><?php echo $this->escape($course->title); ?></a></strong></td>
	<td headers="qf_start_date" nowrap="nowrap">
	<?php 
	    if ($course->start_date != '0000-00-00') {
	        echo JFactory::getDate($course->start_date)->format("j. M Y");
	    } else {
            echo JText::_('COM_SEMINARMAN_NOT_SPECIFIED');
	    }
	?>
	</td>
	<td headers="qf_finish_date" nowrap="nowrap">
	<?php 
	    if ($course->finish_date != '0000-00-00') {
	        echo JFactory::getDate($course->finish_date)->format("j. M Y");
	    } else {
            echo JText::_('COM_SEMINARMAN_NOT_SPECIFIED');
	    } 
	?>
	</td>
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
