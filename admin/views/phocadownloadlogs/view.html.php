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
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\Toolbar;
jimport( 'joomla.application.component.view' );

class PhocaDownloadCpViewPhocaDownloadLogs extends HtmlView
{
	protected $items;
	protected $pagination;
	protected $state;
	//protected $maxandsum;
	protected $t;
	protected $r;
	public $filterForm;
	public $activeFilters;

	function display($tpl = null) {

		$this->t			= PhocaDownloadUtils::setVars('log');
		$this->r 			= new PhocaDownloadRenderAdminViews();
        $this->items		= $this->get('Items');
        $this->pagination	= $this->get('Pagination');
        $this->state		= $this->get('State');
        $this->filterForm   = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');
		$this->t['p']       = ComponentHelper::getParams('com_phocadownload');



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

		ToolbarHelper::title( Text::_( $this->t['l'].'_LOGGING' ), 'file-2' );

		if ($canDo->get('core.edit')){

			$bar = Toolbar::getInstance('toolbar');

			$dhtml = '<button class="btn btn-small" onclick="javascript:if(confirm(\''.addslashes(Text::_('COM_PHOCADOWNLOAD_WARNING_RESET_LOG')).'\')){Joomla.submitbutton(\'phocadownloadlogs.reset\');}" ><i class="icon-approve" title="'.Text::_('COM_PHOCADOWNLOAD_RESET_LOG').'"></i> '.Text::_('COM_PHOCADOWNLOAD_RESET_LOG').'</button>';
			$bar->appendButton('Custom', $dhtml);
			ToolbarHelper::divider();
			//JToolbarHelper::custom('phocadownloaduserstat.reset', 'reset.png', '', 'COM_PHOCADOWNLOAD_RESET' , false);
		}

		//JToolbarHelper::cancel($this->t['tasks'].'.cancel', 'JTOOLBAR_CLOSE');

		ToolbarHelper::help( 'screen.'.$this->t['c'], true );
	}

	protected function getSortFields() {
		return array(
			'a.date'	 	=> Text::_($this->t['l'] . '_DATE'),
			//'usernameno'	=> JText::_($this->t['l'] . '_USER'),
			'username'		=> Text::_($this->t['l'] . '_USERNAME'),
			//'d.title'		=> JText::_($this->t['l'] . '_TITLE'),
			'filename'		=> Text::_($this->t['l'] . '_FILENAME'),
			'category_id'	=> Text::_($this->t['l'] . '_CATEGORY'),
			'a.ip'	 		=> Text::_($this->t['l'] . '_IP'),
			'a.page'	 	=> Text::_($this->t['l'] . '_PAGE'),
			'a.type'	 	=> Text::_($this->t['l'] . '_TYPE'),
			'a.id'	 		=> Text::_($this->t['l'] . '_ID')

		);
	}

}
?>
