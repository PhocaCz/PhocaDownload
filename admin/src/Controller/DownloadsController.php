<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

namespace Phoca\Component\Phocadownload\Administrator\Controller;

defined('_JEXEC') or die();
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
/* jimport('joomla.application.component.controllerform'); */

class DownloadsController extends FormController
{
	protected	$option 		= 'com_phocadownload';

	public function getModel($name = 'Download', $prefix = 'Administrator', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}

	public function cancel($key = NULL) {
		$model = $this->getModel( 'File' );
		$this->setRedirect( 'index.php?option=com_phocadownload&view=downloads' );
	}

	public function reset() {

		$post					= Factory::getApplication()->getInput()->get('post');
		$cid					= Factory::getApplication()->getInput()->get( 'cid', array(0), 'array' );
		$idFile					= Factory::getApplication()->getInput()->get( 'idfile', 0, 'int');

		$model = $this->getModel( 'Download' );

		if ($model->reset($cid)) {
			$msg = Text::_( 'COM_PHOCADOWNLOAD_SUCCESS_RESET_USER_STAT' );
		} else {
			$msg = Text::_( 'COM_PHOCADOWNLOAD_ERROR_RESET_USER_STAT' );
		}

		$link = 'index.php?option=com_phocadownload&view=downloads&id='.(int)$idFile;
		$this->setRedirect($link, $msg);
	}

	function delete() {


		$cid					= Factory::getApplication()->getInput()->get( 'cid', array(0), 'array' );


		$model = $this->getModel( 'Download' );

		if ($model->delete($cid)) {
			$msg = Text::_( 'COM_PHOCADOWNLOAD_SUCCESS_DELETE_USER_STAT' );
		} else {
			$msg = Text::_( 'COM_PHOCADOWNLOAD_ERROR_DELETE_USER_STAT' );
		}

		$link = 'index.php?option=com_phocadownload&view=downloads';
		$this->setRedirect($link, $msg);
	}
}
?>
