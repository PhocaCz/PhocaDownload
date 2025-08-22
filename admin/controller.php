<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 defined('_JEXEC') or die();
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\Helpers\Sidebar;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\MVC\Controller\BaseController;
jimport('joomla.application.component.controller');
$app		= Factory::getApplication();
$option 	= $app->getInput()->get('option');

$l['cp']		= array('COM_PHOCADOWNLOAD_CONTROL_PANEL', '');
$l['f']			= array('COM_PHOCADOWNLOAD_FILES', 'phocadownloadfiles');
$l['c']			= array('COM_PHOCADOWNLOAD_CATEGORIES', 'phocadownloadcats');
$l['l']			= array('COM_PHOCADOWNLOAD_LICENSES', 'phocadownloadlics');
$l['st']		= array('COM_PHOCADOWNLOAD_STATISTICS', 'phocadownloadstat');
$l['d']			= array('COM_PHOCADOWNLOAD_DOWNLOADS', 'phocadownloaddownloads');
$l['u']			= array('COM_PHOCADOWNLOAD_UPLOADS', 'phocadownloaduploads');
$l['fr']		= array('COM_PHOCADOWNLOAD_FILE_RATING', 'phocadownloadrafile');
$l['t']			= array('COM_PHOCADOWNLOAD_TAGS', 'phocadownloadtags');
//$l['ly']		= array('COM_PHOCADOWNLOAD_LAYOUT', 'phocadownloadlayouts');
$l['sty']		= array('COM_PHOCADOWNLOAD_STYLES', 'phocadownloadstyles');
$l['log']		= array('COM_PHOCADOWNLOAD_LOGGING', 'phocadownloadlogs');
$l['in']		= array('COM_PHOCADOWNLOAD_INFO', 'phocadownloadinfo');

// Submenu view
//$view	= JFactory::getApplication()->getInput()->get( 'view', '', '', 'string', J R EQUEST_ALLOWRAW );
//$layout	= JFactory::getApplication()->getInput()->get( 'layout', '', '', 'string', J R EQUEST_ALLOWRAW );
$view	= Factory::getApplication()->getInput()->get('view');
$layout	= Factory::getApplication()->getInput()->get('layout');

if ($layout == 'edit') {
} else {
	foreach ($l as $k => $v) {

		if ($v[1] == '') {
			$link = 'index.php?option='.$option;
		} else {
			$link = 'index.php?option='.$option.'&view=';
		}

		if ($view == $v[1]) {
			Sidebar::addEntry(Text::_($v[0]), $link.$v[1], true );
		} else {
			Sidebar::addEntry(Text::_($v[0]), $link.$v[1]);
		}
	}
}

class PhocadownloadCpController extends BaseController {
	function display($cachable = false, $urlparams = array()) {
		parent::display($cachable , $urlparams);
	}
}
?>
