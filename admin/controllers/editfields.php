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

jimport( 'joomla.application.component.controller' );

/**
 * Jom Social Customfields Controller
 */
class SeminarmanControllerEditfields extends JController
{
	function __construct()
	{
		parent::__construct();

		$this->registerTask( 'publish' , 'savePublish' );
		$this->registerTask( 'unpublish' , 'savePublish' );
		$this->registerTask( 'orderup' , 'saveOrder' );
		$this->registerTask( 'orderdown' , 'saveOrder' );
	}

	/**
	 * Removes the specific field
	 *
	 * @access public
	 *
	 **/
	function removeField()
	{
		$mainframe	= JFactory::getApplication();

 		$ids	= JRequest::getVar( 'cid', array(), 'post', 'array' );

		foreach( $ids as $id )
		{
			$table	= JTable::getInstance( 'editfield', 'Table' );
			$table->load( $id );

			if(!$table->delete( $id ))
			{
				// If there are any error when deleting, we just stop and redirect user with error.
				$message	= JText::_('COM_SEMINARMAN_OPERATION_FAILED');
				$mainframe->redirect( 'index.php?option=com_seminarman&task=customfields' , $message);
				exit;
			}
		}


		$message	= JText::_( 'COM_SEMINARMAN_OPERATION_SUCCESSFULL');
 		$mainframe->redirect( 'index.php?option=com_seminarman&view=editfields' , $message );
	}

	/**
	 * Save the ordering of the entire records.
	 *
	 * @access public
	 *
	 **/
	function saveOrder()
	{
		$mainframe = JFactory::getApplication();

		// Determine whether to order it up or down
		$direction	= ( JRequest::getWord( 'task' , '' ) == 'orderup' ) ? -1 : 1;

		// Get the ID in the correct location
 		$id			= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$db			= JFactory::getDBO();

		if( isset( $id[0] ) )
		{
			$id		= (int) $id[0];

			// Load the JTable Object.
			$table	= JTable::getInstance( 'editfield' , 'table' );

			$table->load( $id );

			if( $table->type == 'group' )
			{
				$query	= 'SELECT * FROM ' . $db->nameQuote( '#__seminarman_fields' ) . ' '
						. 'WHERE ' . $db->nameQuote( 'ordering' ) . ' > ' . $db->Quote( $table->ordering ) . ' '
						. 'AND ' . $db->nameQuote( 'type' ) . '=' . $db->Quote( 'group' ) . ' '
						. 'ORDER BY ordering ASC '
						. 'LIMIT 1';

				$db->setQuery( $query );
				$nextGroup	= $db->loadObject();

				if( $nextGroup || $direction == -1 )
				{
					if( $direction == -1 )
					{
						// Get previous group in list
						$query	= 'SELECT * FROM ' . $db->nameQuote( '#__seminarman_fields' ) . ' '
								. 'WHERE ' . $db->nameQuote( 'ordering' ) . ' < ' . $db->Quote( $table->ordering ) . ' '
								. 'AND ' . $db->nameQuote( 'type' ) . '=' . $db->Quote( 'group' ) . ' '
								. 'ORDER BY ordering DESC LIMIT 1';

						$db->setQuery( $query );
						$previousGroup	= $db->loadObject();

						$query	= 'SELECT * FROM ' . $db->nameQuote( '#__seminarman_fields' ) . ' '
								. 'WHERE ' . $db->nameQuote( 'ordering' ) . ' >= ' . $db->Quote( $table->ordering);

						if( $nextGroup )
						{
							$query	.= ' AND ' . $db->nameQuote( 'ordering' ) . ' < ' . $db->Quote( $nextGroup->ordering );
						}

						$query .= 'ORDER BY ordering ASC';

						$db->setQuery( $query );
						$currentFields	= $db->loadObjectList();

						// Get previous fields in the group
						$query		= 'SELECT * FROM ' . $db->nameQuote( '#__seminarman_fields' ) . ' '
									. 'WHERE ' . $db->nameQuote( 'ordering' ) . ' >= ' . $db->Quote( $previousGroup->ordering ) . ' '
									. 'AND ' . $db->nameQuote( 'ordering') . ' < ' . $db->Quote( $table->ordering ) . ' '
									. 'ORDER BY ordering ASC';

						$db->setQuery( $query );
						$previousFields	= $db->loadObjectList();

						for( $i = 0; $i < count( $previousFields ); $i++ )
						{
							$row	=& $previousFields[ $i ];

							$row->ordering			= $row->ordering + count( $currentFields );

							$query	= 'UPDATE ' . $db->nameQuote( '#__seminarman_fields' ) . ' '
									. 'SET ' . $db->nameQUote('ordering') . '=' . $db->Quote( $row->ordering ) . ' '
									. 'WHERE ' . $db->nameQuote( 'id' ) . '=' . $db->Quote( $row->id );
							$db->setQuery( $query );
							$db->query();
						}

						for( $i = 0; $i < count( $currentFields ); $i ++ )
						{
							$row	=& $currentFields[ $i ];

							$row->ordering	= $row->ordering - count( $previousFields );

							$query	= 'UPDATE ' . $db->nameQuote( '#__seminarman_fields' ) . ' '
									. 'SET ' . $db->nameQUote('ordering') . '=' . $db->Quote( $row->ordering ) . ' '
									. 'WHERE ' . $db->nameQuote( 'id' ) . '=' . $db->Quote( $row->id );
							$db->setQuery( $query );
							$db->query();
						}
					}
					else
					{
						// Get end
						$query	= 'SELECT ordering FROM ' . $db->nameQuote( '#__seminarman_fields' ) . ' '
								. 'WHERE ' . $db->nameQuote( 'ordering' ) . ' > ' . $db->Quote( $nextGroup->ordering ) . ' '
								. 'AND ' . $db->nameQuote( 'type' ) . '=' . $db->Quote( 'group' ) . ' '
								. 'ORDER BY ordering ASC '
								. 'LIMIT 1';
						$db->setQuery( $query );
						$nextGroupLimit	= $db->loadResult();

						// Get the next group childs
						if( $nextGroupLimit )
						{
							$query		= 'SELECT * FROM ' . $db->nameQuote( '#__seminarman_fields' ) . ' '
										. 'WHERE ordering >=' . $nextGroup->ordering . ' '
										. 'AND ordering < ' . $nextGroupLimit . ' '
										. 'ORDER BY ordering ASC';
						}
						else
						{
							$query		= 'SELECT * FROM ' . $db->nameQuote( '#__seminarman_fields' ) . ' '
										. 'WHERE ordering >=' . $nextGroup->ordering . ' '
										. 'ORDER BY ordering ASC';
						}
						$db->setQuery( $query );
						$nextGroupChilds	= $db->loadObjectList();

						$nextGroupsCount	= count( $nextGroupChilds );

						// Get all childs of the current group field
						$query		= 'SELECT * FROM ' . $db->nameQuote( '#__seminarman_fields' ) . ' '
									. 'WHERE ordering >=' . $table->ordering . ' '
									. 'AND ordering < ' . $nextGroup->ordering . ' '
									. 'ORDER BY ordering ASC';

						$db->setQuery( $query );
						$currentGroupChilds	= $db->loadObjectList();
						$currentGroupsCount	= count( $currentGroupChilds );

						for( $i = 0; $i < count( $nextGroupChilds ); $i++ )
						{
							$row	=& $nextGroupChilds[ $i ];

							//$row->ordering		= $row->ordering - $currentGroupsCount;
							$row->ordering			= $table->ordering++;
							$query	= 'UPDATE ' . $db->nameQuote( '#__seminarman_fields' ) . ' '
									. 'SET ' . $db->nameQUote('ordering') . '=' . $db->Quote( $row->ordering ) . ' '
									. 'WHERE ' . $db->nameQuote( 'id' ) . '=' . $db->Quote( $row->id );

							$db->setQuery( $query );
							$db->query();
						}

						for( $i = 0; $i < count( $currentGroupChilds ); $i ++ )
						{
							$child	=& $currentGroupChilds[ $i ];

							$child->ordering	= $nextGroupsCount + $child->ordering;

							$query	= 'UPDATE ' . $db->nameQuote( '#__seminarman_fields' ) . ' '
									. 'SET ' . $db->nameQUote('ordering') . '=' . $db->Quote( $child->ordering ) . ' '
									. 'WHERE ' . $db->nameQuote( 'id' ) . '=' . $db->Quote( $child->id );
							$db->setQuery( $query );
							$db->query();
						}
					}
				}
			}
			else
			{
				$table->move( $direction );
			}

			$cache	= JFactory::getCache( 'com_content');
			$cache->clean();

			$mainframe->redirect( 'index.php?option=com_seminarman&view=editfields' );
		}

	}


function newfield()   {
	// Check for request forgeries
	JRequest::checkToken() or jexit( 'Invalid Token' );
	JRequest::setVar('view', 'editfield');
	JRequest::setVar('hidemainmenu', 1);

	$model = $this->getModel('editfield');
	$user = JFactory::getUser();

	if ($model->isCheckedOut($user->get('id')))
	{
		$this->setRedirect('index.php?option=com_seminarman&view=editfields', JText::_('ECOM_SEMINARMAN_RECORD_EDITED'));
	}

	$model->checkout($user->get('id'));

	parent::display();
}

function newgroup()   {

	// Check for request forgeries
	JRequest::checkToken() or jexit( 'Invalid Token' );
	JRequest::setVar('view', 'editfield');
	JRequest::setVar('layout', 'editgroup');
	JRequest::setVar('hidemainmenu', 1);

	$model = $this->getModel('editfield');
	$user = JFactory::getUser();

	if ($model->isCheckedOut($user->get('id')))
	{
		$this->setRedirect('index.php?option=com_seminarman&view=editfields', JText::_('COM_SEMINARMAN_RECORD_EDITED'));
	}

	$model->checkout($user->get('id'));

	parent::display();
}

function publish()   {
	// Check for request forgeries
	JRequest::checkToken() or jexit( 'Invalid Token' );
	$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
	JArrayHelper::toInteger($cid);

	if (count( $cid ) < 1) {
		JError::raiseError(500, JText::_( 'COM_SEMINARMAN_SELECT_ITEM' ) );
	}

	$model = $this->getModel('editfields');

	if(!$model->publish($cid, 1)) {
		echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
	}

	$this->setRedirect( 'index.php?option=com_seminarman&view=editfields' );
}


function unpublish()   {
	// Check for request forgeries
	JRequest::checkToken() or jexit( 'Invalid Token' );
	$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
	JArrayHelper::toInteger($cid);

	if (count( $cid ) < 1) {
		JError::raiseError(500, JText::_( 'COM_SEMINARMAN_SELECT_ITEM' ) );
	}

	$model = $this->getModel('editfields');

	if(!$model->publish($cid, 0)) {
		echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
	}

	$this->setRedirect( 'index.php?option=com_seminarman&view=editfields' );
}


}