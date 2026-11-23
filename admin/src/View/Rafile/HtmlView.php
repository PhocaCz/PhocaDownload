<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
namespace Phoca\Component\Phocadownload\Administrator\View\Rafile;

defined('_JEXEC') or die();
use Joomla\CMS\MVC\View\HtmlView AS BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Factory;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use Phoca\Component\Phocadownload\Administrator\Render\Adminviews;
use Phoca\Component\Phocadownload\Administrator\Utils\Utils;
use Phoca\Component\Phocadownload\Administrator\Helper\RaFileHelper;

/* jimport( 'joomla.application.component.view' ); */

class HtmlView extends BaseHtmlView
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $t;
	protected $r;
	public $filterForm;
	public $activeFilters;

	function display($tpl = null) {

		$this->t		= Utils::setVars('rafile');
		$this->r 		= new AdminViews();
		$this->items		= $this->get('Items');
        $this->pagination	= $this->get('Pagination');
        $this->state		= $this->get('State');
        $this->filterForm   = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');


		if (count($errors = $this->get('Errors'))) {
			throw new \Exception(implode("\n", $errors), 500);
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	function addToolbar() {

		//require_once JPATH_COMPONENT.'/helpers/'.$this->t['task'].'.php';

		$bar 		= Toolbar::getInstance('toolbar');
		$app = Factory::getApplication();
		$user		= $app->getIdentity();
		$state	= $this->get('State');
		//$isNew		= ($this->item->id == 0);
		//$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		//$class		= ucfirst($this->t['task']).'Helper';
		$canDo		= RaFileHelper::getActions($this->t, $state->get('filter.category_id'));


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
