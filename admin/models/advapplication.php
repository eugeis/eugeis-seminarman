<?php
/**
* Copyright (C) 2013 Open Source Group GmbH www.osg-gmbh.de
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
require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_seminarman' . DS .
		'helpers' . DS . 'seminarman.php');

$params = JComponentHelper::getParams('com_seminarman');

if (SeminarmanFunctions::isSmanbookingPlgEnabled() && $params->get('advanced_booking')) {
    include JPATH_ROOT.DS.'plugins'.DS.'seminarman'.DS.'smanbooking'.DS.'smanbooking'.DS.'models'.DS.'advapplication.php';
}