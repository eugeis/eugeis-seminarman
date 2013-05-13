<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');

function formatDate($str, $format) {
	if ($str != '0000-00-00')
		return JHTML::_('date', $str, $format);
}
?>

<script language="javascript" type="text/javascript">

function resetHits()
{
	document.adminForm.hits.value = 0;
	document.adminForm.hitsdisp.value = 0;
}


// clone informations from template to course
function createFromTmpl()
{
	$task = 'createFromTmpl';
	submitform($task);
}

function apply_discount_2() {
	var price = document.adminForm.elements["price"].value;
	price = price.replace(",", ".");
	var mathop = document.adminForm.elements["price2_mathop"].value;
	var mathval = document.adminForm.elements["price2_value"].value;
	
	if (mathop == '+%') {
        var factor = mathval/100;
        var new_price = parseFloat(price) + parseFloat(price*factor);
	} else if (mathop == '-%') {
        var factor = mathval/100;
        var new_price = parseFloat(price) - parseFloat(price*factor);
	} else if (mathop == '+') {
		var new_price = parseFloat(price) + parseFloat(mathval);
	} else if (mathop == '-') {
		var new_price = parseFloat(price) - parseFloat(mathval);
	}
	
	document.adminForm.elements["price2"].value = new_price;
}

function apply_discount_3() {
	var price = document.adminForm.elements["price"].value;
	price = price.replace(",", ".");
	var mathop = document.adminForm.elements["price3_mathop"].value;
	var mathval = document.adminForm.elements["price3_value"].value;
	
	if (mathop == '+%') {
        var factor = mathval/100;
        var new_price = parseFloat(price) + parseFloat(price*factor);
	} else if (mathop == '-%') {
        var factor = mathval/100;
        var new_price = parseFloat(price) - parseFloat(price*factor);
	} else if (mathop == '+') {
		var new_price = parseFloat(price) + parseFloat(mathval);
	} else if (mathop == '-') {
		var new_price = parseFloat(price) - parseFloat(mathval);
	}
	
	document.adminForm.elements["price3"].value = new_price;
}

function apply_discount_4() {
	var price = document.adminForm.elements["price"].value;
	price = price.replace(",", ".");
	var mathop = document.adminForm.elements["price4_mathop"].value;
	var mathval = document.adminForm.elements["price4_value"].value;
	
	if (mathop == '+%') {
        var factor = mathval/100;
        var new_price = parseFloat(price) + parseFloat(price*factor);
	} else if (mathop == '-%') {
        var factor = mathval/100;
        var new_price = parseFloat(price) - parseFloat(price*factor);
	} else if (mathop == '+') {
		var new_price = parseFloat(price) + parseFloat(mathval);
	} else if (mathop == '-') {
		var new_price = parseFloat(price) - parseFloat(mathval);
	}
	
	document.adminForm.elements["price4"].value = new_price;
}

function apply_discount_5() {
	var price = document.adminForm.elements["price"].value;
	price = price.replace(",", ".");
	var mathop = document.adminForm.elements["price5_mathop"].value;
	var mathval = document.adminForm.elements["price5_value"].value;
	
	if (mathop == '+%') {
        var factor = mathval/100;
        var new_price = parseFloat(price) + parseFloat(price*factor);
	} else if (mathop == '-%') {
        var factor = mathval/100;
        var new_price = parseFloat(price) - parseFloat(price*factor);
	} else if (mathop == '+') {
		var new_price = parseFloat(price) + parseFloat(mathval);
	} else if (mathop == '-') {
		var new_price = parseFloat(price) - parseFloat(mathval);
	}
	
	document.adminForm.elements["price5"].value = new_price;
}

Joomla.submitbutton = function(task){

	var form = document.adminForm;

	if (task == 'cancel') {
		submitform( task );
		return;
	}

	// do field validation
	if (form.title.value == "")
		alert( "<?php echo JText::_('COM_SEMINARMAN_MISSING_TITLE'); ?>" );
	else if(form.catid.selectedIndex == -1)
		alert( "<?php echo JText::_('COM_SEMINARMAN_SELECT_CATEGORY'); ?>" );
	else if(form.tutor_id.value < 1)
		alert( "<?php echo JText::_('COM_SEMINARMAN_SELECT_TUTOR'); ?>" );
	else {
		<?php echo $this->editor->save('text'); ?>
		<?php echo $this->editor->save('certificate_text'); ?>
		Joomla.submitform( task );
	}
};
</script>

<style type="text/css">

fieldset.adminform label {
	min-width: 100px;
	text-align: right;
	padding-right: 10px;
	margin: 3px 0;
}

fieldset input, fieldset select, fieldset img, fieldset button {
    float: left;
    margin: 3px 5px 3px 0;
    width: auto;
    max-width: 200px;
}

fieldset.adminform {
	margin: 5px;
}

fieldset.radio {
    border: 0 none;
    float: left;
    margin: 0 0 5px;
    padding: 0 ! important;
}

fieldset.radio label {
    clear: none;
    display: inline;
    float: left;
    padding-left: 0;
    padding-right: 10px;
    min-width: 0 ! important;
}

</style>

<?php
$infoimage = JHTML::image('components/com_seminarman/assets/images/icon-16-hint.png', JText::_('NOTES'));
$params = JComponentHelper::getParams( 'com_seminarman' );

if(!(JHTMLSeminarman::UserIsCourseManager())){
	$disabled = 'disabled';
	$readonly = 'readonly="readonly" style="border-width: 0px;"';
	$readonly_price = 'readonly="readonly" style="border-width: 0px;"';
	$apply_2_discount = '';
	$apply_3_discount = '';
	$apply_4_discount = '';
	$apply_5_discount = '';
} else {
	$disabled = '';
	if ($params->get('trigger_virtuemart') == 1) {
        // $readonly_price = 'readonly="readonly" style="background: #ddd;"';
        // due to the changes of multiple price model from vm 2.0.16, all prices can now be edited freely
		$readonly_price = '';
	} else {
	    $readonly_price = '';
	}
	$readonly = '';
	$apply_2_discount = '<input type="button" value="' . JText::_('COM_SEMINARMAN_USE_CALC_RULE_2') . '" onclick="apply_discount_2()" />';
	$apply_3_discount = '<input type="button" value="' . JText::_('COM_SEMINARMAN_USE_CALC_RULE_3') . '" onclick="apply_discount_3()" />';
	$apply_4_discount = '<input type="button" value="' . JText::_('COM_SEMINARMAN_USE_CALC_RULE_4') . '" onclick="apply_discount_4()" />';
	$apply_5_discount = '<input type="button" value="' . JText::_('COM_SEMINARMAN_USE_CALC_RULE_5') . '" onclick="apply_discount_5()" />';	
}
?>

<script language="javascript" type="text/javascript">
function isEmpty(str) {
    return (!str || 0 === str.length);
}
function trim(str) {
	str = String(str);
    return str.replace(/^\s+|\s+$/g,"");
}
function show_calculator(idc) {
	switch(idc)
	{
	case 1:
	    document.getElementById("netto_rechner1").style.display="block";
	    break;
	case 2:
	    document.getElementById("netto_rechner2").style.display="block";
	    break;
	case 3:
	    document.getElementById("netto_rechner3").style.display="block";
	    break;
	case 4:
	    document.getElementById("netto_rechner4").style.display="block";
	    break;
	case 5:
	    document.getElementById("netto_rechner5").style.display="block";
	    break;		
	}
}
function calc_netto(idc) {
	switch(idc)
	{
	case 1:
	    var bruttopreis = document.adminForm.elements["bruttopreis1"].value;
	    break;
	case 2:
	    var bruttopreis = document.adminForm.elements["bruttopreis2"].value;
	    break;
	case 3:
	    var bruttopreis = document.adminForm.elements["bruttopreis3"].value;
	    break;
	case 4:
	    var bruttopreis = document.adminForm.elements["bruttopreis4"].value;
	    break;
	case 5:
	    var bruttopreis = document.adminForm.elements["bruttopreis5"].value;
	    break;
	}
	if ((isNaN(trim(bruttopreis)))||(isEmpty(trim(bruttopreis)))) {
        alert(unescape("Der von Ihnen gegebene Bruttopreis ist ungültig, bitte korrigieren!"));
	} else {
		// alert(document.item_form.elements["tax_percents[][0]"].value);
		// var vat=document.getElementById("tax_percent_name_1").value;
		var vat=<?php echo $this->row->vat; ?>;
		if ((isNaN(trim(vat)))||(isEmpty(trim(vat)))) {
			alert(unescape("Das von Ihnen gegebene Steuerregel ist ungültig, bitte korrigieren!"));
		}else{
			var nettopreis = bruttopreis / (1 + vat/100);
			switch(idc)
			{
			case 1:
			    document.adminForm.elements["price"].value = nettopreis;
	            document.getElementById("netto_rechner1").style.display="none";
	            break;
			case 2:
			    document.adminForm.elements["price2"].value = nettopreis;
	            document.getElementById("netto_rechner2").style.display="none";
	            break;
			case 3:
			    document.adminForm.elements["price3"].value = nettopreis;
	            document.getElementById("netto_rechner3").style.display="none";
	            break;
			case 4:
			    document.adminForm.elements["price4"].value = nettopreis;
	            document.getElementById("netto_rechner4").style.display="none";
	            break;
			case 5:
			    document.adminForm.elements["price5"].value = nettopreis;
	            document.getElementById("netto_rechner5").style.display="none";
	            break;
			}
		}
	}
}
function hide_calc(idc) {
	switch(idc)
	{
	case 1:
	    document.getElementById("netto_rechner1").style.display="none";
	    break;
	case 2:
	    document.getElementById("netto_rechner2").style.display="none";
	    break;
	case 3:
	    document.getElementById("netto_rechner3").style.display="none";
	    break;
	case 4:
	    document.getElementById("netto_rechner4").style.display="none";
	    break;
	case 5:
	    document.getElementById("netto_rechner5").style.display="none";
	    break;
	}
}
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">

<div class="width-40 fltlft">
	<fieldset class="adminform">
	<legend><?php echo JText::_( 'COM_SEMINARMAN_DETAILS' ); ?></legend>
	<ul class="adminformlist">
		<li>
			<label><?php echo JText::_('COM_SEMINARMAN_COURSE_ID'); ?></label>
			<input type="text" readonly="readonly" size="32" style="border-width: 0px;" value="<?php echo $this->row->id; ?>"></input>
		</li>
		<li>
			<label><?php echo JText::_('COM_SEMINARMAN_HITS'); ?></label>
			<input type="text" readonly="readonly" name="hitsdisp" size="<?php echo strlen($this->row->hits); ?>" style="border-width: 0px;" value="<?php echo $this->row->hits; ?>"></input>
			<input type="button" class="button" name="reset_hits"  value="<?php echo JText::_('COM_SEMINARMAN_RESET'); ?>" onclick="resetHits()" />
			
		</li>
		<li>
			<label><?php echo JText::_('COM_SEMINARMAN_REVISED'); ?></label>
			<input type="text" readonly="readonly" size="32" style="border-width: 0px;" value="<?php echo $this->row->version; ?> <?php echo JText::_('COM_SEMINARMAN_TIMES'); ?>"></input>
		</li>
		<li>
			<label><?php echo JText::_('COM_SEMINARMAN_CREATED'); ?></label>
			<input type="text" readonly="readonly" size="32" style="border-width: 0px;" value="<?php if ($this->row->created == $this->nullDate) echo JText::_('COM_SEMINARMAN_NEW_COURSE'); else echo JHTML::_('date', $this->row->created, JText::_('DATE_FORMAT_LC2')); ?>"></input>
		</li>
		<li>
			<label><?php echo JText::_('COM_SEMINARMAN_MODIFIED'); ?></label>
			<input type="text" readonly="readonly" size="32" style="border-width: 0px;" value="<?php if ($this->row->modified == $this->nullDate) echo JText::_('COM_SEMINARMAN_NOT_MODIFIED'); else echo JHTML::_('date', $this->row->modified, JText::_('DATE_FORMAT_LC2')); ?>"></input>
		</li>
<?php 
    echo $this->lists['select_vm'];
?>		
		<li>
			<label for="published"><?php echo JText::_('JPUBLISHED'); ?></label>
			<fieldset id="published" class="radio"><?php echo $this->lists['state']; ?></fieldset>
		</li>
		<li>
			<label for="new"><?php echo JText::_('COM_SEMINARMAN_NEW'); ?></label>
			<fieldset id="new" class="radio"><?php echo $this->lists['new']; ?></fieldset>
		</li>
		<li>
			<label for="canceled"><?php echo JText::_('COM_SEMINARMAN_COURSE_CANCELED'); ?></label>
			<fieldset id="canceled" class="radio"><?php echo $this->lists['canceled']; ?></fieldset>
		</li>
		<li>
			<label for="name"><?php echo JText::_('COM_SEMINARMAN_TITLE') ?><span class="star">&nbsp;*</span></label>
			<input class="inputbox" <?php echo $readonly; ?> type="text" name="title" id="title" size="32" maxlength="254" value="<?php echo $this->row->title; ?>" />
		</li>
		<li>
			<label for="title"><?php echo JText::_('COM_SEMINARMAN_ALIAS') ?></label>
			<input class="inputbox" <?php echo $readonly; ?> type="text" name="alias" id="alias" size="32" maxlength="254" value="<?php echo $this->row->alias; ?>" />
		</li>
		<li>
			<label for="code"><?php echo JText::_('COM_SEMINARMAN_COURSE_CODE') ?></label>
			<input name="code" id="code" <?php echo $readonly; ?> size="32" maxlength="20" value="<?php echo $this->row->code; ?>" />
		</li>
		<li>
			<label for="course_template"><?php echo JText::_('COM_SEMINARMAN_TEMPLATE');?></label>
			<?php echo $this->lists['templates'];?>
			<input type="button" value="<?php echo JText::_('COM_SEMINARMAN_CLONE');?>" onclick="createFromTmpl()" />
		</li>
		<li>
			<label for="tutor"><?php echo JText::_('COM_SEMINARMAN_TUTOR'); ?><span class="star">&nbsp;*</span></label>
			<?php echo $this->lists['username']; ?>
		</li>
		<li>
			<label for="start_date"><?php echo JText::_('COM_SEMINARMAN_START_DATE'); ?></label>
			<?php echo JHTML::calendar( formatDate($this->row->start_date, JText::_('COM_SEMINARMAN_DATE_FORMAT1')), 'start_date', 'start_date', JText::_('COM_SEMINARMAN_DATE_FORMAT1_ALT'));?>
		</li>
		<li>
			<label for="finish_date"><?php echo JText::_('COM_SEMINARMAN_FINISH_DATE'); ?></label>
			<?php echo JHTML::calendar( formatDate($this->row->finish_date, JText::_('COM_SEMINARMAN_DATE_FORMAT1')),  'finish_date', 'finish_date', JText::_('COM_SEMINARMAN_DATE_FORMAT1_ALT'));?>
		</li>
		<li>
			<label for="COM_SEMINARMAN_EMAIL_COURSE_BOOKED"><?php echo JText::_('COM_SEMINARMAN_EMAIL_COURSE_BOOKED'); ?></label>
			<?php echo $this->lists['email_template']; ?>
		</li>
		<li>
			<label for="email_template_cancel"><?php echo JText::_('COM_SEMINARMAN_EMAIL_COURSE_CANCELED'); ?></label>
			<?php echo $this->lists['email_template_cancel']; ?>
		</li>
		<li>
			<label for="email_template_trainer"><?php echo JText::_('COM_SEMINARMAN_EMAIL_LIST_ATTENDEES'); ?></label>
			<?php echo $this->lists['email_template_trainer']; ?>
		</li>
		<li>
			<label for="email_template_trainer_cancel"><?php echo JText::_('COM_SEMINARMAN_EMAIL_COURSE_CANCELED_TRAINER'); ?></label>
			<?php echo $this->lists['email_template_trainer_cancel']; ?>
		</li>						
		<li>
			<label for="attlst_template"><?php echo JText::_('COM_SEMINARMAN_ATTENDANCE_LIST_TEMPLATE'); ?></label>
			<?php echo $this->lists['attlst_template']; ?>
		</li>
		<li>
			<label for="theme_points"><?php echo JText::_('COM_SEMINARMAN_POINTS'); ?></label>
			<input class="inputbox" <?php echo $readonly; ?> type="text" name="theme_points" id="theme_points" size="10" maxlength="20" value="<?php echo $this->row->theme_points; ?>" />
		</li>
		<li>
			<label for="vat"><?php echo JText::_('COM_SEMINARMAN_VAT') ?></label>
			<input class="inputbox" <?php echo $readonly; ?> type="text" name="vat" id="vat" size="10" maxlength="20" value="<?php echo $this->row->vat; ?>%" />
		</li>
		<li>
			<label for="price_type"><?php echo JText::_('COM_SEMINARMAN_PRICE_TYPE') ?></label>
			<?php echo $this->lists['price_type']; ?>
		</li>
		<li>
			<label for="min_attend"><?php echo JText::_('COM_SEMINARMAN_MIN_ATTENDEE'); ?></label>
			<input class="inputbox" type="text" name="min_attend" id="min_attend" size="10" maxlength="5" value="<?php echo $this->row->min_attend; ?>" />
		</li>
		<li>
			<label for="capacity"><?php echo JText::_('COM_SEMINARMAN_CAPACITY') ?></label>
			<input class="inputbox" type="text" name="capacity" id="capacity" size="10" maxlength="5" value="<?php echo $this->row->capacity; ?>" />
		</li>
		<li>
			<label for="location"><?php echo JText::_('COM_SEMINARMAN_LOCATION') ?></label>
			<input class="inputbox" type="text" name="location" id="location" size="32" maxlength="254" value="<?php echo $this->row->location; ?>" />
		</li>
		<li>
			<label for="url"><?php echo JText::_('COM_SEMINARMAN_HYPERLINK') ?></label>
			<input class="inputbox" type="text" name="url" id="url" size="32" maxlength="254" value="<?php echo $this->row->url; ?>" />
		</li>
		<li>
			<label for="cid"><?php echo JText::_('COM_SEMINARMAN_CATEGORY'); ?><span class="star">&nbsp;*</span></label>
			<?php echo $this->lists['catid']; ?>
		</li>
		<li>
			<label for="tags"><?php echo JText::_('COM_SEMINARMAN_TAGS'); ?></label>
			<?php echo $this->lists['tagsselect']; ?>
		</li>
	</ul>
	</fieldset>
	
<fieldset class="adminform">
<legend><?php echo JText::_('COM_SEMINARMAN_PUBLISH_INFORMATION'); ?></legend>
<?php
$title = JText::_('COM_SEMINARMAN_DETAILS');
echo $this->pane->startPane('det-pane');
echo $this->pane->startPanel($title, 'details');
echo '<fieldset class="panelform">';
echo $this->form->render('details');
echo '</fieldset>';

$title = JText::_('COM_SEMINARMAN_PARAMETERS');
echo $this->pane->endPanel();
echo $this->pane->startPanel($title, 'params');
echo '<fieldset class="panelform">';
echo $this->form->render('params', 'basic');
echo '</fieldset>';

$title = JText::_('COM_SEMINARMAN_PARAMETERS_ADVANCED');
echo $this->pane->endPanel();
echo $this->pane->startPanel($title, "params-page");
echo '<fieldset class="panelform">';
echo $this->form->render('params', 'advanced');
echo '</fieldset>';

$title = JText::_('COM_SEMINARMAN_METADATA_INFORMATION');
echo $this->pane->endPanel();
echo $this->pane->startPanel($title, "metadata-page");
echo '<fieldset class="panelform">';
echo $this->form->render('meta', 'metadata');
echo '</fieldset>';

echo $this->pane->endPanel();
echo $this->pane->endPane();
?>
</fieldset>	
	
</div>

<div class="width-60 fltlft">

	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_SEMINARMAN_COMPANY_DESC'); ?></legend>
		<?php 
		if(JHTMLSeminarman::UserIsCourseManager()){
		echo $this->editor->display('text', $this->row->text, '100%', '250', '50', '15', array('pagebreak', 'readmore')); 
		}else{
		echo '<div style="display: none;">' . $this->editor->display('text', $this->row->text, '100%', '250', '50', '15', array('pagebreak', 'readmore')) . '</div>';	
		echo html_entity_decode('<div>' . $this->row->text . '</div>');	
		}
		?>
	</fieldset>
	
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_SEMINARMAN_CERTIFICATE_TEXT'); ?></legend>
		<?php 
		if(JHTMLSeminarman::UserIsCourseManager()){
		echo $this->editor->display('certificate_text', $this->row->certificate_text, '100%', '250', '50', '15', array('pagebreak', 'readmore')); 
		}else{
		echo '<div style="display: none;">' . $this->editor->display('certificate_text', $this->row->certificate_text, '100%', '250', '50', '15', array('pagebreak', 'readmore')) . '</div>';
		echo html_entity_decode('<div>' . $this->row->certificate_text . '</div>');		
		}
		?>
	</fieldset>
	
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_SEMINARMAN_FILES'); ?></legend>
		<div id="filelist"><?php echo $this->fileselect; ?></div>
		<div class="button2-left">
			<div class="blank">
				<a class="modal" title="<?php echo JText::_('COM_SEMINARMAN_SELECT'); ?>" href="<?php echo $this->linkfsel; ?>" rel="{handler: 'iframe', size: {x: 850, y: 450}}"><?php echo JText::_('COM_SEMINARMAN_SELECT'); ?></a>
			</div>
		</div>
		<div class="button2-left">
			<div class="blank">
				<a title="<?php echo JText::_('COM_SEMINARMAN_UPLOAD'); ?>" href="<?php	echo "index.php?option=com_seminarman&view=filemanager";?>" target="_blank"><?php echo JText::_('COM_SEMINARMAN_UPLOAD'); ?></a>
			</div>
		</div>
	</fieldset>
	
</div>
<div class="clr"></div>

<?php echo JHTML::_('form.token'); ?>
<input type="hidden" name="price2_mathop" value="<?php echo $this->lists['price2_mathop']; ?>" />
<input type="hidden" name="price2_value" value="<?php echo $this->lists['price2_value']; ?>" />
<input type="hidden" name="price3_mathop" value="<?php echo $this->lists['price3_mathop']; ?>" />
<input type="hidden" name="price3_value" value="<?php echo $this->lists['price3_value']; ?>" />
<input type="hidden" name="price4_mathop" value="<?php echo $this->lists['price4_mathop']; ?>" />
<input type="hidden" name="price4_value" value="<?php echo $this->lists['price4_value']; ?>" />
<input type="hidden" name="price5_mathop" value="<?php echo $this->lists['price5_mathop']; ?>" />
<input type="hidden" name="price5_value" value="<?php echo $this->lists['price5_value']; ?>" />
<input type="hidden" name="option" value="com_seminarman" />
<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
<input type="hidden" name="reference_number" value="<?php if ($this->row->reference_number) echo $this->row->reference_number; else echo uniqid(); ?>" />
<input type="hidden" name="controller" value="courses" />
<input type="hidden" name="view" value="course" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="version" value="<?php echo $this->row->version; ?>" />
<input type="hidden" name="hits" value="<?php echo $this->row->hits; ?>" />
<input type="hidden" name="minus" value="<?php echo $this->row->minus; ?>" />
<input type="hidden" name="plus" value="<?php echo $this->row->plus; ?>" />
</form>


<?php JHTML::_('behavior.keepalive'); ?>