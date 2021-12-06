<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
jimport('joomla.application.component.controllerform');

class PhocaDownloadCpControllerPhocaDownloadDownloads extends FormController
{
	protected	$option 		= 'com_phocadownload';

	public function &getModel($name = 'PhocaDownloadDownload', $prefix = 'PhocaDownloadCpModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}

	function cancel($key = NULL) {
		$model = $this->getModel( 'phocadownload' );
		$this->setRedirect( 'index.php?option=com_phocadownload&view=phocadownloaddownloads' );
	}

	function reset() {

		$post					= Factory::getApplication()->input->get('post');
		$cid					= Factory::getApplication()->input->get( 'cid', array(0), 'post', 'array' );
		$idFile					= Factory::getApplication()->input->get( 'idfile', 0, 'post', 'int' );

		$model = $this->getModel( 'phocadownloaddownloads' );

		if ($model->reset($cid)) {
			$msg = Text::_( 'COM_PHOCADOWNLOAD_SUCCESS_RESET_USER_STAT' );
		} else {
			$msg = Text::_( 'COM_PHOCADOWNLOAD_ERROR_RESET_USER_STAT' );
		}

		$link = 'index.php?option=com_phocadownload&view=phocadownloaddownloads&id='.(int)$idFile;
		$this->setRedirect($link, $msg);
	}

	function delete() {


		$cid					= Factory::getApplication()->input->get( 'cid', array(0), 'post', 'array' );


		$model = $this->getModel( 'phocadownloaddownload' );

		if ($model->delete($cid)) {
			$msg = Text::_( 'COM_PHOCADOWNLOAD_SUCCESS_DELETE_USER_STAT' );
		} else {
			$msg = Text::_( 'COM_PHOCADOWNLOAD_ERROR_DELETE_USER_STAT' );
		}

		$link = 'index.php?option=com_phocadownload&view=phocadownloaddownloads';
		$this->setRedirect($link, $msg);
	}
}
?>
