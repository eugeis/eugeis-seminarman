<?php
/**
 * @version $Id: 1.5.4 2009-10-15
 * @package			Course Manager
 * @subpackage		Component
 * @author			Profinvent {@link http://www.seminarman.com}
 * @copyright 		(C) Profinvent - Joomla Experts
 * @license   		GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model');

/**
 * seminarman Component session Model
 *
 * @package    Course Manager
 * @subpackage seminarman
 * @since 1.5.0
 */
class seminarmanModelsession extends JModel
{
   /**
    * session id
    *
    * @var int
    */
   var $_id = null;

   /**
    * session data
    *
    * @var array
    */
   var $_data = null;

   /**
    * Constructor
    *
    * @since 1.5
    */
   function __construct()
   {
      parent::__construct();

      $array = JRequest::getVar('cid', array(0), '', 'array');
      $edit = JRequest::getVar('edit',true);
      if($edit)
         $this->setId((int)$array[0]);
   }

   /**
    * Method to set the session identifier
    *
    * @access  public
    * @param   int session identifier
    */
   function setId($id)
   {
      // Set session id and wipe data
      $this->_id     = $id;
      $this->_data   = null;
   }

   /**
    * Method to get a session
    *
    * @since 1.5
    */
   function &getData()
   {
      // Load the session data
      if ($this->_loadData())
      {
         // Initialize some variables
         $user = JFactory::getUser();

         // Check to see if the course is published
         if (!$this->_data->course_pub) {
            JError::raiseError( 404, JText::_("Resource Not Found") );
            return;
         }

       }
      else  $this->_initData();

      return $this->_data;
   }

   /**
    * Tests if session is checked out
    *
    * @access  public
    * @param   int   A user id
    * @return  boolean  True if checked out
    * @since   1.5
    */
   function isCheckedOut( $uid=0 )
   {
      if ($this->_loadData())
      {
         if ($uid) {
            return ($this->_data->checked_out && $this->_data->checked_out != $uid);
         } else {
            return $this->_data->checked_out;
         }
      }
   }

   /**
    * Method to checkin/unlock the session
    *
    * @access  public
    * @return  boolean  True on success
    * @since   1.5
    */
   function checkin()
   {
      if ($this->_id)
      {
         $session = $this->getTable();
         if(! $session->checkin($this->_id)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
         }
      }
      return false;
   }

   /**
    * Method to checkout/lock the session
    *
    * @access  public
    * @param   int   $uid  User ID of the user checking the article out
    * @return  boolean  True on success
    * @since   1.5
    */
   function checkout($uid = null)
   {
      if ($this->_id)
      {
         // Make sure we have a user id to checkout the article with
         if (is_null($uid)) {
            $user = JFactory::getUser();
            $uid  = $user->get('id');
         }
         // Lets get to it and checkout the thing...
         $session = $this->getTable();
         if(!$session->checkout($uid, $this->_id)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
         }

         return true;
      }
      return false;
   }

   /**
    * Method to store the session
    *
    * @access  public
    * @return  boolean  True on success
    * @since   1.5
    */
   function store($data)
   {
      $row = $this->getTable();

      // Bind the form fields to the web link table
      if (!$row->bind($data)) {
         $this->setError($this->_db->getErrorMsg());
         return false;
      }
      
      require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_seminarman' . DS . 'helpers' . DS . 'seminarman.php');
      $row->session_date = JHTMLSeminarman::localDate2DbDate($row->session_date);

      // Create the timestamp for the date
      $row->date = gmdate('Y-m-d H:i:s');

      // if new item, order last in appropriate group
      if (!$row->id) {
         $where = 'courseid = ' . (int) $row->courseid ;
         $row->ordering = $row->getNextOrder( $where );
      }

      // Make sure the web link table is valid
      if (!$row->check()) {
         $this->setError($this->_db->getErrorMsg());
         return false;
      }

      // Store the web link table to the database
      if (!$row->store()) {
         $this->setError($this->_db->getErrorMsg());
         return false;
      }
      
      return $row->id;
   }



   /**
    * Method to remove a session
    *
    * @access  public
    * @return  boolean  True on success
    * @since   1.5
    */
   function delete($cid = array())
   {
      $result = false;

      if (count( $cid ))
      {
         JArrayHelper::toInteger($cid);
         $cids = implode( ',', $cid );
         $query = 'DELETE FROM #__seminarman_sessions'
            . ' WHERE id IN ( '.$cids.' )';
         $this->_db->setQuery( $query );
         if(!$this->_db->query()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
         }
      }

      return true;
   }


   /**
    * Method to remove a single session
    *
    * @access  public
    * @return  boolean  True on success
    * @since   1.5
    */
   function deleteone($sessionid)
   {
      $result = false;

      if ($sessionid)
      {
         $query = 'DELETE FROM #__seminarman_sessions'
            . ' WHERE id = '.$sessionid.'';
         $this->_db->setQuery( $query );
         if(!$this->_db->query()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
         }
      }

      return true;
   }

   /**
    * Method to (un)publish a session
    *
    * @access  public
    * @return  boolean  True on success
    * @since   1.5
    */
   function approve($cid = array(), $approve = 1)
   {
      $user    = JFactory::getUser();

      if (count( $cid ))
      {
         JArrayHelper::toInteger($cid);
         $cids = implode( ',', $cid );

         $query = 'UPDATE #__seminarman_sessions'
            . ' SET approved = '.(int) $approved
            . ' WHERE id IN ( '.$cids.' )'
            . ' AND ( checked_out = 0 OR ( checked_out = '.(int) $user->get('id').' ) )'
         ;
         $this->_db->setQuery( $query );
         if (!$this->_db->query()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
         }
      }

      return true;
   }

   function publish($cid = array(), $publish = 1)
   {
      $user    = JFactory::getUser();

      if (count( $cid ))
      {
         JArrayHelper::toInteger($cid);
         $cids = implode( ',', $cid );

         $query = 'UPDATE #__seminarman_sessions'
            . ' SET published = '.(int) $publish
            . ' WHERE id IN ( '.$cids.' )'
            . ' AND ( checked_out = 0 OR ( checked_out = '.(int) $user->get('id').' ) )'
         ;
         $this->_db->setQuery( $query );
         if (!$this->_db->query()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
         }
      }

      return true;
   }


   /**
    * Method to move a session
    *
    * @access  public
    * @return  boolean  True on success
    * @since   1.5
    */
   function move($direction)
   {
      $row = $this->getTable();
      if (!$row->load($this->_id)) {
         $this->setError($this->_db->getErrorMsg());
         return false;
      }

      if (!$row->move( $direction, ' courseid = '.(int) $row->courseid.' AND published >= 0 ' )) {
         $this->setError($this->_db->getErrorMsg());
         return false;
      }

      return true;
   }

   /**
    * Method to move a session
    *
    * @access  public
    * @return  boolean  True on success
    * @since   1.5
    */
   function saveorder($cid = array(), $order)
   {
      $row = $this->getTable();
      $groupings = array();

      // update ordering values
      for( $i=0; $i < count($cid); $i++ )
      {
         $row->load( (int) $cid[$i] );
         // track categories
         $groupings[] = $row->courseid;

         if ($row->ordering != $order[$i])
         {
            $row->ordering = $order[$i];
            if (!$row->store()) {
               $this->setError($this->_db->getErrorMsg());
               return false;
            }
         }
      }

      // execute updateOrder for each parent group
      $groupings = array_unique( $groupings );
      foreach ($groupings as $group){
         $row->reorder('courseid = '.(int) $group);
      }

      return true;
   }

   /**
    * Method to load content session data
    *
    * @access  private
    * @return  boolean  True on success
    * @since   1.5
    */
   function _loadData()
   {
      // Lets load the content if it doesn't already exist
      if (empty($this->_data))
      {
         $query = 'SELECT w.*, cc.title AS course_title,'.
               ' cc.state AS course_pub'.
               ' FROM #__seminarman_sessions AS w' .
               ' LEFT JOIN #__seminarman_courses AS cc ON w.courseid = cc.id' .
               ' WHERE w.id = '.(int) $this->_id;
         $this->_db->setQuery($query);
         $this->_data = $this->_db->loadObject();
         return (boolean) $this->_data;
      }
      return true;
   }

   /* Method to fetch course titles
   *
   * @access public
   * @return string
   */
   function getTitles()
   {
	$db = JFactory::getDBO();
	if(JHTMLSeminarman::UserIsCourseManager()) {
 	    $sql = 'SELECT id, title'
	    . ' FROM #__seminarman_courses'
	    . ' WHERE state = 1'
	    . ' ORDER BY title';
	} else {
 	    $sql = 'SELECT id, title'
	    . ' FROM #__seminarman_courses'
	    . ' WHERE (state = 1 AND tutor_id = ' . JHTMLSeminarman::getUserTutorID() . ')'
	    . ' ORDER BY title';		
	}
	$db->setQuery($sql);
	$titles = $db->loadObjectlist();
 	return $titles;
   }


   /**
    * Method to initialise the session data
    *
    * @access  private
    * @return  boolean  True on success
    * @since   1.5
    */
   function _initData()
   {
      // Lets load the content if it doesn't already exist
      if (empty($this->_data))
      {
         $session = new stdClass();
         $session->id             = 0;
         $session->courseid          = 0;
         $session->title        = null;
         $session->alias               = null;
         $session->status              = null;
         $session->session_date               = null;
       $session->start_time               = null;
       $session->finish_time               = null;
       $session->duration               = null;
         $session->description       = null;
       $session->session_location               = null;
         $session->date           = null;
         $session->hits           = 0;
         $session->published         = 0;
         $session->checked_out       = 0;
         $session->checked_out_time  = 0;
         $session->ordering       = 0;
         $session->archived       = 0;
         $session->params            = null;
         $this->_data               = $session;
         return (boolean) $this->_data;
      }
      return true;
   }
}