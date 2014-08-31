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

if(!defined('DS')){
	define('DS',DIRECTORY_SEPARATOR);
}

class JPaneOSGF {
	var $_type = NULL;

	function __construct() {

	}

	static function getInstance($typ) {
		$obj = New self();
		$obj->_type = $typ;
		return $obj;
	}

	function startPane($PaneID, $options=NULL) {
		if ($this->_type == "tabs") {
			return JHtml::_('tabs.start', $PaneID, $options);
		}
	}

	function startPanel($title, $titleID) {
		if ($this->_type == "tabs") {
			return JHtml::_('tabs.panel', $title, $titleID);
		}
	}

	function endPanel(){
		return;
	}

	function endPane(){
		return JHtml::_('tabs.end');
	}
}

require_once (JPATH_COMPONENT . DS . 'classes' . DS . 'helper.php');
require_once (JPATH_COMPONENT . DS . 'classes' . DS . 'categories.php');

require_once ( JPATH_ROOT.DS.'components'.DS.'com_seminarman'.DS.'defines.seminarman.php');

$params = JComponentHelper::getParams('com_seminarman');
define('COM_SEMINARMAN_FILEPATH', JPATH_ROOT . DS . $params->get('file_path','components/com_seminarman/uploads'));

$language = JFactory::getLanguage();
$language->load('com_seminarman', JPATH_SITE, 'en-GB', true);
$language->load('com_seminarman', JPATH_SITE, null, true);

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'tables');

require_once (JPATH_COMPONENT . DS . 'controller.php');

if ($controller = JRequest::getWord('controller'))
{
    $path = JPATH_COMPONENT . DS . 'controllers' . DS . $controller . '.php';
    if (file_exists($path))
    {
        require_once $path;
    } else
    {
        $controller = '';
    }
}


$classname = 'SeminarmanController' . ucfirst($controller);
$controller = new $classname();

$controller->execute(JRequest::getVar('task', null, 'default', 'cmd'));

$controller->redirect();

?>