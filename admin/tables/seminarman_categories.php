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

class seminarman_categories extends JTable
{

    function seminarman_categories(&$db)
    {
        parent::__construct('#__seminarman_categories', 'id', $db);
    }

    function check()
    {

        if (trim($this->title) == '')
        {
            $this->_error = JText::_('ADD NAME CATEGORY');
            JError::raiseWarning('SOME_ERROR_CODE', $this->_error);
            return false;
        }

        $alias = JFilterOutput::stringURLSafe($this->title);

        if (empty($this->alias) || $this->alias === $alias)
        {
            $this->alias = $alias;
        }


        $query = 'SELECT id' . ' FROM #__seminarman_categories' . ' WHERE title = ' . $this->
            _db->Quote($this->title) . ' AND parent_id = ' . $this->parent_id;
        $this->_db->setQuery($query);

        $xid = intval($this->_db->loadResult());
        if ($xid && $xid != intval($this->id))
        {
            JError::raiseWarning('SOME_ERROR_CODE', JText::sprintf('CATEGORY NAME ALREADY EXIST',
                $this->title));
            return false;
        }

        return true;
    }
}

?>