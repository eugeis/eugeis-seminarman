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

class SeminarmanModelTemplates extends JModelLegacy
{
    var $_template = null;
    var $_salesProspectData = null;
    var $_tags = null;
    var $_id = null;

    function __construct()
    {
        parent::__construct();

        $id = JRequest::getVar('id', 0, '', 'int');
        $this->setId((int)$id);
    }


    function setId($id)
    {

    	$this->_id = $id;
    	$this->_template = null;
    }

    function get($property, $default = null)
    {
    	if ($this->_loadTemplate())
    		if (isset($this->_template->$property))
    			return $this->_template->$property;
    	return $default;
    }

    function set($property, $value = null)
    {
    	if ($this->_loadTemplate())
    	{
    		$this->_template->$property = $value;
    		return true;
    	}
    	return false;
    }

    function &getTemplate()
    {
    	$this->_loadTemplate();
		$user = JFactory::getUser();

		if (!$this->_template->catpublished && $this->_template->catid)
			JError::raiseError(404, JText::_("CATEGORY NOT PUBLISHED"));

		if ($this->_template->created_by_alias)
			$this->_template->creator = $this->_template->created_by_alias;
		else
		{
			$query = 'SELECT name' . ' FROM #__users' . ' WHERE id = ' . (int)$this->_template->created_by;
			$this->_db->setQuery($query);
			$this->_template->creator = $this->_db->loadResult();
		}
		
		$session = JFactory::getSession();
		$this->_template->text = $this->_template->introtext . chr(13) . chr(13) . $this->_template->fulltext;
		
		return $this->_template;
    }

    function _loadTemplate()
    {
        $mainframe = JFactory::getApplication();

        if ($this->_id == '0')
        {
            return false;
        }

        if (empty($this->_template))
        {
            $query = 'SELECT i.*, c.access AS cataccess, c.id AS catid, c.published AS catpublished, c.title AS categorytitle,' .
                // ' u.name AS author, u.usertype,' .
                ' u.name AS author,' .
                ' gr.title AS cgroup,' .
                ' lev.title AS level,' .
                ' i.id as slug,' .
                ' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as categoryslug' .
                ' FROM #__seminarman_templates AS i' .
                ' LEFT JOIN #__seminarman_cats_template_relations AS rel ON rel.templateid = i.id' .
                ' LEFT JOIN #__seminarman_atgroup AS gr ON gr.id = i.id_group' .
                ' LEFT JOIN #__seminarman_experience_level AS lev ON lev.id = i.id_experience_level' .
                ' LEFT JOIN #__seminarman_categories AS c ON c.id = rel.catid' .
                ' LEFT JOIN #__users AS u ON u.id = i.created_by' . ' WHERE i.id = ' . $this->_id . ' AND i.state = 1';
            $this->_db->setQuery($query);
            $this->_template = $this->_db->loadObject();
            return (boolean)$this->_template;
        }
        return true;
    }

    function getTags()
    {
        $query = 'SELECT DISTINCT t.name,' .
            ' CASE WHEN CHAR_LENGTH(t.alias) THEN CONCAT_WS(\':\', t.id, t.alias) ELSE t.id END as slug' .
            ' FROM #__seminarman_tags AS t' .
            ' LEFT JOIN #__seminarman_tags_template_relations AS i ON i.tid = t.id' .
            ' WHERE i.templateid = ' . (int)$this->_id . ' AND t.published = 1' .
            ' ORDER BY t.name';

        $this->_db->setQuery($query);

        $this->_tags = $this->_db->loadObjectList();

        return $this->_tags;
    }

    function getCategories()
    {
        $query = 'SELECT DISTINCT c.id, c.title,' .
            ' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as slug' .
            ' FROM #__seminarman_categories AS c' .
            ' LEFT JOIN #__seminarman_cats_template_relations AS rel ON rel.catid = c.id' .
            ' WHERE rel.templateid = ' . $this->_id;

        $this->_db->setQuery($query);

        $this->_cats = $this->_db->loadObjectList();

        return $this->_cats;
    }
    
    function getSalesProspects()
    {
    	$mainframe = JFactory::getApplication();
    	$user = JFactory::getUser();
    	if ($user->id != 0)
    	{
    		$query = 'SELECT * FROM `#__seminarman_salesprospect`' .
    	        			 ' WHERE user_id = '. (int)$user->get('id').
    	        			 ' AND template_id = ' . $this->_id;
    
    		$this->_db->setQuery($query);
    		$this->_salesProspectData = $this->_db->loadObject();
    
    		if (count($this->_salesProspectData)>0)
    		{
    			$this->_salesProspectData->jusertype = null;
    			$this->_salesProspectData->attendees = 1;
    		}
    		else
    		{
    			$query = 'SELECT * FROM `#__users` WHERE id = '. (int)$user->get('id') .' AND block = 0';
    			$this->_db->setQuery($query);
    
    			$this->_salesProspectData = $this->_db->loadObject();
    			$this->_salesProspectData->attendees = 1;
    			$namePieces = explode(" ", $this->_salesProspectData->name);
    			$this->_salesProspectData->first_name = $namePieces[0];
    			$this->_salesProspectData->last_name = empty($namePieces[1]) ? '' : $namePieces[1];
    			$this->_salesProspectData->email = $user->email;
    			$this->_salesProspectData->jusertype = true;
    			$this->_salesProspectData->user_id = $user->id;
    			
    			$query = 'SELECT * FROM `#__seminarman_fields_values_users_static` WHERE user_id = '.(int)$user->id;
    			$this->_db->setQuery($query);
    			$row = $this->_db->loadAssoc();
    			
    			$this->_salesProspectData->salutation = 0;
    			$this->_salesProspectData->salutationStr = $row['salutation'];
    			$this->_salesProspectData->title = $row['title'];
    		}
    	}
    	else
    		$this->_initSalesProspectData();

    	return $this->_salesProspectData;
    }
    
    function _initSalesProspectData()
    {
    
    	if (empty($this->_salesProspectData))
    	{
    		$salesProspectData = new stdClass();
    		$salesProspectData->id = null;
    		$salesProspectData->attendees = 1;
    		$salesProspectData->first_name = null;
    		$salesProspectData->last_name = null;
    		$salesProspectData->salutation = 0;
    		$salesProspectData->title = '';
    		$salesProspectData->email = null;
    		$salesProspectData->user_id = null;
    		$salesProspectData->jusertype = null;
    		$this->_salesProspectData = $salesProspectData;
    		return (boolean)$this->_salesProspectData;
    	}
    	return true;
    }

    function getAlltags()
    {
        $query = 'SELECT * FROM #__seminarman_tags ORDER BY name';
        $this->_db->setQuery($query);
        $tags = $this->_db->loadObjectlist();
        return $tags;
    }

    function getUsedtags()
    {
        $query = 'SELECT tid FROM #__seminarman_tags_template_relations WHERE templateid = ' . (int)$this->_id;
        $this->_db->setQuery($query);
        // return $this->_db->loadResultArray();
        return $this->_db->loadColumn();
    }

	function isCheckedOut($uid = 0)
	{
		if ($this->_loadTemplate())
		{
			if ($uid)
				return ($this->_template->checked_out && $this->_template->checked_out != $uid);
			else
				return $this->_template->checked_out;
		}
		elseif ($this->_id < 1)
			return false;
		
		JError::raiseWarning(0, 'Unable to Load Data');
		return false;
	}

    function checkin()
    {
        if ($this->_id)
        {
            $template = JTable::getInstance('seminarman_templates', '');
            return $template->checkin($this->_id);
        }
        return false;
    }

    function checkout($uid = null)
    {
        if ($this->_id)
        {

            if (is_null($uid))
            {
                $user = JFactory::getUser();
                $uid = $user->get('id');
            }

            $template = JTable::getInstance('seminarman_templates', '');
            return $template->checkout($uid, $this->_id);
        }
        return false;
    }

    function getCatsselected()
    {
        $query = 'SELECT DISTINCT catid FROM #__seminarman_cats_template_relations WHERE templateid = ' . (int)$this->_id;
        $this->_db->setQuery($query);
        return $used;
    }

    function getFiles()
    {
        $query = 'SELECT DISTINCT rel.fileid, f.filename, f.altname' .
            ' FROM #__seminarman_files AS f' .
            ' LEFT JOIN #__seminarman_files_template_relations AS rel ON rel.fileid = f.id' .
            ' WHERE rel.templateid = ' . (int)$this->_id;
        $this->_db->setQuery($query);
        $files = $this->_db->loadObjectList();

        $files = seminarman_images::BuildIcons($files);

        return $files;
    }

    /**
	 * Returns an array of custom editfields which are created from the back end.
	 *
	 * @access	public
	 * @param	string 	User's id.
	 * @returns array  An objects of custom fields.
	 */
	function getEditableCustomfields($requestid	= null)
	{
		$db   = $this->getDBO();
		$data = array();
		$user = JFactory::getUser();

		$data['id']		= $user->id;
		$data['name']	= $user->name;
		$data['email']	= $user->email;

		if (!$user->guest)
		{
			$q = 'SELECT COUNT(*) FROM `#__seminarman_fields_values_salesprospect`'.
			     ' WHERE requestid='.(int)$requestid.' AND user_id='.(int)$user->id;
			$db->setQuery($q);
			if ($db->loadResult() == 0)
			{
				// Es gibt noch keine Anmeldung auf den Kurs. Werte für Felder aus #__seminarman_fields_values_users holen
				// (kann aber auch leer sein)
				$q = 'SELECT f.*, v.value FROM `#__seminarman_fields` AS f'.
				     ' LEFT JOIN `#__seminarman_fields_values_users` AS v'.
				     ' ON f.fieldcode = v.fieldcode AND v.user_id = '.(int)$user->id.
				     ' WHERE f.published=1 AND f.visible=1 ORDER BY f.ordering';
				$db->setQuery($q);
			}
			else 
			{
				$q = 'SELECT f.*, v.value FROM `#__seminarman_fields` AS f'.
				     ' LEFT JOIN `#__seminarman_fields_values_salesprospect` AS v'.
				     ' ON f.id = v.field_id AND v.requestid = '.(int)$requestid.
				     ' WHERE f.published=1 AND f.visible=1 ORDER BY f.ordering';
				$db->setQuery($q);
			}
		}
		else 
		{
			$q = 'SELECT f.*, v.value FROM `#__seminarman_fields` AS f'.
			     ' LEFT JOIN `#__seminarman_fields_values` AS v'.
			     ' ON f.id = v.field_id AND v.applicationid = 0'.
			     ' WHERE f.published=1 AND f.visible=1 ORDER BY f.ordering';
			$db->setQuery($q);
		}

		$result	= $db->loadAssocList();

		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}

		$data['fields']	= array();
		for($i = 0; $i < count($result); $i++)
		{

			// We know that the groups will definitely be correct in ordering.
			if($result[$i]['type'] == 'group' && $result[$i]['purpose'] == 1)
			{
				$add = True;
				$group	= $result[$i]['name'];

				// Group them up
				if(!isset($data['fields'][$group]))
				{
					// Initialize the groups.
					$data['fields'][$group]	= array();
				}
			}
			if($result[$i]['type'] == 'group' && $result[$i]['purpose'] != 1)
				$add = False;

			// Re-arrange options to be an array by splitting them into an array
			if(isset($result[$i]['options']) && $result[$i]['options'] != '')
			{
				$options	= $result[$i]['options'];
				$options	= explode("\n", $options);

				$countOfOptions = count($options);
				for($x = 0; $x < $countOfOptions; $x++){
					$options[$x] = trim($options[$x]);
				}

				$result[$i]['options']	= $options;

			}

			if($result[$i]['type'] != 'group' && isset($add)){
				if($add)
					$data['fields'][$group][]	= $result[$i];
			}
		}
		return $data;
	}

}


?>
