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

defined('_JEXEC') or die('Restricted access');

function cIsPlural($num){
	return !cIsSingular($num);
}

function cIsSingular($num){
	$config = CMFactory::getConfig();
	$singularnumbers = $config->get('singularnumber');
	$singularnumbers = explode(',', $singularnumbers);
	
	return in_array($num, $singularnumbers);
}

function cEscape($var, $function='htmlspecialchars')
{
	if (in_array($function, array('htmlspecialchars', 'htmlentities')))
	{
		return call_user_func($function, $var, ENT_COMPAT, 'UTF-8');
	}
	return call_user_func($function, $var);
}

function cCleanString($string)
{
	// Replace other special chars  
	$specialCharacters = array(
		'§' => '',
		'\\' => '',
		'/' => '',
		'’' => "'",
		'"' => '',
	);
	foreach( $specialCharacters as $character => $replacement )
	{
		$string	= JString::str_ireplace( $character , $replacement , $string );
	}
	
	return $string;
}

function cReplaceThumbnails( $data )
{
	// Replace matches for {user:thumbnail:ID} so that this can be fixed even if the caching is enabled.
	$html	= preg_replace_callback('/\{user:thumbnail:(.*)\}/', '_replaceThumbnail' , $data );
	
	return $html;
}

function _replaceThumbnail(  $matches )
{
	static	$data = array();
	
	if( !isset($data[$matches[1]]) )
	{
		$user	= CMFactory::getUser( $matches[1] );
		$data[ $matches[1] ]	= $user->getThumbAvatar();
	}
	
	return $data[ $matches[1] ];
}	

function cTrimString( $value , $length )
{
	if( JString::strlen($value) > $length )
	{
		return JString::substr( $value , 0 , $length ) . '<span>...</span>';
	}
	return $value;
}