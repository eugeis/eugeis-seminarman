<form action="<?php echo $this->action ?>" method="post" name="adminForm" id="adminForm" class="form-validate"  enctype="multipart/form-data">
    <p><?php echo JText::_('COM_SEMINARMAN_LST_OF_SALES_PROSPECTS_DESC'); ?></p> 
    <table class="ccontentTable paramlist">
        <tbody>
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
                    <label for="title"><?php echo JText::_('COM_SEMINARMAN_TITLE'); ?>:</label>
                </td>
                <td class="paramlist_value vtop">
                    <input title="<?php echo JText::_('COM_SEMINARMAN_TITLE') . '::' . JText::_('COM_SEMINARMAN_FILL_IN_DETAILS'); ?>" class="hasTip tipRight inputbox" type="text" id="title" name="title" size="50" maxlength="250" value="<?php echo $this->escape($this->attendeedata->title); ?>" />
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
                <td class="paramlist_key vtop" id="lblfield<?php echo $f->id;?>"><label for="lblfield<?php echo $f->id;?>"><?php if ($f->required == 1) echo '* '; ?><?php echo JText::_($f->name);?>:</label></td>
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
        <?php if (($this->attendeedata->id > 0) && (!$this->attendeedata->jusertype)){ ?>
        <button type="button" class="button validate" disabled="disabled">
            <?php echo JText::_('COM_SEMINARMAN_ALREADY_ON_LIST');?>
        </button>
        <?php }else{ ?>
        <button type="button" class="button validate" onclick="submitbuttonSeminarman('save')">
            <?php echo JText::_('COM_SEMINARMAN_JOIN_LIST');?>
        </button>
        <?php } ?>
    </div>

    <input type="hidden" name="template_id" value="<?php echo $this->template->id;?>" />
    <input type="hidden" name="option" value="com_seminarman" />
    <input type="hidden" name="controller" value="salesprospect" />
    <input type="hidden" name="task" value="" />
    <?php

    echo JHTML::_('form.token');

    ?>
</form>