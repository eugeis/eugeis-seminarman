<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class seminarmanModelPdftemplate extends JModelLegacy
{
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


?>