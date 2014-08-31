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

class SeminarmanModelTutor extends JModelLegacy
{
	var $_data = null;
	var $_courses = null;
	var $_id = null;

	function __construct()
	{
		parent::__construct();		
		// $id = JRequest::getVar('id', 0, 'get', 'int');
		$id = JFactory::getApplication()->input->get('id');
		$this->setId((int)$id);
	}
	
	function setId($id)
	{
	
		$this->_id = $id;
		$this->_data = null;
	}

	function getTutor()
	{
 		if ($this->_loadTutor()) {
		    return $this->_data;
 		}
	}

	function _loadTutor()
	{
		$mainframe = JFactory::getApplication();
	
		if ($this->_id == '0')
		{
			return false;
		}
	
		if (empty($this->_data))
		{
	
		    $query = 'SELECT CONCAT_WS(" ", t.other_title, t.firstname, t.lastname) as tutor_label,' .
				' CASE WHEN CHAR_LENGTH(t.alias) THEN CONCAT_WS(\':\', t.id, t.alias) ELSE t.id END as tutor_slug,' .
				' t.id AS tutor_id, t.logofilename AS tutor_photo, t.description AS tutor_desc, t.*' .
				' FROM #__seminarman_tutor AS t WHERE t.id = ' . $this->_id;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean)$this->_data;
		}
		return true;
	}
	
	function getCourses()
	{
		
		if ($this->_loadCourses()) {
	
			return $this->_courses;
		}
	}
	
	function _loadCourses()
	{
		$mainframe = JFactory::getApplication();
		
		if ($this->_id == '0')
		{
			return false;
		}

		if (empty($this->_courses))
		{
		
            $query = $this->_buildQuery($this->_id);
            
            $this->_courses = $this->_getList($query, $this->getState('limitstart'), $this->
                getState('limit'));
			return (boolean)$this->_courses;
		}
		return true;		
	}
	
	function _buildQuery()
	{
	
		$where = $this->_buildCourseWhere();
		$orderby = $this->_buildCourseOrderBy();
	
		$query = 'SELECT DISTINCT i.*,' .
				' CASE WHEN CHAR_LENGTH(i.alias) THEN CONCAT_WS(\':\', i.id, i.alias) ELSE i.id END as slug,' .
				' rel.catid as cat' .
				' FROM #__seminarman_courses AS i' .
		        ' LEFT JOIN #__seminarman_cats_course_relations AS rel ON rel.courseid = i.id' . $where . $orderby;
	
	
		return $query;
	}
	
	function _buildCourseOrderBy()
	{
		$filter_order = $this->getState('filter_order');
		$filter_order_dir = $this->getState('filter_order_dir');
	
		return ' ORDER BY i.title';
	}
	
	function _buildCourseWhere()
	{
		$mainframe = JFactory::getApplication();
		
		$user = JFactory::getUser();
		$gid = (int)$user->get('aid');
		
		$jnow = JFactory::getDate();
		// $now = $jnow->toMySQL();
		$now = $jnow->toSQL();
		$nullDate = $this->_db->getNullDate();
		
		$state = 1;
		
		$params = $mainframe->getParams('com_seminarman');		
		
		$where = ' WHERE i.tutor_id = ' . $this->_id;

		switch ($state)
		{
			case 1:
		
				$where .= ' AND i.state = 1' . ' AND ( publish_up = ' . $this->_db->Quote($nullDate) .
				' OR publish_up <= ' . $this->_db->Quote($now) . ' )' . ' AND ( publish_down = ' .
				$this->_db->Quote($nullDate) . ' OR publish_down >= ' . $this->_db->Quote($now) .
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
		
		
		if ($params->get('filter'))
		{
		
			$filter = JRequest::getString('filter', '', 'request');
			$filter_experience_level = JRequest::getString('filter_experience_level', '', 'request');
			$filter_positiontype = JRequest::getString('filter_positiontype', '', 'request');
		
		
			if ($filter)
			{
		
				$filter = $this->_db->escape(trim(JString::strtolower($filter)));
				$like = $this->_db->Quote('%'. $this->_db->escape($filter, true) .'%', false);
		
				$where .= ' AND ( LOWER( i.title ) LIKE ' . $like .' OR LOWER( i.code ) LIKE '. $like .')';
			}
		} else {
			$filter_experience_level = null;
		}
		
		
		if ($filter_experience_level>0)
		{
			$where .= ' AND LOWER( i.id_experience_level ) = ' . $filter_experience_level;
		}
		
		return $where . ' GROUP BY i.id';
	}
	
	function getEditableCustomfields($tutorId = null)
	{
		$db   = $this->getDBO();
		$data = array();
	
		if (!empty($tutorId))
		{
			$q = 'SELECT f.*, v.value FROM `#__seminarman_fields` AS f'.
					' LEFT JOIN `#__seminarman_fields_values_tutors` AS v'.
					' ON f.id = v.field_id AND v.tutor_id = '.(int)$tutorId.
					' WHERE f.visible=1 AND f.published=1 ORDER BY f.ordering';
			$db->setQuery($q);
		}
		else
		{
			$q = 'SELECT f.*, v.value FROM `#__seminarman_fields` AS f'.
					' LEFT JOIN `#__seminarman_fields_values_tutors` AS v'.
					' ON f.id = v.field_id AND v.tutor_id = 0'.
					' WHERE f.visible=1 AND f.published=1 ORDER BY f.ordering';
			$db->setQuery($q);
		}
	
		$result	= $db->loadAssocList();
	
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
	
		$data['fields']	= array();
		for($i = 0; $i < count($result); $i++)
		{
		// We know that the groups will definitely be correct in ordering.
			if($result[$i]['type'] == 'group' && $result[$i]['purpose'] == 2)
			{
			$add = True;
			$group	= $result[$i]['name'];
	
			// Group them up
			if(!isset($data['fields'][$group]))
			{
			// Initialize the groups.
			$data['fields'][$group]	= array();
			}
			}
			if($result[$i]['type'] == 'group' && $result[$i]['purpose'] != 2)
				$add = False;
	
				// Re-arrange options to be an array by splitting them into an array
				if(isset($result[$i]['options']) && $result[$i]['options'] != '')
				{
				$options	= $result[$i]['options'];
				$options	= explode("\n", $options);
	
				$countOfOptions = count($options);
				for($x = 0; $x < $countOfOptions; $x++){
				$options[$x] = trim($options[$x]);
				}
	
				$result[$i]['options']	= $options;
	
				}
	
	
				if($result[$i]['type'] != 'group' && isset($add)){
				if($add)
					$data['fields'][$group][]	= $result[$i];
				}
				}
				
				return $data;
		}
}