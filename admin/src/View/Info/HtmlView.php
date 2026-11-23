<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
namespace Phoca\Component\Phocadownload\Administrator\View\Info;

use Phoca\Component\Phocadownload\Administrator\Render\Adminview;

defined('_JEXEC') or die();
use Joomla\CMS\MVC\View\HtmlView AS BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\Toolbar;
use Phoca\Component\Phocadownload\Administrator\Utils\Utils;
use Phoca\Component\Phocadownload\Administrator\Helper\CpHelper;

/* jimport( 'joomla.application.component.view' ); */

class HtmlView extends BaseHtmlView
{
	protected $t;
	protected $r;

	function display($tpl = null) {

		$this->t	= Utils::setVars('info');
		$this->r	= new Adminview();
		$this->t['component_head'] 	= 'COM_PHOCADOWNLOAD_PHOCA_DOWNLOAD';
		$this->t['component_links']	= $this->r->getLinks(1);
		$this->t['version'] = Utils::getExtensionVersion();
		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar() {
		//require_once JPATH_COMPONENT.'/helpers/'.$this->t['c'].'cp.php';
		//$class	= $this->t['n'] . 'CpHelper';
		$canDo	= CpHelper::getActions($this->t['c']);

		ToolbarHelper::title( Text::_($this->t['l'].'_PD_INFO' ), 'info' );

		// This button is unnecessary but it is displayed because Joomla! design bug
		$bar = Toolbar::getInstance( 'toolbar' );
		$dhtml = '<a href="index.php?option=com_phocadownload" class="btn btn-primary btn-small"><i class="icon-home-2" title="'.Text::_($this->t['l'].'_CONTROL_PANEL').'"></i> '.Text::_($this->t['l'].'_CONTROL_PANEL').'</a>';
		$bar->appendButton('Custom', $dhtml);

		if ($canDo->get('core.admin')) {
			ToolbarHelper::preferences('com_'.$this->t['c']);
		}
		ToolbarHelper::divider();
		ToolbarHelper::help( 'screen.'.$this->t['c'], true );
	}
}
?>
