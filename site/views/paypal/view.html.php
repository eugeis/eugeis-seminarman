<?php
/**
 * @version $Id: 1.5.4 2009-10-15
 * @package       Course Manager
 * @subpackage    Component
 * @author        Profinvent {@link http://www.profinvent.com}
 * @copyright     (C) Profinvent - Joomla Experts
 * @license       GNU/GPL
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
class seminarmanViewpaypal extends JView
{
   function display($tpl = null)
   {
      $mainframe = JFactory::getApplication();

      if($this->getLayout() <> 'form') {
         $this->_displayForm($tpl);
         return;
      }

      parent::display($tpl);
   }

   function _displayForm($tpl)
   {
      $mainframe = JFactory::getApplication();
      jimport( 'joomla.html.parameter' );

      $pathway =& $mainframe->getPathway();
      $document =& JFactory::getDocument();
      $model =& $this->getModel();
      $user =& JFactory::getUser();
      $uri =& JFactory::getURI();
      $params = &$mainframe->getParams();

      JHTML::_('behavior.tooltip');
      JHTML::_('behavior.formvalidation');
      
      //get course id from url
      $bookingid = (int) JRequest::getVar('bookingid');
      
      //Get data from Model - course details
      $db =& JFactory::getDBO();
      $sql = 'SELECT b.*, cr.title' .
             ' FROM #__seminarman_application AS b' .
             ' LEFT JOIN #__seminarman_courses AS cr ON cr.id = b.course_id' .
             ' WHERE b.id = '.$bookingid;
      $db->setQuery($sql);
      $bookingDetails = $db->loadObject();
      $bookingDetails->bookingid = $bookingid;

   	  if ( (!$bookingid) || ($bookingDetails->email==null))
   	  	JError::raiseError( 403, JText::_('COM_SEMINARMAN_ALERTNOTAUTH') );

      //check if booking is enabled
   	  if ( ($params->get( 'enable_bookings') ) == '0' )
   	  	JError::raiseError( 403, JText::_('COM_SEMINARMAN_ALERTNOTAUTH') );
   	  
   	  $query = 'SELECT field.*, value.value'.
   	           ' FROM ' . $db->nameQuote('#__seminarman_fields') . ' AS field'.
   	           ' LEFT JOIN ' . $db->nameQuote('#__seminarman_fields_values') . ' AS value'.
   	           ' ON field.id=value.field_id AND value.applicationid=' . $bookingid .
   	           ' WHERE field.published=' . $db->Quote('1').
   	           ' ORDER BY field.ordering';
   	  
   	  $db->setQuery($query);
   	  $fields = $db->loadAssocList();
   	  
   	  // Set page title
   	  $menus   = &JSite::getMenu();
   	  $menu = $menus->getActive();
   	  
   	  // because the application sets a default page title, we need to get it
   	  // right from the menu item itself
   	  if (is_object( $menu )) {
   	  	$menu_params = new JParameter( $menu->params );
   	  	if (!$menu_params->get( 'page_title')) {
   	  		$params->set('page_title', JText::_( JText::_('COM_SEMINARMAN_ONLINE_PAYMENT') ));
   	  	}
   	  }
   	  else {
   	  	$params->set('page_title', JText::_( JText::_('COM_SEMINARMAN_ONLINE_PAYMENT') ));
   	  }
   	  
   	  $document->setTitle( $params->get( 'page_title' ) );

 	  // calculate displayed price
   	  $amount = $bookingDetails->price_per_attendee;
   	  $amount += ($amount / 100) * $bookingDetails->price_vat;
   	  
   	  $this->assignRef('username', $user->get('username'));
   	  $this->assignRef('email', $user->get('email'));
   	  $this->assignRef('userid', $user->get('id'));
   	  $this->assign('action', $uri->toString());
   	  $this->assignRef('lists', $lists);
   	  $this->assignRef('amount', $amount);
   	  $this->assignRef('bookingDetails', $bookingDetails);
   	  $this->assignRef('course_sessions', $course_sessions);
   	  $this->assignRef('courseid', $courseid);
   	  $this->assignRef('fields', $fields);
   	  $this->assignRef('params', $params);
   	  parent::display($tpl);
   }
}
?>
