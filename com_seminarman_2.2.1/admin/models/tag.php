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

class SeminarmanModelTag extends JModel
{
    var $_tag = null;

    function __construct()
    {
        parent::__construct();

        $array = JRequest::getVar('cid', 0, '', 'array');
        $this->setId((int)$array[0]);
    }

    function setId($id)
    {

        $this->_id = $id;
        $this->_tag = null;
    }

    function get($property, $default = null)
    {
        if ($this->_loadTag())
        {
            if (isset($this->_tag->$property))
            {
                return $this->_tag->$property;
            }
        }
        return $default;
    }

    function &getTag()
    {
        if ($this->_loadTag())
        {

        } else
            $this->_initTag();

        return $this->_tag;
    }


    function _loadTag()
    {

        if (empty($this->_tag))
        {
            $query = 'SELECT *' . ' FROM #__seminarman_tags' . ' WHERE id = ' . $this->_id;
            $this->_db->setQuery($query);
            $this->_tag = $this->_db->loadObject();

            return (boolean)$this->_tag;
        }
        return true;
    }

    function _initTag()
    {

        if (empty($this->_tag))
        {
            $tag = new stdClass();
            $tag->id = 0;
            $tag->name = null;
            $tag->alias = null;
            $tag->published = 1;
            $this->_tag = $tag;
            return (boolean)$this->_tag;
        }
        return true;
    }

    function checkin()
    {
        if ($this->_id)
        {
            $tag = JTable::getInstance('seminarman_tags', '');
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
                $user = JFactory::getUser();
                $uid = $user->get('id');
            }

            $tag = JTable::getInstance('seminarman_tags', '');
            return $tag->checkout($uid, $this->_id);
        }
        return false;
    }

    function isCheckedOut($uid = 0)
    {
        if ($this->_loadTag())
        {
            if ($uid)
            {
                return ($this->_tag->checked_out && $this->_tag->checked_out != $uid);
            } else
            {
                return $this->_tag->checked_out;
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
        $tag = $this->getTable('seminarman_tags', '');

        if (!$tag->bind($data))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        if (!$tag->check())
        {
            $this->setError($tag->getError());
            return false;
        }

        if (!$tag->store())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        $this->_tag = &$tag;

        return true;
    }

    function addtag($name)
    {

        $obj = new stdClass();
        $obj->name = $name;
        $obj->published = 1;

        $this->store($obj);


        return true;
    }

}

?>