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
jimport('joomla.utilities.date');

class CFieldsTime
{
	/**
	 * Method to format the specified value for text type
	 **/

	function getFieldHTML( $field , $required )
	{
		$html	= '';

		$hour	= '';
		$minute	= 0;

		if(! empty($field->value))
		{
			$myTimeArr	= explode(' ', $field->value);

			if(is_array($myTimeArr) && count($myTimeArr) > 0)
			{
				$myTime	= explode(':', $myTimeArr[0]);

				$hour	= !empty($myTime[0]) ? $myTime[0] : '00';
				$minute	= !empty($myTime[1]) ? $myTime[1] : '00';
			}
		}

		$hours = array();
		for($i=0; $i<24; $i++)
		{
			$hours[] = ($i<10)? '0'.$i : $i;
		}

		$minutes = array();
		for($i=0; $i<60; $i++)
		{
			$minutes[] = ($i<10)? '0'.$i : $i;
		}

		CMFactory::load( 'helpers' , 'string' );
        $class	= ($field->required == 1) ? ' required' : '';
		$html .= '<div class="hasTip tipRight" style="display: inline-block;" title="' . $field->name . '::' . cEscape( $field->tips ) . '">';
		$html .= '<select name="field' . $field->id . '[]" >';
		for( $i = 0; $i < count($hours); $i++)
		{
			if($hours[$i]==$hour)
			{
				$html .= '<option value="' . $hours[$i] . '" selected="selected">' . $hours[$i] . '</option>';
			}
			else
			{
				$html .= '<option value="' . $hours[$i] . '">' . $hours[$i] . '</option>';
			}
		}
		$html .= '</select>:';
		$html .= '<select name="field' . $field->id . '[]" >';
		for( $i = 0; $i < count($minutes); $i++)
		{
			if($minutes[$i]==$minute)
			{
				$html .= '<option value="' . $minutes[$i] . '" selected="selected">' . $minutes[$i] . '</option>';
			}
			else
			{
				$html .= '<option value="' . $minutes[$i] . '">' . $minutes[$i] . '</option>';
			}
		}
		$html .= '</select> hh:min';
		
		$html .= '<span id="errfield'.$field->id.'msg" style="display:none;">&nbsp;</span>';
		$html .= '</div>';

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
		if(is_array($value))
		{
			if( empty( $value[0] ) || empty( $value[1] ) )
			{
				$finalvalue = '';
			}
			else
			{
				$hour 	= !empty($value[0]) ? $value[0]	: '00';
				$minute = !empty($value[1]) ? $value[1]	: '00';

				$finalvalue	= $hour . ':' . $minute . ':00';
			}
		}
		return $finalvalue;
	}
}