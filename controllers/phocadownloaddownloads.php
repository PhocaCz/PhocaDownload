<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();
jimport('joomla.application.component.controllerform');

class PhocaDownloadCpControllerPhocaDownloadDownloads extends JControllerForm
{
	protected	$option 		= 'com_phocadownload';
	
	public function &getModel($name = 'PhocaDownloadDownloads', $prefix = 'PhocaDownloadCpModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	function cancel($key = NULL) {
		$model = $this->getModel( 'phocadownload' );
		$this->setRedirect( 'index.php?option=com_phocadownload&view=phocadownloaddownloads' );
	}

	function reset() {
		
		$post					= JRequest::get('post');
		$cid					= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$idFile					= JRequest::getVar( 'idfile', 0, 'post', 'int' );

		$model = $this->getModel( 'phocadownloaddownloads' );

		if ($model->reset($cid)) {
			$msg = JText::_( 'COM_PHOCADOWNLOAD_SUCCESS_RESET_USER_STAT' );
		} else {
			$msg = JText::_( 'COM_PHOCADOWNLOAD_ERROR_RESET_USER_STAT' );
		}
		
		$link = 'index.php?option=com_phocadownload&view=phocadownloaddownloads&id='.(int)$idFile;
		$this->setRedirect($link, $msg);
	}
}
?>
