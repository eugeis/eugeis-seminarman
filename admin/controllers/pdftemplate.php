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

class seminarmanControllerPdftemplate extends seminarmanController
{
    function __construct($config = array())
    {
        parent::__construct($config);

        $this->registerTask('add', 'display');
        $this->registerTask('edit', 'display');
        $this->registerTask('apply', 'save');
        $this->registerTask('setDefault', 'setDefault');
        $this->childviewname = 'pdftemplate';
        $this->parentviewname = 'settings';
    }

    
    function save()
    {

        $model = $this->getModel('pdftemplate', 'seminarmanModel');
        $data = JRequest::get('post');
        
        if ($id = $model->storeTemplate()) {
        	$msg = JText::_('COM_SEMINARMAN_RECORD_SAVED');
        } else {
        	$msg = JText::_('COM_SEMINARMAN_ERROR_SAVING');
        }
        
        if ($this->getTask() == 'apply') {
        	$link = 'index.php?option=com_seminarman&view=pdftemplate&layout=default&id='.(int)$id;
        } else {
        	$link = 'index.php?option=com_seminarman&view=settings';
        }
        
        $this->setRedirect($link, $msg);
    }


    function remove()
    {
        JRequest::checkToken() or jexit('Invalid Token');
        $cid = JRequest::getVar('cid', array(), 'post', 'array');
        JArrayHelper::toInteger($cid);

        if (count($cid) < 1) {
            JError::raiseError(500, JText::_('COM_SEMINARMAN_SELECT_ITEM'));
        }

        $model = $this->getModel($this->childviewname);

        if ($model->delete($cid)) {
        	$msg = JText::_('COM_SEMINARMAN_OPERATION_SUCCESSFULL');
        } else {
        	$msg = $model->getError();
        }
        
        $this->setRedirect('index.php?option=com_seminarman&view=settings', $msg);
    }

    
    function cancel()
    {
        JRequest::checkToken() or jexit('Invalid Token');
        $this->setRedirect('index.php?option=com_seminarman&view=settings');
    }

    
    function setDefault()
    {
    
    	JRequest::checkToken() or jexit('Invalid Token');
    	$id = JRequest::getVar('id', 0, 'post', 'int');
    	$model = $this->getModel($this->childviewname);
    	$model->setDefault($id);
    	$this->setRedirect('index.php?option=com_seminarman&view=' . $this->parentviewname );
    }
    
    
    function pdf_preview()
    {
    	JRequest::checkToken() or jexit('Invalid Token');
    	
    	require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_seminarman' . DS . 'classes' . DS . 'pdfdocument.php';
    	
    	$html = JRequest::getVar('html', '', 'post', 'string', JREQUEST_ALLOWHTML);
    	
    	$template = new stdClass();
    	$template->srcpdf = JRequest::getString('srcpdf', '', 'post');
    	$template->margin_left = JRequest::getInt('margin_left', '0', 'post');
    	$template->margin_right = JRequest::getInt('margin_right', '0', 'post');
    	$template->margin_top = JRequest::getInt('margin_top', '0', 'post');
    	$template->margin_bottom = JRequest::getInt('margin_bottom', '0', 'post');
    	$template->paperformat = JRequest::getString('paperformat', 'A4', 'post');
    	$template->orientation = JRequest::getString('orientation', 'P', 'post');
    	
    	chdir('..');
    	$pdf = new PdfDocument($template);
    	$pdf->addHTMLBox($html);
    	$pdf->Output('preview.pdf', 'I');
    	exit;
    }
}

?>