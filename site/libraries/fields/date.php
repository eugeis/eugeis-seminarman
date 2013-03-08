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

class CFieldsDate
{
	/**
	 * Method to format the specified value for text type
	 **/
	function getFieldData( $value )
	{
		if( empty( $value ) )
			return $value;

		if(! class_exists('CMFactory'))
		{
			require_once( JPATH_ROOT . DS . 'components' . DS . 'com_seminarman' . DS . 'libraries' . DS . 'core.php' );
		}
		require_once( JPATH_ROOT . DS . 'components' . DS . 'com_seminarman' . DS . 'models' . DS . 'customfields.php' );

		$model	=& CMFactory::getModel( 'customfields' );
		$myDate = $model->formatDate($value);

		return $myDate;
	}

	function getFieldHTML( $field , $required )
	{
		$html	= '';

		$day	= '';
		$month	= 0;
		$year	= '';

		if(! empty($field->value))
		{
			$myDateArr	= explode(' ', $field->value);

			if(is_array($myDateArr) && count($myDateArr) > 0)
			{
				$myDate	= explode('-', $myDateArr[0]);

				$day	= !empty($myDate[2]) ? $myDate[2] : '';
				$month	= !empty($myDate[1]) ? $myDate[1] : 0;
				$year	= !empty($myDate[0]) ? $myDate[0] : '';
			}
		}

		$months	= Array(
						JText::_('January'),
						JText::_('February'),
						JText::_('March'),
						JText::_('April'),
						JText::_('May'),
						JText::_('June'),
						JText::_('July'),
						JText::_('August'),
						JText::_('September'),
						JText::_('October'),
						JText::_('November'),
						JText::_('December')
						);

        $class	= ($field->required == 1) ? ' required' : '';
        CMFactory::load( 'helpers' , 'string' );
		$html .= '<div class="hasTip tipRight" style="display: inline-block;" title="' . $field->name . '::' . cEscape( $field->tips ). '">';
		$html .= '<input type="textbox" size="3" maxlength="2" name="field' . $field->id . '[]" value="' . $day . '" class="inputbox validate-custom-date' . $class . '" /> ';
		$html .= '&nbsp;/&nbsp;<select name="field' . $field->id . '[]" class="select validate-custom-date' . $class . '">';

		$defaultSelected	= '';

		//@rule: If there is no value, we need to default to a default value
		if( $month == 0 )
		{
			$defaultSelected	.= ' selected="selected"';
		}
		$html	.= '<option value=""' . $defaultSelected . '>- ' . JText::_('COM_SEMINARMAN_CHOOSE_PLEASE') . '- </option>';

		for( $i = 0; $i < count($months); $i++)
		{
			if(($i + 1)== $month)
			{
				$html .= '<option value="' . ($i + 1) . '" selected="selected">' . $months[$i] . '</option>';
			}
			else
			{
				$html .= '<option value="' . ($i + 1) . '">' . $months[$i] . '</option>';
			}
		}
		$html .= '</select>&nbsp;/&nbsp;';
		$html .= '<input type="textbox" size="5" maxlength="4" name="field' . $field->id . '[]" value="' . $year . '" class="inputbox validate-custom-date' . $class . '" /> ';
		$html .= JText::_('COM_SEMINARMAN_DAY') . ' / ' . JText::_('COM_SEMINARMAN_MONTH') . ' / ' . JText::_('COM_SEMINARMAN_YEAR');
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
			if( empty( $value[0] ) || empty( $value[1] ) || empty( $value[2] ) )
			{
				$finalvalue = '';
			}
			else
			{
				$day	= intval($value[0]);
				$month	= intval($value[1]);
				$year	= intval($value[2]);

				$day 	= !empty($day) 		? $day 		: 1;
				$month 	= !empty($month) 	? $month 	: 1;
				$year 	= !empty($year) 	? $year 	: 1970;

				// $finalvalue	= $year . '-' . $month . '-' . $day . ' 23:59:59';
        $finalvalue	= $day . '.' . $month . '.' . $year;
			}
		}

		return $finalvalue;
	}
}