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

class CFieldsRadio
{
	function getFieldHTML( $field , $required )
	{
		$html				= '';
		$selectedElement	= 0;
		$class				= ($field->required == 1) ? ' required validate-custom-radio' : '';
		$elementSelected	= 0;
		$elementCnt	        = 0;

		for( $i = 0; $i < count( $field->options ); $i++ )
		{
		    $option		= $field->options[ $i ];
			$selected	= ( $option == $field->value ) ? ' checked="checked"' : '';

			if( empty( $selected ) )
			{
				$elementSelected++;
			}
			$elementCnt++;
		}
        
		for( $i = 0; $i < count( $field->options ); $i++ )
		{
		    $option		= $field->options[ $i ];
            if($isSomethingSelected	= ( $option == $field->value )){
                $isSomethingSelected	= ' checked="checked"';
            }
		}        


		$cnt = 0;
		CMFactory::load( 'helpers' , 'string' );
		$html	.= '<div class="hasTip tipRight" style="display: inline-block;" title="' . $field->name . '::' . cEscape( $field->tips ). '">';
		for( $i = 0; $i < count( $field->options ); $i++ )
		{
		    $option		= $field->options[ $i ];
   

// 		    if(($field->required == 1) && ($elementSelected == $elementCnt) && ($cnt == 0)){
// 		       $selected	= ' checked="checked"'; //default checked for the 1st item.
// 		    } else {
// 		       $selected	= ( $option == $field->value ) ? ' checked="checked"' : '';
// 		    }
// 		    $cnt++;

			$selected	= ( $option == $field->value ) ? ' checked="checked"' : '';
            if(($isSomethingSelected == '')&&($i == 0)){
            
            $selected	= ' checked="checked"';
        }

			$html 	.= '<label class="lblradio-block">';
			$html	.= '<input type="radio" name="field' . $field->id . '" value="' . $option . '"' . $selected . '  class="radio '.$class.'" style="margin: 0 5px 0 0;" />';
			$html	.= JText::_( $option ) . '</label>';
		}
		$html   .= '<span id="errfield'.$field->id.'msg" style="display: none;">&nbsp;</span>';
		$html	.= '</div>';

		return $html;
	}

	function isValid( $value , $required )
	{
		
		return true;
	}
}