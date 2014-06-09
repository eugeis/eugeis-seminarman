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

class SeminarmanModelTutors extends JModelLegacy
{
	var $_data = null;
	var $_id = null;
	
	function __construct()
	{
		parent::__construct();
	}

	function getTutors()
	{
		if (empty($this->_data))
		{
			$query = $this->_buildQuery($this->_id);
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->
					getState('limit'));
		}
		
		return $this->_data;		
	}
	
	function _buildQuery()
	{
	
		$where = $this->_buildTutorWhere();
		$orderby = $this->_buildTutorOrderBy();
	
		$query = 'SELECT CONCAT_WS(" ", t.other_title, t.firstname, t.lastname) as tutor_label,' .
				' CASE WHEN CHAR_LENGTH(t.alias) THEN CONCAT_WS(\':\', t.id, t.alias) ELSE t.id END as tutor_slug,' .
				' t.id AS tutor_id, t.logofilename AS tutor_photo, t.description AS tutor_desc' .
				' FROM #__seminarman_tutor AS t' . $where . $orderby;
	
		return $query;
	}
	
	function _buildTutorOrderBy()
	{
	//	$filter_order = $this->getState('filter_order');
	//	$filter_order_dir = $this->getState('filter_order_dir');
	
	//	return ' ORDER BY '. $filter_order .' '. $filter_order_dir .', i.title';
		return ' ORDER BY t.lastname';
	}
	
	function _buildTutorWhere()
	{
		return ' WHERE t.published = 1';
	}
	
}