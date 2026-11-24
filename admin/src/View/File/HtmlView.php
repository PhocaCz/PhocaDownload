<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
namespace Phoca\Component\Phocadownload\Administrator\View\File;

defined('_JEXEC') or die();
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Phoca\Component\Phocadownload\Administrator\Render\Adminview;
use Phoca\Component\Phocadownload\Administrator\Utils\Utils;
use Phoca\Component\Phocadownload\Administrator\Helper\FilesHelper;

/* jimport( 'joomla.application.component.view' ); */

class HtmlView extends BaseHtmlView
{
	protected $state;
	protected $item;
	protected $form;
	protected $t;
	protected $r;

	public function display($tpl = null) {

		$this->t		= Utils::setVars('file');
		$this->r 		= new AdminView();
		$this->state	= $this->get('State');
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');

		$params = ComponentHelper::getParams('com_phocadownload');
		/** @var CMSWebApplicationInterface $app */
$app = Factory::getApplication();
		$document = $app->getDocument();
		$document->addScriptOptions(
			'com_phocadownload.externalUrlParser',
			array(
				'versionFormat' => $params->get('external_url_parser_version', 'full'),
				'dateOffsetHours' => (int) $params->get('external_url_parser_date_offset', 0),
				'copyDateToPublishUp' => (int) $params->get('external_url_parser_copy_date_to_publish_up', 0),
			)
		);

		Text::script('COM_PHOCADOWNLOAD_EXTERNAL_URL_PARSE_ERROR');
		Text::script('COM_PHOCADOWNLOAD_EXTERNAL_URL_PARSE_URL_EMPTY');
		Text::script('COM_PHOCADOWNLOAD_EXTERNAL_URL_PARSE_URL_INVALID');

		$document->getWebAssetManager()
			->registerAndUseScript('com_phocadownload.external-url-parser', 'media/com_phocadownload/js/external-url-parser.js', array('version' => 'auto'), array('defer' => true));


		if (isset($this->item->textonly) && (int)$this->item->textonly == 1 && Factory::getApplication()->getInput()->get('layout') != 'edit_text') {
			$tpl = 'text';
		}
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
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->id);
		//$class		= ucfirst($this->t['tasks']).'Helper';
		$canDo		= FilesHelper::getActions($this->t, $this->state->get('filter.category_id'));

		$text = $isNew ? Text::_( $this->t['l'] . '_NEW' ) : Text::_($this->t['l'] . '_EDIT');
		ToolbarHelper::title(   Text::_( $this->t['l'] . '_FILE' ).': <small><small>[ ' . $text.' ]</small></small>' , 'file');

		// If not checked out, can save the item.
		if (!$checkedOut && $canDo->get('core.edit')){
			ToolbarHelper::apply($this->t['task'] . '.apply', 'JTOOLBAR_APPLY');
			ToolbarHelper::save($this->t['task'] . '.save', 'JTOOLBAR_SAVE');
			ToolbarHelper::addNew($this->t['task'] . '.save2new', 'JTOOLBAR_SAVE_AND_NEW');

		}
		// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create')) {
			//JToolbarHelper::custom($this->t.'.save2copy', 'copy.png', 'copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}
		if (empty($this->item->id))  {
			ToolbarHelper::cancel($this->t['task'] . '.cancel', 'JTOOLBAR_CANCEL');
		}
		else {
			ToolbarHelper::cancel($this->t['task'] . '.cancel', 'JTOOLBAR_CLOSE');
		}

		ToolbarHelper::divider();
		ToolbarHelper::help( 'screen.'.$this->t['c'], true );
	}
}
?>
