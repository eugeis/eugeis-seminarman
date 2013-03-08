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

jimport('joomla.application.component.model');

class SeminarmanModelFile extends JModel
{
    var $_file = null;

    function __construct()
    {
        parent::__construct();

        $array = JRequest::getVar('cid', 0, '', 'array');
        $this->setId((int)$array[0]);
    }

    function setId($id)
    {

        $this->_id = $id;
        $this->_file = null;
    }

    function get($property, $default = null)
    {
        if ($this->_loadFile())
        {
            if (isset($this->_file->$property))
            {
                return $this->_file->$property;
            }
        }
        return $default;
    }

    function &getFile()
    {
        if ($this->_loadFile())
        {

        }
        return $this->_file;
    }


    function _loadFile()
    {

        if (empty($this->_file))
        {
            $query = 'SELECT *' . ' FROM #__seminarman_files' . ' WHERE id = ' . $this->_id;
            $this->_db->setQuery($query);
            $this->_file = $this->_db->loadObject();

            return (boolean)$this->_file;
        }
        return true;
    }


    function checkin()
    {
        if ($this->_id)
        {
            $tag = &JTable::getInstance('seminarman_files', '');
            return $tag->checkout($uid, $this->_id);
        }
        return false;
    }

    function checkout($uid = null)
    {
        if ($this->_id)
        {

            if (is_null($uid))
            {
                $user = &JFactory::getUser();
                $uid = $user->get('id');
            }

            $file = &JTable::getInstance('seminarman_files', '');
            return $file->checkout($uid, $this->_id);
        }
        return false;
    }

    function isCheckedOut($uid = 0)
    {
        if ($this->_loadFile())
        {
            if ($uid)
            {
                return ($this->_file->checked_out && $this->_file->checked_out != $uid);
            } else
            {
                return $this->_file->checked_out;
            }
        } elseif ($this->_id < 1)
        {
            return false;
        } else
        {
            JError::raiseWarning(0, 'UNABLE LOAD DATA');
            return false;
        }
    }

    function store($data)
    {
        $file = &$this->getTable('seminarman_files', '');

        if (!$file->bind($data))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        if (!$file->check())
        {
            $this->setError($file->getError());
            return false;
        }

        if (!$file->store())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        $this->_file = &$file;

        return true;
    }
}

?>