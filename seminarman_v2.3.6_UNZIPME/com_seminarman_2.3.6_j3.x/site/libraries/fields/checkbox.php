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

class CFieldsCheckbox
{
	function _translateValue( &$string )
	{
		$string	= JText::_( $string );
	}

	/**
	 * Method to format the specified value for text type
	 **/
	function getFieldData( $value )
	{
		// Since multiple select has values separated by commas, we need to replace it with <br />.
		$fieldArray	= explode ( ',' , $value );

		array_walk($fieldArray, array('CFieldsCheckbox', '_translateValue'));

		$fieldValue = implode('<br />', $fieldArray);
		return $fieldValue;
	}

	function getFieldHTML( $field , $required )
	{
		$class				= ($field->required == 1) ? ' required validate-custom-checkbox' : '';
		$lists				= is_array( $field->value ) ? $field->value : explode(',', $field->value);
		$html				= '';
		$elementSelected	= 0;
		$elementCnt	        = 0;

		foreach( $field->options as $option )
		{
			$selected	= in_array( JString::trim( $option ) , $lists ) ? ' checked="checked"' : '';

			if( empty( $selected ) )
			{
				$elementSelected++;
			}
			$elementCnt++;
		}

		$cnt = 0;
		CMFactory::load( 'helpers' , 'string' );
		$html	.= '<div class="hasTip tipRight" style="display: inline-block;" title="' . $field->name . '::' . cEscape( $field->tips ). '">';
		foreach( $field->options as $option )
		{
			$selected	= in_array( $option, $lists ) ? ' checked="checked"' : '';

			$html .= '<label class="lblradio-block">';
			$html .= '<input type="checkbox" name="field' . $field->id . '[]" value="' . $option . '"' . $selected . ' class="checkbox '.$class.'" style="margin: 0 5px 5px 0;" />';
			$html .= JText::_( $option ) . '</label>';

			$cnt++;
		}
		$html   .= '<span id="errfield'.$field->id.'msg" style="display: none;">&nbsp;</span>';
		$html	.= '</div>';

		return $html;
	}

	function isValid( $value , $required )
	{
		if( $required && empty($value))
		{
			return false;
		}
		return true;
	}

	function formatdata( $value )
	{
		$finalvalue = '';
		if(!empty($value))
		{
			foreach($value as $listValue){
				$finalvalue	.= $listValue . ',';
			}
		}
		return $finalvalue;
	}
}