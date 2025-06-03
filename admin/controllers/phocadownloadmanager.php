<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\Filesystem\File;
use Joomla\CMS\Language\Text;

jimport('joomla.application.component.controllerform');

class PhocaDownloadCpControllerPhocaDownloadManager extends FormController
{
	protected	$option 		= 'com_phocadownload';
	protected	$view_list		= 'phocadownloadmanager';
	protected	$layout			= 'edit';

	function __construct() {
		parent::__construct();
		$this->layout = 'edit';
	}

	protected function allowAdd($data = array()) {
		$user		= Factory::getUser();
		$allow		= null;
		$allow	= $user->authorise('core.create', 'com_phocadownload');
		if ($allow === null) {
			return parent::allowAdd($data);
		} else {
			return $allow;
		}
	}

	protected function allowEdit($data = array(), $key = 'id') {
		$user		= Factory::getUser();
		$allow		= null;
		$allow	= $user->authorise('core.edit', 'com_phocadownload');
		if ($allow === null) {
			return parent::allowEdit($data, $key);
		} else {
			return $allow;
		}
	}

	function edit($key = NULL, $urlVar = NULL) {
		$this->setRedirect(Route::_('index.php?option='.$this->option.'&view='.$this->view_list.'&layout='.$this->layout.'&manager=filemultiple', false));
	}

	function cancel($key = NULL) {
		$this->setRedirect( 'index.php?option=com_phocadownload&view=phocadownloadfiles' );
	}

	function delete($key = null, $urlVar = null) {

		Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));

		$cid	= Factory::getApplication()->input->get('cid', array(), '', 'array');
		$returnUrl	= Factory::getApplication()->input->get( 'return-url', null, 'post', 'base64' );//includes field
		$manager		= Factory::getApplication()->input->get( 'manager', 'file', 'string' );


		if ($cid[0] != '') {

			$filePath	= PhocaDownloadPath::getPathSet($manager);
			$fileToRemove = $filePath['orig_abs_ds']. $cid[0];

			if (PhocaDownloadFile::exists($fileToRemove)) {

				$db = Factory::getDBO();

				$query = 'SELECT a.filename'
				.' FROM #__phocadownload AS a'
				.' WHERE a.filename = '.$db->quote($cid[0]) . ' OR a.filename_play = '.$db->quote($cid[0]). ' OR a.filename_preview = '.$db->quote($cid[0])
				.' ORDER BY a.id';
				$db->setQuery($query, 0, 1);
				$filename = $db->loadObject();

				if (isset($filename->filename) && $filename->filename != '') {
					$this->app->enqueueMessage(Text::_('COM_PHOCADOWNLOAD_WARNING_FILE_EXISTS_IN_SYSTEM'), 'warning');
					$this->setRedirect(Route::_(base64_decode($returnUrl), false));
					return false;
				}

				if (File::delete($fileToRemove)) {

					$this->app->enqueueMessage(Text::_('COM_PHOCADOWNLOAD_FILE_SUCCESSFULLY_DELETED'), 'success');
				} else {
					$this->app->enqueueMessage(Text::_('COM_PHOCADOWNLOAD_FILE_SUCCESSFULLY_DELETED'), 'error');
				}

			}

		}

		$this->setRedirect(Route::_(base64_decode($returnUrl), false));
		return true;

	}
}
?>
