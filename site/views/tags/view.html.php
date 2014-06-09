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
jimport('joomla.html.parameter' );
jimport('joomla.html.pagination');

class SeminarmanViewTags extends JViewLegacy{
    function display($tpl = null)
    {
        $mainframe = JFactory::getApplication();

        $Itemid = JRequest::getInt('Itemid');
        
        $document = JFactory::getDocument();
        $menu = JFactory::getApplication()->getMenu();
        $uri = JFactory::getURI();
        $lang = JFactory::getLanguage();
        $course = $menu->getActive();
        $params = $mainframe->getParams('com_seminarman');
        $model = $this->getModel('tags');

        $limit = $mainframe->getUserStateFromRequest('com_seminarman.' . $this->getLayout() .'.limit', 'limit', $params->def('limit', 0), 'int');
        $tmpl_limit = $mainframe->getUserStateFromRequest('com_seminarman.' . $this->getLayout() .'.tmpl_limit', 'tmpl_limit', $params->def('tmpl_limit', 0), 'int');

        $document->addStyleSheet($this->baseurl . 
            '/components/com_seminarman/assets/css/seminarman.css');
        if ($lang->isRTL()){
            $document->addStyleSheet($this->baseurl .
                '/components/com_seminarman/assets/css/seminarman_rtl.css');
        }

        $document->addCustomTag('<!--[if IE]><style type="text/css">.floattext{zoom:1;}, * html #seminarman dd { height: 1%; }</style><![endif]-->');

        $params->def('page_title', $course->title);

        $pathway = $mainframe->getPathWay();
        $pathway->setItemName(1, $course->title);

        if (!$course->title){
            $document->setTitle($params->get('page_title'));
            $document->setMetadata('keywords', $params->get('page_title'));
        }

        $courses = $this->get('Data');
        $templates = $this->get('LstOfProspects');
        $tag = $this->get('Tag');
        $total = $this->get('Total');

        if (empty($tag)){
            return JError::raiseError(404, JText::sprintf('COM_SEMINARMAN_TAG_N_NOT_FOUND', $tid));
        }

        $count = count($courses);
    	for($i = 0; $i < $count; $i++){
    		$item = &$courses[$i];
    		$item->count=$i;
    		$category = $model->getCategory($item->id);
    		$item->category = $category;
    		$link = JRoute::_($item->url);
    		$item->currency_price = $params->get('currency');
    		
    		// calculate displayed price
    		if ($params->get('show_gross_price') == '1') {
    			$item->price += ($item->price / 100) * $item->vat;
    		}
    		$old_locale = setlocale(LC_NUMERIC, NULL);
    		setlocale(LC_NUMERIC, $lang->getLocale());
    		$item->price = JText::sprintf('%.2f', round($item->price, 2));
    		setlocale(LC_NUMERIC, $old_locale);
    		
    		$menuclass = 'category' . $params->get('pageclass_sfx');
    		$itemParams = new JRegistry($item->attribs);
    		
    		if (($item->url) <> 'http://'){
    			switch ($itemParams->get('target', $params->get('target'))){
    				case 1:
    					$item->link = '<a href="' . $link . '" target="_blank" class="' . $menuclass . '">' . JText::_('COM_SEMINARMAN_MORE_DETAILS'). ' ' . $this->escape($item->title) . '...</a>';
    					break;

    				case 2:
    					$item->link = "<a href=\"#\" onclick=\"javascript: window.open('" . $link . "', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550'); return false\" class=\"$menuclass\">" . JText::_('COM_SEMINARMAN_MORE_DETAILS'). ' ' . $this->escape($item->title) . "...</a>\n";
    					break;

    				default:
    					$item->link = '<a href="' . $link . '" class="' . $menuclass . '">' . JText::_('COM_SEMINARMAN_MORE_DETAILS'). ' ' . $this->escape($item->title) . '...</a>';
    					break;
    			}
    		}else
    			$item->link = null;
    		
    		switch ($item->new) {
    			case 1:
    				$item->show_new_icon = '&nbsp;&nbsp;' . JHTML::_('image', 'components/com_seminarman/assets/images/new_item.png', JText::_('COM_SEMINARMAN_NEW'));
    				break;
    			default:
    				$item->show_new_icon = '';
    				break;
    		}

    		switch ($itemParams->get('show_sale')){
    			case 1:
    				$item->show_sale_icon = '&nbsp;&nbsp;' . JHTML::_('image', 'components/com_seminarman/assets/images/sale_item.png', JText::_('COM_SEMINARMAN_SALE'));
    				break;
    			default:
    				$item->show_sale_icon = '';
    				break;
    		}
    		if ($itemParams->get('current_capacity', $params->get('current_capacity')) > 0){
    			switch ($itemParams->get('current_capacity', $params->get('current_capacity'))){
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
    			$db = JFactory::getDBO();
    			$sql = 'SELECT SUM(b.attendees)'
    			 . ' FROM #__seminarman_application AS b'
    			 . ' WHERE b.published = 1'
    			 . ' AND b.course_id = ' . $item->id
    			 . ' AND b.status > ' . $current_capacity_setting
    			 . ' AND b.status < 3';
    			$db->setQuery($sql);
    			$item->currentBookings = ($item->capacity) - ($db->loadResult());

    			if ($item->currentBookings > 0){
    				$booking_ok = True;
    			}else{
    				$booking_ok = False;
    			}
    		}else
    			$booking_ok = True;
    		
    		$user = JFactory::getUser();
    		
    		if ($booking_ok)
    			if (!$params->get('enable_multiple_bookings_per_user') && $user->id && $model->hasUserBooked($item->id))
    				$item->book_link = '<span class="centered italic">' . JText::_('COM_SEMINARMAN_ALREADY_BOOKED_SHORT') . '</span>';
    			else
    				$item->book_link = '<div class="button2-left"><div class="blank"><a href="' . JRoute::_('index.php?option=com_seminarman&view=courses&cid=' . $category->slug . '&id=' . $item->slug . '&Itemid=' . $Itemid) . '">' . JText::_('COM_SEMINARMAN_BOOK_NOW') . '</a></div></div>';
    		else
    			$item->book_link = '<span class="centered italic">' . JText::_('COM_SEMINARMAN_FULL') . '</span>';
    		 
    		$db = JFactory::getDBO();
 
    		if ($item->start_date != '0000-00-00'){
    			$item->start_date = JFactory::getDate($item->start_date)->format("j. M Y");
    		} else{
    			$item->start_date = JText::_('COM_SEMINARMAN_NOT_SPECIFIED');
    		}
    		if ($item->finish_date != '0000-00-00'){
    			$item->finish_date = JFactory::getDate($item->finish_date)->format("j. M Y");
    		}else{
    			$item->finish_date = JText::_('COM_SEMINARMAN_NOT_SPECIFIED');
    		}

    		$sql = 'SELECT * FROM #__seminarman_sessions'
    		 . ' WHERE published = 1'
    		 . ' AND courseid = ' . $item->id
    		 . ' ORDER BY ordering';
    		$db->setQuery($sql);
    		$course_sessions = $db->loadObjectList();
    		$item->count_sessions = count($course_sessions);
    		$item->course_sessions = $course_sessions;

    	}
    	
        $count = count($templates);
    	for($i = 0; $i < $count; $i++){
    		$item = &$templates[$i];
    		$item->count=$i;
    		$category = $model->getCategoryOfTemplate($item->id);
    		$item->category = $category;
    		$link = JRoute::_($item->url);
    		$item->currency_price = $params->get('currency');
    		
    		// calculate displayed price
    		if ($params->get('show_gross_price') == '1') {
    			$item->price += ($item->price / 100) * $item->vat;
    		}
    		$old_locale = setlocale(LC_NUMERIC, NULL);
    		setlocale(LC_NUMERIC, $lang->getLocale());
    		$item->price = JText::sprintf('%.2f', round($item->price, 2));
    		setlocale(LC_NUMERIC, $old_locale);
    		
    		$menuclass = 'category' . $params->get('pageclass_sfx');
    		$itemParams = new JRegistry($item->attribs);
    		
    		if (($item->url) <> 'http://'){
    			switch ($itemParams->get('target', $params->get('target'))){
    				case 1:
    					$item->link = '<a href="' . $link . '" target="_blank" class="' . $menuclass . '">' . JText::_('COM_SEMINARMAN_MORE_DETAILS'). ' ' . $this->escape($item->title) . '...</a>';
    					break;

    				case 2:
    					$item->link = "<a href=\"#\" onclick=\"javascript: window.open('" . $link . "', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550'); return false\" class=\"$menuclass\">" . JText::_('COM_SEMINARMAN_MORE_DETAILS'). ' ' . $this->escape($item->title) . "...</a>\n";
    					break;

    				default:
    					$item->link = '<a href="' . $link . '" class="' . $menuclass . '">' . JText::_('COM_SEMINARMAN_MORE_DETAILS'). ' ' . $this->escape($item->title) . '...</a>';
    					break;
    			}
    		}else
    			$item->link = null;
    		
    		switch ($itemParams->get('show_sale')){
    			case 1:
    				$item->show_sale_icon = '&nbsp;&nbsp;' . JHTML::_('image', 'components/com_seminarman/assets/images/sale_item.png', JText::_('COM_SEMINARMAN_SALE'));
    				break;
    			default:
    				$item->show_sale_icon = '';
    				break;
    		}
    	}

    	
        $lists = array();
        $lists['filter_order'] = $model->getState('filter_order');
        $lists['filter_order2'] = $model->getState('filter_order2');
        $lists['filter_order_Dir'] = $model->getState('filter_order_dir');
        $lists['filter_order_Dir2'] = $model->getState('filter_order_dir2');
        
        $lists['filter'] = JRequest::getString('filter');
        $lists['filter2'] = JRequest::getString('filter2');
        
        $experience_level[] = JHTML::_('select.option', '0', JText::_('COM_SEMINARMAN_ALL'), 'id', 'title');
        $titles = $this->get('titles');
        $experience_level = array_merge($experience_level, $titles);
        $lists['filter_experience_level'] = JHTML::_('select.genericlist', $experience_level, 'filter_experience_level', 'class="inputbox" size="1" ', 'id', 'title', JRequest::getString('filter_experience_level'));
        $lists['filter_experience_level2'] = JHTML::_('select.genericlist', $experience_level, 'filter_experience_level2', 'class="inputbox" size="1" ', 'id', 'title', JRequest::getString('filter_experience_level2'));
        
        $pageNav = new JPagination($this->get('Total'), JRequest::getInt('limitstart'), $limit );
        $pageNav->setAdditionalUrlParam('filter_order', $lists['filter_order']);
        $pageNav->setAdditionalUrlParam('filter_order_Dir', $lists['filter_order_Dir']);
        $pageNav2 = new JPagination($this->get('TotalLstOfProspects'), JRequest::getInt('tmpl_limitstart'), $tmpl_limit, 'tmpl_');
        $pageNav2->setAdditionalUrlParam('filter_order2', $lists['filter_order2']);
        $pageNav2->setAdditionalUrlParam('filter_order_Dir2', $lists['filter_order_Dir2']);
        
        $this->assign('action', $uri->toString());

        $this->assignRef('params', $params);
        $this->assignRef('courses', $courses);
        $this->assignRef('templates', $templates);
        $this->assignRef('tag', $tag);
        $this->assignRef('pageNav', $pageNav);
        $this->assignRef('pageNav2', $pageNav2);
        $this->assignRef('lists', $lists);

        parent::display($tpl);
    }
}

?>
