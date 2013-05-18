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

class SeminarmanModelBookings extends JModel
{
    var $_data = null;

    var $_total = null;

    function __construct()
    {
        parent::__construct();

        $mainframe = JFactory::getApplication();

        $params = $mainframe->getParams('com_seminarman');

        $limit = $mainframe->getUserStateFromRequest('com_seminarman.bookings.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);

        $this->setState('filter_order', JRequest::getCmd('filter_order', 'i.title'));
        $this->setState('filter_order_dir', JRequest::getCmd('filter_order_Dir', 'ASC'));
        $this->setState('filter_experience_level', JRequest::getCmd('filter_experience_level'));
        $this->setState('filter_positiontype', JRequest::getCmd('filter_positiontype'));
        
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
    	    ' gr.title AS cgroup, lev.title AS level, app.id AS applicationid, app.status,' . 
    	    ' app.invoice_filename_prefix AS invoice_filename_prefix, app.invoice_number AS invoice_number,' .
    		' app.note, app.attendance' .
    	    ' FROM #__seminarman_courses AS i' .
            ' LEFT JOIN #__seminarman_application AS app ON app.course_id = i.id' .
    	    ' LEFT JOIN #__seminarman_cats_course_relations AS rel ON rel.courseid = i.id' .
    	    ' LEFT JOIN #__seminarman_tutor AS emp ON emp.id = i.tutor_id' .
    	    ' LEFT JOIN #__seminarman_atgroup AS gr ON gr.id = i.id_group' .
    	    ' LEFT JOIN #__seminarman_experience_level AS lev ON lev.id = i.id_experience_level' .
    	    //' LEFT JOIN #__seminarman_sessions AS ses ON ses.courseid = i.id' .
    	    ' LEFT JOIN #__seminarman_categories AS c ON c.id = rel.catid' . $where .
    	    ' GROUP BY app.id' . $orderby;

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
        //$gid = (int)$user->get('aid');
        $params = $mainframe->getParams('com_seminarman');
        $jnow = JFactory::getDate();
        $now = $jnow->toMySQL();
        $nullDate = $this->_db->getNullDate();

        $state = 1;

        $where = ' WHERE app.user_id = ' . (int)$user->get('id');
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
    	$where .= ' AND app.published = 1';

    	if ($params->get('filter'))
    	{

    		$filter = JRequest::getString('filter', '', 'request');
    		$filter_experience_level = JRequest::getString('filter_experience_level', '', 'request');
    		$filter_positiontype = JRequest::getString('filter_positiontype', '', 'request');


    		if ($filter)
    		{

    			$filter = $this->_db->getEscaped(trim(JString::strtolower($filter)));

    			$where .= ' AND LOWER( i.title ) LIKE ' . $this->_db->Quote('%' . $this->_db->
    			    getEscaped($filter, true) . '%', false);
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

		$query = 'SELECT c.*,' . ' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as slug' .
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
//
		return $this->_category;
	}


	/**
	 * Returns an array of custom editfields which are created from the back end.
	 *
	 * @access	public
	 * @param	string 	User's id.
	 * @returns array  An objects of custom fields.
	 */
	function getEditableCustomfields($applicationId	= null)
	{
		$db   = $this->getDBO();
		$data = array();
		$user = JFactory::getUser();

		$data['id']		= $user->id;
		$data['name']	= $user->name;
		$data['email']	= $user->email;

		if (!$user->guest)
		{
			$q = 'SELECT COUNT(*) FROM `#__seminarman_fields_values`'.
					' WHERE applicationid='.(int)$applicationId.' AND user_id='.(int)$user->id;
			$db->setQuery($q);
			if ($db->loadResult() == 0)
			{
				// Es gibt noch keine Anmeldung auf den Kurs. Werte für Felder aus #__seminarman_fields_values_users holen
				// (kann aber auch leer sein)
				$q = 'SELECT f.*, v.value FROM `#__seminarman_fields` AS f'.
						' LEFT JOIN `#__seminarman_fields_values_users` AS v'.
						' ON f.fieldcode = v.fieldcode AND v.user_id = '.(int)$user->id.
						' WHERE f.published=1 AND f.visible=1 ORDER BY f.ordering';
				$db->setQuery($q);
			}
			else
			{
				$q = 'SELECT f.*, v.value FROM `#__seminarman_fields` AS f'.
						' LEFT JOIN `#__seminarman_fields_values` AS v'.
						' ON f.id = v.field_id AND v.applicationid = '.(int)$applicationId.
						' WHERE f.published=1 AND f.visible=1 ORDER BY f.ordering';
				$db->setQuery($q);
			}
		}
		else
		{
			$q = 'SELECT f.*, v.value FROM `#__seminarman_fields` AS f'.
					' LEFT JOIN `#__seminarman_fields_values` AS v'.
					' ON f.id = v.field_id AND v.applicationid = 0'.
					' WHERE f.published=1 AND f.visible=1 ORDER BY f.ordering';
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
			if($result[$i]['type'] == 'group' && $result[$i]['purpose'] == 0)
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
			if($result[$i]['type'] == 'group' && $result[$i]['purpose'] != 0)
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

?>