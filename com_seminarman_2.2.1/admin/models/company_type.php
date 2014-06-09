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

class seminarmanModelcompany_type extends JModel
{
    var $_id = null;

    var $_data = null;

    function __construct()
    {
        parent::__construct();

        $array = JRequest::getVar('cid', array(0), '', 'array');
        $edit = JRequest::getVar('edit', true);
        if ($edit)
            $this->setId((int)$array[0]);
        $this->childviewname = 'company_type';
    }

    function setId($id)
    {

        $this->_id = $id;
        $this->_data = null;
    }

    function &getData()
    {

        if ($this->_loadData())
        {

            $user = JFactory::getUser();

        } else
            $this->_initData();

        return $this->_data;
    }

    function isCheckedOut($uid = 0)
    {
        if ($this->_loadData())
        {
            if ($uid)
            {
                return ($this->_data->checked_out && $this->_data->checked_out != $uid);
            } else
            {
                return $this->_data->checked_out;
            }
        }
    }

    function checkin()
    {
        if ($this->_id)
        {
            $group = $this->getTable();
            if (!$group->checkin($this->_id))
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
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

            $group = $this->getTable();
            if (!$group->checkout($uid, $this->_id))
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

            return true;
        }
        return false;
    }

    function store($data)
    {
        $row = $this->getTable();

        if (!$row->bind($data))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        $row->date = gmdate('Y-m-d H:i:s');

        if (!$row->id)
        {
            $where = '';
            $row->ordering = $row->getNextOrder($where);
        }

        if (!$row->check())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        if (!$row->store())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        return $row->id;
    }


    function delete($cid = array())
    {
        $result = false;

        if (count($cid))
        {
            JArrayHelper::toInteger($cid);
            $cids = implode(',', $cid);

            $query_check = 'SELECT id_comp_type FROM #__seminarman_tutor' . ' WHERE id_comp_type IN ( ' . $cids . ' )';
            $this->_db->setQuery($query_check);
            $relatedRecords = $this->_db->loadResult();
            if ($relatedRecords > 0)
            {
                JError::raiseWarning('ERROR_CODE', JText::_('COM_SEMINARMAN_RELATED_RECORDS_ERROR'));
                return false;
            }


            $query = 'DELETE FROM #__seminarman_' . $this->childviewname . ' WHERE id IN ( ' . $cids .
                ' )';
            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }

        return true;
    }

    function approve($cid = array(), $approve = 1)
    {
        $user = JFactory::getUser();

        if (count($cid))
        {
            JArrayHelper::toInteger($cid);
            $cids = implode(',', $cid);

            $query = 'UPDATE #__seminarman_' . $this->childviewname . ' SET approved = ' . (int)
                $approved . ' WHERE id IN ( ' . $cids . ' )' .
                ' AND ( checked_out = 0 OR ( checked_out = ' . (int)$user->get('id') . ' ) )';
            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }

        return true;
    }

    function publish($cid = array(), $publish = 1)
    {
        $user = JFactory::getUser();

        if (count($cid))
        {
            JArrayHelper::toInteger($cid);
            $cids = implode(',', $cid);

            $query = 'UPDATE #__seminarman_' . $this->childviewname . ' SET published = ' . (int)
                $publish . ' WHERE id IN ( ' . $cids . ' )' .
                ' AND ( checked_out = 0 OR ( checked_out = ' . (int)$user->get('id') . ' ) )';
            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }

        return true;
    }


    function move($direction)
    {
        $row = $this->getTable();
        if (!$row->load($this->_id))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        if (!$row->move($direction, ' published >= 0 '))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        return true;
    }

    function saveorder($cid = array(), $order)
    {
        $row = $this->getTable();
        $groupings = array();

        for ($i = 0; $i < count($cid); $i++)
        {
            $row->load((int)$cid[$i]);

            $groupings[] = '';

            if ($row->ordering != $order[$i])
            {
                $row->ordering = $order[$i];
                if (!$row->store())
                {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
            }
        }

        $groupings = array_unique($groupings);
        foreach ($groupings as $group)
        {
            $row->reorder('');
        }

        return true;
    }

    function _loadData()
    {

        if (empty($this->_data))
        {
            $query = 'SELECT w.*' . ' FROM #__seminarman_' . $this->childviewname . ' AS w' .
                ' WHERE w.id = ' . (int)$this->_id;
            $this->_db->setQuery($query);
            $this->_data = $this->_db->loadObject();
            return (boolean)$this->_data;
        }
        return true;
    }

    function _initData()
    {

        if (empty($this->_data))
        {
            $company_type = new stdClass();
            $company_type->id = 0;
            $company_type->title = null;
            $company_type->alias = null;
            $company_type->code = null;
            $company_type->description = null;
            $company_type->date = null;
            $company_type->hits = 0;
            $company_type->published = 0;
            $company_type->checked_out = 0;
            $company_type->checked_out_time = 0;
            $company_type->ordering = 0;
            $company_type->archived = 0;
            $company_type->approved = 0;
            $company_type->params = null;
            $this->_data = $company_type;
            return (boolean)$this->_data;

        }
        return true;
    }
}