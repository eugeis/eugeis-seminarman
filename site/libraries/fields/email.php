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

class CFieldsEmail
{
	/**
	 * Method to format the specified value for text type
	 **/
	function getFieldData( $value )
	{
		if( empty( $value ) )
			return $value;

		CMFactory::load( 'helpers' , 'linkgenerator' );

		return cGenerateEmailLink($value);
	}

	function getFieldHTML( $field , $required )
	{
		// If maximum is not set, we define it to a default
		$field->max	= empty( $field->max ) ? 200 : $field->max;

		$class	= ($field->required == 1) ? ' required' : '';
		CMFactory::load( 'helpers' , 'string' );
		ob_start();
?>
	<input title="<?php echo $field->name . '::'. cEscape( $field->tips );?>" type="text" value="<?php echo $field->value;?>" id="field<?php echo $field->id;?>" name="field<?php echo $field->id;?>" maxlength="<?php echo $field->max;?>" size="40" class="hasTip tipRight inputbox validate-customfields-email<?php echo $class;?>" />
	<span id="errfield<?php echo $field->id;?>msg" style="display:none;">&nbsp;</span>
<?php
		$html	= ob_get_contents();
		ob_end_clean();

		return $html;
	}

	function isValid( $value , $required )
	{
		CMFactory::load( 'helpers' , 'emails' );

		$isValid	= isValidInetAddress( $value );

		if( !empty($value) && $isValid )
		{
			return true;
		}
		else if( empty($value) && !$required )
		{
			return true;
		}

		return false;
	}
}