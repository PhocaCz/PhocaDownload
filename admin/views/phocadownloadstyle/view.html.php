<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Client\ClientHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
jimport('joomla.application.component.view');


class PhocaDownloadCpViewPhocaDownloadStyle extends HtmlView
{
	protected $item;
	protected $form;
	protected $state;
	protected $t;
	protected $r;

	public function display($tpl = null)
	{
		$this->t		= PhocaDownloadUtils::setVars('style');
		$this->r = new PhocaDownloadRenderAdminView();


		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');
		$this->ftp		= ClientHelper::setCredentialsFromRequest('ftp');
		$model 			= $this->getModel();

		// Set CSS for codemirror
		Factory::getApplication()->setUserState('editor.source.syntax', 'css');


		// New or edit
		if (!$this->form->getValue('id') || $this->form->getValue('id') == 0) {
			$this->form->setValue('source', null, '');
			$this->form->setValue('type', null, 2);
			$this->t['ssuffixtype'] = Text::_($this->t['l'].'_WILL_BE_CREATED_FROM_TITLE');

		} else {
			$this->source	= $model->getSource($this->form->getValue('id'), $this->form->getValue('filename'), $this->form->getValue('type'));
			$this->form->setValue('source', null, $this->source->source);
			$this->t['ssuffixtype'] = '';
		}

		// Only help input form field - to display Main instead of 1 and Custom instead of 2
		if ($this->form->getValue('type') == 1) {
			$this->form->setValue('typeoutput', null, Text::_($this->t['l'].'_MAIN_CSS'));
		} else {
			$this->form->setValue('typeoutput', null, Text::_($this->t['l'].'_CUSTOM_CSS'));
		}

		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors), 500);
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar() {

		require_once JPATH_COMPONENT.'/helpers/'.$this->t['tasks'].'.php';
		Factory::getApplication()->input->set('hidemainmenu', true);
		$bar 		= Toolbar::getInstance('toolbar');
		$user		= Factory::getUser();
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		$class		= ucfirst($this->t['tasks']).'Helper';
		$canDo		= $class::getActions($this->t, $this->state->get('filter.category_id'));

		$text = $isNew ? Text::_( $this->t['l'] . '_NEW' ) : Text::_($this->t['l'] . '_EDIT');
		ToolbarHelper::title(   Text::_( $this->t['l'] . '_STYLE' ).': <small><small>[ ' . $text.' ]</small></small>' , 'eye');

		// If not checked out, can save the item.
		if (!$checkedOut && $canDo->get('core.edit')){
			ToolbarHelper::apply($this->t['task'].'.apply', 'JTOOLBAR_APPLY');
			ToolbarHelper::save($this->t['task'].'.save', 'JTOOLBAR_SAVE');
		}

		ToolbarHelper::cancel($this->t['task'].'.cancel', 'JTOOLBAR_CLOSE');
		ToolbarHelper::divider();
		ToolbarHelper::help( 'screen.'.$this->t['c'], true );
	}

}
?>
