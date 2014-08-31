<?php
/**
 * @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
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

// no direct access
defined('_JEXEC') or die('Restricted access');

class SeminarmanCustomfieldsLibrary
{
	static function getFieldData( $fieldType , $value )
	{
		$fieldType	= strtolower( $fieldType );

		CMFactory::load( 'libraries' . DS . 'fields' , $fieldType );

		$class		= 'CFields' . ucfirst( $fieldType );

		if( class_exists( $class ) )
		{
			$object		= new $class();

			if( method_exists( $object , 'getFieldData' ) )
			{
				return $object->getFieldData( $value );
			}
		}
		if($fieldType == 'select' || $fieldType == 'singleselect' || $fieldType == 'radio')
		{
			return JText::_( $value );
		}
		else if($fieldType == 'textarea')
		{
			return nl2br($value);
		}
		else
		{
			return $value;
		}
	}

	/**
	 * Method to get the HTML output for specific fields
	 **/
	static function getFieldHTML( $field, $showRequired = '&nbsp; *', $readonly = false )
	{
		$fieldType	= strtolower( $field->type);

		if (is_array($field))
		{
			jimport( 'joomla.utilities.arrayhelper');
			$field = JArrayHelper::toObject($field);
		}

		CMFactory::load( 'libraries' . DS . 'fields' , $fieldType );

		$class	= 'CFields' . ucfirst( $fieldType );

		if (is_object($field->options))
		{
			$field->options = JArrayHelper::fromObject($field->options);
		}

		// Clean the options
		if( !empty( $field->options ) && !is_array( $field->options ) )
		{
			array_walk( $field->options , array( 'JString' , 'trim' ) );
		}


		if( !isset($field->value) )
		{
			$field->value	= '';
		}

		if( class_exists( $class ) )
		{
			$object	= new $class();

			if( method_exists( $object, 'getFieldHTML' ) )
			{
				$html	= $object->getFieldHTML( $field , $showRequired );
				return $html;
			}
		}
		return JText::sprintf('UNKNOWN CUSTOM FIELD TYPE' , $class , $fieldType );
	}

	/**
	 * Method to validate any custom field in PHP. Javascript validation is not sufficient enough.
	 * We also need to validate fields in PHP since if the user knows how to send POST data, then they
	 * will bypass javascript validations.
	 **/
	static function validateField( $fieldType , $value , $required )
	{
		$fieldType	= strtolower( $fieldType );

		CMFactory::load( 'libraries' . DS . 'fields' , $fieldType );

		$class	= 'CFields' . ucfirst( $fieldType );

		if( class_exists( $class ) )
		{
			$object	= new $class();

			if( method_exists( $object, 'isValid' ) )
			{
				return $object->isValid( $value , $required );
			}
		}
		// Assuming there is no need for validation in these subclasses.
		return true;
	}

	static function formatData( $fieldType , $value )
	{
		$fieldType	= strtolower( $fieldType );

		CMFactory::load( 'libraries' . DS . 'fields' , $fieldType );

		$class	= 'CFields' . ucfirst( $fieldType );

		if( class_exists( $class ) )
		{
			$object	= new $class();

			if( method_exists( $object, 'formatData' ) )
			{
				return $object->formatData( $value );
			}
		}
		// Assuming there is no need for formatting in subclasses.
		return $value;
	}
}