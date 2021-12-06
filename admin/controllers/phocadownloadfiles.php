<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Factory;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\Router\Route;
jimport('joomla.application.component.controlleradmin');

class PhocaDownloadCpControllerPhocaDownloadFiles extends AdminController
{
	protected	$option 		= 'com_phocadownload';
	
	public function __construct($config = array())
	{
		parent::__construct($config);	
		$this->registerTask('disapprove',	'approve');
	
	}
	
	public function &getModel($name = 'PhocaDownloadFile', $prefix = 'PhocaDownloadCpModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	
	function approve()
	{
		// Check for request forgeries
		Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

		// Get items to publish from the request.
		$cid	= Factory::getApplication()->input->get('cid', array(), '', 'array');
		$data	= array('approve' => 1, 'disapprove' => 0);
		$task 	= $this->getTask();
		$value	= ArrayHelper::getValue($data, $task, 0, 'int');

		if (empty($cid)) {
			throw new Exception(Text::_($this->text_prefix.'_NO_ITEM_SELECTED'), 500);
			return false;
		} else {
			// Get the model.
			$model = $this->getModel();

			// Make sure the item ids are integers
			ArrayHelper::toInteger($cid);

			// Publish the items.
			
			if (!$model->approve($cid, $value)) {
				throw new Exception($model->getError(), 500);
				return false;
			} else {
				if ($value == 1) {
					$ntext = $this->text_prefix.'_N_ITEMS_APPROVED';
				} else if ($value == 0) {
					$ntext = $this->text_prefix.'_N_ITEMS_DISAPPROVED';
				} 
				$this->setMessage(Text::plural($ntext, count($cid)));
			}
		}

		$this->setRedirect(Route::_('index.php?option='.$this->option.'&view='.$this->view_list, false));
	}
	
	public function saveOrderAjax() {
		Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));
		$pks = $this->input->post->get('cid', array(), 'array');
		$order = $this->input->post->get('order', array(), 'array');
		ArrayHelper::toInteger($pks);
		ArrayHelper::toInteger($order);
		$model = $this->getModel();
		$return = $model->saveorder($pks, $order);
		if ($return) { echo "1";}
		Factory::getApplication()->close();
	}
	
}
?>