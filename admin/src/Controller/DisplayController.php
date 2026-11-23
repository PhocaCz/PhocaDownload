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

//use Joomla\CMS\Factory;
//use Joomla\CMS\HTML\Helpers\Sidebar;
//use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
/* \jimport('joomla.application.component.controller');


$l['cp']	= array('COM_PHOCAEMAIL_CONTROL_PANEL', '');
$l['w']		= array('COM_PHOCAEMAIL_SEND_EMAIL', 'phocaemailwrite');
$l['sn']	= array('COM_PHOCAEMAIL_SEND_NEWSLETTER', 'phocaemailsendnewsletter');
$l['n']		= array('COM_PHOCAEMAIL_NEWSLETTERS', 'phocaemailnewsletters');
$l['s']		= array('COM_PHOCAEMAIL_SUBSCRIBERS', 'Subscribers');
$l['l']		= array('COM_PHOCAEMAIL_MAILING_LISTS', 'phocaemaillists');
$l['in']	= array('COM_PHOCAEMAIL_INFO', 'phocaemailinfo');

$view	= Factory::getApplication()->getInput()->get('view');
$layout	= Factory::getApplication()->getInput()->get('layout');


if ($layout == 'edit') {

} else {

	foreach ($l as $k => $v) {

		if ($v[1] == '') {
			$link = 'index.php?option=com_phocaemail';
		} else {
			$link = 'index.php?option=com_phocaemail&view=';
		}

		if ($view == $v[1]) {
			Sidebar::addEntry(Text::_($v[0]), $link.$v[1], true );
		} else {
			Sidebar::addEntry(Text::_($v[0]), $link.$v[1]);
		}

	}
} */

class DisplayController extends BaseController {
	protected $default_view = 'Cp';
	function display($cachable = false, $urlparams = array()) {
		parent::display($cachable, $urlparams);
	}
}
