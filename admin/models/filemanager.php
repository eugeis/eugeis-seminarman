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

class SeminarmanModelFilemanager extends JModelLegacy
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

            $this->_data = seminarman_images::BuildIcons($this->_data);

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
        $having = $this->_buildContentHaving();

        $query = 'SELECT f.*, u.name AS uploader, COUNT(rel.fileid) AS nrassigned' .
            ' FROM #__seminarman_files AS f' .
            ' LEFT JOIN #__seminarman_files_course_relations AS rel ON rel.fileid = f.id' .
            ' LEFT JOIN #__users AS u ON u.id = f.uploaded_by' . $where . ' GROUP BY f.id' .
            $having . $orderby;

        return $query;
    }

    function _buildContentOrderBy()
    {
        $mainframe = JFactory::getApplication();

        $filter_order = $mainframe->getUserStateFromRequest('com_seminarman' .
            '.filemanager.filter_order', 'filter_order', 'f.filename', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest('com_seminarman' .
            '.filemanager.filter_order_Dir', 'filter_order_Dir', '', 'word');

        $orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir .
            ', f.filename';

        return $orderby;
    }

    function _buildContentWhere()
    {
        $mainframe = JFactory::getApplication();

        $search = $mainframe->getUserStateFromRequest('com_seminarman' . '.filemanager.search',
            'search', '', 'string');
        $filter = $mainframe->getUserStateFromRequest('com_seminarman' . '.filemanager.filter',
            'filter', '', 'int');
        $search = $this->_db->escape(trim(JString::strtolower($search)));

        $where = array();

        if ($search && $filter == 1)
        {
            $where[] = ' LOWER(f.filename) LIKE ' . $this->_db->Quote('%' . $this->_db->
                escape($search, true) . '%', false);
        }

        if ($search && $filter == 2)
        {
            $where[] = ' LOWER(f.altname) LIKE ' . $this->_db->Quote('%' . $this->_db->
                escape($search, true) . '%', false);
        }

        $where = (count($where) ? ' WHERE ' . implode(' AND ', $where) : '');

        return $where;
    }

    function _buildContentHaving()
    {
        $mainframe = JFactory::getApplication();

        $filter_assigned = $mainframe->getUserStateFromRequest('com_seminarman' .
            '.filemanager.filter_assigned', 'filter_assigned', '', 'word');

        $having = '';

        if ($filter_assigned)
        {
            if ($filter_assigned == 'O')
            {
                $having = ' HAVING COUNT(rel.fileid) = 0';
            } else
                if ($filter_assigned == 'A')
                {
                    $having = ' HAVING COUNT(rel.fileid) > 0';
                }
        }

        return $having;
    }

    function delete($cid)
    {
        if (count($cid))
        {
            jimport('joomla.filesystem.file');

            $cids = implode(',', $cid);

            $query = 'SELECT f.filename' . ' FROM #__seminarman_files AS f' . ' WHERE f.id IN (' .
                $cids . ')';

            $this->_db->setQuery($query);
            // $filenames = $this->_db->loadResultArray();
            $filenames = $this->_db->loadColumn();

            foreach ($filenames as $name)
            {
                $path = JPath::clean(COM_SEMINARMAN_FILEPATH . DS . DS . $name);
                if (!JFile::delete($path))
                {
                    JError::raiseWarning(100, JText::_('Unable to delete:') . $path);
                }
            }

            $query = 'DELETE FROM #__seminarman_files' . ' WHERE id IN (' . $cids . ')';

            $this->_db->setQuery($query);

            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

            $query = 'DELETE FROM #__seminarman_files_course_relations' . ' WHERE fileid IN (' . $cids .
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
}

?>
