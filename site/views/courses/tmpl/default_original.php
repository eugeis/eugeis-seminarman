<?php
/**
*
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.formvalidation');
$mainframe = JFactory::getApplication();
$params = $mainframe->getParams('com_seminarman');
?>


<script type="text/javascript">
function submitbuttonSeminarman(task)
{
	var form = document.adminForm;

	if (document.getElementById('first_name').value == "")
		alert( "<?php echo JText::_('COM_SEMINARMAN_MISSING_FIRST_NAME', true); ?>" );
	else if (document.getElementById('last_name').value == "")
		alert( "<?php echo JText::_('COM_SEMINARMAN_MISSING_LAST_NAME', true); ?>" );
	else if(form.salutation.value == '')
		alert( "<?php echo JText::_('COM_SEMINARMAN_MISSING_SALUTATION_NAME', true); ?>" );
	else if (document.getElementById('cm_email').value == "")
		alert( "<?php echo JText::_('COM_SEMINARMAN_MISSING_EMAIL', true); ?>" );
	else if ((document.getElementById('cm_tos')) && !(document.getElementById('cm_tos').checked)) 
		alert( "<?php echo JText::sprintf('COM_SEMINARMAN_ACCEPT_TOS', 'AGB'); ?>" );
	else if (document.formvalidator.isValid(form))
		Joomla.submitform( task );
	else
		alert("<?php echo JText::_('COM_SEMINARMAN_VALUES_NOT_ACCEPTABLE'); ?>");

};
</script>

<div id="seminarman" class="seminarman">

    <p class="buttons"><?php echo seminarman_html::favouritesbutton($this->params); echo seminarman_html::printbutton($this->print_link, $this->params); ?></p>

<?php if ($this->params->get('show_page_title', 1) && $this->params->get('page_title') != $this->course->title): ?>
    <h1 class="componentheading"><?php echo $this->params->get('page_title'); ?></h1>
<?php endif; ?>

    <h2 class="seminarman course<?php echo $this->course->id; ?>"><?php echo $this->escape($this->course->title); ?></h2>

    <div class="course_details floattext">

        <dl class="course_info_left floattext">
            <dt class="start_date"><?php echo JText::_('COM_SEMINARMAN_START_DATE') . ':'; ?></dt>
            <dd class="start_date"><?php echo $this->course->start_date; ?></dd>
            <dt class="finish_date"><?php echo JText::_('COM_SEMINARMAN_FINISH_DATE') . ':'; ?></dt>
            <dd class="finish_date"><?php echo $this->course->finish_date; ?></dd>
<?php if ($this->params->get('show_modify_date')): ?>
            <dt class="modified"><?php echo JText::_('COM_SEMINARMAN_LAST_REVISED') . ':'; ?></dt>
            <dd class="modified"><?php echo $this->course->modified ? JFactory::getDate($this->course->modified)->format("j. F Y") : JText::_('COM_SEMINARMAN_NEVER'); ?></dd>
<?php endif; ?>
            <dt class="reference"><?php echo JText::_('COM_SEMINARMAN_COURSE_CODE') . ':'; ?></dt>
            <dd class="reference"><?php if ($this->course->code<>"") echo $this->course->code; ?></dd>
<?php if ($this->params->get('show_hits')):?>
            <dt class="hits"><?php echo JText::_('COM_SEMINARMAN_HITS') . ':'; ?></dt>
            <dd class="hits"><?php echo $this->course->hits; ?></dd>
<?php endif; ?>
<?php if ($this->params->get('show_favourites')): ?>
            <dt class="favourites"><?php echo JText::_('COM_SEMINARMAN_FAVOURED') . ':'; ?></dt>
            <dd class="favourites"><?php echo $this->favourites . ' ' . seminarman_html::favoure($this->course, $this->params, $this->favoured); ?></dd>
<?php endif; ?>
        </dl>

        <dl class="course_info_right floattext">
        	<dd>
        	<span class="centered">
<?php if ( $this->params->get('image') ) : ?>
                <img src="<?php $baseurl = JURI::base(); echo $baseurl; ?>/images/<?php echo $this->params->get('image'); ?>" alt="<?php echo $this->params->get('image'); ?>">
<?php endif; ?>
            </span>
            </dd>
        </dl>

    </div>
<?php
if (($this->params->get('second_currency') != 'NONE') && ($this->params->get('second_currency') != $this->params->get('currency'))){
   	if (doubleval($this->params->get('factor')) > 0) {
        $show_2_price = true;
        $sec_currency = $this->params->get('second_currency');
        $factor = doubleval(str_replace(",", ".", $this->params->get('factor')));		
    } else {
        $show_2_price = false;		
    }
} else {
    $show_2_price = false;    	
}
?>
    <div class="course_details floattext">

        <dl class="course_info_left floattext">
            <dt class="price"><?php echo JText::_('COM_SEMINARMAN_PRICE') . ':'; ?></dt>
            <dd class="price">
<?php 
if ($show_2_price) {
	echo $this->escape($this->course->price) . ' ' . $this->escape($this->course->currency_price) . ' | ' . JText::sprintf('%.2f', round(doubleval($factor*(doubleval(str_replace(",", ".", $this->escape($this->course->price))))), 2)) . ' ' . $sec_currency . ' ' . $this->escape($this->course->price_type); echo ($this->params->get('show_gross_price') == 1) ? ' ('.JText::_('COM_SEMINARMAN_WITH_VAT').')' : ' ('.JText::_('COM_SEMINARMAN_WITHOUT_VAT').')'; 
} else {
    echo $this->escape($this->course->price) . ' ' . $this->escape($this->course->currency_price) . ' ' . $this->escape($this->course->price_type); echo ($this->params->get('show_gross_price') == 1) ? ' ('.JText::_('COM_SEMINARMAN_WITH_VAT').')' : ' ('.JText::_('COM_SEMINARMAN_WITHOUT_VAT').')'; 
}
?>
            </dd>
<?php if ($this->params->get('show_location')): ?>
            <dt class="location"><?php echo JText::_('COM_SEMINARMAN_LOCATION') . ':'; ?></dt>
            <dd class="location">
            <?php
            if ( empty( $this->course->location ) ) {
                echo JText::_('COM_SEMINARMAN_NOT_SPECIFIED');
            }
            else {
                if ( empty( $this->course->url ) || $this->course->url == "http://" ) {
                    echo $this->course->location;
                }
                else {?>
                    <a href='<?php echo $this->course->url; ?>' target="_blank"><?php echo $this->course->location; ?></a>
                <?php
                }
            }
            ?>
            </dd>
<?php endif; ?>
<?php if ($this->params->get('show_group')): ?>
            <dt class="group"><?php echo JText::_('COM_SEMINARMAN_GROUP') . ':'; ?></dt>
            <dd class="group"><?php echo empty($this->course->cgroup) ? JText::_('COM_SEMINARMAN_NOT_SPECIFIED') : $this->course->cgroup; ?></dd>
<?php endif; ?>
        </dl>

        <dl class="course_info_right floattext">
<?php if ($this->params->get('show_experience_level')): ?>
            <dt class="level"><?php echo JText::_('COM_SEMINARMAN_LEVEL') . ':'; ?></dt>
            <dd class="level"><?php $level = $this->escape($this->course->level); echo empty($level) ? JText::_('COM_SEMINARMAN_NOT_SPECIFIED') : $level; ?></dd>
<?php endif; ?>
<?php if ($this->params->get('show_capacity')): ?>
            <dt class="capacity"><?php
            
            	if ($this->params->get('current_capacity'))
            		echo JText::_('COM_SEMINARMAN_FREE_SEATS') . ':';
            	else
            		echo JText::_('COM_SEMINARMAN_SEATS') . ':';

                ?></dt>
            <dd class="capacity"><?php
                if ($this->params->get('current_capacity')) : ?>
                <?php echo $this->course->currentAvailability . " " . JText::_('COM_SEMINARMAN_OF') . " "; ?>
                <?php endif; ?>
                <?php echo $this->course->capacity; ?>
            </dd>
<?php endif; ?>
<?php if ($this->params->get('show_tutor')): ?>
            <dt class="tutor"><?php echo JText::_('COM_SEMINARMAN_TUTOR') . ':'; ?></dt>
            <dd class="tutor"><?php echo $this->course->tutor; ?></dd>
<?php endif; ?>
            <dt class="author"></dt>
            <dd class="author"></dd>
        </dl>

    </div>
<?php if (($this->course->count_sessions > 0) &&  ($this->params->get('show_sessions'))) :?>
    <div class="course_details floattext">

        <table class="proc100">
            <tr>
                <td class="sectiontableheader centered proc20 hepix20"><?php echo JText::_('COM_SEMINARMAN_DATE'); ?></td>
                <td class="sectiontableheader centered proc20 hepix20"><?php echo JText::_('COM_SEMINARMAN_START_TIME'); ?></td>
                <td class="sectiontableheader centered proc20 hepix20"><?php echo JText::_('COM_SEMINARMAN_FINISH_TIME'); ?></td>
                <td class="sectiontableheader centered proc20 hepix20"><?php echo JText::_('COM_SEMINARMAN_DURATION'); ?></td>
                <td class="sectiontableheader centered proc20 hepix20"><?php echo JText::_('COM_SEMINARMAN_ROOM'); ?></td>
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

    <h2 class="description"><?php echo JText::_('COM_SEMINARMAN_DESCRIPTION'); ?></h2>
    <div class="description course_text"><?php echo $this->course->text; ?></div>

    <!--files-->
    <?php

    $n = count($this->files);
    $i = 0;
if ($n != 0):

    ?>
    <h2 class="seminarman course_files"><?php echo JText::_('COM_SEMINARMAN_FILES_FOR_DOWNLOAD'); ?></h2>

    <div class="filelist">
        <?php
        foreach ($this->files as $file):
       		echo JHTML::image($file->icon, '') . ' ';
        ?>
        	<strong><a href="<?php echo JRoute::_('index.php?fileid=' . $file->fileid . '&task=download'); ?>"><?php echo $file->altname ? $this->escape($file->altname) : $this->escape($file->filename); ?></a></strong>
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
<?php //  VM Product Details Button
//    if (!is_null($this->vmlink)) {
//	    echo '<p>' . JHTML::link($this->vmlink, 'zur Buchung in VirtueMart', array('title' => $this->course->title,'class' => 'product-details')) . '</p>';
//    }
?>

<?php
if ( $this->show_application_form == 1 )   {
        include "default_loadappform.php";
}
?>

    <!--categories-->
    <?php

    if ($this->params->get('show_categories')):

    ?>
    <h2 class="seminarman course_categories"><?php echo JText::_('COM_SEMINARMAN_CATEGORY'); ?></h2>
    <?php

    $n = count($this->categories);
    $i = 0;

    ?>
    <div class="categorylist">
        <?php

        foreach ($this->categories as $category):

        ?>
        <strong><a href="<?php echo JRoute::_('index.php?view=category&cid=' . $category->slug); ?>"><?php echo $this->escape($category->title); ?></a></strong>
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

    <!--tags-->
    <?php

    if ($this->params->get('show_tags')):
    $n = count($this->tags);
    $i = 0;
    if ($n != 0):

    ?>
    <h2 class="seminarman course_tags"><?php echo JText::_('COM_SEMINARMAN_ASSIGNED_TAGS'); ?></h2>
    <div class="taglist">
<?php foreach ($this->tags as $tag): ?>
        <strong><a href="<?php echo JRoute::_('index.php?view=tags&id=' . $tag->slug); ?>"><?php echo $this->escape($tag->name); ?></a></strong>
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
