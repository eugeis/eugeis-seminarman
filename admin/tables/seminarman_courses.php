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

class seminarman_courses extends JTable{

    function seminarman_courses(&$db)
    {
        parent::__construct('#__seminarman_courses', 'id', $db);
    }

    function check()
    {
        if (trim($this->title) == ''){
            $this->_error = JText::_('ADD TITLE');
            JError::raiseWarning('SOME_ERROR_CODE', $this->_error);
            return false;
        }

    	if(empty($this->alias)) {
    		$this->alias = $this->title;
    	}

    	$this->alias = JFilterOutput::stringURLSafe($this->alias);
    	if(trim(str_replace('-','',$this->alias)) == '') {
    		$datenow = JFactory::getDate();
    		$this->alias = $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
    	}

    	/** check for existing alias */
    	$query = 'SELECT id FROM #__seminarman_courses WHERE alias = '.$this->_db->Quote($this->alias);
    	$this->_db->setQuery($query);
    	$xid = intval($this->_db->loadResult());

    	if ($xid && $xid != intval($this->id)) {
    		$this->setError(JText::sprintf('WARNNAMETRYAGAIN', JText::_('Alias already exists')));
    		$datenow = JFactory::getDate();
    		$this->alias = $this->alias . $datenow->toFormat("%y%m%d%H%M%S");


    	}


    	if (!(eregi('http://', $this->url) || (eregi('https://', $this->url)) || (eregi('ftp://', $this->url)))) {
    		$this->url = 'http://'.$this->url;
    	}

        return true;
    }
}

?>