<?php

/**
 *
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
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class seminarmanModelapplication extends JModelLegacy
{
    var $_id = null;

    var $_data = null;

    function __construct()
    {
        parent::__construct();

        $id = JRequest::getVar('id', 0, '', 'int');
        $this->_course_id = JRequest::getVar('course_id', 0, '', 'int');
        $this->setId((int)$id);
    }

    function setId($id)
    {
        $this->_id = $id;
        $this->_data = null;
    }

    function &getData()
    {
        if ($this->_loadData()) {
            $user = JFactory::getUser();

            if (!$this->_data->published) {
                JError::raiseError(404, JText::_("Resource Not Found"));
                return false;
            }

            if (!$this->_data->cat_pub) {
                JError::raiseError(404, JText::_("Resource Not Found"));
                return;
            }

            if ($this->_data->cat_access > $user->get('aid', 0)) {
                JError::raiseError(403, JText::_('ALERTNOTAUTH'));
                return;
            }
        } else
            $this->_initData();

        return $this->_data;
    }
    
    function hit()
    {
        $mainframe = JFactory::getApplication();

        if ($this->_id) {
            $booking = $this->getTable();
            $booking->hit($this->_id);
            return true;
        }
        return false;
    }

    function isCheckedOut($uid = 0)
    {
        if ($this->_loadData()) {
            if ($uid) {
                return ($this->_data->checked_out && $this->_data->checked_out != $uid);
            } else {
                return $this->_data->checked_out;
            }
        }
    }

    function checkin()
    {
        if ($this->_id) {
            $booking = $this->getTable();
            if (!$booking->checkin($this->_id)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            return true;
        }
        return false;
    }

    function checkout($uid = null)
    {
        if ($this->_id) {
            if (is_null($uid)) {
                $user = JFactory::getUser();
                $uid = $user->get('id');
            }

            $booking = $this->getTable();
            if (!$booking->checkout($uid, $this->_id)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

            return true;
        }
        return false;
    }

    function store($data)
    {
    	$db = $this->getDBO();
    	 
        JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_seminarman'.DS.'tables');
        $row = JTable::getInstance('application', 'Table');
        
        $tempArray = array();
        $dataArray = array('date'=>date('Y-m-d H:i:s'), 'user'=>JText::_('COM_SEMINARMAN_ONLINE_BOOKING'), 'status'=>0);
        array_push($tempArray, $dataArray);
        $data['params']['protocols'] = json_encode($tempArray);
        
        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        $row->date = gmdate('Y-m-d H:i:s');
        
        if (!$row->id) {
        	$is_insert = 1;
            $where = 'course_id = ' . (int)$row->course_id;
            $row->ordering = $row->getNextOrder($where);
        }
        else
        	$is_insert = 0;

        if (!$row->check()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        
        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        
        $user = JFactory::getUser();
        if ($user->guest)
        	$uid = $data['user_id'];
        else
        	$uid = $user->id;
        
        $title = $db->quote($data['title']);
        $salutation = $db->quote($data['salutation']);
        $db->setQuery('REPLACE INTO `#__seminarman_fields_values_users_static` (user_id, salutation, title) VALUES ('.$uid.','.$salutation.','.$title.')');
        $db->query();
        
        return ($row->id);
    }
    
    function uploadfile()
    {
        $mainframe = JFactory::getApplication();
        $params = JComponentHelper::getParams('com_seminarman');

        $file = JRequest::getVar('cv', null, 'files', 'array');

        if ($file['size'] > 0) {
            jimport('joomla.filesystem.file');

            $path = COM_SEMINARMAN_CVFILEPATH . DS;

            $filename = seminarman_upload::sanitize($path, $file['name']);

            $filepath = JPath::clean($path . strtolower($filename));

            $src = $file['tmp_name'];

            $dest = $filepath;

            if (!seminarman_upload::check($file, $err)) {
                if ($format == 'json') {
                    jimport('joomla.error.log');
                    $log = JLog::getInstance('com_seminarman.error.php');
                    $log->addEntry(array('comment' => 'Invalid: ' . $filepath . ': ' . $err));
                    header('HTTP/1.0 415 Unsupported Media Type');
                    die('Error. Unsupported Media Type!');
                } else {
                    JError::raiseNotice(100, JText::_($err));

                    if ($return) {
                        $mainframe->redirect(base64_decode($return));
                    }
                    return;
                }
            }

            if (JFile::upload($src, $dest)) {
                return $filename;
            } else {
                $msg .= "File upload failed.";
                return false;
            }
        }
    }

    function copyfile($file)
    {
        $mainframe = JFactory::getApplication();

        jimport('joomla.filesystem.file');
        $filename = JFile::makeSafe($file);

        $path = COM_SEMINARMAN_CVFILEPATH . DS;

        $filename_new = $this->updateFilename($filename);
        $filename_new = seminarman_upload::sanitize($path, $filename_new);

        $src = COM_SEMINARMAN_UPLOADEDCVFILEPATH . DS . $filename;
        $dst = COM_SEMINARMAN_CVFILEPATH . DS . $filename_new;
        if (JFile::copy($src, $dst)) {
            return $filename_new;
        }
        return false;
    }
    function updateFilename($filename)
    {
        $mainframe = JFactory::getApplication();

        jimport('joomla.filesystem.file');

        $filename = preg_replace("/^[.]*/", '', $filename);
        $filename = preg_replace("/[.]*$/", '', $filename);

        $lastdotpos = strrpos($filename, '.');

        $chars = '[^0-9a-zA-Z()_-]';
        $filename = strtolower(preg_replace("/$chars/", '_', $filename));

        $beforedot = substr($filename, 0, $lastdotpos);
        $afterdot = substr($filename, $lastdotpos + 1);

        $filename_new = substr($beforedot, 0, (strlen($beforedot) - 11)) . '.' . $afterdot;
        return $filename_new;
    }

    function sendemail($emaildata, $emailTemplate = 0, $attachment = '')
    {
        $mainframe = JFactory::getApplication();
        $db = JFactory::getDBO();

        if ($emailTemplate != 0) {
            $emailCond = " AND id=" . $emailTemplate;
        } else {
        	$emailCond = " AND isdefault=1";
        }

        $query = "SELECT * FROM " . $db->quoteName('#__seminarman_emailtemplate') . " WHERE (templatefor=0".  $emailCond . ")";
        $db->setQuery($query);
        $template = $db->loadObject();
        if ($template) {
            $msgSubject = $template->subject;
            $msgBody = $template->body;
            $msgRecipient = $template->recipient;
            $msgRecipientBCC = $template->bcc;
            
            if (!$this->sendEmailToUserApplication($emaildata, $msgSubject, $msgBody, $msgRecipient, $msgRecipientBCC, $attachment))
            	return false;
            return true;
        }
        return false;
    }

    function sendEmailToUserApplication($emaildata, $msgSubject, $msgBody, $msgRecipient, $msgRecipientBCC, $attachment='')
    {
    	if (empty($msgRecipient))
    		return False;
    	
        $mainframe = JFactory::getApplication();
        $db = JFactory::getDBO();
        $message = JFactory::getMailer();
        $config = JFactory::getConfig();
        $params = JComponentHelper::getParams('com_seminarman');

        $query = 'SELECT app.*, c.reference_number, c.title AS course, c.price AS course_price_orig, c.code, c.introtext, c.fulltext, c.capacity, c.location, c.url, c.id AS course_id, app.price_per_attendee, app.price_total, app.price_vat, tut.id AS tutor_id, tut.title AS tutor, tut.salutation AS tutor_salutation, tut.firstname AS tutor_first_name, tut.lastname AS tutor_last_name, tut.other_title AS tutor_other_title, gr.title AS atgroup, gr.description AS atgroup_desc, ex.title AS experience_level, ex.description AS experience_level_desc' .
            ' FROM #__seminarman_application AS app' .
            ' LEFT JOIN #__seminarman_courses AS c ON c.id = app.course_id' .
            ' LEFT JOIN #__seminarman_tutor AS tut ON tut.id = c.tutor_id' .
            ' LEFT JOIN #__seminarman_atgroup AS gr ON gr.id = c.id_group' .
            ' LEFT JOIN #__seminarman_experience_level AS ex ON ex.id = c.id_experience_level' .
            ' WHERE app.id = ' . $emaildata['applicationid'];

        $db->setQuery($query);
        $queryResult = $db->loadObject();
        if ($queryResult) {        	
        	// what is the below for? not my codes
            $userQuery = "SELECT name, email FROM " . $db->quoteName('#__users') . "
			WHERE id = " . $emaildata['user_id'];

            $db->setQuery($userQuery);
            $user = $db->loadObject();

            // what is this (below)? not my codes
            $tutorQuery = "SELECT name, email FROM " . $db->quoteName('#__users') . "
			WHERE id = " . $queryResult->user_id;

            $db->setQuery($tutorQuery);
            $tutor = $db->loadObject();
            // what is above?

            // tutor custom fields
            $query_tutor_custom = 'SELECT f.fieldcode, ct.value FROM `#__seminarman_fields_values_tutors` AS ct'.
            		' LEFT JOIN `#__seminarman_fields` AS f ON ct.field_id = f.id'.
            		' WHERE ct.tutor_id = '. $queryResult->tutor_id . ' AND f.published = ' . $db->Quote('1');
            $db->setQuery($query_tutor_custom);
            $tutor_customs = $db->loadAssocList();
            
            for ($i = 0; $i < count($tutor_customs); $i++) {
            	$msgSubject = str_replace('{' . strtoupper($tutor_customs[$i]['fieldcode']) . '}', $tutor_customs[$i]['value'],
            			$msgSubject);
            	$msgBody = str_replace('{' . strtoupper($tutor_customs[$i]['fieldcode']) . '}', $tutor_customs[$i]['value'],
            			$msgBody);
            }
            
            // app custom fields (it MUST be here after tutor customs, otherwise you have problems; i don't think the below codes are well done, but it works. they are not my codes.)
            $query = 'SELECT field.*, value.value ' . 'FROM ' . $db->quoteName('#__seminarman_fields') .
                ' AS field ' . 'LEFT JOIN ' . $db->quoteName('#__seminarman_fields_values') .
                ' AS value ' . 'ON field.id=value.field_id AND value.applicationid=' . $emaildata['applicationid'] .
                ' ' . 'WHERE field.published=' . $db->Quote('1') . ' ' .
                'ORDER BY field.ordering';
            $this->_db->setQuery($query);
            $fields = $db->loadAssocList();

            for ($i = 0; $i < count($fields); $i++) {
                $msgSubject = str_replace('{' . strtoupper($fields[$i]['fieldcode']) . '}', $fields[$i]['value'],
                    $msgSubject);
                $msgBody = str_replace('{' . strtoupper($fields[$i]['fieldcode']) . '}', $fields[$i]['value'],
                    $msgBody);
            }

            // calculate and format price
            $lang = JFactory::getLanguage();
            
		    $price_orig = $queryResult->course_price_orig;
		    $price_booking = $queryResult->price_per_attendee;
		    $quantity = $queryResult->attendees;
		    $tax_rate = $queryResult->price_vat / 100.0;
		    $price_total_orig = $price_orig * $quantity;
		    $price_total_booking = $queryResult->price_total;
		    $price_orig_with_tax = $price_orig * (1 + $tax_rate);
		    $price_booking_with_tax = $price_booking * (1 + $tax_rate);
		    $price_total_orig_with_tax = $price_total_orig * (1 + $tax_rate);
		    $price_total_booking_with_tax = $price_total_booking * (1 + $tax_rate);
		    $tax_orig = $price_total_orig * $tax_rate;
		    $tax_booking = $price_total_booking * $tax_rate;            
            
            $old_locale = setlocale(LC_NUMERIC, NULL);
            setlocale(LC_NUMERIC, $lang->getLocale());
            // if (doubleval($price_total_orig) == doubleval($price_total_booking)) {
            //    $queryResult->price_per_attendee = JText::sprintf('%.2f', $price_booking);
            //    $queryResult->price_total = JText::sprintf('%.2f', $price_total_booking);
            //    $queryResult->price_vat_percent = $queryResult->price_vat;
            //    $queryResult->price_per_attendee_vat = JText::sprintf('%.2f', $price_booking_with_tax);
            //    $queryResult->price_total_vat = JText::sprintf('%.2f', $price_total_booking_with_tax);
            //    $queryResult->price_vat = JText::sprintf('%.2f', $tax_booking);
            // } else {
            //    $queryResult->price_per_attendee = '<s>' . JText::sprintf('%.2f', $price_orig) . '</s> ' . JText::sprintf('%.2f', $price_booking);
            //    $queryResult->price_total = '<s>' . JText::sprintf('%.2f', $price_total_orig) . '</s> ' . JText::sprintf('%.2f', $price_total_booking);
            //    $queryResult->price_vat_percent = $queryResult->price_vat;
            //    $queryResult->price_per_attendee_vat = '<s>' . JText::sprintf('%.2f', $price_orig_with_tax) . '</s> ' . JText::sprintf('%.2f', $price_booking_with_tax);
            //    $queryResult->price_total_vat = '<s>' . JText::sprintf('%.2f', $price_total_orig_with_tax) . '</s> ' . JText::sprintf('%.2f', $price_total_booking_with_tax);
            //    $queryResult->price_vat = JText::sprintf('%.2f', $tax_booking);            	
            // }                        
            // $queryResult->price_vat_percent = $queryResult->price_vat;
            // $queryResult->price_per_attendee_vat = JText::sprintf('%.2f', (($queryResult->price_per_attendee / 100.0) * $queryResult->price_vat) + $queryResult->price_per_attendee);
            // $queryResult->price_total_vat = JText::sprintf('%.2f', (($queryResult->price_total / 100.0) * $queryResult->price_vat) + $queryResult->price_total);
            // $queryResult->price_vat = JText::sprintf('%.2f', ($queryResult->price_total / 100.0) * $queryResult->price_vat_percent);                        
            // $queryResult->price_per_attendee = JText::sprintf('%.2f', $queryResult->price_per_attendee);
            // $queryResult->price_total = JText::sprintf('%.2f', $queryResult->price_total);
            
            $queryResult->price_per_attendee = JText::sprintf('%.2f', round($price_orig, 2));
            $queryResult->price_total = JText::sprintf('%.2f', round($price_total_orig,2));
            $queryResult->price_vat_percent = $queryResult->price_vat;
            $queryResult->price_per_attendee_vat = JText::sprintf('%.2f', round($price_orig_with_tax, 2));
            $queryResult->price_total_vat = JText::sprintf('%.2f', round($price_total_booking_with_tax, 2));
            $queryResult->price_vat = JText::sprintf('%.2f', round($tax_booking, 2));
            $queryResult->price_total_discount = JText::sprintf('%.2f', round(($price_total_orig - $price_total_booking), 2));
            $queryResult->price_total_orig_vat = JText::sprintf('%.2f', round($price_total_orig_with_tax, 2));
            
            $queryResult->price_booking_single = JText::sprintf('%.2f', round($price_booking, 2));
            $queryResult->price_booking_total = JText::sprintf('%.2f', round($price_total_booking, 2));
            
            setlocale(LC_NUMERIC, $old_locale);
            
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
            	$COURSE_START_WEEKDAY = (!empty($emaildata['start_date'])) ? strtr(date('l', strtotime($emaildata['start_date'])), $trans) : '';
            } else {
            	$COURSE_START_WEEKDAY = (!empty($emaildata['start_date'])) ? date('l', strtotime($emaildata['start_date'])) : '';
            }
            
            // first session infos
            $sql = 'SELECT * FROM #__seminarman_sessions'
            . ' WHERE published = 1'
            . ' AND courseid = ' . $queryResult->course_id
            . ' ORDER BY session_date';
            $db->setQuery($sql);
            $course_sessions = $db->loadObjectList();
            
            if(!empty($course_sessions)){
            	$COURSE_FIRST_SESSION_TITLE = $course_sessions[0]->title;
            	$COURSE_FIRST_SESSION_CLOCK = date('H:i', strtotime($course_sessions[0]->start_time)) . ' - ' . date('H:i', strtotime($course_sessions[0]->finish_time));
            	$COURSE_FIRST_SESSION_DURATION = $course_sessions[0]->duration;
            	$COURSE_FIRST_SESSION_ROOM = $course_sessions[0]->session_location;
            	$COURSE_FIRST_SESSION_COMMENT = $course_sessions[0]->description;
            } else {
            	$COURSE_FIRST_SESSION_TITLE = '';
            	$COURSE_FIRST_SESSION_CLOCK = '';
            	$COURSE_FIRST_SESSION_DURATION = '';
            	$COURSE_FIRST_SESSION_ROOM = '';
            	$COURSE_FIRST_SESSION_COMMENT = '';
            }

            if (!empty( $queryResult->title )) $queryResult->title .= ' ';

            $msgSubject = str_replace('{ADMIN_CUSTOM_RECIPIENT}', $params->get('component_email'), $msgSubject);
            $msgSubject = str_replace('{ATTENDEES_TOTAL}', $queryResult->attendees, $msgSubject);
            $msgSubject = str_replace('{TITLE}', $queryResult->title, $msgSubject);
            $msgSubject = str_replace('{SALUTATION}', $queryResult->salutation, $msgSubject);
            $msgSubject = str_replace('{FIRSTNAME}', $queryResult->first_name, $msgSubject);
            $msgSubject = str_replace('{LASTNAME}', $queryResult->last_name, $msgSubject);
            $msgSubject = str_replace('{EMAIL}', $queryResult->email, $msgSubject);
            // $msgSubject = str_replace('{ATTENDEES_TOTAL}', $queryResult->attendees, $msgSubject);
            $msgSubject = str_replace('{COURSE_ID}', $queryResult->course_id, $msgSubject);
            $msgSubject = str_replace('{COURSE_TITLE}', $queryResult->course, $msgSubject);
            $msgSubject = str_replace('{COURSE_CODE}', $queryResult->code, $msgSubject);
            $msgSubject = str_replace('{COURSE_INTROTEXT}', $queryResult->introtext, $msgSubject);
            $msgSubject = str_replace('{COURSE_FULLTEXT}', $queryResult->fulltext, $msgSubject);
            $msgSubject = str_replace('{COURSE_CAPACITY}', $queryResult->capacity, $msgSubject);
            $msgSubject = str_replace('{COURSE_LOCATION}', $queryResult->location, $msgSubject);
            $msgSubject = str_replace('{COURSE_URL}', $queryResult->url, $msgSubject);
            $msgSubject = str_replace('{PRICE_PER_ATTENDEE}', $queryResult->price_per_attendee, $msgSubject);
            $msgSubject = str_replace('{PRICE_PER_ATTENDEE_VAT}', $queryResult->price_per_attendee_vat, $msgSubject);
            $msgSubject = str_replace('{PRICE_TOTAL}', $queryResult->price_total, $msgSubject);
            $msgSubject = str_replace('{PRICE_TOTAL_VAT}', $queryResult->price_total_vat, $msgSubject);
            $msgSubject = str_replace('{PRICE_VAT_PERCENT}', $queryResult->price_vat_percent, $msgSubject);
            $msgSubject = str_replace('{PRICE_VAT}', $queryResult->price_vat, $msgSubject);
            $msgSubject = str_replace('{PRICE_TOTAL_DISCOUNT}', $queryResult->price_total_discount, $msgSubject);
            $msgSubject = str_replace('{PRICE_TOTAL_ORIG_VAT}', $queryResult->price_total_orig_vat, $msgSubject);
            $msgSubject = str_replace('{PRICE_REAL_BOOKING_SINGLE}', $queryResult->price_booking_single, $msgSubject);
            $msgSubject = str_replace('{PRICE_REAL_BOOKING_TOTAL}', $queryResult->price_booking_total, $msgSubject);
            $msgSubject = str_replace('{PRICE_GROUP_ORDERED}', $queryResult->pricegroup, $msgSubject);
            $msgSubject = str_replace('{COURSE_START_DATE}', JFactory::getDate($emaildata['start_date'])->format(JText::_('COM_SEMINARMAN_DATE_FORMAT1')), $msgSubject);
            $msgSubject = str_replace('{COURSE_FINISH_DATE}', JFactory::getDate($emaildata['finish_date'])->format(JText::_('COM_SEMINARMAN_DATE_FORMAT1')), $msgSubject);
            $msgSubject = str_replace('{COURSE_START_TIME}', (!empty($emaildata['start_time'])) ? date('H:i', strtotime($emaildata['start_time'])) : '', $msgSubject);
            $msgSubject = str_replace('{COURSE_FINISH_TIME}', (!empty($emaildata['finish_time'])) ? date('H:i', strtotime($emaildata['finish_time'])) : '', $msgSubject);
            $msgSubject = str_replace('{TUTOR}', $queryResult->tutor, $msgSubject);
            $msgSubject = str_replace('{TUTOR_FIRSTNAME}', $queryResult->tutor_first_name, $msgSubject);
            $msgSubject = str_replace('{TUTOR_LASTNAME}', $queryResult->tutor_last_name, $msgSubject);
            $msgSubject = str_replace('{TUTOR_SALUTATION}', $queryResult->tutor_salutation, $msgSubject);
            $msgSubject = str_replace('{TUTOR_OTHER_TITLE}', $queryResult->tutor_other_title, $msgSubject);
            $msgSubject = str_replace('{GROUP}', $queryResult->atgroup, $msgSubject);
            $msgSubject = str_replace('{GROUP_DESC}', $queryResult->atgroup_desc, $msgSubject);
            $msgSubject = str_replace('{EXPERIENCE_LEVEL}', $queryResult->experience_level, $msgSubject);
            $msgSubject = str_replace('{EXPERIENCE_LEVEL_DESC}', $queryResult->experience_level_desc, $msgSubject);
            $msgSubject = str_replace('{COURSE_START_WEEKDAY}', $COURSE_START_WEEKDAY, $msgSubject);
            $msgSubject = str_replace('{COURSE_FIRST_SESSION_TITLE}', $COURSE_FIRST_SESSION_TITLE, $msgSubject);
            $msgSubject = str_replace('{COURSE_FIRST_SESSION_CLOCK}', $COURSE_FIRST_SESSION_CLOCK, $msgSubject);
            $msgSubject = str_replace('{COURSE_FIRST_SESSION_DURATION}', $COURSE_FIRST_SESSION_DURATION, $msgSubject);
            $msgSubject = str_replace('{COURSE_FIRST_SESSION_ROOM}', $COURSE_FIRST_SESSION_ROOM, $msgSubject);
            $msgSubject = str_replace('{COURSE_FIRST_SESSION_COMMENT}', $COURSE_FIRST_SESSION_COMMENT, $msgSubject);

            $msgBody = str_replace('{ADMIN_CUSTOM_RECIPIENT}', $params->get('component_email'), $msgBody);
            $msgBody = str_replace('{ATTENDEES_TOTAL}', $queryResult->attendees, $msgBody);
            $msgBody = str_replace('{SALUTATION}', $queryResult->salutation, $msgBody);
            $msgBody = str_replace('{TITLE}', $queryResult->title, $msgBody);
            $msgBody = str_replace('{FIRSTNAME}', $queryResult->first_name, $msgBody);
            $msgBody = str_replace('{LASTNAME}', $queryResult->last_name, $msgBody);
            $msgBody = str_replace('{EMAIL}', $queryResult->email, $msgBody);
            $msgBody = str_replace('{COURSE_ID}', $queryResult->course_id, $msgBody);
            $msgBody = str_replace('{COURSE_TITLE}', $queryResult->course, $msgBody);
            $msgBody = str_replace('{COURSE_CODE}', $queryResult->code, $msgBody);
            $msgBody = str_replace('{COURSE_INTROTEXT}', $queryResult->introtext, $msgBody);
            $msgBody = str_replace('{COURSE_FULLTEXT}', $queryResult->fulltext, $msgBody);
            $msgBody = str_replace('{COURSE_CAPACITY}', $queryResult->capacity, $msgBody);
            $msgBody = str_replace('{COURSE_LOCATION}', $queryResult->location, $msgBody);
            $msgBody = str_replace('{COURSE_URL}', $queryResult->url, $msgBody);
            $msgBody = str_replace('{PRICE_PER_ATTENDEE}', $queryResult->price_per_attendee, $msgBody);
            $msgBody = str_replace('{PRICE_PER_ATTENDEE_VAT}', $queryResult->price_per_attendee_vat, $msgBody);
            $msgBody = str_replace('{PRICE_TOTAL}', $queryResult->price_total, $msgBody);
            $msgBody = str_replace('{PRICE_TOTAL_VAT}', $queryResult->price_total_vat, $msgBody);
            $msgBody = str_replace('{PRICE_VAT_PERCENT}', $queryResult->price_vat_percent, $msgBody);
            $msgBody = str_replace('{PRICE_VAT}', $queryResult->price_vat, $msgBody);
            $msgBody = str_replace('{PRICE_TOTAL_DISCOUNT}', $queryResult->price_total_discount, $msgBody);
            $msgBody = str_replace('{PRICE_TOTAL_ORIG_VAT}', $queryResult->price_total_orig_vat, $msgBody);
            $msgBody = str_replace('{PRICE_REAL_BOOKING_SINGLE}', $queryResult->price_booking_single, $msgBody);
            $msgBody = str_replace('{PRICE_REAL_BOOKING_TOTAL}', $queryResult->price_booking_total, $msgBody);
            $msgBody = str_replace('{PRICE_GROUP_ORDERED}', $queryResult->pricegroup, $msgBody);
            //$msgBody = str_replace('{COURSE_START_DATE}', strftime("%a, %d %B %Y", strtotime($emaildata['start_date'])), $msgBody);
            $msgBody = str_replace('{COURSE_START_DATE}', JFactory::getDate($emaildata['start_date'])->format(JText::_('COM_SEMINARMAN_DATE_FORMAT1')), $msgBody);
            //$msgBody = str_replace('{COURSE_FINISH_DATE}', strftime("%a, %d %B %Y", strtotime($emaildata['finish_date'])), $msgBody);
            $msgBody = str_replace('{COURSE_FINISH_DATE}', JFactory::getDate($emaildata['finish_date'])->format(JText::_('COM_SEMINARMAN_DATE_FORMAT1')), $msgBody); 
            $msgBody = str_replace('{COURSE_START_TIME}', (!empty($emaildata['start_time'])) ? date('H:i', strtotime($emaildata['start_time'])) : '', $msgBody);
            $msgBody = str_replace('{COURSE_FINISH_TIME}', (!empty($emaildata['finish_time'])) ? date('H:i', strtotime($emaildata['finish_time'])) : '', $msgBody);
            $msgBody = str_replace('{TUTOR}', $queryResult->tutor, $msgBody);
            $msgBody = str_replace('{TUTOR_FIRSTNAME}', $queryResult->tutor_first_name, $msgBody);
            $msgBody = str_replace('{TUTOR_LASTNAME}', $queryResult->tutor_last_name, $msgBody);
            $msgBody = str_replace('{TUTOR_SALUTATION}', $queryResult->tutor_salutation, $msgBody);
            $msgBody = str_replace('{TUTOR_OTHER_TITLE}', $queryResult->tutor_other_title, $msgBody);
            $msgBody = str_replace('{GROUP}', $queryResult->atgroup, $msgBody);
            $msgBody = str_replace('{GROUP_DESC}', $queryResult->atgroup_desc, $msgBody);
            $msgBody = str_replace('{EXPERIENCE_LEVEL}', $queryResult->experience_level, $msgBody);
            $msgBody = str_replace('{EXPERIENCE_LEVEL_DESC}', $queryResult->experience_level_desc, $msgBody);
            $msgBody = str_replace('{COURSE_START_WEEKDAY}', $COURSE_START_WEEKDAY, $msgBody);
            $msgBody = str_replace('{COURSE_FIRST_SESSION_TITLE}', $COURSE_FIRST_SESSION_TITLE, $msgBody);
            $msgBody = str_replace('{COURSE_FIRST_SESSION_CLOCK}', $COURSE_FIRST_SESSION_CLOCK, $msgBody);
            $msgBody = str_replace('{COURSE_FIRST_SESSION_DURATION}', $COURSE_FIRST_SESSION_DURATION, $msgBody);
            $msgBody = str_replace('{COURSE_FIRST_SESSION_ROOM}', $COURSE_FIRST_SESSION_ROOM, $msgBody);
            $msgBody = str_replace('{COURSE_FIRST_SESSION_COMMENT}', $COURSE_FIRST_SESSION_COMMENT, $msgBody);

            $msgRecipient = str_replace('{EMAIL}', $queryResult->email, $msgRecipient);
            $msgRecipient = str_replace('{ADMIN_CUSTOM_RECIPIENT}', $params->get('component_email'), $msgRecipient);
            
            $msgRecipients = explode(",", $msgRecipient);
            
            // $senderEmail = $config->get('mailfrom');
            // $senderName = $config->get('fromname');
            $senderEmail = $config->get('mailfrom');
            $senderName = $config->get('fromname');
            $message->addRecipient($msgRecipients);
            if (!empty($msgRecipientBCC))
            {
            	$msgRecipientBCC = str_replace('{EMAIL}', $queryResult->email, $msgRecipientBCC);
            	$msgRecipientBCC = str_replace('{ADMIN_CUSTOM_RECIPIENT}', $params->get('component_email'), $msgRecipientBCC);
            	$message->addBCC(explode(",", $msgRecipientBCC));
            }            

            $message->setSubject($msgSubject);
            $message->setBody($msgBody);
            $sender = array($senderEmail, $senderName);
            $message->setSender($sender);
            $message->IsHTML(true);
            
            if (! empty($attachment))
            	$message->addAttachment($attachment);

            $sent = $message->send();
            return $sent;
            
        }
    }

    function saveCustomfields($applicationId, $userId, $fields)
    {
        $db = $this->getDBO();

        foreach ($fields as $id => $value) {
        	
        	$q = 'REPLACE INTO `#__seminarman_fields_values`'.
        	     ' (applicationid, user_id, field_id, value)'.
        	     ' VALUES ('.(int)$applicationId.','.(int)$userId.','.(int)$id.','.$db->quote($value).')';
        	$db->setQuery($q);
        	$db->query();
        	
        	$user = JFactory::getUser();
        	$q = 'REPLACE INTO `#__seminarman_fields_values_users`'.
        	     ' (user_id, fieldcode, value)'.
        	     ' VALUES ('.(int)$userId.',(SELECT fieldcode FROM `#__seminarman_fields` WHERE id='.(int)$id.'),'.$db->quote($value).')';
        	 
        	$db->setQuery($q);
        	$db->query();
        }
    }

    function getAdminEmails()
    {
        $emails = '';
        $db = $this->getDBO();

        $query = 'SELECT ' . $db->quoteName('email') . ' FROM ' . $db->quoteName('#__users') .
            ' WHERE ' . $db->quoteName('gid') . '=' . $db->quote(24) . ' OR ' . $db->
            quoteName('gid') . '=' . $db->Quote(25);

        $db->setQuery($query);
        // $emails = $db->loadColumn(); 
        $emails = $db->loadColumn();

        return $emails;
    }

    function getCurrentBookings()
    {
        $mainframe = JFactory::getApplication();
        $params = $mainframe->getParams();
        if ($params->get('current_capacity') == 1) {
            $statusTopLimit = 3;
            $statusBottomLimit = -1;
        }
        if ($params->get('current_capacity') == 2) {
            $statusTopLimit = 3;
            $statusBottomLimit = 0;
        }

        $db = JFactory::getDBO();
        $sql = 'SELECT SUM(b.attendees)' . ' FROM #__seminarman_application AS b' .
            ' WHERE b.published = 1' . ' AND b.course_id = ' . $this->_course_id .
            ' AND b.status <' . $statusTopLimit = 3 . ' AND b.status >' . $statusBottomLimit;
        $db->setQuery($sql);
        $current_bookings = $db->loadResult();
        return $current_bookings;
    }
    
    function getCurrentBookingsForUser($user_id = 0)
    {
    	if ($user_id == 0)
    		return 0;
    	
    	$db = JFactory::getDBO();
    	$q = 'SELECT COUNT(id) FROM `#__seminarman_application`'.
    	          ' WHERE user_id = '. (int)$user_id.
    	          ' AND course_id = ' . $this->_course_id .
    	          ' AND published = 1 AND status < 3';
    	$db->setQuery($q);
    	return (int)$db->loadResult();
    }


	function sendRegistrationEmail($user, $password)
	{
		$config	= JFactory::getConfig();
		$params = JComponentHelper::getParams('com_users');
		

		$subject = JText::sprintf('COM_SEMINARMAN_EMAIL_ACCOUNT_DETAILS',
			$user->name,
			$config->get('sitename')
		);
		
		if ($params->get('useractivation') == 2)
			$body = JText::sprintf('COM_SEMINARMAN_EMAIL_REGISTERED_WITH_ADMIN_ACTIVATION_BODY', 
				$user->name,
				$config->get('sitename'),
				JUri::base().'index.php?option=com_users&task=registration.activate&token='.$user->activation,
				JUri::base(),
				 $user->username, 
				 $password
			);
		else if ($params->get('useractivation') == 1)
			$body = JText::sprintf('COM_SEMINARMAN_EMAIL_REGISTERED_WITH_ACTIVATION_BODY',
				$user->name,
				$config->get('sitename'),
				JUri::base().'index.php?option=com_users&task=registration.activate&token='.$user->activation,
				JUri::base(),
				$user->username,
				$password
			);
		else
			$body = JText::sprintf('COM_SEMINARMAN_EMAIL_REGISTERED_BODY',
				$user->name,
				$config->get('sitename'),
				JUri::base()
			);
		
		$message = JFactory::getMailer();
		$message->addRecipient($user->email);
		$message->addBcc($config->get('mailfrom'));
		$message->setSubject(html_entity_decode($subject, ENT_QUOTES));
		$message->setBody($body);
		$message->setSender(array($config->get('mailfrom'), $config->get('fromname')));
		$message->IsHTML(false);
		
		return $message->send();
	}
	
    /**
    * returns an key value array that can be used to replace
    * the fields in a pdf template with actual values
    * @param $applicationid (int)
    */
    function getFieldValuesForTemplate($applicationid)
    {
    	$db = JFactory::getDBO();
    	$data = array();
    	
    	// application data
    	$db->setQuery('SELECT'.
                          ' a.invoice_number AS `INVOICE_NUMBER`,'.
                          ' a.date AS `INVOICE_DATE`,'.
                          ' a.attendees AS `ATTENDEES_TOTAL`,'.
                          ' a.salutation AS `SALUTATION`,'.
                          ' a.title AS `TITLE`,'.
                          ' a.first_name AS `FIRSTNAME`,'.
  	                      ' a.last_name AS `LASTNAME`,'.
  	                      ' a.email AS `EMAIL`,'.
  	                      ' a.price_per_attendee AS `PRICE_PER_ATTENDEE`,'.
  	                      ' a.price_total AS `PRICE_TOTAL`,'.
   	                      ' a.price_vat AS `PRICE_VAT_PERCENT`,'.
    			          ' a.pricegroup AS `PRICE_GROUP_ORDERED`,'.
    			          ' a.status AS `PAYMENT_STATUS`,'.
   	                      ' c.code AS `COURSE_CODE`,'.
   	                      ' c.title AS `COURSE_TITLE`,'.
   	                      ' c.capacity AS `COURSE_CAPACITY`,'.
   	                      ' c.location AS `COURSE_LOCATION`,'.
   	                      ' c.url AS `COURSE_URL`,'.
   	                      ' c.start_date AS `COURSE_START_DATE`,'.
   	                      ' c.finish_date AS `COURSE_FINISH_DATE`,'.
    			' c.start_time AS `COURSE_START_TIME`,'.
    			' c.finish_time AS `COURSE_FINISH_TIME`,'.
    	                  ' c.price AS `COURSE_PRICE_ORIG`,'.
    					  ' c.id AS `COURSE_ID`,'.
    			          ' t.id AS `TUTOR_ID`,'.
   	                      ' t.title AS `TUTOR`,'.
   	                      ' t.firstname AS `TUTOR_FIRSTNAME`,'.
   	                      ' t.lastname AS `TUTOR_LASTNAME`,'.
    			          ' t.salutation AS `TUTOR_SALUTATION`,'.
    			          ' t.other_title AS `TUTOR_OTHER_TITLE`,'.
   	                      ' g.title AS `GROUP`,'.
   	                      ' l.title AS `EXPERIENCE_LEVEL`'.
 	                    ' FROM `#__seminarman_application` AS a'.
  	                    ' LEFT JOIN `#__seminarman_courses` AS c ON a.course_id = c.id'.
  	                    ' LEFT JOIN `#__seminarman_tutor` AS t ON c.tutor_id = t.id'.
    	                ' LEFT JOIN `#__seminarman_atgroup` AS g ON c.id_group = g.id'.
    	                ' LEFT JOIN `#__seminarman_experience_level` AS l ON c.id_experience_level = l.id'.
  	                    ' WHERE a.id = '. (int) $applicationid );
    	$data = $db->loadAssoc();
    	
    	if ($data['PAYMENT_STATUS'] == 0) {
    		$data['PAYMENT_STATUS'] = JText::_( 'COM_SEMINARMAN_SUBMITTED' );
    	} elseif ($data['PAYMENT_STATUS'] == 1) {
    		$data['PAYMENT_STATUS'] = JText::_( 'COM_SEMINARMAN_PENDING' );
    	} elseif ($data['PAYMENT_STATUS'] == 2) {
    		$data['PAYMENT_STATUS'] = JText::_( 'COM_SEMINARMAN_PAID' );
    	} elseif ($data['PAYMENT_STATUS'] == 3) {
    		$data['PAYMENT_STATUS'] = JText::_( 'COM_SEMINARMAN_CANCELED' );
    	}
    	
    	// custom fields
    	$db->setQuery('SELECT fieldcode, value FROM `#__seminarman_fields_values` AS v'.
        	              ' LEFT JOIN `#__seminarman_fields` AS f ON v.field_id = f.id'.
        	              ' WHERE applicationid = '. (int) $applicationid );
    	foreach ($db->loadRowList() as $row)
    		$data[$row[0]] = $row[1];
    	
    	// custom tutor fields
    	$db->setQuery('SELECT f.fieldcode, ct.value FROM `#__seminarman_fields_values_tutors` AS ct'.
    			       ' LEFT JOIN `#__seminarman_fields` AS f ON ct.field_id = f.id'.
    			       ' WHERE ct.tutor_id = '. (int) $data['TUTOR_ID'] . ' AND f.published = ' . $db->Quote('1'));
    	foreach ($db->loadRowList() as $row)
    		$data[$row[0]] = $row[1]; 
    	
		// calculate and format prices
		$lang = JFactory::getLanguage();
		
		$price_orig = $data['COURSE_PRICE_ORIG'];
		$price_booking = $data['PRICE_PER_ATTENDEE'];
		$quantity = $data['ATTENDEES_TOTAL'];
		$tax_rate = $data['PRICE_VAT_PERCENT'] / 100.0;
		$price_total_orig = $price_orig * $quantity;
		$price_total_booking = $data['PRICE_TOTAL'];
		$price_orig_with_tax = $price_orig * (1 + $tax_rate);
		$price_booking_with_tax = $price_booking * (1 + $tax_rate);
		$price_total_orig_with_tax = $price_total_orig * (1 + $tax_rate);
		$price_total_booking_with_tax = $price_total_booking * (1 + $tax_rate);
		$tax_orig = $price_total_orig * $tax_rate;
		$tax_booking = $price_total_booking * $tax_rate;
		
		$old_locale = setlocale(LC_NUMERIC, NULL);
		setlocale(LC_NUMERIC, $lang->getLocale());
		// if (doubleval($price_total_orig) == doubleval($price_total_booking)) {
		//    $data['PRICE_PER_ATTENDEE'] = JText::sprintf('%.2f', $price_booking);
		//    $data['PRICE_TOTAL'] = JText::sprintf('%.2f', $price_total_booking);
		//    $data['PRICE_PER_ATTENDEE_VAT'] = JText::sprintf('%.2f', $price_booking_with_tax);
		//    $data['PRICE_TOTAL_VAT'] = JText::sprintf('%.2f', $price_total_booking_with_tax);
		//    $data['PRICE_VAT'] = JText::sprintf('%.2f', $tax_booking);
		// } else {
		//    $data['PRICE_PER_ATTENDEE'] = '<s>' . JText::sprintf('%.2f', $price_orig) . '</s> ' . JText::sprintf('%.2f', $price_booking);
		//    $data['PRICE_TOTAL'] = '<s>' . JText::sprintf('%.2f', $price_total_orig) . '</s> ' . JText::sprintf('%.2f', $price_total_booking);
		//    $data['PRICE_PER_ATTENDEE_VAT'] = '<s>' . JText::sprintf('%.2f', $price_orig_with_tax) . '</s> ' . JText::sprintf('%.2f', $price_booking_with_tax);
		//    $data['PRICE_TOTAL_VAT'] = '<s>' . JText::sprintf('%.2f', $price_total_orig_with_tax) . '</s> ' . JText::sprintf('%.2f', $price_total_booking_with_tax);
		//    $data['PRICE_VAT'] = JText::sprintf('%.2f', $tax_booking);			
		// }
		// $data['PRICE_PER_ATTENDEE_VAT'] = JText::sprintf('%.2f', (($data['PRICE_PER_ATTENDEE'] / 100.0) * $data['PRICE_VAT_PERCENT']) + $data['PRICE_PER_ATTENDEE']);
		// $data['PRICE_TOTAL_VAT'] = JText::sprintf('%.2f', (($data['PRICE_TOTAL'] / 100.0) * $data['PRICE_VAT_PERCENT']) + $data['PRICE_TOTAL']);
		// $data['PRICE_VAT'] = JText::sprintf('%.2f', ($data['PRICE_TOTAL'] / 100.0) * $data['PRICE_VAT_PERCENT']);
		// $data['PRICE_PER_ATTENDEE'] = JText::sprintf('%.2f', $data['PRICE_PER_ATTENDEE']);
		// $data['PRICE_TOTAL'] = JText::sprintf('%.2f', $data['PRICE_TOTAL']);
		$data['PRICE_PER_ATTENDEE'] = JText::sprintf('%.2f', round($price_orig, 2));
		$data['PRICE_TOTAL'] = JText::sprintf('%.2f', round($price_total_orig, 2));
		$data['PRICE_PER_ATTENDEE_VAT'] = JText::sprintf('%.2f', round($price_orig_with_tax, 2));
		$data['PRICE_TOTAL_DISCOUNT'] = JText::sprintf('%.2f', round(($price_total_orig - $price_total_booking), 2));
		$data['PRICE_VAT'] = JText::sprintf('%.2f', round($tax_booking, 2));
		$data['PRICE_TOTAL_VAT'] = JText::sprintf('%.2f', round($price_total_booking_with_tax, 2));
		$data['PRICE_TOTAL_ORIG_VAT'] = JText::sprintf('%.2f', round($price_total_orig_with_tax, 2));
		
		$data['PRICE_REAL_BOOKING_SINGLE'] = JText::sprintf('%.2f', round($price_booking, 2));
		$data['PRICE_REAL_BOOKING_TOTAL'] = JText::sprintf('%.2f', round($price_total_booking, 2));
		
		setlocale(LC_NUMERIC, $old_locale);
    	 
    	// format date
    	$data['INVOICE_DATE'] = JFactory::getDate($data['INVOICE_DATE'])->format(JText::_('COM_SEMINARMAN_DATE_FORMAT1'));
    	$data['COURSE_START_DATE'] = JFactory::getDate($data['COURSE_START_DATE'])->format(JText::_('COM_SEMINARMAN_DATE_FORMAT1'));
    	$data['COURSE_FINISH_DATE'] = JFactory::getDate($data['COURSE_FINISH_DATE'])->format(JText::_('COM_SEMINARMAN_DATE_FORMAT1'));
    	$data['COURSE_START_TIME'] = (!empty($data['COURSE_START_TIME'])) ? date('H:i', strtotime($data['COURSE_START_TIME'])) : '';
    	$data['COURSE_FINISH_TIME'] = (!empty($data['COURSE_FINISH_TIME'])) ? date('H:i', strtotime($data['COURSE_FINISH_TIME'])) : '';

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
         . ' AND courseid = ' . $data['COURSE_ID']
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
    
    function getInvoiceNumber()
    {
    	$db = JFactory::getDBO();
    	$params = JComponentHelper::getParams('com_seminarman');

    	$db->setQuery('LOCK TABLES `#__seminarman_invoice_number` WRITE');
    	$db->query();

    	$db->setQuery('UPDATE `#__seminarman_invoice_number` SET number=GREATEST(number+1,'.(int)$params->get('invoice_number_start').')');
    	$db->query();
    	
    	$db->setQuery('SELECT number FROM `#__seminarman_invoice_number`');
    	$db->query();
    	$next = $db->loadResult();
    	
    	$db->setQuery('UNLOCK TABLES');
    	$db->query();
    	
    	return $next;
    }
}