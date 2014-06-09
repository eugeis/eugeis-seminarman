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

class seminarmanViewsalesprospect extends JViewLegacy
{
    function display($tpl = null)
    {
        $mainframe = JFactory::getApplication();
        $childviewname = 'salesprospect';


        if ($this->getLayout() == 'form')
        {
            $this->_displayForm($tpl);
            return;
        }

        $application = $this->get('data');

        if ($application->url)
        {

            $mainframe->redirect($application->url);
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
        $document = JFactory::getDocument();
        $lang = JFactory::getLanguage();

        $document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend.css');
        if ($lang->isRTL())
        {
            $document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend_rtl.css');
        }

        require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_seminarman' . DS .
            'helpers' . DS . 'seminarman.php');
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
            $application->approved = 1;
            $application->order = 0;
        }
    	$params = JComponentHelper::getParams( 'com_seminarman' );
    	$application->currency_price = $params->get( 'currency' );

        $query = 'SELECT ordering AS value, CONCAT_WS(\' \', first_name, last_name) AS text FROM #__seminarman_salesprospect ORDER BY ordering';

        // $lists['ordering'] = JHTML::_('list.specificordering', $application, $application-> id, $query);
        $lists['ordering'] = JHTML::_('list.ordering', $application-> id, $query);
        $lists['salutation'] = JHTMLSeminarman::getListFromXML('Salutation', 'salutation', 0, $application->salutation);
        $lists['username'] = JHTMLSeminarman::getSelectUser('user_id', $application->user_id, $disabled);

        JFilterOutput::objectHTMLSafe($group, ENT_QUOTES, 'description');

        $nulldate = $db->getNullDate();
    	$customfields	= $model->getEditableCustomfields( $application->id );
    	$user->customfields	=& $customfields;
    	$this->assignRef( 'user' , $user );
        $this->assignRef('lists', $lists);
        $this->assignRef('nullDate', $nulldate);
        $this->assignRef('application', $application);

        parent::display($tpl);
    }
}