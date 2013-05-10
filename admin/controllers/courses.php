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

jimport('joomla.application.component.controller');

require_once JPATH_ROOT.'/components/com_seminarman/helpers/application.php';

class SeminarmanControllerCourses extends SeminarmanController
{
    function __construct()
    {
        parent::__construct();

        // $this->registerTask('add', 'edit');
        $this->registerTask('apply', 'save');
        $this->registerTask('createFromTmpl', 'createFromTmpl');
        $this->registerTask('setNew', 'setNew');
        $this->registerTask('unsetNew', 'setNew');
        $this->registerTask('setCanceled', 'setCanceled');
        $this->registerTask('unsetCanceled', 'setCanceled');

        $task = JRequest::getCmd('task');
        switch (strtolower($task))
        {
            case 'publish':
                $this->changeContent(1);
                break;

            case 'unpublish':
                $this->changeContent(0);
                break;

            case 'archive':
                $this->changeContent(-1);
                break;

            case 'unarchive':
                $this->changeContent(0);
                break;
            default:
                break;
        }
    }

    function save()
    {

        JRequest::checkToken() or jexit('Invalid Token');

        $task = JRequest::getVar('task');

        $post = JRequest::get('post');

        $model = $this->getModel('course');

        if ($model->store($post))
        {

            switch ($task)
            {
                case 'apply':
                    $link = 'index.php?option=com_seminarman&controller=courses&task=edit&cid[]=' . (int)$model->get('id');
                    break;

                default:
                    $link = 'index.php?option=com_seminarman&view=courses';
                    break;
            }
            $msg = JText::_('COM_SEMINARMAN_RECORD_SAVED');

            $cache = JFactory::getCache('com_seminarman');
            $cache->clean();

        } else
        {
            $msg = JText::_('COM_SEMINARMAN_ERROR_SAVING');
            JError::raiseError(500, $model->getError());
            $link = 'index.php?option=com_seminarman&view=course';
        }

        $model->checkin();

        $this->setRedirect($link, $msg);
    }
    
    function createFromTmpl()
    {
    	JRequest::checkToken() or jexit('Invalid Token');
    	 
    	JRequest::setVar('view', 'course');
    	JRequest::setVar('hidemainmenu', 1);

    	$id = JRequest::getVar('id');
    	if ($id != 0)
    	{
    		// bereits bestehender Kurs wird von Template überschrieben
    		JRequest::setVar('cid', $id);
    	}

    	$model = $this->getModel('course');
    	$user = JFactory::getUser();
    	
    	parent::display();
    }
    
    function setNew()
    {
    	JRequest::checkToken() or jexit('Invalid Token');
    	 
    	$cid = JRequest::getVar('cid', array(0), 'post', 'array');
    	$cid = $cid[0];
    	$model = $this->getModel('course');
    	
    	if ($this->task == 'setNew') $model->setNew($cid, 1);
    	else $model->setNew($cid, 0);
    	
    	$cache = JFactory::getCache('com_seminarman');
    	$cache->clean();
        
        $this->setRedirect('index.php?option=com_seminarman&view=courses');
    }
    
    function setCanceled()
    {
    	JRequest::checkToken() or jexit('Invalid Token');
    
    	$cid = JRequest::getVar('cid', array(0), 'post', 'array');
    	$cid = $cid[0];
    	$model = $this->getModel('course');
    	 
    	if ($this->task == 'setCanceled') $model->setCanceled($cid, 1);
    	else $model->setCanceled($cid, 0);
    	 
    	$cache = JFactory::getCache('com_seminarman');
    	$cache->clean();
    
    	$this->setRedirect('index.php?option=com_seminarman&view=courses');
    }

    function orderup()
    {

        JRequest::checkToken() or jexit('Invalid Token');

        $model = $this->getModel('courses');
        $model->move(-1);

        $this->setRedirect('index.php?option=com_seminarman&view=courses');
    }

    function orderdown()
    {

        JRequest::checkToken() or jexit('Invalid Token');

        $model = $this->getModel('courses');
        $model->move(1);

        $this->setRedirect('index.php?option=com_seminarman&view=courses');
    }

    function saveorder()
    {

        JRequest::checkToken() or jexit('Invalid Token');

        $cid = JRequest::getVar('cid', array(0), 'post', 'array');
        $order = JRequest::getVar('order', array(0), 'post', 'array');

        $model = $this->getModel('courses');
        if (!$model->saveorder($cid, $order))
        {
            $msg = '';
            JError::raiseError(500, $model->getError());
        } else
        {
            $msg = JText::_('COM_SEMINARMAN_OPERATION_SUCCESSFULL');
        }

        $this->setRedirect('index.php?option=com_seminarman&view=courses', $msg);
    }

    function setcoursestate()
    {
        $id = JRequest::getInt('id', 0);
        $state = JRequest::getVar('state', 0);

        $model = $this->getModel('courses');

        if (!$model->setcoursestate($id, $state))
        {
            JError::raiseError(500, $model->getError());
        }

        if ($state == 1)
        {
            $img = 'tick.png';
            $alt = JText::_('Published');
        } else
            if ($state == 0)
            {
                $img = 'publish_x.png';
                $alt = JText::_('Unpublished');
            } else
                if ($state == -1)
                {
                    $img = 'disabled.png';
                    $alt = JText::_('Archived');
                } else
                    if ($state == -2)
                    {
                        $img = 'publish_r.png';
                        $alt = JText::_('PENDING');
                    } else
                        if ($state == -3)
                        {
                            $img = 'publish_y.png';
                            $alt = JText::_('OPEN QUESTION');
                        } else
                            if ($state == -4)
                            {
                                $img = 'publish_g.png';
                                $alt = JText::_('IN PROGRESS');
                            }

        $cache = JFactory::getCache('com_seminarman');
        $cache->clean();

        echo '<img src="images/' . $img . '" width="16" height="16" border="0" alt="' .
            $alt . '" />';
    }

    function remove()
    {

        JRequest::checkToken() or jexit('Invalid Token');

        $cid = JRequest::getVar('cid', array(0), 'post', 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            JError::raiseError(500, JText::_('COM_SEMINARMAN_SELECT_ITEM'));
        }

        $model = $this->getModel('courses');

        if (!$model->delete($cid))
        {
            $msg = '';
            JError::raiseError(500, $model->getError());
        } else
        {
            $msg = JText::_('COM_SEMINARMAN_OPERATION_SUCCESSFULL');

            $cache = JFactory::getCache('com_seminarman');
            $cache->clean();
        }

        $this->setRedirect('index.php?option=com_seminarman&view=courses', $msg);
    }

    function cancel()
    {

        JRequest::checkToken() or jexit('Invalid Token');

        $course = JTable::getInstance('seminarman_courses', '');
        $course->bind(JRequest::get('post'));
        $course->checkin();

        $this->setRedirect('index.php?option=com_seminarman&view=courses');
    }
    
    function add()
    {
        JRequest::setVar('view', 'course');
        JRequest::setVar('hidemainmenu', 1);

        $model = $this->getModel('course');
        $user = JFactory::getUser();
        
	    $course_owner = intval($model->get('created_by'));
	    $current_uid = intval($user->id);
	    
        if ($model->isCheckedOut($user->get('id')))
        {
            $this->setRedirect('index.php?option=com_seminarman&view=courses', JText::_('COM_SEMINARMAN_EDITED_BY_ANOTHER_ADMIN'));
        }

        $model->checkout($user->get('id'));

        parent::display();
    }

    function edit()
    {
    	
// jimport('joomla.application.component.modeladmin'); 
JModel::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_users' . DS . 'models' , 'UsersModel');
    	
        JRequest::setVar('view', 'course');
        JRequest::setVar('hidemainmenu', 1);
        
        // $items_model =& JModel::getInstance('claudy', 'UsersModel');
        // $items_model = JModel::getInstance( 'claudy', 'UsersModel' );
        // var_dump($items_model);
        // $items_model->setState();
        // $items_model->setClaudy();
        
        // $this->test();

        $model = $this->getModel('course');
        $user = JFactory::getUser();
        
	    $course_owner = intval($model->get('created_by'));
	    $current_uid = intval($user->id);

    	// If ($course_owner != $current_uid) {
	    //	$link = 'index.php?option=com_seminarman&view=courses';
	    //	$msg = 'dude, not allowed! '.$course_owner.'(course owner) != '.$current_uid.'(current uid)';
	    //	$this->setRedirect($link, $msg);
	    // } else {	    
        if ($model->isCheckedOut($user->get('id')))
        {
            $this->setRedirect('index.php?option=com_seminarman&view=courses', JText::_('COM_SEMINARMAN_EDITED_BY_ANOTHER_ADMIN'));
        }

        $model->checkout($user->get('id'));

        parent::display();
	    //}
    }

	function showSessions()
	{
		JRequest::setVar('view', 'sessions');

		$model = $this->getModel('course');
		$user = JFactory::getUser();
		$cid = JRequest::getVar('cid');
		//I want just the first item
		$cid = $cid [0];

        $this->setRedirect('index.php?option=com_seminarman&view=sessions&filter_courseid='. $cid);
	}


    function resethits()
    {
        $id = JRequest::getInt('id', 0);
        $model = $this->getModel('course');

        $model->resetHits($id);

        $cache = JFactory::getCache('com_seminarman');
        $cache->clean();

        echo 0;
    }

    function resetvotes()
    {
        $id = JRequest::getInt('id', 0);
        $model = $this->getModel('course');

        $model->resetVotes($id);

        $cache = JFactory::getCache('com_seminarman');
        $cache->clean();

        echo '+ 0 | - 0';
    }

    function gettags()
    {
        $id = JRequest::getInt('id', 0);
        $model = $this->getModel('course');
        $tags = $model->gettags();

        $used = null;

        if ($id)
        {
            $used = $model->getusedtags($id);
        }
        if (!is_array($used))
        {
            $used = array();
        }

        $rsp = '<div class="qf_tagbox">';
        $n = count($tags);
        for ($i = 0, $n; $i < $n; $i++)
        {
            $tag = $tags[$i];

            if (($i % 5) == 0)
            {
                if ($i != 0)
                {
                    $rsp .= '</div>';
                }
                $rsp .= '<div class="qf_tagline">';
            }
            $rsp .= '<span class="qf_tag"><span class="qf_tagidbox"><input type="checkbox" name="tag[]" value="' .
                $tag->id . '"' . (in_array($tag->id, $used) ? 'checked="checked"' : '') .
                ' /></span>' . $tag->name . '</span>';
        }
        $rsp .= '</div>';
        $rsp .= '</div>';
        $rsp .= '<div class="clear"></div>';
        $rsp .= '<div class="qf_addtag">';
        $rsp .= '<label for="addtags">' . JText::_('ADD TAG') . '</label>';
        $rsp .= '<input type="text" id="tagname" class="inputbox" size="30" />';
        $rsp .= '<input type="button" class="button" value="' . JText::_('ADD') .
            '" onclick="addtag()" />';
        $rsp .= '</div>';

        echo $rsp;
    }

    function getvotes()
    {
        $id = JRequest::getInt('id', 0);
        $model = $this->getModel('course');
        $votes = $model->getvotes($id);

        if ($votes)
        {
            echo '+ ' . (int)$votes[0]->plus . ' | - ' . (int)$votes[0]->minus;
        } else
        {
            echo '+ 0 | - 0';
        }
    }

    function gethits()
    {
        $id = JRequest::getInt('id', 0);
        $model = $this->getModel('course');
        $hits = $model->gethits($id);

        if ($hits)
        {
            echo $hits;
        } else
        {
            echo 0;
        }
    }
    function changeContent($state = 0)
    {
        $mainframe = JFactory::getApplication();
        $model = $this->getModel('courses');

        JRequest::checkToken() or jexit('Invalid Token');

        $db = JFactory::getDBO();
        $user = JFactory::getUser();

        $cid = JRequest::getVar('cid', array(), 'post', 'array');
        JArrayHelper::toInteger($cid);
        $option = JRequest::getCmd('option');
        $task = JRequest::getCmd('task');
        $rtask = JRequest::getCmd('returntask', '', 'post');
        if ($rtask)
        {
            $rtask = '&task=' . $rtask;
        }

        if (count($cid) < 1)
        {
            $redirect = JRequest::getVar('redirect', '', 'post', 'int');            
            $msg = JText::_('COM_SEMINARMAN_SELECT_ITEM');
            $mainframe->redirect('index.php?option=' . $option . $rtask . '&sectionid=' . $redirect,
                $msg, 'error');
        }

        $uid = $user->get('id');
        $total = count($cid);
        $cids = implode(',', $cid);

        $query = 'UPDATE #__seminarman_courses' . ' SET state = ' . (int)$state .
            ' WHERE id IN ( ' . $cids . ' ) AND ( checked_out = 0 OR (checked_out = ' . (int)
            $uid . ' ) )';
        $db->setQuery($query);
        if (!$db->query())
        {
            JError::raiseError(500, $db->getErrorMsg());
            return false;
        }

        if (count($cid) == 1)
        {
            $row = JTable::getInstance('seminarman_courses', '');

        }

        switch ($state)
        {
            case - 1:
                $msg = JText::sprintf('COM_SEMINARMAN_OPERATION_SUCCESSFULL');
                break;

            case 1:
                $msg = JText::sprintf('COM_SEMINARMAN_OPERATION_SUCCESSFULL');
                break;

            case 0:
            default:
                if ($task == 'unarchive')
                {
                    $msg = JText::sprintf('COM_SEMINARMAN_OPERATION_SUCCESSFULL');
                } else
                {
                    $msg = JText::sprintf('COM_SEMINARMAN_OPERATION_SUCCESSFULL');
                }
                break;
        }

        $cache = JFactory::getCache('com_seminarman');
        $cache->clean();


        $mainframe->redirect('index.php?option=' . $option . "&view=courses", $msg);
    }


    function filemanager()
    {

        JRequest::checkToken() or jexit('Invalid Token');
        $this->setRedirect('index.php?option=com_seminarman&view=filemanager');
    }

	/**
	 * Copies one or more courses
	 */
	function copy()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'COM_SEMINARMAN_SELECT_ITEM' ) );
		}

		$model = $this->getModel('course');

		if(!$model->copycourse($cid)) {
			$msg = JText::_( 'COM_SEMINARMAN_ERROR_SAVING' );
			$this->setRedirect( 'index.php?option=com_seminarman&view=courses', $msg );
		} else {$msg = JText::_( 'COM_SEMINARMAN_RECORD_SAVED' );}

		$this->setRedirect( 'index.php?option=com_seminarman&view=courses', $msg );
	}
	
	
	function attendancelist()
	{
		require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_seminarman'.DS.'classes'.DS.'pdfdocument.php';

		JRequest::checkToken() or jexit( 'Invalid Token' );
		$model = $this->getModel('course');

		$course = $model->getCourse();
		$template = $model->getAttendanceLstTemplate($course->attlst_template);
		$templateData = $model->getAttendanceLstTemplateData();
		$attendees = $model->getAttendeesData();
		
		$templateData['ATTENDEES_STATUS_0'] = 0;
		$templateData['ATTENDEES_STATUS_1'] = 0;
		$templateData['ATTENDEES_STATUS_2'] = 0;
		$templateData['ATTENDEES_STATUS_3'] = 0;
		
		//count attendees per status
		foreach ($attendees as $k => $v) {
			$key = 'ATTENDEES_STATUS_'.$v['STATUS_ID'];
			$templateData[$key] = $templateData[$key] + 1;
		}
		
		$pdf = new PdfAttList($template, $templateData, $attendees);
		$pdf->Output('attendees_'. $course->id .'.pdf', 'I');
		exit;
	}
	
	function sendattendancelist(){
		require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_seminarman'.DS.'classes'.DS.'pdfdocument.php';

		JRequest::checkToken() or jexit( 'Invalid Token' );
		$courseIds = JRequest::getVar( 'cid' );
		$fillErrors = array();
		foreach ($courseIds as $courseId) {
			$model = $this->getModel('course');
			$model->setId($courseId);
			$course = $model->getCourse();
			$template = $model->getAttendanceLstTemplate($course->attlst_template);
			$templateData = $model->getAttendanceLstTemplateData();
			$attendees = $model->getAttendeesData();
			
			$templateData['ATTENDEES_STATUS_0'] = 0;
			$templateData['ATTENDEES_STATUS_1'] = 0;
			$templateData['ATTENDEES_STATUS_2'] = 0;
			$templateData['ATTENDEES_STATUS_3'] = 0;
			
			//count attendees per status
			foreach ($attendees as $k => $v) {
				$key = 'ATTENDEES_STATUS_'.$v['STATUS_ID'];
				$templateData[$key] = $templateData[$key] + 1;
			}
			
			$pdf = new PdfAttList($template, $templateData, $attendees);
			$pdf->store(JPATH_ROOT.DS.'invoices'.DS.$course->title.'-'.JFactory::getDate($course->start_date)->format(JText::_('COM_SEMINARMAN_DATE_FORMAT1')).'.pdf');
			$user = $model->getTutorAsUser();
			$template_id = 2;
			$catTitles = ApplicationHelper::getCourseCategoryTitles($courseId);
			ApplicationHelper::sendemailToUserByCourseAndTemplate($user, $course, $catTitles, $fillErrors, $template_id, $pdf->getFile());
		}
		if (empty($fillErrors)) {
			$msg = JText::_( 'COM_SEMINARMAN_ATTENDANCE_LIST_SENT' ) . ' ' . join(', ', $fillErrors);
		} else {
			$msg = JText::_( 'COM_SEMINARMAN_ATTENDANCE_LIST_SENT_FAILED' ) . ' Errors: ' . join(', ', $fillErrors);
		}
		$this->setRedirect('index.php?option=com_seminarman&view=courses', $msg );
	}

	function cancelcategorytousergroup()
	{
		$this->categorytousergroup(true);
	}
	
	function bookcategorytousergroup()
	{
		$this->categorytousergroup(false);
	}
	
	function categorytousergroup($cancel = false)
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$courseIds = JRequest::getVar( 'cid' );
		$fillErrors = array();
		foreach ($courseIds as $courseId) {
			$catTitles = ApplicationHelper::getCourseCategoryTitles($courseId);
			if($catTitles){
				$groupIds = $this->getGroupIdsMappedByCategories($catTitles);
				if($groupIds){
					$userIds = $this->getUserIdsByGroups($groupIds);
					$users = $this->getUsersByIds($userIds);
					$course = ApplicationHelper::loadCourse($courseId);
					if($userIds){
						if($cancel){
							$this->cancelCourseForUsers($course, $catTitles, $users, $fillErrors);							
							$msg = JText::_( 'COM_SEMINARMAN_CANCELED_CATEGORY_TO_USERGROUP' );
						}else{
							$this->bookCourseForUsers($course, $catTitles, $users, $fillErrors);
							$msg = JText::_( 'COM_SEMINARMAN_BOOKED_CATEGORY_TO_USERGROUP' ) . ' ' . join(', ', $fillErrors);
						}
					}else{
						$msg = JText::_( 'COM_SEMINARMAN_NO_USERS_IN_USERGROUPS' );
					}
				}else {
					$msg = JText::_( 'COM_SEMINARMAN_NO_USERGROUPS_MATCH_TO_CATEGORIES' );
				}
			}else{
				$msg = JText::_( 'COM_SEMINARMAN_NO_CATEGORIES_SELECTED' );
			}
		}
		if(count($this->getErrors())){
			$msg = $msg. join(',',$this->getErrors());
		}
		$this->setRedirect('index.php?option=com_seminarman&view=courses', $msg );
	}

	private function getUserIdsByGroups($groupIds)
	{
		// Get a database object.
		$db = JFactory::getDbo();
	
		$query	= $db->getQuery(true);
		$query->select('DISTINCT(user_id)');
		$query->from('#__usergroups as ug1');
		$query->join('INNER', '#__user_usergroup_map AS m ON ug1.id=m.group_id');
		$query->where('ug1.id IN ('.join(",",$groupIds).')');
	
		$db->setQuery($query);
	
		$userIds = $db->loadResultArray();
	
		return $userIds;
	}
	
	private function getUsersByIds($userIds)
	{
		// Get a database object.
		$db = JFactory::getDbo();

		$db->setQuery('SELECT u.* FROM #__users AS u WHERE u.id IN ('.join(",",$userIds).')');

		$users = $db->loadObjectList();

		return $users;
	}
	
	private function getAppIdsByCourseIdAndUserIdsByIds($courseId, $userIds)
	{
		// Get a database object.
		$db = JFactory::getDbo();
	
		$db->setQuery('SELECT a.id FROM #__seminarman_application AS a WHERE a.course_id=' . $courseId . ' AND a.user_id IN ('.join(",",$userIds).')');
		
		$appIds = $db->loadResultArray();
	
		return $appIds;
	}

	private function buildUsergroupPrefix()
	{
		$year = (int)date("Y");
		$mon = (int)date("n");

		if($mon<9){
			$prefix = $year-1;
		}else{
			$prefix = $year;
		}
		return $prefix;
	}

	private function getGroupIdsMappedByCategories($catTitles)
	{
		$db = JFactory::getDbo();
		$prefix = $this->buildUsergroupPrefix();

		//create in string
		$first = true;
		$in ='';
		foreach($catTitles as $catTitle) {
			if($first){
				$first = false;
			}else {
				$in = $in.',';
			}
			$in = $in.$db->quote($prefix.' '.$catTitle,false);
		}

		$db->setQuery(
				'SELECT g.id FROM #__usergroups AS g WHERE g.title IN ('.$in.')'
		);
		$groupIds = $db->loadResultArray();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseNotice(500, $db->getErrorMsg());
			return null;
		}
		return $groupIds;
	}

	private function cancelBookedCourseNoEmail($courseId, $userIds)
	{
		$model = $this->getModel( 'application' );
		$appIds = $this->getAppIdsByCourseIdAndUserIdsByIds($courseId, $userIds);
		if(!empty($appIds)){
			$model->delete($appIds);
		}
	}

	private function cancelCourseForUsers($course, $catTitles, $users, $fillErrors)
	{
		CMFactory::load('libraries', 'customfields');
		$customFields = array();
		$msgEmailError = JText::_( 'COM_SEMINARMAN_CANCELING_EMAIL_ERROR' );
		$sendEmail = !ApplicationHelper::isCourseOld($course);
		$model = $this->getModel( 'application' );
		foreach($users as $user) {
			$userId = $user->id;
			// did this user already book that course?
			$applicationId = ApplicationHelper::getIdByCourseAndUser($course->id, $userId);
			if ($applicationId) {
				if($sendEmail){
					ApplicationHelper::sendemail($application_id, $user, $course, $catTitles, $fillErrors, 3);
				}
				ApplicationHelper::cancel($applicationId);
			}
		}
	}	
	
	private function bookCourseForUsers($course, $catTitles, $users, $fillErrors)
	{
		CMFactory::load('libraries', 'customfields');
		$customFields = array();
		$msgEmailError = JText::_( 'COM_SEMINARMAN_BOOKING_EMAIL_ERROR' );
		$sendEmail = !ApplicationHelper::isCourseOld($course);
		foreach($users as $user) {
			$userId = $user->id;
			// did this user already book that course?
			$applicationId = ApplicationHelper::getIdByCourseAndUser($course->id, $userId);
			if (!$applicationId) {
				$application_id = ApplicationHelper::book($user, $course, $fillErrors);
				if($application_id != null ){
					if($sendEmail){
						ApplicationHelper::sendemail($application_id, $user, $course, $catTitles, $fillErrors, $course->email_template);
					}
				}
			}
		}
	}
	
	function checkAllowed()
	{
		$model = $this->getModel('course');
	    $course_owner = (int)$model->get('created_by');
	    $current_uid = JFactory::getUser()->id;
	    If ($course_owner != $current_uid) {
	    	$link = 'index.php?option=com_seminarman&view=course';
	    	$msg = 'not allowed!';
	    	$this->setRedirect($link, $msg);
	    }	
	}
	
	function test()
	{
        $app = JFactory::getApplication();
    	$app->enqueueMessage('Hola Claudy!', 'message');
    	// return true;	
	}
}
