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
$params = &$mainframe->getParams('com_seminarman');
?>


<script language="javascript" type="text/javascript">
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
	else if (document.formvalidator.isValid(form))
		Joomla.submitform( task );
	else
		alert("<?php echo JText::_('COM_SEMINARMAN_VALUES_NOT_ACCEPTABLE'); ?>");
	
};
</script>

<div id="seminarman" class="seminarman">

    <p class="buttons"><?php echo seminarman_html::printbutton($this->print_link, $this->params); ?></p>

<?php if ($this->params->get('show_page_title', 1) && $this->params->get('page_title') != $this->template->title): ?>
    <h1 class="componentheading"><?php echo $this->params->get('page_title'); ?></h1>
<?php endif; ?>

    <h2 class="seminarman course<?php echo $this->template->id; ?>"><?php echo $this->escape($this->template->title); ?></h2>

    <div class="course_details floattext">

        <dl class="course_info_left floattext">
<?php if ($this->params->get('show_modify_date')): ?>
            <dt class="modified"><?php echo JText::_('COM_SEMINARMAN_LAST_REVISED') . ':'; ?></dt>
            <dd class="modified"><?php echo $this->template->modified ? JFactory::getDate($this->template->modified)->format("j. F Y") : JText::_('COM_SEMINARMAN_NEVER'); ?></dd>
<?php endif; ?>
            <dt class="reference"><?php echo JText::_('COM_SEMINARMAN_COURSE_CODE') . ':'; ?></dt>
            <dd class="reference"><?php if ($this->template->code<>"") echo $this->template->code; ?></dd>
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

    <div class="course_details floattext">

        <dl class="course_info_left floattext">
            <dt class="price"><?php echo JText::_('COM_SEMINARMAN_PRICE') . ':'; ?></dt>
            <dd class="price"><?php echo $this->escape($this->template->price) . ' ' . $this->escape($this->template->currency_price) . ' ' . $this->escape($this->template->price_type).' '; echo ($this->params->get('show_gross_price') == 1) ? '('.JText::_('COM_SEMINARMAN_WITH_VAT').')' : '('.JText::_('COM_SEMINARMAN_WITHOUT_VAT').')'; ?></dd>
<?php if ($this->params->get('show_location')): ?>
            <dt class="location"><?php echo JText::_('COM_SEMINARMAN_LOCATION') . ':'; ?></dt>
            <dd class="location"><?php echo empty($this->template->location) ? JText::_('COM_SEMINARMAN_NOT_SPECIFIED') : $this->template->location; ?></dd>
<?php endif; ?>
            <dt class="start_date"><?php echo JText::_('COM_SEMINARMAN_DATES') . ':'; ?></dt>
            <dd class="start_date"><?php echo JText::_('COM_SEMINARMAN_NOT_SCHEDULED'); ?></dd>
        </dl>

        <dl class="course_info_right floattext">
<?php if ($this->params->get('show_group')): ?>
            <dt class="group"><?php echo JText::_('COM_SEMINARMAN_GROUP') . ':'; ?></dt>
            <dd class="group"><?php echo empty($this->template->cgroup) ? JText::_('COM_SEMINARMAN_NOT_SPECIFIED') : $this->template->cgroup; ?></dd>
<?php endif; ?>
<?php if ($this->params->get('show_experience_level')): ?>
            <dt class="level"><?php echo JText::_('COM_SEMINARMAN_LEVEL') . ':'; ?></dt>
            <dd class="level"><?php $level = $this->escape($this->template->level); echo empty($level) ? JText::_('COM_SEMINARMAN_NOT_SPECIFIED') : $level; ?></dd>
<?php endif; ?>
        </dl>

    </div>

    <h2 class="description"><?php echo JText::_('COM_SEMINARMAN_DESCRIPTION'); ?></h2>
    <div class="description course_text"><?php echo $this->template->text; ?></div>

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
    <!--application form-->
    <h2 class="componentheading<?php echo $this->params->get('pageclass_sfx'); ?>"><?php echo JText::_('COM_SEMINARMAN_ON_LST_OF_SALES_PROSPECTS'); ?></h2>
    <div class="course_applicationform">
	<?php
	switch ($params->get('enable_bookings')) {
		case 2:
			echo $this->loadTemplate('salesprospectform');
			break;
		case 1:
			if ($this->user->get('guest'))
				echo JText::_('COM_SEMINARMAN_PLEASE_LOGIN_FIRST') .'.';
			else
				echo  $this->loadTemplate('salesprospectform');
			break;
		default:
			echo JText::_('COM_SEMINARMAN_BOOKINGS_DISABLED') .'.';
	}
	?>
    </div>


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
    endif;

    ?>

</div>
