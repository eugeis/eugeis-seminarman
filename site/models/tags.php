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

class SeminarmanModelTags extends JModelLegacy
{
    var $_data = null;

    var $_tag = null;

    var $_total = null;

    function __construct()
    {
        parent::__construct();

        $mainframe = JFactory::getApplication();

        $params = $mainframe->getParams('com_seminarman');
        
        $orderingDef = $params->get('list_ordering');
        switch ($orderingDef)
        {
        	case '0' :
        		$ordering = 'i.title';
        		break;
        
        	case '1' :
        		$ordering = 'i.start_date';
        		break;
        
        	case '2' :
        		$ordering = 'i.ordering';
        		break;
        }
        
        $id = JRequest::getInt('id', 0);
        $this->setId((int)$id);
        
        $limit = $mainframe->getUserStateFromRequest('com_seminarman.tag.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $tmpl_limit = $mainframe->getUserStateFromRequest('com_seminarman.tag.tmpl_limit', 'tmpl_limit', $mainframe->getCfg('list_limit'), 'int');
        $tmpl_limitstart = JRequest::getVar('tmpl_limitstart', 0, '', 'int');
        $tmpl_limitstart = ($tmpl_limit != 0 ? (floor($tmpl_limitstart / $tmpl_limit) * $tmpl_limit) : 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
        $this->setState('tmpl_limit', $tmpl_limit);
        $this->setState('tmpl_limitstart', $tmpl_limitstart);

        $this->setState('filter_order', JRequest::getCmd('filter_order', $ordering));
        $this->setState('filter_order_dir', JRequest::getCmd('filter_order_Dir', 'ASC'));
        $this->setState('filter_order2', JRequest::getCmd('filter_order2', $ordering));
        $this->setState('filter_order_dir2', JRequest::getCmd('filter_order_Dir2', 'ASC'));
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

    	$query = 'SELECT DISTINCT i.*, (i.plus / (i.plus + i.minus) ) * 100 AS votes,' .
            ' CONCAT_WS(" ", emp.firstname, emp.lastname) as tutor,' .
    	    ' CASE WHEN CHAR_LENGTH(i.alias) THEN CONCAT_WS(\':\', i.id, i.alias) ELSE i.id END as slug,' .
    	    ' gr.title AS cgroup, lev.title AS level' .
    	    ' FROM #__seminarman_courses AS i' .
            ' INNER JOIN #__seminarman_tags_course_relations AS t ON t.courseid = i.id' .
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

        $where = ' WHERE t.tid = ' . $this->_id;

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

    function getTag()
    {

        $query = 'SELECT t.name, t.id,' .
            ' CASE WHEN CHAR_LENGTH(t.alias) THEN CONCAT_WS(\':\', t.id, t.alias) ELSE t.id END as slug' .
            ' FROM #__seminarman_tags AS t' . ' WHERE t.id = ' . $this->_id .
            ' AND t.published = 1';

        $this->_db->setQuery($query);
        $this->_tag = $this->_db->loadObject();

        return $this->_tag;
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

		$query = 'SELECT c.*,' . ' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as slug' .
		    ' FROM #__seminarman_categories AS c' .
	    	' LEFT JOIN #__seminarman_cats_course_relations AS rel ON rel.catid = c.id' .
		    ' WHERE rel.courseid = ' . $courseid .
		    ' AND c.published = 1';
            
		$this->_db->setQuery($query);
		$this->_category = $this->_db->loadObject();

		if (!$this->_category->published)
		{
			JError::raiseError(404, JText::sprintf('CATEGORY #%d NOT FOUND', $this->_id));
			return false;
		}

		return $this->_category;
	}

	function getCategoryOfTemplate($templateid)
	{
	
		$user = JFactory::getUser();
		$gid = (int)$user->get('aid');
	
		$query = 'SELECT c.*,' . ' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as slug' .
			    ' FROM #__seminarman_categories AS c' .
		    	' LEFT JOIN #__seminarman_cats_template_relations AS rel ON rel.catid = c.id' .
			    ' WHERE rel.templateid = ' . $templateid .
			    ' AND c.published = 1';
	
		$this->_db->setQuery($query);
		$this->_category = $this->_db->loadObject();
	
		if (!$this->_category->published)
		{
			JError::raiseError(404, JText::sprintf('CATEGORY #%d NOT FOUND', $this->_id));
			return false;
		}
	
		return $this->_category;
	}
	
	function _buildQueryLstOfProspects()
	{
		$query = 'SELECT DISTINCT i.*, gr.title AS cgroup, lev.title AS level, i.id AS slug' .
		   		      ' FROM #__seminarman_templates AS i ' .
		   		      ' LEFT JOIN #__seminarman_tags_template_relations AS rel ON rel.templateid = i.id'.
		   		      ' LEFT JOIN #__seminarman_atgroup AS gr ON gr.id = i.id_group' .
		   		      ' LEFT JOIN #__seminarman_experience_level AS lev ON lev.id = i.id_experience_level' .
		   		      ' WHERE i.state=1 AND rel.tid='. $this->_id;
		
		$mainframe = JFactory::getApplication();
		$params = $mainframe->getParams('com_seminarman');
			
		if ($params->get('filter'))
		{
			$filter2 = JRequest::getString('filter2', '', 'request');
			$filter_experience_level2 = JRequest::getString('filter_experience_level2', '', 'request');
	
			if ($filter2) {
				$filter2 = $this->_db->escape(trim(JString::strtolower($filter2)));
				$like = $this->_db->Quote('%' . $this->_db->escape($filter2, true) . '%', false);
				$query .= ' AND ( LOWER( i.title ) LIKE ' . $like .' OR LOWER( i.code ) LIKE '. $like .')';
	
			}
	
			if ($filter_experience_level2 > 0)
			$query .= ' AND LOWER( i.id_experience_level ) = ' . $filter_experience_level2;
		}
			
	
		$filter_order2 = $this->getState('filter_order2');
		$filter_order_dir2 = $this->getState('filter_order_dir2');
		
		$query .= ' ORDER BY '. $filter_order2 .' '. $filter_order_dir2 .', i.title';
			
		return $query;
	}
	
	function getLstOfProspects()
	{
		return $this->_getList($this->_buildQueryLstOfProspects(), $this->getState('tmpl_limitstart'), $this->getState('tmpl_limit'));
	}
	
	function getTotalLstOfProspects()
	{
		return $this->_getListCount($this->_buildQueryLstOfProspects());
	}
	
	function hasUserBooked($course_id)
	{
		if (empty($this->_bookings))
		$this->_loadBookings();
		 
		return in_array($course_id, $this->_bookings);
	}
	
	function _loadBookings()
	{
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
	
		$q = 'SELECT course_id FROM `#__seminarman_application`'.
	        	          ' WHERE user_id = '. $user->id. 
	        	          ' AND published = 1';
		$db->setQuery($q);
		// $this->_bookings = $db->loadResultArray();
		$this->_bookings = $db->loadColumn();
	}
}

?>
