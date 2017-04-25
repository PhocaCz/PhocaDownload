<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die;

class PhocaDownloadCpControllerPhocaDownloadStyles extends JControllerAdmin
{
	protected	$option 		= 'com_phocadownload';
	
	public function __construct($config = array()){
		parent::__construct($config);
		$this->registerTask('apply', 'save');
	}

	public function &getModel($name = 'PhocaDownloadStyle', $prefix = 'PhocaDownloadCpModel', $config = array()) {
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	public function saveOrderAjax() {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$pks = $this->input->post->get('cid', array(), 'array');
		$order = $this->input->post->get('order', array(), 'array');
		JArrayHelper::toInteger($pks);
		JArrayHelper::toInteger($order);
		$model = $this->getModel();
		$return = $model->saveorder($pks, $order);
		if ($return) { echo "1";}
		JFactory::getApplication()->close();
	}
	

}
?>