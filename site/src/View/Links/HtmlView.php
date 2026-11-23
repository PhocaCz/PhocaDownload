<?php
/*
 * @package Joomla
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
namespace Phoca\Component\Phocadownload\Site\View\Links;

defined('_JEXEC') or die();
use Joomla\CMS\MVC\View\HtmlView AS BaseHtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper;
/* jimport( 'joomla.application.component.view' ); */
use Joomla\String\StringHelper;
use Phoca\Component\Phocadownload\Administrator\Category\Category;
use Phoca\Component\Phocadownload\Administrator\Render\Adminviews;
use Phoca\Component\Phocadownload\Administrator\Utils\Utils;

class HtmlView extends BaseHtmlView
{
	public $_context 	= 'com_phocadownload.links';

	protected $t;
	protected $r;

	function display($tpl = null) {
		$app	= Factory::getApplication();
		$this->r = new AdminViews();
		$this->t = Utils::setVars('links');

		//Frontend Changes
		$tUri = '';
		if (!$app->isClient('administrator')) {
			$tUri = Uri::base();

		}

		$editor    = $app->getInput()->getCmd('editor', '');
		if (!empty($editor)) {
			$this->document->addScriptOptions('xtd-phocadownload', array('editor' => $editor));
		}

		$document	= Factory::getDocument();
		$uri		= Uri::getInstance();
		//HTMLHelper::stylesheet( 'media/com_phocadownload/css/administrator/phocadownload.css' );
		//HTMLHelper::stylesheet( 'media/plg_editors-xtd_phocadownload/css/phocadownload.css' );
		$wa 				= $app->getDocument()->getWebAssetManager();
		$wa->registerAndUseStyle('com_phocadownload.phocadownload.admin', 'media/com_phocadownload/css/administrator/phocadownload.css', array('version' => 'auto'));
		$wa->registerAndUseStyle('plg_editors-xtd_phocadownload', 'media/plg_editors-xtd_phocadownload/css/phocadownload.css', array('version' => 'auto'));

		$eName	= Factory::getApplication()->getInput()->get('editor');
		$eName	= preg_replace( '#[^A-Z0-9\-\_\[\]]#i', '', $eName );


		$this->t['linkcategories']	= $tUri.'index.php?option=com_phocadownload&amp;view=linkcats&amp;tmpl=component&amp;editor='.$eName;
		$this->t['linkcategory']	= $tUri.'index.php?option=com_phocadownload&amp;view=linkcat&amp;tmpl=component&amp;editor='.$eName;
		$this->t['linkfile']		= $tUri.'index.php?option=com_phocadownload&amp;view=linkfile&amp;tmpl=component&amp;editor='.$eName;
		$this->t['linkytb']		= $tUri.'index.php?option=com_phocadownload&amp;view=linkytb&amp;tmpl=component&amp;editor='.$eName;


		parent::display($tpl);
	}
}
?>
