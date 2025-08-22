<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;

class PhocaDownloadControllerUser extends PhocaDownloadController
{
	public $loginUrl;
	public $loginString;
	public $url;
	public $itemId;


	function __construct() {
		parent::__construct();

		$this->registerTask( 'unpublish', 'unpublish' );
		$app					= Factory::getApplication();
		$this->itemId			= $app->getInput()->get( 'Itemid', 0, 'int' );
		$this->loginUrl			= Route::_('index.php?option=com_users&view=login', false);
		$this->loginString		= Text::_('COM_PHOCADOWNLOAD_NOT_AUTHORISED_ACTION');
		$this->url				= 'index.php?option=com_phocadownload&view=user&Itemid='. $this->itemId;
	}
	/*
	function display() {
		if ( ! Factory::getApplication()->getInput()->getCmd( 'view' ) ) {
			$this->input->set('view', 'user' );
		}
		parent::display();
    }*/

	function unpublish() {

		$app				= Factory::getApplication();
		$post['id']			= $app->getInput()->get( 'actionid', '', 'int', 0  );
		$post['limitstart']	= $app->getInput()->get( 'limitstart', '', 'int', 0  );
		$model 				= $this->getModel('user');
		//$isOwnerCategory 	= 1;//$model->isOwnerCategoryImage((int)$this->_user->id, (int)$id);
		// USER RIGHT - Delete - - - - - - - - - - -
		// 2, 2 means that user access will be ignored in function getUserRight for display Delete button
		$user = Factory::getUser();
		$rightDisplayDelete	= 0;
		$catAccess	= PhocaDownloadAccess::getCategoryAccessByFileId((int)$post['id']);
		if (!empty($catAccess)) {
			$rightDisplayDelete = PhocaDownloadAccess::getUserRight('deleteuserid', $catAccess->deleteuserid, 2, $user->getAuthorisedViewLevels(), $user->get('id', 0), 0);
		}
		// - - - - - - - - - - - - - - - - - - - - - -

		if ($rightDisplayDelete) {
			if(!$model->publish((int)$post['id'], 0)) {
			$msg = Text::_('COM_PHOCADOWNLOAD_ERROR_UNPUBLISHING_ITEM');
			} else {
			$msg = Text::_('COM_PHOCADOWNLOAD_SUCCESS_UNPUBLISHING_ITEM');
			}
		} else {
		    $app->enqueueMessage($this->loginString, 'error');
			$app->redirect($this->loginUrl);
			exit;
		}

		$lSO = '';
		if ($post['limitstart'] != '') {
			$lSO = '&limitstart='.(int)$post['limitstart'];
		}

		$this->setRedirect( Route::_($this->url. $lSO, false), $msg );
	}

	function publish() {
		$app				= Factory::getApplication();
		$post['id']			= $app->getInput()->get( 'actionid', '', 'int', 0  );
		$post['limitstart']	= $app->getInput()->get( 'limitstart', '', 'int', 0  );
		$model 				= $this->getModel('user');
		//$isOwnerCategory 	= 1;//$model->isOwnerCategoryImage((int)$this->_user->id, (int)$id);

		// USER RIGHT - Delete - - - - - - - - - - -
		// 2, 2 means that user access will be ignored in function getUserRight for display Delete button
		$user = Factory::getUser();
		$rightDisplayDelete	= 0;
		$catAccess	= PhocaDownloadAccess::getCategoryAccessByFileId((int)$post['id']);

		if (!empty($catAccess)) {
			$rightDisplayDelete = PhocaDownloadAccess::getUserRight('deleteuserid', $catAccess->deleteuserid, 2, $user->getAuthorisedViewLevels(), $user->get('id', 0), 0);
		}
		// - - - - - - - - - - - - - - - - - - - - - -

		if ($rightDisplayDelete) {
			if(!$model->publish((int)$post['id'], 1)) {
			$msg = Text::_('COM_PHOCADOWNLOAD_ERROR_PUBLISHING_ITEM');
			} else {
			$msg = Text::_('COM_PHOCADOWNLOAD_SUCCESS_PUBLISHING_ITEM');
			}
		} else {
			$app->enqueueMessage($this->loginString, 'error');
			$app->redirect($this->loginUrl);
			exit;
		}

		$lSO = '';
		if ($post['limitstart'] != '') {
			$lSO = '&limitstart='.(int)$post['limitstart'];
		}

		$this->setRedirect( Route::_($this->url. $lSO, false), $msg );
	}

	function delete() {

		$app				= Factory::getApplication();
		$post['id']			= $app->getInput()->get( 'actionid', '', 'int', 0  );
		$post['limitstart']	= $app->getInput()->get( 'limitstart', '', 'int', 0  );
		$model 				= $this->getModel('user');
		//$isOwnerCategory 	= 1;//$model->isOwnerCategoryImage((int)$this->_user->id, (int)$id);

		// USER RIGHT - Delete - - - - - - - - - - -
		// 2, 2 means that user access will be ignored in function getUserRight for display Delete button
		$user = Factory::getUser();
		$rightDisplayDelete	= 0;
		$catAccess	= PhocaDownloadAccess::getCategoryAccessByFileId((int)$post['id']);
		if (!empty($catAccess)) {
			$rightDisplayDelete = PhocaDownloadAccess::getUserRight('deleteuserid', $catAccess->deleteuserid, 2, $user->getAuthorisedViewLevels(), $user->get('id', 0), 0);
		}
		// - - - - - - - - - - - - - - - - - - - - - -

		if ($rightDisplayDelete) {
			if(!$model->delete((int)$post['id'])) {
			$msg = Text::_('COM_PHOCADOWNLOAD_ERROR_DELETING_ITEM');
			} else {
			$msg = Text::_('COM_PHOCADOWNLOAD_SUCCESS_DELETING_ITEM');
			}
		} else {
			$app->enqueueMessage($this->loginString, 'error');
			$app->redirect($this->loginUrl);
			exit;
		}

		$lSO = '';
		if ($post['limitstart'] != '') {
			$lSO = '&limitstart='.(int)$post['limitstart'];
		}

		$this->setRedirect( Route::_($this->url. $lSO, false), $msg );
	}
}
?>
