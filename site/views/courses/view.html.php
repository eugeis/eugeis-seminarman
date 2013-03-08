<?php
/**
*
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
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
jimport( 'joomla.html.parameter' );

class SeminarmanViewCourses extends JView{
    function display($tpl = null)
    {
        $mainframe = JFactory::getApplication();

        $document = &JFactory::getDocument();
        $user = &JFactory::getUser();
        $menus = &JSite::getMenu();
        $lang = &JFactory::getLanguage();
        $menu = $menus->getActive();
        $dispatcher = &JDispatcher::getInstance();
        $params = &$mainframe->getParams('com_seminarman');
        $uri = &JFactory::getURI();

        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
        $cid = JRequest::getInt('cid', 0);

        if ($this->getLayout() == 'form'){
            $this->_displayForm($tpl);
            return;
        }

        require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_seminarman' . DS .
            'helpers' . DS . 'seminarman.php');

        $document->addStyleSheet($this->baseurl .
            '/components/com_seminarman/assets/css/seminarman.css');
        if ($lang->isRTL()){
            $document->addStyleSheet($this->baseurl .
                '/components/com_seminarman/assets/css/seminarman_rtl.css');
        }

        $document->addCustomTag('<!--[if IE]><style type="text/css">.floattext{zoom:1;}, * html #seminarman dd { height: 1%; }</style><![endif]-->');

        if (!$user->id || $user->id == 0){
            $document = &JFactory::getDocument();
        }

        $document = &JFactory::getDocument();

        $course = &$this->get('Course');
        $course->currency_price = $params->get('currency');
        
        $price_before_vat = $course->price;
        
        // calculate displayed price
        if ($params->get('show_gross_price') == '1') {
        	$course->price += ($course->price / 100) * $course->vat;
        }
        
        $old_locale = setlocale(LC_NUMERIC, NULL);
        setlocale(LC_NUMERIC, $lang->getLocale());
        $course->price = JText::sprintf('%.2f', round($course->price, 2));
        setlocale(LC_NUMERIC, $old_locale);
                
        $attendeedata = &$this->get('attendee');

        if (($course->id == 0)){
            $id = JRequest::getInt('id', 0);
            return JError::raiseError(404, JText::sprintf('COURSE #%d NOT FOUND', $id));
        }

        if ($params->get('show_tags')){
            $tags = &$this->get('Tags');
        }

        if ($params->get('show_categories')){
            $categories = &$this->get('Categories');
        }

        if ($params->get('show_favourites')){
            $favourites = &$this->get('Favourites');
            $favoured = &$this->get('Favoured');
        }

        $files = &$this->get('Files');

        if ($params->get('trigger_onprepare_content')){
            JPluginHelper::importPlugin('content');
            $results = $dispatcher->trigger('onPrepareContent', array(&$course, &$params, $limitstart));
        }

        $cats = new seminarman_cats($cid);
        $parents = $cats->getParentlist();
        $pathway = &$mainframe->getPathWay();

        foreach ($parents as $parent){
            $pathway->addItem($this->escape($parent->title), JRoute::_('index.php?view=category&cid=' .
                    $parent->categoryslug));
        }
        $pathway->addItem($this->escape($course->title), JRoute::_('index.php?view=courses&id=' .
                $course->slug));

        if (is_object($menu)){
            $menu_params = new JParameter($menu->params);
            if (!$menu_params->get('page_title')){
                $params->set('page_title', $course->title);
            }
        }else{
            $params->set('page_title', $course->title);
        }

        if ($cid){
            $parentcat = array_pop($parents);
            $doc_title = $parentcat->title . ' - ' . $params->get('page_title');
        }else{
            $doc_title = $params->get('page_title');
        }

        $document->setTitle($doc_title);

        if ($course->meta_description){
            $document->setDescription($course->meta_description);
        }

        if ($course->meta_keywords){
            $document->setMetadata('keywords', $course->meta_keywords);
        }

        $mdata = new JParameter($course->metadata);
        $mdata = $mdata->toArray();
        foreach ($mdata as $k => $v){
            if ($v){
                $document->setMetadata($k, $v);
            }
        }
        $db = &JFactory::getDBO();

        if ($course->start_date != '0000-00-00'){
          $course->start_date = JFactory::getDate($course->start_date)->format("j. F Y");
          
        } else {
          $course->start_date = JText::_('COM_SEMINARMAN_NOT_SPECIFIED');
        }
        if ($course->finish_date != '0000-00-00'){
          $course->finish_date = JFactory::getDate($course->finish_date)->format("j. F Y");
          
          
        } else {
          $course->finish_date = JText::_('COM_SEMINARMAN_NOT_SPECIFIED');
        }

        $sql = 'SELECT * FROM #__seminarman_sessions'
         . ' WHERE published = 1'
         . ' AND courseid = ' . $course->id
         . ' ORDER BY session_date';
        $db->setQuery($sql);
        $course_sessions = $db->loadObjectList();
        $course->count_sessions = count($course_sessions);
        
        foreach ($course_sessions as $course_session)
        	if ($course_session->session_date != '0000-00-00')
        		$course_session->session_date = JFactory::getDate($course_session->session_date)->format("j. F Y");
        	else
        		$course_session->session_date = JText::_('COM_SEMINARMAN_NOT_SPECIFIED');

        $itemParams = new JParameter($course->attribs);
        $params->merge($itemParams);

        $print_link = JRoute::_('index.php?view=courses&cid=' . $course->categoryslug .
            '&id=' . $course->slug . '&pop=1&tmpl=component');

        $default = isset($attendeedata->salutationStr) ? $attendeedata->salutationStr : $attendeedata->salutation;
        $lists['salutation'] = JHTMLSeminarman::getListFromXML('Salutation', 'salutation', 0,  $default);
        // capacity check
        switch ($params->get('current_capacity')){
            // cases for a parameter
            case 1:
                $current_capacity_setting = - 1;
                break;

            case 2:
                $current_capacity_setting = 0;
                break;

            default:
                $current_capacity_setting = - 1;
                break;
        }
        // add currentbookings information
        $db = &JFactory::getDBO();
        $sql = 'SELECT SUM(b.attendees)'
         . ' FROM #__seminarman_application AS b'
         . ' WHERE b.published = 1'
         . ' AND b.course_id = ' . $course->id
         . ' AND b.status > ' . $current_capacity_setting
         . ' AND b.status < 3';
        $db->setQuery($sql);
        $course->currentAvailability = ($course->capacity) - ($db->loadResult());
        $show_application_form = 1;
        if ($params->get('current_capacity') && $course->currentAvailability < 1){
            // no more bookings can be made for the course selected
        	$show_application_form = 0;
            $mainframe->enqueueMessage(JText::_('COM_SEMINARMAN_NO_SEATS_LEFT'));
        }
        if ($course->canceled){
        	$show_application_form = 0;
            $mainframe->enqueueMessage(JText::_('COM_SEMINARMAN_COURSE_IS_CANCELED'));
        }
        JPluginHelper::importPlugin('content');
        $results = $mainframe->triggerEvent( 'onPrepareContent', array( &$course, &$params, 0 ));

        $data = new stdClass();
        $model = &$this->getModel('courses');
        $data->customfields = $model->getEditableCustomfields($attendeedata->id);
        CMFactory::load('libraries' , 'customfields');
        
        JPluginHelper::importPlugin('seminarman');
        
        // fire vmengine
        $ergebnisse = $dispatcher->trigger('onShowingCourse', array($course->id)); 
        if (!empty($ergebnisse)) {      
            $vmlink = $ergebnisse[0];
        } else {
        	$vmlink = null;
        }
        
        $this->assignRef('fields', $data->customfields ['fields']);
        $this->assignRef('course', $course);
        $this->assignRef('tags', $tags);
        $this->assignRef('categories', $categories);
        $this->assignRef('attendeedata', $attendeedata);
        $this->assignRef('favourites', $favourites);
        $this->assignRef('favoured', $favoured);
        $this->assignRef('files', $files);
        $this->assignRef('user', $user);
        $this->assignRef('params', $params);
        $this->assignRef('print_link', $print_link);
        $this->assignRef('parentcat', $parentcat);
        $this->assignRef('course_sessions', $course_sessions);
        $this->assignRef('lists', $lists);
        $this->assign('action', $uri->toString());
        $this->assignRef('vmlink', $vmlink);
        $this->assignRef('price_before_vat', $price_before_vat);
        $this->assignRef('show_application_form', $show_application_form);

        parent::display($tpl);
        
    }
    
}

?>
