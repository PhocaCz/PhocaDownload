<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
namespace Phoca\Component\Phocadownload\Administrator\View\Tag;

defined('_JEXEC') or die();
use Joomla\CMS\MVC\View\HtmlView AS BaseHtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Phoca\Component\Phocadownload\Administrator\Render\Adminview;
use Phoca\Component\Phocadownload\Administrator\Utils\Utils;
use Phoca\Component\Phocadownload\Administrator\Helper\TagsHelper;

/* jimport( 'joomla.application.component.view' ); */

class HtmlView extends BaseHtmlView
{
	protected $state;
	protected $item;
	protected $form;
	protected $t;
	protected $r;

	public function display($tpl = null) {

		$this->t		= Utils::setVars('tag');
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
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		//$class		= ucfirst($this->t['tasks']).'Helper';
		$canDo		= TagsHelper::getActions($this->t, $this->state->get('filter.tag_id'));

		$text = $isNew ? Text::_( $this->t['l'] . '_NEW' ) : Text::_($this->t['l'] . '_EDIT');
		ToolbarHelper::title(   Text::_( $this->t['l'] . '_TAG' ).': <small><small>[ ' . $text.' ]</small></small>' , 'tags');

		// If not checked out, can save the item.
		if (!$checkedOut && $canDo->get('core.edit')){
			ToolbarHelper::apply($this->t['task'].'.apply', 'JTOOLBAR_APPLY');
			ToolbarHelper::save($this->t['task'].'.save', 'JTOOLBAR_SAVE');
			ToolbarHelper::addNew($this->t['task'].'.save2new', 'JTOOLBAR_SAVE_AND_NEW');
		}

		if (empty($this->item->id))  {
			ToolbarHelper::cancel($this->t['task'].'.cancel', 'JTOOLBAR_CANCEL');
		}
		else {
			ToolbarHelper::cancel($this->t['task'].'.cancel', 'JTOOLBAR_CLOSE');
		}

		ToolbarHelper::divider();
		ToolbarHelper::help( 'screen.'.$this->t['c'], true );
	}
}
?>
