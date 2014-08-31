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

class SeminarmanModelFavourites extends JModelLegacy
{
    var $_data = null;

    var $_total = null;

    function __construct()
    {
        parent::__construct();

        $mainframe = JFactory::getApplication();

        $params = $mainframe->getParams('com_seminarman');

    	$id = JRequest::getInt('id', 0);

    	$this->setId((int)$id);

        $limit = JRequest::getInt('limit', $params->get('limit'));
        $limitstart = JRequest::getInt('limitstart');

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);

        $this->setState('filter_order', JRequest::getCmd('filter_order', 'i.title'));
        $this->setState('filter_order_dir', JRequest::getCmd('filter_order_Dir', 'ASC'));
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

    function _buildQuery()
    {

        $where = $this->_buildCourseWhere();
        $orderby = $this->_buildCourseOrderBy();


    	$query = 'SELECT DISTINCT i.*, (i.plus / (i.plus + i.minus) ) * 100 AS votes, emp.comp_name AS tutor,' .
    	    ' CASE WHEN CHAR_LENGTH(i.alias) THEN CONCAT_WS(\':\', i.id, i.alias) ELSE i.id END as slug,' .
    	    ' gr.title AS cgroup, lev.title AS level, c.icon AS icon' .
    	    ' FROM #__seminarman_courses AS i' .
            ' LEFT JOIN #__seminarman_favourites AS f ON f.courseid = i.id' .
    	    ' LEFT JOIN #__seminarman_cats_course_relations AS rel ON rel.courseid = i.id' .
    	    ' LEFT JOIN #__seminarman_tutor AS emp ON emp.id = i.tutor_id' .
    	    ' LEFT JOIN #__seminarman_atgroup AS gr ON gr.id = i.id_group' .
    	    ' LEFT JOIN #__seminarman_experience_level AS lev ON lev.id = i.id_experience_level' .
    	    //' LEFT JOIN #__seminarman_sessions AS ses ON ses.courseid = i.id' .
    	    ' LEFT JOIN #__seminarman_categories AS c ON c.id = rel.catid' . $where .
    	    ' GROUP BY i.id' . $orderby;

        return $query;
    }

    function _buildCourseOrderBy()
    {
        $filter_order = $this->getState('filter_order');
        $filter_order_dir = $this->getState('filter_order_dir');

        $orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_dir . ', i.title';

        return $orderby;
    }

    function _buildCourseWhere()
    {
        $mainframe = JFactory::getApplication();

        $user = JFactory::getUser();
        $gid = (int)$user->get('aid');
        $params = $mainframe->getParams('com_seminarman');
        $jnow = JFactory::getDate();
        // $now = $jnow->toMySQL();
        $now = $jnow->toSQL();
        $nullDate = $this->_db->getNullDate();

        $state = 1;

        $where = ' WHERE f.userid = ' . (int)$user->get('id');
        //$where .= ' AND c.access <= ' . $gid;

        switch ($state)
        {
            case 1:

                $where .= ' AND i.state = 1' . ' AND ( i.publish_up = ' . $this->_db->Quote($nullDate) .
                    ' OR i.publish_up <= ' . $this->_db->Quote($now) . ' )' . ' AND ( i.publish_down = ' .
                    $this->_db->Quote($nullDate) . ' OR i.publish_down >= ' . $this->_db->Quote($now) .
                    ' )';

                break;

            case - 1:

                $year = JRequest::getInt('year', date('Y'));
                $month = JRequest::getInt('month', date('m'));

                $where .= ' AND i.state = -1';
                $where .= ' AND YEAR( i.created ) = ' . (int)$year;
                $where .= ' AND MONTH( i.created ) = ' . (int)$month;
                break;

            default:
                $where .= ' AND i.state = ' . (int)$state;
                break;
        }

    	//$where .= ' AND ses.published = 1';

    	if ($params->get('filter'))
    	{

    		$filter = JRequest::getString('filter', '', 'request');
    		$filter_experience_level = JRequest::getString('filter_experience_level', '', 'request');
    		$filter_positiontype = JRequest::getString('filter_positiontype', '', 'request');


    		if ($filter)
    		{

    			$filter = $this->_db->escape(trim(JString::strtolower($filter)));

    			$where .= ' AND LOWER( i.title ) LIKE ' . $this->_db->Quote('%' . $this->_db->
    			    escape($filter, true) . '%', false);
    		}
    	}
    	if ($filter_experience_level)
    	{
    		$where .= ' AND LOWER( i.id_experience_level ) = ' . $filter_experience_level;
    	}
        return $where;
    }

	function gettitles()
	{
		$user = JFactory::getUser();
		$gid = (int)$user->get('aid');
		$ordering = 'ordering ASC';

		$query = 'SELECT id, title' . ' FROM #__seminarman_experience_level' .
		    ' WHERE published = 1' . ' ORDER BY ' . $ordering;

		$this->_db->setQuery($query);
		$this->_subs = $this->_db->loadObjectList();

		return $this->_subs;
	}

	function getCategory($courseid)
	{

		$user = JFactory::getUser();
		$gid = (int)$user->get('aid');

		$query = 'SELECT DISTINCT c.*,' .
			' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as slug' .
		    ' FROM #__seminarman_categories AS c' .
    	    ' LEFT JOIN #__seminarman_cats_course_relations AS rel ON rel.catid = c.id' .
		    ' WHERE rel.courseid = ' . $courseid .
		    ' AND c.published = 1';
		    //' AND c.access <= ' . $gid;

		$this->_db->setQuery($query);
		$this->_category = $this->_db->loadObject();

		if (!$this->_category->published)
		{
			JError::raiseError(404, JText::sprintf('CATEGORY #%d NOT FOUND', $this->_id));
			return false;
		}


//		if ($this->_category->access > $gid)
//		{
//			JError::raiseError(403, JText::_("ALERTNOTAUTH"));
//			return false;
//		}

		return $this->_category;
	}
}

?>