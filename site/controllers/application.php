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

jimport('joomla.application.component.controller');

require_once JPATH_ROOT.'/components/com_seminarman/helpers/application.php';

require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_seminarman'.DS.'classes'.DS.'pdfdocument.php';

class seminarmanControllerApplication extends seminarmanController
{
	function save()
	{
		JRequest::checkToken('get') or jexit('Invalid Token');
		$course_id = JRequest::getVar( 'course_id' );
		$msg = JText::_( 'COM_SEMINARMAN_THANK_YOU_FOR_YOUR_APPLICATION' );
		if($course_id){
			$user = JFactory::getUser();
			$course = ApplicationHelper::loadCourse($course_id);
			$fillErrors = array();
			$application_id = ApplicationHelper::book($user, $course, $fillErrors);
			if($application_id!=null){
				if(!ApplicationHelper::isCourseOld($course)){
					$catTitles = ApplicationHelper::getCourseCategoryTitles($course_id);
					ApplicationHelper::sendemail($application_id, $user, $course, $catTitles, $fillErrors);
				}
			}else {
				$msg = join(', ', $fillErrors);
			}
		}
		$back = JRequest::getVar( 'back' );
		$this->setRedirect( $back, $msg );
	}

	function changestatus()   {
		global $ueConfig;
		$cid  = JRequest::getVar( 'cid' );
		if( JRequest::getVar( JSession::getFormToken() ) == '1' ){
			$back = JRequest::getVar( 'back' );
			if(!ApplicationHelper::isCourseOldByApplicationId($cid)){
				$this->_changestatus($cid);
				$msg = JText::_( 'COM_SEMINARMAN_STATUS_UPDATED' );
			}else{
				$msg = JText::_( 'COM_SEMINARMAN_COURSE_IN_PAST' );
			}
			$this->setRedirect( $back, $msg );
		} else {
			$conf_hash = floatval($ueConfig['reg_confirmation_hash']);
			$cidtoken  = JRequest::getVar( 'cidtoken' );
			$cidtokenVerification = md5($cid+$conf_hash);
			if($cidtoken == $cidtokenVerification) {
				if(!ApplicationHelper::isCourseOldByApplicationId($cid)){
					$msg = JText::_( 'COM_SEMINARMAN_STATUS_UPDATED' );
					$this->_changestatus($cid);
				}else{
					$msg = JText::_( 'COM_SEMINARMAN_COURSE_IN_PAST' );
				}
			}else {
				$msg = JText::_( 'COM_SEMINARMAN_OLD_LINK' );
			}
			$this->setRedirect( 'index.php', $msg  );
		}
	}

	private function _changestatus($cid) {
			
		//JArrayHelper::toInteger($cid);
		$status = JRequest::getVar('status');
		ApplicationHelper::setStatus($cid, $status);
	}
	
	function cart() {
		
		$view = $this->getView('Courses', 'html');
		$model = $this->getModel('Courses');		
		$view->setModel($model, true);		
		// $view->setLayout('cart');		
		$view->display('cart');
		
	}

	function cancel() {
		
		$view = $this->getView('Courses', 'html');
		$model = $this->getModel('Courses');
		$view->setModel($model, true);
		$view->display();
	}
}

?>
