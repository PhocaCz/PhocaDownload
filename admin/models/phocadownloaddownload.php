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
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Table\Table;
use Joomla\Registry\Registry;
use Joomla\CMS\Application\ApplicationHelper;
use Joomla\Utilities\ArrayHelper;
jimport('joomla.application.component.modeladmin');

class PhocaDownloadCpModelPhocaDownloadDownload extends AdminModel
{
	protected	$option 		= 'com_phocadownload';
	protected 	$text_prefix	= 'com_phocadownload';
	public 		$typeAlias 		= 'com_phocadownload.phocadownloaddownload';

	protected function canDelete($record)
	{

		$user = Factory::getUser();

		if (!empty($record->catid)) {
			return $user->authorise('core.delete', 'com_phocadownload.phocadownloaddownload.'.(int) $record->catid);
		} else {
			return parent::canDelete($record);
		}
	}



	public function getTable($type = 'PhocaDownloadDownload', $prefix = 'Table', $config = array())
	{
		return Table::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true) {

		$app	= Factory::getApplication();
		$form 	= $this->loadForm('com_phocadownload.phocadownloaddownload', 'phocadownloaddownload', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form)) {
			return false;
		}
		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = Factory::getApplication()->getUserState('com_phocadownload.edit.phocadownload.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

		public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk)) {
			// Convert the params field to an array.
			if (isset($item->metadata)) {
				$registry = new Registry;
				$registry->loadString($item->metadata);
				$item->metadata = $registry->toArray();
			}

		}

		return $item;
	}

	protected function prepareTable($table)
	{
		jimport('joomla.filter.output');
		$date = Factory::getDate();
		$user = Factory::getUser();

		$table->title		= htmlspecialchars_decode($table->title, ENT_QUOTES);
		$table->alias		= ApplicationHelper::stringURLSafe($table->alias);


		$table->confirm_license	= PhocaDownloadUtils::getIntFromString($table->confirm_license);
		$table->hits			= PhocaDownloadUtils::getIntFromString($table->hits);
		$table->tokenhits		= PhocaDownloadUtils::getIntFromString($table->tokenhits);

		if (empty($table->alias)) {
			$table->alias = ApplicationHelper::stringURLSafe($table->title);
		}

		if (empty($table->id)) {
			// Set the values
			//$table->created	= $date->toSql();

			// Set ordering to the last item if not set
			if (empty($table->ordering)) {
				$db = Factory::getDbo();
				//$db->setQuery('SELECT MAX(ordering) FROM #__phocadownload');
				$db->setQuery('SELECT MAX(ordering) FROM #__phocadownload WHERE catid = '.(int)$table->catid);
				$max = $db->loadResult();

				$table->ordering = $max+1;
			}
		}
		else {
			// Set the values
			//$table->modified	= $date->toSql();
			//$table->modified_by	= $user->get('id');
		}
	}


function delete(&$cid = array()) {


		$result 			= false;

		if (count( $cid )) {
			ArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );


			//Delete it from DB
			$query = 'DELETE FROM #__phocadownload_user_stat'
				. ' WHERE id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->execute()) {
				throw new Exception($this->_db->getError());
				return false;
			}

		}
		return true;
	}
}
?>
