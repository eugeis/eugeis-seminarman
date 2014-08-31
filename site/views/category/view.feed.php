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

jimport('joomla.application.component.view');

class SeminarmanViewCategory extends JViewLegacy
{
    function display()
    {
        $mainframe = JFactory::getApplication();
        
        $Itemid = JRequest::getInt('Itemid');
        
        $doc = JFactory::getDocument();
        $params = $mainframe->getParams();
        $doc->link = JRoute::_('index.php?option=com_seminarman&view=category&cid=' .
            JRequest::getVar('cid', null, '', 'int') . '&Itemid=' . $Itemid);

        JRequest::setVar('limit', $mainframe->getCfg('feed_limit'));
        $category = $this->get('Category');
        $rows = $this->get('Data');

        foreach ($rows as $row)
        {

            $title = $this->escape($row->title);
            $title = html_entity_decode($title);


            $link = JRoute::_('index.php?option=com_seminarman&view=courses&cid=' . $category->
                slug . '&id=' . $row->slug . '&Itemid=' . $Itemid);

            $description = ($params->get('feed_summary', 0) ? $row->introtext . $row->
                fulltext : $row->introtext);

            @$date = ($row->created ? date('r', strtotime($row->created)) : '');

            $course = new JFeedItem();
            $course->title = $title;
            $course->link = $link;
            $course->description = $description;
            $course->date = $date;

            $course->category = $this->escape($category->title);

            $doc->addItem($course);
        }
    }
}

?>