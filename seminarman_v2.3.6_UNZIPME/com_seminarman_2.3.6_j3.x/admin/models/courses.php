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

require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_seminarman' . DS .
            'helpers' . DS . 'seminarman.php');

class SeminarmanModelCourses extends JModelLegacy
{
    var $_data = null;

    var $_total = null;

    var $_pagination = null;

    var $_id = null;

    function __construct()
    {
        parent::__construct();

        $mainframe = JFactory::getApplication();

        $limit = $mainframe->getUserStateFromRequest('com_seminarman' . '.limit', 'limit', $mainframe->
            getCfg('list_limit'), 'int');
        $limitstart = $mainframe->getUserStateFromRequest('com_seminarman' . '.limitstart',
            'limitstart', 0, 'int');

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);

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

        if (empty($this->_data))
        {
            $query = $this->_buildQuery();
            $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->
                getState('limit'));

            $k = 0;
            $count = count($this->_data);
            for ($i = 0; $i < $count; $i++)
            {
                $course = &$this->_data[$i];
                $course->categories = $this->getCategories($course->id);
                $k = 1 - $k;
            }

        }

        return $this->_data;
    }

    function getTotal()
    {

        if (empty($this->_total))
        {
            $query = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);
        }

        return $this->_total;
    }

    function getPagination()
    {

        if (empty($this->_pagination))
        {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'),
                $this->getState('limit'));
        }

        return $this->_pagination;
    }

    function _buildQuery()
    {

        $where = $this->_buildContentWhere();
        $orderby = $this->_buildContentOrderBy();

        $query = 'SELECT DISTINCT rel.courseid, i.*, u.name AS editor,  (i.plus / (i.plus + i.minus) ) * 100 AS votes' .
            ' FROM #__seminarman_courses AS i' .
            ' LEFT JOIN #__seminarman_cats_course_relations AS rel ON rel.courseid = i.id' .
            ' LEFT JOIN #__users AS u ON u.id = i.checked_out' . $where . $orderby;

        return $query;
    }

    function _buildContentOrderBy()
    {
        $mainframe = JFactory::getApplication();

        $filter_order = $mainframe->getUserStateFromRequest('com_seminarman' .
            '.courses.filter_order', 'filter_order', 'i.ordering', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest('com_seminarman' .
            '.courses.filter_order_Dir', 'filter_order_Dir', '', 'word');

        $orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir .
            ', i.ordering';

        return $orderby;
    }

    function _buildContentWhere()
    {
        $mainframe = JFactory::getApplication();
        
        $current_uid = JFactory::getUser()->id;

        $filter_state = $mainframe->getUserStateFromRequest('com_seminarman' .
            '.courses.filter_state', 'filter_state', '', 'word');
	$filter_category = $mainframe->getUserStateFromRequest('com_seminarman' .
            '.courses.filter_category', 'filter_category', '');
	$filter_search = $mainframe->getUserStateFromRequest( 'com_seminarman'.'.application.filter_search',    'filter_search',      '',            'int' );
        $search = $mainframe->getUserStateFromRequest('com_seminarman' . '.courses.search',
            'search', '', 'string');
        $search = $this->_db->escape(trim(JString::strtolower($search)));

        $where = array();

        $where[] = ' i.state != -1';

        if ($filter_state)
        {
            if ($filter_state == 'P')
            {
                $where[] = 'i.state = 1';
            } else
                if ($filter_state == 'U')
                {
                    $where[] = 'i.state = 0';
                } else
                    if ($filter_state == 'A')
                    {
                        $where[] = 'i.state = -1';
                    } else
                        if ($filter_state == 'W')
                        {
                            $where[] = 'i.state = -2';
                        } else
                            if ($filter_state == 'O')
                            {
                                $where[] = 'i.state = -3';
                            } else
                                if ($filter_state == 'T')
                                {
                                    $where[] = 'i.state = -4';
                                }
        }

       if(!(JHTMLSeminarman::UserIsCourseManager())){
            $where[] = 'i.tutor_id = ' . JHTMLSeminarman::getUserTutorID();
        }
        
	if ($filter_category) { $where[] = 'rel.catid = '.$filter_category; }

        if ($search && $filter_search == 1)
        {
            $where[] = ' LOWER(i.title) LIKE ' . $this->_db->Quote('%' . $this->_db->
                escape($search, true) . '%', false);
        }
        
        if ($search && $filter_search == 2) {
        	$where[] = ' LOWER(i.code) LIKE '.$this->_db->Quote('%' . $this->_db->
                escape($search, true) . '%', false);
        }

        $where = (count($where) ? ' WHERE ' . implode(' AND ', $where) : '');

        return $where;
    }

    function setcoursestate($id, $state = 1)
    {
        $user = JFactory::getUser();

        if ($id)
        {

            $query = 'UPDATE #__seminarman_courses' . ' SET state = ' . (int)$state .
                ' WHERE id = ' . (int)$id . ' AND ( checked_out = 0 OR ( checked_out = ' . (int)
                $user->get('id') . ' ) )';
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
        $row = JTable::getInstance('seminarman_courses', '');

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
        $row = JTable::getInstance('seminarman_courses', '');

        for ($i = 0; $i < count($cid); $i++)
        {
            $row->load((int)$cid[$i]);

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

        return true;
    }

    function delete($cid)
    {
        if (count($cid))
        {
            $cids = implode(',', $cid);
            $query = 'DELETE FROM #__seminarman_courses' . ' WHERE id IN (' . $cids . ')';

            $this->_db->setQuery($query);

            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

            $query = 'DELETE FROM #__seminarman_tags_course_relations' . ' WHERE courseid IN (' . $cids .
                ')';
            $this->_db->setQuery($query);

            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

            $query = 'DELETE FROM #__seminarman_cats_course_relations' . ' WHERE courseid IN (' . $cids .
                ')';
            $this->_db->setQuery($query);

            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

            $query = 'DELETE FROM #__seminarman_files_course_relations' . ' WHERE courseid IN (' . $cids .
                ')';
            $this->_db->setQuery($query);

            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

            return true;
        }
        return false;
    }

    function getCategories($id)
    {
        $query = 'SELECT DISTINCT c.id, c.title' . ' FROM #__seminarman_categories AS c' .
            ' LEFT JOIN #__seminarman_cats_course_relations AS rel ON rel.catid = c.id' .
            ' WHERE rel.courseid = ' . (int)$id;

        $this->_db->setQuery($query);

        $this->_cats = $this->_db->loadObjectList();

        return $this->_cats;
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

}

?>