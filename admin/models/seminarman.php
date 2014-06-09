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

jimport('joomla.application.component.model');

require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_seminarman' . DS .
		'helpers' . DS . 'seminarman.php');

class SeminarmanModelSeminarman extends JModelLegacy
{
    function __construct()
    {
        parent::__construct();
    }

    function getLatestJobs()
    {
        $query = 'SELECT id, title' . ' FROM #__seminarman_courses' . ' WHERE state = 1';

        if(!(JHTMLSeminarman::UserIsCourseManager())){
                $query .= ' and tutor_id = ' . JHTMLSeminarman::getUserTutorID();
        }

        $query .= ' ORDER BY created DESC' . ' LIMIT 5';

        $this->_db->SetQuery($query);
        $genstats = $this->_db->loadObjectList();

        return $genstats;
    }

    function getLatestApplications()
    {
        $query = 'SELECT a.id, a.first_name, a.last_name, i.title, a.date' .
            ' FROM #__seminarman_application AS a' .
            ' LEFT JOIN #__seminarman_courses AS i ON i.id = a.course_id ' .
            ' WHERE a.published = 1' ;

        if(!(JHTMLSeminarman::UserIsCourseManager())){
                $query .= ' and i.tutor_id = ' . JHTMLSeminarman::getUserTutorID();
        }

        $query .= ' ORDER BY a.date DESC' . ' LIMIT 5';

        $this->_db->SetQuery($query);
        $genstats = $this->_db->loadObjectList();

        return $genstats;
    }

}

?>
