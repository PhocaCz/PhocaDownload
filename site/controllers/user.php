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

	/**
	 * Common handler for publish / unpublish / delete.
	 * Validates CSRF token, checks user right and runs the action.
	 */
	protected function processAction(string $action): void
	{
		// CSRF protection - must be the first thing that happens
		$this->checkToken('request');

		$app				= Factory::getApplication();
		$post['id']			= $app->getInput()->get( 'actionid', 0, 'int' );
		$post['limitstart']	= $app->getInput()->get( 'limitstart', '', 'int' );
		$model 				= $this->getModel('user');

		// USER RIGHT - Delete
		// 2, 2 means that user access will be ignored in function getUserRight for display Delete button
		$user				= Factory::getUser();
		$rightDisplayDelete	= 0;
		$catAccess			= PhocaDownloadAccess::getCategoryAccessByFileId((int)$post['id']);
		if (!empty($catAccess)) {
			$rightDisplayDelete = PhocaDownloadAccess::getUserRight('deleteuserid', $catAccess->deleteuserid, 2, $user->getAuthorisedViewLevels(), $user->get('id', 0), 0);
		}

		if (!$rightDisplayDelete) {
			$app->enqueueMessage($this->loginString, 'error');
			$app->redirect($this->loginUrl);
			return;
		}

		switch ($action) {
			case 'publish':
				$ok = $model->publish((int)$post['id'], 1);
				$msg = Text::_($ok ? 'COM_PHOCADOWNLOAD_SUCCESS_PUBLISHING_ITEM' : 'COM_PHOCADOWNLOAD_ERROR_PUBLISHING_ITEM');
				break;
			case 'unpublish':
				$ok = $model->publish((int)$post['id'], 0);
				$msg = Text::_($ok ? 'COM_PHOCADOWNLOAD_SUCCESS_UNPUBLISHING_ITEM' : 'COM_PHOCADOWNLOAD_ERROR_UNPUBLISHING_ITEM');
				break;
			case 'delete':
				$ok = $model->delete((int)$post['id']);
				$msg = Text::_($ok ? 'COM_PHOCADOWNLOAD_SUCCESS_DELETING_ITEM' : 'COM_PHOCADOWNLOAD_ERROR_DELETING_ITEM');
				break;
			default:
				return;
		}

		$lSO = '';
		if ($post['limitstart'] !== '' && $post['limitstart'] !== null) {
			$lSO = '&limitstart='.(int)$post['limitstart'];
		}

		$this->setRedirect( Route::_($this->url. $lSO, false), $msg );
	}

	function unpublish() {
		$this->processAction('unpublish');
	}

	function publish() {
		$this->processAction('publish');
	}

	function delete() {
		$this->processAction('delete');
	}
}
