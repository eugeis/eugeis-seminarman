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

function cValidateURL($url)
{
	//$regex = '/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i';
	$regex = '/^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,6}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&amp;?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/';

	if (preg_match($regex, JString::trim($url), $matches)) {
		return array($matches[1], $matches[2]);
	} else {
		return false;
	}
}

function cValidateEmails($email)
{
	CMFactory::load( 'helpers' , 'emails' );
	return isValidInetAddress($email);
}


function cGenerateUrlLink($urllink)
{
	$url		= JString::trim($urllink);
	$schemeRegex	= "/^(https?|ftp)\:\/\/?(.*)?/i";
 	if( preg_match($schemeRegex, $url))
	{
		$url	= '<a href="'.$url.'" target="_BLANK" rel="nofollow">'.$url.'</a>';
	}
	else
	{
		// if not found then just include an 'http://'
		$url	= '<a href="http://'.$url.'" target="_BLANK" rel="nofollow">'.$url.'</a>';
	}
	return $url;
}

function cGenerateEmailLink($emailadd)
{
	$email		= JString::trim($emailadd);
	$emailLink	= JHTML::_( 'email.cloak', $email );
	if(empty($emailLink)){
		$emailLink	= '<a href="mailto:'.$email.'">'.$email.'</a>';
	}//end if

	return $emailLink;

}

function cGenerateHyperLink($hyperlink)
{
	$link = JString::trim($hyperlink);

	if(cValidateEmails($link)){
		return cGenerateEmailLink($link);
	} else if(cValidateURL($link)) {
		return cGenerateUrlLink($link);
	} else {
		//do nothing, just return original string.
		return $link;
	}
}


/**
 * Automatically link urls in the provided message
 *
 * @param	$message	A string of message that may or may not contain a url.
 *
 * return	$message	A modified copy of the message with the proper hyperlinks.
 **/
function cGenerateURILinks( $message )
{
	$message = preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.\-]*(\?\S+)?)?)?)@', '<a href="$1">$1</a>', $message );

	return $message;
}