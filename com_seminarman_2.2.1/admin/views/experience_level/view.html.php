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

class seminarmanViewExperience_level extends JView
{
    function display($tpl = null)
    {
        $mainframe = JFactory::getApplication();
        $childviewname = 'experience_level';

        if ($this->getLayout() == 'form')
        {
            $this->_displayForm($tpl);
            return;
        }

        $experience_level = $this->get('data');

        if ($experience_level->url)
        {

            $mainframe->redirect($experience_level->url);
        }

        parent::display($tpl);
    }

    function _displayForm($tpl)
    {
        $mainframe = JFactory::getApplication();

        $db = JFactory::getDBO();
    	$document = JFactory::getDocument();
        $uri = JFactory::getURI();
        $user = JFactory::getUser();
        $model = $this->getModel();
    	$lists = array();

        $experience_level = $this->get('data');
        $isNew = ($experience_level->id < 1);

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

            $experience_level->published = 1;
            $experience_level->approved = 1;
            $experience_level->order = 0;
        }

        $query = 'SELECT ordering AS value, title AS text' .
            ' FROM #__seminarman_experience_level' . ' ORDER BY ordering';

        $lists['ordering'] = JHTML::_('list.specificordering', $experience_level, $experience_level->
            id, $query);

        $lists['published'] = JHTML::_('select.booleanlist', 'published',
            'class="inputbox"', $experience_level->published);

        JFilterOutput::objectHTMLSafe($group, ENT_QUOTES, 'description');

        $file = JPATH_COMPONENT . DS . 'models' . DS . 'experience_level.xml';
        $params = new JParameter($experience_level->params, $file);


        $this->assignRef('lists', $lists);
        $this->assignRef('experience_level', $experience_level);
        $this->assignRef('params', $params);

        parent::display($tpl);
    }
}