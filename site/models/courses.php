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

class SeminarmanModelCourses extends JModelLegacy
{
    var $_course = null;
    var $_tags = null;
    var $_id = null;
    var $_attendeedata = null;

    function __construct()
    {
        parent::__construct();

        $id = JRequest::getVar('id', 0, '', 'int');       
        $this->setId((int)$id);
    }


    function setId($id)
    {

        $this->_id = $id;
        $this->_course = null;
    }

    function get($property, $default = null)
    {
        if ($this->_loadCourse())
        {
            if (isset($this->_course->$property))
            {
                return $this->_course->$property;
            }
        }
        return $default;
    }

    function set($property, $value = null)
    {
        if ($this->_loadCourse())
        {
            $this->_course->$property = $value;
            return true;
        } else
        {
            return false;
        }
    }

    function &getCourse()
    {
        if ($this->_loadCourse())
        {
            $user = JFactory::getUser();

            if (!$this->_course->catpublished && $this->_course->catid)
            {
                JError::raiseError(404, JText::_("CATEGORY NOT PUBLISHED"));
            }

            if ($this->_course->created_by_alias)
            {
                $this->_course->creator = $this->_course->created_by_alias;
            } else
            {
                $query = 'SELECT name' . ' FROM #__users' . ' WHERE id = ' . (int)$this->_course->
                    created_by;
                $this->_db->setQuery($query);
                $this->_course->creator = $this->_db->loadResult();
            }

            if ($this->_course->created_by == $this->_course->modified_by)
            {
                $this->_course->modifier = $this->_course->creator;
            } else
            {
                $query = 'SELECT name' . ' FROM #__users' . ' WHERE id = ' . (int)$this->_course->
                    modified_by;
                $this->_db->setQuery($query);
                $this->_course->modifier = $this->_db->loadResult();
            }

            if ($this->_course->modified == $this->_db->getNulldate())
            {
                $this->_course->modified = null;
            }

            $session = JFactory::getSession();
            $hitcheck = false;
            if ($session->has('hit', 'seminarman'))
            {
                $hitcheck = $session->get('hit', 0, 'seminarman');
                $hitcheck = in_array($this->_course->id, $hitcheck);
            }
            if (!$hitcheck)
            {

                $this->hit();

                $stamp = array();
                $stamp[] = $this->_course->id;
                $session->set('hit', $stamp, 'seminarman');
            }

            $this->_course->text = $this->_course->introtext . chr(13) . chr(13) . $this->_course->
                fulltext;
        } else
        {
            $user = JFactory::getUser();
            $course = JTable::getInstance('seminarman_courses', '');
            if ($user->authorize('com_seminarman', 'state'))
            {
                $course->state = 1;
            }
            $course->id = 0;
            $course->author = null;
            $course->created_by = $user->get('id');
            $course->text = '';
            $course->title = null;
	    	$course->code = null;
            $course->meta_description = '';
            $course->meta_keywords = '';
            $this->_course = $course;
        }

        return $this->_course;
    }

    function _loadCourse()
    {
        $mainframe = JFactory::getApplication();
        $jnow = JFactory::getDate();
        // $now = $jnow->toMySQL();
        $now = $jnow->toSQL();
        $nullDate = $this->_db->getNullDate();

        if ($this->_id == '0')
        {
            return false;
        }

        if (empty($this->_course))
        {
            $query = 'SELECT i.*, (i.plus / (i.plus + i.minus) ) * 100 AS votes, c.access AS cataccess, c.id AS catid, c.published AS catpublished, c.title AS categorytitle,' .
                // ' u.name AS author, u.usertype,' .
                ' u.name AS author,' .
                ' CONCAT_WS(\' \', emp.salutation, emp.other_title, emp.firstname, emp.lastname) AS tutor,' .
                ' emp.published AS tutor_published,' .
                ' gr.title AS cgroup,' .
                ' lev.title AS level,' .
                ' CASE WHEN CHAR_LENGTH(i.alias) THEN CONCAT_WS(\':\', i.id, i.alias) ELSE i.id END as slug,' .
                ' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as categoryslug' .
                ' FROM #__seminarman_courses AS i' .
                ' LEFT JOIN #__seminarman_cats_course_relations AS rel ON rel.courseid = i.id' .
                ' LEFT JOIN #__seminarman_atgroup AS gr ON gr.id = i.id_group' .
                ' LEFT JOIN #__seminarman_experience_level AS lev ON lev.id = i.id_experience_level' .
                ' LEFT JOIN #__seminarman_tutor AS emp ON emp.id = i.tutor_id' .
                ' LEFT JOIN #__seminarman_categories AS c ON c.id = rel.catid' .
                ' LEFT JOIN #__users AS u ON u.id = i.created_by' . ' WHERE i.id = ' . $this->
                _id . ' AND i.state = 1' . ' AND ( i.publish_up = ' . $this->_db->Quote($nullDate) .
                ' OR i.publish_up <= ' . $this->_db->Quote($now) . ' )' .
                ' AND ( i.publish_down = ' . $this->_db->Quote($nullDate) .
                ' OR i.publish_down >= ' . $this->_db->Quote($now) . ' )';
            $this->_db->setQuery($query);
            $this->_course = $this->_db->loadObject();
            return (boolean)$this->_course;
        }
        return true;
    }

    function getTags()
    {
        $query = 'SELECT DISTINCT t.name,' .
            ' CASE WHEN CHAR_LENGTH(t.alias) THEN CONCAT_WS(\':\', t.id, t.alias) ELSE t.id END as slug' .
            ' FROM #__seminarman_tags AS t' .
            ' LEFT JOIN #__seminarman_tags_course_relations AS i ON i.tid = t.id' .
            ' WHERE i.courseid = ' . (int)$this->_id . ' AND t.published = 1' .
            ' ORDER BY t.name';

        $this->_db->setQuery($query);

        $this->_tags = $this->_db->loadObjectList();

        return $this->_tags;
    }

    function getCategories()
    {
        $query = 'SELECT DISTINCT c.id, c.title,' .
            ' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as slug' .
            ' FROM #__seminarman_categories AS c' .
            ' LEFT JOIN #__seminarman_cats_course_relations AS rel ON rel.catid = c.id' .
            ' WHERE rel.courseid = ' . $this->_id;

        $this->_db->setQuery($query);

        $this->_cats = $this->_db->loadObjectList();

        return $this->_cats;
    }

   
    function getAttendee()
    {
        $mainframe = JFactory::getApplication();
        $user = JFactory::getUser();
        if ($user->id != 0)
        {
        	$query = 'SELECT * FROM `#__seminarman_application`' .
        			 ' WHERE user_id = '. (int)$user->get('id').
        			 ' AND course_id = ' . $this->_id .
        			 ' AND course_id = ' . $this->_id .
        			 ' AND published <> -2 AND status < 3';
        	
        	$this->_db->setQuery($query);
        	$this->_attendeedata = $this->_db->loadObject();
        	
        	if (count($this->_attendeedata)>0)
            {
            	$this->_attendeedata->jusertype = null;
            	$this->_attendeedata->attendees = 1;
            }
            else
            {
            	$query = 'SELECT * FROM `#__users` WHERE id = '. (int)$user->get('id') .' AND block = 0';
            	$this->_db->setQuery($query);
            	$this->_attendeedata = $this->_db->loadObject();
            	
            	$this->_attendeedata->attendees = 1;
            	$namePieces = explode(" ", $this->_attendeedata->name);
            	$this->_attendeedata->first_name = $namePieces[0];
            	$this->_attendeedata->last_name = empty($namePieces[1]) ? '' : $namePieces[1];
            	$this->_attendeedata->email = $user->email;
            	$this->_attendeedata->jusertype = true;
            	$this->_attendeedata->user_id = $user->id;
            	
            	$query = 'SELECT * FROM `#__seminarman_fields_values_users_static` WHERE user_id = '.(int)$user->id;
            	$this->_db->setQuery($query);
            	$row = $this->_db->loadAssoc();
            	
            	$this->_attendeedata->salutation = 0;
            	$this->_attendeedata->salutationStr = $row['salutation'];
            	$this->_attendeedata->title = $row['title'];
            }
        }
        else
        {
            $this->_initattendeedata();
        }
        return $this->_attendeedata;
    }

	function _initattendeedata()
	{
		if (empty($this->_attendeedata))
		{
			$attendeedata = new stdClass();
			$attendeedata->id = null;
			$attendeedata->attendees = 1;
			$attendeedata->first_name = null;
			$attendeedata->last_name = null;
			$attendeedata->salutation = 0;
			$attendeedata->title = '';
			$attendeedata->email = null;
			$attendeedata->user_id = null;
            $attendeedata->jusertype = null;
			$this->_attendeedata = $attendeedata;
			return (boolean)$this->_attendeedata;
		}
		return true;
	}

    function hit()
    {
        if ($this->_id)
        {
            $course = JTable::getInstance('seminarman_courses', '');
            $course->hit($this->_id);
            return true;
        }
        return false;
    }

    function getAlltags()
    {
        $query = 'SELECT * FROM #__seminarman_tags ORDER BY name';
        $this->_db->setQuery($query);
        $tags = $this->_db->loadObjectlist();
        return $tags;
    }

    function getUsedtags()
    {
        $query = 'SELECT tid FROM #__seminarman_tags_course_relations WHERE courseid = ' . (int)
            $this->_id;
        $this->_db->setQuery($query);
        // $used = $this->_db->loadResultArray();
        $used = $this->_db->loadColumn();
        return $used;
    }

    function isCheckedOut($uid = 0)
    {
        if ($this->_loadCourse())
        {
            if ($uid)
            {
                return ($this->_course->checked_out && $this->_course->checked_out != $uid);
            } else
            {
                return $this->_course->checked_out;
            }
        } elseif ($this->_id < 1)
        {
            return false;
        } else
        {
            JError::raiseWarning(0, 'Unable to Load Data');
            return false;
        }
    }

    function checkin()
    {
        if ($this->_id)
        {
            $course = JTable::getInstance('seminarman_courses', '');
            return $course->checkin($this->_id);
        }
        return false;
    }

    function checkout($uid = null)
    {
        if ($this->_id)
        {

            if (is_null($uid))
            {
                $user = JFactory::getUser();
                $uid = $user->get('id');
            }

            $course = JTable::getInstance('seminarman_courses', '');
            return $course->checkout($uid, $this->_id);
        }
        return false;
    }

    function store($data)
    {
        $course = JTable::getInstance('seminarman_courses', '');
        $user = JFactory::getUser();

        if (!$course->bind($data))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        $course->id = (int)$course->id;

        $isNew = ($course->id < 1);

        if ($isNew)
        {
            $course->created = gmdate('Y-m-d H:i:s');
            $course->created_by = $user->get('id');
        } else
        {
            $course->modified = gmdate('Y-m-d H:i:s');
            $course->modified_by = $user->get('id');

            $query = 'SELECT hits, minus, plus, created, created_by, version' .
                ' FROM #__seminarman_courses' . ' WHERE id = ' . (int)$course->id;

            $this->_db->setQuery($query);
            $result = $this->_db->loadObjectList();

            $course->plus = $result->plus;
            $course->minus = $result->minus;
            $course->hits = $result->hits;

            $course->created = $result->created;
            $course->created_by = $result->created_by;

            $course->version = $result->version;
            $course->version++;

            if (!$user->authorize('com_seminarman', 'state'))
            {
                $course->state = $result->state;
            }
        }

        if (!$user->authorize('com_seminarman', 'state'))
        {
            if ($isNew)
            {

                $course->state = -2;
            } else
            {
                $query = 'SELECT state' . ' FROM #__seminarman_courses' . ' WHERE id = ' . (int)$course->
                    id;

                $this->_db->setQuery($query);
                $result = $this->_db->loadResult();

                $course->state = $result;
            }
        }

        seminarman_html::saveContentPrep($course);

        if (!$course->check())
        {
            $this->setError($course->getError());
            return false;
        }

        if (!$course->store())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        if ($isNew)
        {
            $this->_id = $course->_db->insertId();
        }

        $course->ordering = $course->getNextOrder();

        $tags = JRequest::getVar('tag', array(), 'post', 'array');

        $query = 'DELETE FROM #__seminarman_tags_course_relations WHERE courseid = ' . $course->
            id;
        $this->_db->setQuery($query);
        $this->_db->query();

        foreach ($tags as $tag)
        {
            $query = 'INSERT INTO #__seminarman_tags_course_relations (`tid`, `courseid`) VALUES(' .
                $tag . ',' . $course->id . ')';
            $this->_db->setQuery($query);
            $this->_db->query();
        }

        $cats = JRequest::getVar('cid', array(), 'post', 'array');

        $query = 'DELETE FROM #__seminarman_cats_course_relations WHERE courseid = ' . $course->
            id;
        $this->_db->setQuery($query);
        $this->_db->query();

        foreach ($cats as $cat)
        {
            $query = 'INSERT INTO #__seminarman_cats_course_relations (`catid`, `courseid`) VALUES(' .
                $cat . ',' . $course->id . ')';
            $this->_db->setQuery($query);
            $this->_db->query();
        }

        $this->_course = &$course;

        return $this->_course->id;
    }

    function storevote($id, $vote)
    {
        if ($vote == 1)
        {
            $target = 'plus';
        } elseif ($vote == 0)
        {
            $target = 'minus';
        } else
        {
            return false;
        }

        $query = 'UPDATE #__seminarman_courses' . ' SET ' . $target . ' = ( ' . $target .
            ' + 1 )' . ' WHERE id = ' . (int)$id;
        $this->_db->setQuery($query);
        $this->_db->query();

        return true;
    }

    function getCatsselected()
    {
        $query = 'SELECT DISTINCT catid FROM #__seminarman_cats_course_relations WHERE courseid = ' . (int)
            $this->_id;
        $this->_db->setQuery($query);
        // $used = $this->_db->loadResultArray();
        $used = $this->_db->loadColumn();
        return $used;
    }

    function storetag($data)
    {
        $row = $this->getTable('seminarman_tags', '');

        if (!$row->bind($data))
        {
            JError::raiseError(500, $this->_db->getErrorMsg());
            return false;
        }

        if (!$row->check())
        {
            $this->setError($row->getError());
            return false;
        }

        if (!$row->store())
        {
            JError::raiseError(500, $this->_db->getErrorMsg());
            return false;
        }

        return $row->id;
    }

    function addtag($name)
    {
        $obj = new stdClass();
        $obj->name = $name;
        $obj->published = 1;

        $this->storetag($obj);


        return true;
    }

    function getFavourites()
    {
        $query = 'SELECT COUNT(id) AS favs FROM #__seminarman_favourites WHERE courseid = ' . (int)
            $this->_id;
        $this->_db->setQuery($query);
        $favs = $this->_db->loadResult();
        return $favs;
    }

    function getFavoured()
    {
        $user = JFactory::getUser();

        $query = 'SELECT COUNT(id) AS fav FROM #__seminarman_favourites WHERE courseid = ' . (int)
            $this->_id . ' AND userid= ' . (int)$user->id;
        $this->_db->setQuery($query);
        $fav = $this->_db->loadResult();
        return $fav;
    }

    function getFiles()
    {
        $query = 'SELECT DISTINCT rel.fileid, f.filename, f.altname' .
            ' FROM #__seminarman_files AS f' .
            ' LEFT JOIN #__seminarman_files_course_relations AS rel ON rel.fileid = f.id' .
            ' WHERE rel.courseid = ' . (int)$this->_id;
        $this->_db->setQuery($query);
        $files = $this->_db->loadObjectList();

        $files = seminarman_images::BuildIcons($files);

        return $files;
    }

    function removefav()
    {
        $user = JFactory::getUser();

        $query = 'DELETE FROM #__seminarman_favourites WHERE courseid = ' . (int)$this->_id .
            ' AND userid = ' . (int)$user->id;
        $this->_db->setQuery($query);
        $remfav = $this->_db->query();
        return $remfav;
    }

    function addfav()
    {
        $user = JFactory::getUser();

        $obj = new stdClass();
        $obj->courseid = $this->_id;
        $obj->userid = $user->id;

        $addfav = $this->_db->insertObject('#__seminarman_favourites', $obj);
        return $addfav;
    }

    function setcoursestate($id, $state = 1)
    {
        $user = JFactory::getUser();

        if ($id)
        {

            $query = 'UPDATE #__seminarman_courses' . ' SET state = ' . (int)$state .
                ' WHERE id = ' . (int)$id . ' AND ( checked_out = 0 OR ( checked_out = ' . (int)
                $user->get('id') . ' ) )';
            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return true;
    }

	/**
	 * Returns an array of custom editfields which are created from the back end.
	 *
	 * @access	public
	 * @param	string 	User's id.
	 * @returns array  An objects of custom fields.
	 */
	function getEditableCustomfields($applicationId	= null)
	{
		$db   = $this->getDBO();
		$data = array();
		$user = JFactory::getUser();
		
		$data['id']		= $user->id;
		$data['name']	= $user->name;
		$data['email']	= $user->email;

		if (!$user->guest)
		{
			$q = 'SELECT COUNT(*) FROM `#__seminarman_fields_values`'.
			     ' WHERE applicationid='.(int)$applicationId.' AND user_id='.(int)$user->id;
			$db->setQuery($q);
			if ($db->loadResult() == 0)
			{
				// Es gibt noch keine Anmeldung auf den Kurs. Werte für Felder aus #__seminarman_fields_values_users holen
				// (kann aber auch leer sein)
				$q = 'SELECT f.*, v.value FROM `#__seminarman_fields` AS f'.
				     ' LEFT JOIN `#__seminarman_fields_values_users` AS v'.
				     ' ON f.fieldcode = v.fieldcode AND v.user_id = '.(int)$user->id.
				     ' WHERE f.published=1 AND f.visible=1 ORDER BY f.ordering';
				$db->setQuery($q);
			}
			else 
			{
				$q = 'SELECT f.*, v.value FROM `#__seminarman_fields` AS f'.
				     ' LEFT JOIN `#__seminarman_fields_values` AS v'.
				     ' ON f.id = v.field_id AND v.applicationid = '.(int)$applicationId.
				     ' WHERE f.published=1 AND f.visible=1 ORDER BY f.ordering';
				$db->setQuery($q);
			}
		}
		else 
		{
			$q = 'SELECT f.*, v.value FROM `#__seminarman_fields` AS f'.
			     ' LEFT JOIN `#__seminarman_fields_values` AS v'.
			     ' ON f.id = v.field_id AND v.applicationid = 0'.
			     ' WHERE f.published=1 AND f.visible=1 ORDER BY f.ordering';
			$db->setQuery($q);
		}

		$result	= $db->loadAssocList();

		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}

		$data['fields']	= array();
		for($i = 0; $i < count($result); $i++)
		{
			// We know that the groups will definitely be correct in ordering.
			if($result[$i]['type'] == 'group' && $result[$i]['purpose'] == 0)
			{
				$add = True;	
				$group	= $result[$i]['name'];

				// Group them up
				if(!isset($data['fields'][$group]))
				{
					// Initialize the groups.
					$data['fields'][$group]	= array();
				}
			}
			if($result[$i]['type'] == 'group' && $result[$i]['purpose'] != 0)
				$add = False;

			// Re-arrange options to be an array by splitting them into an array
			if(isset($result[$i]['options']) && $result[$i]['options'] != '')
			{
				$options	= $result[$i]['options'];
				$options	= explode("\n", $options);

				$countOfOptions = count($options);
				for($x = 0; $x < $countOfOptions; $x++){
					$options[$x] = trim($options[$x]);
				}

				$result[$i]['options']	= $options;

			}


			if($result[$i]['type'] != 'group' && isset($add)){
				if($add)
					$data['fields'][$group][]	= $result[$i];
			}
		}
		return $data;
	}

}


?>