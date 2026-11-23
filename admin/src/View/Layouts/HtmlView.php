<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
namespace Phoca\Component\Phocadownload\Administrator\View\Layouts;


defined('_JEXEC') or die();
use Joomla\CMS\MVC\View\HtmlView AS BaseHtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Phoca\Component\Phocadownload\Administrator\Helper\LayoutsHelper;
/* jimport( 'joomla.html.pane' ); */
/* jimport( 'joomla.application.component.view' ); */

class HtmlView extends BaseHtmlView
{
	protected $items;

	function display($tpl = null) {

		//require_once JPATH_COMPONENT.'/helpers/phocadownloadlayouts.php';
		$idString 	= LayoutsHelper::getTableId();
		$app		= Factory::getApplication();
		$app->redirect(Route::_('index.php?option=com_phocadownload&view=layout&task=phocadownloadlayout.edit'.$idString, false));
		return;
	}
}
?>
