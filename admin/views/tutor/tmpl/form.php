<?php /**
 * @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
 * @website http://www.profinvent.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 **/
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');

$edit = JRequest::getVar('edit', true);
$text = !$edit ? JText::_('COM_SEMINARMAN_NEW') : JText::_('COM_SEMINARMAN_EDIT');
JToolBarHelper::title(JText::_('COM_SEMINARMAN_TUTOR') . ': <span class="small">[ ' . $text . ' ]</span>', 'tutors');
JToolBarHelper::apply();
JToolBarHelper::save();
if (!$edit)
    JToolBarHelper::cancel();
else
    JToolBarHelper::cancel('cancel', 'COM_SEMINARMAN_CLOSE');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
    var form = document.adminForm;
    var stripped = form.primary_phone.value.replace(/[\(\)\.\-\ ]/g, '');
	var bill_phone_stripped = form.bill_phone.value.replace(/[\(\)\.\-\ ]/g, '');
	var zip_stripped = form.zip.value.replace(/[\(\)\.\-\ ]/g, '');
	var bill_zip_stripped = form.bill_zip.value.replace(/[\(\)\.\-\ ]/g, '');

    	if (task == 'cancel') {
    		Joomla.submitform( task );
    		return;
    	}

			// do field validation
            if(form.title.value == '')
			{
				alert( "<?php echo JText::_('COM_SEMINARMAN_MISSING_DISPLAY_NAME', true); ?>" );

			}
			else if ( form.firstname.value == "" ) {
				alert( "<?php echo JText::_('COM_SEMINARMAN_MISSING_FIRST_NAME', true); ?>" );
			}
            
            else if(form.lastname.value == '')
			{
				alert( "<?php echo JText::_('COM_SEMINARMAN_MISSING_LAST_NAME', true); ?>" );

			}
			else if(form.salutation.value == '0')
			{
				alert( "<?php echo JText::_('COM_SEMINARMAN_MISSING_SALUTATION_NAME.', true); ?>" );

			}

			else {
				Joomla.submitform( task );
			}
	
	}

	function updatejuserform(){
	    if (document.getElementById("juserstate0").checked) {
	    	document.getElementById("juser_row0").style.display = 'none';
	    	document.getElementById("juser_row1").style.display = 'none';
	  	    document.getElementById("juser_row2").style.display = 'none';
	  	    document.getElementById("juser_row3").style.display = 'none';
	  	    document.getElementById("juser_row4").style.display = 'none'; 
	  	    document.getElementById("juser_row5").style.display = 'none';       
	    } else {
	    	document.getElementById("juser_row0").style.display = 'table-row';
	    	var sel = document.getElementById('juser_option');
	    	if (sel.options[sel.selectedIndex].value == '0') {		    	
	    	    document.getElementById("juser_row1").style.display = 'table-row';
	  	        document.getElementById("juser_row2").style.display = 'table-row';
	  	        document.getElementById("juser_row3").style.display = 'table-row';
	  	        document.getElementById("juser_row4").style.display = 'table-row';
	  	        document.getElementById("juser_row5").style.display = 'none';
	    	} else {
	    	    document.getElementById("juser_row1").style.display = 'none';
	  	        document.getElementById("juser_row2").style.display = 'none';
	  	        document.getElementById("juser_row3").style.display = 'none';
	  	        document.getElementById("juser_row4").style.display = 'none';
	  	        document.getElementById("juser_row5").style.display = 'table-row';
	    	}
	    }            
	}
</script>


<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_SEMINARMAN_QUALIFIED_FOR_TEMPLATES'); ?></legend>
				<?php echo $this->lists['templates_add'];?>
				<input type="text" name="template_prio" id="template_prio" value="<?php echo JText::_('COM_SEMINARMAN_PRIORITY'); ?>" style="color: gray;" class="text_area" onfocus="this.value = ''; this.style.color = '';" onblur="if (this.value == '') { this.value = '<?php echo JText::_('COM_SEMINARMAN_PRIORITY'); ?>'; this.style.color = 'gray'; }"/>
<?php $isnew = ($this->tutor->id == 0); ?>
<?php if ($isnew): ?>
				<button disabled="disabled"><?php echo JText::_('COM_SEMINARMAN_SAVE_FIRST'); ?></button>
<?php else: ?>
				<button onclick="javascript:Joomla.submitbutton('apply')"><?php echo JText::_('COM_SEMINARMAN_ADD') .' / '. JText::_('COM_SEMINARMAN_REMOVE'); ?></button>
<?php endif; ?>
				<table class="adminlist cellspace1">
					<thead>
						<tr>
							<th width="25%"><?php echo JText::_('COM_SEMINARMAN_NAME'); ?></th>
							<th width="55%"><?php echo JText::_('COM_SEMINARMAN_DISPLAY_NAME'); ?></th>
							<th width="10%"><?php echo JText::_('COM_SEMINARMAN_PRIO'); ?></th>
							<th width="10%"><?php echo JText::_('COM_SEMINARMAN_REMOVE'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php $k=0; foreach ($this->lists['qualified_templates'] as $t) : ?>
						<tr class="<?php echo "row$k"; ?>">
							<td><a href="<?php echo JRoute::_('index.php?option=com_seminarman&controller=templates&task=edit&cid[]=' . $t->id); ?> " target="_blank" style="font-size: 1em;"><?php echo $t->name; ?></a></td>
							<td><?php echo $t->title; ?></td>
							<td><?php echo $t->priority; ?></td>
							<td><input type="checkbox" name="template_remove[]" value="<?php echo $t->id; ?>" /></td>
						</tr>
					<?php $k = 1 - $k; endforeach; ?>
					</tbody>
				</table>
		</fieldset>
	</div>
	
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_SEMINARMAN_GENERAL'); ?></legend>
			<table class="admintable">
				<tr>
					<td class="key"><label for="title"><?php echo JText::_('COM_SEMINARMAN_DISPLAY_NAME'); ?> * :</label></td>
					<td><input class="text_area" type="text" name="title" id="title" size="32" maxlength="100" value="<?php echo $this->tutor->title; ?>" /></td>
				</tr>
				<tr>
					<td class="key"><label for="alias"><?php echo JText::_('COM_SEMINARMAN_ALIAS'); ?>:</label></td>
					<td><input class="text_area" type="text" name="alias" id="alias" size="32" maxlength="100" value="<?php echo $this->tutor->alias; ?>" /></td>
				</tr>
				<tr>
					<td class="key"><label for="ordering"><?php echo JText::_('COM_SEMINARMAN_ORDERING'); ?>:</label></td>
					<td><?php echo $this->lists['ordering']; ?></td>
				</tr>
			</table>
		</fieldset>
	</div>
	
	<div class="width-40 fltrt">
		<fieldset class="adminform">
		<legend><?php echo JText::_('COM_SEMINARMAN_IMAGE'); ?></legend>
		<?php
		if ($this->tutor->logofilename != '') { ?>
			<img src="<?php $params = JComponentHelper::getParams( 'com_seminarman' ); echo JURI::root().$params->get('image_path', 'images'). '/' . $this->tutor->logofilename; ?>">
		<?php } ?>
		
		<input class="text_area" type="file" name="logofilename" id="logofilename" size="32" maxlength="250" value=""/>
	</fieldset>
	</div>
	
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_SEMINARMAN_USER_INFORMATION'); ?></legend>

			<table class="admintable">
			<tr>
				<td class="key"><label for="name"><?php echo JText::_('COM_SEMINARMAN_FIRST_NAME'); ?> * :</label></td>
				<td><input class="inputbox" type="text" name="firstname" id="firstname" size="60" maxlength="100" value="<?php echo $this->tutor->firstname; ?>" /></td>
			</tr>

			<tr>
				<td class="key"><label for="name"><?php echo JText::_('COM_SEMINARMAN_LAST_NAME'); ?> * :</label></td>
				<td><input class="inputbox" type="text" name="lastname" id="lastname" size="60" maxlength="100" value="<?php echo $this->tutor->lastname; ?>" /></td>
			</tr>

			<tr>
				<td class="key"><label for="name"><?php echo JText::_('COM_SEMINARMAN_SALUTATION'); ?> * :</label></td>
				<td><?php echo $this->lists['salutation']; ?></td>
			</tr>

			<tr>
				<td class="key"><label for="name"><?php echo JText::_('COM_SEMINARMAN_OTHER_TITLE'); ?>:</label></td>
				<td><input class="inputbox" type="text" name="other_title" id="other_title" size="30" maxlength="30" value="<?php echo $this->tutor->other_title; ?>" /></td>
			</tr>
		    </table>
		</fieldset>
	</div>

	<div class="width-40 fltrt">
		<fieldset class="adminform">
		    <legend><?php echo JText::_('COM_SEMINARMAN_JOOMLA_ACC'); ?></legend>
		    <table class="admintable">
		        <tr>
		            <td class="key"><label for="name"><?php echo ''; ?></label></td>
		            <td><fieldset class="radio"><?php echo $this->lists['juserstate']; ?></fieldset></td>
		        </tr>
                
                <tr id="juser_row0" style="display:table-row;">
				    <td class="key"><label for="name"><?php echo JText::_('Option'); ?></label></td>
				    <td><?php echo $this->lists['method']; ?></td>
			    </tr>
                		    
			    <tr id="juser_row1" style="display:table-row;">
				    <td class="key"><label for="name"><?php echo JText::_('COM_SEMINARMAN_USERNAME'); ?></label></td>
				    <td><?php echo $this->lists['jusername']; ?></td>
			    </tr>
			    
			    <tr id="juser_row2" style="display:table-row;">
				    <td class="key"><label for="name"><?php echo JText::_('COM_SEMINARMAN_PASSWORD'); ?></label></td>
				    <td><?php echo $this->lists['jpassword1']; ?></td>
			    </tr>

			    <tr id="juser_row3" style="display:table-row;">
				    <td class="key"><label for="name"><?php echo JText::_('COM_SEMINARMAN_PASSWORD_REPEAT'); ?></label></td>
				    <td><?php echo $this->lists['jpassword2']; ?></td>
			    </tr>
			
			    <tr id="juser_row4" style="display:table-row;">
				    <td class="key"><label for="name"><?php echo JText::_('COM_SEMINARMAN_EMAIL'); ?></label></td>
				    <td><?php echo $this->lists['jemail']; ?></td>
			    </tr>
			    
			    <tr id="juser_row5" style="display:table-row;">
				    <td class="key"><label for="name"><?php echo JText::_('COM_SEMINARMAN_USERNAME'); ?>:</label></td>
				    <td><?php echo $this->lists['username']; ?></td>
			    </tr>			    
			    
			</table>
			
	</fieldset>
	</div>

<script type="text/javascript" language="javascript">
    if (document.getElementById("juserstate0").checked) {
    	document.getElementById("juser_row0").style.display = 'none';
    	document.getElementById("juser_row1").style.display = 'none';
  	    document.getElementById("juser_row2").style.display = 'none';
  	    document.getElementById("juser_row3").style.display = 'none';
  	    document.getElementById("juser_row4").style.display = 'none'; 
  	    document.getElementById("juser_row5").style.display = 'none';       
    } else {
      if (document.getElementById("juserstate0").disabled) {
    	  document.getElementById("juser_row0").style.display = 'none';
    	  document.getElementById("juser_row2").style.display = 'none';
    	  document.getElementById("juser_row3").style.display = 'none';
    	  document.getElementById("juser_row5").style.display = 'none';
      } else {
    	  updatejuserform();
      }
    }
</script>

	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_SEMINARMAN_COMPANY_INFORMATION'); ?></legend>
			<table class="admintable">
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_SEMINARMAN_COMPANY_NAME'); ?>:</label></td>
					<td><input class="inputbox" type="text" name="comp_name" id="comp_name" size="60" maxlength="100" value="<?php echo $this->tutor->comp_name; ?>" /></td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_SEMINARMAN_PRIMARY_PHONE'); ?>:</label></td>
					<td><input class="inputbox" type="text" name="primary_phone" id="primary_phone" size="60" maxlength="100" value="<?php echo $this->tutor->primary_phone; ?>" /></td>
				</tr>
				
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_SEMINARMAN_FAX_NUMBER'); ?>:</label></td>
					<td><input class="inputbox" type="text" name="fax_number" id="fax_number" size="60" maxlength="100" value="<?php echo $this->tutor->fax_number; ?>" /></td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_SEMINARMAN_WEBSITE'); ?>:</label></td>
					<td><input class="inputbox" type="text" name="url" id="url" size="60" maxlength="100" value="<?php echo $this->tutor->url; ?>" /></td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_SEMINARMAN_STREET'); ?>:</label></td>
					<td><input class="inputbox" type="text" name="street" id="street" size="60" maxlength="100" value="<?php echo $this->tutor->street; ?>" /></td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_SEMINARMAN_ZIP'); ?>:</label></td>
					<td><input class="inputbox" type="text" name="zip" id="zip" size="60" maxlength="255" value="<?php echo $this->tutor->zip; ?>" /></td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_SEMINARMAN_CITY'); ?>:</label></td>
					<td><input class="inputbox" type="text" name="city" id="city" size="60" maxlength="255" value="<?php echo $this->tutor->city; ?>" /></td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_SEMINARMAN_STATE'); ?>:</label></td>
					<td><input class="inputbox" type="text" name="state" id="state" size="60" maxlength="255" value="<?php echo $this->tutor->state; ?>" /></td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_SEMINARMAN_COUNTRY'); ?>:</label></td>
					<td><?php echo $this->lists['country']; ?></td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_SEMINARMAN_COMPANY_TYPE'); ?>:</label></td>
					<td><?php echo $this->lists['company_type']; ?></td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_SEMINARMAN_INDUSTRY'); ?>:</label></td>
					<td><?php echo $this->lists['industry']; ?></td>
				</tr>
			</table>
		</fieldset>
	</div>
	
	<div class="width-40 fltrt">
		<fieldset class="adminform">
		    <legend><?php echo JText::_('COM_SEMINARMAN_CUSTOM_FIELDS'); ?></legend>
		    <table class="admintable">
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
		    </table>		
	    </fieldset>
	</div>
	
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_SEMINARMAN_BILLING_ADDRESS'); ?></legend>
			<a href="javascript:void(0);" onClick="copyBillingDetails()"><?php echo JText:: _('COM_SEMINARMAN_COPY_TEXT'); ?></a>
			<table class="admintable">
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_SEMINARMAN_ADDRESS'); ?>:	</label></td>
					<td><input class="inputbox" type="text" name="bill_addr" id="bill_addr" size="60" maxlength="255" value="<?php echo $this->tutor->bill_addr; ?>" /></td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_SEMINARMAN_ADDRESS_CONT'); ?>:</label></td>
					<td><input class="inputbox" type="text" name="bill_addr_cont" id="bill_addr_cont" size="60" maxlength="255" value="<?php echo $this->tutor->bill_addr_cont; ?>" /></td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_SEMINARMAN_ZIP'); ?>:</label></td>
					<td><input class="inputbox" type="text" name="bill_zip" id="bill_zip" size="60" maxlength="255" value="<?php echo $this->tutor->bill_zip; ?>" /></td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_SEMINARMAN_CITY'); ?>:</label></td>
					<td><input class="inputbox" type="text" name="bill_city" id="bill_city" size="60" maxlength="255" value="<?php echo $this->tutor->bill_city; ?>" /></td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_SEMINARMAN_STATE'); ?>:</label></td>
					<td><input class="inputbox" type="text" name="bill_state" id="bill_state" size="60" maxlength="255" value="<?php echo $this->tutor->bill_state; ?>" /></td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_SEMINARMAN_COUNTRY'); ?>:</label></td>
					<td><?php echo $this->lists['billing_country']; ?></td></tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_SEMINARMAN_PRIMARY_PHONE'); ?>:</label></td>
					<td><input class="inputbox" type="text" name="bill_phone" id="bill_phone" size="60" maxlength="255" value="<?php echo $this->tutor->bill_phone; ?>" /></td>
				</tr>
		    </table>
		</fieldset>
	</div>
	
	<div class="width-40 fltrt">
		<fieldset class="adminform">
		    <legend><?php echo "Advanced Options"; ?></legend>
		    <table class="admintable">
                 <?php echo $this->lists['invm'];?>
		    </table>		
	    </fieldset>
	</div>

	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_SEMINARMAN_COMPANY_DESC'); ?></legend>
			<table class="admintable">
				<tr>
					<td><?php $editor = JFactory::getEditor(); echo $editor->display('description', $this->tutor->description, '840', '200', '90', '15', false); ?></td>
				</tr>
			</table>
		</fieldset>
	</div>

	<input type="hidden" name="published" value="1" />
	<input type="hidden" name="option" value="com_seminarman" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="controller" value="tutor" />
	<input type="hidden" name="cid" value="<?php echo $this->tutor->id; ?>" />
	<input type="hidden" name="id" value="<?php echo $this->tutor->id; ?>" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="logotodelete" value="<?php echo $this->tutor->logofilename; ?>" />
	<?php echo $this->lists['juid']; ?>
	<?php echo JHTML::_('form.token'); ?>
</form>