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

class CFieldsUrl
{
	/**
	 * Method to format the specified value for text type
	 **/
	function getFieldData( $value )
	{
		if( empty( $value ) )
			return $value;

		return '<a href="' . $value . '" target="_blank">' . $value . '</a>';
	}

	function getFieldHTML( $field , $required )
	{
		// If maximum is not set, we define it to a default
		$field->max	= empty( $field->max ) ? 200 : $field->max;

		$class	= ($field->required == 1) ? ' required' : '';
		$scheme	= '';
		$host	= '';

		if(! empty($field->value))
		{
			$url	= parse_url($field->value);
			$scheme	= $url[ 'scheme' ];
			$host	= $url[ 'host' ];
		}
		CMFactory::load( 'helpers' , 'string' );
		ob_start();
?>
	<select name="field<?php echo $field->id;?>[]">
		<option value="http://"<?php echo ($scheme == 'http') ? ' selected="selected"' : '';?>><?php echo JText::_('http://');?></option>
		<option value="https://"<?php echo ($scheme == 'https') ? ' selected="selected"' : '';?>><?php echo JText::_('https://');?></option>
	</select>
	<input title="<?php echo $field->name . '::'. cEscape( $field->tips );?>" type="text" value="<?php echo $host;?>" id="field<?php echo $field->id;?>" name="field<?php echo $field->id;?>[]" maxlength="<?php echo $field->max;?>" size="40" class="hasTip tipRight inputbox validate-customfields-url<?php echo $class;?>" />
	<span id="errfield<?php echo $field->id;?>msg" style="display:none;">&nbsp;</span>
<?php
		$html	= ob_get_contents();
		ob_end_clean();

		return $html;
	}

	function isValid( $value , $required )
	{
		CMFactory::load( 'helpers' , 'linkgenerator' );

		$isValid	= cValidateURL( $value );
		$url		= parse_url( $value );
		$host		= isset($url['host']) ? $url['host'] : '';

		if( !$isValid && $required )
			return false;
		else if( !empty($host) && !$isValid )
			return false;

		return true;
	}

	function formatdata( $value )
	{
		if( empty( $value[0] ) || empty( $value[1] ) )
		{
			$value = '';
		}
		else
		{

			$scheme	= $value[ 0 ];
			$url	= $value[ 1 ];
			$value	= $scheme . $url;
		}
		return $value;
	}
}