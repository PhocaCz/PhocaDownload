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
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Factory;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
jimport( 'joomla.application.component.view' );

class PhocaDownloadCpViewPhocaDownloadRaFile extends HtmlView
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

		$bar 		= Toolbar::getInstance('toolbar');
		$user		= Factory::getUser();
		$state	= $this->get('State');
		//$isNew		= ($this->item->id == 0);
		//$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		$class		= ucfirst($this->t['task']).'Helper';
		$canDo		= $class::getActions($this->t, $state->get('filter.category_id'));


		ToolbarHelper::title( Text::_( $this->t['l'].'_FILE_RATING' ), 'star' );

		if ($canDo->get('core.delete')) {
			ToolbarHelper::deleteList(  Text::_( $this->t['l'].'_WARNING_DELETE_ITEMS' ), $this->t['task'].'.delete', $this->t['l'].'_DELETE');
		}
		ToolbarHelper::divider();
		ToolbarHelper::help( 'screen.'.$this->t['c'], true );
	}

	protected function getSortFields() {
		return array(

			'ua.username' 	=> Text::_($this->t['l'] . '_USER'),
			'file_title'	=> Text::_($this->t['l'] . '_FILENAME'),
			'category_title' 	=> Text::_($this->t['l'] . '_CATEGORY'),
			'a.rating' 		=> Text::_($this->t['l'] . '_RATING'),
			'a.id' 			=> Text::_('JGRID_HEADING_ID')
		);
	}
}
?>
