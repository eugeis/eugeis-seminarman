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

jimport('joomla.application.component.controller');

class SeminarmanControllerFilemanager extends SeminarmanController
{
    function __construct()
    {
        parent::__construct();
        $this->registerTask('apply', 'save');
    }

    function upload()
    {
        $mainframe = JFactory::getApplication();

        JRequest::checkToken('request') or jexit('Invalid Token');

        $file = JRequest::getVar('Filedata', '', 'files', 'array');
        $format = JRequest::getVar('format', 'html', '', 'cmd');
        $return = JRequest::getVar('return-url', null, 'post', 'base64');
        $err = null;

        jimport('joomla.utilities.date');

        jimport('joomla.client.helper');
        JClientHelper::setCredentialsFromRequest('ftp');

        jimport('joomla.filesystem.file');
        $file['name'] = JFile::makeSafe($file['name']);

        if (isset($file['name']))
        {

            $path = COM_SEMINARMAN_FILEPATH . DS;

            $filename = seminarman_upload::sanitize($path, $file['name']);
            $filepath = JPath::clean(COM_SEMINARMAN_FILEPATH . DS . strtolower($filename));

            if (!seminarman_upload::check($file, $err))
            {
                if ($format == 'json')
                {
                    jimport('joomla.error.log');
                    $log = &JLog::getInstance('com_seminarman.error.php');
                    $log->addEntry(array('comment' => 'Invalid: ' . $filepath . ': ' . $err));
                    header('HTTP/1.0 415 Unsupported Media Type');
                    die('COM_SEMINARMAN_UNSUPPORTED_MEDIA_FILE');
                } else
                {
                    JError::raiseNotice(100, JText::_($err));

                    if ($return)
                    {
                        $mainframe->redirect(base64_decode($return));
                    }
                    return;
                }
            }

            if (!JFile::upload($file['tmp_name'], $filepath))
            {
                if ($format == 'json')
                {
                    jimport('joomla.error.log');
                    $log = &JLog::getInstance('com_seminarman.error.php');
                    $log->addEntry(array('comment' => 'Cannot upload: ' . $filepath));
                    header('HTTP/1.0 409 Conflict');
                    jexit('COM_SEMINARMAN_FILE_EXISTS');
                } else
                {
                    JError::raiseWarning(100, JText::_('COM_SEMINARMAN_OPERATION_FAILED'));

                    if ($return)
                    {
                        $mainframe->redirect(base64_decode($return));
                    }
                    return;
                }
            } else
            {
                if ($format == 'json')
                {
                    jimport('joomla.error.log');
                    $log = &JLog::getInstance();
                    $log->addEntry(array('comment' => $filepath));

                    $db = &JFactory::getDBO();
                    $user = &JFactory::getUser();
                    $config = &JFactory::getConfig();

                    $tzoffset = $config->getValue('config.offset');
                    $date = &JFactory::getDate('now', -$tzoffset);

                    $obj = new stdClass();
                    $obj->filename = $filename;
                    $obj->altname = $file['name'];
                    $obj->hits = 0;
                    $obj->uploaded = $date->toMySQL();
                    $obj->uploaded_by = $user->get('id');

                    $db->insertObject('#__seminarman_files', $obj);

                    jexit('Upload complete');
                } else
                {

                    $db = &JFactory::getDBO();
                    $user = &JFactory::getUser();
                    $config = &JFactory::getConfig();

                    $tzoffset = $config->getValue('config.offset');
                    $date = &JFactory::getDate('now', -$tzoffset);

                    $obj = new stdClass();
                    $obj->filename = $filename;
                    $obj->altname = $file['name'];
                    $obj->hits = 0;
                    $obj->uploaded = $date->toMySQL();
                    $obj->uploaded_by = $user->get('id');

                    $db->insertObject('#__seminarman_files', $obj);

                    $mainframe->enqueueMessage(JText::_('COM_SEMINARMAN_OPERATION_SUCCESSFULL'));

                    if ($return)
                    {
                        $mainframe->redirect(base64_decode($return));
                    }
                    return;
                }
            }
        }
        $mainframe->redirect(base64_decode($return));
    }

    function ftpValidate()
    {

        jimport('joomla.client.helper');
        JClientHelper::setCredentialsFromRequest('ftp');
    }

    function edit()
    {
        JRequest::setVar('view', 'file');
        JRequest::setVar('hidemainmenu', 1);

        $model = $this->getModel('file');
        $user = &JFactory::getUser();

        if ($model->isCheckedOut($user->get('id')))
        {
            $this->setRedirect('index.php?option=com_seminarman&controller=filemanager&task=edit',
                JText::_('COM_SEMINARMAN_RECORD_EDITED'));
        }

        $model->checkout($user->get('id'));

        parent::display();
    }

    function remove()
    {

        JRequest::checkToken() or jexit('Invalid Token');

        $cid = JRequest::getVar('cid', array(0), 'post', 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            JError::raiseError(500, JText::_('COM_SEMINARMAN_SELECT_ITEM'));
        }

        $model = $this->getModel('filemanager');

        if (!$model->delete($cid))
        {
            $msg = '';
            JError::raiseError(500, JText::_('COM_SEMINARMAN_OPERATION_FAILED'));
        } else
        {
            $msg = JText::_('COM_SEMINARMAN_OPERATION_SUCCESSFULL');
            $cache = &JFactory::getCache('com_seminarman');
            $cache->clean();
        }

        $this->setRedirect('index.php?option=com_seminarman&view=filemanager', $msg);
    }

    function save()
    {

        JRequest::checkToken() or jexit('Invalid Token');

        $task = JRequest::getVar('task');

        $post = JRequest::get('post');

        $model = $this->getModel('file');

        if ($model->store($post))
        {

            switch ($task)
            {
                case 'apply':
                    $link = 'index.php?option=com_seminarman&controller=filemanager&task=edit&cid[]=' . (int)
                        $model->get('id');
                    break;

                default:
                    $link = 'index.php?option=com_seminarman&view=filemanager';
                    break;
            }
            $msg = JText::_('COM_SEMINARMAN_OPERATION_SUCCESSFULL');

            $model->checkin();

            $cache = &JFactory::getCache('com_seminarman');
            $cache->clean();

        } else
        {

            $msg = JText::_('COM_SEMINARMAN_ERROR_SAVING');
            JError::raiseError(500, $model->getError());
            $link = 'index.php?option=com_seminarman&view=filemanager';
        }

        $this->setRedirect($link, $msg);
    }

    function cancel()
    {

        JRequest::checkToken() or jexit('Invalid Token');

        $file = &JTable::getInstance('seminarman_files', '');
        $file->bind(JRequest::get('post'));
        $file->checkin();

        $this->setRedirect('index.php?option=com_seminarman&view=filemanager');
    }

    function goback()
    {

        JRequest::checkToken() or jexit('Invalid Token');
        $this->setRedirect('index.php?option=com_seminarman&view=courses');
    }
}