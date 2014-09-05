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

class SeminarmanModelCategories extends JModelLegacy
{
    var $_pagination = null;

    var $_id = null;

    function __construct()
    {
        parent::__construct();

        $array = JRequest::getVar('cid', 0, '', 'array');
        $this->setId((int)$array[0]);

    }

    function setId($id)
    {

        $this->_id = $id;
        $this->_data = null;
    }

    function getData()
    {
        $mainframe = JFactory::getApplication();

        static $courses;

        if (isset($courses))
        {
            return $courses;
        }

        $limit = $mainframe->getUserStateFromRequest('com_seminarman.limit', 'limit', $mainframe->
            getCfg('list_limit'), 'int');
        $limitstart = $mainframe->getUserStateFromRequest('com_seminarman.limitstart',
            'limitstart', 0, 'int');
        $filter_order = $mainframe->getUserStateFromRequest('com_seminarman.categories.filter_order',
            'filter_order', 'c.ordering', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest('com_seminarman.categories.filter_order_Dir',
            'filter_order_Dir', '', 'word');
        $filter_state = $mainframe->getUserStateFromRequest('com_seminarman.categories.filter_state',
            'filter_state', '', 'word');
        $search = $mainframe->getUserStateFromRequest('com_seminarman.categories.search',
            'search', '', 'string');
        $search = $this->_db->escape(trim(JString::strtolower($search)));

        $orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir .
            ', c.ordering';

        $where = array();
        if ($filter_state)
        {
            if ($filter_state == 'P')
            {
                $where[] = 'c.published = 1';
            } else
                if ($filter_state == 'U')
                {
                    $where[] = 'c.published = 0';
                }
        }

        $where = (count($where) ? ' WHERE ' . implode(' AND ', $where) : '');


        if ($search)
        {


            $query = 'SELECT c.id' . ' FROM #__seminarman_categories AS c' .
                ' WHERE LOWER(c.title) LIKE ' . $this->_db->Quote('%' . $this->_db->escape($search, true) .
                '%', false) . $where;
            $this->_db->setQuery($query);
            $search_rows = $this->_db->loadColumn();
        }

        $query = 'SELECT c.*, u.name AS editor, COUNT(rel.catid) AS nrassigned' .
            ' FROM #__seminarman_categories AS c' .
            ' LEFT JOIN #__seminarman_cats_course_relations AS rel ON rel.catid = c.id' .
            ' LEFT JOIN #__users AS u ON u.id = c.checked_out' . $where . ' GROUP BY c.id' .
            $orderby;
        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();

        $children = array();

        $levellimit = 10;

        foreach ($rows as $child)
        {
            $parent = $child->parent_id;
            $list = @$children[$parent] ? $children[$parent] : array();
            array_push($list, $child);
            $children[$parent] = $list;
        }

        $list = seminarman_cats::treerecurse(0, '', array(), $children, false, max(0, $levellimit -
            1));

        if ($search)
        {
            $list1 = array();

            foreach ($search_rows as $sid)
            {
                foreach ($list as $course)
                {
                    if ($course->id == $sid)
                    {
                        $list1[] = $course;
                    }
                }
            }

            $list = $list1;
        }

        $total = count($list);

        jimport('joomla.html.pagination');
        $this->_pagination = new JPagination($total, $limitstart, $limit);

        $list = array_slice($list, $this->_pagination->limitstart, $this->_pagination->
            limit);

        return $list;
    }

    function &getPagination()
    {
        if ($this->_pagination == null)
        {
            $this->getData();
        }
        return $this->_pagination;
    }

    function publish($cid = array(), $publish = 1)
    {
        $user = JFactory::getUser();

        if (count($cid))
        {
            if (!$publish)
            {

                foreach ($cid as $id)
                {
                    $this->_addCategories($id, $cid);
                }
            } else
            {

                foreach ($cid as $id)
                {
                    $this->_addCategories($id, $cid, 'parents');
                }
            }

            $cids = implode(',', $cid);

            $query = 'UPDATE #__seminarman_categories' . ' SET published = ' . (int)$publish .
                ' WHERE id IN (' . $cids . ')' . ' AND ( checked_out = 0 OR ( checked_out = ' . (int)
                $user->get('id') . ' ) )';
            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return $cid;
    }

    function move($direction)
    {
        $row = JTable::getInstance('seminarman_categories', '');

        if (!$row->load($this->_id))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        if (!$row->move($direction, 'parent_id = ' . $row->parent_id))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        return true;
    }

    function saveorder($cid = array(), $order)
    {
        $row = JTable::getInstance('seminarman_categories', '');

        $groupings = array();

        for ($i = 0; $i < count($cid); $i++)
        {
            $row->load((int)$cid[$i]);

            $groupings[] = $row->parent_id;

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
            $row->reorder('parent_id = ' . $group);
        }

        return true;
    }

    function delete($cids)
    {

        foreach ($cids as $id)
        {
            $this->_addCategories($id, $cids);
        }

        $cids = implode(',', $cids);

        $query = 'SELECT c.id, c.parent_id, c.title, COUNT( e.catid ) AS numcat' .
            ' FROM #__seminarman_categories AS c' .
            ' LEFT JOIN #__seminarman_cats_course_relations AS e ON e.catid = c.id' .
            ' WHERE c.id IN (' . $cids . ')' . ' GROUP BY c.id';
        $this->_db->setQuery($query);

        if (!($rows = $this->_db->loadObjectList()))
        {
            JError::raiseError(500, $this->_db->stderr());
            return false;
        }

        $err = array();
        $cid = array();

        foreach ($rows as $row)
        {
            if ($row->numcat == 0)
            {
                $cid[] = $row->id;
            } else
            {
                $err[] = $row->title;
            }
        }

        if (count($cid) && count($err) == 0)
        {
            $cids = implode(',', $cid);
            $query = 'DELETE FROM #__seminarman_categories' . ' WHERE id IN (' . $cids . ')';

            $this->_db->setQuery($query);

            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }

        if (count($err))
        {
            $cids = implode(', ', $err);
            $msg = JText::sprintf('COM_SEMINARMAN_RELATED_RECORDS_ERROR', $cids);
            return $msg;
        } else
        {
            $total = count($cid);
            $msg = $total . ' ' . JText::_('COM_SEMINARMAN_OPERATION_SUCCESSFULL');
            return $msg;
        }
    }

    function access($id, $access)
    {
        $category = $this->getTable('seminarman_categories', '');

        $cids = array();
        $cids[] = $id;
        $this->_addCategories($id, $cids);

        foreach ($cids as $cid)
        {

            $category->load((int)$cid);

            if ($category->access < $access)
            {
                $category->access = $access;
            } else
            {
                $category->load($id);
                $category->access = $access;
            }

            if (!$category->check())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            if (!$category->store())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

        }

        $pcids = array();
        $this->_addCategories($id, $pcids, 'parents');

        foreach ($pcids as $pcid)
        {

            if ($pcid == 0 || $pcid == $id)
            {
                continue;
            }

            $category->load((int)$pcid);

            if ($category->access > $access)
            {

                $category->access = $access;

                if (!$category->check())
                {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
                if (!$category->store())
                {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }

            }
        }
        return true;
    }

    function _addCategories($id, &$list, $type = 'children')
    {

        $return = true;

        if ($type == 'children')
        {
            $get = 'id';
            $source = 'parent_id';
        } else
        {
            $get = 'parent_id';
            $source = 'id';
        }

        $query = 'SELECT ' . $get . ' FROM #__seminarman_categories' . ' WHERE ' . $source .
            ' = ' . (int)$id;
        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();

        if ($this->_db->getErrorNum())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        foreach ($rows as $row)
        {
            $found = false;
            foreach ($list as $idx)
            {
                if ($idx == $row->$get)
                {
                    $found = true;
                    break;
                }
            }
            if (!$found)
            {
                $list[] = $row->$get;
            }
            $return = $this->_addCategories($row->$get, $list, $type);
        }
        return $return;
    }
}

?>
