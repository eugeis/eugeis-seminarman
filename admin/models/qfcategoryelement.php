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

class SeminarmanModelQfcategoryelement extends JModelLegacy
{
    var $_data = null;

    var $_total = null;

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
            // $search_rows = $this->_db->loadResultArray();
            $search_rows = $this->_db->loadColumn();
        }

        $query = 'SELECT c.*, u.name AS editor' .
            ' FROM #__seminarman_categories AS c' .
            ' LEFT JOIN #__users AS u ON u.id = c.checked_out' . $where . $orderby;
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

    function getPagination()
    {
        if ($this->_pagination == null)
        {
            $this->getData();
        }
        return $this->_pagination;
    }
}

?>
