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

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
jimport( 'joomla.application.component.view' );
phocadownloadimport('phocadownload.render.renderadminviews');

class phocaDownloadViewphocaDownloadLinkYtb extends HtmlView
{

	protected $t;
	protected $r;

	function display($tpl = null) {
		$app	= Factory::getApplication();
		$this->r = new PhocaDownloadRenderAdminViews();
		$this->t = PhocaDownloadUtils::setVars('linkytb');
		$document	= Factory::getDocument();
		$uri		= Uri::getInstance();
		//Frontend Changes
		$tUri = '';
		if (!$app->isClient('administrator')) {
			$tUri = Uri::base();

		}

		$editor    = $app->input->getCmd('editor', '');
		if (!empty($editor)) {
			$this->document->addScriptOptions('xtd-phocadownload', array('editor' => $editor));
		}


		HTMLHelper::stylesheet( 'media/com_phocadownload/css/administrator/phocadownload.css' );
		HTMLHelper::stylesheet( 'media/plg_editors-xtd_phocadownload/css/phocadownload.css' );


		$eName				= Factory::getApplication()->input->get('editor');
		$this->t['ename']		= preg_replace( '#[^A-Z0-9\-\_\[\]]#i', '', $eName );
		$this->t['backlink']	= $tUri.'index.php?option=com_phocadownload&amp;view=phocadownloadlinks&amp;tmpl=component&amp;editor='.$this->t['ename'];


		parent::display($tpl);
	}
}
?>
