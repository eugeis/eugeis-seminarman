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

class SeminarmanViewFavourites extends JView{
    function display($tpl = null)
    {
        $mainframe = JFactory::getApplication();
        jimport( 'joomla.html.parameter' );

        $document = &JFactory::getDocument();
        $menus = &JSite::getMenu();
        $menu = $menus->getActive();
        $params = &$mainframe->getParams('com_seminarman');
        $uri = &JFactory::getURI();
        $lang = &JFactory::getLanguage();
        $model = & $this->getModel('favourites');
        $user		=& JFactory::getUser();

        $limitstart = JRequest::getInt('limitstart');
        $limit = JRequest::getInt('limit', $params->get('course_num'));

        $document->addStyleSheet($this->baseurl .
            '/components/com_seminarman/assets/css/seminarman.css');
        if ($lang->isRTL()){
            $document->addStyleSheet($this->baseurl .
                '/components/com_seminarman/assets/css/seminarman_rtl.css');
        }

        $document->addCustomTag('<!--[if IE]><style type="text/css">.floattext{zoom:1;}, * html #seminarman dd { height: 1%; }</style><![endif]-->');

        if (is_object($menu)){
            $menu_params = new JParameter($menu->params);

            if (!$menu_params->get('page_title')){
                $params->set('page_title', JText::_('COM_SEMINARMAN_MY_FAVOURITES'));
            }
        }else{
            $params->set('page_title', JText::_('COM_SEMINARMAN_MY_FAVOURITES'));
        }

        $pathway = &$mainframe->getPathWay();
        $pathway->addItem($params->get('page_title'), JRoute::_('index.php?view=favourites'));

        $document->setTitle($params->get('page_title'));
        $document->setMetadata('keywords', $params->get('page_title'));

            if ($user->get('guest')){
                $redirectUrl = JRoute::_('index.php?option=com_seminarman&view=favourites', false);
                $redirectUrl = base64_encode($redirectUrl);
                $redirectUrl = '&return=' . $redirectUrl;
                $joomlaLoginUrl = 'index.php?option=com_users&view=login';
                $finalUrl = $joomlaLoginUrl . $redirectUrl;
                $mainframe->redirect($finalUrl, JText::_('COM_SEMINARMAN_PLEASE_LOGIN_FIRST'));
            }

        $courses = &$this->get('Data');
        $total = &$this->get('Total');


        $count = count($courses);
    	for($i = 0; $i < $count; $i++){
    		$item = &$courses[$i];
    		$item->count=$i;
    		$category = $model->getCategory($item->id);
    		// $link = JRoute::_('index.php?view=courses&cid=' . $category->slug . '&id=' . $item->slug);
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
    		
    		$itemParams = new JParameter($item->attribs);

    		$menuclass = 'category' . $params->get('pageclass_sfx');
    		if (($item->url) <> 'http://'){
    			switch ($itemParams->get('target', $params->get('target'))){
    				// cases are slightly different
    				case 1:
    					// open in a new window
    					$item->link = '<a href="' . $link . '" target="_blank" class="' . $menuclass . '">' . JText::_('COM_SEMINARMAN_MORE_DETAILS'). ' ' . $this->escape($item->title) . '...</a>';
    					break;

    				case 2:
    					// open in a popup window
    					$item->link = "<a href=\"#\" onclick=\"javascript: window.open('" . $link . "', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550'); return false\" class=\"$menuclass\">" . JText::_('More details about ') . $this->escape($item->title) . "...</a>\n";
    					break;

    				default:
    					// formerly case 2
    					// open in parent window
    					$item->link = '<a href="' . $link . '" class="' . $menuclass . '">' . JText::_('COM_SEMINARMAN_MORE_DETAILS'). ' ' . $this->escape($item->title) . '...</a>';
    					break;
    			}
    		}else{
    			$item->link = null;
    		}
    		// $item->title = '<strong>'. $this->escape($item->title) .'</strong>';
    		// check for NEW icon
    		//switch ($itemParams->get('show_new')){
    		switch ($item->new) {
    			// cases for a parameter - show new
    			case 1:
    				// display new icon
    				$item->show_new_icon = '&nbsp;&nbsp;' . JHTML::_('image', 'administrator/components/com_seminarman/assets/images/new_item.png', JText::_('COM_SEMINARMAN_NEW'));
    				break;
    			default:
    				// nothing to display
    				$item->show_new_icon = '';
    				break;
    		}
    		// check for SALE icon
    		switch ($itemParams->get('show_sale')){
    			// cases for a parameter - show sale
    			case 1:
    				// display new icon
    				// $item->show_sale_icon = '&nbsp;&nbsp;<img src="'.JPATH_COMPONENT_ADMINISTRATOR.DS.'assets'.DS.'images'.DS.'sale_item.png"/>';
    				$item->show_sale_icon = '&nbsp;&nbsp;' . JHTML::_('image', 'administrator/components/com_seminarman/assets/images/sale_item.png', JText::_('COM_SEMINARMAN_SALE'));
    				break;
    			default:
    				// nothing to display
    				$item->show_sale_icon = '';
    				break;
    		}
    		if ($itemParams->get('current_capacity', $params->get('current_capacity')) > 0){
    			switch ($itemParams->get('current_capacity', $params->get('current_capacity'))){
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
    			 . ' AND b.course_id = ' . $item->id
    			 . ' AND b.status > ' . $current_capacity_setting
    			 . ' AND b.status < 3';
    			$db->setQuery($sql);
    			$item->currentBookings = ($item->capacity) - ($db->loadResult());

    			if ($item->currentBookings > 0){
    				// create booking button
    				$item->book_link = '<div class="button2-left"><div class="blank"><a href="' . JRoute::_('index.php?view=courses&cid=' . $category->slug . '&id=' . $item->slug) . '">' . JText::_('COM_SEMINARMAN_BOOK_NOW') . '</a></div></div>';
    			}else{
    				$item->book_link = '<span class="centered italic">' . JText::_('COM_SEMINARMAN_FULL') . '</span>';
    			}
    		}else{
    			// create booking button
    			$item->book_link = '<div class="button2-left"><div class="blank"><a href="' . JRoute::_('index.php?view=courses&cid=' . $category->slug . '&id=' . $item->slug) . '">' . JText::_('COM_SEMINARMAN_BOOK_NOW') . '</a></div></div>';
    		}

    		// show sessions
    		$db = &JFactory::getDBO();
    		/*$sql = 'SELECT min(session_date) AS start_date, max(session_date) AS finish_date FROM #__seminarman_sessions'
    		 . ' WHERE published = 1'
    		 . ' AND courseid = ' . $item->id
    		 . ' ORDER BY ordering';
    		$db->setQuery($sql);
    		$course_sessions_dates = $db->loadObjectList();*/
    		
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


        $filter_order = JRequest::getCmd('filter_order', 'i.title');
        $filter_order_Dir = JRequest::getCmd('filter_order_Dir', 'ASC');
        $filter = JRequest::getString('filter');

        $lists = array();
        $lists['filter_order'] = $filter_order;
        $lists['filter_order_Dir'] = $filter_order_Dir;
        $lists['filter'] = $filter;

        $filter_experience_level = JRequest::getString('filter_experience_level');
        $filter_positiontype = JRequest::getString('filter_positiontype');

        $experience_level[] = JHTML::_('select.option', '0', JText::_('COM_SEMINARMAN_ALL'), 'id', 'title');
        $titles = &$this->get('titles');
        $experience_level = array_merge($experience_level, $titles);
        $lists['filter_experience_level'] = JHTML::_('select.genericlist', $experience_level,
            'filter_experience_level', 'class="inputbox" size="1" ', 'id', 'title', $filter_experience_level);

        jimport('joomla.html.pagination');

        $pageNav = new JPagination($total, $limitstart, $limit);
        $page = $total - $limit;

        $this->assign('action', $uri->toString());

        $this->assignRef('courses', $courses);
        $this->assignRef('category', $category);
        $this->assignRef('params', $params);
        $this->assignRef('page', $page);
        $this->assignRef('pageNav', $pageNav);
        $this->assignRef('lists', $lists);

        parent::display($tpl);
    }
}

?>