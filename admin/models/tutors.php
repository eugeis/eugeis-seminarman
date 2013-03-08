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

class seminarmanModeltutors extends JModel
{
    var $_data = null;

    var $_total = null;

    var $_pagination = null;

    function __construct()
    {
        parent::__construct();

        $mainframe = JFactory::getApplication();
        $this->childviewname = 'tutors';

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

        $query = ' SELECT a.*, u.name AS editor, cc.code as country_code, u.block as userpublished ' .
            ' FROM #__seminarman_tutor AS a ' .
            ' LEFT JOIN #__users AS u ON u.id = a.user_id ' .
            ' LEFT JOIN #__seminarman_country AS cc ON cc.id = a.id_country ' . $where . $orderby;

        return $query;
    }

    function _buildContentOrderBy()
    {
        $mainframe = JFactory::getApplication();

        $filter_order = $mainframe->getUserStateFromRequest('com_seminarman' .
            '.tutors.filter_order', 'filter_order', 'a.ordering', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest('com_seminarman' .
            '.tutors.filter_order_Dir', 'filter_order_Dir', '', 'word');

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
        $filter_state = $mainframe->getUserStateFromRequest('com_seminarman' .
            '.tutors.filter_state', 'filter_state', '', 'word');
        $filter_order = $mainframe->getUserStateFromRequest('com_seminarman' .
            '.tutors.filter_order', 'filter_order', 'a.ordering', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest('com_seminarman' .
            '.tutors.filter_order_Dir', 'filter_order_Dir', '', 'word');
        $search = $mainframe->getUserStateFromRequest('com_seminarman' . '.tutors.search',
            'search', '', 'string');
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
                $where[] = 'u.block = 0';
            } else
                if ($filter_state == 'U')
                {
                    $where[] = 'u.block= 1';
                }
        }

        $where = (count($where) ? ' WHERE ' . implode(' AND ', $where) : '');

        return $where;
    }


}