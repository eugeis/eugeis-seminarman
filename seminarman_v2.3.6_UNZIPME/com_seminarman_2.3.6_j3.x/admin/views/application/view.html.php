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

class seminarmanViewapplication extends JViewLegacy
{
    function display($tpl = null)
    {
        $mainframe = JFactory::getApplication();
        $childviewname = 'application';

        if ($this->getLayout() == 'form')
        {
            $this->_displayForm($tpl);
            return;
        }

        if ($this->getLayout() == 'invoicepdf')
        {
        	$this->_invoicepdf();
        	return;
        }        

        $application = $this->get('data');

        if ($application->url)
        {
            $mainframe->redirect($application->url);
        }

        parent::display();
    }
    
    function _invoicepdf()
    {   
    	$mainframe = JFactory::getApplication();
    	$application = $this->get('data');
    	$params = JComponentHelper::getParams( 'com_seminarman' );
    	
    	$filename = $application->invoice_filename_prefix.$application->invoice_number.'.pdf';
    	$filepath = JPATH_ROOT.DS.$params->get('invoice_save_dir').DS.$filename;
    	
    	jimport('joomla.filesystem.file');
    	if (!$pdf_data = JFile::read($filepath))
    		$mainframe->redirect('index.php?option=com_seminarman&view=applications');
    	
    	ob_end_clean();
    	header('Content-Type: application/pdf');
    	header('Content-Disposition: attachment; filename="'. $filename .'"');
    	print $pdf_data;
    	flush();
    	exit;
   		
    }
    
    function _displayForm($tpl)
    {
        $mainframe = JFactory::getApplication();

        $db = JFactory::getDBO();
        $uri = JFactory::getURI();
        $user = JFactory::getUser();
        $model = $this->getModel();
        $document = JFactory::getDocument();
        $lang = JFactory::getLanguage();

        $document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend.css');
        if ($lang->isRTL())
        {
            $document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend_rtl.css');
        }

        require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_seminarman' . DS . 'helpers' . DS . 'seminarman.php');
        require_once( JPATH_ROOT . DS . 'components' . DS . 'com_seminarman' . DS . 'libraries' . DS . 'customfields.php' );

        $lists = array();

        $application = $this->get('data');
        $isNew = ($application->id < 1);

        if ($model->isCheckedOut($user->get('id')))
        {
            $msg = JText::_('COM_SEMINARMAN_RECORD_EDITED');
            $mainframe->redirect('index.php?option=' . $option, $msg);
        }

        if (!$isNew)
        {
            $model->checkout($user->get('id'));
            $disabled = 1;
            $formType['isNew'] = null;
        } else
        {
            $formType['isNew'] = 1;
            $disabled = 0;
            $application->published = 1;
            $application->approved = 1;
            $application->order = 0;
        }
    	$params = JComponentHelper::getParams( 'com_seminarman' );
    	$application->currency_price = $params->get( 'currency' );

        $query = 'SELECT ordering AS value, CONCAT_WS(\' \', first_name, last_name) AS text' .
            ' FROM #__seminarman_application' . ' ORDER BY ordering';

        // $lists['ordering'] = JHTML::_('list.specificordering', $application, $application->id, $query);
        $lists['ordering'] = JHTML::_('list.ordering', $application->id, $query);
        $lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $application->published);
        $lists['salutation'] = JHTMLSeminarman::getListFromXML('Salutation', 'salutation', 0, $application->salutation);
        $lists['username'] = JHTMLSeminarman::getSelectUser('user_id', $application->user_id, $disabled);

    	// build status list
    	$statuslist[] = JHTML::_('select.option',  '0', JText::_( 'COM_SEMINARMAN_SUBMITTED' ), 'value', 'text' );
    	$statuslist[] = JHTML::_('select.option',  '1', JText::_( 'COM_SEMINARMAN_PENDING' ), 'value', 'text' );
    	$statuslist[] = JHTML::_('select.option',  '2', JText::_( 'COM_SEMINARMAN_PAID' ), 'value', 'text' );
    	$statuslist[] = JHTML::_('select.option',  '3', JText::_( 'COM_SEMINARMAN_CANCELED' ), 'value', 'text' );
    	$lists['status'] = JHTML::_('select.genericlist', $statuslist, 'status', 'class="inputbox" size="1"','value', 'text', $application->status );

    	JFilterOutput::objectHTMLSafe($group, ENT_QUOTES, 'description');

    	$customfields	= $model->getEditableCustomfields( $application->id );
    	$user->customfields	=& $customfields;
    	$this->assignRef('user' , $user);
        $this->assignRef('lists', $lists);
        $this->assignRef('application', $application);

        parent::display($tpl);
    }
}