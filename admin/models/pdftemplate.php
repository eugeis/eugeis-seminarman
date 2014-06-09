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

class seminarmanModelPdftemplate extends JModelLegacy
{
    var $id = null;

    function __construct()
    {
        parent::__construct();
        $this->id = JRequest::getVar('id');
    }

    
    function getData()
    {
		if ($this->id == 0) {
			$data = new stdClass();
			$data->id = 0;
			$data->name = null;
			$data->templatefor = 0;
			$data->html = null;
			$data->srcpdf = null;
			$data->isdefault = 0;
			$data->margin_left = 0;
			$data->margin_right = 0;
			$data->margin_top = 0;
			$data->margin_bottom = 0;
			$data->paperformat = 'A4';
			$data->orientation = 'P';
			return $data;
		}
		
		$db = JFactory::getDBO();
		$db->setQuery('SELECT w.* FROM #__seminarman_pdftemplate AS w WHERE w.id='.(int)$this->id);
		return $db->loadObject();
    }

 
    function delete($cid = array())
    {
		if (count($cid))
		{
			$db = JFactory::getDBO();

			JArrayHelper::toInteger($cid);
			$cids = implode(',', $cid);
			
			/* are there still courses with this? */
			$query = 'SELECT COUNT(id) AS count, GROUP_CONCAT(id) AS ids FROM #__seminarman_courses WHERE invoice_template IN ('. $cids .') OR attlst_template IN ('. $cids .')';
			$db->setQuery($query);
			$res = $db->loadAssoc();
			if ($res['count'] > 0) {
				$this->setError(JText::sprintf('COM_SEMINARMAN_RELATED_N_RECORDS', JText::_('COM_SEMINARMAN_COURSE') .' '. $res['ids']));
				return false;
			}
			
			/* are there still templates with this? */
			$query = 'SELECT COUNT(id) AS count, GROUP_CONCAT(id) AS ids FROM #__seminarman_templates WHERE invoice_template IN ('. $cids .') OR attlst_template IN ('. $cids .')';
			$db->setQuery($query);
			$res = $db->loadAssoc();
			if ($res['count'] > 0) {
				$this->setError(JText::sprintf('COM_SEMINARMAN_RELATED_N_RECORDS', JText::_('COM_SEMINARMAN_TEMPLATE') .' '. $res['ids']));
				return false;
			}

			$query = 'DELETE FROM #__seminarman_pdftemplate WHERE id IN ('. $cids .')';
			$db->setQuery($query);
			if (!$db->query()) {
				$this->setError($db->getErrorMsg());
				return false;
			}
			$this->makeOneDefault();
        }

        return true;
    }
    
    
    function makeOneDefault()
    {
    	$db = JFactory::getDBO();
    	 
    	$db->setQuery('SELECT COUNT(id) FROM #__seminarman_pdftemplate WHERE templatefor=0 AND isdefault=1');
    	$count = $db->loadResult();
    	if ($count == 0) {
    		$db->setQuery('UPDATE #__seminarman_pdftemplate SET isdefault=1 WHERE templatefor=0 LIMIT 1');
    		$db->query();
    	}
    	
    	$db->setQuery('SELECT COUNT(id) FROM #__seminarman_pdftemplate WHERE templatefor=1 AND isdefault=1');
    	$count = $db->loadResult();
    	if ($count == 0) {
    		$db->setQuery('UPDATE #__seminarman_pdftemplate SET isdefault=1 WHERE templatefor=1 LIMIT 1');
    		$db->query();
    	}
    }


    function storeTemplate()
    {
    	$db = JFactory::getDBO();
    	 
    	$row = $this->getTable('pdftemplate');

    	$data = JRequest::get('post');
    	$data['html'] = JRequest::getVar('html', '', 'post', 'string', JREQUEST_ALLOWHTML);

    	if (!$row->bind($data))
    	{
    		$this->setError($db->getErrorMsg());
    		return false;
    	}
    	if (!$row->store())
    	{
    		$this->setError($db->getErrorMsg());
    		return false;
    	}

    	$this->makeOneDefault();

        return $row->id;
    }


	function getFields()
	{
		$db = JFactory::getDBO();
		
		$query = "SELECT DISTINCT name, fieldcode, type FROM #__seminarman_fields" .
		    " WHERE published = 1".
		    // " AND visible = 1".
			" AND type NOT LIKE '%group%'";
		$db->setQuery($query);
		$fields = $db->loadObjectList();
		return $fields;
	}
	
	
	function setDefault($id)
	{
		$db = JFactory::getDBO();
	
		$db->setQuery('SELECT templatefor FROM #__seminarman_pdftemplate WHERE id='.(int)$id);
		$tf = $db->loadResult();
		$db->setQuery('UPDATE #__seminarman_pdftemplate SET isdefault=1 WHERE templatefor='.(int)$tf.' AND id='. (int)$id);
		$db->query();
		$db->setQuery('UPDATE #__seminarman_pdftemplate SET isdefault=0 WHERE templatefor='.(int)$tf.' AND id<>'. (int)$id);
		$db->query();
	}
	
	function getTemplate($id = 0)
	{
		$db = JFactory::getDBO();
		$query = 'SELECT * FROM #__seminarman_pdftemplate WHERE templatefor=0 AND ';
		if ($id == 0) {
			$query .= 'isdefault=1 LIMIT 1';
		} else {
			$query .= 'id='.(int)$id.' LIMIT 1';
		}
		$db->setQuery($query);
		return $db->loadObject();
	}
}
