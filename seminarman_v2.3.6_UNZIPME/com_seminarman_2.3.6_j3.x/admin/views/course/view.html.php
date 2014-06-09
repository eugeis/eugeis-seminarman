<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 2 of the License, or
* any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
**/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class SeminarmanViewCourse extends JViewLegacy
{

    function display($tpl = null)
    {
        $mainframe = JFactory::getApplication();

        jimport('joomla.html.pane');

        $editor = JFactory::getEditor();
        $document = JFactory::getDocument();
        $user = JFactory::getUser();
        $db = JFactory::getDBO();
        $lang = JFactory::getLanguage();
        $pane = JPaneOSG::getInstance('sliders');


        JHTML::_('behavior.tooltip');

        $nullDate = $db->getNullDate();

        $cid = JRequest::getVar('cid');

        $document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend.css');
        if ($lang->isRTL())
        {
            $document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend_rtl.css');
        }

        require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_seminarman' . DS .
            'helpers' . DS . 'seminarman.php');

        if ($cid)
            JToolBarHelper::title(JText::_('COM_SEMINARMAN_EDIT_COURSE'), 'courseedit');
        else
            JToolBarHelper::title(JText::_('COM_SEMINARMAN_NEW_COURSE'), 'courseedit');

        JToolBarHelper::apply();
        JToolBarHelper::save();
        JToolBarHelper::cancel();

        $model = $this->getModel();
        $row = $this->get('Course');
        $files = $this->get('Files');
        $tags = $this->get('Tags');
        $usedtags = $model->getusedtags($row->id);
        $categories = seminarman_cats::getCategoriesTree(1);
        $selectedcats = $this->get('Catsselected');
        $disabled = 0;

        if ($row->id)
        {
            if ($model->isCheckedOut($user->get('id')))
            {
                JError::raiseWarning('SOME_ERROR_CODE', $row->title . ' ' . JText::_('COM_SEMINARMAN_RECORD_EDITED'));
                $mainframe->redirect('index.php?option=com_seminarman&view=categories');
            }
            $disabled = 1;
        }

        JFilterOutput::objectHTMLSafe($row, ENT_QUOTES);

        $lists = array();
        
        if(JHTMLSeminarman::UserIsCourseManager()){
            $lists['catid'] = seminarman_cats::buildcatselect($categories, 'catid[]', $selectedcats, false,
                'multiple="multiple" size="8"');
        }else{
            $lists['catid'] = seminarman_cats::buildcatselect($categories, 'catid_orig[]', $selectedcats, false,
                'multiple="multiple" size="8" disabled') . seminarman_cats::buildcatselect($categories, 'catid[]', $selectedcats, false,
                'multiple="multiple" size="8" style="display: none;"');       	
        }

        // $form = new JParameter('', JPATH_COMPONENT . DS . 'models' . DS . 'course.xml');
        $form = new JForm('params');
        $form->loadFile( JPATH_COMPONENT . DS . 'models' . DS . 'course.xml' );

        $active = (intval($row->created_by) ? intval($row->created_by) : $user->get('id'));
        $form->setValue('created_by', 'details', $active);
        $form->setValue('created_by_alias', 'details', $row->created_by_alias);
        
        if ($row->created != "0000-00-00 00:00:00")
        	$form->setValue('created', NULL, JHTML::_('date', $row->created, JText::_('COM_SEMINARMAN_DATE_FORMAT1')));
        else
        	$form->setValue('created', NULL, JHTML::_('date', NULL, JText::_('COM_SEMINARMAN_DATE_FORMAT1')));
        
        if ($row->publish_up != "0000-00-00 00:00:00")
        	$form->setValue('publish_up', 'details', JHTML::_('date', $row->publish_up, JText::_('COM_SEMINARMAN_DATE_FORMAT1')));
        else
        	$form->setValue('publish_up', 'details', JHTML::_('date', NULL, JText::_('COM_SEMINARMAN_DATE_FORMAT1')));
        
        if (JHTML::_('date', $row->publish_down, 'Y') <= 1969 || $row->publish_down == $db->getNullDate() || $row->publish_down == '' )
            $form->setValue('publish_down', 'details', JText::_('COM_SEMINARMAN_NEVER'));
        else
            $form->setValue('publish_down', 'details', JHTML::_('date', $row->publish_down,  JText::_('COM_SEMINARMAN_DATE_FORMAT1')));
        
        // $form->loadINI($row->attribs);
        $data_course = array();
        $data_attribs = new JRegistry();
        $data_attribs->loadString($row->attribs);
        $data_course['params'] = $data_attribs->toArray();

        $form->setValue('description', 'meta', $row->meta_description);
        $form->setValue('keywords', 'meta', $row->meta_keywords);
        // $form->loadINI($row->metadata);
        $data_metadata = new JRegistry();
        $data_metadata->loadString($row->metadata);
        $data_course['meta'] = $data_metadata->toArray();
        $form->bind($data_course);

        $js = "
        		function qfSelectFile(id, file) {
        
        			var name = 'a_name'+id;
        			var ixid = 'a_id'+id;
        			var li = document.createElement('li');
        			var txt = document.createElement('input');
        			var hid = document.createElement('input');
        			var clrdiv = document.createElement('div');
        			clrdiv.setAttribute('class','clr');
        			txt.setAttribute('size','50');
        
        
        			var filelist = document.getElementById('filelist').getElementsByTagName('ul')[0];
        
        			var button = document.createElement('input');
        			button.type = 'button';
        			button.name = 'removebutton_'+id;
        			button.id = 'removebutton_'+id;
        			$(button).addEvent('click', function() { qfRemoveFile('" . JText::_('REMOVED') .
                    "', id ) });
        			button.value = '" . JText::_('COM_SEMINARMAN_REMOVE') . "';
        			
        			txt.type = 'text';
        			txt.disabled = 'disabled';
        			txt.id	= name;
        			txt.value	= file;
        
        			hid.type = 'hidden';
        			hid.name = 'fid[]';
        			hid.value = id;
        			hid.id = ixid;
        			
        			filelist.appendChild(li);
        			li.appendChild(txt);
        			li.appendChild(button);
        			li.appendChild(hid);
        			filelist.appendChild(clrdiv);
        		}
        
        		function qfRemoveFile(file, i) {
        
        			var name = 'a_name' + i;
        			var id = 'a_id' + i;
        
        			document.getElementById(id).value = 0;
        			document.getElementById(name).value = file;
        		}";

        $document->addScriptDeclaration($js);

        JHTML::_('behavior.modal', 'a.modal');

        $i = 0;
        $fileselect = '<ul class="adminformlist">';
        if ($files)
        {
        	foreach ($files as $file)
        	{
        		$fileselect .= '<li><input style="background: #ffffff;" type="text" id="a_name'. $i .'" value="'. $file->filename .'" disabled="disabled" size="50" />';
        		$fileselect .= '<input type="hidden" id="a_id'. $i .'" name="fid[]" value="'. $file->fileid .'" />';
        		$fileselect .= '<input class="inputbox" type="button" onclick="qfRemoveFile(\''. JText::_('COM_SEMINARMAN_REMOVE') .'\', '. $i . ');" value="'. JText::_('COM_SEMINARMAN_REMOVE') .'" /></li>';
        		$fileselect .= '<div class="clr"></div>';
        		$i++;
        	}
        }
        $fileselect .= '</ul>';
        
        $linkfsel = 'index.php?option=com_seminarman&amp;view=fileselement&amp;tmpl=component&amp;index=' . $i;

        $lists['state'] = JHTML::_('select.booleanlist', 'state', '', $row->state);
        $lists['new'] = JHTML::_('select.booleanlist', 'new', '', $row->new);
        $lists['canceled'] = JHTML::_('select.booleanlist', 'canceled', '', $row->canceled);
        
        if(JHTMLSeminarman::UserIsCourseManager()){
            $lists['experience_level'] = JHTMLSeminarman::getSelectExperienceLevel('id_experience_level', $row->id_experience_level);
        }else{
        	$lists['experience_level'] = JHTMLSeminarman::getSelectExperienceLevel('id_experience_level', $row->id_experience_level, 'disabled') . '<input type="hidden" name="id_experience_level" value="' . $row->id_experience_level . '" />';
        }
        
        if(JHTMLSeminarman::UserIsCourseManager()){
            $lists['atgroup'] = JHTMLSeminarman::getSelectATGroup('id_group', $row->id_group, '');
        }else{
        	$lists['atgroup'] = JHTMLSeminarman::getSelectATGroup('id_group', $row->id_group, 1) . '<input type="hidden" name="id_group" value="' . $row->id_group . '" />';
        }
        
        $lists['job_experience'] = JHTMLSeminarman::getListFromXML('Job Experience', 'job_experience', 0, $row->job_experience);
        
        if(JHTMLSeminarman::UserIsCourseManager()){
            $lists['price_type'] = JHTMLSeminarman::getListFromXML('Price Type', 'price_type', '', $row->price_type);
        }else{
            $lists['price_type'] = JHTMLSeminarman::getListFromXML('Price Type', 'price_type', 1, $row->price_type) . '<input type="hidden" name="price_type" value="' . $row->price_type . '" />';	
        }
        
        if(JHTMLSeminarman::UserIsCourseManager()){
            $lists['email_template'] = JHTMLSeminarman::getSelectEmailTemplate('email_template', $row->email_template);
        }else{
        	$lists['email_template'] = JHTMLSeminarman::getSelectEmailTemplate('email_template', $row->email_template, 'disabled') . '<input type="hidden" name="email_template" value="' . $row->email_template . '" />';
        }
        
        if(JHTMLSeminarman::UserIsCourseManager()){
            $lists['invoice_template'] = JHTMLSeminarman::getSelectPdfTemplate('invoice_template', $row->invoice_template, 0);
        }else{
        	$lists['invoice_template'] = JHTMLSeminarman::getSelectPdfTemplate('invoice_template', $row->invoice_template, 0, 'disabled') . '<input type="hidden" name="invoice_template" value="' . $row->invoice_template . '" />';
        }
        
        if(JHTMLSeminarman::UserIsCourseManager()){
            $lists['attlst_template'] = JHTMLSeminarman::getSelectPdfTemplate('attlst_template', $row->attlst_template, 1);
        }else{
        	$lists['attlst_template'] = JHTMLSeminarman::getSelectPdfTemplate('attlst_template', $row->attlst_template, 1, 'disabled') . '<input type="hidden" name="attlst_template" value="' . $row->attlst_template . '" />';
        }

        (count($tags) > 9) ? ($tags_size = 10) : ($tags_size = count($tags) + 1);
        $lists['tagsselect'] = '<select size="'. $tags_size .'" multiple="multiple" name="tag[]" id="tag">';
        foreach ($tags as $tag) {
        	$lists['tagsselect'] .= '<option ';
        	foreach ($usedtags as $used)
        	if ($used == $tag->id)
        	$lists['tagsselect'] .= 'selected="selected" ';
        	$lists['tagsselect'] .= 'value="'. $tag->id .'">'. $tag->name .'</option>';
        }
        $lists['tagsselect'] .= '</select>';
        
        // get all templates
        if(JHTMLSeminarman::UserIsCourseManager()){
            $query = 'SELECT id AS value, CONCAT(name, \' (\', id, \')\') as text FROM #__seminarman_templates ORDER BY id';
        }else{
            $teacherid = JHTMLSeminarman::getUserTutorID();	
            $query = 'SELECT t.id AS value, CONCAT(t.name, \' (\', id, \')\') as text FROM #__seminarman_templates AS t LEFT JOIN #__seminarman_tutor_templates_relations AS r ON (t.id = r.templateid) WHERE r.tutorid = ' . $teacherid . ' ORDER BY t.id';	
        }
        $db->setQuery($query);
        $templates = $db->loadObjectList();
        
        // build select list of template names
        $types[] = JHTML::_('select.option', 0, '- ' . JText::_('COM_SEMINARMAN_CHOOSE_PLEASE') . ' -');
        
       	foreach ($templates as $template)
       		$types[] = JHTML::_('select.option', $template->value, JText::_($template->text));
       	
       	// template aus post
       	$templateId = JRequest::getVar('templateId', 0);
       	if ($templateId == 0)
       		if (!empty($row->templateId))
       			$templateId = $row->templateId;
        
        $lists['templates'] = JHTML::_('select.genericlist', $types, 'templateId', 'class="inputbox" size="1" ', 'value', 'text', $templateId);
        
        if(JHTMLSeminarman::UserIsCourseManager()){
            $lists['username'] = JHTMLSeminarman::getSelectTutor('tutor_id', $row->tutor_id, $templateId);
        }else{
            $teacherid = JHTMLSeminarman::getUserTutorID();	
            $query_tutor = 'SELECT id AS value, CONCAT(title, CONCAT(\' (\', id, \')\')) AS text'.
    		            ' FROM #__seminarman_tutor WHERE id = ' . $teacherid;
            $db->setQuery($query_tutor);
            foreach ($db->loadObjectList() as $tutor)
    			    $teachers[] = JHtml::_('select.option', $tutor->value, JText::_($tutor->text));
    	    $lists['username'] = JHtml::_('select.genericlist', $teachers, 'tutor_id', 'class="inputbox" size="1" ', 'value', 'text', $teacherid);
        }
        
        $params = JComponentHelper::getParams('com_seminarman');
        if ($params->get('trigger_virtuemart') == 1) {
        	if (($row->id) == 0) {
        		// create new course and vm engine is on
        		$lists['select_vm'] = '<li><label for="invm">In VirtueMart</label><fieldset id="invm" class="radio">' . JHTML::_('select.booleanlist', 'invm', 'class="inputbox"', 1) . '</fieldset></li>';
        	} else {
        		$db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('*')
                      ->from('#__seminarman_vm_course_product_map')
                      ->where('sm_course_id = ' . $row->id );
                $db->setQuery($query);
                $result = $db->loadAssoc(); 
                if (is_null($result)) {
                	// no vm product mapped yet
                	$lists['select_vm'] = '<li><label for="invm">In VirtueMart</label><fieldset id="invm" class="radio">' . JHTML::_('select.booleanlist', 'invm', 'class="inputbox"', 0) . '</fieldset></li>';
                } else {
                	// a vm product is mapped, is this valid?
                	$register_vm_id = $result["vm_product_id"];
            	    $query_check = $db->getQuery(true);
            	    $query_check->select('*')
            	            ->from('#__virtuemart_products')
            	            ->where('virtuemart_product_id = ' . $register_vm_id);
            	    $db->setQuery($query_check);
            	    $result_check = $db->loadAssoc();
            	    if (is_null($result_check)){
            	    	// invalid
            	    	$lists['select_vm'] = '<li><label for="invm">' . JText::_('COM_SEMINARMAN_SET_IN_VM') . '</label><fieldset id="invm" class="radio">' . JHTML::_('select.booleanlist', 'invm', 'class="inputbox"', 0) . '</fieldset></li>';
            	    } else {
            	    	// valid
            	    	$lists['select_vm'] = '<li><label for="invm">' . JText::_('COM_SEMINARMAN_SET_IN_VM') . '</label><fieldset id="invm" class="radio">' . JHTML::_('select.booleanlist', 'invm', 'class="inputbox" disabled', 1) . '<input type="hidden" name="invm" value="1"></fieldset></li>';
            	    }
                }      		
        	}
        } else {
        	$lists['select_vm'] = '';
        }

        $query_price_rule2 = $db->getQuery(true);
        $query_price_rule2->select('*')
                          ->from('#__seminarman_pricegroups')
                          ->where('gid=2');
        $db->setQuery($query_price_rule2);
        $result_rule2 = $db->loadAssoc();
        if(!is_null($result_rule2)) {
        	$lists['price2_mathop'] = $result_rule2['calc_mathop'];
        	$lists['price2_value'] = $result_rule2['calc_value'];
        } else {
        	$lists['price2_mathop'] = '-';
        	$lists['price2_value'] = 0;
        }

        $query_price_rule3 = $db->getQuery(true);
        $query_price_rule3->select('*')
                          ->from('#__seminarman_pricegroups')
                          ->where('gid=3');
        $db->setQuery($query_price_rule3);
        $result_rule3 = $db->loadAssoc();
        if(!is_null($result_rule3)) {
        	$lists['price3_mathop'] = $result_rule3['calc_mathop'];
        	$lists['price3_value'] = $result_rule3['calc_value'];
        } else {
        	$lists['price3_mathop'] = '-';
        	$lists['price3_value'] = 0;
        } 

        $query_price_rule4 = $db->getQuery(true);
        $query_price_rule4->select('*')
        ->from('#__seminarman_pricegroups')
        ->where('gid=4');
        $db->setQuery($query_price_rule4);
        $result_rule4 = $db->loadAssoc();
        if(!is_null($result_rule4)) {
        	$lists['price4_mathop'] = $result_rule4['calc_mathop'];
        	$lists['price4_value'] = $result_rule4['calc_value'];
        } else {
        	$lists['price4_mathop'] = '-';
        	$lists['price4_value'] = 0;
        }
        
        $query_price_rule5 = $db->getQuery(true);
        $query_price_rule5->select('*')
        ->from('#__seminarman_pricegroups')
        ->where('gid=5');
        $db->setQuery($query_price_rule5);
        $result_rule5 = $db->loadAssoc();
        if(!is_null($result_rule5)) {
        	$lists['price5_mathop'] = $result_rule5['calc_mathop'];
        	$lists['price5_value'] = $result_rule5['calc_value'];
        } else {
        	$lists['price5_mathop'] = '-';
        	$lists['price5_value'] = 0;
        }
        
        $this->assignRef('lists', $lists);
        $this->assignRef('row', $row);
        $this->assignRef('editor', $editor);
        $this->assignRef('pane', $pane);
        $this->assignRef('nullDate', $nullDate);
        $this->assignRef('form', $form);
        $this->assignRef('fileselect', $fileselect);
        $this->assignRef('linkfsel', $linkfsel);
        $this->assignRef('tags', $tags);
        $this->assignRef('usedtags', $usedtags);
        parent::display($tpl);
    }
}

?>