<?php
/**
 * @version $Id: 1.5.4 2009-10-15
 * @package			Course Manager
 * @subpackage		Component
 * @author			Profinvent {@link http://www.profinvent.com}
 * @copyright 		(C) Profinvent - Joomla Experts
 * @license   		GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
* course Table class
*
* @package		Course Manager
* @subpackage	seminarman
* @since 1.5.0
*/
class Tabletutor extends JTable
{

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db) {
		parent::__construct('#__seminarman_tutor', 'id', $db);
	}

	/**
	* Overloaded bind function
	*
	* @acces public
	* @param array $hash named array
	* @return null|string	null is operation was satisfactory, otherwise returns an error
	* @see JTable:bind
	* @since 1.5
	*/
	function bind($array, $ignore = '')
	{
		if (key_exists( 'params', $array ) && is_array( $array['params'] ))
		{
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}

		return parent::bind($array, $ignore);
	}

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 * @since 1.0
	 */
	function check()
	{

		/** check for existing name */
		$query = 'SELECT id FROM #__seminarman_tutor WHERE alias = '.$this->_db->Quote($this->alias);
		$this->_db->setQuery($query);

		$xid = intval($this->_db->loadResult());
		if ($xid && $xid != intval($this->id)) {
			$this->setError(JText::sprintf('WARNNAMETRYAGAIN', JText::_('Alias already exists')));
			$this->alias = $this->lastname . "-" . time();
			// return false;
		}

		if(empty($this->alias)) {
			$this->alias = $this->lastname;
		}
		$this->alias = JFilterOutput::stringURLSafe($this->alias);
		if(trim(str_replace('-','',$this->alias)) == '') {
			$datenow = JFactory::getDate();
			$this->alias = $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
		}

		return true;
	}
}