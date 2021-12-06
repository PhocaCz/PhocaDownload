<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Factory;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
jimport( 'joomla.application.component.view' );

class PhocaDownloadCpViewPhocaDownloadset extends HtmlView
{
	function display($tpl = null) {


		$uri		= Uri::getInstance();
		$document	= Factory::getDocument();
		$db		    = Factory::getDBO();


		// Get data from the model
		$items		= & $this->get( 'Data');

		//$this->assignRef('items',		$items);
		$this->t['items'] = $items;
		//$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
		$this->_setToolbar();
	}

	function _setToolbar() {
		ToolbarHelper::title(   Text::_( 'Phoca Download Settings' ), 'settings.png' );
		ToolbarHelper::save();
		ToolbarHelper::apply();
		ToolbarHelper::cancel( 'cancel', 'Close' );
		ToolbarHelper::help( 'screen.phocadownload', true );
	}
}
?>
