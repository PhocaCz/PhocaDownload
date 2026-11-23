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
namespace Phoca\Component\Phocadownload\Site\View\Linkytb;

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
	public $_context 	= 'com_phocadownload.linkytb';

	protected $t;
	protected $r;

	function display($tpl = null) {
		$app	= Factory::getApplication();
		$this->r = new AdminViews();
		$this->t = Utils::setVars('linkytb');
		$document	= Factory::getDocument();
		$uri		= Uri::getInstance();
		//Frontend Changes
		$tUri = '';
		if (!$app->isClient('administrator')) {
			$tUri = Uri::base();

		}

		$editor    = $app->getInput()->getCmd('editor', '');
		if (!empty($editor)) {
			$this->document->addScriptOptions('xtd-phocadownload', array('editor' => $editor));
		}


		//HTMLHelper::stylesheet( 'media/com_phocadownload/css/administrator/phocadownload.css' );
		//HTMLHelper::stylesheet( 'media/plg_editors-xtd_phocadownload/css/phocadownload.css' );
		$wa 				= $app->getDocument()->getWebAssetManager();
		$wa->registerAndUseStyle('com_phocadownload.phocadownload.admin', 'media/com_phocadownload/css/administrator/phocadownload.css', array('version' => 'auto'));
		$wa->registerAndUseStyle('plg_editors-xtd_phocadownload', 'media/plg_editors-xtd_phocadownload/css/phocadownload.css', array('version' => 'auto'));


		$eName				= Factory::getApplication()->getInput()->get('editor');
		$this->t['ename']		= preg_replace( '#[^A-Z0-9\-\_\[\]]#i', '', $eName );
		$this->t['backlink']	= $tUri.'index.php?option=com_phocadownload&amp;view=links&amp;tmpl=component&amp;editor='.$this->t['ename'];


		parent::display($tpl);
	}
}
?>
