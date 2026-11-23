<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
namespace Phoca\Component\Phocadownload\Administrator\View\Layout;

defined('_JEXEC') or die();

use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\MVC\View\HtmlView AS BaseHtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use Phoca\Component\Phocadownload\Administrator\Render\Adminview;
use Phoca\Component\Phocadownload\Administrator\Utils\Utils;
use Phoca\Component\Phocadownload\Administrator\Helper\LayoutsHelper;

/* jimport( 'joomla.application.component.view' ); */

class HtmlView extends BaseHtmlView
{
	protected $state;
	protected $item;
	protected $form;
	protected $t;
	protected $r;

	public function display($tpl = null) {

		$this->t		= Utils::setVars('layout');
		$this->r = new Adminview();
		$this->state	= $this->get('State');
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');



		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar() {

		//require_once JPATH_COMPONENT.'/helpers/'.$this->t['tasks'].'.php';
		Factory::getApplication()->getInput()->set('hidemainmenu', true);
		$bar 		= Toolbar::getInstance('toolbar');
		$app = Factory::getApplication();
		$user		= $app->getIdentity();
		//$isNew		= ($this->item->id == 0);
		//$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		//$class		= ucfirst($this->t['tasks']).'Helper';
		$canDo		= LayoutsHelper::getActions($this->t);

		ToolbarHelper::title(   Text::_( $this->t['l'].'_LAYOUT' ), 'file-2' );
		ToolbarHelper::custom($this->t['task'].'.back', 'home-2', '', $this->t['l'].'_CONTROL_PANEL', false);
		//JToolbarHelper::cancel('phocadownloadlayout.cancel', 'JTOOLBAR_CANCEL');

		if ($canDo->get('core.edit')) {
			ToolbarHelper::apply($this->t['task'].'.apply', 'JTOOLBAR_APPLY');
			//JToolbarHelper::save('phocapdfplugin.save', 'JTOOLBAR_SAVE');
		}
		ToolbarHelper::divider();
		ToolbarHelper::help( 'screen.'.$this->t['c'], true );
	}
}
?>
