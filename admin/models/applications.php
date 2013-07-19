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

class seminarmanModelapplications extends JModel
{
    var $_data = null;

    var $_total = null;

    var $_pagination = null;

    var $currentPeriodId = null;
    
    function __construct()
    {
        parent::__construct();

        $mainframe = JFactory::getApplication();
        $this->childviewname = 'application';
		
        $this->currentPeriodId = $mainframe->getUserStateFromRequest('com_seminarman' . 'periods.filter_periodid', 'filter_periodid', 0, 'int' );
		
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->
            getCfg('list_limit'), 'int');
        $limitstart = $mainframe->getUserStateFromRequest('com_seminarman' . '.limitstart',
            'limitstart', 0, 'int');

        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
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

        $query = ' SELECT a.*, a.user_id, u.name AS editor, j.reference_number, j.title, j.id AS courseid, j.start_date, j.finish_date, j.code' .
            ' FROM #__seminarman_' . $this->childviewname . ' AS a ' .
            ' LEFT JOIN #__users AS u ON u.id = a.checked_out ' .
            ' LEFT JOIN #__seminarman_courses AS j ON j.id = a.course_id' . $where . $orderby;

        return $query;
    }

    function _buildContentOrderBy()
    {
        $mainframe = JFactory::getApplication();

        $filter_order = $mainframe->getUserStateFromRequest('com_seminarman' . $this->
            childviewname . '.filter_order', 'filter_order', 'a.id', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest('com_seminarman' . $this->
            childviewname . '.filter_order_Dir', 'filter_order_Dir', 'desc', 'word');

        if ($filter_order == 'a.ordering')
        {
            $orderby = ' ORDER BY a.ordering ' . $filter_order_Dir;
        } else
        {
            $orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir .
                ' , a.ordering ';
        }

        return $orderby;
    }

    function _buildContentWhere()
    {
        $mainframe = JFactory::getApplication();
        $db = JFactory::getDBO();
        $filter_state = $mainframe->getUserStateFromRequest('com_seminarman' . $this->
            childviewname . '.filter_state', 'filter_state', '', 'word');
    	$filter_courseid     = $mainframe->getUserStateFromRequest( 'com_seminarman'.'.applications.filter_courseid',    'filter_courseid',      0,          'int' );
    	$filter_statusid     = $mainframe->getUserStateFromRequest( 'com_seminarman'.'.applications.filter_statusid',    'filter_statusid',      0,          'int' );

        $filter_order = $mainframe->getUserStateFromRequest('com_seminarman' . $this->
            childviewname . '.filter_order', 'filter_order', 'a.ordering', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest('com_seminarman' . $this->
            childviewname . '.filter_order_Dir', 'filter_order_Dir', '', 'word');
    	$filter_search     = $mainframe->getUserStateFromRequest( 'com_seminarman'.'.application.filter_search',    'filter_search',      '',            'int' );

        $search = $mainframe->getUserStateFromRequest('com_seminarman' . $this->childviewname .
            '.search', 'search', '', 'string');
        $search = JString::strtolower($search);

        $where = array();

    	if ($filter_courseid > 0) {
    		$where[] = 'a.course_id = '.(int) $filter_courseid;
    	}

    	if ($filter_statusid > 0) {
    		$where[] = 'a.status = '.((int) $filter_statusid - 1);
    	}
    	
    	if ($this->currentPeriodId > 0) {
    		$currentPeriod = $this->getCurrentPeriod();
    		$where[] = '(j.finish_date >= "' . $currentPeriod->start_date . '" AND ' . 'j.start_date <= "' . $currentPeriod->finish_date . '")';
    	}

    	if ($search && $filter_search == 1) {
    		$where[] = ' LOWER(a.last_name) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
    	}

    	if ($search && $filter_search == 2) {
    		$where[] = ' LOWER(a.first_name) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
    	}

    	if ($search && $filter_search == 3) {
    		$where[] = ' LOWER(a.email) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
    	}
    	
    	if ($search && $filter_search == 4) {
    		$where[] = ' LOWER(j.code) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
    	}

    	if ($search && $filter_search == 5) {
    		if ($this->searchForNull($search)) {
    			$where[] = ' (a.note IS NULL OR length(a.note) = 0)';
    		}else {
    			$where[] = ' a.note = '.(double)$search;
    		}
    	}
    	
    	if ($search && $filter_search == 6) {
    		if ($this->searchForNull($search)) {
    			$where[] = ' (a.attendance IS NULL OR length(a.attendance) = 0)';
    		}else {
    			$where[] = ' a.attendance = '.(int)$search;
    		}
    	}
    	
    	if ($search && $filter_search == 7) {
    		$where[] = ' LOWER(j.title) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
    	}
    	
       	switch ($filter_state)
       	{
       		case 'P':
       			$where[] = 'a.published = 1';
       			break;
       		case 'U':
       			$where[] = 'a.published = 0';
       			break;
      		case 'T':
       			$where[] = 'a.published = -2';
       			break;
      		default:
      			$where[] = 'a.published !=  -2';
       	}
       	
        if(!(JHTMLSeminarman::UserIsCourseManager())){
            $where[] = 'j.tutor_id = ' . JHTMLSeminarman::getUserTutorID();
        }

        $where = (count($where) ? ' WHERE ' . implode(' AND ', $where) : '');

        return $where;
    }
    
    function searchForNull($search) {
    	return strcasecmp($search, 'null') == 0;
    }

	/* Method to fetch course titles
	   *
	   * @access public
	   * @return string
	*/
	function getTitles()
	{
		$db = JFactory::getDBO();
		if(JHTMLSeminarman::UserIsCourseManager()){
		$sql = 'SELECT id, title as title'
		. ' FROM #__seminarman_courses'
		. ' WHERE state = 1'
		. ' ORDER BY title';
		}else{
		$sql = 'SELECT id, title as title'
		. ' FROM #__seminarman_courses'
		. ' WHERE (state = 1 AND tutor_id = ' . JHTMLSeminarman::getUserTutorID() . ')' 
		. ' ORDER BY title';			
		}
		$db->setQuery($sql);
		$titles = $db->loadObjectlist();
		return $titles;
	}
	
	function getCurrentPeriod()
	{
		$db = JFactory::getDBO();
		$sql = 'SELECT * FROM #__seminarman_period WHERE id = '. $this->currentPeriodId;
		$db->setQuery($sql);
		$ret = $db->loadObject();
		return $ret;
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
				
			$q = 'SELECT f.*, v.value FROM `#__seminarman_fields` AS f'.
					' LEFT JOIN `#__seminarman_fields_values` AS v'.
					' ON f.id = v.field_id AND v.applicationid = '.(int)$applicationId.
					' WHERE f.published=1 AND f.visible=1 ORDER BY f.ordering';
			$db->setQuery($q);
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