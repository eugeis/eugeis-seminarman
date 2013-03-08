<?php
/**
 * @version $Id: 1.5.4 2009-10-15
 * @package			Course Manager
 * @subpackage		Component
 * @author			Profinvent {@link http://www.profinvent.com}
 * @copyright 		(C) Profinvent - Joomla Experts
 * @license   		GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the seminarman component
 *
 * @static
 * @package    Course Manager
 * @subpackage seminarman
 * @since 1.5.0
 */
class seminarmanViewsession extends JView
{
   function display($tpl = null)
   {
      $mainframe = JFactory::getApplication();
      
        $document = &JFactory::getDocument();
        $lang = &JFactory::getLanguage();
        
        $document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend.css');
        if ($lang->isRTL())
        	$document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend_rtl.css');
      
      require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_seminarman' . DS .
            'helpers' . DS . 'seminarman.php');

      if($this->getLayout() == 'form') {
         $this->_displayForm($tpl);
         return;
      }

      //get the session
      $session =& $this->get('data');

      if ($session->url) {
         // redirects to url if matching id found
         $mainframe->redirect($session->url);
      }

      parent::display($tpl);
   }

   function _displayForm($tpl)
   {
      $mainframe = JFactory::getApplication();

      $db      =& JFactory::getDBO();
      $uri  =& JFactory::getURI();
      $user    =& JFactory::getUser();
      $model   =& $this->getModel();
   	    $document = &JFactory::getDocument();
   	$document->addScript(JURI::base() .
   	       'components/com_seminarman/assets/js/fieldsmanipulation.js');

      $lists = array();

      //get the session
      $session  =& $this->get('data');
      $isNew      = ($session->id < 1);


      // fail if checked out not by 'me'
      if ($model->isCheckedOut( $user->get('id') )) {
         $msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_( 'The session' ), $session->title );
         $mainframe->redirect( 'index.php?option='. $option, $msg );
      }

      // Edit or Create?
      if (!$isNew)
      {
         $model->checkout( $user->get('id') );

      }
      else
      {
         // initialise new record
         $session->published = 1;
         $session->approved    = 1;
         $session->order    = 0;

      }
      

      // build the html select list for ordering
      $query = 'SELECT ordering AS value, title AS text'
         . ' FROM #__seminarman_sessions'
         . ' WHERE courseid = ' . (int) $session->courseid
         . ' ORDER BY ordering';

      $lists['ordering']         = JHTML::_('list.specificordering',  $session, $session->id, $query );

      //Get data from Model (list of published courses)
      $titles = & $this->get( 'titles' );
      // build list of courses
      $catlist[]         = JHTML::_('select.option',  '0', '- ' . JText::_( 'COM_SEMINARMAN_SELECT_COURSE' ) . ' -', 'id', 'title' );
      $catlist         = array_merge( $catlist, $titles );
   	$filter_courseid = JRequest::getVar( 'filter_courseid', 0, 'post', 'int' );
   	if (!$session->courseid>0 && $filter_courseid>0) {
   		$session->courseid = $filter_courseid;
   	}
   	    
      $lists['courseid']      = JHTML::_('select.genericlist', $catlist, 'courseid', 'class="inputbox" size="1" onChange="copyCourseTitle()"','id', 'title', $session->courseid );
	  $lists['session_date']  = JHTML::_('calendar', JHTML::_('date', $session->session_date, JText::_('COM_SEMINARMAN_DATE_FORMAT1')), 'session_date', 'session_date', JText::_('COM_SEMINARMAN_DATE_FORMAT1_ALT'));

	  // build the html select list
      $lists['published']     = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $session->published );

      //clean session data
      JFilterOutput::objectHTMLSafe( $session, ENT_QUOTES, 'description' );

      $file    = JPATH_COMPONENT.DS.'models'.DS.'session.xml';
      $params = new JParameter( $session->params, $file );

      $this->assignRef('lists',     $lists);
      $this->assignRef('session',    $session);
      $this->assignRef('params',    $params);

      parent::display($tpl);
   }
}