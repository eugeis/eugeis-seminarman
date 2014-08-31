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


// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view' );

/**
 * Configuration view for Jom Social
 */
class SeminarmanViewEditfields extends JViewLegacy
{
	/**
	 * The default method that will display the output of this view which is called by
	 * Joomla
	 *
	 * @param	string template	Template file name
	 **/
	function display( $tpl = null )
	{
		$customfields	= $this->getModel( 'Editfields' );

		$fields		= $customfields->getFields();
		$pagination	= $customfields->getPagination();

		// Load tooltips
		JHTML::_('behavior.tooltip', '.hasTip');
		$document = JFactory::getDocument();
		$lang = JFactory::getLanguage();

		$document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend.css');
		if ($lang->isRTL())
			$document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend_rtl.css');		

		JToolBarHelper::title( JText::_('COM_SEMINARMAN_CUSTOM_FIELDS'), 'config' );

		// Add the necessary buttons
		JToolBarHelper::back('COM_SEMINARMAN_GO_BACK' , 'index.php?option=com_seminarman&view=settings');
		JToolBarHelper::divider();
		JToolBarHelper::addNew('newfield', JText::_('COM_SEMINARMAN_ADD_CUSTOM_FIELD'));
		JToolBarHelper::addNew('newgroup', JText::_('COM_SEMINARMAN_ADD_GROUP'));
		JToolBarHelper::divider();
		JToolBarHelper::publishList('publish', JText::_('JTOOLBAR_PUBLISH'));
		JToolBarHelper::unpublishList('unpublish', JText::_('JTOOLBAR_UNPUBLISH'));
		JToolBarHelper::divider();
		JToolBarHelper::trash('removefield', JText::_('COM_SEMINARMAN_DELETE'));

		$this->assignRef( 'fields' 		, $fields );
		$this->assignRef( 'pagination'	, $pagination );
		
		$fields_with_empty_code = $customfields->fields_with_empty_code();
		if(!empty($fields_with_empty_code)){
			$app = JFactory::getApplication();
			foreach($fields_with_empty_code as $f_empty) {
				$app->enqueueMessage(JText::_('COM_SEMINARMAN_FIELD_NO_CODE') . ': ' . $f_empty["name"], 'error');
			}
		}
		
		$fields_with_same_code = $customfields->fields_with_same_code();
		if(!empty($fields_with_same_code)){
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('COM_SEMINARMAN_FIELD_CODE_UNIQUE'), 'error');
			if(isset($fields_with_same_code['booking'])){
				foreach($fields_with_same_code['booking'] as $key => $value){
					$app->enqueueMessage(JText::sprintf('COM_SEMINARMAN_FIELD_CODE_REPEATED', $key, JText::_('COM_SEMINARMAN_BOOKINGS'), $value), 'error');
				}
			}
			if(isset($fields_with_same_code['sp'])){
				foreach($fields_with_same_code['sp'] as $key => $value){
					$app->enqueueMessage(JText::sprintf('COM_SEMINARMAN_FIELD_CODE_REPEATED', $key, JText::_('COM_SEMINARMAN_LST_OF_SALES_PROSPECTS'), $value), 'error');
				}
			}
			if(isset($fields_with_same_code['tutor'])){
				foreach($fields_with_same_code['tutor'] as $key => $value){
					$app->enqueueMessage(JText::sprintf('COM_SEMINARMAN_FIELD_CODE_REPEATED', $key, JText::_('COM_SEMINARMAN_TUTOR_PROFILE'), $value), 'error');
				}
			}
		}
		
		$fields_with_diff_type = $customfields->fields_with_diff_type();
		if(!empty($fields_with_diff_type)){
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('COM_SEMINARMAN_FIELD_CODE_REPEAT_ALLOWED'), 'error');
			foreach($fields_with_diff_type as $f_diff) {
				$app->enqueueMessage(JText::sprintf('COM_SEMINARMAN_FIELD_CODE_REPEAT_CONFLICT', $f_diff['name1'], $f_diff['name2'], $f_diff['fieldcode']), 'error');
			}
		}
		
		parent::display( $tpl );
	}

	/**
	 * Method to get the Field type in text
	 *
	 * @param	string	Type of field
	 *
	 * @return	string	Text representation of the field type.
	 **/
	function getFieldText( $type )
	{
		$model	= $this->getModel( 'Editfields' );
		$types	= $model->getCustomfieldsTypes();
		$value	= isset( $types[ $type ] ) ? $types[ $type ] : '';

		return $value;
// 		switch( $type )
// 		{
// 			case 'list':
// 				$type	= JText::_('MULTIPLE SELECT');
// 				break;
// 			case 'select':
// 				$type	= JText::_('SELECT');
// 				break;
// 			case 'text':
// 				$type	= JText::_('TEXTBOX');
// 				break;
// 			case 'radio':
// 				$type	= JText::_('RADIO');
// 				break;
// 			case 'checkbox':
// 				$type	= JText::_('CHECKBOX');
// 				break;
// 			case 'date':
// 			 	$type	= JText::_('DATE');
// 			 	break;
// 			case 'textarea':
// 				$type	= JText::_('TEXTAREA');
// 				break;
// 			case 'url':
// 				$type	= JText::_('URL');
// 				break;
// 			case 'country':
// 				$type	= JText::_('COUNTRY');
// 				break;
// 			case 'email':
// 				$type	= JText::_('EMAIL');
// 				break;
// 			case 'time':
// 				$type	= JText::_('TIME');
// 				break;
// 			default:
// 				$type	= JText::_('UNKNOWN');
// 				break;
// 		}
// 		return $type;
	}

	/**
	 * Method to get the publish status HTML
	 *
	 * @param	object	Field object
	 * @param	string	Type of the field
	 * @param	string	The ajax task that it should call
	 * @return	string	HTML source
	 **/
	function getPublish( &$row , $type )
	{

		$imgY	= 'tick.png';
		$imgX	= 'publish_x.png';
		$rowf = (int)$row->id;

		switch ($type)
		{
			case 'published':
				// publish/unpublish
				$taskoption= $row->$type ? "unpublish()" : "publish()";
				$alt	= $row->$type ? $rowf.JText::_('JPUBLISHED') : $rowf.JText::_('JUNPUBLISHED');
				break;

			case 'visible':
				// publish/unpublish
				$taskoption= $row->$type ? "unvisible()" : "visible()";
				$alt	= $row->$type ? $rowf.JText::_('JPUBLISHED') : $rowf.JText::_('JUNPUBLISHED');
				break;

			case 'required':
				// publish/unpublish
				$taskoption= $row->$type ? "unrequire()" : "require()";
				$alt	= $row->$type ? $rowf.JText::_('JPUBLISHED') : $rowf.JText::_('JUNPUBLISHED');
				break;
		}

		$image	= $row->$type ? $imgY : $imgX;

		//$href = '<a href="javascript:void(0);" onclick="return listItemTask(\'cb'. $rowf . '\',\''.$taskoption.'\');">';
		//$href  .= '<span><img src="images/' . $image . '" border="0" alt="' . $alt . '" /></span></a>';
		$href  = '<img src="images/' . $image . '" border="0" alt="' . $alt . '" />';

		return $href;
	}


}