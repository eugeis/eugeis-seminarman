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

<div id="seminarman" class="seminarman">

    <p class="buttons"><?php echo seminarman_html::favouritesbutton($this->params); echo seminarman_html::printbutton($this->print_link, $this->params); ?></p>

<?php 
    if ($this->params->get('show_page_heading', 1)) {
    	$page_heading = trim($this->params->get('page_heading'));
        if (!empty($page_heading)) {
            echo '<h1 class="componentheading">' . $page_heading . '</h1>';
        } else {
        	echo '<h1 class="componentheading">' . $this->course->title . '</h1>';
        }
    }
?>

    <h2 class="seminarman course<?php echo $this->course->id; ?>"><?php echo $this->escape($this->course->title); ?></h2>

    <div class="course_details floattext">

        <dl class="course_info_left floattext">
            <dt class="start_date"><?php echo JText::_('COM_SEMINARMAN_START_DATE') . ':'; ?></dt>
            <dd class="start_date"><?php echo (!empty($this->course->start_time)) ? $this->course->start_date . ', ' . $this->course->start_time : $this->course->start_date; ?></dd>
            <dt class="finish_date"><?php echo JText::_('COM_SEMINARMAN_FINISH_DATE') . ':'; ?></dt>
            <dd class="finish_date"><?php echo (!empty($this->course->finish_time)) ? $this->course->finish_date . ', ' . $this->course->finish_time : $this->course->finish_date; ?></dd>
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
<?php 
    $course_attribs = new JRegistry();
    $course_attribs->loadString($this->course->attribs);
?>
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
                <?php echo "<span id='cca'>".$this->course->currentAvailability."</span>" . " " . JText::_('COM_SEMINARMAN_OF') . " "; ?>
                <?php endif; ?>
                <?php echo $this->course->capacity; ?>
            </dd>
<?php endif; ?>
<?php if ($this->params->get('show_tutor')): ?>
            <dt class="tutor"><?php echo JText::_('COM_SEMINARMAN_TUTOR') . ':'; ?></dt>
          <?php if ($this->course->tutor_published) { ?>
            <dd class="tutor"><a href="<?php echo JRoute::_('index.php?option=com_seminarman&view=tutor&id=' . $this->course->tutor_id . '&Itemid=' . $Itemid); ?>"><?php echo $this->course->tutor; ?></a></dd>
          <?php } else { ?>
            <dd class="tutor"><?php echo $this->course->tutor; ?></dd>
          <?php } ?>
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
        	<strong><a href="<?php echo JRoute::_('index.php?option=com_seminarman&fileid=' . $file->fileid . '&task=download' . '&Itemid=' . $Itemid); ?>"><?php echo $file->altname ? $this->escape($file->altname) : $this->escape($file->filename); ?></a></strong>
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
$show_course_booking = $course_attribs->get('show_booking_form');
if (($this->show_application_form == 1) && ($show_course_booking !== 0))   {
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
        <strong><a href="<?php echo JRoute::_('index.php?option=com_seminarman&view=category&cid=' . $category->slug . '&Itemid=' . $Itemid); ?>"><?php echo $this->escape($category->title); ?></a></strong>
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
        <strong><a href="<?php echo JRoute::_('index.php?option=com_seminarman&view=tags&id=' . $tag->slug . '&Itemid=' . $Itemid); ?>"><?php echo $this->escape($tag->name); ?></a></strong>
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
