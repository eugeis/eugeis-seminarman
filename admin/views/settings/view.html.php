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

class SeminarmanViewSettings extends JViewLegacy
{
	protected $rows;
	protected $pagiNav;
	protected $emailTemplates;
    
    function display($tpl = null)
    {
    	require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_seminarman' . DS .
            'helpers' . DS . 'seminarman.php');    	
    	
        if(JHTMLSeminarman::UserIsCourseManager()){    	
    	
	        $user = JFactory::getUser();
	        $db = JFactory::getDBO();
	        $document = JFactory::getDocument();
	        $params = JComponentHelper::getParams('com_seminarman');
	        
	        JHTML::_('behavior.tooltip');
	
	        JToolBarHelper::title(JText::_('COM_SEMINARMAN_SETTINGS'), 'smconfig');
	
	        $rows = $this->get('Data');
	        $pageNav = $this->get('Pagination');
	        $emailTemplates = $this->get('EmailTemplates');
	        
	        $pdfTemplates = $this->get('PdfTemplates');
	        foreach ($pdfTemplates as $tmpl) {
	        	switch ($tmpl->templatefor) {
	        		case 1:
	        			$tmpl->templateforStr = JText::_('COM_SEMINARMAN_ATTENDANCE_LIST');
	        			break;
	        		case 0:
	        		default:
	        			$tmpl->templateforStr = JText::_('COM_SEMINARMAN_INVOICES');
	        			break;
	        	}
	        }
	        
	        $priceG2 = $this->get('PriceG2');
	        $priceG3 = $this->get('PriceG3');
	        $priceG4 = $this->get('PriceG4');
	        $priceG5 = $this->get('PriceG5');
	        
	        $lists = array();
	        
	        $lists['usergroups2'] = JHTMLSeminarman::getUsergroups('id_usergroup2', $priceG2->reg_group, 0);
	        $lists['usergroups3'] = JHTMLSeminarman::getUsergroups('id_usergroup3', $priceG3->reg_group, 0);
	        $lists['usergroups4'] = JHTMLSeminarman::getUsergroups('id_usergroup4', $priceG4->reg_group, 0);
	        $lists['usergroups5'] = JHTMLSeminarman::getUsergroups('id_usergroup5', $priceG5->reg_group, 0);
	
	        $query = 'SELECT id AS value, CONCAT_WS(\' / \', username, name, id ) AS text FROM #__users ORDER BY username'; 
	        $db->setQuery($query);
	        $items = $db->loadObjectList();
	        $manager_list = "";
	        $manager_access_lst="";
	        $vmpub_list = "";
	        
	        foreach ($items as $item) {
	        	// $member = JFactory::getUser($item->id);
	        	if (JHTMLSeminarman::UserIsCourseManager($item->value)){
	                // jimport('joomla.application.component.helper');
	                // $component = JComponentHelper::getComponent('com_virtuemart', true);                                
	                // if ((!($component->enabled)) || ($params->get('trigger_virtuemart') == 0)) {
	                if ((!SeminarmanFunctions::isVMEnabled()) || ($params->get('trigger_virtuemart') == 0)) {
	                    $vmpublisher = "";
	                } else {
	                	if (($this->user_is_vm_publisher($item->value)) || ($this->user_is_admin($item->value))) {
	                		$vmpublisher = "<b>YES</b>";
	                	} else {
	                	    $vmpublisher = "<b>NO</b>";	
	                	}
	                }
	        		$manager_list = $manager_list . $item->text . '<br />';
	        		$manager_access_lst = $manager_access_lst . JText::_('COM_SEMINARMAN_ACCESS_FULL') . '<br />';
	        		$vmpub_list = $vmpub_list . $vmpublisher . '<br />';
	        	}
	        } 
	
	        $query_manager_grp = $db->getQuery(true);
	        $query_manager_grp->select('jm_id')
	              ->from('#__seminarman_usergroups')
	              ->where('sm_id = 1');
	        $db->setQuery($query_manager_grp);
	        $foundManagerGrp = $db->loadResult();
	        if (is_null($foundManagerGrp)) {
	        	$manager_grp = "";
	        } elseif ($foundManagerGrp > 0) {
	        	// is it valid?
	        	$query_jm = $db->getQuery(true);
	        	$query_jm->select('*')
	        	->from('#__usergroups')
	        	->where('id = ' . $foundManagerGrp);
	        	$db->setQuery($query_jm);
	        	$result_jm = $db->loadAssoc();
	        	if (!is_null($result_jm)) {
	        		// valid joomla group
	        		$manager_grp = $result_jm['title'] . ' / ' . $result_jm['id'];
	        	} else {
	        		// not valid
	        		$manager_grp = "";
	        	}
	        } else {
	        	$manager_grp = "";
	        }
	        
	        $query_tutor_grp = $db->getQuery(true);
	        $query_tutor_grp->select('jm_id')
	        ->from('#__seminarman_usergroups')
	        ->where('sm_id = 2');
	        $db->setQuery($query_tutor_grp);
	        $foundTutorGrp = $db->loadResult();
	        $tutor_grp_id = 0;
	        if (is_null($foundTutorGrp)) {
	        	$tutor_grp = "";
	        } elseif ($foundTutorGrp > 0) {
	        	// is it valid?
	        	$query_jm = $db->getQuery(true);
	        	$query_jm->select('*')
	        	->from('#__usergroups')
	        	->where('id = ' . $foundTutorGrp);
	        	$db->setQuery($query_jm);
	        	$result_jm = $db->loadAssoc();
	        	if (!is_null($result_jm)) {
	        		// valid joomla group
	        		$tutor_grp = $result_jm['title'] . ' / ' . $result_jm['id'];
	        		$tutor_grp_id = $result_jm['id'];
	        	} else {
	        		// not valid
	        		$tutor_grp = "";
	        	}
	        } else {
	        	$tutor_grp = "";
	        }
	        
	        $tutor_list = "";
	        $tutor_access_lst = "";
	        $tutor_vm_publist = "";
	        if (!empty($tutor_grp_id)) {
	        	jimport('joomla.access.access');
	            $tutor_users = JAccess::getUsersByGroup($tutor_grp_id);
	            jimport( 'joomla.user.user' );
	            foreach($tutor_users as $tutor_user_id){
	                $tutor_user = JFactory::getUser($tutor_user_id);
	                $tutor_list = $tutor_list . $tutor_user->username . ' / ' . $tutor_user->name . ' / ' . $tutor_user->id . '<br />';
	                if (JHTMLSeminarman::UserIsCourseManager($tutor_user_id)){
	                	$tutor_access_lst = $tutor_access_lst . JText::_('COM_SEMINARMAN_ACCESS_FULL') . '<br />';
	                } else {
	                	$tutor_access_lst = $tutor_access_lst . JText::_('COM_SEMINARMAN_ACCESS_RESTRICTED') . '<br />';
	                }
	                if ((!SeminarmanFunctions::isVMEnabled()) || ($params->get('trigger_virtuemart') == 0)) {
	                	$vmpublisher = "";
	                } else {
	                	if (($this->user_is_vm_publisher($tutor_user_id)) || ($this->user_is_admin($tutor_user_id))) {
	                		$vmpublisher = "<b>YES</b>";
	                	} else {
	                		$vmpublisher = "<b>NO</b>";
	                	}
	                }
	                $tutor_vm_publist = $tutor_vm_publist . $vmpublisher . '<br />';
	            }
	        }
	        
	        //we delivered an error within the versions from 2.0.1 to 2.1.1alpha3, this error exists only within the new installation of those vesions.
	        //here a check
	        $db_stati = 1;
	        
	        $result_check = $db->getTableColumns('#__seminarman_courses');
	        if (!(isset($result_check['price4']))) {
	        	// field price4 doesn't exist in table courses.
	        	$db_stati = 0;
	        }
	        if (!(isset($result_check['price5']))) {
	        	// field price5 doesn't exist in table courses.
	        	$db_stati = 0;
	        }
	        
	        $result_check = $db->getTableColumns('#__seminarman_templates');
	        if (!(isset($result_check['price4']))) {
	        	// field price4 doesn't exist in table course templates.
	        	$db_stati = 0;
	        }
	        if (!(isset($result_check['price5']))) {
	        	// field price5 doesn't exist in table course templates.
	        	$db_stati = 0;
	        }
	        
	        $query_check = $db->getQuery(true);
	        $query_check->select('*')
	                    ->from('#__seminarman_pricegroups')
	                    ->where('id=3');
	        $db->setQuery($query_check);
	        $result_check = $db->loadAssoc();
	        if (is_null($result_check)) {
	        	// initial price group 4 was not registered in db.
	        	$db_stati = 0;
	        }
	        
	        $query_check = $db->getQuery(true);
	        $query_check->select('*')
	                    ->from('#__seminarman_pricegroups')
	                    ->where('id=4');
	        $db->setQuery($query_check);
	        $result_check = $db->loadAssoc();
	        if (is_null($result_check)) {
	        	// initial price group 5 was not registered in db.
	        	$db_stati = 0;
	        }
	        
	        $result_tables = $db->getTableList();
	        $dVar=new JConfig();
	        $dPre = $dVar->dbprefix;
	        $result_check = in_array($dPre . 'seminarman_fields_values_tutors', $result_tables);
	        if ($result_check == false) {
	        	// table of custom fields values for tutors doesn't exist.
	        	$db_stati = 0;
	        }
	        // End DB Schema Check
	        
	        // check if the seminar manager and trainer groups have the proper rights in joomla as well as seminar manager component
	        
	        $grp_rights = 1;
	        
	        if ((empty($manager_grp)) || (empty($tutor_grp))) {
	        	// at least one of the both groups not created yet
	        	$grp_rights = 0;
	        } else {
	        	$asset	= JTable::getInstance('asset');
	        	if ($asset->loadByName('root.1')) {
	        		$rules_json = $asset->rules;
	        		$rules_array = json_decode($rules_json, true);
	        		// check frontend login
	        		if ((!(isset($rules_array['core.login.site'][$foundManagerGrp]))) || (!(isset($rules_array['core.login.site'][$foundTutorGrp])))) {
	        			$grp_rights = 0;
	        		} else {
	        			if (!(($rules_array['core.login.site'][$foundManagerGrp] == 1) && ($rules_array['core.login.site'][$foundTutorGrp] == 1))) {
	        				$grp_rights = 0;
	        			}
	        		}
	        		// check backend login
	        		if ((!(isset($rules_array['core.login.admin'][$foundManagerGrp]))) || (!(isset($rules_array['core.login.admin'][$foundTutorGrp])))) {
	        			$grp_rights = 0;
	        		} else {
	        			if (!(($rules_array['core.login.admin'][$foundManagerGrp] == 1) && ($rules_array['core.login.admin'][$foundTutorGrp] == 1))) {
	        				$grp_rights = 0;
	        			}
	        		}
	        	}
	        	
	        	// are the joomla backend menu and toolar visible for the both groups?
	        	$level	= JTable::getInstance('viewlevel');
	        	if ($level->load(3)){
	        		$rules_json = $level->rules;
	        		$rules_array = json_decode($rules_json, true);
	        		if (!(in_array(intval($foundManagerGrp), $rules_array) && in_array(intval($foundTutorGrp), $rules_array))) {
	        			$grp_rights = 0;
	        		}
	        	}
	        	
	        	// do the both groups have access to our component?
	        	$asset	= JTable::getInstance('asset');
	        	if ($asset->loadByName('com_seminarman'))
	        	{
	        		$rules_json = $asset->rules;
	        		$rules_array = json_decode($rules_json, true);
	        		if ((!(isset($rules_array['core.manage'][$foundManagerGrp]))) || (!(isset($rules_array['core.manage'][$foundTutorGrp])))) {
	        			$grp_rights = 0;
	        		} else {
	        		    if (!(($rules_array['core.manage'][$foundManagerGrp] == 1) && ($rules_array['core.manage'][$foundTutorGrp] == 1))) {
	        			    $grp_rights = 0;
	        		    }
	        		}
	        	}        	
	        }

	        // VirtueMart Integration Special
	        
	        $vm_status = "";
	        $vmengine_status ="";
	        $vm_sm_plg_status = "";
	        $vm_rel_dbstati = 1;
	        $vm_root_cat = "";
	        $vm_compat_currency = "";
	        $vm_compat_tax = "";
	        $vm_applied_grps = "";
	        $vm_applied_rules = '<font color="orange">Due to the new price model in the newest VirtueMart it\'s not necessary any more</font>';
	        
	        if ($params->get('trigger_virtuemart') == 1) {
	        	require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'liveupdate'.DS.'classes'.DS.'xmlslurp.php';
	        	if(SeminarmanFunctions::isVMEnabled()) {
	        		$xmlslurp = new LiveUpdateXMLSlurp();
	        		$data = $xmlslurp->getInfo('com_virtuemart', 'virtuemart.xml');
	        		$vm_status = '<font color="green">Running</font> (' . $data['version'] . ')';

	        		// find a compatible currency in virtuemart
	        		$sm_currency_code = $params->get('currency');
	        		$query_currency = $db->getQuery(true);
	        		$query_currency->select('*')
	        		->from('#__virtuemart_currencies')
	        		->where('currency_code_3 = "' . $sm_currency_code . '"');
	        		$db->setQuery($query_currency);
	        		$result_currency = $db->loadAssoc();
	        		if (is_null($result_currency)) {
	        			$vm_compat_currency = '<font color="red">Not found</font>';
	        			$vm_compa_currency_id = 0;
	        		} else {
	        			$vm_compat_currency = '<font color="green">Found</font> (' . $sm_currency_code .')';
	        			$vm_compa_currency_id = $result_currency['virtuemart_currency_id'];
	        		}
	        		
	        		// find a compatible tax rule in virtuemart
	        		if (version_compare($data['version'], '2.0.8', 'ge')) {
	        			$tax_type = 'VatTax';
	        		} else {
	        			$tax_type = 'Tax';
	        		}
	        		
	        		$sm_vat = $params->get('vat');
	        		$query = $db->getQuery(true);
	        		$query->select('*')
	        		->from('#__virtuemart_calcs')
	        		->where('calc_currency = ' . $vm_compa_currency_id)
	        		->where('published = 1')
	        		->where('calc_kind = "' . $tax_type . '"')
	        		->where('calc_value_mathop = "+%"')
	        		->where('calc_value = ' . $sm_vat);
	        		$db->setQuery($query);
	        		$result = $db->loadAssoc();	        		
	        		if (!(is_null($result))) {
	        			$vm_compat_tax = '<font color="green">Found</font> <small>Seminarman: ' . JText::_('COM_SEMINARMAN_VAT') . ' ' . $sm_vat .'% / VirtueMart: ' . $result['calc_name'] . ' (ID: ' . $result['virtuemart_calc_id'] . ')</small>';
	        		} else {
	        			$vm_compat_tax = '<font color="red">Not found</font>';
	        		}
	        		
	        	} else {
	        		$vm_status = '<font color="red">Not installed or not enabled</font>';
	        	}
	        
	        	if(SeminarmanFunctions::isVMEngineEnabled()) {
	        		$xmlslurp = new LiveUpdateXMLSlurp();
	        		$data = $xmlslurp->getInfo('plg_seminarman_vmengine', 'vmengine.xml');
	        		$vmcompat = explode('|', $data['description']);
	        		$vmengine_status = isset($vmcompat[1]) ? '<font color="green">Running</font> (' . $data['version'] . ')<br /><small>' . $vmcompat[1] . '</small>' : '<font color="green">Running</font> (' . $data['version'] . ')';
	        	} else {
	        		$vmengine_status = '<font color="red">Not installed or not enabled</font>';
	        	}
	        	
	        	if(SeminarmanFunctions::isVMSMPlgEnabled()) {
	        		$xmlslurp = new LiveUpdateXMLSlurp();
	        		$data = $xmlslurp->getInfo('plg_vmcustom_smansync', 'smansync.xml');
	        		$vmcompat = explode('|', $data['description']);
	        		$vm_sm_plg_status = isset($vmcompat[1]) ? '<font color="green">Running</font> (' . $data['version'] . ')<br /><small>' . $vmcompat[1] . '</small>' : '<font color="green">Running</font> (' . $data['version'] . ')';
	        	} else {
	        		$vm_sm_plg_status = '<font color="red">Not installed or not enabled</font>';
	        	}
	        	
	        	$lst_tables = $db->getTableList();
	        	$dVar=new JConfig();
	        	$dPre = $dVar->dbprefix;
	        	$result_check = in_array($dPre . 'seminarman_vm_cat_map', $lst_tables);
	        	if ($result_check == false) {
	        		$vm_rel_dbstati = 0;
	        	} else {
	        		// check if a special line with sm_cat_id = 0 exists
	        		$query_base = $db->getQuery(true);
	        		$query_base->select('vm_cat_id')
	        		->from('#__seminarman_vm_cat_map')
	        		->where('sm_cat_id = 0');
	        		$db->setQuery($query_base);
	        		$foundBaseID = $db->loadResult();
	        		if (is_null($foundBaseID)) {
	        			$vm_rel_dbstati = 0;
	        		} else {
	        			if ($foundBaseID > 0) {
	        				// is it valid?
	        				$query_vm = $db->getQuery(true);
	        				$query_vm->select('*')
	        				->from('#__virtuemart_categories')
	        				->where('virtuemart_category_id = ' . $foundBaseID);
	        				$db->setQuery($query_vm);
	        				$result_vm = $db->loadAssoc();
	        				if (!is_null($result_vm)) {
	        					$vm_root_cat = '<font color="green">Available</font> (ID: ' . $foundBaseID . ')';
	        				}
	        			}
	        		}
	        	}	        	
	        	$result_check = in_array($dPre . 'seminarman_vm_course_product_map', $lst_tables);
	        	if ($result_check == false) {
	        		$vm_rel_dbstati = 0;
	        	}
	        	$result_check = in_array($dPre . 'seminarman_vm_shopper_map', $lst_tables);
	        	if ($result_check == false) {
	        		$vm_rel_dbstati = 0;
	        	} else {
	        		// check if the both lines for price group 2 & 3 exist
	        		$query_p2 = $db->getQuery(true);
	        		$query_p2->select('vm_shopper_gid')
	        		->from('#__seminarman_vm_shopper_map')
	        		->where('sm_price_gid = 2');
	        		$db->setQuery($query_p2);
	        		$foundVMP2 = $db->loadResult();
	        		if (is_null($foundVMP2)) {
	        			$vm_rel_dbstati = 0;
	        		}
	        		$query_p3 = $db->getQuery(true);
	        		$query_p3->select('vm_shopper_gid')
	        		->from('#__seminarman_vm_shopper_map')
	        		->where('sm_price_gid = 3');
	        		$db->setQuery($query_p3);
	        		$foundVMP3 = $db->loadResult();
	        		if (is_null($foundVMP3)) {
	        			$vm_rel_dbstati = 0;
	        		}
	        		
	        		// find customer groups and discount rules in virtuemart, which are associated with seminarman
	        		if(SeminarmanFunctions::isVMEnabled()) {
	        			if($foundVMP2 > 0) {
	        				$query = $db->getQuery(true);
	        				$query->select('*')
	        				->from('#__virtuemart_shoppergroups')
	        				->where('published = 1')
	        				->where('virtuemart_shoppergroup_id = ' . $foundVMP2);
	        				$db->setQuery($query);
	        				$result = $db->loadAssoc();
	        				if(!(is_null($result))) {
	        					$vm_applied_grps = $result['shopper_group_name'] . ' / ID: ' . $result['virtuemart_shoppergroup_id'];
	        				}
	        			}
	        			
	        			if($foundVMP3 > 0) {
	        				$query = $db->getQuery(true);
	        				$query->select('*')
	        				->from('#__virtuemart_shoppergroups')
	        				->where('published = 1')
	        				->where('virtuemart_shoppergroup_id = ' . $foundVMP3);
	        				$db->setQuery($query);
	        				$result = $db->loadAssoc();
	        				if(!(is_null($result))) {
	        					if (empty($vm_applied_grps)) {
	        					    $vm_applied_grps = $result['shopper_group_name'] . ' / ID: ' . $result['virtuemart_shoppergroup_id'];
	        					} else {
	        						$vm_applied_grps = $vm_applied_grps . '<br />' . $result['shopper_group_name'] . ' / ID: ' . $result['virtuemart_shoppergroup_id'];
	        					}
	        				}	        				 
	        			}
	        		}
	        	}
	        	
	        }
	        
	        $this->assignRef('lists', $lists);
	        $this->assignRef('rows', $rows);
	        $this->assignRef('pageNav', $pageNav);
	        $this->assignRef('emailTemplates', $emailTemplates);
	        $this->assignRef('pdfTemplates', $pdfTemplates);
	        $this->assignRef('priceG2', $priceG2);
	        $this->assignRef('priceG3', $priceG3);
	        $this->assignRef('priceG4', $priceG4);
	        $this->assignRef('priceG5', $priceG5);
	        $this->assignRef('ManagerGrp', $manager_grp);
	        $this->assignRef('TutorGrp', $tutor_grp);
	        $this->assignRef('managerlist', $manager_list);
	        $this->assignRef('manageraccesslst', $manager_access_lst);
	        $this->assignRef('managervmpublist', $vmpub_list);
	        $this->assignRef('tutorlist', $tutor_list);
	        $this->assignRef('tutoraccesslst', $tutor_access_lst);
	        $this->assignRef('tutorvmpublist', $tutor_vm_publist);
	        $this->assignRef('dbstati', $db_stati);
	        $this->assignRef('grprights', $grp_rights);
	        $this->assignRef('params', $params);
	        $this->assignRef('vmstati', $vm_status);
	        $this->assignRef('vmenginestati', $vmengine_status);
	        $this->assignRef('vmsmstati', $vm_sm_plg_status);
	        $this->assignRef('vmreldbstati', $vm_rel_dbstati);
	        $this->assignRef('vmrootcat', $vm_root_cat);
	        $this->assignRef('vmcompacurrency', $vm_compat_currency);
	        $this->assignRef('vmcompatax', $vm_compat_tax);
	        $this->assignRef('vmappliedgrps', $vm_applied_grps);
	        $this->assignRef('vmappliedrules', $vm_applied_rules);
			$this->addToolbar();
	        parent::display($tpl);
        
        }else{        	
			$app = JFactory::getApplication();
			$app->redirect('index.php?option=com_seminarman', 'Only seminar manager group can access settings.');	        	
        }        
    }
    
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_HOME'), 'index.php?option=com_seminarman');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_APPLICATIONS'),'index.php?option=com_seminarman&view=applications');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_LST_OF_SALES_PROSPECTS'), 'index.php?option=com_seminarman&view=salesprospects');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_COURSES'),'index.php?option=com_seminarman&view=courses');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_TEMPLATES'),'index.php?option=com_seminarman&view=templates');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_CATEGORIES'),'index.php?option=com_seminarman&view=categories');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_TAGS'),'index.php?option=com_seminarman&view=tags');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_TUTORS'),'index.php?option=com_seminarman&view=tutors');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_PERIODS'),'index.php?option=com_seminarman&view=periods');
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_SETTINGS'),'index.php?option=com_seminarman&view=settings', true);


        JToolBarHelper::preferences('com_seminarman'); 
        $this->document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend.css');
        $lang = JFactory::getLanguage();        
        if ($lang->isRTL())
        {
            $this->document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend_rtl.css');
        }               
	}

    function user_is_vm_publisher($mid) {
    	$db = JFactory::getDBO();
        $query_vmadmin = 'SELECT * FROM #__virtuemart_vmusers AS v WHERE v.virtuemart_user_id = ' . $mid . ' AND (v.perms = "storeadmin" OR v.perms = "admin")';
        $db->setQuery($query_vmadmin);
        $item_vmuser = $db->loadAssoc();
        if(empty($item_vmuser)){
             return false;
        } else {
             return true;
        }
    }
    
    function user_is_admin($mid) {
        jimport( 'joomla.user.helper' );
        $groups = JUserHelper::getUserGroups($mid);
        //8 is for Super User and 7 is for Administrator
    	foreach($groups as $temp) {
			if(in_array($temp, Array(7,8))){
					return true;
                    break;
			}
		}
		return false;
    }
}

?>
