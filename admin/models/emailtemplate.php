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

class seminarmanModelEmailtemplate extends JModelLegacy
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
			$data->templatefor = 0;
			$data->title = null;
			$data->subject = null;
			$data->body = null;
			$data->recipient = null;
			$data->bcc = null;
			$data->status = null;
			$data->isDefault = 0;
			return $data;
		}
		
		$db = JFactory::getDBO();
		$db->setQuery('SELECT w.* FROM #__seminarman_emailtemplate AS w WHERE w.id='.(int)$this->id);
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
			$db->setQuery('SELECT COUNT(id) AS count, GROUP_CONCAT(id) AS ids FROM #__seminarman_courses WHERE email_template IN ('. $cids .')');
			$res = $db->loadAssoc();
			if ($res['count'] > 0) {
				$this->setError(JText::sprintf('COM_SEMINARMAN_RELATED_N_RECORDS', JText::_('COM_SEMINARMAN_COURSE') .' '. $res['ids']));
				return false;
			}


			/* are there still templates with this? */
			$db->setQuery('SELECT COUNT(id) AS count, GROUP_CONCAT(id) AS ids FROM #__seminarman_templates WHERE email_template IN ('. $cids .')');
			$res = $db->loadAssoc();
			if ($res['count'] > 0) {
				$this->setError(JText::sprintf('COM_SEMINARMAN_RELATED_N_RECORDS', JText::_('COM_SEMINARMAN_TEMPLATE') .' '. $res['ids']));
				return false;
			}

			$db->setQuery('DELETE FROM #__seminarman_emailtemplate WHERE id IN ( '. $cids .' )');
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
		
		$db->setQuery('SELECT COUNT(id) FROM #__seminarman_emailtemplate WHERE templatefor=0 AND isdefault=1');
		$count = $db->loadResult();
		if ($count == 0) {
			$db->setQuery('UPDATE #__seminarman_emailtemplate SET isdefault=1 WHERE templatefor=0 LIMIT 1');
			$db->query();
		}
		
		$db->setQuery('SELECT COUNT(id) FROM #__seminarman_emailtemplate WHERE templatefor=1 AND isdefault=1');
		$count = $db->loadResult();
		if ($count == 0) {
			$db->setQuery('UPDATE #__seminarman_emailtemplate SET isdefault=1 WHERE templatefor=1 LIMIT 1');
			$db->query();
		}
	}
	

	function storeEmailTemplate()
	{
		$db = JFactory::getDBO();
		
		$row = $this->getTable('emailtemplate');

		$data = JRequest::get('post');
		$data['body'] = JRequest::getVar('body', '', 'post', 'string', JREQUEST_ALLOWRAW);

		if (!$row->bind($data)) {
			$this->setError($db->getErrorMsg());
			return false;
		}
		if (!$row->store()) {
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
		return $db->loadObjectList();
	}

	
	function setDefault($id)
	{
		$db = JFactory::getDBO();
		
		$db->setQuery('SELECT templatefor FROM #__seminarman_emailtemplate WHERE id='.(int)$id);
		$tf = $db->loadResult();
		$db->setQuery('UPDATE #__seminarman_emailtemplate SET isdefault=1 WHERE templatefor='.(int)$tf.' AND id='. (int)$id);
		$db->query();
		$db->setQuery('UPDATE #__seminarman_emailtemplate SET isdefault=0 WHERE templatefor='.(int)$tf.' AND id<>'. (int)$id);
		$db->query();
	}
}
