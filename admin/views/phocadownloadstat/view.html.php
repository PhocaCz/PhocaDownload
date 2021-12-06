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
jimport( 'joomla.application.component.view' );

class PhocaDownloadCpViewPhocaDownloadStat extends HtmlView
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $maxandsum;
	protected $t;
	protected $r;

	function display($tpl = null) {

		$this->t			= PhocaDownloadUtils::setVars('stat');
		$this->r = new PhocaDownloadRenderAdminViews();
        $this->items		= $this->get('Items');
        $this->pagination	= $this->get('Pagination');
        $this->state		= $this->get('State');
        $this->filterForm   = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');
		$this->maxandsum	= $this->get('MaxAndSum');

		foreach ($this->items as &$item) {
			if ($item->textonly == 0) {
				$this->ordering[0][] = $item->id;
			}
		}



		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors), 500);
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	function addToolbar() {
		require_once JPATH_COMPONENT.'/helpers/'.$this->t['task'].'.php';
		$class	= ucfirst($this->t['task']).'Helper';
		$canDo	= $class::getActions($this->t);
		ToolbarHelper::title( Text::_( $this->t['l'].'_STATISTICS' ), 'chart' );
		ToolbarHelper::custom($this->t['task'].'.back', 'home-2', '', $this->t['l'].'_CONTROL_PANEL', false);
	//	JToolbarHelper::cancel($this->t['task'].'.cancel', 'JTOOLBAR_CLOSE');
		ToolbarHelper::divider();
		ToolbarHelper::help( 'screen.'.$this->t['c'], true );
	}

	protected function getSortFields() {
		return array(
			/*'a.ordering'	=> JText::_('JGRID_HEADING_ORDERING'),*/
			'a.title' 		=> Text::_($this->t['l'] . '_TITLE'),
			'a.filename' 	=> Text::_($this->t['l'] . '_FILENAME'),
			'a.hits' 		=> Text::_($this->t['l'] . '_DOWNLOADS')
			/*'a.id' 			=> JText::_('JGRID_HEADING_ID')*/
		);
	}
}
?>
