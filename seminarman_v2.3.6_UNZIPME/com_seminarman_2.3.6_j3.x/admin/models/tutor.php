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

jimport('joomla.application.component.model');

class seminarmanModeltutor extends JModelLegacy{
    var $_id = null;

    var $_data = null;

    function __construct()
    {
        parent::__construct();

        $array = JRequest::getVar('cid', array(0), '', 'array');
        $edit = JRequest::getVar('edit', true);
        if ($edit)
            $this->setId((int)$array[0]);
        $this->childviewname = 'tutor';
    }

    function setId($id)
    {
        $this->_id = $id;
        $this->_data = null;
    }

    function &getData()
    {
        if ($this->_loadData()){
            $user = JFactory::getUser();
        }else
            $this->_initData();

        return $this->_data;
    }

    function isCheckedOut($uid = 0)
    {
        if ($this->_loadData()){
            if ($uid){
                return ($this->_data->checked_out && $this->_data->checked_out != $uid);
            }else{
                return $this->_data->checked_out;
            }
        }
    }

    function checkin()
    {
        if ($this->_id){
            $group = $this->getTable();
            if (!$group->checkin($this->_id)){
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return false;
    }

    function checkout($uid = null)
    {
        if ($this->_id){
            if (is_null($uid)){
                $user = JFactory::getUser();
                $uid = $user->get('id');
            }

            $group = $this->getTable();
            if (!$group->checkout($uid, $this->_id)){
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

            return true;
        }
        return false;
    }


    function delete($cid = array())
    {
    	$db = JFactory::getDBO();
    	 
        if (count($cid)){
            JArrayHelper::toInteger($cid);
            $cids = implode(',', $cid);

            $db->setQuery('SELECT tutor_id FROM #__seminarman_courses WHERE tutor_id IN ( '. $cids .' )');
            $relatedRecords = $db->loadResult();
            if ($relatedRecords > 0) {
                $this->setError( JText::_('COM_SEMINARMAN_RELATED_RECORDS_ERROR') );
                return false;
            }

            $db->setQuery('DELETE FROM #__seminarman_'. $this->childviewname .' WHERE id IN ( '. $cids .' )');
            if (!$db->query()){
                $this->setError($db->getErrorMsg());
                return false;
            }
            
            $db->setQuery('DELETE FROM #__seminarman_tutor_templates_relations WHERE tutorid IN (' . $cids . ')');
            $db->query();
        }

        return true;
    }

    function approve($cid = array(), $approve = 1)
    {
        $user = JFactory::getUser();

        if (count($cid)){
            JArrayHelper::toInteger($cid);
            $cids = implode(',', $cid);

            $query = 'UPDATE #__seminarman_' . $this->childviewname . ' SET approved = ' . (int)
            $approved . ' WHERE id IN ( ' . $cids . ' )' .
            ' AND ( checked_out = 0 OR ( checked_out = ' . (int)$user->get('id') . ' ) )';
            $this->_db->setQuery($query);
            if (!$this->_db->query()){
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

         $query = 'UPDATE #__seminarman_tutor'
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

    function move($direction)
    {
        $row = $this->getTable();
        if (!$row->load($this->_id)){
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        if (!$row->move($direction, ' published >= 0 ')){
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        return true;
    }

    function saveorder($cid = array(), $order)
    {
        $row = $this->getTable();

        for ($i = 0; $i < count($cid); $i++){
            $row->load((int)$cid[$i]);

            if ($row->ordering != $order[$i]){
                $row->ordering = $order[$i];
                if (!$row->store()){
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
            }
        }

        $row->reorder('');

        return true;
    }

    function _loadData()
    {
        if (empty($this->_data)){
            $query = 'SELECT w.*' . ' FROM #__seminarman_' . $this->childviewname . ' AS w' .
            ' WHERE w.id = ' . (int)$this->_id;
            $this->_db->setQuery($query);
            $this->_data = $this->_db->loadObject();
            return (boolean)$this->_data;
        }
        return true;
    }

    function UploadImage($file)
    {
        if ($file['size'] > 0){
            jimport('joomla.filesystem.file');
            $post = JRequest::get('post');
    		$params = JComponentHelper::getParams( 'com_seminarman' );

            $path = COM_SEMINARMAN_IMAGEPATH . DS . $post['logotodelete'];
            if (JFile::exists($path)){
                JFile::delete($path);
            }

            $filename = JFile::makeSafe($post['id'] . $file['name']);

            $src = $file['tmp_name'];
            $dest = COM_SEMINARMAN_IMAGEPATH . DS . $filename;
            $extensions = $params->get('image_extensions');
            $extensions = explode(',', $extensions);
            for ($i = 0; $i < count($extensions); $i++){
                if (strtolower(JFile::getExt($filename)) == $extensions[$i]){
                    $fileextensions = 1;
                }
            }
            if ($fileextensions = 1){
                if (JFile::upload($src, $dest)){
                    $row->logofilename = $post['id'] . $file['name'];

                    require_once JPATH_COMPONENT . DS . 'classes' . DS . 'thumb' . DS .
                    'ThumbLib.inc.php';

                    $thumb = PhpThumbFactory::create($dest);
                    $thumb->resize($params->get('pwidth'), $params->get('pheight'));
                    $thumb->save($dest);
                    return $row->logofilename;
                }else{
                    $msg .= JText::_('Error uploading image');
                    return false;
                }
            }else{
                return false;
            }
        }
    }

    function _initData()
    {
        if (empty($this->_data)){
            $tutor = new stdClass();
            $tutor->id = 0;
            $tutor->title = null;
            $tutor->alias = null;
            $tutor->code = null;
            $tutor->description = null;
            $tutor->date = null;
            $tutor->hits = 0;
            $tutor->published = 1;
            $tutor->checked_out = 0;
            $tutor->checked_out_time = 0;
            $tutor->ordering = 0;
            $tutor->archived = 0;
            $tutor->approved = 0;
            $tutor->params = null;
            $tutor->user_id = null;
            $tutor->firstname = null;
            $tutor->lastname = null;
            $tutor->salutation = null;
            $tutor->other_title = null;
            $tutor->comp_name = null;
            $tutor->primary_phone = null;
            $tutor->fax_number = null;
            $tutor->email = null;
            $tutor->url = null;
            $tutor->street = null;
            $tutor->id_country = null;
            $tutor->state = null;
            $tutor->city = null;
            $tutor->zip = null;
            $tutor->id_comp_type = null;
            $tutor->industry = null;
            $tutor->logofilename = null;
            $tutor->logo = null;
            $tutor->bill_addr = null;
            $tutor->bill_addr_cont = null;
            $tutor->bill_id_country = null;
            $tutor->bill_state = null;
            $tutor->bill_city = null;
            $tutor->bill_zip = null;
            $tutor->bill_phone = null;
            $tutor->metadescription = null;
            $tutor->metakeywords = null;
            $tutor->status = null;

            $this->_data = $tutor;
            return (boolean)$this->_data;
        }
        return true;
    }
    
    function getTemplates()
    {
    	$query = 'SELECT DISTINCT t.id, t.name, t.title, rel.priority'.
        		' FROM #__seminarman_tutor_templates_relations AS rel'. 
        		' LEFT JOIN #__seminarman_templates AS t'.
        		' ON rel.templateid =  t.id'. 
        		' WHERE rel.tutorid = '. (int)$this->_id .
        		' ORDER BY t.name, t.title';
    	$this->_db->setQuery($query);
    	return $this->_db->loadObjectList();
    }
    
    function addTemplate($template_id, $template_prio, $id)
    {
    	if ($template_id == 0 || $id == 0)
    		return;

  		$query = 'INSERT INTO #__seminarman_tutor_templates_relations (`tutorid`, `templateid`, `priority`)'.
					' VALUES ('. $id .','. $template_id . ','. $template_prio .')'.
					' ON DUPLICATE KEY UPDATE priority = '. $template_prio;
   		$this->_db->setQuery($query);
   		$this->_db->query();    		
    }
    
    function removeTemplates($template_ids, $id)
    {
    	if ($id == 0)
    		return;
    	
    	$template_ids = implode(',', $template_ids);
		$query = 'DELETE FROM #__seminarman_tutor_templates_relations'.
        			' WHERE tutorid = '. $id.
        			' AND templateid IN ('. $template_ids . ')';
    	$this->_db->setQuery($query);
    	$this->_db->query();
    }
    
    /**
     * Returns an array of custom editfields which are created from the back end.
     *
     * @access	public
     * @param	string 	User's id.
     * @returns array  An objects of custom fields.
     */
    function getEditableCustomfields($tutorId = null)
    {
    	$db   = $this->getDBO();
    	$data = array();
    
    	if (!empty($tutorId))
    	{
    		$q = 'SELECT f.*, v.value FROM `#__seminarman_fields` AS f'.
    				' LEFT JOIN `#__seminarman_fields_values_tutors` AS v'.
    				' ON f.id = v.field_id AND v.tutor_id = '.(int)$tutorId.
    				' WHERE f.published=1 ORDER BY f.ordering';
    		$db->setQuery($q);
    	}
    	else
    	{
    		$q = 'SELECT f.*, v.value FROM `#__seminarman_fields` AS f'.
    				' LEFT JOIN `#__seminarman_fields_values_tutors` AS v'.
    				' ON f.id = v.field_id AND v.tutor_id = 0'.
    				' WHERE f.published=1 ORDER BY f.ordering';
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
    	if($result[$i]['type'] == 'group' && $result[$i]['purpose'] == 2)
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
    	if($result[$i]['type'] == 'group' && $result[$i]['purpose'] != 2)
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
    
    function saveCustomfields($tutorId, $fields)
    {
    	$db = $this->getDBO();
    
    	foreach ($fields as $id => $value) {
    		 
    		$q = 'REPLACE INTO `#__seminarman_fields_values_tutors`'.
    				' (tutor_id, field_id, value)'.
    				' VALUES ('.(int)$tutorId.','.(int)$id.','.$db->quote($value).')';
    		$db->setQuery($q);
    		$db->query();
    	}
    }
    

}