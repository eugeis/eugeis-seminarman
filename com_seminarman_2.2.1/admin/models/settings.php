<?php
/**
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
**/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class SeminarmanModelSettings extends JModel
{
    var $_pagination = null;

    var $_id = null;
    
    var $_PriceG2 = null;
    var $_PriceG3 = null;
    var $_PriceG4 = null;
    var $_PriceG5 = null;

    function __construct()
    {
        parent::__construct();

        $array = JRequest::getVar('cid', 0, '', 'array');
        $this->setId((int)$array[0]);

    }

    function setId($id)
    {

        $this->_id = $id;
        $this->_data = null;
    }

    /**
     * Function retrieves e-mail templates for course booking
     */
    function getEmailTemplates() {
        $mainframe = JFactory::getApplication();

        $query = 'SELECT * FROM #__seminarman_emailtemplate ORDER BY templatefor, id';
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }
    
    function getPdfTemplates() {
    	$mainframe = JFactory::getApplication();
    
    	$query = 'SELECT * FROM #__seminarman_pdftemplate ORDER BY templatefor, id';
    	$this->_db->setQuery($query);
    	return $this->_db->loadObjectList();
    }
    
    function getPriceG2() {
        if (empty($this->_PriceG2))
        {
            $query = 'SELECT * FROM #__seminarman_pricegroups WHERE gid = 2';
            $this->_db->setQuery($query);
            $this->_PriceG2 = $this->_db->loadObject();
        }
        return $this->_PriceG2;    	
    }
    
    function getPriceG3() {
        if (empty($this->_PriceG3))
        {
            $query = 'SELECT * FROM #__seminarman_pricegroups WHERE gid = 3';
            $this->_db->setQuery($query);
            $this->_PriceG3 = $this->_db->loadObject();
        }
        return $this->_PriceG3;     	
    }
    
    function getPriceG4() {
    	if (empty($this->_PriceG4))
    	{
    		$query = 'SELECT * FROM #__seminarman_pricegroups WHERE gid = 4';
    		$this->_db->setQuery($query);
    		$this->_PriceG4 = $this->_db->loadObject();
    	}
    	return $this->_PriceG4;
    }
    
    function getPriceG5() {
    	if (empty($this->_PriceG5))
    	{
    		$query = 'SELECT * FROM #__seminarman_pricegroups WHERE gid = 5';
    		$this->_db->setQuery($query);
    		$this->_PriceG5 = $this->_db->loadObject();
    	}
    	return $this->_PriceG5;
    }

}


?>