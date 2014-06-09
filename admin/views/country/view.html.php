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

class seminarmanViewCountry extends JViewLegacy
{
    function display($tpl = null)
    {
        $mainframe = JFactory::getApplication();
        
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

        $country = $this->get('data');

        if ($country->url)
        {

            $mainframe->redirect($country->url);
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

        $country = $this->get('data');
        $isNew = ($country->id < 1);

        if ($model->isCheckedOut($user->get('id')))
        {
            $msg = JText::_('COM_SEMINARMAN_RECORD_EDITED');
            $mainframe->redirect('index.php?option=' . $option, $msg);
        }

        if (!$isNew)
        {
            $model->checkout($user->get('id'));
        } else
        {

            $country->published = 1;
            $country->approved = 1;
            $country->order = 0;
        }

        $query = 'SELECT ordering AS value, title AS text' . ' FROM #__seminarman_country' .
            ' ORDER BY ordering';

        // $lists['ordering'] = JHTML::_('list.specificordering', $country, $country->id, $query);
        $lists['ordering'] = JHTML::_('list.ordering', $country->id, $query);

        $lists['published'] = JHTML::_('select.booleanlist', 'published',
            'class="inputbox"', $country->published);

        JFilterOutput::objectHTMLSafe($country, ENT_QUOTES, 'description');

        $this->assignRef('lists', $lists);
        $this->assignRef('country', $country);

        parent::display($tpl);
    }
}
