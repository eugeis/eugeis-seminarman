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

function SeminarmanBuildRoute(&$query)
{
    $segments = array();

    if (isset($query['view']))
    {
        if (empty($query['Itemid']))
        {
            $segments[] = $query['view'];
        }

        if ($query['view'] == 'tags')
        {
            $segments[] = $query['view'];
        }

        if ($query['view'] == 'favourites')
        {
            $segments[] = $query['view'];
        }

    	if ($query['view'] == 'paypal')
    	{
    		$segments[] = $query['view'];
    	}
    	
    	if ($query['view'] == 'templates')
    	{
    		$segments[] = $query['view'];
    	}
    	
    	if ($query['view'] == 'tutor')
    	{
    		$segments[] = $query['view'];
    	}
    	
    	if ($query['view'] == 'tutors')
    	{
    		$segments[] = $query['view'];
    	}
    	
    	if ($query['view'] == 'courses') {    		
    	    // if it is from module    		
            if (!empty($query['mod'])) {
            	$segments[] = $query['view'];
            }
    	}

        unset($query['view']);
    }
    ;

    if (isset($query['cid']))
    {
        $segments[] = $query['cid'];
        unset($query['cid']);
    }
    ;

    if (isset($query['id']))
    {
        $segments[] = $query['id'];
        unset($query['id']);
    }
    ;
    
    return $segments;
}

function SeminarmanParseRoute($segments)
{
    $vars = array();
    
    $count = count($segments);

    if ($segments[0] == 'tags')
    {
        $vars['view'] = 'tags';
        $vars['id'] = $segments[$count - 1];
        return $vars;
    }

	if ($segments[0] == 'paypal')
	{
		$vars['view'] = 'paypal';
		$vars['bookingid'] = $segments[$count - 1];
		return $vars;
	}

    if ($segments[0] == 'favourites')
    {
        $vars['view'] = 'favourites';
        return $vars;
    }
    
    if ($segments[0] == 'tutors')
    {
    	$vars['view'] = 'tutors';
    	return $vars;
    }

    if ($count == 1)
    {
        $vars['cid'] = $segments[$count - 1];
        $vars['view'] = 'category';
    }

    if ($count == 0)
    {
        $vars['view'] = 'seminarman';
    }

    if (($count == 2))
    {	
    	$vars['cid'] = $segments[$count - 2];
        $vars['view'] = 'courses';
        $vars['id'] = $segments[$count - 1];
    }
    
    if (($count == 2) && ($segments[0] == 'tutor'))
    {
    	// $vars['cid'] = $segments[$count - 2];
    	$vars['view'] = 'tutor';
    	$vars['id'] = $segments[$count - 1];
    }    
    
    if ($count == 3)
    {
    	$vars['cid'] = $segments[$count - 2];
    	$vars['view'] = 'templates';
    	$vars['id'] = $segments[$count - 1];    	
    }
    
    return $vars;
}

?>