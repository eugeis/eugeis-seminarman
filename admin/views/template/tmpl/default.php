<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');

function formatDate($str, $format) {
	if ($str != '0000-00-00')
		return JHTML::_('date', $str, $format);
}
?>

<script language="javascript" type="text/javascript">

function apply_discount_2() {
	var price = document.adminForm.elements["price"].value;
	price = price.replace(",", ".");
	var mathop = document.adminForm.elements["price2_mathop"].value;
	var mathval = document.adminForm.elements["price2_value"].value;
	
	if (mathop == '+%') {
        var factor = mathval/100;
        var new_price = price + (price*factor);
	} else if (mathop == '-%') {
        var factor = mathval/100;
        var new_price = price - (price*factor);
	} else if (mathop == '+') {
		var new_price = price + mathval;
	} else if (mathop == '-') {
		var new_price = price - mathval;
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
        var new_price = price + (price*factor);
	} else if (mathop == '-%') {
        var factor = mathval/100;
        var new_price = price - (price*factor);
	} else if (mathop == '+') {
		var new_price = price + mathval;
	} else if (mathop == '-') {
		var new_price = price - mathval;
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
        var new_price = price + (price*factor);
	} else if (mathop == '-%') {
        var factor = mathval/100;
        var new_price = price - (price*factor);
	} else if (mathop == '+') {
		var new_price = price + mathval;
	} else if (mathop == '-') {
		var new_price = price - mathval;
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
        var new_price = price + (price*factor);
	} else if (mathop == '-%') {
        var factor = mathval/100;
        var new_price = price - (price*factor);
	} else if (mathop == '+') {
		var new_price = price + mathval;
	} else if (mathop == '-') {
		var new_price = price - mathval;
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
	if (form.name.value == "")
		alert( "<?php echo JText::_('COM_SEMINARMAN_MISSING_NAME'); ?>" );
	else if (form.title.value == "")
		alert( "<?php echo JText::_('COM_SEMINARMAN_MISSING_TITLE'); ?>" );
	else if(form.catid.selectedIndex == -1)
		alert( "<?php echo JText::_('COM_SEMINARMAN_SELECT_CATEGORY'); ?>" );
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

.pane-sliders {
	margin: 18px 5px 5px;
}

</style>

<?php
$infoimage = JHTML::image('components/com_seminarman/assets/images/icon-16-hint.png', JText::_('NOTES'));
$params = JComponentHelper::getParams( 'com_seminarman' );
if ($params->get('trigger_virtuemart') == 1) {
	$readonly = 'readonly="readonly" style="background: #ddd;"';
} else {
	$readonly = '';
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
		<li>
			<label for="published"><?php echo JText::_('JPUBLISHED'); ?></label>
			<fieldset id="published" class="radio"><?php echo $this->lists['state']; ?></fieldset>
		</li>
		<li>
			<label for="name"><?php echo JText::_('COM_SEMINARMAN_TEMPLATE_NAME') ?><span class="star">&nbsp;*</span></label>
			<input class="inputbox" type="text" name="name" id="name" size="32" maxlength="254" value="<?php echo $this->row->name; ?>" />
		</li>
		<li>
			<label for="title"><?php echo JText::_('COM_SEMINARMAN_TITLE') ?><span class="star">&nbsp;*</span></label>
			<input name="title" value="<?php echo $this->row->title; ?>" size="32" maxlength="254" />
		</li>
		<li>
			<label for="code"><?php echo JText::_('COM_SEMINARMAN_COURSE_CODE') ?></label>
			<input class="inputbox" type="text" name="code" id="code" size="32" maxlength="20" value="<?php echo $this->row->code; ?>" />
		</li>
		<li>
			<label for="start_date"><?php echo JText::_('COM_SEMINARMAN_START_DATE'); ?></label>
			<?php echo JHTML::calendar( formatDate($this->row->start_date, JText::_('COM_SEMINARMAN_DATE_FORMAT1')),  'start_date', 'start_date', JText::_('COM_SEMINARMAN_DATE_FORMAT1_ALT'));?>
		</li>
		<li>
			<label for="finish_date"><?php echo JText::_('COM_SEMINARMAN_FINISH_DATE'); ?></label>
			<?php echo JHTML::calendar( formatDate($this->row->finish_date, JText::_('COM_SEMINARMAN_DATE_FORMAT1')),  'finish_date', 'finish_date', JText::_('COM_SEMINARMAN_DATE_FORMAT1_ALT'));?>
		</li>
		<li>
			<label for="email_template"><?php echo JText::_('COM_SEMINARMAN_EMAIL_TEMPLATE'); ?></label>
			<?php echo $this->lists['email_template']; ?>
		</li>
		<li>
			<label for="invoice_template"><?php echo JText::_('COM_SEMINARMAN_PDF_TEMPLATE'); ?></label>
			<?php echo $this->lists['invoice_template']; ?>
		</li>
		<li>
			<label for="attlst_template"><?php echo JText::_('COM_SEMINARMAN_ATTENDANCE_LIST_TEMPLATE'); ?></label>
			<?php echo $this->lists['attlst_template']; ?>
		</li>
		<li>
			<label for="group"><?php echo JText::_('COM_SEMINARMAN_GROUP') ?></label>
			<?php echo $this->lists['atgroup']; ?>
		</li>
		<li>
			<label for="experience_level"><?php echo JText::_('COM_SEMINARMAN_EXPERIENCE_LEVEL') ?></label>
			<?php echo $this->lists['experience_level']; ?>
		</li>
		<li>
			<label for="theme_points"><?php echo JText::_('COM_SEMINARMAN_POINTS'); ?></label>
			<input class="inputbox" type="text" name="theme_points" id="theme_points" size="10" maxlength="20" value="<?php echo $this->row->theme_points; ?>" />
		</li>
		<li>
			<label for="price"><?php echo JText::_('COM_SEMINARMAN_PRICE') ?> (<?php $params = JComponentHelper::getParams( 'com_seminarman' ); echo $params->get( 'currency' ); ?>)</label>
			<input class="inputbox" type="text" name="price" id="price" size="10" maxlength="20" value="<?php echo $this->row->price; ?>" />
			<input class="calculator" type="button" value="<?php echo JText::_('COM_SEMINARMAN_CALC_BUTTON') ?>" onclick="show_calculator(1);" />
		</li>
<li id="netto_rechner1" style="clear: left; background: #ddd; margin-left: 110px; padding: 10px; overflow: hidden; display: none;">
  <?php echo JText::sprintf('COM_SEMINARMAN_CALC_DESC', $this->row->vat); ?><br>
  <input class="calc_input"  type="text" name="bruttopreis1" id="bruttopreis1" size="10" maxlength="20" value="" />
  <input class="calc_input" type="button" value="<?php echo JText::_('COM_SEMINARMAN_CALC_NET_BUTTON') ?>" onclick="calc_netto(1);" />
  <input class="calc_input" type="button" value="<?php echo JText::_('COM_SEMINARMAN_CALC_CLOSE') ?>" onclick="hide_calc(1);" />
</li>
		<li>
			<label for="price2"><?php echo '2. ' . JText::_('COM_SEMINARMAN_PRICE') ?> (<?php $params = JComponentHelper::getParams( 'com_seminarman' ); echo $params->get( 'currency' ); ?>)</label>
			<input class="inputbox" <?php echo $readonly; ?> type="text" name="price2" id="price2" size="10" maxlength="20" value="<?php echo $this->row->price2; ?>" />
			<input type="button" value="<?php echo JText::_('COM_SEMINARMAN_USE_CALC_RULE_2'); ?>" onclick="apply_discount_2()" />
			<input class="calculator" type="button" value="<?php echo JText::_('COM_SEMINARMAN_CALC_BUTTON') ?>" onclick="show_calculator(2);" />
		</li>
<li id="netto_rechner2" style="clear: left; background: #ddd; margin-left: 110px; padding: 10px; overflow: hidden; display: none;">
  <?php echo JText::sprintf('COM_SEMINARMAN_CALC_DESC', $this->row->vat); ?><br>
  <input class="calc_input"  type="text" name="bruttopreis2" id="bruttopreis2" size="10" maxlength="20" value="" />
  <input class="calc_input" type="button" value="<?php echo JText::_('COM_SEMINARMAN_CALC_NET_BUTTON') ?>" onclick="calc_netto(2);" />
  <input class="calc_input" type="button" value="<?php echo JText::_('COM_SEMINARMAN_CALC_CLOSE') ?>" onclick="hide_calc(2);" />
</li>
		<li>
			<label for="price3"><?php echo '3. ' . JText::_('COM_SEMINARMAN_PRICE') ?> (<?php $params = JComponentHelper::getParams( 'com_seminarman' ); echo $params->get( 'currency' ); ?>)</label>
			<input class="inputbox" <?php echo $readonly; ?> type="text" name="price3" id="price3" size="10" maxlength="20" value="<?php echo $this->row->price3; ?>" />
			<input type="button" value="<?php echo JText::_('COM_SEMINARMAN_USE_CALC_RULE_3'); ?>" onclick="apply_discount_3()" />
			<input class="calculator" type="button" value="<?php echo JText::_('COM_SEMINARMAN_CALC_BUTTON') ?>" onclick="show_calculator(3);" />
		</li>
<li id="netto_rechner3" style="clear: left; background: #ddd; margin-left: 110px; padding: 10px; overflow: hidden; display: none;">
  <?php echo JText::sprintf('COM_SEMINARMAN_CALC_DESC', $this->row->vat); ?><br>
  <input class="calc_input"  type="text" name="bruttopreis3" id="bruttopreis3" size="10" maxlength="20" value="" />
  <input class="calc_input" type="button" value="<?php echo JText::_('COM_SEMINARMAN_CALC_NET_BUTTON') ?>" onclick="calc_netto(3);" />
  <input class="calc_input" type="button" value="<?php echo JText::_('COM_SEMINARMAN_CALC_CLOSE') ?>" onclick="hide_calc(3);" />
</li>
		<li>
			<label for="price4"><?php echo '4. ' . JText::_('COM_SEMINARMAN_PRICE') ?> (<?php $params = JComponentHelper::getParams( 'com_seminarman' ); echo $params->get( 'currency' ); ?>)</label>
			<input class="inputbox" <?php echo $readonly; ?> type="text" name="price4" id="price4" size="10" maxlength="20" value="<?php echo $this->row->price4; ?>" />
			<input type="button" value="<?php echo JText::_('COM_SEMINARMAN_USE_CALC_RULE_4'); ?>" onclick="apply_discount_4()" />
			<input class="calculator" type="button" value="<?php echo JText::_('COM_SEMINARMAN_CALC_BUTTON') ?>" onclick="show_calculator(4);" />
		</li>
<li id="netto_rechner4" style="clear: left; background: #ddd; margin-left: 110px; padding: 10px; overflow: hidden; display: none;">
  <?php echo JText::sprintf('COM_SEMINARMAN_CALC_DESC', $this->row->vat); ?><br>
  <input class="calc_input"  type="text" name="bruttopreis4" id="bruttopreis4" size="10" maxlength="20" value="" />
  <input class="calc_input" type="button" value="<?php echo JText::_('COM_SEMINARMAN_CALC_NET_BUTTON') ?>" onclick="calc_netto(4);" />
  <input class="calc_input" type="button" value="<?php echo JText::_('COM_SEMINARMAN_CALC_CLOSE') ?>" onclick="hide_calc(4);" />
</li>
		<li>
			<label for="price5"><?php echo '5. ' . JText::_('COM_SEMINARMAN_PRICE') ?> (<?php $params = JComponentHelper::getParams( 'com_seminarman' ); echo $params->get( 'currency' ); ?>)</label>
			<input class="inputbox" <?php echo $readonly; ?> type="text" name="price5" id="price5" size="10" maxlength="20" value="<?php echo $this->row->price5; ?>" />
			<input type="button" value="<?php echo JText::_('COM_SEMINARMAN_USE_CALC_RULE_5'); ?>" onclick="apply_discount_5()" />
			<input class="calculator" type="button" value="<?php echo JText::_('COM_SEMINARMAN_CALC_BUTTON') ?>" onclick="show_calculator(5);" />
		</li>
<li id="netto_rechner5" style="clear: left; background: #ddd; margin-left: 110px; padding: 10px; overflow: hidden; display: none;">
  <?php echo JText::sprintf('COM_SEMINARMAN_CALC_DESC', $this->row->vat); ?><br>
  <input class="calc_input"  type="text" name="bruttopreis5" id="bruttopreis5" size="10" maxlength="20" value="" />
  <input class="calc_input" type="button" value="<?php echo JText::_('COM_SEMINARMAN_CALC_NET_BUTTON') ?>" onclick="calc_netto(5);" />
  <input class="calc_input" type="button" value="<?php echo JText::_('COM_SEMINARMAN_CALC_CLOSE') ?>" onclick="hide_calc(5);" />
</li>
		<li>
			<label for="vat"><?php echo JText::_('COM_SEMINARMAN_VAT') ?></label>
			<input class="inputbox" type="text" name="vat" id="vat" size="10" maxlength="20" value="<?php echo $this->row->vat; ?>%" />
		</li>
		<li>
			<label for="price_type"><?php echo JText::_('COM_SEMINARMAN_PRICE_TYPE') ?></label>
			<?php echo $this->lists['price_type']; ?>
		</li>
		<li>
			<label for="min_attend"><?php echo JText::_('COM_SEMINARMAN_MIN_ATTENDEE') ?></label>
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
			<label for="catid"><?php echo JText::_('COM_SEMINARMAN_CATEGORY'); ?><span class="star">&nbsp;*</span></label>
			<?php echo $this->lists['catid']; ?>
		</li>
		<li>
			<label for="tags"><?php echo JText::_('COM_SEMINARMAN_TAGS'); ?></label>
			<?php echo $this->lists['tagsselect']; ?>
		</li>
	</ul>
	</fieldset>
	
<?php

$title = JText::_('COM_SEMINARMAN_PARAMETERS');
echo $this->pane->startPane('det-pane');
echo $this->pane->startPanel($title, 'params');
echo '<fieldset class="panelform">';
echo $this->form->render('params', 'basic');
echo '</fieldset>';
echo $this->pane->endPanel();
echo $this->pane->endPane();
?>
</div>
<div class="width-60 fltlft">
	
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_SEMINARMAN_COMPANY_DESC'); ?></legend>
		<?php echo $this->editor->display('text', $this->row->text, '100%', '250', '50', '15', array('pagebreak', 'readmore')); ?>
	</fieldset>
	
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_SEMINARMAN_CERTIFICATE_TEXT'); ?></legend>
		<?php echo $this->editor->display('certificate_text', $this->row->certificate_text, '100%', '250', '50', '15', array('pagebreak', 'readmore')); ?>
	</fieldset>
	
<?php $isnew = ($this->row->id == 0); ?>
	
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_SEMINARMAN_QUALIFIED_TUTORS'); ?></legend>
<?php if ($isnew): ?>
		<p><input type="button" value="<?php echo JText::_('COM_SEMINARMAN_SAVE_FIRST'); ?>" disabled="disabled"/></p>
<?php else: ?>
		<?php echo $this->lists['tutors_add'];?>
		<input type="text" name="tutor_prio" id="tutor_prio" value="<?php echo JText::_('COM_SEMINARMAN_PRIORITY'); ?>" style="color: gray;" class="text_area" onfocus="this.value = ''; this.style.color = '';" onblur="if (this.value == '') { this.value = '<?php echo JText::_('COM_SEMINARMAN_PRIORITY'); ?>'; this.style.color = 'gray'; }"/>
		<input type="button" id="add_tutor" onclick="javascript:Joomla.submitbutton('apply')"<?php if ($isnew) echo 'disabled="disabled"'; ?> value="<?php echo JText::_('COM_SEMINARMAN_ADD'); ?>" />
<?php endif; ?>
		<table class="adminlist cellspace1">
			<thead>
				<tr>
					<th class="nowrap" width="10%"><?php echo JText::_('COM_SEMINARMAN_ID'); ?></th>
					<th class="nowrap" width="70%"><?php echo JText::_('COM_SEMINARMAN_TITLE'); ?></th>
					<th class="nowrap" width="10%"><?php echo JText::_('COM_SEMINARMAN_PRIO'); ?></th>
					<th class="nowrap" width="10%"><?php echo JText::_('COM_SEMINARMAN_REMOVE'); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($this->lists['qualified_tutors'] as $t) : ?>
					<tr class="row0">
						<td><?php echo $t->id; ?></td>
						<td><a href="<?php echo JRoute::_('index.php?option=com_seminarman&controller=tutor&task=edit&cid[]=' . $t->id); ?> " target="_blank" style="font-size: 1em;"><?php echo $t->title; ?></a></td>
						<td><?php echo $t->priority; ?></td>
						<td><input type="checkbox" name="tutors_remove[]" value="<?php echo $t->id; ?>" /></td>
					</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
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
<input type="hidden" name="controller" value="templates" />
<input type="hidden" name="view" value="template" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="version" value="<?php echo $this->row->version; ?>" />
</form>

<?php JHTML::_('behavior.keepalive'); ?>