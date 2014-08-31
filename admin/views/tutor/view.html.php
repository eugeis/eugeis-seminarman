<?php
/**
 * @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
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

jimport('joomla.application.component.view');

class seminarmanViewtutor extends JViewLegacy
{
	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();

		if ($this->getLayout() == 'form')
		{
			$this->_displayForm($tpl);
			return;
		}

		$tutor = $this->get('data');
		
		if ($tutor->url)
		$mainframe->redirect($tutor->url);

		parent::display($tpl);
	}

	function _displayForm($tpl)
	{
		$mainframe = JFactory::getApplication();

		$db = JFactory::getDBO();
		$uri = JFactory::getURI();
		$user = JFactory::getUser();
		$model = $this->getModel();
		$document = JFactory::getDocument();
		$lang = JFactory::getLanguage();

		$document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend.css');
		if ($lang->isRTL())
			$document->addStyleSheet('components/com_seminarman/assets/css/seminarmanbackend_rtl.css');

		$lists = array();

		$tutor = $this->get('data');
		$isNew = ($tutor->id < 1);

		require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_seminarman' . DS . 'helpers' . DS . 'seminarman.php');

		$document->addScript(JURI::base() . 'components/com_seminarman/assets/js/fieldsmanipulation.js');

		if ($model->isCheckedOut($user->get('id')))
		{
			$msg = JText::sprintf('COM_SEMINARMAN_RECORD_EDITED', JText::_('COM_SEMINARMAN_TUTOR'), $tutor->title);
			$mainframe->redirect('index.php?option=' . $option, $msg);
		}

		if (!$isNew)
		{
			$model->checkout($user->get('id'));
			$disabled = 1;
		} else
		{

			$disabled = 0;
			$tutor->published = 1;
			$tutor->approved = 1;
			$tutor->order = 0;
		}

		$query = 'SELECT ordering AS value, title AS text' . ' FROM #__seminarman_tutor' . ' ORDER BY ordering';
		
		$lists['templates_add'] = JHTMLSeminarman::getSelectTemplate('template_id');
		$lists['qualified_templates'] = $this->get('Templates');
		
		// $lists['ordering'] = JHtml::_('list.specificordering', $tutor, $tutor->id, $query);
		$lists['ordering'] = JHtml::_('list.ordering', $tutor->id, $query);

		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $tutor->published);
		$lists['username'] = JHTMLSeminarman::getSelectUserForTrainer('juser_id', $tutor->user_id, 0);
		$lists['salutation'] = JHTMLSeminarman::getListFromXML('Salutation', 'salutation', 0, $tutor->salutation);
		$lists['country'] = JHTMLSeminarman::getSelectCountry('id_country', $tutor->id_country, '');
		$lists['company_type'] = JHTMLSeminarman::getSelectCompType('id_comp_type', $tutor->id_comp_type, '');
		$lists['industry'] = JHTMLSeminarman::getListFromXML('Industry', 'industry', 0, $tutor->industry);
		$lists['billing_country'] = JHTMLSeminarman::getSelectCountry('bill_id_country', $tutor->bill_id_country, '');
        // $lists['juserstate'] = JHTML::_('select.booleanlist', 'state', 'enabled', true);
        $juserstate = JHTMLSeminarman::getJUserState($tutor->id, 'juserstate');
        $lists['juserstate'] = $juserstate['selection'];
        $lists['jusername'] = $juserstate['username'];
        $lists['jpassword1'] = $juserstate['password1'];
        $lists['jpassword2'] = $juserstate['password2'];
        $lists['jemail'] = $juserstate['email'];
        $lists['juid'] = $juserstate['userid'];
        $lists['method'] = $juserstate['method'];

	    // Let's check if the virtuemart component exists.
        // jimport('joomla.application.component.helper');
        // $component = JComponentHelper::getComponent('com_virtuemart', true);
        $params = JComponentHelper::getParams('com_seminarman');
        // if (($component->enabled) && ($params->get('trigger_virtuemart') == 1)) {
        if ((SeminarmanFunctions::isVMEnabled()) && ($params->get('trigger_virtuemart') == 1)) {        
            $lists['invm'] = '<tr><td><label for="invm">VirtueMart Publisher</label></td><td><fieldset class="radio">'.$juserstate["invm"].'</fieldset></td></tr>';
        } else {
        	$lists['invm'] = '';
        }
		
		JFilterOutput::objectHTMLSafe($tutor, ENT_QUOTES, 'description');
		
		$data = new stdClass();
		$data->customfields = $model->getEditableCustomfields($tutor->id);
		CMFactory::load('libraries' , 'customfields');
		
		$fields = $data->customfields ['fields'];
		$this->assignRef('fields', $fields);
		$this->assignRef('lists', $lists);
		$this->assignRef('tutor', $tutor);

		parent::display($tpl);
	}
}