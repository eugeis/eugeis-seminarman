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

class CFieldsSelect
{
	function getFieldHTML( $field , $required, $isDropDown = true)
	{
		$class		= ($field->required == 1) ? ' required' : '';
		$optionSize	= 1; // the default 'select below'

		if( !empty( $field->options ) )
		{
			$optionSize	+= count($field->options);
		}

		$dropDown	= ($isDropDown) ? '' : ' size="'.$optionSize.'"';
		CMFactory::load( 'helpers' , 'string' );
		$html		= '<select id="field'.$field->id.'" name="field' . $field->id . '"' . $dropDown . ' class="hasTip tipRight select'.$class.'" title="' . $field->name . '::' . cEscape( $field->tips ). '">';

		$defaultSelected	= '';

		//@rule: If there is no value, we need to default to a default value
		if(empty( $field->value ) )
		{
			$defaultSelected	.= ' selected="selected"';
		}


		if($isDropDown)
		{
			$html	.= '<option value="" ' . $defaultSelected . '>- ' . JText::_('COM_SEMINARMAN_CHOOSE_PLEASE') . ' -</option>';
		}

		if( !empty( $field->options ) )
		{
			$selectedElement	= 0;

			foreach( $field->options as $option )
			{
				//$field->value="yes";
				//$option = "yes";
				$selected	= ( $option == $field->value ) ? ' selected="selected"' : '';

				if( !empty( $selected ) )
				{
					$selectedElement++;
				}

				$html	.= '<option value="' . $option . '"' . $selected . '>' . JText::_( $option ) . '</option>';
			}

			if($selectedElement == 0)
			{
				//if nothing is selected, we default the 1st option to be selected.
				$eleName	= 'field'.$field->id;
				$js			=<<< HTML
					   <script type='text/javascript'>
						   var slt = document.getElementById('$eleName');
						   if(slt != null)
						   {
						       slt.options[0].selected = true;
						   }
					   </script>
HTML;
			}
		}
		$html	.= '</select>';
		$html   .= '<span id="errfield'.$field->id.'msg" style="display:none;">&nbsp;</span>';

		return $html;
	}
}