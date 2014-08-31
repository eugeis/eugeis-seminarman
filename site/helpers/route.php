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

jimport('joomla.application.component.helper');

class SeminarmanHelperRoute
{
    function getCourseRoute($id, $catid = 0)
    {
        $needles = array('course' => (int)$id, 'category' => (int)$catid);

        $link = 'index.php?option=com_seminarman&view=courses';

        if ($catid)
        {
            $link .= '&cid=' . $catid;
        }

        $link .= '&id=' . $id;

        if ($course = SeminarmanHelperRoute::_findCourse($needles))
        {
            $link .= '&Courseid=' . $course->id;
        }
        ;

        return $link;
    }

    function getCategoryRoute($catid)
    {
        $needles = array('category' => (int)$catid);

        $link = 'index.php?option=com_seminarman&view=category&cid=' . $catid;

        if ($course = SeminarmanHelperRoute::_findCourse($needles))
        {
            $link .= '&courseid=' . $course->id;
        }
        ;

        return $link;
    }

    function getTagRoute($id)
    {
        $needles = array('tags' => (int)$id);

        $link = 'index.php?option=com_seminarman&view=tags&id=' . $id;

        if ($course = SeminarmanHelperRoute::_findCourse($needles))
        {
            $link .= '&Courseid=' . $course->id;
        }
        ;

        return $link;
    }

    function _findCourse($needles)
    {
        $component = JComponentHelper::getComponent('com_seminarman');

        $menus = JApplication::getMenu('site', array());
        $courses = $menus->getItems('componentid', $component->id);
        $user = JFactory::getUser();
        $access = (int)$user->get('aid');

        $match = null;
        $count = 0;

        foreach ($needles as $needle => $id)
        {
            $count++;
//
//            foreach ($courses as $course)
//            {
//
//                if ((@$course->query['view'] == $needle) && (@$course->query['id'] == $id))
//                {
//                    $match = $course;
//                    break;
//                }
//
//                if ($count == 2)
//                {
//
//                    if ((@$course->query['view'] == 'category') && (@$course->query['cid'] == $id))
//                    {
//                        $match = $course;
//                        break;
//                    }
//                }
//
//            }

            if (isset($match))
            {
                break;
            }
        }

        if (empty($match))
        {
 //           foreach ($courses as $course)
//            {
//                if (@$course->published == 1 && @$course->access <= $access && @$course->query['view'] !=
//                    'favourites' && @$course->query['layout'] != 'form')
//                {
//                    $match = $course;
//                    break;
//                }
//            }
        }

        return $match;
    }
}

?>