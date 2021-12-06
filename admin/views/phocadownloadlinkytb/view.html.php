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
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
jimport( 'joomla.application.component.view' );

class phocaDownloadCpViewphocaDownloadLinkYtb extends HtmlView
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
		

		$eName				= Factory::getApplication()->input->get('e_name');
		$this->t['ename']		= preg_replace( '#[^A-Z0-9\-\_\[\]]#i', '', $eName );
		$this->t['backlink']	= $tUri.'index.php?option=com_phocadownload&amp;view=phocadownloadlinks&amp;tmpl=component&amp;e_name='.$this->t['ename'];

		//$this->assignRef('tmpl',	$this->t);
		parent::display($tpl);
	}
}
?>
