<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined( '_JEXEC' ) or die();
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\HTML\HTMLHelper;
jimport( 'joomla.application.component.view' );

class PhocaDownloadCpViewPhocaDownloadFiles extends HtmlView
{

	protected $items;
	protected $pagination;
	protected $state;
	protected $t;
	protected $r;
	public $filterForm;
    public $activeFilters;

	function display($tpl = null) {

		$this->t			= PhocaDownloadUtils::setVars('file');
		$this->r 			= new PhocaDownloadRenderAdminViews();
		$this->items		= $this->get('Items');
        $this->pagination	= $this->get('Pagination');
        $this->state		= $this->get('State');
        $this->filterForm   = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

		// Preprocess the list of items to find ordering divisions.
		foreach ($this->items as &$item) {
			$this->ordering[$item->catid][] = $item->id;
		}


		$this->tmpl['notapproved'] 	= $this->get( 'NotApprovedFile' );

		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar() {

		require_once JPATH_COMPONENT.'/helpers/'.$this->t['tasks'].'.php';
		$state	= $this->get('State');
		$class	= ucfirst($this->t['tasks']).'Helper';
		$canDo	= $class::getActions($this->t, $state->get('filter.file_id'));
		$user  = Factory::getUser();
		$bar = Toolbar::getInstance('toolbar');

		ToolbarHelper::title( Text::_($this->t['l'].'_FILES'), 'file.png' );
		if ($canDo->get('core.create')) {
			ToolbarHelper::addNew( $this->t['task'].'.add','JTOOLBAR_NEW');
			ToolbarHelper::addNew( $this->t['task'].'.addtext', $this->t['l'].'_ADD_TEXT');
			ToolbarHelper::custom( $this->t['c'].'m.edit', 'multiple.png', '', $this->t['l'].'_MULTIPLE_ADD' , false);
		}

		if ($canDo->get('core.edit')) {
			ToolbarHelper::editList($this->t['task'].'.edit','JTOOLBAR_EDIT');
		}

		if ($canDo->get('core.create')) {
			//JToolbarHelper::divider();
			//JToolbarHelper::custom( $this->t['task'].'.copyquick','copy.png', '', $this->t['l'].'_QUICK_COPY', true);
			//JToolbarHelper::custom( $this->t['task'].'.copy','copy.png', '', $this->t['l'].'_COPY', true);
		}

		$dropdown = $bar->dropdownButton('status-group')->text('JTOOLBAR_CHANGE_STATUS')->toggleSplit(false)->icon('icon-ellipsis-h')->buttonClass('btn btn-action')->listCheck(true);
		$childBar = $dropdown->getChildToolbar();

		if ($canDo->get('core.edit.state')) {

			//ToolbarHelper::divider();
			$childBar->publish($this->t['tasks'].'.publish')->listCheck(true);
			$childBar->unpublish($this->t['tasks'].'.unpublish')->listCheck(true);
			$childBar->standardButton('approve')->text($this->t['l'].'_APPROVE')->task($this->t['tasks'].'.approve')->listCheck(true);
			$childBar->standardButton('disapprove')->text($this->t['l'].'_NOT_APPROVE')->task($this->t['tasks'].'.disapprove')->listCheck(true);
			//ToolbarHelper::custom($this->t['tasks'].'.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			//ToolbarHelper::custom($this->t['tasks'].'.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			//ToolbarHelper::custom( $this->t['tasks'].'.approve', 'approve.png', '',  $this->t['l'].'_APPROVE' , true);
			//ToolbarHelper::custom( $this->t['tasks'].'.disapprove', 'disapprove.png', '',  $this->t['l'].'_NOT_APPROVE' , true);
		}

		if ($canDo->get('core.delete')) {
			$childBar->delete($this->t['tasks'].'.delete')->text($this->t['l'].'_DELETE')->message( $this->t['l'].'_WARNING_DELETE_ITEMS')->icon('icon-trash')->listCheck(true);
			//ToolbarHelper::deleteList( Text::_( $this->t['l'].'_WARNING_DELETE_ITEMS' ), $this->t['tasks'].'.delete', $this->t['l'].'_DELETE');
		}

		// Add a batch button
		if ($user->authorise('core.edit'))
		{

			/*$title = Text::_('JTOOLBAR_BATCH');

			$dhtml = '<joomla-toolbar-button id="toolbar-batch" list-selection>';
			$dhtml .= "<button data-bs-toggle=\"modal\" data-bs-target=\"#collapseModal\" class=\"btn btn-small\">
						<i class=\"icon-checkbox-partial\" title=\"$title\"></i>
						$title</button>";
			$dhtml .= '</joomla-toolbar-button>';

			$bar->appendButton('Custom', $dhtml, 'batch');*/


			/*$bar->popupButton('batch')
				->text('JTOOLBAR_BATCH')
				->selector('collapseModal')
				->listCheck(true);*/
			$childBar->popupButton('batch')->text('JTOOLBAR_BATCH')->selector('collapseModal')->listCheck(true);


			/*HTMLHelper::_('bootstrap.renderModal', 'collapseModal');
			$title = \JText::_('JTOOLBAR_BATCHx');
			$layout = new \JLayoutFile('joomla.toolbar.batch');
			$dhtml = $layout->render(array('title' => $title));
			\JToolbar::getInstance('toolbar')->appendButton('Custom', $dhtml, 'batch');*/

		}

		ToolbarHelper::divider();
		ToolbarHelper::help( 'screen.'.$this->t['c'], true );
	}

	protected function getSortFields() {
		return array(
			'a.ordering'	=> Text::_('JGRID_HEADING_ORDERING'),
			'a.title' 		=> Text::_($this->t['l'] . '_TITLE'),
			'a.filename' 	=> Text::_($this->t['l'] . '_FILENAME'),
			'a.date' 		=> Text::_($this->t['l'] . '_DATE'),
			'a.hits' 		=> Text::_($this->t['l'] . '_DOWNLOADS'),
			'a.owner_id'	=> Text::_($this->t['l'] . '_OWNER'),
			'uploadusername'=> Text::_($this->t['l'] . '_UPLOADED_BY'),
			'a.published' 	=> Text::_($this->t['l'] . '_PUBLISHED'),
			'a.approved' 	=> Text::_($this->t['l'] . '_APPROVED'),
			'category_id' 	=> Text::_($this->t['l'] . '_CATEGORY'),
			'language' 		=> Text::_('JGRID_HEADING_LANGUAGE'),
			'a.id' 			=> Text::_('JGRID_HEADING_ID')
		);
	}
}
?>
