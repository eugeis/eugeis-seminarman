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

class JHTMLSeminarman
{
    static function getUserGroups($var, $default, $disabled) {
    	$db = JFactory::getDBO();
    	$query = $db->getQuery(true);
    	$query->select('grp.id, grp.title')
              ->from('`#__usergroups` AS grp');
        $db->setQuery($query); 
        $groups = $db->loadObjectList(); 
        
        $types[] = JHtml::_('select.option', '0', '- '. JText::_('COM_SEMINARMAN_CHOOSE_PLEASE') .' -');
        foreach ($groups as $group) {
        	$types[] = JHtml::_('select.option', $group->id, JText::_($group->title));
        }
        
        if ($default == '')
            $default = '0';

        if ($disabled == 1) {
            $disabled = 'disabled';
        } else {
        	$disabled = '';
        }           

        return JHtml::_('select.genericlist', $types, $var, 'class="inputbox" size="1" ' . $disabled . '', 'value', 'text', $default);
    }
	
	static function getSelectUser($var, $default, $disabled)
    {
        $db = JFactory::getDBO();

        $option = '';
        if ($disabled == 1)
            $option = 'disabled';

        if ($default == '')
            $default = '0';


        $query = 'SELECT id AS value, CONCAT_WS(\' / \', username, name, id ) AS text FROM #__users ORDER BY username';
        $db->setQuery($query);
        $items = $db->loadObjectList();

        $types[] = JHtml::_('select.option', '0', '- '. JText::_('COM_SEMINARMAN_CHOOSE_PLEASE') .' -');
        foreach ($items as $item) {
            $types[] = JHtml::_('select.option', $item->value, JText::_($item->text));
        }

        if ($disabled == 1) {
            $disabled = 'disabled';
        }

        return JHtml::_('select.genericlist', $types, $var, 'class="inputbox" size="1" ' . $disabled . '', 'value', 'text', $default);
    }
    
	static function getSelectUserForTrainer($var, $default, $disabled)
    {
        $db = JFactory::getDBO();

        $option = '';
        if ($disabled == 1)
            $option = 'disabled';

        if ($default == '')
            $default = '0';


        $query = 'SELECT id AS value, CONCAT_WS(\' / \', username, name, id ) AS text FROM #__users ORDER BY username';
        $db->setQuery($query);
        $items = $db->loadObjectList();

        $types[] = JHtml::_('select.option', '0', '- '. JText::_('COM_SEMINARMAN_CHOOSE_PLEASE') .' -');
        foreach ($items as $item) {
        	// only if user is neither admin nor course manager nor tutor
        	//if (!(JHTMLSeminarman::user_is_admin($item->value)||JHTMLSeminarman::UserIsCourseManager($item->value)||JHTMLSeminarman::getUserTutorID($item->value)>0)){
                $types[] = JHtml::_('select.option', $item->value, JText::_($item->text));
        	//}
        }

        if ($disabled == 1) {
            $disabled = 'disabled';
        }

        return JHtml::_('select.genericlist', $types, $var, 'class="inputbox" size="1" ' . $disabled . '', 'value', 'text', $default);
    }
    
    static function getJUserState($uid, $var)
    {
        $value = array();
    	// if create trainer, creating juser is ready to go.
    	if($uid == 0){
        	// return JHTML::_('select.booleanlist', $var, '', true);
        	$disabled = '';
        	$disableform = '';
        	$selected = true;
        	$loginname = '';
        	$jemail = '';
        	$juserid = 0;
        	$value['invm'] = '<span class="readonly">'.JText::_('COM_SEMINARMAN_SAVE_JOOMLA_ACC_FIRST').'</span>'; 
        } else {   	
    	    $db = JFactory::getDBO();

            $query = 'SELECT u.id AS juid, u.username AS jlogin, u.email AS jemail FROM #__users AS u LEFT JOIN #__seminarman_tutor AS t ON (u.id = t.user_id) WHERE t.id = '.$uid;
            $db->setQuery($query);
            // $item = $db->loadObjectList();
            $item = $db->loadAssoc();

            if (empty($item)){   // tutor has no joomla account yet or his/her joomla account is deleted
            	$disabled = '';
            	$disableform = '';
            	$selected = false;
            	$loginname = '';
            	$jemail = '';
            	$juserid = 0;
            	$value['invm'] = '<span class="readonly">'.JText::_('COM_SEMINARMAN_SAVE_JOOMLA_ACC_FIRST').'</span>'; 
            }else{  // tutor has a joomla account
            	$disabled = 'disabled';
            	$disableform = 'disabled';
            	$selected = true;
            	$loginname = $item['jlogin'];
            	$jemail = $item['jemail'];
            	$juserid = $item['juid'];
	            // Let's check if the virtuemart component exists.
                // jimport('joomla.application.component.helper');
                // $component = JComponentHelper::getComponent('com_virtuemart', true);
                // if (!($component->enabled)) {
                if (!(SeminarmanFunctions::isVMEnabled())) {
      	            $value['invm'] = '<span class="readonly">VirtueMart is either not installed or not enabled</span>';
                } else {
                	$params = JComponentHelper::getParams('com_seminarman');
                    if ($params->get('trigger_virtuemart') == 1) {
                    	$query_vmadmin = 'SELECT * FROM #__virtuemart_vmusers AS v WHERE v.virtuemart_user_id = ' . $juserid . ' AND (v.perms = "storeadmin" OR v.perms = "admin")';
                    	$db->setQuery($query_vmadmin);
                    	$item_vmuser = $db->loadAssoc();
                    	if(empty($item_vmuser)){
                    		$value['invm'] = JHTML::_('select.booleanlist', 'invm', '', 0);
                    	} else {
                    		$value['invm'] = JHTML::_('select.booleanlist', 'invm', 'disabled', 1);
                    	}
                    } else {
                        $value['invm'] = '<span class="readonly">VMEngine is not enabled</span>';
                    }
                }
            }        
        }
        $value['selection'] = JHTML::_('select.booleanlist', $var, $disabled . ' onclick="updatejuserform()"', $selected);
        $value['username'] = '<input class="inputbox required" type="text" name="user_name" size="30" value="' . $loginname . '" ' . $disableform . ' />';
        $value['password1'] = '<input class="inputbox required" type="password" name="jpassword1" size="30" ' . $disableform . ' />';
        $value['password2'] = '<input class="inputbox required" type="password" name="jpassword2" size="30" ' . $disableform . ' />';
        $value['email'] = '<input class="inputbox required" type="email" name="jemail" size="30" value="' . $jemail . '" ' . $disableform . ' />';
        $value['userid'] = '<input type="hidden" name="user_id" value="' . $juserid . '" />';
        
        $method[] = JHtml::_('select.option', '0', JText::_('COM_SEMINARMAN_CREATE_NEW_JOOMLA_ACC'));
        $method[] = JHtml::_('select.option', '1', JText::_('COM_SEMINARMAN_SELECT_JOOMLA_ACC'));
        $value['method'] = JHtml::_('select.genericlist', $method, 'juser_option', 'class="inputbox" size="1" ' . $disabled . ' onchange="updatejuserform()"', 'value', 'text', '0');
        
        return $value;
    }
    
	static function getPeriods()
	{
		$db = JFactory::getDBO();
		$sql = 'SELECT id, title as title'
		. ' FROM #__seminarman_period'
		. ' WHERE published = 1'
		. ' ORDER BY title';
		$db->setQuery($sql);
		$titles = $db->loadObjectlist();
		return $titles;
	}
	
	static function getPeriodsList($filter_periodid)
	{
		$titles = JHTMLSeminarman::getPeriods();

    	$javascript = 'onchange="document.adminForm.submit();"';
    	$periodlist = array();
    	$periodlist[] = JHTML::_('select.option',  '0', '- '. JText::_( 'COM_SEMINARMAN_SELECT_PERIOD' ). ' -', 'id', 'title' );
    	$periodlist = array_merge( $periodlist, $titles );
    	return JHTML::_('select.genericlist', $periodlist, 'filter_periodid', 'class="inputbox" size="1" onchange="submitform( );"','id', 'title', $filter_periodid );
	}
	
    static function getSelectCountry($var, $default, $disabled)
    {
        $db = JFactory::getDBO();

        $option = '';
        if ($disabled == 1)
            $option = 'disabled';

        if ($default == '')
            $default = '0';

        $query = 'SELECT id AS value, title AS text FROM #__seminarman_country ORDER BY title';
        $db->setQuery($query);
        $items = $db->loadObjectList();

        $types[] = JHtml::_('select.option', '0', '- '. JText::_('COM_SEMINARMAN_CHOOSE_PLEASE') .' -');
        foreach ($items as $item) {
            $types[] = JHtml::_('select.option', $item->value, JText::_($item->text));
        }

        return JHtml::_('select.genericlist', $types, $var, 'class="inputbox" size="1" ' . $option . '', 'value', 'text', $default);
    }
    

	static function getSelectExperienceLevel($var, $default, $disabled = '')
	{
		$db = JFactory::getDBO();

		$query = 'SELECT id AS value, title AS text FROM #__seminarman_experience_level';
		$db->setQuery($query);
		$items = $db->loadObjectList();

		$types[] = JHtml::_('select.option', '0', '- '. JText::_('COM_SEMINARMAN_NOVALUE') .' -');
		foreach ($items as $item)  {
			$types[] = JHtml::_('select.option', $item->value, JText::_($item->text));
		}

		return JHtml::_('select.genericlist', $types, $var, 'class="inputbox" size="1" ' . $disabled, 'value', 'text', $default);
	}
	
	static function getPeriod($var, $default, $disabled = '')
	{
		$db = JFactory::getDBO();

		$query = 'SELECT id AS value, title AS text FROM #__seminarman_period';
		$db->setQuery($query);
		$items = $db->loadObjectList();

		$types[] = JHtml::_('select.option', '0', '- '. JText::_('COM_SEMINARMAN_NOVALUE') .' -');
		foreach ($items as $item)  {
			$types[] = JHtml::_('select.option', $item->value, JText::_($item->text));
		}

		return JHtml::_('select.genericlist', $types, $var, 'class="inputbox" size="1" ' . $disabled, 'value', 'text', $default);
	}
	
	static function getSelectATGroup($var, $default, $disabled)
	{
		$db = JFactory::getDBO();

		$option = '';
		if ($disabled == 1)
			$option = 'disabled';

		$query = 'SELECT id AS value, title AS text FROM #__seminarman_atgroup';
		$db->setQuery($query);
		$items = $db->loadObjectList();

		$types[] = JHtml::_('select.option', '0', '- '. JText::_('COM_SEMINARMAN_NOVALUE') .' -');
		foreach ($items as $item)  {
			$types[] = JHtml::_('select.option', $item->value, JText::_($item->text));
		}

		return JHtml::_('select.genericlist', $types, $var, 'class="inputbox" size="1" ' . $option . '', 'value', 'text', $default);
	}
	

    static function getSelectCompType($var, $default, $disabled)
    {
        $db = JFactory::getDBO();

        $option = '';
        if ($disabled == 1)
            $option = 'disabled';

        $query = 'SELECT id AS value, title AS text FROM #__seminarman_company_type ORDER BY ordering';
        $db->setQuery($query);
        $items = $db->loadObjectList();

        $types[] = JHtml::_('select.option', '0', '- '. JText::_('COM_SEMINARMAN_CHOOSE_PLEASE') .' -');
        foreach ($items as $item) {
            $types[] = JHtml::_('select.option', $item->value, JText::_($item->text));
        }

        return JHtml::_('select.genericlist', $types, $var, 'class="inputbox" size="1" ' . $option . '', 'value', 'text', $default);
    }

   
    static function getSelectTutor($var, $default = 0, $templateId = 0)
    {
    	$db = JFactory::getDBO();
    	  	
    	if (empty($templateId))
    	{
    		// Kurs wurde ohne Template angelegt oder Template existiert nicht mehr
    		// -> alle Trainer auflisten
    		$query = 'SELECT id AS value, CONCAT(title, CONCAT(\' (\', id, \')\')) AS text'.
    		            ' FROM #__seminarman_tutor ORDER BY title';
    		$db->setQuery($query);
    		
    		$types[] = JHtml::_('select.option', '0', '- ' . JText::_('COM_SEMINARMAN_CHOOSE_PLEASE') . ' -');
    		foreach ($db->loadObjectList() as $tutor)
    			$types[] = JHtml::_('select.option', $tutor->value, JText::_($tutor->text));
    	}
    	else
    	{
    		// Kurs kommt von Template
    		// -> geeignete Trainer oben auflisten und nach priority sortieren, darunter alle übrigen
    		$query = 'SELECT DISTINCT t.id AS value, CONCAT(t.title, CONCAT(\' (id: \', t.id, \', prio: \', rel.priority, \')\')) AS text'.
    					' FROM #__seminarman_tutor AS t'.
    					' LEFT JOIN #__seminarman_tutor_templates_relations AS rel '.
    					' ON rel.tutorid = t.id'.
    					' WHERE rel.templateid = ' . (int)$templateId .
    					' ORDER BY rel.priority DESC';
    		$db->setQuery($query);
    		$tutors = $db->loadObjectList();
    		
    		if (empty($tutors)) {
    			$types[] = JHtml::_('select.option', 0, '- '.  JText::_('COM_SEMINARMAN_NO_QUALIFIED_TUTORS') .' -');
    		} else {
    			$types[] = JHtml::_('select.option', 0, '- '.  JText::_('COM_SEMINARMAN_QUALIFIED_TUTORS') .' -');
    			foreach ($db->loadObjectList() as $tutor) {
    				$types[] = JHtml::_('select.option', $tutor->value, JText::_($tutor->text));
    			}
    		}
    		
    		// übrige Trainer
    		$query = 'SELECT DISTINCT t.id AS value, CONCAT(t.title, CONCAT(\' (id: \', t.id, \')\')) AS text'.
    					' FROM #__seminarman_tutor AS t'.
    					' WHERE id NOT IN ('.
    						' SELECT DISTINCT t.id FROM #__seminarman_tutor AS t'.
    						' LEFT JOIN #__seminarman_tutor_templates_relations AS rel '.
    						' ON rel.tutorid = t.id'.
    						' WHERE rel.templateid = ' . (int)$templateId .
    					')';
    		$db->setQuery($query);
    		$tutors = $db->loadObjectList();

    		if (!empty($tutors)) {
    			$types[] = JHtml::_('select.option', 0, '- '.  JText::_('COM_SEMINARMAN_REMAINING_TUTORS') .' -');
    			foreach ($db->loadObjectList() as $tutor)
    			$types[] = JHtml::_('select.option', $tutor->value, JText::_($tutor->text));
    		}
    	}
    
    	return JHtml::_('select.genericlist', $types, $var, 'class="inputbox" size="1" ', 'value', 'text', $default);
    }
    
    
    static function getSelectTemplate($var, $maxlength = 80)
    {
    	$db = JFactory::getDBO();
    	
   		$query = 'SELECT id, CONCAT(IF(LENGTH(`name`) > '.$maxlength.', CONCAT(LEFT(`name`, '.($maxlength - 3).'), "..."), `name`), \' (\', id, \')\') AS text FROM #__seminarman_templates ORDER BY name';
    	$db->setQuery($query);
    
    	$types[] = JHtml::_('select.option', '0', '- '. JText::_('COM_SEMINARMAN_CHOOSE_PLEASE') .' -');
    	foreach ($db->loadObjectList() as $template) {
    		$types[] = JHtml::_('select.option', $template->id, JText::_($template->text));
    	}
    
    	return JHtml::_('select.genericlist', $types, $var, 'class="inputbox" size="1" ', 'value', 'text', 0);
    }
    
    
    static function getVirtualTable()
    {
        // $parser = JFactory::getXMLParser('Simple');

        // $pathToXML_File = JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'virtual_tables.xml';
        
        // $parser->loadFile($pathToXML_File);
        // $document = &$parser->document;

        // $result =  &$document->table;
        // return $result;
        
    	$pathToXML_File = JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'virtual_tables.xml';
    	$xml=JFactory::getXML($pathToXML_File);
    	
    	$tables = array();
    	foreach($xml->table as $table) {
    	  $tables[] = $table;
    	}
    	
    	return $tables;
    }

    
    static function getTableFromXML($tableTitle)
    {
        $tables = JHTMLSeminarman::getVirtualTable();

        for ($i = 0, $c = count($tables); $i < $c; $i++)
        {
            $album = $tables[$i];
            // $name = $album->getElementByPath('title');
            $name = $album->title;
            // if ($name->data() == $tableTitle) {
            if ($name == $tableTitle) {
                // if ($values = $album->getElementByPath('values')) {
            	if ($values = $album->values) {
                    // $listing = $values->value;
                    $listing = array();
                    foreach($values->value as $wert) {
                    	$listing[]=$wert;
                    }
                    
                    for ($ti = 0, $tc = count($listing); $ti < $tc; $ti++) {
                        $value = &$listing[$ti];
                        // $XMLvalue[$ti] = $value->data();
                        $XMLvalue[$ti] = $value;
                    }
                }
                return $XMLvalue;
            }
        }
    }

    
    static function getListFromXML($tableTitle, $db_field, $disabled, $default)
    {
        $values = JHTMLSeminarman::getTableFromXML($tableTitle);

        $option = '';
        if ($disabled == 1)
            $option = 'disabled';

        $options[] = JHtml::_('select.option', '', '- ' . JText::_('COM_SEMINARMAN_CHOOSE_PLEASE') . ' -');
        foreach ($values as $course)  {
            $options[] = JHtml::_('select.option', $course, JText::_($course));
        }

        return JHtml::_('select.genericlist', $options, $db_field, 'class="inputbox" size="1"'. $option, 'value', 'text', $default);
    }

    
    static function getSelectEmailTemplate($var, $default = '', $disabled = '')
    {
        $db = JFactory::getDBO();

        $query = 'SELECT id AS value, title AS text FROM #__seminarman_emailtemplate WHERE templatefor=0 ORDER BY id';
        $db->setQuery($query);
        $templates = $db->loadObjectList();
        
        $types[] = JHtml::_('select.option', '0', '- '. JText::_('COM_SEMINARMAN_DEFAULT') .' -');
        foreach ($templates as $template) {
            $types[] = JHtml::_('select.option', $template->value, JText::_($template->text));
        }

        return JHtml::_('select.genericlist', $types, $var, 'class="inputbox" size="1" ' . $disabled, 'value', 'text', $default);
    }
    
    
    static function getSelectPdfTemplate($var, $default = '', $templatefor=0, $disabled = '')
    {
    	$db = JFactory::getDBO();
    
    	$query = 'SELECT id AS value, name AS text FROM #__seminarman_pdftemplate WHERE templatefor='.(int)$templatefor.' ORDER BY id';
    	$db->setQuery($query);
    	$templates = $db->loadObjectList();
    
    	$types[] = JHtml::_('select.option', '0', '- '. JText::_('COM_SEMINARMAN_DEFAULT') .' -');
    	foreach ($templates as $template) {
    		$types[] = JHtml::_('select.option', $template->value, JText::_($template->text));
    	}
    
    	return JHtml::_('select.genericlist', $types, $var, 'class="inputbox" size="1" ' . $disabled, 'value', 'text', $default);
    }
    

	static function localDate2DbDate($str) {
		
 		if (empty($str))
 			return "0000-00-00";
 		
    	$db = JFactory::getDBO();
 		$db->setQuery('SELECT STR_TO_DATE("'. $str .'","'. JText::_('COM_SEMINARMAN_DATE_FORMAT1_ALT') .'")');
 		if (!$db->query())
 			return "0000-00-00";
 		return $db->loadResult();
	}
	
	static function UserIsCourseManager($uid = null){
		
		if (empty($uid)) {
			$user = JFactory::getUser();
		} else {
			$user = JFactory::getUser($uid);
		}
		
		$userGroups = $user->getAuthorisedGroups();
		
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')
              ->from('#__seminarman_usergroups AS g')
              ->where('g.sm_id = 1');
        $db->setQuery($query);
        $result = $db->loadAssoc();

        $manager_id = $result["jm_id"];

        $ismanager = false;
        
        if ($user->authorise('core.admin', 'com_seminarman')) {
        	$ismanager = true;
        } else {        
            foreach ($userGroups as $gid) {
        	    if ($gid == $manager_id) {
        		    $ismanager = true;
        		    break;
        	    }
            }
        }
		
		return $ismanager;
		
	}
	
	static function getUserTutorID($uid = null){
		if (empty($uid)) {
			$user = JFactory::getUser();
		} else {
			$user = JFactory::getUser($uid);
		}
		$userId = $user->get('id');	

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')
              ->from('#__seminarman_tutor AS t')
              ->where('t.user_id = '.(int)$userId);		
        $db->setQuery($query);
        $result = $db->loadAssoc();

        if(empty($result)){
        	$tutor_id = 0;
        }else{
        	$tutor_id = $result["id"];
        }
        return $tutor_id;
	}
	
    static function user_is_admin($uid) {
        jimport( 'joomla.user.helper' );
        $groups = JUserHelper::getUserGroups($uid);
        //8 is for Super User and 7 is for Administrator
    	foreach($groups as $temp) {
			if(in_array($temp, Array(7,8))){
					return true;
                    break;
			}
		}
		return false;
    }
    
    static function get_price_view ($course_id, $jsfunction = '', $vmlink = NULL) {
    
    	$db = JFactory::getDbo();
    	$params = JComponentHelper::getParams( 'com_seminarman' );
    
    	$html = "";
    
    	$query = $db->getQuery(true);
    	$query->select('*')
    	->from('#__seminarman_courses')
    	->where('id = '.(int)$course_id);
    	$db->setQuery($query);
    	$course = $db->loadObject();
    
    	$currency = $params->get( 'currency' );
    
    	$tax_rate = doubleval(str_replace(",", ".", $course->vat))/100;
    	//             $tax_rate = doubleval(str_replace(",", ".", $this->escape($course->vat)))/100;
    
    	$standard_netto = $course->price;
    	//$standard_netto = $this->escape($this->price_before_vat);
    
    	$standard_brutto = JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $standard_netto)) * (1 + $tax_rate), 2));
    	$standard_netto = JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $standard_netto)), 2));
    	//             $standard_brutto = JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $this->escape($standard_netto))) * (1 + $tax_rate), 2));
    	//             $standard_netto = JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $this->escape($standard_netto))), 2));
    
    
    	$display_party = array();
    	 
    	$display_free_charge = $params->get('display_free_charge');
    
    	if ($params->get('show_price_1') == 0) {
    		$price1_label = JText::_('COM_SEMINARMAN_NET') . ': '
    		. $standard_netto . ' ' . $currency . ', ' . JText::_('COM_SEMINARMAN_GROSS_WITH_TAX') . ': ' . $standard_brutto . ' ' . $currency;
    		if($course->vat == 0) {
    			$price1_label = $standard_netto . ' ' . $currency;
    		}
    	} else {
    		$price1_label = JText::_('COM_SEMINARMAN_PRICE_STANDARD');
    	}
    	 
    	if (!empty($display_free_charge) && ($standard_netto == 0)) {
    		$price1_label = JText::_($params->get('display_free_charge'));
    	}
    
    	// price1
    	$display_party[ 'display_price1' ] = '<input id="booking_price1" type="radio" value="0" checked="checked" name="booking_price[]" '.$jsfunction.'><label for="jformbookingprice1">' . $price1_label . '</label>';
    
    	// price2-5
    	for ( $i=2; $i < 6; $i++ ) {
    		$val = JHTMLSeminarman::_get_price_display ( $db, $course, $i, $display_party, $price1_label, $jsfunction );
    	}
    
    	if (($params->get('trigger_virtuemart') == 1)  && !is_null($vmlink)) {
    		$display_party[ 'display_price1' ] = '<input id="booking_price1" type="radio" value="66" checked="checked" name="booking_price[]" '.$jsfunction.'><label for="jformbookingprice1">'
    		. JText::_('COM_SEMINARMAN_PRICE_SHOW_IN_VM') . '</label>';
    		$display_party[ 'display_price2' ] = '';
    		$display_party[ 'display_price3' ] = '';
    		$display_party[ 'display_price4' ] = '';
    		$display_party[ 'display_price5' ] = '';
    	}
    
    	$html .= $display_party[ 'display_price1' ];
    	$html .= $display_party[ 'display_price2' ];
    	$html .= $display_party[ 'display_price3' ];
    	$html .= $display_party[ 'display_price4' ];
    	$html .= $display_party[ 'display_price5' ];
    
    	return $html;
    }
    
    static function _get_price_display ( $db, $course, $num, &$display_party, $price1_label, $jsfunction ) {
    
    	// fetch the pricegrp from db
    	$query_pricegroup = $db->getQuery(true);
    	$query_pricegroup->select('*')
    	->from('#__seminarman_pricegroups')
    	->where("gid=$num");
    	$db->setQuery($query_pricegroup);
    	$priceg = $db->loadAssoc();
    	$priceg_name = $priceg['title'];
    	$priceg_usg = json_decode($priceg['jm_groups']);
    
    	if (is_null($priceg_usg)) {
    		$priceg_usg = array();
    	}
    
    	// build view
    	$user = JFactory::getUser();
    	$current_usergroups = $user->getAuthorisedGroups();
    
    	$courseprice = $course->{"price$num"};
    	$coursevat = $course->vat;
    	$params = JComponentHelper::getParams( 'com_seminarman' );
    	$currency = $params->get( 'currency' );
    	$tax_rate = doubleval(str_replace(",", ".", $course->vat))/100;
    	$param_show = $params->get("show_price_$num");
    	 
    	$display_free_charge = $params->get('display_free_charge');
    
    	if (!is_null($courseprice) && !($param_show == 0)) {
    		$price_netto = JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $courseprice)), 2));
    		$price_brutto = JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $courseprice)) * (1 + $tax_rate), 2));
    
    		$dp_chck = '<input id="booking_price'.$num.'" type="radio" value="' . ($num-1) . '" checked="checked" name="booking_price[]" '.$jsfunction.' style="clear: left;" >';
    		$dp_unck = '<input id="booking_price'.$num.'" type="radio" value="' . ($num-1) . '" name="booking_price[]" '.$jsfunction.' style="clear: left;" >';
    
    		$label_n = '<label for="jformbookingprice'.$num.'" >'
    		. $priceg_name . ' (' . $price_netto . ' ' . $currency . ')</label>';
    		$label_nb = '<label for="jformbookingprice'.$num.'" >'
    		. $priceg_name . ' (' . JText::_('COM_SEMINARMAN_NET') . ': ' . $price_netto . ' ' . $currency . ', ' .
    		JText::_('COM_SEMINARMAN_GROSS_WITH_TAX') . ': ' . $price_brutto . ' ' . $currency . ')</label>';
    		$label_ng = '<label for="jformbookingprice'.$num.'" >'
    		. $priceg_name . '<span style="display: none;"> ' . $price_netto . ' ' . $currency . ' </span> ' . '</label>';
    
    		if (!empty($display_free_charge) && ($courseprice == 0)) {
    			$label_n = '<label for="jformbookingprice'.$num.'" >'
    			. $priceg_name . ' (' . JText::_($params->get('display_free_charge')) . ')</label>';
    			$label_nb = '<label for="jformbookingprice'.$num.'" >'
    			. $priceg_name . ' (' . JText::_($params->get('display_free_charge')) . ')</label>';
    		}
    
    		if ($param_show == 1) {  // Anzeige nur für getroffene Nutzer
    			if (array_intersect($current_usergroups, $priceg_usg)) { // getroffen
    
    				for( $i = 1; $i < $num; $i++ ) {
    					$display_party[ "display_price$num" ] = '';
    				}
    				$display_price = '<br>'. $dp_chck . $label_nb;
    				if($coursevat == 0) {
    					$display_price = '<br>'. $dp_chck . $label_n;
    				}
    			}
    			else {  // nicht getroffen
    				$display_price = '';
    			}
    		} elseif ($param_show == 2) {  // Anzeige für alle Nutzer
    			if (array_intersect($current_usergroups, $priceg_usg)) { // getroffen
    				$display_party[ 'display_price1' ] = '<input id="booking_price1" type="radio" value="0" name="booking_price[]" style="clear: left;" '.$jsfunction.' ><label for="jformbookingprice1">' . $price1_label . '</label>';
    				$display_price = '<br>'.$dp_chck . $label_nb;
    				if($coursevat == 0) {
    					$display_price = '<br>'.$dp_chck . $label_n;
    				}
    			}
    			else {
    				$display_price = '<br>'.$dp_unck . $label_nb;
    				if($coursevat == 0) {
    					$display_price = '<br>'.$dp_unck . $label_n;
    				}
    			}
    		} elseif ($param_show == 3) {  // Anzeige ohne Preiswert
    			if (array_intersect($current_usergroups, $priceg_usg)) { // getroffen
    				$display_party[ 'display_price1' ] = '<input id="booking_price1" type="radio" value="0" name="booking_price[]" style="clear: left;" '.$jsfunction.' ><label for="jformbookingprice1">' . $price1_label . '</label>';
    				$display_price = '<br>'.$dp_chck . $label_ng;
    			} else {
    				$display_price = '<br>'.$dp_unck . $label_ng;
    			}
    		}
    	} else { // Anzeige für keine Nutzer oder kein x. Preis definiert
    		$display_price = '';
    	}
    
    	$display_party[ "display_price$num"] = $display_price;
    	return true;
    }    
}

class SeminarmanFunctions {
    static function isVMEnabled(){
        $db = JFactory::getDbo();
        $db->setQuery("SELECT enabled FROM #__extensions WHERE name = 'virtuemart'");
        $vm_enabled = ($db->loadResult() == 1);
        if(!$vm_enabled){
            return false;
        } else {
            return true;	
        } 	
    }
    
    static function isVMEngineEnabled() {
    	$db = JFactory::getDbo();
    	$db->setQuery("SELECT enabled FROM #__extensions WHERE name = 'plg_seminarman_vmengine'");
    	$vmengine_enabled = ($db->loadResult() == 1);
    	if(!$vmengine_enabled){
    		return false;
    	} else {
    		return true;
    	}    	
    }
    
    static function isVMSMPlgEnabled() {
    	$db = JFactory::getDbo();
    	$db->setQuery("SELECT enabled FROM #__extensions WHERE name = 'plg_vmcustom_smansync'");
    	$vmsmplg_enabled = ($db->loadResult() == 1);
    	if(!$vmsmplg_enabled){
    		return false;
    	} else {
    		return true;
    	}
    }
    
    static function isSmanbookingPlgEnabled() {
    	$db = JFactory::getDbo();
    	$db->setQuery("SELECT enabled FROM #__extensions WHERE element = 'smanbooking'");
    	$smbookingplg_enabled = ($db->loadResult() == 1);
    	if(!$smbookingplg_enabled){
    		return false;
    	} else {
    		return true;
    	}
    }
    
    static function isSmanwaitingPlgEnabled() {
    	$db = JFactory::getDbo();
    	$db->setQuery("SELECT enabled FROM #__extensions WHERE element = 'smanwaiting'");
    	$smwaitingplg_enabled = ($db->loadResult() == 1);
    	if(!$smwaitingplg_enabled){
    		return false;
    	} else {
    		return true;
    	}
    }
    
    static function isSmanpdflistPlgEnabled() {
    	$db = JFactory::getDbo();
    	$db->setQuery("SELECT enabled FROM #__extensions WHERE element = 'smanpdflist'");
    	$smpdflistplg_enabled = ($db->loadResult() == 1);
    	if(!$smpdflistplg_enabled){
    		return false;
    	} else {
    		return true;
    	}    	
    }
}
