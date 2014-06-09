<?php 
$html_prices = JHTMLSeminarman::get_price_view($this->course->id, '', $this->vmlink);

if ($this->params->get('enable_payment_overview') == 1) {
	if (($this->params->get('trigger_virtuemart') == 1) && !is_null($this->vmlink)) {
		$next_action = 'save';
	} else {
	    $next_action = 'cart';
	}
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
<?php echo $html_prices; ?>
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
                    <input id='ccb' title="<?php echo JText::_('COM_SEMINARMAN_NR_ATTENDEES') . '::' . JText::_('COM_SEMINARMAN_FILL_IN_DETAILS'); ?>" class="hasTip tipRight inputbox required validate-numeric" type="text" id="attendees" name="attendees" size="5" maxlength="3" value="<?php echo $this->escape($this->attendeedata->attendees); ?>" />
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
    <tr><td colspan="2"><h2><?php echo JText::_($name);?></h2></td></tr>
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
                <td class="paramlist_value vtop">
                    <?php 
                        if (($f->type == "checkboxtos") && ($this->params->get('enable_payment_overview') == 1)) {
                        	if (($this->params->get('trigger_virtuemart') == 1) && !is_null($this->vmlink)) {
                        		echo SeminarmanCustomfieldsLibrary::getFieldHTML($f , '');
                        	}
                        } else { 
                        	echo SeminarmanCustomfieldsLibrary::getFieldHTML($f , ''); 
                        }
                    ?>
                </td>
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
        <button type="button" class="button validate" onclick="
        if (document.getElementById('cca') && document.getElementById('ccb')) { 
            if (parseInt(document.getElementById('cca').innerHTML) < parseInt(document.getElementById('ccb').value)) {
        	    alert( '<?php echo JText::_( 'COM_SEMINARMAN_BOOKING_GREATER_FREESPACES2' ) ?>' + ' (' + document.getElementById('ccb').value + ') ' + '<?php echo JText::_( 'COM_SEMINARMAN_BOOKING_GREATER_FREESPACES3' ) ?>' + ' (' + document.getElementById('cca').innerHTML + ').' );
            } else {
        	    submitbuttonSeminarman('<?php echo $next_action; ?>')
            }
        } 
        else {
        	submitbuttonSeminarman('<?php echo $next_action; ?>')
        }
        ">
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
