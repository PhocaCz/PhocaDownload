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

class PhocaDownloadCpViewPhocaDownloadDownloads extends HtmlView
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $maxandsum;
	protected $t;
	protected $r;
	public $filterForm;
	public $activeFilters;

	function display($tpl = null) {

		$this->t			= PhocaDownloadUtils::setVars('download');
		$this->r	= new PhocaDownloadRenderAdminviews();
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$this->filterForm   = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		$this->maxandsum	= $this->get('MaxAndSum');


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
		//$state	= $this->get('State');
		$class	= ucfirst($this->t['tasks']).'Helper';
		$canDo	= $class::getActions($this->t);

		ToolbarHelper::title( Text::_( $this->t['l'].'_DOWNLOADS' ), 'download' );

		if ($canDo->get('core.edit')){

			$bar = Toolbar::getInstance('toolbar');

			$dhtml = '<button class="btn btn-small" onclick="javascript:if(document.adminForm.boxchecked.value==0){alert(\''.Text::_('COM_PHOCADOWNLOAD_SELECT_ITEM_RESET').'\');}else{if(confirm(\''.Text::_('COM_PHOCADOWNLOAD_WARNING_RESET_DOWNLOADS').'\')){Joomla.submitbutton(\''.$this->t['tasks'].'.reset\');}}" ><i class="icon-reset" title="'.Text::_('COM_PHOCADOWNLOAD_RESET').'"></i> '.Text::_('COM_PHOCADOWNLOAD_RESET').'</button>';
			$bar->appendButton('Custom', $dhtml);
			ToolbarHelper::divider();
			//JToolbarHelper::custom('phocadownloaduserstat.reset', 'reset.png', '', 'COM_PHOCADOWNLOAD_RESET' , false);

            if ($canDo->get('core.delete')) {
			    ToolbarHelper::deleteList( Text::_( $this->t['l'].'_WARNING_DELETE_ITEMS' ), $this->t['tasks'].'.delete', $this->t['l'].'_DELETE');
		    }
		}

		//JToolbarHelper::cancel($this->t['tasks'].'.cancel', 'JTOOLBAR_CLOSE');

		ToolbarHelper::help( 'screen.'.$this->t['c'], true );
	}

	protected function getSortFields() {
		return array(

			'username'		=> Text::_($this->t['l'] . '_USERNAME'),
			'a.count'	 	=> Text::_($this->t['l'] . '_COUNT'),
			'filename'		=> Text::_($this->t['l'] . '_FILENAME')

		);
	}

}
?>
