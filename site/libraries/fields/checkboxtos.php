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

class CFieldsCheckboxTos
{
	function getFieldHTML( $field , $required )
	{
		CMFactory::load( 'helpers' , 'string' );
		if ($field->required == 1)
		{
			$class = 'required validate-custom-checkbox';
			$asterisk = '*';
		}
		else
		{
			$class = '';
			$asterisk = '';
		}
		
		
		$text = $field->options[0];
		
		return '<input'.
		       ' type="checkbox"'.
		       ' id="cm_tos"'.
		       ' name="field'. $field->id . '"'.
		       ' title="' . $field->name . '::' . cEscape( $field->tips ). '"'.
		       ' value="1"'. 
		       ' class="checkbox hasTip '. $class .'"'.
		       ' style="margin-top: 1em; float: left;" />'.
		       '<p style="margin-top: 0.9em; margin-bottom: 1em; margin-left: 1.5em;"> '. $text . ' ' . $asterisk . '</p>'.
		       '<span id="errfield'. $field->id .'msg" style="display:none;">&nbsp;</span>';
	}

	function isValid( $value , $required )
	{
		if( $required && empty($value))
		{
			return false;
		}
		return true;
	}

}
