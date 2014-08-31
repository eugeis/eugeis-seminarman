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

class TableEditfield extends JTable
{

    function __construct(&$db)
    {
        parent::__construct('#__seminarman_fields', 'id', $db);
    }

    function bind($array, $ignore = '')
    {
        if (key_exists('params', $array) && is_array($array['params']))
        {
            $registry = new JRegistry();
            $registry->loadArray($array['params']);
            $array['params'] = $registry->toString();
        }

        return parent::bind($array, $ignore);
    }

    function check()
    {

        return true;
    }

	function load( $id = null , $isGroup = false )
	{
		if( $id == 0 )
		{
			$this->id				= 0;
			$this->type				= ( $isGroup ) ? 'group' : '';
			$this->published		= true;
			$this->visible			= true;
			$this->required			= true;
			$this->name				= '';
			$this->tips				= '';
			$this->min				= 10;
			$this->max				= 100;
			$this->options			= '';
			$this->registration		= true;
		}
		else
		{
			parent::load( $id );
		}
	}

	function store( $groupOrdering = '' )
	{
		$db		= $this->getDBO();

		// For new groups, we need to get the max ordering
		if( $this->type == 'group' )
		{
			if( $this->id == 0 )
			{
				// Set the ordering
				$query	= 'SELECT MAX(' . $db->quoteName('ordering') . ') FROM ' . $db->quoteName('#__seminarman_fields');

				$db->setQuery( $query );
				$this->ordering	= $db->loadResult() + 1;
			}
		}
		else
		{
			if( (!empty( $groupOrdering ) && $this->id == 0 ) || ( !empty($groupOrdering) && $this->getCurrentParent() != $groupOrdering ) )
			{
				// Get the last ordering for this groups item
				// Now increment the rest of the ordering.
				$query	= 'UPDATE ' . $db->quoteName( '#__seminarman_fields' ). ' '
						. 'SET ordering=ordering + 1 '
						. 'WHERE ordering > ' . $groupOrdering;
				//$firstItemOrdering;
				$db->setQuery( $query );
				$db->query();

				$this->ordering	= $groupOrdering + 1;
			}
		}

		return parent::store();
	}

	function getCurrentParent()
	{
		$db		= $this->getDBO();

		$query	= 'SELECT ' . $db->quoteName( 'ordering' ) . ' '
				. 'FROM ' . $db->quoteName( '#__seminarman_fields' ) . ' '
				. 'WHERE ' . $db->quoteName( 'ordering' ) . '<' . $this->ordering . ' '
				. 'AND ' . $db->quoteName( 'type' ) . '=' . $db->Quote( 'group' ) . ' '
				. 'ORDER BY ordering DESC';
		$db->setQuery( $query );

		return $db->loadResult();
	}

}