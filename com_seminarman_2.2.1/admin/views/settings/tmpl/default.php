<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');


jimport('joomla.html.pane');


$pane = JPane::getInstance('tabs', array('startOffset' => 0));
echo $pane->startPane('pane');
echo $pane->startPanel(JText::_('COM_SEMINARMAN_MAIN_SETTINGS'), 'panel1');

?>

<script type="text/javascript">
function setDefaultEmail(id)
{
	var f = document.templateForm;
	f.id.value = id;
	Joomla.submitform('setDefault', f);
}

function setDefaultPdf(id, type)
{
	var f = document.pdfTemplateForm;
	f.id.value = id;
	Joomla.submitform('setDefault', f);
}
</script>
<table class="adminlist">
	<thead>
		<tr>
			<th class="pix10"></th>
			<th class="proc98 left"><?php echo JText::_('COM_SEMINARMAN_MAIN_SETTINGS'); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><img SRC="../administrator/components/com_seminarman/assets/images/icon-16-config.png"></td>
			<td><a href="<?php echo JRoute::_('index.php?option=com_seminarman&view=editfields'); ?>"><?php echo JText::_('COM_SEMINARMAN_CUSTOM_FIELDS'); ?></a></td>
		</tr>
		<tr>
			<td><img SRC="../administrator/components/com_seminarman/assets/images/icon-16-config.png"></td>
			<td><a href="<?php echo JRoute::_('index.php?option=com_seminarman&view=editcss'); ?>"><?php echo JText::_('COM_SEMINARMAN_EDIT_CSS'); ?></a></td>
		</tr>
	</tbody>
</table>

<?php
echo $pane->endPanel();
echo $pane->startPanel(JText::_('COM_SEMINARMAN_REFERENCE_TABLES'), 'panel2');
?>

<table class="adminlist">
	<thead>
		<tr>
			<th class="pix10"><img SRC="../administrator/components/com_seminarman/assets/images/icon-16-dbtable.png" ALT="Table"></th>
			<th class="proc98 left"><?php echo JText::_('COM_SEMINARMAN_REFERENCE_TABLES'); ?> </th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>1.</td>
			<td><a href="<?php echo JRoute::_('index.php?option=com_seminarman&view=editvirtualtables'); ?>"><?php echo JText::_('COM_SEMINARMAN_XML_TABLES'); ?></a></td>
		</tr>
		<tr>
			<td>2.</td>
			<td><a href="<?php echo JRoute::_('index.php?option=com_seminarman&view=countries'); ?>"><?php echo JText::_('COM_SEMINARMAN_COUNTRY'); ?></a></td>
		</tr>
		<tr>
			<td>3.</td>
			<td><a href="<?php echo JRoute::_('index.php?option=com_seminarman&view=company_types'); ?>"><?php echo JText::_('COM_SEMINARMAN_COMPANY_TYPES'); ?></a></td>
		</tr>
		<tr>
			<td>4.</td>
			<td><a href="<?php echo JRoute::_('index.php?option=com_seminarman&view=atgroups'); ?>"><?php echo JText::_('COM_SEMINARMAN_ATTENDEE_GROUPS'); ?></a></td>
		</tr>
		<tr>
			<td>5.</td>
			<td><a href="<?php echo JRoute::_('index.php?option=com_seminarman&view=experience_levels'); ?>"><?php echo JText::_('COM_SEMINARMAN_EXPERIENCE_LEVELS'); ?></a></td>
		</tr>
	</tbody>
</table>

<?php
echo $pane->endPanel();
echo $pane->startPanel(JText::_('COM_SEMINARMAN_DEFAULT_EMAIL_TEMPLATES'), 'panel3');
?>

<form action="index.php" id="templateForm" name="templateForm" method="post">
	<input type="hidden" name="option" value="com_seminarman" />
	<input type="hidden" name="controller" value="emailtemplate" />
	<input type="hidden" name="task" value="remove" />
	<input type="hidden" name="id" value="" />
	
	<?php echo JHTML::_('form.token');?>

	<table class="adminlist">
	<thead>
		<tr>
			<th class="pix10 left"><?php echo JText::_('COM_SEMINARMAN_NUM'); ?></th>
			<th class="proc30 left"><?php echo JText::_('COM_SEMINARMAN_DEFAULT_EMAIL_TEMPLATES'); ?></th>
			<th class="left"><?php echo JText::_('COM_SEMINARMAN_DEFAULT'); ?></th>
			<th class="left"><?php echo JText::_('COM_SEMINARMAN_USE_FOR'); ?></th>
			<th class="pix5"><img style="width:24px;height:24px;cursor:pointer;" src="../administrator/components/com_seminarman/assets/images/trash.png" onclick="document.templateForm.submit();" /></th>
		</tr>
	</thead>
	<tbody>
<?php
$i = 0;
foreach($this->emailTemplates as $etmpl):?>
    <tr>
    	<td><?php echo $i++.'.';?></td>
        <td><a href="<?php echo JRoute::_('index.php?option=com_seminarman&view=emailtemplate&layout=default&id='.$etmpl->id);?>"><?php echo $etmpl->title;?></a></td>
        <td>
        <a class="jgrid" href="javascript:void(0);" onclick="javascript:setDefaultEmail(<?php echo $etmpl->id; ?>)">
        	<span class="state <?php echo $etmpl->isdefault ? 'default' : 'notdefault'; ?>">
        		<span class="text">Standard</span>
        	</span>
        </a>
        </td>
        <td><?php echo $etmpl->templatefor == 0 ? JText::_('COM_SEMINARMAN_BOOKING_CONFIRMATION') : JText::_('COM_SEMINARMAN_SALES_PROSPECT_NOTIFICATION'); ?></td>
        <td><input type="checkbox" name="cid[]" value="<?php echo $etmpl->id; ?>" /></td>
    </tr>
<?php endforeach; ?>
	<tr>
		<td colspan="5"><a href="<?php echo JRoute::_('index.php?option=com_seminarman&view=emailtemplate&layout=default');?>"><?php echo JText::_('COM_SEMINARMAN_ADD_NEW_TEMPLATE');?></a></td>
    </tr>
    </tbody>
	</table>
</form>

<?php
echo $pane->endPanel();
echo $pane->startPanel(JText::_('COM_SEMINARMAN_PDF_TEMPLATES'), 'panel4');
?>

<form action="index.php" id="pdfTemplateForm" name="pdfTemplateForm" method="post">
	<input type="hidden" name="option" value="com_seminarman" />
	<input type="hidden" name="controller" value="pdftemplate" />
	<input type="hidden" name="task" value="remove" />
	<input type="hidden" name="id" value="" />
	
	<?php echo JHTML::_('form.token');?>

	<table class="adminlist">
	<thead>
		<tr>
			<th class="pix10 left"><?php echo JText::_('COM_SEMINARMAN_NUM'); ?></th>
			<th class="proc30 left"><?php echo JText::_('COM_SEMINARMAN_PDF_TEMPLATE'); ?></th>
			<th class="left"><?php echo JText::_('COM_SEMINARMAN_DEFAULT'); ?></th>
			<th class="left"><?php echo JText::_('COM_SEMINARMAN_USE_FOR'); ?></th>
			<th class="pix5"><img style="width:24px;height:24px;cursor:pointer;" src="../administrator/components/com_seminarman/assets/images/trash.png" onclick="document.pdfTemplateForm.submit();" /></th>
		</tr>
	</thead>
	<tbody>
<?php
$i = 0;
foreach($this->pdfTemplates as $ptmpl):?>
    <tr>
    	<td><?php echo $i++.'.';?></td>
        <td><a href="<?php echo JRoute::_('index.php?option=com_seminarman&view=pdftemplate&layout=default&id='.$ptmpl->id);?>"><?php echo $ptmpl->name;?></a></td>
        <td>
	        <a class="jgrid" href="javascript:void(0);" onclick="javascript:setDefaultPdf(<?php echo $ptmpl->id; ?>, type=0)">
        		<span class="state <?php echo $ptmpl->isdefault ? 'default' : 'notdefault'; ?>"><span class="text">Standard</span></span>
	        </a>
        </td>
        <td><?php echo $ptmpl->templateforStr; ?></td>
        <td><input type="checkbox" name="cid[]" value="<?php echo $ptmpl->id; ?>" /></td>
    </tr>
<?php endforeach; ?>
	<tr>
		<td colspan="5"><a href="<?php echo JRoute::_('index.php?option=com_seminarman&view=pdftemplate');?>"><?php echo JText::_('COM_SEMINARMAN_ADD_NEW_TEMPLATE');?></a></td>
    </tr>
    </tbody>
	</table>
</form>

<?php
echo $pane->endPanel();
echo $pane->startPanel(JText::_('COM_SEMINARMAN_IMPORT_EXPORT'), 'panel5');
?>

<table class="adminlist">
	<thead>
		<tr>
			<th class="pix10"></th>
			<th class="proc98 left"><?php echo JText::_('COM_SEMINARMAN_IMPORT_EXPORT'); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><img SRC="../administrator/components/com_seminarman/assets/images/icon-16-config.png" ></td>
			<td><a href="<?php echo JRoute::_('index.php?option=com_seminarman&view=importexport'); ?>"><?php echo JText::_('COM_SEMINARMAN_CSV_EXPORT'); ?></a></td>
		</tr>
	</tbody>
</table>

<?php
echo $pane->endPanel();
echo $pane->startPanel(JText::_('COM_SEMINARMAN_PRICE_GROUPS'), 'panel6');
?>
<?php JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html'); ?>
<style type="text/css">
  td.preisgruppe ul {
      list-style: none;
  }

  td.preisgruppe ul li label{
      clear: none;
      margin: 3px 0 0 5px;
  }
  
  td.preisgruppe ul li input{
      clear: left;
  }    
</style>
<form action="index.php" method="post" name="adminForm">
<table class="adminlist">
	<thead>
		<tr>
			<th class="pix30"></th>
			<th class="proc98" style="width: 50%;"><?php echo JText::_('COM_SEMINARMAN_ASSIGNED_GROUPS'); ?></th>
			<th class="proc98" style="width: 20%;"><?php echo JText::_('COM_SEMINARMAN_REG_GROUP'); ?></th>
		    <th class="pix30"><?php echo JText::_('COM_SEMINARMAN_PRICE_MOD'); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
		    <td><?php echo JText::_('COM_SEMINARMAN_PRICE_GROUP_2'); ?></td>
			<td class="preisgruppe">
<?php echo JHtml::_('access.usergroups', 'sec_price', json_decode($this->priceG2->jm_groups), true); ?>			
			</td>
			<td>
<label><?php echo JText::_('COM_SEMINARMAN_REG_GROUP_2_DESC'); ?></label>
<!--   <select name="price2_ug" id="price2_ug" style="clear: left;">
	<option>Registriert</option>
	<option>Autor</option>
	<option>Editor</option>
	<option>Publisher</option>
	<option>... ...</option>
</select>	 -->
<?php echo $this->lists['usergroups2']; ?>	
			</td>
			<td>
<label><?php echo JText::_('COM_SEMINARMAN_PRICE_GROUP_TITLE'); ?></label>
<input style="margin-bottom: 15px; clear: left;" type="text" value="<?php echo $this->priceG2->title; ?>" maxlength="255" size="20" name="price2_title" id="price2_title" class="inputbox" style="clear: left;"/>
<br><br>
<label><?php echo JText::_('COM_SEMINARMAN_PRICE_MOD_OPT'); ?></label>			
<select name="price2_mathop" id="price2_mathop" style="clear: left;">
	<option <?php if($this->priceG2->calc_mathop == '+') echo 'selected'; ?> value="+">+</option>
	<option <?php if($this->priceG2->calc_mathop == '-') echo 'selected'; ?> value="-">-</option>
	<option <?php if($this->priceG2->calc_mathop == '+%') echo 'selected'; ?> value="+%">+%</option>
	<option <?php if($this->priceG2->calc_mathop == '-%') echo 'selected'; ?> value="-%">-%</option>
</select>
<br><br>
<label><?php echo JText::_('COM_SEMINARMAN_PRICE_MOD_VALUE'); ?></label>
<input type="text" value="<?php echo $this->priceG2->calc_value ?>" maxlength="255" size="20" name="price2_value" id="price2_value" class="inputbox" style="clear: left;"/>			
			</td>
		</tr>
		<tr>
		    <td style="background: #eee;"><?php echo JText::_('COM_SEMINARMAN_PRICE_GROUP_3'); ?></td>
			<td class="preisgruppe" style="background: #eee;">
<?php echo JHtml::_('access.usergroups', 'thr_price', json_decode($this->priceG3->jm_groups), true); ?>			
			</td>
			<td style="background: #eee;">
<label><?php echo JText::_('COM_SEMINARMAN_REG_GROUP_3_DESC'); ?></label>
<!--   <select name="price2_ug" id="price2_ug" style="clear: left;">
	<option>Registriert</option>
	<option>Autor</option>
	<option>Editor</option>
	<option>Publisher</option>
	<option>... ...</option>
</select>	 -->
<?php echo $this->lists['usergroups3']; ?>			
			</td>
			<td style="background: #eee;">
<label><?php echo JText::_('COM_SEMINARMAN_PRICE_GROUP_TITLE'); ?></label>
<input style="margin-bottom: 15px; clear: left;" type="text" value="<?php echo $this->priceG3->title; ?>" maxlength="255" size="20" name="price3_title" id="price3_title" class="inputbox" style="clear: left;"/>
<br><br>
<label><?php echo JText::_('COM_SEMINARMAN_PRICE_MOD_OPT'); ?></label>
<select name="price3_mathop" id="price3_mathop" style="clear: left;">
	<option <?php if($this->priceG3->calc_mathop == '+') echo 'selected'; ?> value="+">+</option>
	<option <?php if($this->priceG3->calc_mathop == '-') echo 'selected'; ?> value="-">-</option>
	<option <?php if($this->priceG3->calc_mathop == '+%') echo 'selected'; ?> value="+%">+%</option>
	<option <?php if($this->priceG3->calc_mathop == '-%') echo 'selected'; ?> value="-%">-%</option>
</select>
<br><br>
<label><?php echo JText::_('COM_SEMINARMAN_PRICE_MOD_VALUE'); ?></label>
<input type="text" value="<?php echo $this->priceG3->calc_value; ?>" maxlength="255" size="20" name="price3_value" id="price3_value" class="inputbox" style="clear: left;"/>			
			</td>
		</tr>
		<tr>
		    <td><?php echo JText::_('COM_SEMINARMAN_PRICE_GROUP_4'); ?></td>
			<td class="preisgruppe">
<?php echo JHtml::_('access.usergroups', 'fou_price', json_decode($this->priceG4->jm_groups), true); ?>			
			</td>
			<td>
<label><?php echo JText::_('COM_SEMINARMAN_REG_GROUP_4_DESC'); ?></label>
<?php echo $this->lists['usergroups4']; ?>			
			</td>
			<td>
<label><?php echo JText::_('COM_SEMINARMAN_PRICE_GROUP_TITLE'); ?></label>
<input style="margin-bottom: 15px; clear: left;" type="text" value="<?php echo $this->priceG4->title; ?>" maxlength="255" size="20" name="price4_title" id="price4_title" class="inputbox" style="clear: left;"/>
<br><br>
<label><?php echo JText::_('COM_SEMINARMAN_PRICE_MOD_OPT'); ?></label>
<select name="price4_mathop" id="price4_mathop" style="clear: left;">
	<option <?php if($this->priceG4->calc_mathop == '+') echo 'selected'; ?> value="+">+</option>
	<option <?php if($this->priceG4->calc_mathop == '-') echo 'selected'; ?> value="-">-</option>
	<option <?php if($this->priceG4->calc_mathop == '+%') echo 'selected'; ?> value="+%">+%</option>
	<option <?php if($this->priceG4->calc_mathop == '-%') echo 'selected'; ?> value="-%">-%</option>
</select>
<br><br>
<label><?php echo JText::_('COM_SEMINARMAN_PRICE_MOD_VALUE'); ?></label>
<input type="text" value="<?php echo $this->priceG4->calc_value; ?>" maxlength="255" size="20" name="price4_value" id="price4_value" class="inputbox" style="clear: left;"/>			
			</td>
		</tr>
		<tr>
		    <td style="background: #eee;"><?php echo JText::_('COM_SEMINARMAN_PRICE_GROUP_5'); ?></td>
			<td class="preisgruppe" style="background: #eee;">
<?php echo JHtml::_('access.usergroups', 'fif_price', json_decode($this->priceG5->jm_groups), true); ?>			
			</td>
			<td style="background: #eee;">
<label><?php echo JText::_('COM_SEMINARMAN_REG_GROUP_5_DESC'); ?></label>
<?php echo $this->lists['usergroups5']; ?>			
			</td>
			<td style="background: #eee;">
<label><?php echo JText::_('COM_SEMINARMAN_PRICE_GROUP_TITLE'); ?></label>
<input style="margin-bottom: 15px; clear: left;" type="text" value="<?php echo $this->priceG5->title; ?>" maxlength="255" size="20" name="price5_title" id="price5_title" class="inputbox" style="clear: left;"/>
<br><br>
<label><?php echo JText::_('COM_SEMINARMAN_PRICE_MOD_OPT'); ?></label>
<select name="price5_mathop" id="price5_mathop" style="clear: left;">
	<option <?php if($this->priceG5->calc_mathop == '+') echo 'selected'; ?> value="+">+</option>
	<option <?php if($this->priceG5->calc_mathop == '-') echo 'selected'; ?> value="-">-</option>
	<option <?php if($this->priceG5->calc_mathop == '+%') echo 'selected'; ?> value="+%">+%</option>
	<option <?php if($this->priceG5->calc_mathop == '-%') echo 'selected'; ?> value="-%">-%</option>
</select>
<br><br>
<label><?php echo JText::_('COM_SEMINARMAN_PRICE_MOD_VALUE'); ?></label>
<input type="text" value="<?php echo $this->priceG5->calc_value; ?>" maxlength="255" size="20" name="price5_value" id="price5_value" class="inputbox" style="clear: left;"/>			
			</td>
		</tr>
		<tr><td colspan="4" align="center"><input type="submit" value="<?php echo JText::_('COM_SEMINARMAN_SAVE');?>" style="float: none;" /></td></tr>
	</tbody>
</table>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_seminarman" />
	<input type="hidden" name="controller" value="settings" />
	<input type="hidden" name="view" value="settings" />
	<input type="hidden" name="task" value="savePricegroups" />
</form>
<?php
echo $pane->endPanel();
echo $pane->startPanel(JText::_('COM_SEMINARMAN_STATUS_TAB'), 'panel7');
?>
<script type="text/javascript">
    function setManagerGrp() {
    	document.forms["formCreateManagerGrp"].submit();
    }
    function setTutorGrp() {
    	document.forms["formCreateTutorGrp"].submit();
    }
    function setDBSchema() {
    	document.forms["formFixDB"].submit();
    }
    function setGrpRights() {
    	document.forms["formSetRights"].submit();
    }
</script>
<table class="adminlist">
	<thead>
		<tr>
			<th class="pix10" style="width: 3%;"></th>
			<th class="proc98" style="width: 40%;"><?php echo JText::_('COM_SEMINARMAN_OBJECT'); ?></th>
			<th class="proc98" style="width: 45%;"><?php echo JText::_('COM_SEMINARMAN_STATUS'); ?></th>
		    <th class="pix30" style="width: 12%;"></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>1.</td>
			<td align="center"><?php echo JText::_('COM_SEMINARMAN_GROUP_MANAGER'); ?></td>
			<td align="center">
			<?php 
			    if (!empty($this->ManagerGrp)) {
			    	echo '<font color="green">' . JText::_('COM_SEMINARMAN_STATUS_AVAILABLE') . '</font> (' . $this->ManagerGrp . ')';
			    } else {
			    	echo '<font color="red">' . JText::_('COM_SEMINARMAN_STATUS_NOT_AVAILABLE') . '</font>';
			    }
			?>
			</td>
		    <td align="center">
		    <?php 
			    if (empty($this->ManagerGrp)) {
			    	echo '<button onclick="setManagerGrp()">Create Now!</button>';
			    }
			?>
		    </td>
		</tr>
		<tr>
			<td>2.</td>
			<td align="center"><?php echo JText::_('COM_SEMINARMAN_GROUP_TUTOR'); ?></td>
			<td align="center">
			<?php 
			    if (!empty($this->TutorGrp)) {
			    	echo '<font color="green">' . JText::_('COM_SEMINARMAN_STATUS_AVAILABLE') . '</font> (' . $this->TutorGrp . ')';
			    } else {
			    	echo '<font color="red">' . JText::_('COM_SEMINARMAN_STATUS_NOT_AVAILABLE') . '</font>';
			    }
			?>			
			</td>
		    <td align="center">
		    <?php 
			    if (empty($this->TutorGrp)) {
			    	echo '<button onclick="setTutorGrp()">Create Now!</button>';
			    }
			?>		    
		    </td>
		</tr>
		<tr>
			<td>3.</td>
			<td align="center"><?php echo JText::_('COM_SEMINARMAN_DATABASE_SCHEMA'); ?></td>
			<td align="center">
			<?php 
			    if ($this->dbstati == 1) {
			    	echo '<font color="green">' . JText::_('COM_SEMINARMAN_STATUS_OK') . '</font>';
			    } else {
			    	echo '<font color="red">' . JText::_('COM_SEMINARMAN_STATUS_ERROR') . '</font>';
			    }
			?>
			</td>
			<td align="center">
			<?php 
			    if ($this->dbstati == 0) {
			    	echo '<button onclick="setDBSchema()">Fix Now!</button>';
			    }
			?>
			</td>
		</tr>
		<tr>
			<td>4.</td>
			<td align="center"><?php echo JText::_('COM_SEMINARMAN_GROUP_RIGHTS'); ?></td>
			<td align="center">
			<?php 
			    if ($this->grprights == 1) {
			    	echo '<font color="green">' . JText::_('COM_SEMINARMAN_STATUS_OK') . '</font>';
			    } else {
			    	echo '<font color="red">' . JText::_('COM_SEMINARMAN_STATUS_MISSING') . '</font>';
			    }
			?>
			</td>
		    <td align="center">
		    <?php 
		        if ($this->grprights == 0) {
		        	echo '<button onclick="setGrpRights()">Set Now!</button>';
		        }
		    ?>
		    </td>
		</tr>
	</tbody>
</table>
<br /><br />
<?php if ($this->params->get('trigger_virtuemart') == 1): ?>
<script type="text/javascript">
    function setVMRelDBSchema() {
    	document.forms["formFixVMRelDB"].submit();
    }
    function setVMCat() {
    	document.forms["formCreateVMCat"].submit();
    }
</script>
<table class="adminlist">
	<thead>
		<tr>
			<th class="pix10" style="width: 3%;"></th>
			<th class="proc98" style="width: 40%;">VirtueMart Integration <?php echo JText::_('COM_SEMINARMAN_OBJECT'); ?></th>
			<th class="proc98" style="width: 45%;"><?php echo JText::_('COM_SEMINARMAN_STATUS'); ?></th>
		    <th class="pix30" style="width: 12%;"></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>1.</td>
			<td align="center">VirtueMart Component</td>
			<td align="center"><?php echo $this->vmstati; ?></td>
		    <td align="center"></td>
		</tr>
		<tr>
			<td>2.</td>
			<td align="center">OSG Seminar Manager - VirtueMart Engine Plugin</td>
			<td align="center"><?php echo $this->vmenginestati; ?></td>
		    <td align="center"></td>
		</tr>
		<tr>
			<td>3.</td>
			<td align="center">VirtueMart - Seminarman Sync Plugin</td>
			<td align="center"><?php echo $this->vmsmstati; ?></td>
		    <td align="center"></td>
		</tr>
		<tr>
			<td>4.</td>
			<td align="center">VM Integration Related Database Schema</td>
			<td align="center">
			<?php 
			    if ($this->vmreldbstati == 1) {
			    	echo '<font color="green">' . JText::_('COM_SEMINARMAN_STATUS_OK') . '</font>';
			    } else {
			    	echo '<font color="red">Not set yet or error</font>';
			    }
			?>
			</td>
		    <td align="center">
			<?php 
			    if ($this->vmreldbstati == 0) {
			    	echo '<button onclick="setVMRelDBSchema()">Set/Fix Now!</button>';
			    }
			?>
		    </td>
		</tr>
		<tr>
			<td>5.</td>
			<td align="center">Seminar Root-Category in VirtueMart</td>
			<td align="center">
			<?php 
			    if (!empty($this->vmrootcat)) {
			    	echo $this->vmrootcat;
			    } else {
			    	echo '<font color="red">Not created yet or invalid</font>';
			    }
			?>
			</td>
		    <td align="center">
		    <?php 
			    if (empty($this->vmrootcat)) {
			    	echo '<button onclick="setVMCat()">Create Now!</button>';
			    }
			?>
		    </td>
		</tr>
		<tr>
			<td>6.</td>
			<td align="center">Compatible Currency in VirtueMart</td>
			<td align="center"><?php echo $this->vmcompacurrency; ?></td>
		    <td align="center"></td>
		</tr>
		<tr>
			<td>7.</td>
			<td align="center">Compatible Tax Rule in VirtueMart</td>
			<td align="center"><?php echo $this->vmcompatax; ?></td>
		    <td align="center"></td>
		</tr>
		<tr>
			<td>8.</td>
			<td align="center">Customer Groups in VirtueMart Applied to Seminarman<br />(max supported: 2)</td>
			<td align="center"><?php echo $this->vmappliedgrps; ?></td>
		    <td align="center"></td>
		</tr>
		<tr>
			<td>9.</td>
			<td align="center">Discount Rules in VirtueMart Applied to Seminarman<br />(max supported: 2)</td>
			<td align="center"><?php echo $this->vmappliedrules; ?></td>
		    <td align="center"></td>
		</tr>	
	</tbody>
</table>
<br /><br />
<form action="index.php" method="post" name="adminForm" id="formFixVMRelDB">
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_seminarman" />
	<input type="hidden" name="controller" value="settings" />
	<input type="hidden" name="view" value="settings" />
	<input type="hidden" name="task" value="vmpowerupdate" />
</form>
<form action="index.php" method="post" name="adminForm" id="formCreateVMCat">
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_seminarman" />
	<input type="hidden" name="controller" value="settings" />
	<input type="hidden" name="view" value="settings" />
	<input type="hidden" name="task" value="createmainvmcategory" />
</form>
<?php endif; ?>
<table class="adminlist">
	<thead>
		<tr>
			<th class="pix10" style="width: 3%;"></th>
			<th class="proc98" style="width: 40%;"><?php echo JText::_('COM_SEMINARMAN_MANAGER_MEMBERS'); ?></th>
			<?php 
			if ((SeminarmanFunctions::isVMEnabled()) && ($this->params->get('trigger_virtuemart') == 1)) {
				echo '<th class="proc98" style="width: 30%;">' . JText::_('COM_SEMINARMAN_ACCESS_TO_COM') . '</th><th class="proc98" style="width: 15%;">VM Publisher</th>';
			} else {
				echo '<th class="proc98" style="width: 45%;">' . JText::_('COM_SEMINARMAN_ACCESS_TO_COM') . '</th>';
			}
			?>
		    <th class="pix30" style="width: 12%;"></th>
		</tr>
	</thead>
	<tbody>
	<tr><td></td><td style="text-align: center;"><?php echo $this->managerlist; ?></td>
	<?php 
	if ((SeminarmanFunctions::isVMEnabled()) && ($this->params->get('trigger_virtuemart') == 1)) {
		echo '<td style="text-align: center;">' . $this->manageraccesslst . '</td><td style="text-align: center;">' . $this->managervmpublist . '</td>';
	} else {
		echo '<td style="text-align: center;">' . $this->manageraccesslst . '</td>';
	}
	?>
	<td></td></tr>
	</tbody>
</table>
<br /><br />
<table class="adminlist">
	<thead>
		<tr>
			<th class="pix10" style="width: 3%;"></th>
			<th class="proc98" style="width: 40%;"><?php echo JText::_('COM_SEMINARMAN_TUTOR_MEMBERS'); ?></th>
			<?php 
			if ((SeminarmanFunctions::isVMEnabled()) && ($this->params->get('trigger_virtuemart') == 1)) {
				echo '<th class="proc98" style="width: 30%;">' . JText::_('COM_SEMINARMAN_ACCESS_TO_COM') . '</th><th class="proc98" style="width: 15%;">VM Publisher</th>';
			} else {
				echo '<th class="proc98" style="width: 45%;">' . JText::_('COM_SEMINARMAN_ACCESS_TO_COM') . '</th>';
			}			
			?>
		    <th class="pix30" style="width: 12%;"></th>
		</tr>
	</thead>
	<tbody>
	<tr><td></td><td style="text-align: center;"><?php echo $this->tutorlist; ?></td>
	<?php 
	if ((SeminarmanFunctions::isVMEnabled()) && ($this->params->get('trigger_virtuemart') == 1)) {
		echo '<td style="text-align: center;">' . $this->tutoraccesslst . '</td><td style="text-align: center;">' . $this->tutorvmpublist . '</td>';
	} else {
		echo '<td style="text-align: center;">' . $this->tutoraccesslst . '</td>';
	}
	?>
	<td></td></tr>
	</tbody>
</table>
<br /><br />
<form action="index.php" method="post" name="adminForm" id="formCreateManagerGrp">
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_seminarman" />
	<input type="hidden" name="controller" value="settings" />
	<input type="hidden" name="view" value="settings" />
	<input type="hidden" name="task" value="createmanagergroup" />
</form>
<form action="index.php" method="post" name="adminForm" id="formCreateTutorGrp">
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_seminarman" />
	<input type="hidden" name="controller" value="settings" />
	<input type="hidden" name="view" value="settings" />
	<input type="hidden" name="task" value="createtrainergroup" />
</form>
<form action="index.php" method="post" name="adminForm" id="formFixDB">
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_seminarman" />
	<input type="hidden" name="controller" value="settings" />
	<input type="hidden" name="view" value="settings" />
	<input type="hidden" name="task" value="fixDB" />
</form>
<form action="index.php" method="post" name="adminForm" id="formSetRights">
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_seminarman" />
	<input type="hidden" name="controller" value="settings" />
	<input type="hidden" name="view" value="settings" />
	<input type="hidden" name="task" value="setRights" />
</form>
<?php
echo $pane->endPanel();
echo $pane->startPanel('Info', 'panel8');
include (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_seminarman' . DS . 'info.php');
echo $pane->endPanel();
echo $pane->endPane();
?>

<form action="index.php" method="post" name="adminForm">
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_seminarman" />
	<input type="hidden" name="controller" value="categories" />
	<input type="hidden" name="view" value="categories" />
	<input type="hidden" name="task" value="" />
<!-- <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" /><?php echo JHTML::_('form.token'); ?> -->
</form>
