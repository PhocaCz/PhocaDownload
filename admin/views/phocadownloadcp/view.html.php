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
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\Toolbar;
jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.pane' );

class PhocaDownloadCpViewPhocaDownloadCp extends HtmlView
{
	protected $t;
	protected $r;
	protected $views;

	public function display($tpl = null) {

		$this->t	= PhocaDownloadUtils::setVars('cp');
		$this->r	= new PhocaDownloadRenderAdminview();
		$i = ' icon-';
		$d = 'duotone ';

		$this->views= array(
		'files'		=> array($this->t['l'] . '_FILES', $d.$i.'archive', '#c1a46d'),
		'cats'		=> array($this->t['l'] . '_CATEGORIES', $d.$i.'folder-open', '#da7400'),
		'lics'		=> array($this->t['l'] . '_LICENSES', $d.$i.'file-check', '#fb1000'),
		'stat'		=> array($this->t['l'] . '_STATISTICS', $d.$i.'chart', '#8c0069'),
		'downloads'	=> array($this->t['l'] . '_DOWNLOADS', $i.'box-remove', '#33af49'),
		'uploads'	=> array($this->t['l'] . '_UPLOADS', $i.'box-add', '#ff9326'),
		'rafile'	=> array($this->t['l'] . '_FILE_RATING', $i.'featured', '#FFC93C'),
		'tags'		=> array($this->t['l'] . '_TAGS', $d.$i.'tag-double', '#CC0033'),
		//'layouts'	=> array($this->t['l'] . '_LAYOUT', $d.$i.'modules', '#cd76cc'),
		'styles'	=> array($this->t['l'] . '_STYLES', $i.'styles', '#9900CC'),
		'logs'		=> array($this->t['l'] . '_LOGGING', $d.$i.'logs', '#c0c0c0'),
		'info'		=> array($this->t['l'] . '_INFO', $d.$i.'info-circle', '#3378cc')
		);


		$this->t['version'] = PhocaDownloadUtils::getExtensionVersion();
		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar() {
		require_once JPATH_COMPONENT.'/helpers/phocadownloadcp.php';

		$canDo	= PhocaDownloadCpHelper::getActions($this->t['c']);
		ToolbarHelper::title( Text::_( $this->t['l'].'_PD_CONTROL_PANEL' ), 'home-2 cpanel' );

		// This button is unnecessary but it is displayed because Joomla! design bug
		$bar = Toolbar::getInstance( 'toolbar' );
		$dhtml = '<a href="index.php?option=com_phocadownload" class="btn btn-small"><i class="icon-home-2" title="'.Text::_($this->t['l'].'_CONTROL_PANEL').'"></i> '.Text::_($this->t['l'].'_CONTROL_PANEL').'</a>';
		$bar->appendButton('Custom', $dhtml);

		if ($canDo->get('core.admin')) {
			ToolbarHelper::preferences('com_phocadownload');
			ToolbarHelper::divider();
		}
		ToolbarHelper::help( 'screen.phocadownload', true );
	}
}
?>
