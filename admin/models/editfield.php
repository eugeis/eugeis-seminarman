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

class SeminarmanModelEditfield extends JModelLegacy
{
    var $_tag = null;

    function __construct()
    {
        parent::__construct();

        $array = JRequest::getVar('cid', 0, '', 'array');
        $this->setId((int)$array[0]);
    }

    function setId($id)
    {

        $this->_id = $id;
        $this->_tag = null;
    }

    function get($property, $default = null)
    {
        if ($this->_loadTag())
        {
            if (isset($this->_tag->$property))
            {
                return $this->_tag->$property;
            }
        }
        return $default;
    }

    function &getTag()
    {
        if ($this->_loadTag())
        {

        } else
            $this->_initTag();

        return $this->_tag;
    }


    function _loadTag()
    {

        if (empty($this->_tag))
        {
            $query = 'SELECT *' . ' FROM #__seminarman_fields' . ' WHERE id = ' . $this->_id;
            $this->_db->setQuery($query);
            $this->_tag = $this->_db->loadObject();

            return (boolean)$this->_tag;
        }
        return true;
    }

    function _initTag()
    {

        if (empty($this->_tag))
        {
            $tag = new stdClass();
            $tag->id = 0;
            $tag->name = null;
            $tag->alias = null;
            $tag->purpose = 0;
            $tag->published = 1;
            $tag->ordering = null;
            $tag->type = null;
            $tag->min = null;
			$tag->max = null;
			$tag->options = null;
			$tag->fieldcode = null;
			$tag->paypalcode = null;
			$tag->visible = 1;
			$tag->required = 0;
			$tag->registration = 0;
    		$tag->tips = null;
            $this->_tag = $tag;
            return (boolean)$this->_tag;
        }
        return true;
    }

    function checkin()
    {
        if ($this->_id)
        {
            $tag = JTable::getInstance('seminarman_tags', '');
            return $tag->checkout($uid, $this->_id);
        }
        return false;
    }

    function checkout($uid = null)
    {
        if ($this->_id)
        {

            if (is_null($uid))
            {
                $user = JFactory::getUser();
                $uid = $user->get('id');
            }

            $tag = JTable::getInstance('seminarman_tags', '');
            return $tag->checkout($uid, $this->_id);
        }
        return false;
    }

    function isCheckedOut($uid = 0)
    {
        //if ($this->_loadTag())
        //{
          //  if ($uid)
           // {
            //    return ($this->_tag->checked_out && $this->_tag->checked_out != $uid);
            //} else
            //{
             //   return $this->_tag->checked_out;
            //}
        //} elseif ($this->_id < 1)
        //{
         //   return false;
        //} else
        //{
         //   JError::raiseWarning(0, 'UNABLE LOAD DATA');
          //  return false;
       // }
    }

    function store($data)
    {
        //$tag = $this->getTable('editfield', '');
    	$tag = $this->getTable();

        if (!$tag->bind($data))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        if (!$tag->check())
        {
            $this->setError($tag->getError());
            return false;
        }

        if (!$tag->store())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        $this->_tag = &$tag;

        return $tag->id;
    }

    function addtag($name)
    {

        $obj = new stdClass();
        $obj->name = $name;
        $obj->published = 1;

        $this->store($obj);


        return true;
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
		$query	= 'SELECT COUNT(*) FROM ' . $db->quoteName( '#__seminarman_fields' );
		$db->setQuery( $query );
		$total	= $db->loadResult();

		jimport('joomla.html.pagination');

		// Get the pagination object
		$this->_pagination	= new JPagination( $total , $limitstart , $limit );

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

	function getCustomfieldsTypes()
	{
		static $types = false;

		if( !$types )
		{
			$path	= JPATH_ROOT . DS . 'components' . DS . 'com_seminarman' . DS . 'libraries' . DS . 'fields' . DS . 'customfields.xml';

			// $parser	= JFactory::getXMLParser( 'Simple' );
			// $parser->loadFile( $path );
			$parser = JFactory::getXML($path);
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
}

?>