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

class seminarmanModelsalesprospect extends JModel
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
        $this->childviewname = 'salesprospect';
    }

    function setId($id)
    {

        $this->_id = $id;
        $this->_data = null;
    }

    function &getData()
    {
    	$this->_loadData();
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

            $query = 'DELETE FROM #__seminarman_' . $this->childviewname . ' WHERE id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

        	$query = 'DELETE FROM #__seminarman_fields_values_salesprospect WHERE requestid IN ( ' . $cids . ' )';
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


    function move($direction)
    {
        $row = $this->getTable();
        if (!$row->load($this->_id))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        if (!$row->move($direction))
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

	function getEditableCustomfields($requestid	= null)
	{
		$db			= $this->getDBO();
		$data		= new stdClass();

		// Attach custom fields into the user object
		$strSQL	= 'SELECT field.*, value.value '
				. 'FROM ' . $db->nameQuote('#__seminarman_fields') . ' AS field '
				. 'LEFT JOIN ' . $db->nameQuote('#__seminarman_fields_values_salesprospect') . ' AS value '
 				. 'ON field.id=value.field_id AND value.requestid=' . $db->Quote($requestid) . ' '
 				. 'WHERE published=1 ' 
 				. 'ORDER BY field.ordering';

		$db->setQuery( $strSQL );

		$result	= $db->loadAssocList();

		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}

		$data->fields	= array();
		for($i = 0; $i < count($result); $i++)
		{

			// We know that the groups will definitely be correct in ordering.
			if($result[$i]['type'] == 'group' && $result[$i]['purpose'] == 1)
			{
				$add = True;
				$group	= $result[$i]['name'];

				// Group them up
				if(!isset($data->fields[$group]))
				{
					// Initialize the groups.
					$data->fields[$group]	= array();
				}
			}
			if($result[$i]['type'] == 'group' && $result[$i]['purpose'] != 1)
				$add = False;

			// Re-arrange options to be an array by splitting them into an array
			if(isset($result[$i]['options']) && $result[$i]['options'] != '')
			{
				$options	= $result[$i]['options'];
				$options	= explode("\n", $options);
				foreach ($options as $option ){
					$option = trim($option);
				}

				//array_walk($options, array( 'JString' , 'trim' ) );
				$countOfOptions = count($options);
				for($x = 0; $x < $countOfOptions; $x++){
					$options[$x] = trim($options[$x]);
				}
				$result[$i]['options']	= $options;

				//$result[$i]['options'] = array('yes','no');

			}

			if($result[$i]['type'] != 'group' && isset($add)) {
				if($add)
					$data->fields[$group][]	= $result[$i];
			}
		}
		//$this->_dump($data);
		return $data;
	}

	function saveCustomfields($requestid, $userId, $fields)
	{
		$db = $this->getDBO();
	
		foreach ($fields as $id => $value) {
			 
			$q = 'REPLACE INTO `#__seminarman_fields_values_salesprospect`'.
	        	     ' (requestid, user_id, field_id, value)'.
	        	     ' VALUES ('.(int)$requestid.','.(int)$userId.','.(int)$id.','.$db->quote($value).')';
			$db->setQuery($q);
			$db->query();
		}
	}
	

    function _loadData()
    {

        if (empty($this->_data))
        {
            $query = 'SELECT w.*, j.reference_number, j.title AS course_title, j.price, j.currency_price,j.price_type,j.code' .
                ' FROM #__seminarman_' . $this->childviewname . ' AS w' .
                ' LEFT JOIN #__seminarman_templates AS j ON j.id = w.template_id' . ' WHERE w.id = ' . (int)
                $this->_id;
            $this->_db->setQuery($query);
            $this->_data = $this->_db->loadObject();
            return (boolean)$this->_data;
        }
        return true;
    }
}