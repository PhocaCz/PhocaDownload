<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */


defined('_JEXEC') or die();
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Router\Route;
jimport('joomla.application.component.controllerform');

class PhocaDownloadCpControllerPhocaDownloadFile extends FormController
{
	protected	$option 		= 'com_phocadownload';
	
	function __construct($config=array()) {
		
		parent::__construct($config);
		
		$task = Factory::getApplication()->getInput()->get('task');
		if ((string)$task == 'addtext') {
			Factory::getApplication()->getInput()->set('task','add');
			Factory::getApplication()->getInput()->set('layout','edit_text');
		}
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
	
	public function batch($model =  null) {
		Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));

		// Set the model
		$model	= $this->getModel('phocadownloadfile', '', array());

		// Preset the redirect
		$this->setRedirect(Route::_('index.php?option=com_phocadownload&view=phocadownloadfiles'.$this->getRedirectToListAppend(), false));

		return parent::batch($model);
	}
	
	/*
	function copyquick() {
		$cid	= Factory::getApplication()->getInput()->get( 'cid', array(0), 'post', 'array' );
		$model	= $this->getModel( 'phocadownloadfile' );
		if ($model->copyQuick($cid)) {
			$msg = Text::_( 'COM_PHOCADOWNLOAD_SUCCESS_COPY_FILE' );
		} else {
			$msg = Text::_( 'COM_PHOCADOWNLOAD_ERROR_COPY_FILE' );
		}
		$link = 'index.php?option=com_phocadownload&view=phocadownloadfiles';
		$this->setRedirect($link, $msg);
	}*/

}
?>
