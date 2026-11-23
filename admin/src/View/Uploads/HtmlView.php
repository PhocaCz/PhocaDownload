<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
namespace Phoca\Component\Phocadownload\Administrator\View\Uploads;

defined('_JEXEC') or die();
use Joomla\CMS\MVC\View\HtmlView AS BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\Toolbar;
use Phoca\Component\Phocadownload\Administrator\Render\Adminviews;
use Phoca\Component\Phocadownload\Administrator\Utils\Utils;
use Phoca\Component\Phocadownload\Administrator\Helper\UploadsHelper;

/* jimport( 'joomla.application.component.view' ); */

/* jimport( 'joomla.filesystem.file' ); */
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

		$this->t			= Utils::setVars('upload');
		$this->r = new AdminViews();
		$this->items		= $this->get('Items');
        $this->pagination	= $this->get('Pagination');
        $this->state		= $this->get('State');
        $this->filterForm   = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');



		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new \Exception(implode("\n", $errors), 500);
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);

	}

	function addToolbar() {

		//require_once JPATH_COMPONENT.'/helpers/'.$this->t['tasks'].'.php';
		//$state	= $this->get('State');
		//$class	= ucfirst($this->t['tasks']).'Helper';
		$canDo	= UploadsHelper::getActions($this->t);

		ToolbarHelper::title( Text::_( $this->t['l'].'_UPLOADS' ), 'upload' );

		if ($canDo->get('core.admin')) {

			$bar = Toolbar::getInstance('toolbar');

			$dhtml = '<button class="btn btn-small btn-success" onclick="javascript:if(confirm(\''.addslashes(Text::_('COM_PHOCADOWNLOAD_WARNING_AUTHORIZE_ALL')).'\')){Joomla.submitbutton(\'uploads.approveall\');}" ><i class="icon-approve" title="'.Text::_('COM_PHOCADOWNLOAD_APPROVE_ALL').'"></i> '.Text::_('COM_PHOCADOWNLOAD_APPROVE_ALL').'</button>';
			$bar->appendButton('Custom', $dhtml);


			ToolbarHelper::divider();
		}


		ToolbarHelper::help( 'screen.'.$this->t['c'], true );
	}

	protected function getSortFields() {
		return array(

			//'d.title' 		=> JText::_($this->t['l'] . '_TITLE'),
			//'d.filename' 	=> JText::_($this->t['l'] . '_FILENAME'),
			//'usernameno'	=> JText::_($this->t['l'] . '_USER'),
			'username'		=> Text::_($this->t['l'] . '_USERNAME')
			//'a.count'	 	=> JText::_($this->t['l'] . '_COUNT')

		);
	}
}
?>
