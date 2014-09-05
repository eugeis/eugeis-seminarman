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

require_once JPATH_ROOT.'/components/com_seminarman/helpers/application.php';

class SeminarmanModelCourse extends JModelLegacy
{
    var $_course = null;
	var $_tutorUser = null;

    function __construct()
    {
        parent::__construct();

        $cid = JRequest::getVar('cid', array(0), '', 'array');
        JArrayHelper::toInteger($cid, array(0));
        $this->setId($cid[0]);
    }

    function setId($id)
    {

        $this->_id = $id;
        $this->_course = null;
		$this->_tutorUser = null;
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

    function &getCourse()
    {
        if ($this->_loadCourse())
        {
            if (JString::strlen($this->_course->fulltext) > 1)
            {
                $this->_course->text = $this->_course->introtext . "<hr id=\"system-readmore\" />" .
                    $this->_course->fulltext;
            } else
            {
                $this->_course->text = $this->_course->introtext;
            }

            $query = 'SELECT name' . ' FROM #__users' . ' WHERE id = ' . (int)$this->_course->
                created_by;
            $this->_db->setQuery($query);
            $this->_course->creator = $this->_db->loadResult();

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

        } else
            $this->_initCourse();
        
        if ($this->getState('task') == 'createFromTmpl')
        {
        	$this->_initFromTmpl(JRequest::getVar('templateId'));
        }
        
        return $this->_course;
    }


    function _loadCourse()
    {

        if (empty($this->_course))
        {
			$query = 'SELECT c.*, t.user_id as TUTOR_USER_ID, t.title AS `TUTOR`, t.firstname AS `TUTOR_FIRSTNAME`, t.lastname AS `TUTOR_LASTNAME` ' .
				' FROM #__seminarman_courses AS c' .
				' INNER JOIN #__seminarman_tutor AS t ON t.id = c.tutor_id' .
					' WHERE c.id ='. $this->_id;

            $this->_db->setQuery($query);
            $this->_course = $this->_db->loadObject();

            return (boolean)$this->_course;
        }
        return true;
    }

	function getTutorAsUser()
	{
		if (empty($this->_tutorUser))
		{
			$query = 'SELECT u.* FROM #__users AS u' .
					' INNER JOIN #__seminarman_tutor AS t ON t.user_id = u.id' .
					' INNER JOIN #__seminarman_courses AS c ON t.id = c.tutor_id' .
					' WHERE c.id ='. $this->_id;

			$this->_db->setQuery($query);
			$this->_tutorUser = $this->_db->loadObject();
		}
		return $this->_tutorUser;
	}

    function _initCourse()
    {

        if (empty($this->_course))
        {
        	$user = JFactory::getUser();
        	$params = JComponentHelper::getParams( 'com_seminarman' );
            $createdate = JFactory::getDate();
            $nullDate = $this->_db->getNullDate();

            $course = new stdClass();
            $course->id = 0;
            $course->cid[] = 0;
            $course->reference_number = null;
            $course->code = null;
            $course->title = null;
            $course->alias = null;
            $course->text = null;
            $course->plus = 0;
            $course->minus = 0;
            $course->hits = 0;
            $course->version = 0;
            $course->meta_description = null;
            $course->meta_keywords = null;
            $course->created = $nullDate;
            $course->created_by = null;
            $course->created_by_alias = $user->get('name');
            $course->modified = $nullDate;
            $course->modified_by = null;
            $course->attribs = null;
            $course->metadata = null;
            $course->state = 0;
            $course->job_title = null;
            $course->tutor_id = null;
            $course->id_pos_type = null;
            $course->price_type = null;
            $course->vat = $params->get('vat');
            $course->id_experience_level = null;
            $course->job_experience = null;
            $course->id_group = null;
            $course->url = null;
            $course->price = 0;
            $course->currency_price = null;
            $course->capacity = null;
            $course->location = null;
            $course->expire_date = null;
            $createdate = JFactory::getDate();
            $course->publish_up = $createdate->toUnix();
            $course->publish_down = '';
            $course->expire_down = null;
        	$course->capacity = null;
            $this->_course = $course;
            $course->email_template = 0;
            $course->invoice_template = 0;
            $course->attlst_template = 0;
            $course->start_date = '0000-00-00';
            $course->finish_date = '0000-00-00';
            $course->start_time = null;
            $course->finish_time = null;
            $course->templateId = 0;
            $course->new = 1;
            $course->canceled = 0;
            $course->certificate_text = null;
            $course->price2 = null;
            $course->price3 = null;
            $course->price4 = null;
            $course->price5 = null;
            $course->min_attend = 0;
            $course->theme_points = 0;
            return (boolean)$this->_course;
        }
        return true;
    }
    
    function _initFromTmpl($templateId)
    {
    	$this->_initCourse();
    	$user = JFactory::getUser();
    	
    	// get data from template
    	$query = 'SELECT * FROM #__seminarman_templates WHERE id=' . (int) $templateId;
    	$this->_db->setQuery($query);
    	
    	if (!($tpl = $this->_db->loadObject()))
    		return $this->_course;

    	$this->_course->title = $tpl->title;
    	$this->_course->code = $tpl->code;
    	$this->_course->price = $tpl->price;
    	$this->_course->vat = $tpl->vat;
    	$this->_course->text = $tpl->introtext;
    	$this->_course->price_type = $tpl->price_type;
    	$this->_course->location = $tpl->location;
    	$this->_course->url = $tpl->url;
    	$this->_course->email_template = $tpl->email_template;
    	$this->_course->invoice_template = $tpl->invoice_template;
    	$this->_course->attlst_template = $tpl->attlst_template;
    	$this->_course->start_date = $tpl->start_date;
    	$this->_course->finish_date = $tpl->finish_date;
    	$this->_course->id_group = $tpl->id_group;
    	$this->_course->id_experience_level = $tpl->id_experience_level;
    	$this->_course->job_experience = $tpl->job_experience;
    	$this->_course->capacity = $tpl->capacity;
    	$this->_course->attribs = $tpl->attribs;
    	$this->_course->certificate_text = $tpl->certificate_text;
    	
    	$this->_course->price2 = $tpl->price2;
    	$this->_course->price3 = $tpl->price3;
    	$this->_course->price4 = $tpl->price4;
    	$this->_course->price5 = $tpl->price5;
    	$this->_course->min_attend = $tpl->min_attend;
    	$this->_course->theme_points = $tpl->theme_points;
    	 

    	return (boolean)$this->_course;
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
            JError::raiseWarning(0, 'UNABLE LOAD DATA');
            return false;
        }
    }

    function store($data)
    {  
    	
    	$data['price'] = str_replace(array(',',' '),array('.',''),$data['price']);
    	$data['price2'] = str_replace(array(',',' '),array('.',''),$data['price2']);
        $data['price3'] = str_replace(array(',',' '),array('.',''),$data['price3']);
        $data['price4'] = str_replace(array(',',' '),array('.',''),$data['price4']);
        $data['price5'] = str_replace(array(',',' '),array('.',''),$data['price5']);
        
        $data['start_time'] = str_replace(' ', '', $data['start_time']);
        $data['finish_time'] = str_replace(' ', '', $data['finish_time']);
        
        // if ($data['price2']=='') $data['price2']=NULL;
        // if ($data['price3']=='') $data['price3']=NULL;
        // if ($data['price4']=='') $data['price4']=NULL;
        // if ($data['price5']=='') $data['price5']=NULL;
        
    	require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_seminarman' . DS . 'helpers' . DS . 'seminarman.php');

        JRequest::checkToken() or jexit('Invalid Token');

        $course = $this->getTable('seminarman_courses', '');
        $user = JFactory::getUser();

        $details = JRequest::getVar('details', array(), 'post', 'array');
        $tags = JRequest::getVar('tag', array(), 'post', 'array');
        $cats = JRequest::getVar('catid', array(), 'post', 'array');
        $files = JRequest::getVar('fid', array(), 'post', 'array');
        $files = array_filter($files);

        if (!is_array($cats) || count($cats) < 1)
        {
            $this->setError('SELECT CATEGORY');
            return false;
        }

        if (!$course->bind($data))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        $course->bind($details);

        $course->id = (int)$course->id;
        $this->_id = $course->id;

        $nullDate = $this->_db->getNullDate();

        if ($course->id)
        {
            $course->modified = gmdate('Y-m-d H:i:s');
            $course->modified_by = $user->get('id');
            if (!$course->code) { $course->code = $course->id; }
        } else
        {
            $course->modified = $nullDate;
            $course->modified_by = '';

            $course->created = gmdate('Y-m-d H:i:s');
            $course->created_by = $user->get('id');
            $course->new = 0;
        }
        
        $course->start_date = JHTMLSeminarman::localDate2DbDate($course->start_date);
        $course->finish_date = JHTMLSeminarman::localDate2DbDate($course->finish_date);
        $course->publish_up = JHTMLSeminarman::localDate2DbDate($course->publish_up);
        $course->publish_down = JHTMLSeminarman::localDate2DbDate($course->publish_down);
        
        $course->vat = str_replace(",", ".", $course->vat);
        
        $course->state = JRequest::getVar('state', 0, '', 'int');
        $params = JRequest::getVar('params', null, 'post', 'array');

        if (is_array($params))
        {
            $txt = array();
            foreach ($params as $k => $v)
            {
                $txt[] = "$k=$v";
            }
            $course->attribs = implode("\n", $txt);
        }

        $metadata = JRequest::getVar('meta', null, 'post', 'array');
        if (is_array($params))
        {
            $txt = array();
            foreach ($metadata as $k => $v)
            {
                if ($k == 'description')
                {
                    $course->meta_description = $v;
                } elseif ($k == 'keywords')
                {
                    $course->meta_keywords = $v;
                } else
                {
                    $txt[] = "$k=$v";
                }
            }
            $course->metadata = implode("\n", $txt);
        }

        seminarman_html::saveContentPrep($course);

        if (!$course->id)
        {
            $course->ordering = $course->getNextOrder();
        }

        if (!$course->check())
        {
            $this->setError($course->getError());
            return false;
        }
        
        $course->version++;

        if (!$course->store())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        } 
        else if (!$course->code) 
        {
        	$course->code = $course->id;
        	if (!$course->store())
        	{
        		$this->setError($this->_db->getErrorMsg());
        		return false;
        	}
        }

        $this->_course = &$course;

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

        $query = 'DELETE FROM #__seminarman_files_course_relations WHERE courseid = ' . $course->
            id;
        $this->_db->setQuery($query);
        $this->_db->query();

        foreach ($files as $file)
        {
            $query = 'INSERT IGNORE INTO #__seminarman_files_course_relations (`fileid`, `courseid`) VALUES(' .
                $file . ',' . $course->id . ')';
            $this->_db->setQuery($query);
            $this->_db->query();
        }
        
        if ($data['price2']=='') {
        	$query = 'UPDATE #__seminarman_courses SET price2 = NULL WHERE id = ' . $course->id;
        	$this->_db->setQuery($query);
        	$this->_db->query();        	
        }
        
        if ($data['price3']=='') {
        	$query = 'UPDATE #__seminarman_courses SET price3 = NULL WHERE id = ' . $course->id;
        	$this->_db->setQuery($query);
        	$this->_db->query();
        }
        
        if ($data['price4']=='') {
        	$query = 'UPDATE #__seminarman_courses SET price4 = NULL WHERE id = ' . $course->id;
        	$this->_db->setQuery($query);
        	$this->_db->query();
        }
        
        if ($data['price5']=='') {
        	$query = 'UPDATE #__seminarman_courses SET price5 = NULL WHERE id = ' . $course->id;
        	$this->_db->setQuery($query);
        	$this->_db->query();
        }
        
        if ($data['start_time']=='') {
        	$query = 'UPDATE #__seminarman_courses SET start_time = NULL WHERE id = ' . $course->id;
        	$this->_db->setQuery($query);
        	$this->_db->query();
        }
        
        if ($data['finish_time']=='') {
        	$query = 'UPDATE #__seminarman_courses SET finish_time = NULL WHERE id = ' . $course->id;
        	$this->_db->setQuery($query);
        	$this->_db->query();
        }
        
        $dispatcher = JDispatcher::getInstance();
        JPluginHelper::importPlugin('seminarman');

        // fire vmengine
        $results = $dispatcher->trigger('onProcessCourse', array($course));        
        
        // $smkid = $this->_course->id;
        // $smkvat = doubleval($this->_course->vat);
        
        // $this->vmupdate($smkid, $smkvat);
        
        return true;

    }
    
    function setNew($cid, $value)
    {
    	JRequest::checkToken() or jexit('Invalid Token');
    	
    	$user = JFactory::getUser();
    	
    	$query = 'UPDATE #__seminarman_courses SET new=' . (int)$value . ' ' .
    				'WHERE id=' . (int) $cid . ' ' .
    				'AND (checked_out=0 OR (checked_out=' . $user->get('id') . '))';
    	$this->_db->setQuery($query);
    	$this->_db->query();
    	
    	return true;
    }
    
    function setCanceled($cid, $value)
    {
    	JRequest::checkToken() or jexit('Invalid Token');
    	$user = JFactory::getUser();
    	 
    	$query = 'UPDATE #__seminarman_courses SET canceled=' . (int)$value . ' ' .
        				'WHERE id=' . (int) $cid . ' ' .
        				'AND (checked_out=0 OR (checked_out=' . $user->get('id') . '))';
    	$this->_db->setQuery($query);
    	$this->_db->query();
    	 
    	return true;
    }

    function resetHits($id)
    {
        $row = $this->getTable('seminarman_courses', '');
        $row->load($id);
        $row->hits = 0;
        $row->store();
        $row->checkin();
        return $row->id;
    }


    function gettags()
    {
        $query = 'SELECT * FROM #__seminarman_tags ORDER BY name';
        $this->_db->setQuery($query);
        $tags = $this->_db->loadObjectlist();
        return $tags;
    }

    function getusedtags($id)
    {
    	if ($this->getState('task') == 'createFromTmpl')
    		$query = 'SELECT DISTINCT tid FROM #__seminarman_tags_template_relations WHERE templateid = '.(int)JRequest::getVar('templateId');
    	else
    		$query = 'SELECT DISTINCT tid FROM #__seminarman_tags_course_relations WHERE courseid = ' . (int)$id;
        $this->_db->setQuery($query);
        // $used = $this->_db->loadColumn();
        $used = $this->_db->loadColumn();
        return $used;
    }

 
    function gethits($id)
    {
        $query = 'SELECT hits FROM #__seminarman_courses WHERE id = ' . (int)$id;
        $this->_db->setQuery($query);
        $hits = $this->_db->loadResult();

        return $hits;
    }

    function getCatsselected()
    {
    	if ($this->getState('task') == 'createFromTmpl')
    		$query = 'SELECT DISTINCT catid FROM #__seminarman_cats_template_relations WHERE templateid = '.(int)JRequest::getVar('templateId');
    	else
    		$query = 'SELECT DISTINCT catid FROM #__seminarman_cats_course_relations WHERE courseid = '.(int)$this->_id;
    	 
        $this->_db->setQuery($query);
        // $used = $this->_db->loadColumn();
        $used = $this->_db->loadColumn();
        return $used;
    }

    function getFiles()
    {
    	if ($this->getState('task') == 'createFromTmpl')
    	{
    		$query = 'SELECT DISTINCT rel.fileid, f.filename' . ' FROM #__seminarman_files AS f' .
    			            ' LEFT JOIN #__seminarman_files_template_relations AS rel ON rel.fileid = f.id' .
    			            ' WHERE rel.templateid = ' . (int)JRequest::getVar('templateId');
    	} else
    	{
	        $query = 'SELECT DISTINCT rel.fileid, f.filename' . ' FROM #__seminarman_files AS f' .
	            ' LEFT JOIN #__seminarman_files_course_relations AS rel ON rel.fileid = f.id' .
	            ' WHERE rel.courseid = ' . (int)$this->_id;
    	}
        $this->_db->setQuery($query);
        $files = $this->_db->loadObjectList();
        return $files;
    }

	function copycourse($cid = array()){

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		//$this->setRedirect( 'index.php?option=com_seminarman' );

		$cid	= JRequest::getVar( 'cid', null, 'post', 'array' );
		$db		= JFactory::getDBO();
		$table	= JTable::getInstance('seminarman_courses', '');
		//$table = $this->getTable();
		$user	= JFactory::getUser();
		$n		= count( $cid );
		if ($n > 0)
		{
			foreach ($cid as $id)
			{
				if ($table->load( (int)$id ))
				{
					$table->id				= 0;
					$table->title			= 'Copy of ' . $table->title;
					$table->alias			= 'copy-of-' . $table->alias;
					$table->hits			= 0;
					$table->state			= 0;
					$table->publish_up		= $table->publish_up;
					$table->publish_down	= $table->publish_down;
					$table->ordering		= 0;
					$table->date			= $db->getNullDate();

					if (!$table->store()) {

						return false;
					}
				}
				else {
					return JError::raiseWarning( 500, $table->getError() );
				}
			}
		}
		else {
			return JError::raiseWarning( 500, JText::_( 'No items selected' ) );
		}
		return $n;
	}

    /**
     * Retrieves e-mail templates for booking
     *
     * @return array of objects with templates
     */
    function getEmailTemplates()
    {
        $query = 'SELECT id, title FROM #__seminarman_emailtemplate WHERE templatefor=0';
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }
    
    function getAttendanceLstTemplate($id = 0)
    {
    	$db = JFactory::getDBO();
    	$query = 'SELECT * FROM #__seminarman_pdftemplate WHERE templatefor=1 AND ';
    	if ($id == 0) {
    		$query .= 'isdefault=1 LIMIT 1';
    	} else {
    		$query .= 'id='.(int)$id.' LIMIT 1';
    	}
    	$db->setQuery($query);
    	
    	return $db->loadObject();
    }
    
    /**
    * returns an key value array that can be used to replace
    * the fields in a pdf template with actual values
    */
    function getAttendanceLstTemplateData()
    {
    	$db = JFactory::getDBO();
    	 
    	$db->setQuery('SELECT'.
    	                      ' NOW() AS `CURRENT_DATE`,'.
    	                      ' SUM(a.attendees) AS `ATTENDEES_TOTAL`,'.
    			              ' c.id AS `COURSE_ID`,'.
    	                      ' c.title AS `COURSE_TITLE`,'.
       	                      ' c.code AS `COURSE_CODE`,'.
       	                      ' c.capacity AS `COURSE_CAPACITY`,'.
       	                      ' c.location AS `COURSE_LOCATION`,'.
       	                      ' c.url AS `COURSE_URL`,'.
       	                      ' c.start_date AS `COURSE_START_DATE`,'.
       	                      ' c.finish_date AS `COURSE_FINISH_DATE`,'.
    			' c.start_time AS `COURSE_START_TIME`,'.
    			' c.finish_time AS `COURSE_FINISH_TIME`,'.
    			              ' t.id AS `TUTOR_ID`,'.
       	                      ' t.title AS `TUTOR`,'.
       	                      ' t.firstname AS `TUTOR_FIRSTNAME`,'.
       	                      ' t.lastname AS `TUTOR_LASTNAME`,'.
    			              ' t.salutation AS `TUTOR_SALUTATION`,'.
    			              ' t.other_title AS `TUTOR_OTHER_TITLE`'.
     	                    ' FROM `#__seminarman_courses` AS c'.
      	                    ' LEFT JOIN `#__seminarman_tutor` AS t ON c.tutor_id = t.id'.
      	                    ' LEFT JOIN `#__seminarman_application` AS a ON a.course_id = c.id AND a.status IN (1,2)'.
      	                    ' WHERE c.id = '. (int) $this->_id);
    	$data = $db->loadAssoc();
    	
    	// format date
    	$data['CURRENT_DATE'] = JFactory::getDate($data['CURRENT_DATE'])->format(JText::_('COM_SEMINARMAN_DATE_FORMAT1'));
    	$data['COURSE_START_DATE'] = JFactory::getDate($data['COURSE_START_DATE'])->format(JText::_('COM_SEMINARMAN_DATE_FORMAT1'));
    	$data['COURSE_FINISH_DATE'] = JFactory::getDate($data['COURSE_FINISH_DATE'])->format(JText::_('COM_SEMINARMAN_DATE_FORMAT1'));
		$data['CATEGORIES'] = join(', ',ApplicationHelper::getCourseCategoryTitles((int) $this->_id));
	$data['COURSE_START_TIME'] = (!empty($data['COURSE_START_TIME'])) ? date('H:i', strtotime($data['COURSE_START_TIME'])) : '';
    	$data['COURSE_FINISH_TIME'] = (!empty($data['COURSE_FINISH_TIME'])) ? date('H:i', strtotime($data['COURSE_FINISH_TIME'])) : '';

    	// custom tutor fields
    	$db->setQuery('SELECT f.fieldcode, ct.value FROM `#__seminarman_fields_values_tutors` AS ct'.
    			' LEFT JOIN `#__seminarman_fields` AS f ON ct.field_id = f.id'.
    			' WHERE ct.tutor_id = '. (int) $data['TUTOR_ID'] . ' AND f.published = ' . $db->Quote('1'));
    	foreach ($db->loadRowList() as $row)
    		$data[$row[0]] = $row[1];    	
    	
    	// start weekday
    	$langs = JComponentHelper::getParams('com_languages');
    	$selectedLang = $langs->get('site', 'en-GB');
    	if ($selectedLang == "de-DE") {
    		$trans = array(
    				'Monday'    => 'Montag',
    				'Tuesday'   => 'Dienstag',
    				'Wednesday' => 'Mittwoch',
    				'Thursday'  => 'Donnerstag',
    				'Friday'    => 'Freitag',
    				'Saturday'  => 'Samstag',
    				'Sunday'    => 'Sonntag',
    				'Mon'       => 'Mo',
    				'Tue'       => 'Di',
    				'Wed'       => 'Mi',
    				'Thu'       => 'Do',
    				'Fri'       => 'Fr',
    				'Sat'       => 'Sa',
    				'Sun'       => 'So',
    				'January'   => 'Januar',
    				'February'  => 'Februar',
    				'March'     => 'März',
    				'May'       => 'Mai',
    				'June'      => 'Juni',
    				'July'      => 'Juli',
    				'October'   => 'Oktober',
    				'December'  => 'Dezember'
    		);
    		$data['COURSE_START_WEEKDAY'] = (!empty($data['COURSE_START_DATE'])) ? strtr(date('l', strtotime($data['COURSE_START_DATE'])), $trans) : '';
    	} else {
    		$data['COURSE_START_WEEKDAY'] = (!empty($data['COURSE_START_DATE'])) ? date('l', strtotime($data['COURSE_START_DATE'])) : '';
    	}
    	
    	// first session infos
    	$sql = 'SELECT * FROM #__seminarman_sessions'
    	. ' WHERE published = 1'
    	. ' AND courseid = ' . $this->_id
    	. ' ORDER BY session_date';
    	$db->setQuery($sql);
    	$course_sessions = $db->loadObjectList();
    	
    	if(!empty($course_sessions)){
    		$data['COURSE_FIRST_SESSION_TITLE'] = $course_sessions[0]->title;
    		$data['COURSE_FIRST_SESSION_CLOCK'] = date('H:i', strtotime($course_sessions[0]->start_time)) . ' - ' . date('H:i', strtotime($course_sessions[0]->finish_time));
    		$data['COURSE_FIRST_SESSION_DURATION'] = $course_sessions[0]->duration;
    		$data['COURSE_FIRST_SESSION_ROOM'] = $course_sessions[0]->session_location;
    		$data['COURSE_FIRST_SESSION_COMMENT'] = $course_sessions[0]->description;
    	} else {
    		$data['COURSE_FIRST_SESSION_TITLE'] = '';
    		$data['COURSE_FIRST_SESSION_CLOCK'] = '';
    		$data['COURSE_FIRST_SESSION_DURATION'] = '';
    		$data['COURSE_FIRST_SESSION_ROOM'] = '';
    		$data['COURSE_FIRST_SESSION_COMMENT'] = '';
    	}
    	
    	return $data;
    }
    
    function getAttendeesData()
    {
    	$db = JFactory::getDBO();
    	
    	$params = JComponentHelper::getParams( 'com_seminarman' );
    	$order_type = $params->get("order_of_attlst");
    	
    	switch($order_type) {
    		case "0":
    			$order_type_string = "a.id";
    			break;
    		case "1":
    			$order_type_string = "a.last_name, a.first_name";
    			break;
    		case "2":
    			$order_type_string = "a.first_name, a.last_name";
    			break;
    		case "3":
    			$order_type_string = "a.email";
    			break;
    		default:
    			$order_type_string = "a.id";
    	}
    	
    	$db->setQuery('SELECT a.id,'.
    	                      ' a.salutation AS `SALUTATION`,'.
       	                      ' a.title AS `TITLE`,'.
       	                      ' a.first_name AS `FIRSTNAME`,'.
       	                      ' a.last_name AS `LASTNAME`,'.
    			              ' a.pricegroup AS `PRICE_GROUP_ORDERED`,'.
    			              ' a.status AS `PAYMENT_STATUS`,'.
       	                      ' a.email AS `EMAIL`,'.
    						  ' a.note_reading AS `NOTE_READING`,'.
						      ' a.note_test AS `NOTE_TEST`,'.
						      ' a.note_work AS `NOTE_WORK`,'.
						      ' a.note AS `NOTE`,'.
						      ' a.attendance AS `ATTENDANCE`,'.
    						  ' a.status AS `STATUS_ID`'.
     	                    ' FROM `#__seminarman_application` AS a'.
      	                    ' LEFT JOIN `#__seminarman_courses` AS c ON a.course_id = c.id AND a.status IN (1,2)'.
      	                    ' WHERE c.id = '. (int) $this->_id . ' AND a.published = 1' .
      	                    ' ORDER BY ' . $order_type_string);
    	$data = $db->loadAssocList('id');  	
    	
    	foreach ($data as $k => $v) {
    		unset($data[$k]['id']);
		
		if ($data[$k]['PAYMENT_STATUS'] == 1) {
    			$data[$k]['PAYMENT_STATUS'] = JText::_( 'COM_SEMINARMAN_PENDING' );
    		} elseif ($data[$k]['PAYMENT_STATUS'] == 2) {
    			$data[$k]['PAYMENT_STATUS'] = JText::_( 'COM_SEMINARMAN_PAID' );
    		}
    	}
    	
		//add empty values for not set data
		$db->setQuery('SELECT fieldcode'.
				' FROM `#__seminarman_fields` AS f'.
				' WHERE f.purpose = 0');
		$result = $db->loadColumn();
		if(isset($result)){
			foreach ($result as $fieldcode) {
				$field = strtoupper($fieldcode);
				foreach ($data as $name => $applicationData) {
					$data[$name][$field] = ' ';
				}
			}
		}

    	$db->setQuery('SELECT v.applicationid, fieldcode, value'.
    	                   ' FROM `#__seminarman_fields_values` AS v'.
    	                   ' LEFT JOIN `#__seminarman_fields` AS f ON v.field_id = f.id'.
    	                   ' LEFT JOIN `#__seminarman_application` AS a ON a.id = v.applicationid'.
    	                   ' WHERE a.status IN (1,2) AND a.course_id = '. (int) $this->_id . ' AND a.published = 1' .
    	                   ' ORDER BY v.applicationid');
    	foreach ($db->loadRowList() as $record) {
    		$data[$record[0]][$record[1]] = $record[2];
    	}
  	
    	return $data;
    }
    
}

?>
