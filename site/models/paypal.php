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
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

/**
* seminarman Component paypal Model
*
* @package Course Manager
* @subpackage Content
* @since 1.5.0
*/
class seminarmanModelPaypal extends JModelLegacy{
    /**
    * Booking id
    *
    * @var int
    */
    var $_id = null;

    /**
    * PayPal trnsaction date
    *
    * @var date
    */
    var $_transaction_date = null;

    /**
    * Transaction ID
    *
    * @var integer
    */
    var $_transaction_id = null;

    /**
    * Constructor
    *
    * @since 1.5
    */
    function __construct()
    {
        parent::__construct();

        $mainframe = JFactory::getApplication();

        $config = JFactory::getConfig();

        $id = JRequest::getVar('bookingid', 0, '', 'int');
        $this->setId((int)$id);

        $transaction_id = JRequest::getVar('txn_id', 0, '', 'int');
    }

    /**
    * Method to set the booking id
    *
    * @access public
    * @param int $ Booking ID number
    */
    function setId($id)
    {
        // Set booking ID
        $this->_id = $id;
    }

    function setTransactionId($transaction_id)
    {
        // Set booking ID
        $this->_transaction_id = $transaction_id;
    }

    function updatestatus()
    {
        $db = JFactory::getDBO();
        if ($this->_id){
            $db->setQuery('UPDATE #__seminarman_application'
                 . ' SET status = 1'
                 . ' WHERE id = ' . (int) $this->_id);

            if (!$db->query()){
                $this->setError(JText::_('DATABASE_ERROR'));
                return false;
            }
            return true;
        }
        return false;
    }

    function updatestatusIPN($item_number, $txn_id)
    {
        $db = JFactory::getDBO();
        // Set booking ID
        $this->_item_number = $item_number;
        $this->_txn_id = $txn_id;
        if ($this->_item_number){
            $db->setQuery('UPDATE #__seminarman_application'
                 . ' SET status = 2'
                 . ' WHERE id = ' . (int) $this->_item_number);

            if (!$db->query()){
                $this->setError(JText::_('DATABASE_ERROR'));
                return false;
            }

            $db->setQuery('UPDATE #__seminarman_application'
                 . ' SET transaction_id = "' . $this->_txn_id . '"'
                 . ' WHERE id = ' . (int) $this->_item_number);

            if (!$db->query()){
                $this->setError(JText::_('DATABASE_ERROR'));
                return false;
            }

            return true;
        }
        return false;
    }

    function getAdmins()
    {
        $db = JFactory::getDBO();

        $db = JFactory::getDBO();
        $query = 'SELECT name, email, sendEmail' .
        ' FROM #__users' .
        ' WHERE LOWER( usertype ) = "super administrator"';
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
    }

  
}