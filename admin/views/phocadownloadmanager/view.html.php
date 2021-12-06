<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die();
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Client\ClientHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\Toolbar\ToolbarHelper;
jimport( 'joomla.client.helper' );
jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.pane' );

class PhocaDownloadCpViewPhocaDownloadManager extends HtmlView
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

	public function display($tpl = null) {

		$this->t		= PhocaDownloadUtils::setVars('manager');
		$this->r 		= new PhocaDownloadRenderAdminView();
		$this->field	= Factory::getApplication()->input->get('field');
		$this->fce 		= 'phocaSelectFileName_'.$this->field;




		$this->folderstate	= $this->get('FolderState');
		$this->files		= $this->get('Files');
		$this->folders		= $this->get('Folders');
		$this->session		= Factory::getSession();
		$this->manager 		= Factory::getApplication()->input->get( 'manager', '',  'file' );



		if ($this->manager == 'filemultiple') {
			$this->form			= $this->get('Form');
		}

		$params = ComponentHelper::getParams($this->t['o']);

		$this->t['multipleuploadchunk']	= $params->get( 'multiple_upload_chunk', 0 );
		$this->t['uploadmaxsize'] 		= $params->get( 'upload_maxsize', 3145728 );
		$this->t['uploadmaxsizeread'] 	= PhocaDownloadFile::getFileSizeReadable($this->t['uploadmaxsize']);
		$this->t['enablemultiple'] 		= $params->get( 'enable_multiple_upload_admin', 1 );
		$this->t['multipleuploadmethod'] = $params->get( 'multiple_upload_method', 4 );

		$this->currentFolder = '';
		if (isset($this->folderstate->folder) && $this->folderstate->folder != '') {
			$this->currentFolder = $this->folderstate->folder;
		}

		// - - - - - - - - - -
		//TABS
		// - - - - - - - - - -
		$this->t['tab'] 			= Factory::getApplication()->input->get('tab', '', '', 'string');
		$this->t['displaytabs']	= 0;

		// UPLOAD
		$this->t['currenttab']['upload'] = $this->t['displaytabs'];
		$this->t['displaytabs']++;

		// MULTIPLE UPLOAD
		if((int)$this->t['enablemultiple']  >= 0) {
			$this->t['currenttab']['multipleupload'] = $this->t['displaytabs'];
			$this->t['displaytabs']++;
		}

		$group 	= PhocaDownloadSettings::getManagerGroup($this->manager);

		// - - - - - - - - - - -
		// Upload
		// - - - - - - - - - - -
		$sU							= new PhocaDownloadFileUploadSingle();
		$sU->returnUrl				= 'index.php?option=com_phocadownload&view=phocadownloadmanager&tab=upload'.str_replace('&amp;', '&', $group['c']).'&manager='.PhocaDownloadUtils::filterValue($this->manager, 'alphanumeric').'&field='.PhocaDownloadUtils::filterValue($this->field, 'alphanumeric2').'&folder='.PhocaDownloadUtils::filterValue($this->currentFolder, 'folderpath');
		$sU->tab					= 'upload';
		$this->t['su_output']	= $sU->getSingleUploadHTML();
		$this->t['su_url']		= Uri::base().'index.php?option=com_phocadownload&task=phocadownloadupload.upload&amp;'
								  .$this->session->getName().'='.$this->session->getId().'&amp;'
								  . Session::getFormToken().'=1&amp;viewback=phocadownloadmanager&amp;manager='.PhocaDownloadUtils::filterValue($this->manager, 'alphanumeric').'&amp;field='.PhocaDownloadUtils::filterValue($this->field, 'alphanumeric2').'&amp;'
								  .'folder='. PhocaDownloadUtils::filterValue($this->currentFolder, 'folderpath').'&amp;tab=upload';


		// - - - - - - - - - - -
		// Multiple Upload
		// - - - - - - - - - - -
		// Get infos from multiple upload
		$muFailed						= Factory::getApplication()->input->get( 'mufailed', '0', '', 'int' );
		$muUploaded						= Factory::getApplication()->input->get( 'muuploaded', '0', '', 'int' );
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

			PhocadownloadFileUploadMultiple::renderMultipleUploadLibraries();
			$mU						= new PhocaDownloadFileUploadMultiple();
			$mU->frontEnd			= 0;
			$mU->method				= $this->t['multipleuploadmethod'];
			$mU->url				= Uri::base().'index.php?option=com_phocadownload&task=phocadownloadupload.multipleupload&amp;'
									 .$this->session->getName().'='.$this->session->getId().'&'
									 . Session::getFormToken().'=1&tab=multipleupload&manager='.PhocaDownloadUtils::filterValue($this->manager, 'alphanumeric').'&field='.PhocaDownloadUtils::filterValue($this->field, 'alphanumeric2').'&folder='. PhocaDownloadUtils::filterValue($this->currentFolder, 'folderpath');
			$mU->reload				= Uri::base().'index.php?option=com_phocadownload&view=phocadownloadmanager'
									.str_replace('&amp;', '&', PhocaDownloadUtils::filterValue($group['c'], 'text')).'&'
									.$this->session->getName().'='.$this->session->getId().'&'
									. Session::getFormToken().'=1&tab=multipleupload&'
									.'manager='.PhocaDownloadUtils::filterValue($this->manager, 'alphanumeric').'&field='.PhocaDownloadUtils::filterValue($this->field, 'alphanumeric2').'&folder='. PhocaDownloadUtils::filterValue($this->currentFolder, 'folderpath');
			$mU->maxFileSize		= PhocadownloadFileUploadMultiple::getMultipleUploadSizeFormat($this->t['uploadmaxsize']);
			$mU->chunkSize			= '1mb';

			$mU->renderMultipleUploadJS(0, $this->t['multipleuploadchunk']);
			$this->t['mu_output']= $mU->getMultipleUploadHTML();
		}


		$this->t['ftp'] 			= !ClientHelper::hasCredentials('ftp');
		$this->t['path']			= PhocaDownloadPath::getPathSet($this->manager);

		$this->addToolbar();
		parent::display($tpl);
		echo HTMLHelper::_('behavior.keepalive');
	}

	function setFolder($index = 0) {
		if (isset($this->folders[$index])) {
			$this->_tmp_folder = &$this->folders[$index];
		} else {
			$this->_tmp_folder = new CMSObject;
		}
	}

	function setFile($index = 0) {
		if (isset($this->files[$index])) {
			$this->_tmp_file = &$this->files[$index];
		} else {
			$this->_tmp_file = new CMSObject;
		}
	}

	protected function addToolbar() {

		Factory::getApplication()->input->set('hidemainmenu', true);
		require_once JPATH_COMPONENT.'/helpers/'.$this->t['task'].'.php';
		$state	= $this->get('State');
		$class	= ucfirst($this->t['task']).'Helper';
		$canDo	= $class::getActions($this->t, $state->get('filter.multiple'));

		ToolbarHelper::title( Text::_( $this->t['l'].'_MULTIPLE_ADD' ), 'plus' );

		if ($canDo->get('core.create')){
			ToolbarHelper::save($this->t['c'].'m.save', 'JTOOLBAR_SAVE');
		}

		ToolbarHelper::cancel($this->t['c'].'m.cancel', 'JTOOLBAR_CLOSE');
		ToolbarHelper::divider();
		ToolbarHelper::help( 'screen.'.$this->t['c'], true );
	}
}
?>
