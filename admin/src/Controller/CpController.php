<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

namespace Phoca\Component\Phocadownload\Administrator\Controller;

\defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\Helpers\Sidebar;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
/* jimport('joomla.application.component.controller'); */
$app		= Factory::getApplication();
$option 	= $app->getInput()->get('option');

$l['cp']		= array('COM_PHOCADOWNLOAD_CONTROL_PANEL', '');
$l['f']			= array('COM_PHOCADOWNLOAD_FILES', 'dfiles');
$l['c']			= array('COM_PHOCADOWNLOAD_CATEGORIES', 'cats');
$l['l']			= array('COM_PHOCADOWNLOAD_LICENSES', 'lics');
$l['st']		= array('COM_PHOCADOWNLOAD_STATISTICS', 'stat');
$l['d']			= array('COM_PHOCADOWNLOAD_DOWNLOADS', 'downloads');
$l['u']			= array('COM_PHOCADOWNLOAD_UPLOADS', 'uploads');
$l['fr']		= array('COM_PHOCADOWNLOAD_FILE_RATING', 'rafile');
$l['t']			= array('COM_PHOCADOWNLOAD_TAGS', 'tags');
//$l['ly']		= array('COM_PHOCADOWNLOAD_LAYOUT', 'layouts');
$l['sty']		= array('COM_PHOCADOWNLOAD_STYLES', 'styles');
$l['log']		= array('COM_PHOCADOWNLOAD_LOGGING', 'logs');
$l['in']		= array('COM_PHOCADOWNLOAD_INFO', 'info');

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

class CpController extends BaseController {
	function display($cachable = false, $urlparams = array()) {
		parent::display($cachable , $urlparams);
	}
}
?>
