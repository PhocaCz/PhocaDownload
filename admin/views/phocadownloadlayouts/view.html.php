<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
jimport( 'joomla.html.pane' );
jimport( 'joomla.application.component.view' );

class PhocaDownloadCpViewPhocaDownloadLayouts extends HtmlView
{
	protected $items;
	
	function display($tpl = null) {
		
		require_once JPATH_COMPONENT.'/helpers/phocadownloadlayouts.php';
		$idString 	= PhocaDownloadLayoutsHelper::getTableId();
		$app		= Factory::getApplication();
		$app->redirect(Route::_('index.php?option=com_phocadownload&view=phocadownloadlayout&task=phocadownloadlayout.edit'.$idString, false));
		return;
	}
}
?>