<?php
/**
 * @version $Id: 1.5.4 2009-10-15
 * @package			Course Manager
 * @subpackage		Component
 * @author			Profinvent {@link http://www.profinvent.com}
 * @copyright 		(C) Profinvent - Joomla Experts
 * @license   		GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the seminarman component
 *
 * @static
 * @package		Course Manager
 * @subpackage	seminarman
 * @since 1.5.0
 */
class seminarmanViewCategory extends JViewLegacy
{
	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();

		$document = JFactory::getDocument();

		$document->link = JRoute::_('index.php?option=com_seminarman&view=category&id='.JRequest::getVar('id',null, '', 'int'));

		JRequest::setVar('limit', $mainframe->getCfg('feed_limit'));

		// Get data from the model
		$items		= $this->get( 'data' );
		$category	= $this->get( 'category' );

		foreach ( $items as $item )
		{
			// strip html from feed item title
			$title = $this->escape( $item->title );
			$title = html_entity_decode( $title );

			// url link to article
			//$link = JRoute::_('index.php?option=com_seminarman&view=course&id='. $item->id );

			$link = JRoute::_('index.php?option=com_seminarman&view=category&id='. JRequest::getVar('id',null, '', 'int'));

			// strip html from feed item description text
			$description = $item->description;
			$date = ( $item->date ? date( 'r', strtotime($item->date) ) : '' );

			// load individual item creator class
			$feeditem = new JFeedItem();
			$feeditem->title 		= $title;
			$feeditem->link 		= $link;
			$feeditem->description 	= $description;
			$feeditem->date			= $date;
			$feeditem->category   	= 'seminarman';

			// loads item info into rss array
			$document->addItem( $feeditem );
		}
	}
}
?>