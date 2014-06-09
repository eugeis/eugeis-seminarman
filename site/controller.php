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

class SeminarmanController extends JControllerLegacy
{
    function __construct()
    {
        parent::__construct();
    }

    function display($cachable = false, $urlparams = false)
    {

        $user = JFactory::getUser();
        if ($user->get('id') || JRequest::getVar('view') == 'category') {
        	parent::display(false);
        } else {
        	parent::display(true);
        }
    }


    function add()
    {
        $user = JFactory::getUser();

        $view = $this->getView('Courses', 'html');

        if (!$user->authorize('com_seminarman', 'add'))
        {
            JError::raiseError(403, JText::_("ALERTNOTAUTH"));
        }

        $model = $this->getModel('Courses');

        $view->setModel($model, true);

        $view->setLayout('form');

        $view->display();
    }

    function save()
    {

        JRequest::checkToken() or jexit('Invalid Token');

        $db = JFactory::getDBO();
        $user = JFactory::getUser();

        $model = $this->getModel('Courses');

        $post = JRequest::get('post');

        $isNew = ((int)$post['id'] < 1);

        if ($user->get('id') < 1)
        {
            JError::raiseError(403, JText::_('ALERTNOTAUTH'));
            return;
        }

        if (!(($user->authorize('com_seminarman', 'edit') || $user->authorize('com_content',
            'edit', 'content', 'own')) || $user->authorize('com_seminarman', 'add')))
        {
            JError::raiseError(403, JText::_("ALERTNOTAUTH"));
        }

        if ($model->store($post))
        {
            if ($isNew)
            {
                $post['id'] = (int)$model->get('id');
            }
        } else
        {
            $msg = JText::_('ERROR STORING course');
            JError::raiseError(500, $model->getError());
        }

        $model->checkin();

        if ($isNew)
        {

            $query = 'SELECT DISTINCT c.id, c.title,' .
                ' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as slug' .
                ' FROM #__seminarman_categories AS c' .
                ' LEFT JOIN #__seminarman_cats_course_relations AS rel ON rel.catid = c.id' .
                ' WHERE rel.courseid = ' . (int)$model->get('id');

            $db->setQuery($query);

            $categories = $db->loadObjectList();

            $n = count($categories);
            $i = 0;
            $catstring = '';
            foreach ($categories as $category)
            {
                $catstring .= $category->title;
                $i++;
                if ($i != $n)
                {
                    $catstring .= ', ';
                }
            }

            $query = 'SELECT id, email, name' . ' FROM #__users' . ' WHERE sendEmail = 1';
            $db->setQuery($query);
            if (!$db->query())
            {
                JError::raiseError(500, $db->stderr(true));
                return;
            }
            $adminRows = $db->loadObjectList();

            require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_messages' . DS .
                'tables' . DS . 'message.php');

            foreach ($adminRows as $adminRow)
            {



                $message = new TableMessage($db);
                $message->send($user->get('id'), $adminRow->id, JText::_('NEW seminarman course'),
                    JText::sprintf('ON NEW course', $post['title'], $user->get('username'), $catstring));
            }

        } else
        {

            $cache = JFactory::getCache('com_seminarman');
            $cache->clean();
        }

        if ($user->authorize('com_seminarman', 'state'))
        {
            $msg = JText::_('course SAVED');
        } else
        {
            $msg = $isNew ? JText::_('THANKS SUBMISSION') : JText::_('course SAVED');
        }

        $link = JRequest::getString('referer', JURI::base(), 'post');
        $this->setRedirect($link, $msg);
    }

    function cancel()
    {

        $user = JFactory::getUser();

        $course = JTable::getInstance('seminarman_courses', '');
        $course->bind(JRequest::get('post'));

        if ($user->authorize('com_seminarman', 'edit') || $user->authorize('com_seminarman',
            'edit', 'own'))
        {
            $course->checkin();
        }

        $referer = JRequest::getString('referer', JURI::base(), 'post');
        $this->setRedirect($referer);
    }

  
    function addtag()
    {

        $user = JFactory::getUser();

        $name = JRequest::getString('name', '');

        if ($user->authorize('com_seminarman', 'newtags'))
        {
            $model = $this->getModel('courses');
            $model->addtag($name);
        }
        return;
    }

    function addfavourite()
    {
        $cid = JRequest::getInt('cid', 0);
        $id = JRequest::getInt('id', 0);

        $model = $this->getModel('courses');
        if ($model->addfav())
        {
            $msg = JText::_('COM_SEMINARMAN_FAVOURITE_ADDED');
        } else
        {
            JError::raiseError(500, $model->getError());
            $msg = JText::_('COM_SEMINARMAN_FAVOURITE_NOT_ADDED');
        }

        $cache = JFactory::getCache('com_seminarman');
        $cache->clean();

        $this->setRedirect(JRoute::_('index.php?option=com_seminarman&view=courses&cid=' . $cid . '&id=' . $id, false),
            $msg);

        return;
    }

    function removefavourite()
    {
        $cid = JRequest::getInt('cid', 0);
        $id = JRequest::getInt('id', 0);

        $model = $this->getModel('courses');
        if ($model->removefav())
        {
            $msg = JText::_('COM_SEMINARMAN_FAVOURITE_REMOVED');
        } else
        {
            JError::raiseError(500, $model->getError());
            $msg = JText::_('COM_SEMINARMAN_FAVOURITE_NOT_REMOVED');
        }

        $cache = JFactory::getCache('com_seminarman');
        $cache->clean();

        if ($cid)
        {
            $this->setRedirect(JRoute::_('index.php?option=com_seminarman&view=courses&cid=' . $cid . '&id=' . $id, false),
                $msg);
        } else
        {
            $this->setRedirect(JRoute::_('index.php?option=com_seminarman&view=favourites', false), $msg);
        }

        return;
    }


    function download()
    {
        $mainframe = JFactory::getApplication();

        jimport('joomla.filesystem.file');

        $id = JRequest::getInt('fileid', 0);
        $db = JFactory::getDBO();

        $query = 'SELECT filename' . ' FROM #__seminarman_files' . ' WHERE id = ' . (int)$id;
        $db->setQuery($query);
        $file = $db->loadResult();

        $basePath = COM_SEMINARMAN_FILEPATH;

        $abspath = str_replace(DS, '/', JPath::clean($basePath . DS . $file));
        if (!JFile::exists($abspath))
        {
            return;
        }

        $size = filesize($abspath);
        $ext = strtolower(JFile::getExt($file));

        $filetable = JTable::getInstance('seminarman_files', '');
        $filetable->hit($id);

        if (ini_get('zlib.output_compression'))
        {
            ini_set('zlib.output_compression', 'Off');
        }

        switch ($ext)
        {
            case "pdf":
                $ctype = "application/pdf";
                break;
            case "exe":
                $ctype = "application/octet-stream";
                break;
            case "rar":
            case "zip":
                $ctype = "application/zip";
                break;
            case "txt":
                $ctype = "text/plain";
                break;
            case "doc":
                $ctype = "application/msword";
                break;
            case "xls":
                $ctype = "application/vnd.ms-excel";
                break;
            case "ppt":
                $ctype = "application/vnd.ms-powerpoint";
                break;
            case "gif":
                $ctype = "image/gif";
                break;
            case "png":
                $ctype = "image/png";
                break;
            case "jpeg":
            case "jpg":
                $ctype = "image/jpg";
                break;
            case "mp3":
                $ctype = "audio/mpeg";
                break;
            default:
                $ctype = "application/force-download";
        }
        header("Pragma: public");

        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);

        header("Content-Type: $ctype");

        header("Content-Disposition: attachment; filename=\"" . $file . "\";");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . $size);

        readfile($abspath);
        $mainframe->close();
    }
}

?>
