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


<script language="javascript" type="text/javascript">
function submitbuttonSeminarman(task)
{
	var form = document.adminForm;

	if (task == "cancel")
		Joomla.submitform( task );
	else if ((document.getElementById('cm_tos')) && !(document.getElementById('cm_tos').checked))
		  alert( "<?php echo JText::sprintf('COM_SEMINARMAN_ACCEPT_TOS', 'AGB'); ?>" );
	else if (document.formvalidator.isValid(form))
		Joomla.submitform( task );
	else
		  alert("<?php echo JText::_('COM_SEMINARMAN_VALUES_NOT_ACCEPTABLE'); ?>");
};
</script>
<?php 
// list of courses
$db = JFactory::getDBO();
$db->setQuery('SELECT * FROM #__seminarman_courses WHERE id ='. $_POST['course_id']);
if (!$db->query()) {
	JError::raiseError(500, $db->stderr(true));
	return;
}

$courseRows = $db->loadObject();

$price_orig = $courseRows->price;
$price_booking = $price_orig;
if ($_POST['booking_price'][0] == 1) { // 2. price group
	$price_booking = $courseRows->price2;
} elseif ($_POST['booking_price'][0] == 2) { // 3. price group
	$price_booking = $courseRows->price3;
} elseif ($_POST['booking_price'][0] == 3) { // 4. price group
	$price_booking = $courseRows->price4;
} elseif ($_POST['booking_price'][0] == 4) { // 5. price group
	$price_booking = $courseRows->price5;
}
if (empty($_POST['attendees'])) {
	$_POST['attendees'] = 1;
}
$price_total_orig = $price_orig * $_POST['attendees'];
$price_total_booking = $price_booking * $_POST['attendees'];
$price_total_discount = $price_total_orig - $price_total_booking;
$tax_rate = $courseRows->vat / 100.0;
$tax_booking = $price_total_booking * $tax_rate;
$price_total_booking_with_tax = $price_total_booking * (1 + $tax_rate);

?>
<div id="seminarman" class="seminarman">
<h2><?php echo JText::_('COM_SEMINARMAN_CART_CONFIRM'); ?></h2>
<br />
    <table class="seminarman_cart">
    <tr><td colspan="2"><h2><?php echo JText::_('COM_SEMINARMAN_CART_REG_DATA');?></h2></td></tr>
    <tr><td class="paramlist_key vtop">&nbsp;</td>
        <td class="paramlist_value vtop"><?php echo $_POST['salutation'] . ' ' . $_POST['first_name'] . ' ' . $_POST['last_name']; ?></td></tr>
    <tr><td class="paramlist_key vtop"><label for="jformemail"><?php echo JText::_('COM_SEMINARMAN_EMAIL'); ?>:</label></td>
        <td class="paramlist_value vtop"><?php echo $_POST['email']; ?></td></tr>
    <tr><td colspan="2">&nbsp;<br>&nbsp;</td></tr>      
    <?php
    // custom fields
    foreach ($this->fields as $name => $this->fieldGroup){
    if ($name != 'ungrouped'){?>
    <tr><td colspan="2"><h2><?php echo $name;?></h2></td></tr>
    <?php
    }

    ?>

            <?php

            foreach ($this->fieldGroup as $f){
            $f = JArrayHelper::toObject ($f);
            $f->value = $this->escape($f->value);

            ?>
            <tr>
                <td class="paramlist_key vtop" id="lblfield<?php echo $f->id;?>"><label for="lblfield<?php echo $f->id;?>"><?php if ($f->type != "checkboxtos") echo JText::_($f->name) . ':'; ?></label></td>
                <td class="paramlist_value vtop"><?php
                    $var = 'field' . $f->id;
                    if ($f->type != "checkboxtos") {
                    	if (($f->type == "checkbox") || ($f->type == "list")) {
                    		if (isset($_POST[$var])) {
                    		    foreach ($_POST[$var] as $f_item) {
                    		        echo $f_item . "<br />";	
                    		    }
                    		}
                    	// } elseif ($f->type == "date") {
                    	//	$str_datum = "";
                    	//	foreach ($_POST[$var] as $f_item) {
                    	//		$str_datum = $str_datum . "." . $f_item;    
                    	//    }
                    	//    $str_datum = substr($str_datum, 1);
                    	//    echo $str_datum;
                    	} else {
                    	    if (isset($_POST[$var])) echo $_POST[$var]; 
                    	}
                    }
                  ?></td>
            </tr>
            <?php
            }

            ?>
    <tr><td colspan="2">&nbsp;<br>&nbsp;</td></tr>
    <?php
    }

    ?>
    </table>
</div>
<br /><br />
<table style="width: 100%; font-size: small;" border="1" cellpadding="5" align="center" class="seminarman_cart">
<tbody>
<tr>
<td style="width: 10%; text-align: left;"><?php echo JText::_('COM_SEMINARMAN_COURSE_CODE'); ?></td>
<td style="width: 50%; text-align: left;"><?php echo JText::_('COM_SEMINARMAN_COURSE'); ?></td>
<td style="width: 10%; text-align: left;"><?php echo JText::_('COM_SEMINARMAN_CART_QUANTITY'); ?></td>
<td style="width: 15%; text-align: right;"><?php echo JText::_('COM_SEMINARMAN_CART_PRICE_SINGLE') . ' ' . $params->get('currency'); ?></td>
<td style="width: 15%; text-align: right;"><?php echo JText::_('COM_SEMINARMAN_CART_PRICE_TOTAL') . ' ' . $params->get('currency'); ?></td>
</tr>
<tr>
<td style="text-align: left;"><?php echo $courseRows->code; ?></td>
<td style="text-align: left;"><?php echo $courseRows->title; ?></td>
<td style="text-align: left;"><?php echo $_POST['attendees']; ?></td>
<td style="text-align: right;"><?php echo JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $this->escape($price_orig))), 2)); ?></td>
<td style="text-align: right;"><?php echo JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $this->escape($price_total_orig))), 2)); ?></td>
</tr>
<?php if ($courseRows->vat <> 0) { ?>
<tr>
<td style="text-align: right;" colspan="3"><?php echo JText::_('COM_SEMINARMAN_CART_NETTO_TOTAL'); ?></td>
<td style="text-align: right;" colspan="2"><?php echo JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $this->escape($price_total_orig))), 2)); ?></td>
</tr>
<?php } ?>
<?php if ($price_total_discount <> 0) { ?>
<tr>
<td style="text-align: right;" colspan="3"><?php echo JText::_('COM_SEMINARMAN_CART_DISCOUNT_TOTAL'); ?></td>
<td style="text-align: right;" colspan="2"><?php echo JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $this->escape($price_total_discount))), 2)); ?></td>
</tr>
<?php } ?>
<?php if ($courseRows->vat <> 0) { ?>
<tr>
<td style="text-align: right;" colspan="3"><?php echo JText::sprintf('COM_SEMINARMAN_CART_WITHOUT_VAT', JText::sprintf('%.0f', round(doubleval(str_replace(",", ".", $this->escape($courseRows->vat))), 2)) . '%'); ?></td>
<td style="text-align: right;" colspan="2"><?php echo JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $this->escape($tax_booking))), 2)); ?></td>
</tr>
<?php } ?>
<tr>
<td style="text-align: right;" colspan="3"><strong><?php echo JText::_('COM_SEMINARMAN_CART_BOOKING_TOTAL'); ?></strong></td>
<td style="text-align: right;" colspan="2"><strong><?php echo JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $this->escape($price_total_booking_with_tax))), 2)); ?></strong></td>
</tr>
</tbody>
</table>
<br />
<center>
<form action="#" method="post" name="adminForm" id="adminForm" class="form-validate"  enctype="multipart/form-data">
<?php 
    foreach ($this->fields as $name => $this->fieldGroup){
    	foreach ($this->fieldGroup as $f){
    		$f = JArrayHelper::toObject ($f);
    		$f->value = $this->escape($f->value);
    		// $var = 'field' . $f->id;
    		// echo '<input type="hidden" name="' . $var .'" value="' . $_POST[$var] . '" />';
    		if ($f->type == "checkboxtos") {
    			$tos = $f->options->{"0"};
    			// echo '<input type="checkbox" id="cm_tos" /> ' . $tos . '<br /><br />';
    			echo '<div style="text-align: left; overflow: hidden;">'. SeminarmanCustomfieldsLibrary::getFieldHTML($f , '') . '</div>';
    		}
    	}
    }
?>
<button type="button" class="button cancel" onclick="submitbuttonSeminarman('cancel')">
<?php echo JText::_('COM_SEMINARMAN_CART_CANCEL_BUTTON');?>
</button>
<button type="button" class="button validate" onclick="submitbuttonSeminarman('save')">
<?php echo JText::_('COM_SEMINARMAN_CART_CONFIRM_BUTTON');?>
</button>
    <input type="hidden" name="course_id" value="<?php echo $_POST['course_id']; ?>" />
    <input type="hidden" name="email" value="<?php echo $_POST['email']; ?>" />
    <input type="hidden" name="attendees" value="<?php echo $_POST['attendees']; ?>" />
    <input type="hidden" name="salutation" value="<?php echo $_POST['salutation']; ?>" />
    <input type="hidden" name="title" value="<?php echo $_POST['title']; ?>" />
    <input type="hidden" name="first_name" value="<?php echo $_POST['first_name']; ?>" />
    <input type="hidden" name="last_name" value="<?php echo $_POST['last_name']; ?>" />
    <input type="hidden" name="booking_price[]" value="<?php echo $_POST['booking_price'][0]; ?>" />
    <?php 
    foreach ($this->fields as $name => $this->fieldGroup){
    	foreach ($this->fieldGroup as $f){
    		$f = JArrayHelper::toObject ($f);
    		$f->value = $this->escape($f->value);
    		$var = 'field' . $f->id;
    		if (($f->type == "checkbox") || ($f->type == "list")) {
    			if (isset($_POST[$var])) {
    			foreach ($_POST[$var] as $f_item) {
    			    echo '<input type="hidden" name="' . $var .'[]" value="' . $f_item . '" />';
    			}
    			}
    		} elseif ($f->type != "checkboxtos") {
    			if (isset($_POST[$var])) {
    		        echo '<input type="hidden" name="' . $var .'" value="' . $_POST[$var] . '" />';
    			}
    		}
    	}
    }
    ?>

    
    <input type="hidden" name="option" value="com_seminarman" />
    <input type="hidden" name="controller" value="application" />
    <input type="hidden" name="task" value="" />
<?php
    echo JHTML::_('form.token');
?>
</form>
</center>
