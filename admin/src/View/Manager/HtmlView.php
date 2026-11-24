<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
namespace Phoca\Component\Phocadownload\Administrator\View\Manager;

use stdClass;

defined( '_JEXEC' ) or die();
use Joomla\CMS\MVC\View\HtmlView AS BaseHtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Client\ClientHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Phoca\Component\Phocadownload\Administrator\File\File as PhocaFile;
use Phoca\Component\Phocadownload\Administrator\File\UploadMultiple;
use Phoca\Component\Phocadownload\Administrator\File\UploadSingle;
use Phoca\Component\Phocadownload\Administrator\Path\Path as PhocaPath;
use Phoca\Component\Phocadownload\Administrator\Render\Adminview;
use Phoca\Component\Phocadownload\Administrator\Utils\Settings;
use Phoca\Component\Phocadownload\Administrator\Utils\Utils;
use Phoca\Component\Phocadownload\Administrator\Helper\ManagerHelper;

/* jimport( 'joomla.client.helper' ); */
/* jimport( 'joomla.application.component.view' ); */
/* jimport( 'joomla.html.pane' ); */

class HtmlView extends BaseHtmlView
{
	protected $field;
	protected $fce;
	protected $folderstate;
	protected $images;
	protected $folders;
	protected $tmpl;
	protected $session;
	protected $currentFolder;
	protected $t;
	protected $r;
	protected $returnUrl;

	public function display($tpl = null) {

	/** @var CMSWebApplicationInterface $app */
$app = Factory::getApplication();

		$this->t		= Utils::setVars('manager');
		$this->r 		= new AdminView();
		$this->field	= Factory::getApplication()->getInput()->get('field');
		$this->fce 		= 'phocaSelectFileName_'.$this->field;
		$this->returnUrl = base64_encode(Uri::getInstance()->toString());
		$this->folderstate	= $this->get('FolderState');
		$this->files		= $this->get('Files');
		$this->folders		= $this->get('Folders');
		$this->session		= $app->getSession();
		$this->manager 		= Factory::getApplication()->getInput()->get( 'manager', '',  'file' );


		if ($this->manager == 'filemultiple') {
			$this->form			= $this->get('Form');
		}

		$params = ComponentHelper::getParams($this->t['o']);

		$this->t['multipleuploadchunk']	= $params->get( 'multiple_upload_chunk', 0 );
		$this->t['uploadmaxsize'] 		= $params->get( 'upload_maxsize', 3145728 );
		$this->t['uploadmaxsizeread'] 	= PhocaFile::getFileSizeReadable($this->t['uploadmaxsize']);
		$this->t['enablemultiple'] 		= $params->get( 'enable_multiple_upload_admin', 1 );
		$this->t['multipleuploadmethod'] = $params->get( 'multiple_upload_method', 4 );

		$this->currentFolder = '';
		if (isset($this->folderstate->folder) && $this->folderstate->folder != '') {
			$this->currentFolder = $this->folderstate->folder;
		}

		// - - - - - - - - - -
		//TABS
		// - - - - - - - - - -
		$this->t['tab'] 			= (string)Factory::getApplication()->getInput()->get('tab', '', '');
		$this->t['displaytabs']	= 0;

		// UPLOAD
		$this->t['currenttab']['upload'] = $this->t['displaytabs'];
		$this->t['displaytabs']++;

		// MULTIPLE UPLOAD
		if((int)$this->t['enablemultiple']  >= 0) {
			$this->t['currenttab']['multipleupload'] = $this->t['displaytabs'];
			$this->t['displaytabs']++;
		}

		$group 	= Settings::getManagerGroup($this->manager);

		// - - - - - - - - - - -
		// Upload
		// - - - - - - - - - - -
		$sU							= new UploadSingle();
		$sU->returnUrl				= 'index.php?option=com_phocadownload&view=manager&tab=upload'.str_replace('&amp;', '&', $group['c']).'&manager='.Utils::filterValue($this->manager, 'alphanumeric').'&field='.Utils::filterValue($this->field, 'alphanumeric2').'&folder='.Utils::filterValue($this->currentFolder, 'folderpath');
		$sU->tab					= 'upload';
		$this->t['su_output']	= $sU->getSingleUploadHTML();
		$this->t['su_url']		= Uri::base().'index.php?option=com_phocadownload&task=upload.upload&amp;'
								  .$this->session->getName().'='.$this->session->getId().'&amp;'
								  . Session::getFormToken().'=1&amp;viewback=phocadownloadmanager&amp;manager='.Utils::filterValue($this->manager, 'alphanumeric').'&amp;field='.Utils::filterValue($this->field, 'alphanumeric2').'&amp;'
								  .'folder='. Utils::filterValue($this->currentFolder, 'folderpath').'&amp;tab=upload';


		// - - - - - - - - - - -
		// Multiple Upload
		// - - - - - - - - - - -
		// Get infos from multiple upload
		$muFailed						= (int)Factory::getApplication()->getInput()->get( 'mufailed', '0', 'int');
		$muUploaded						= (int)Factory::getApplication()->getInput()->get( 'muuploaded', '0', 'int', );
		$this->t['mu_response_msg']	= $muUploadedMsg 	= '';

		if ($muUploaded > 0) {
			$muUploadedMsg = Text::_('COM_PHOCADOWNLOAD_COUNT_UPLOADED_FILE'). ': ' . $muUploaded;
		}
		if ($muFailed > 0) {
			$muFailedMsg = Text::_('COM_PHOCADOWNLOAD_COUNT_NOT_UPLOADED_FILE'). ': ' . $muFailed;
		}
		if ($muFailed > 0 && $muUploaded > 0) {
			$this->t['mu_response_msg'] = '<div class="alert alert-info alert-dismissible">'
			.''
			.Text::_('COM_PHOCADOWNLOAD_COUNT_UPLOADED_FILE'). ': ' . $muUploaded .'<br />'
			.Text::_('COM_PHOCADOWNLOAD_COUNT_NOT_UPLOADED_FILE'). ': ' . $muFailed.'<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
		} else if ($muFailed > 0 && $muUploaded == 0) {
			$this->t['mu_response_msg'] = '<div class="alert alert-danger alert-dismissible">'
			.''
			.Text::_('COM_PHOCADOWNLOAD_COUNT_NOT_UPLOADED_FILE'). ': ' . $muFailed.'<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
		} else if ($muFailed == 0 && $muUploaded > 0){
			$this->t['mu_response_msg'] = '<div class="alert alert-success alert-dismissible">'
			.''
			.Text::_('COM_PHOCADOWNLOAD_COUNT_UPLOADED_FILE'). ': ' . $muUploaded.'<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
		} else {
			$this->t['mu_response_msg'] = '';
		}

		if((int)$this->t['enablemultiple']  >= 0) {

			UploadMultiple::renderMultipleUploadLibraries();
			$mU						= new UploadMultiple();
			$mU->frontEnd			= 0;
			$mU->method				= $this->t['multipleuploadmethod'];
			$mU->url				= Uri::base().'index.php?option=com_phocadownload&task=upload.multipleupload&amp;'
									 .$this->session->getName().'='.$this->session->getId().'&'
									 . Session::getFormToken().'=1&tab=multipleupload&manager='.Utils::filterValue($this->manager, 'alphanumeric').'&field='.Utils::filterValue($this->field, 'alphanumeric2').'&folder='. Utils::filterValue($this->currentFolder, 'folderpath');
			$mU->reload				= Uri::base().'index.php?option=com_phocadownload&view=manager'
									.str_replace('&amp;', '&', $group['c']).'&'
									.$this->session->getName().'='.$this->session->getId().'&'
									. Session::getFormToken().'=1&tab=multipleupload&'
									.'manager='.Utils::filterValue($this->manager, 'alphanumeric').'&field='.Utils::filterValue($this->field, 'alphanumeric2').'&folder='. Utils::filterValue($this->currentFolder, 'folderpath');
			$mU->maxFileSize		= UploadMultiple::getMultipleUploadSizeFormat($this->t['uploadmaxsize']);
			$mU->chunkSize			= '1mb';

			$mU->renderMultipleUploadJS(0, $this->t['multipleuploadchunk']);
			$this->t['mu_output']= $mU->getMultipleUploadHTML();
		}


		$this->t['ftp'] 			= !ClientHelper::hasCredentials('ftp');
		$this->t['path']			= PhocaPath::getPathSet($this->manager);

		$this->addToolbar();
		parent::display($tpl);
		Factory::getApplication()->getDocument()->getWebAssetManager()->useScript('keepalive');
	}

	function setFolder($index = 0) {
		if (isset($this->folders[$index])) {
			$this->_tmp_folder = &$this->folders[$index];
		} else {
			$this->_tmp_folder = new stdClass();
		}
	}

	function setFile($index = 0) {
		if (isset($this->files[$index])) {
			$this->_tmp_file = &$this->files[$index];
		} else {
			$this->_tmp_file = new stdClass();
		}
	}

	protected function addToolbar() {

		Factory::getApplication()->getInput()->set('hidemainmenu', true);
		//require_once JPATH_COMPONENT.'/helpers/'.$this->t['task'].'.php';
		$state	= $this->get('State');
		//$class	= ucfirst($this->t['task']).'Helper';
		$canDo	= ManagerHelper::getActions($this->t, $state->get('filter.multiple'));

		ToolbarHelper::title( Text::_( $this->t['l'].'_MULTIPLE_ADD' ), 'plus' );

		if ($canDo->get('core.create')){
			ToolbarHelper::save('manager.save', 'JTOOLBAR_SAVE');
		}

		ToolbarHelper::cancel('manager.cancel', 'JTOOLBAR_CLOSE');
		ToolbarHelper::divider();
		ToolbarHelper::help( 'screen.'.$this->t['c'], true );
	}
}
?>
