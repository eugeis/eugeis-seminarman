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

class SeminarmanViewSettings extends JView
{
	protected $rows;
	protected $pagiNav;
	protected $emailTemplates;
    
    function display($tpl = null)
    {
    	require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_seminarman' . DS .
            'helpers' . DS . 'seminarman.php');
        
        if(JHTMLSeminarman::UserIsCourseManager()){    	
    	
        $user = &JFactory::getUser();
        $db = &JFactory::getDBO();
        $document = &JFactory::getDocument();

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
        
        require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_seminarman' . DS .
            'helpers' . DS . 'seminarman.php');
        
        $lists['usergroups2'] = JHTMLSeminarman::getUsergroups('id_usergroup2', $priceG2->reg_group, 0);
        $lists['usergroups3'] = JHTMLSeminarman::getUsergroups('id_usergroup3', $priceG3->reg_group, 0);
        $lists['usergroups4'] = JHTMLSeminarman::getUsergroups('id_usergroup4', $priceG4->reg_group, 0);
        $lists['usergroups5'] = JHTMLSeminarman::getUsergroups('id_usergroup5', $priceG5->reg_group, 0);

        $query = 'SELECT id AS value, CONCAT_WS(\' / \', username, name, id ) AS text FROM #__users ORDER BY username'; 
        $db->setQuery($query);
        $items = $db->loadObjectList();
        $manager_list = "";
        $vmpub_list = "";
        
        foreach ($items as $item) {
        	// $member = JFactory::getUser($item->id);
        	if (JHTMLSeminarman::UserIsCourseManager($item->value)){
                // jimport('joomla.application.component.helper');
                // $component = JComponentHelper::getComponent('com_virtuemart', true);                
                
                $params = JComponentHelper::getParams('com_seminarman');
                
                // if ((!($component->enabled)) || ($params->get('trigger_virtuemart') == 0)) {
                if ((!SeminarmanFunctions::isVMEnabled()) || ($params->get('trigger_virtuemart') == 0)) {
                    $vmpublisher = "";
                } else {
                	if (($this->manager_is_vm_publisher($item->value)) || ($this->manager_is_admin($item->value))) {
                		$vmpublisher = "VirtueMart Publisher: <b>YES</b>";
                	} else {
                	    $vmpublisher = "VirtueMart Publisher: <b>NO</b>";	
                	}
                }
        		$manager_list = $manager_list . '<br>' . $item->text;
        		$vmpub_list = $vmpub_list . '<br>' . $vmpublisher;
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
        $this->assignRef('managerlist', $manager_list);
        $this->assignRef('vmpublist', $vmpub_list);
		$this->addToolbar();
        parent::display($tpl);
        
        }else{
        	
$app =& JFactory::getApplication();
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
    	JSubMenuHelper::addEntry(JText::_('COM_SEMINARMAN_SETTINGS'),'index.php?option=com_seminarman&view=settings', true);


        JToolBarHelper::preferences('com_seminarman'); 
        $this->document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend.css');
        $lang = &JFactory::getLanguage();        
        if ($lang->isRTL())
        {
            $this->document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend_rtl.css');
        }               
	}

    function manager_is_vm_publisher($mid) {
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
    
    function manager_is_admin($mid) {
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