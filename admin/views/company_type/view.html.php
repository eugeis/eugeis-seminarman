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

class seminarmanViewcompany_type extends JViewLegacy
{
    function display($tpl = null)
    {
        $mainframe = JFactory::getApplication();
        $childviewname = 'company_type';
        
        $document = JFactory::getDocument();
        $lang = JFactory::getLanguage();
        
        $document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend.css');
        if ($lang->isRTL())
        	$document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend_rtl.css');

        if ($this->getLayout() == 'form')
        {
            $this->_displayForm($tpl);
            return;
        }

        $company_type = $this->get('data');

        if ($company_type->url)
        {

            $mainframe->redirect($company_type->url);
        }

        parent::display($tpl);
    }

    function _displayForm($tpl)
    {
        $mainframe = JFactory::getApplication();

        $db = JFactory::getDBO();
        $uri = JFactory::getURI();
        $user = JFactory::getUser();
        $model = $this->getModel();


        $lists = array();

        $company_type = $this->get('data');
        $isNew = ($company_type->id < 1);

        if ($model->isCheckedOut($user->get('id')))
        {
            $msg = JText::sprintf('COM_SEMINARMAN_RECORD_EDITED', JText::_('COM_SEMINARMAN_COMPANY_TYPE'), $company_type->
                title);
            $mainframe->redirect('index.php?option=' . $option, $msg);
        }

        if (!$isNew)
        {
            $model->checkout($user->get('id'));
        } else
        {

            $company_type->published = 1;
            $company_type->approved = 1;
            $company_type->order = 0;
        }

        $query = 'SELECT ordering AS value, title AS text' .
            ' FROM #__seminarman_company_type' . ' ORDER BY ordering';

        // $lists['ordering'] = JHTML::_('list.specificordering', $company_type, $company_type->id, $query);
        $lists['ordering'] = JHTML::_('list.ordering', $company_type->id, $query);

        $lists['published'] = JHTML::_('select.booleanlist', 'published',
            'class="inputbox"', $company_type->published);

        JFilterOutput::objectHTMLSafe($group, ENT_QUOTES, 'description');

        $file = JPATH_COMPONENT . DS . 'models' . DS . 'company_type.xml';
        $params = new JParameter($company_type->params, $file);
        
        $js_code = "
        	function Joomla.submitbutton(pressbutton) {
        		var form = document.adminForm;
        		if (pressbutton == 'cancel') {
        			Joomla.submitform( pressbutton );
        			return;
        		}
        
        		// do field validation
        		if (form.title.value == ''){
        			alert( ".
        
                JText::_('COM_SEMINARMAN_MISSING_TITLE', true) ." );
        		
                } else {
        			Joomla.submitform( pressbutton );
        		}
        	}
        ";        
        $document = JFactory::getDocument();
        $document->addScriptDeclaration($js_code);

        $this->assignRef('lists', $lists);
        $this->assignRef('company_type', $company_type);
        $this->assignRef('params', $params);

        parent::display($tpl);
    }
}
