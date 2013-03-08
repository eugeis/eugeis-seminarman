<?php 
$db = JFactory::getDbo();
$user = JFactory::getUser();
$current_usergroups = $user->getAuthorisedGroups();

$currency = $this->escape($this->course->currency_price);
$tax_rate = doubleval(str_replace(",", ".", $this->escape($this->course->vat)))/100;

if ($this->params->get('show_gross_price') == 1) {
	$standard_netto = $this->escape($this->price_before_vat);
} else {
    // $standard_netto = $this->escape($this->course->price);
    $standard_netto = $this->escape($this->price_before_vat);	
}

$standard_brutto = JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $this->escape($standard_netto))) * (1 + $tax_rate), 2));
$standard_netto = JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $this->escape($standard_netto))), 2));

$query_pricegroup2 = $db->getQuery(true);
$query_pricegroup2->select('*')
                  ->from('#__seminarman_pricegroups')
                  ->where('gid=2');
$db->setQuery($query_pricegroup2);
$priceg2 = $db->loadAssoc();
$priceg2_name = $priceg2['title'];
$priceg2_usg = json_decode($priceg2['jm_groups']);

$query_pricegroup3 = $db->getQuery(true);
$query_pricegroup3->select('*')
                  ->from('#__seminarman_pricegroups')
                  ->where('gid=3');
$db->setQuery($query_pricegroup3);
$priceg3 = $db->loadAssoc();
$priceg3_name = $priceg3['title'];
$priceg3_usg = json_decode($priceg3['jm_groups']);

$query_pricegroup4 = $db->getQuery(true);
$query_pricegroup4->select('*')
                  ->from('#__seminarman_pricegroups')
                  ->where('gid=4');
$db->setQuery($query_pricegroup4);
$priceg4 = $db->loadAssoc();
$priceg4_name = $priceg4['title'];
$priceg4_usg = json_decode($priceg4['jm_groups']);

$query_pricegroup5 = $db->getQuery(true);
$query_pricegroup5->select('*')
                  ->from('#__seminarman_pricegroups')
                  ->where('gid=5');
$db->setQuery($query_pricegroup5);
$priceg5 = $db->loadAssoc();
$priceg5_name = $priceg5['title'];
$priceg5_usg = json_decode($priceg5['jm_groups']);

if (is_null($priceg2_usg)) {
	$priceg2_usg = array();
}

if (is_null($priceg3_usg)) {
	$priceg3_usg = array();
}

if (is_null($priceg4_usg)) {
	$priceg4_usg = array();
}

if (is_null($priceg5_usg)) {
	$priceg5_usg = array();
}

if ($this->params->get('show_price_1') == 0) {
	$price1_label = JText::_('COM_SEMINARMAN_NET') . ': '
                 . $standard_netto . ' ' . $currency . ', ' . JText::_('COM_SEMINARMAN_GROSS_WITH_TAX') . ': ' . $standard_brutto . ' ' . $currency;
} else {
	$price1_label = 'Standard';
}

$display_price1 = '<input id="booking_price1" type="radio" value="0" checked="checked" name="booking_price[]"><label for="jformbookingprice1">' . $price1_label . '</label>';

if (!is_null($this->course->price2) && !($this->params->get('show_price_2') == 0)) {
    $price2_netto = JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $this->escape($this->course->price2))), 2));
    $price2_brutto = JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $this->escape($this->course->price2))) * (1 + $tax_rate), 2));
	if ($this->params->get('show_price_2') == 1) {  // Anzeige nur für getroffene Nutzer
		if (array_intersect($current_usergroups, $priceg2_usg)) { // getroffen
			$display_price1 = '';
			$display_price2 = '<input id="booking_price2" type="radio" value="1" checked="checked" name="booking_price[]"><label for="jformbookingprice2">'
                          . $priceg2_name . ' (' . JText::_('COM_SEMINARMAN_NET') . ': ' . $price2_netto . ' ' . $currency . ', ' . JText::_('COM_SEMINARMAN_GROSS_WITH_TAX') . ': ' . $price2_brutto . ' ' . $currency . ')</label>';
		} else {  // nicht getroffen
			$display_price2 = '';
		}
	} elseif ($this->params->get('show_price_2') == 2) {  // Anzeige für alle Nutzer
		if (array_intersect($current_usergroups, $priceg2_usg)) { // getroffen
            $display_price1 = '<input id="booking_price1" type="radio" value="0" name="booking_price[]"><label for="jformbookingprice1">' . $price1_label . '</label>';
            $display_price2 = '<br><input id="booking_price2" type="radio" value="1" checked="checked" name="booking_price[]"><label for="jformbookingprice2">'
                          . $priceg2_name . ' (' . JText::_('COM_SEMINARMAN_NET') . ': ' . $price2_netto . ' ' . $currency . ', ' . JText::_('COM_SEMINARMAN_GROSS_WITH_TAX') . ': ' . $price2_brutto . ' ' . $currency . ')</label>';			
		} else {
            $display_price2 = '<br><input id="booking_price2" type="radio" value="1" name="booking_price[]"><label for="jformbookingprice2">'
                          . $priceg2_name . ' (' . JText::_('COM_SEMINARMAN_NET') . ': ' . $price2_netto . ' ' . $currency . ', ' . JText::_('COM_SEMINARMAN_GROSS_WITH_TAX') . ': ' . $price2_brutto . ' ' . $currency . ')</label>';
		}		
	} elseif ($this->params->get('show_price_2') == 3) {  // Anzeige ohne Preiswert
		if (array_intersect($current_usergroups, $priceg2_usg)) { // getroffen
            $display_price1 = '<input id="booking_price1" type="radio" value="0" name="booking_price[]"><label for="jformbookingprice1">' . $price1_label . '</label>';
            $display_price2 = '<br><input id="booking_price2" type="radio" value="1" checked="checked" name="booking_price[]"><label for="jformbookingprice2">'
                          . $priceg2_name . '</label>';			
		} else {
            $display_price2 = '<br><input id="booking_price2" type="radio" value="1" name="booking_price[]"><label for="jformbookingprice2">'
                          . $priceg2_name . '</label>';
		}		
	}
} else { // Anzeige für keine Nutzer oder kein 2. Preis definiert
	$display_price2 = '';
} 

if (!is_null($this->course->price3) && !($this->params->get('show_price_3') == 0)) {
    $price3_netto = JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $this->escape($this->course->price3))), 2));
    $price3_brutto = JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $this->escape($this->course->price3))) * (1 + $tax_rate), 2));
	if ($this->params->get('show_price_3') == 1) {  // Anzeige nur für getroffene Nutzer
		if (array_intersect($current_usergroups, $priceg3_usg)) {  // getroffen
			$display_price1 = '';
			$display_price2 = '';
			$display_price3 = '<input id="booking_price3" type="radio" value="2" checked="checked" name="booking_price[]"><label for="jformbookingprice3">'
                          . $priceg3_name . ' (' . JText::_('COM_SEMINARMAN_NET') . ': ' . $price3_netto . ' ' . $currency . ', ' . JText::_('COM_SEMINARMAN_GROSS_WITH_TAX') . ': ' . $price3_brutto . ' ' . $currency . ')</label>';
		} else { // nicht getroffen
			$display_price3 = '';
		}
	} elseif ($this->params->get('show_price_3') == 2) {  // Anzeige für alle Nutzer
		if (array_intersect($current_usergroups, $priceg3_usg)) {
            $display_price1 = '<input id="booking_price1" type="radio" value="0" name="booking_price[]"><label for="jformbookingprice1">' . $price1_label . '</label>';			
            $display_price3 = '<br><input id="booking_price3" type="radio" value="2" checked="checked" name="booking_price[]"><label for="jformbookingprice3">'
                          . $priceg3_name . ' (' . JText::_('COM_SEMINARMAN_NET') . ': ' . $price3_netto . ' ' . $currency . ', ' . JText::_('COM_SEMINARMAN_GROSS_WITH_TAX') . ': ' . $price3_brutto . ' ' . $currency . ')</label>';
		} else {
            $display_price3 = '<br><input id="booking_price3" type="radio" value="2" name="booking_price[]"><label for="jformbookingprice3">'
                          . $priceg3_name . ' (' . JText::_('COM_SEMINARMAN_NET') . ': ' . $price3_netto . ' ' . $currency . ', ' . JText::_('COM_SEMINARMAN_GROSS_WITH_TAX') . ': ' . $price3_brutto . ' ' . $currency . ')</label>';	
		}	
	} elseif ($this->params->get('show_price_3') == 3) {  // Anzeige ohne Preiswert
		if (array_intersect($current_usergroups, $priceg3_usg)) {
            $display_price1 = '<input id="booking_price1" type="radio" value="0" name="booking_price[]"><label for="jformbookingprice1">' . $price1_label . '</label>';			
            $display_price3 = '<br><input id="booking_price3" type="radio" value="2" checked="checked" name="booking_price[]"><label for="jformbookingprice3">'
                          . $priceg3_name . '</label>';
		} else {
            $display_price3 = '<br><input id="booking_price3" type="radio" value="2" name="booking_price[]"><label for="jformbookingprice3">'
                          . $priceg3_name . '</label>';	
		}		
	}
} else { // Anzeige für keine Nutzer oder kein 3. Preis definiert
	$display_price3 = '';
}

if (!is_null($this->course->price4) && !($this->params->get('show_price_4') == 0)) {
	$price4_netto = JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $this->escape($this->course->price4))), 2));
	$price4_brutto = JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $this->escape($this->course->price4))) * (1 + $tax_rate), 2));
	if ($this->params->get('show_price_4') == 1) {  // Anzeige nur für getroffene Nutzer
		if (array_intersect($current_usergroups, $priceg4_usg)) {  // getroffen
			$display_price1 = '';
			$display_price2 = '';
			$display_price3 = '';
			$display_price4 = '<input id="booking_price4" type="radio" value="3" checked="checked" name="booking_price[]"><label for="jformbookingprice4">'
			. $priceg4_name . ' (' . JText::_('COM_SEMINARMAN_NET') . ': ' . $price4_netto . ' ' . $currency . ', ' . JText::_('COM_SEMINARMAN_GROSS_WITH_TAX') . ': ' . $price4_brutto . ' ' . $currency . ')</label>';
		} else { // nicht getroffen
			$display_price4 = '';
		}
	} elseif ($this->params->get('show_price_4') == 2) {  // Anzeige für alle Nutzer
		if (array_intersect($current_usergroups, $priceg4_usg)) {
			$display_price1 = '<input id="booking_price1" type="radio" value="0" name="booking_price[]"><label for="jformbookingprice1">' . $price1_label . '</label>';
			$display_price4 = '<br><input id="booking_price4" type="radio" value="3" checked="checked" name="booking_price[]"><label for="jformbookingprice4">'
			. $priceg4_name . ' (' . JText::_('COM_SEMINARMAN_NET') . ': ' . $price4_netto . ' ' . $currency . ', ' . JText::_('COM_SEMINARMAN_GROSS_WITH_TAX') . ': ' . $price4_brutto . ' ' . $currency . ')</label>';
		} else {
			$display_price4 = '<br><input id="booking_price4" type="radio" value="3" name="booking_price[]"><label for="jformbookingprice4">'
			. $priceg4_name . ' (' . JText::_('COM_SEMINARMAN_NET') . ': ' . $price4_netto . ' ' . $currency . ', ' . JText::_('COM_SEMINARMAN_GROSS_WITH_TAX') . ': ' . $price4_brutto . ' ' . $currency . ')</label>';
		}
	} elseif ($this->params->get('show_price_4') == 3) {  // Anzeige ohne Preiswert
		if (array_intersect($current_usergroups, $priceg4_usg)) {
			$display_price1 = '<input id="booking_price1" type="radio" value="0" name="booking_price[]"><label for="jformbookingprice1">' . $price1_label . '</label>';
			$display_price4 = '<br><input id="booking_price4" type="radio" value="3" checked="checked" name="booking_price[]"><label for="jformbookingprice4">'
			. $priceg4_name . '</label>';
		} else {
			$display_price4 = '<br><input id="booking_price4" type="radio" value="3" name="booking_price[]"><label for="jformbookingprice4">'
			. $priceg4_name . '</label>';
		}
	}
} else { // Anzeige für keine Nutzer oder kein 4. Preis definiert
	$display_price4 = '';
}

if (!is_null($this->course->price5) && !($this->params->get('show_price_5') == 0)) {
	$price5_netto = JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $this->escape($this->course->price5))), 2));
	$price5_brutto = JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $this->escape($this->course->price5))) * (1 + $tax_rate), 2));
	if ($this->params->get('show_price_5') == 1) {  // Anzeige nur für getroffene Nutzer
		if (array_intersect($current_usergroups, $priceg5_usg)) {  // getroffen
			$display_price1 = '';
			$display_price2 = '';
			$display_price3 = '';
			$display_price4 = '';
			$display_price5 = '<input id="booking_price5" type="radio" value="4" checked="checked" name="booking_price[]"><label for="jformbookingprice5">'
			. $priceg5_name . ' (' . JText::_('COM_SEMINARMAN_NET') . ': ' . $price5_netto . ' ' . $currency . ', ' . JText::_('COM_SEMINARMAN_GROSS_WITH_TAX') . ': ' . $price5_brutto . ' ' . $currency . ')</label>';
		} else { // nicht getroffen
			$display_price5 = '';
		}
	} elseif ($this->params->get('show_price_5') == 2) {  // Anzeige für alle Nutzer
		if (array_intersect($current_usergroups, $priceg5_usg)) {
			$display_price1 = '<input id="booking_price1" type="radio" value="0" name="booking_price[]"><label for="jformbookingprice1">' . $price1_label . '</label>';
			$display_price5 = '<br><input id="booking_price5" type="radio" value="4" checked="checked" name="booking_price[]"><label for="jformbookingprice5">'
			. $priceg5_name . ' (' . JText::_('COM_SEMINARMAN_NET') . ': ' . $price5_netto . ' ' . $currency . ', ' . JText::_('COM_SEMINARMAN_GROSS_WITH_TAX') . ': ' . $price5_brutto . ' ' . $currency . ')</label>';
		} else {
			$display_price5 = '<br><input id="booking_price5" type="radio" value="4" name="booking_price[]"><label for="jformbookingprice5">'
			. $priceg5_name . ' (' . JText::_('COM_SEMINARMAN_NET') . ': ' . $price5_netto . ' ' . $currency . ', ' . JText::_('COM_SEMINARMAN_GROSS_WITH_TAX') . ': ' . $price5_brutto . ' ' . $currency . ')</label>';
		}
	} elseif ($this->params->get('show_price_5') == 3) {  // Anzeige ohne Preiswert
		if (array_intersect($current_usergroups, $priceg5_usg)) {
			$display_price1 = '<input id="booking_price1" type="radio" value="0" name="booking_price[]"><label for="jformbookingprice1">' . $price1_label . '</label>';
			$display_price5 = '<br><input id="booking_price5" type="radio" value="4" checked="checked" name="booking_price[]"><label for="jformbookingprice5">'
			. $priceg5_name . '</label>';
		} else {
			$display_price5 = '<br><input id="booking_price5" type="radio" value="4" name="booking_price[]"><label for="jformbookingprice5">'
			. $priceg5_name . '</label>';
		}
	}
} else { // Anzeige für keine Nutzer oder kein 5. Preis definiert
	$display_price5 = '';
}

if (($this->params->get('trigger_virtuemart') == 1)  && !is_null($this->vmlink)) {
    $display_price1 = '<input id="booking_price1" type="radio" value="66" checked="checked" name="booking_price[]"><label for="jformbookingprice1">' 
                      . JText::_('COM_SEMINARMAN_PRICE_SHOW_IN_VM') . '</label>'; 
    $display_price2 = '';
    $display_price3 = ''; 
    $display_price4 = '';
    $display_price5 = '';  
}

if ($this->params->get('enable_payment_overview') == 1) {
	$next_action = 'cart';
} else {
	$next_action = 'save';
}

?>
<form action="<?php echo $this->action ?>" method="post" name="adminForm" id="adminForm" class="form-validate"  enctype="multipart/form-data">
    <table class="ccontentTable paramlist">
        <tbody>
            <tr><td colspan="2"><h2><?php echo JText::_('COM_SEMINARMAN_PRICE_SINGLE');?></h2></td></tr>
            <tr>
                <td class="paramlist_key vtop">
                    <label for="jformprice">* <?php echo JText::_('COM_SEMINARMAN_PRICE_BOOKING'); ?></label>
                </td>
                <td class="paramlist_value vtop">
<fieldset id="booking_price" class="radio" style="margin: 0 0 10px; padding: 0;">
<?php echo $display_price1; ?>
<?php echo $display_price2; ?>
<?php echo $display_price3; ?>
<?php echo $display_price4; ?>
<?php echo $display_price5; ?>
</fieldset>
                </td>
            </tr>            
        	<tr><td colspan="2"><h2><?php echo JText::_('COM_SEMINARMAN_ATTENDEE_DATA');?></h2></td></tr>
 <?php if ($this->params->get('enable_num_of_attendees')): ?>
            <tr>
                <td class="paramlist_key vtop">
                    <label for="jformattendees">* <?php echo JText::_('COM_SEMINARMAN_NR_ATTENDEES'); ?></label>
                </td>
                <td class="paramlist_value vtop">
                    <input title="<?php echo JText::_('COM_SEMINARMAN_NR_ATTENDEES') . '::' . JText::_('COM_SEMINARMAN_FILL_IN_DETAILS'); ?>" class="hasTip tipRight inputbox required" type="text" id="attendees" name="attendees" size="5" maxlength="3" value="<?php echo $this->escape($this->attendeedata->attendees); ?>" />
                </td>
            </tr>
<?php endif; ?>
            <tr>
                <td class="paramlist_key vtop">
                    <label for="jformsalutation">* <?php echo JText::_('COM_SEMINARMAN_SALUTATION'); ?>:</label>
                </td>
                <td class="paramlist_value vtop">
                    <?php echo $this->lists['salutation']; ?>
                </td>
            </tr>
            <tr>
                <td class="paramlist_key vtop">
                    <label for="title">&nbsp;&nbsp;<?php echo JText::_('COM_SEMINARMAN_TITLE'); ?>:</label>
                </td>
                <td class="paramlist_value vtop">
                    <input title="<?php echo JText::_('COM_SEMINARMAN_TITLE') . '::' . JText::_('COM_SEMINARMAN_FILL_IN_DETAILS'); ?>" class="hasTip tipRight inputbox" type="text" id="title" name="title" size="20" maxlength="250" value="<?php echo $this->escape($this->attendeedata->title); ?>" />
                </td>
            </tr>
            <tr>
                <td class="paramlist_key vtop">
                    <label for="jformfirstname">* <?php echo JText::_('COM_SEMINARMAN_FIRST_NAME'); ?>:</label>
                </td>
                <td class="paramlist_value vtop">
                    <input title="<?php echo JText::_('COM_SEMINARMAN_FIRST_NAME') . '::' . JText::_('COM_SEMINARMAN_FILL_IN_DETAILS'); ?>" class="hasTip tipRight inputbox required" type="text" id="first_name" name="first_name" size="50" maxlength="250" value="<?php echo $this->escape($this->attendeedata->first_name); ?>" />
                </td>
            </tr>
            <tr>
                <td class="paramlist_key vtop">
                    <label for="jformlastname">* <?php echo JText::_('COM_SEMINARMAN_LAST_NAME'); ?>:</label>
                </td>
                <td class="paramlist_value vtop">
                    <input title="<?php echo JText::_('COM_SEMINARMAN_LAST_NAME') . '::' . JText::_('COM_SEMINARMAN_FILL_IN_DETAILS'); ?>" class="hasTip tipRight inputbox required" type="text" id="last_name" name="last_name" size="50" maxlength="250" value="<?php echo $this->escape($this->attendeedata->last_name); ?>" />
                </td>
            </tr>
            <tr>
                <td class="paramlist_key vtop">
                    <label for="jformemail">* <?php echo JText::_('COM_SEMINARMAN_EMAIL'); ?>:</label>
                </td>
                <td class="paramlist_value vtop">
                    <input title="<?php echo JText::_('COM_SEMINARMAN_EMAIL') . '::' . JText::_('COM_SEMINARMAN_FILL_IN_DETAILS'); ?>" class="hasTip tipRight inputbox validate-email" type="text" id="cm_email" name="email" size="50" maxlength="100" value="<?php echo $this->escape($this->attendeedata->email); ?>" />
                </td>
            </tr>

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
                <td class="paramlist_key vtop" id="lblfield<?php echo $f->id;?>"><label for="lblfield<?php echo $f->id;?>"><?php if ($f->type != "checkboxtos") { if ($f->required == 1) echo '* '; echo JText::_($f->name) . ':'; } ?></label></td>
                <td class="paramlist_value vtop"><?php echo SeminarmanCustomfieldsLibrary::getFieldHTML($f , ''); ?></td>
            </tr>
            <?php
            }

            ?>
    <?php
    }

    ?>
    		<tr>
    			<td></td>
    			<td><p style="float: right;">* <?php echo JText::_('COM_SEMINARMAN_REQUIRED_VALUES'); ?></p></td>
    		</tr>
        </tbody>
    </table>

    <div>
        <?php if (!$this->params->get('enable_multiple_bookings_per_user') && ($this->attendeedata->id > 0) && (!$this->attendeedata->jusertype)){ ?>
        <button type="button" class="button validate" disabled="disabled">
        	<?php echo JText::_('COM_SEMINARMAN_ALREADY_BOOKED'); ?>
        </button>
        <?php }else{ ?>
        <button type="button" class="button validate" onclick="submitbuttonSeminarman('<?php echo $next_action; ?>')">
<?php
    if (($this->params->get('trigger_virtuemart') == 1) && !is_null($this->vmlink)) {
    	echo JText::_('COM_SEMINARMAN_BOOKING_IN_VM');
    } else {	
        echo JText::_('COM_SEMINARMAN_SUBMIT');
    }
?>
        </button>
        <?php } ?>
    </div>

    <input type="hidden" name="course_id" value="<?php echo $this->course->id;?>" />
    <input type="hidden" name="option" value="com_seminarman" />
    <input type="hidden" name="controller" value="application" />
    <input type="hidden" name="task" value="" />
    <?php

    echo JHTML::_('form.token');
    ?>
</form>