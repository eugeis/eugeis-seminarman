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

class SeminarmanModelCategory extends JModelLegacy
{
    var $_category = null;

    function __construct()
    {
        parent::__construct();

        $array = JRequest::getVar('cid', 0, '', 'array');
        $this->setId((int)$array[0]);
    }

    function setId($id)
    {

        $this->_id = $id;
        $this->_category = null;
    }

    function get($property, $default = null)
    {
        if ($this->_loadCategory())
        {
            if (isset($this->_category->$property))
            {
                return $this->_category->$property;
            }
        }
        return $default;
    }

    function &getCategory()
    {
        if ($this->_loadCategory())
        {

        } else
            $this->_initCategory();

        return $this->_category;
    }

    function _loadCategory()
    {

        if (empty($this->_category))
        {
            $query = 'SELECT *' . ' FROM #__seminarman_categories' . ' WHERE id = ' . $this->
                _id;
            $this->_db->setQuery($query);
            $this->_category = $this->_db->loadObject();

            return (boolean)$this->_category;
        }
        return true;
    }

    function _initCategory()
    {

        if (empty($this->_category))
        {
            $category = new stdClass();
            $category->id = 0;
            $category->parent_id = 0;
            $category->title = null;
            $category->alias = null;
            $category->text = null;
            $category->meta_description = null;
            $category->meta_keywords = null;
            $category->published = 1;
            $category->icon = JText::_('SELECTIMAGE');
            $category->image = JText::_('SELECTIMAGE');
            $category->access = 0;
            $this->_category = $category;
            return (boolean)$this->_category;
        }
        return true;
    }

    function checkin()
    {
        if ($this->_id)
        {
            $category = JTable::getInstance('seminarman_categories', '');
            return $category->checkin($this->_id);
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

            $category = JTable::getInstance('seminarman_categories', '');
            if (!$category->checkout($uid, $this->_id))
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

            return true;
        }
        return false;
    }

    function isCheckedOut($uid = 0)
    {
        if ($this->_loadCategory())
        {
            if ($uid)
            {
                return ($this->_category->checked_out && $this->_category->checked_out != $uid);
            } else
            {
                return $this->_category->checked_out;
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
        $category = $this->getTable('seminarman_categories', '');
       
        if (!$category->bind($data))
        {
            $this->setError(500, $this->_db->getErrorMsg());
            return false;
        }

        if (!$category->id)
        {
            $category->ordering = $category->getNextOrder();
        }

        if (!$category->check())
        {
            $this->setError($category->getError());
            return false;
        }

        if (!$category->store())
        {
            $this->setError(500, $this->_db->getErrorMsg());
            return false;
        }

        $this->_category = &$category;
        
        $dispatcher = JDispatcher::getInstance();
        JPluginHelper::importPlugin('seminarman');
        
        // fire vmengine
        $results = $dispatcher->trigger('onProcessCategory', array($category));
        
        return true;        
    }    
}

?>