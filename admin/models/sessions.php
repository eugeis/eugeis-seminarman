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

class seminarmanModelSessions extends JModel
{
    var $_data = null;

    var $_total = null;

    var $_pagination = null;

    function __construct()
    {
        parent::__construct();

        $mainframe = JFactory::getApplication();
        $this->childviewname = 'session';

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

        $query = ' SELECT a.*, u.name AS editor, c.title AS coursetitle ' . ' FROM #__seminarman_sessions AS a ' .
        	' LEFT JOIN #__users AS u ON u.id = a.checked_out ' .
        	' LEFT JOIN #__seminarman_courses AS c ON c.id = a.courseid ' .
            $where . $orderby;

        return $query;
    }

    function _buildContentOrderBy()
    {
        $mainframe = JFactory::getApplication();

        $filter_order = $mainframe->getUserStateFromRequest('com_seminarman' . $this->
            childviewname . '.filter_order', 'filter_order', 'a.ordering', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest('com_seminarman' . $this->
            childviewname . '.filter_order_Dir', 'filter_order_Dir', '', 'word');

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
        $db = &JFactory::getDBO();
        $filter_state = $mainframe->getUserStateFromRequest('com_seminarman' . $this->
            childviewname . '.filter_state', 'filter_state', '', 'word');
        $filter_order = $mainframe->getUserStateFromRequest('com_seminarman' . $this->
            childviewname . '.filter_order', 'filter_order', 'a.ordering', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest('com_seminarman' . $this->
            childviewname . '.filter_order_Dir', 'filter_order_Dir', '', 'word');
$filter_courseid		= $mainframe->getUserStateFromRequest( 'com_seminarman'.'filter_courseid',		'filter_courseid',		0,				'int' );
        $search = $mainframe->getUserStateFromRequest('com_seminarman' . $this->childviewname .
            '.search', 'search', '', 'string');
        $search = JString::strtolower($search);

        $where = array();


        if ($search)
        {
            $where[] = ' LOWER(a.title) LIKE ' . $db->Quote('%' . $db->getEscaped($search, true) .
                '%', false);
        }


        if ($filter_state)
        {
            if ($filter_state == 'P')
            {
                $where[] = 'a.published = 1';
            } else
                if ($filter_state == 'U')
                {
                    $where[] = 'a.published = 0';
                }
        }
    	if ($filter_courseid > 0) {
    		$where[] = 'a.courseid = '.(int) $filter_courseid;
    	}

        $where = (count($where) ? ' WHERE ' . implode(' AND ', $where) : '');

        return $where;
    }

	/* Method to fetch group titles and ids
	   *
	   * @access public
	   * @return string
	*/
	function getCourseTitles()
	{
		$db =& JFactory::getDBO();
		if(JHTMLSeminarman::UserIsCourseManager()) {
		    $sql = 'SELECT id, title'
		    . ' FROM #__seminarman_courses'
		    . ' WHERE state = 1'
		    . ' ORDER BY title';
		}else{
		    $sql = 'SELECT id, title'
		    . ' FROM #__seminarman_courses'
		    . ' WHERE (state = 1 AND tutor_id = ' . JHTMLSeminarman::getUserTutorID() . ')'
		    . ' ORDER BY title';			
		}
		$db->setQuery($sql);
		$coursetitles = $db->loadObjectlist();
		return $coursetitles;
	}


}