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

class PhocaDownloadCpViewPhocaDownloadStyles extends HtmlView
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $t;
	protected $r;
	public $filterForm;
    public $activeFilters;


	function display($tpl = null) {

		$this->t			= PhocaDownloadUtils::setVars('style');
		$this->r 			= new PhocaDownloadRenderAdminViews();
		$model				= $this->getModel();
		$model->checkItems();
        $this->items		= $this->get('Items');
        $this->pagination	= $this->get('Pagination');
        $this->state		= $this->get('State');
        $this->filterForm   = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');



		foreach ($this->items as &$item) {
			$this->ordering[$item->type][] = $item->id;
		}

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors), 500);
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);

	}

	function addToolbar() {

		require_once JPATH_COMPONENT.'/helpers/'.$this->t['tasks'].'.php';
		$state	= $this->get('State');
		$class	= ucfirst($this->t['tasks']).'Helper';
		$canDo	= $class::getActions($this->t, $state->get('filter.category_id'));

		ToolbarHelper::title( Text::_( $this->t['l'].'_STYLES' ), 'eye' );

		if ($canDo->get('core.create')) {
			ToolbarHelper::addNew( $this->t['task'].'.add','JTOOLBAR_NEW');
		}

		if ($canDo->get('core.edit')) {
			ToolbarHelper::editList($this->t['task'].'.edit','JTOOLBAR_EDIT');
		}
		if ($canDo->get('core.edit.state')) {

			ToolbarHelper::divider();
			ToolbarHelper::custom($this->t['tasks'].'.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			ToolbarHelper::custom($this->t['tasks'].'.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
		}

		if ($canDo->get('core.delete')) {
			ToolbarHelper::deleteList(  Text::_( $this->t['l'].'_WARNING_DELETE_ITEMS' ), $this->t['tasks'].'.delete',  $this->t['l'].'_DELETE');
		}
		ToolbarHelper::divider();
		ToolbarHelper::help( 'screen.'.$this->t['c'], true );
	}

	protected function getSortFields() {
		return array(
			'a.ordering'	=> Text::_('JGRID_HEADING_ORDERING'),
			'a.title'	 	=> Text::_($this->t['l'] . '_TITLE'),
			'a.filename'	=> Text::_($this->t['l'] . '_FILENAME'),
			'a.published'	=> Text::_($this->t['l'] . '_PUBLISHED'),
			'a.type'	 	=> Text::_($this->t['l'] . '_TYPE'),
			//'a.language' 		=> JText::_('JGRID_HEADING_LANGUAGE'),
			'a.id' 			=> Text::_('JGRID_HEADING_ID')
		);
	}
}
?>
