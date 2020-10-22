<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
jimport( 'joomla.application.component.view' );

class PhocaDownloadCpViewPhocaDownloadRaFile extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $t;
	protected $r;
	public $filterForm;
	public $activeFilters;

	function display($tpl = null) {

		$this->t		= PhocaDownloadUtils::setVars('rafile');
		$this->r 		= new PhocaDownloadRenderAdminViews();
		$this->items		= $this->get('Items');
        $this->pagination	= $this->get('Pagination');
        $this->state		= $this->get('State');
        $this->filterForm   = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');


		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors), 500);
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	function addToolbar() {

		require_once JPATH_COMPONENT.'/helpers/'.$this->t['task'].'.php';

		$bar 		= JToolbar::getInstance('toolbar');
		$user		= JFactory::getUser();
		$state	= $this->get('State');
		//$isNew		= ($this->item->id == 0);
		//$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		$class		= ucfirst($this->t['task']).'Helper';
		$canDo		= $class::getActions($this->t, $state->get('filter.category_id'));


		JToolbarHelper::title( JText::_( $this->t['l'].'_FILE_RATING' ), 'star' );

		if ($canDo->get('core.delete')) {
			JToolbarHelper::deleteList(  JText::_( $this->t['l'].'_WARNING_DELETE_ITEMS' ), $this->t['task'].'.delete', $this->t['l'].'_DELETE');
		}
		JToolbarHelper::divider();
		JToolbarHelper::help( 'screen.'.$this->t['c'], true );
	}

	protected function getSortFields() {
		return array(

			'ua.username' 	=> JText::_($this->t['l'] . '_USER'),
			'file_title'	=> JText::_($this->t['l'] . '_FILENAME'),
			'category_title' 	=> JText::_($this->t['l'] . '_CATEGORY'),
			'a.rating' 		=> JText::_($this->t['l'] . '_RATING'),
			'a.id' 			=> JText::_('JGRID_HEADING_ID')
		);
	}
}
?>
