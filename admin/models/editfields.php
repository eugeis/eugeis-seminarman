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


// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.model' );

class SeminarmanModelEditfields extends JModelLegacy
{
	/**
	 * Configuration data
	 *
	 * @var object	JPagination object
	 **/
	var $_pagination;
	/**
	 * Constructor
	 */
	function __construct()
	{
		$mainframe	= JFactory::getApplication();

		// Call the parents constructor
		parent::__construct();

		// Get the pagination request variables
		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart	= $mainframe->getUserStateFromRequest( 'seminarman.limitstart', 'limitstart', 0, 'int' );

		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	/**
	 * Retrieves the JPagination object
	 *
	 * @return object	JPagination object
	 **/
	function &getPagination()
	{
		if ($this->_pagination == null)
		{
			$this->getFields();
		}
		return $this->_pagination;
	}

	/**
	 * Returns the Fields
	 *
	 * @return object
	 **/
	function &getFields()
	{
		$mainframe	= JFactory::getApplication();

		static $fields;

		if( isset( $fields ) )
		{
			return $fields;
		}

		// Initialize variables
		$db			= JFactory::getDBO();

		// Get the limit / limitstart
		$limit		= $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart	= $mainframe->getUserStateFromRequest('seminarmanlimitstart', 'limitstart', 0, 'int');

		// In case limit has been changed, adjust limitstart accordingly
		$limitstart	= ($limit != 0) ? ($limitstart / $limit ) * $limit : 0;

		// Get the total number of records for pagination
		// $query	= 'SELECT COUNT(*) FROM ' . $db->quoteName( '#__seminarman_fields' );
		$query	= 'SELECT COUNT(*) FROM ' . $db->quoteName( '#__seminarman_fields' );
		$db->setQuery( $query );
		$total	= $db->loadResult();

		jimport('joomla.html.pagination');

		// Get the pagination object
		$this->_pagination	= new JPagination( $total , $limitstart , $limit );

		// $query	= 'SELECT * FROM ' . $db->quoteName( '#__seminarman_fields' ) . ' '
		//		. 'ORDER BY ' . $db->quoteName( 'ordering' );
		$query	= 'SELECT * FROM ' . $db->quoteName( '#__seminarman_fields' ) . ' '
		. 'ORDER BY ' . $db->quoteName( 'ordering' );

		$db->setQuery( $query , $this->_pagination->limitstart , $this->_pagination->limit );

		$fields	= $db->loadObjectList();

		return $fields;
	}

	function &getGroups()
	{
		static $fieldGroups;

		if( isset( $fieldGroups ) )
		{
			return $fieldGroups;
		}

		$db		= JFactory::getDBO();

		$query	= 'SELECT * '
				. 'FROM ' . $db->quoteName( '#__seminarman_fields' )
				. 'WHERE ' . $db->quoteName( 'type' ) . '=' . $db->Quote( 'group' );

		$db->setQuery( $query );

		$fieldGroups	= $db->loadObjectList();

		return $fieldGroups;
	}

	function &getFieldGroup( $fieldId )
	{
		static $fieldGroup;

		if( isset( $fieldGroup ) )
		{
			return $fieldGroup;
		}

		$db		= JFactory::getDBO();

		$query	= 'SELECT * FROM ' . $db->quoteName( '#__seminarman_fields' )
				. 'WHERE ' . $db->quoteName( 'ordering' ) . '<' . $db->Quote( $fieldId ) . ' '
				. 'AND ' . $db->quoteName( 'type' ) . '=' . $db->Quote( 'group' )
				. 'ORDER BY ordering DESC '
				. 'LIMIT 1';

		$db->setQuery( $query );

		$fieldGroup	= $db->loadObject();

		return $fieldGroup;
	}



	function getGroupFields( $groupOrderingId )
	{
		$fieldArray	= array();
		$db			= JFactory::getDBO();

		$query	= 'SELECT * FROM ' . $db->quoteName( '#__seminarman_fields' )
				. ' WHERE ' . $db->quoteName( 'ordering' ) . '>' . $db->Quote( $groupOrderingId )
				. ' ORDER BY `ordering` ASC ';

		$db->setQuery( $query );

		$fieldGroup	= $db->loadObjectList();

		if(count($fieldGroup) > 0)
		{
			foreach($fieldGroup as $field)
			{
				if($field->type == 'group')
					break;
				else
				 	$fieldArray[]	= $field;
			}
		}

		return $fieldArray;
	}


	function getCustomfieldsTypes()
	{
		static $types = false;

		if( !$types )
		{
			$path	= JPATH_ROOT . DS . 'components' . DS . 'com_seminarman' . DS . 'libraries' . DS . 'fields' . DS . 'customfields.xml';

			// $parser	= JFactory::getXMLParser( 'Simple' );
			// $parser->loadFile( $path );
			$parser	= JFactory::getXML($path);
			// $fields	= $parser->document->getElementByPath( 'fields' );
			$fields = $parser->fields;
			
			$data	= array();

			// foreach( $fields->children() as $field )
			foreach( $fields->field as $field )
			{
				// $type	= $field->getElementByPath( 'type' );
				// $name	= $field->getElementByPath( 'name' );
				$type	= strval($field->type);
				$name	= strval($field->name);
				
				// $data[ $type->data() ]	= $name->data();
				$data[$type]	= $name;
			}
			$types	= $data;
		}
		return $types;
	}

	function publish($cid = array(), $publish = 1)
	{
		$user    = JFactory::getUser();

		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			$query = 'UPDATE #__seminarman_fields'
			   . ' SET published = '.(int) $publish
			   . ' WHERE id IN ( '.$cids.' )'
			;
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}
	
	function fields_with_empty_code() {
		$db	= JFactory::getDBO();
		$query	= 'SELECT * FROM ' . $db->quoteName( '#__seminarman_fields' ) . ' WHERE fieldcode="" AND type<>"group"';
		$db->setQuery( $query );
		return $db->loadAssocList();
	}
	
	function fields_with_same_code() {
		$db	= JFactory::getDBO();
		$q = 'SELECT f.* FROM `#__seminarman_fields` AS f ORDER BY f.ordering';
		$db->setQuery($q);
		$result	= $db->loadAssocList();
	
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
	
		// we have 3 purposes
		$purpose_booking = array();
		$purpose_sp = array();
		$purpose_tutor = array();
	
		for($i = 0; $i < count($result); $i++) {
			// We know that the groups will definitely be correct in ordering.
			if($result[$i]['type'] == 'group'){
				switch($result[$i]['purpose']){
					case 0:
						$addto = 0;
						break;
					case 1:
						$addto = 1;
						break;
					case 2:
						$addto = 2;
						break;
				}
			} else {
				switch ($addto) {
					case 0:
						$purpose_booking[] = array('name' => $result[$i]['name'], 'fieldcode' => $result[$i]['fieldcode']);
						break;
					case 1:
						$purpose_sp[] = array('name' => $result[$i]['name'], 'fieldcode' => $result[$i]['fieldcode']);
						break;
					case 2:
						$purpose_tutor[] = array('name' => $result[$i]['name'], 'fieldcode' => $result[$i]['fieldcode']);
						break;
				}
			}
		}
	
		$fields_booking = array();
		$fields_sp = array();
		$fields_tutor = array();
		$data = array();
	
		foreach ($purpose_booking as $field) {
			$code = $field['fieldcode'];
			if (!isset($fields_booking[$code])) {
				$fields_booking[$code] = 1;
			} else {
				$fields_booking[$code] ++;
				$data['booking'][$code] = $fields_booking[$code];
			}
		}
	
		foreach ($purpose_sp as $field) {
			$code = $field['fieldcode'];
			if (!isset($fields_sp[$code])) {
				$fields_sp[$code] = 1;
			} else {
				$fields_sp[$code] ++;
				$data['sp'][$code] = $fields_sp[$code];
			}
		}
	
		foreach ($purpose_tutor as $field) {
			$code = $field['fieldcode'];
			if (!isset($fields_tutor[$code])) {
				$fields_tutor[$code] = 1;
			} else {
				$fields_tutor[$code] ++;
				$data['tutor'][$code] = $fields_tutor[$code];
			}
		}
	
		return $data;
	
	}
	
	function fields_with_diff_type() {
		$db	= JFactory::getDBO();
		$q = 'SELECT f.* FROM `#__seminarman_fields` AS f ORDER BY f.ordering';
		$db->setQuery($q);
		$result	= $db->loadAssocList();
	
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
	
		// we have 3 purposes
		$purpose_booking = array();
		$purpose_sp = array();
		$purpose_tutor = array();
	
		for($i = 0; $i < count($result); $i++) {
			// We know that the groups will definitely be correct in ordering.
			if($result[$i]['type'] == 'group'){
				switch($result[$i]['purpose']){
					case 0:
						$addto = 0;
						break;
					case 1:
						$addto = 1;
						break;
					case 2:
						$addto = 2;
						break;
				}
			} else {
				switch ($addto) {
					case 0:
						$purpose_booking[] = array('name' => $result[$i]['name'], 'fieldcode' => $result[$i]['fieldcode'], 'type' => $result[$i]['type']);
						break;
					case 1:
						$purpose_sp[] = array('name' => $result[$i]['name'], 'fieldcode' => $result[$i]['fieldcode'], 'type' => $result[$i]['type']);
						break;
					case 2:
						$purpose_tutor[] = array('name' => $result[$i]['name'], 'fieldcode' => $result[$i]['fieldcode'], 'type' => $result[$i]['type']);
						break;
				}
			}
		}
	
		$data = array();
	
		foreach($purpose_booking as $f_booking){
			foreach($purpose_sp as $f_sp){
				if (($f_booking['fieldcode'] == $f_sp['fieldcode']) && ($f_booking['type'] != $f_sp['type'])) {
					$data[]=array('name1' => $f_booking['name'], 'name2' => $f_sp['name'], 'fieldcode' => $f_booking['fieldcode']);
				}
			}
		}
	
		return $data;
	}
}
