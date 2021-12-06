<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die();
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Factory;
use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Client\ClientHelper;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Log\Log;
jimport('joomla.application.component.modeladmin');


class PhocaDownloadCpModelPhocaDownloadStyle extends AdminModel
{
	protected	$option 		= 'com_phocadownload';
	protected 	$text_prefix	= 'com_phocadownload';
	public 		$typeAlias 		= 'com_phocadownload.phocadownloadstyle';
	
	protected function canDelete($record)
	{
		//$user = JFactory::getUser();
		return parent::canDelete($record);
	}
	
	protected function canEditState($record)
	{
		//$user = JFactory::getUser();
		return parent::canEditState($record);
	}
	
	public function getTable($type = 'PhocaDownloadStyle', $prefix = 'Table', $config = array())
	{
		return Table::getInstance($type, $prefix, $config);
	}
	
	public function getForm($data = array(), $loadData = true) {
		
		$app	= Factory::getApplication();
		$form 	= $this->loadForm('com_phocadownload.phocadownloadstyles', 'phocadownloadstyle', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}
	
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = Factory::getApplication()->getUserState('com_phocadownload.edit.phocadownloadstyles.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}
	
	protected function prepareTable($table)
	{
		jimport('joomla.filter.output');
		$date = Factory::getDate();
		$user = Factory::getUser();

		$table->title		= htmlspecialchars_decode($table->title, ENT_QUOTES);
		$table->alias		= ApplicationHelper::stringURLSafe($table->alias);

		if (empty($table->alias)) {
			$table->alias = ApplicationHelper::stringURLSafe($table->title);
		}

		if (empty($table->id)) {
			// Set the values
			//$table->created	= $date->toSql();

			// Set ordering to the last item if not set
			if (empty($table->ordering)) {
				$db = Factory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__phocadownload_styles WHERE type = '.(int)$table->type);
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
	
	protected function getReorderConditions($table = null)
	{
		$condition = array();
		$condition[] = 'type = '. (int) $table->type;
		//$condition[] = 'state >= 0';
		return $condition;
	}
	
	public function increaseOrdering($categoryId) {
		
		$ordering = 1;
		$this->_db->setQuery('SELECT MAX(ordering) FROM #__phocadownload_styles WHERE type='.(int)$categoryId);
		$max = $this->_db->loadResult();
		$ordering = $max + 1;
		return $ordering;
	}
	
	public function &getSource($id, $filename, $type) {
		$item = new stdClass;
		
		$filePath = PhocaDownloadFile::existsCSS($filename, $type);
		if ($filePath) {
			//$item->id			= $id;
			//$item->type			= $type;
			//$item->filname      = $filename;
			$item->source       = file_get_contents($filePath);
		} else {
		
			throw new Exception(Text::_('COM_PHOCADOWNLOAD_FILE_DOES_NOT_EXIST'), 500);
		}
		return $item;
	}
	
	public function save($data) {
		jimport('joomla.filesystem.file');
		
		// New
		if ($data['id'] < 1) {
			$data['type'] = 2;// Custom in every case
			if ($data['title'] != '') {
				$filename = ApplicationHelper::stringURLSafe($data['title']);
				
				if (trim(str_replace('-','',$filename)) == '') {
					$filename = Factory::getDate()->toFormat("%Y-%m-%d-%H-%M-%S");
				}
			} else {
				$filename = Factory::getDate()->toFormat("%Y-%m-%d-%H-%M-%S");
			}
			$filename 			= $filename . '.css';
			$data['filename']	= $filename;
			
			$filePath = PhocaDownloadFile::existsCSS($filename, $data['type']);
			if ($filePath) {
				
				throw new Exception(Text::sprintf('COM_PHOCADOWNLOAD_FILE_ALREADY_EXISTS', $fileName), 500);
				return false;
			} else {
				$filePath = PhocaDownloadFile::getCSSPath($data['type']) . $filename;
			}
		} else {
			
			$filename = PhocaDownloadFile::getCSSFile($data['id']);
			
			$filePath = PhocaDownloadFile::existsCSS($filename, $data['type']);
		}
		
		//$dispatcher = JEventDispatcher::getInstance();
		$fileName	= $filename;


		// Include the extension plugins for the save events.
		//JPluginHelper::importPlugin('extension');

		// Set FTP credentials, if given.
		ClientHelper::setCredentialsFromRequest('ftp');
		$ftp = ClientHelper::getCredentials('ftp');

		// Try to make the template file writeable.
		if (!$ftp['enabled'] && Path::isOwner($filePath) && !Path::setPermissions($filePath, '0644')) {
			
			throw new Exception(Text::_('COM_PHOCADOWNLOAD_ERROR_SOURCE_FILE_NOT_WRITABLE'), 500);
			return false;
		}

		// Trigger the onExtensionBeforeSave event.
		/*$result = $dispatcher->trigger('onExtensionBeforeSave', array('com_phocadownload.source', &$data, false));
		if (in_array(false, $result, true)) {
			throw new Exception($table->getError(), 500);
			return false;
		}*/

		$return = File::write($filePath, $data['source']);

		// Try to make the template file unwriteable.
		
			
		if (!$return) {
			
			throw new Exception(Text::sprintf('COM_PHOCADOWNLOAD_ERROR_FAILED_TO_SAVE_FILENAME', $fileName), 500);
			return false;
		}

		// Trigger the onExtensionAfterSave event.
		//$dispatcher->trigger('onExtensionAfterSave', array('com_templates.source', &$table, false));

		//return true;
		return parent::save($data);
	}
	
	public function delete(&$pks)
	{
		//$dispatcher = JEventDispatcher::getInstance();
		$pks = (array) $pks;
		$table = $this->getTable();

		// Include the content plugins for the on delete events.
		PluginHelper::importPlugin('content');

		// Iterate the items to delete each one.
		foreach ($pks as $i => $pk)
		{

			if ($table->load($pk))
			{

				if ($this->canDelete($table))
				{

					$context = $this->option . '.' . $this->name;

					// Trigger the onContentBeforeDelete event.
					$result = Factory::getApplication()->triggerEvent($this->event_before_delete, array($context, $table));
					if (in_array(false, $result, true))
					{
						throw new Exception($table->getError(), 500);
						return false;
					}
					
					//PHOCAEDIT
					$filePath = PhocaDownloadFile::getCSSFile($pk, true);
					//END PHOCAEDIT
					
					if (!$table->delete($pk))
					{
						throw new Exception($table->getError(), 500);
						return false;
					}

					//PHOCAEDIT
					if (file_exists($filePath)) {
						File::delete($filePath);
					}
					//END PHOCAEDIT
					
					// Trigger the onContentAfterDelete event.
					Factory::getApplication()->triggerEvent($this->event_after_delete, array($context, $table));

				}
				else
				{

					// Prune items that you can't change.
					unset($pks[$i]);
					$error = $this->getError();
					if ($error)
					{
						Log::add($error, Log::WARNING, '');
						return false;
					}
					else
					{
						Log::add(Text::_('JLIB_APPLICATION_ERROR_DELETE_NOT_PERMITTED'), Log::WARNING, '');
						return false;
					}
				}

			}
			else
			{
				throw new Exception($table->getError(), 500);
				return false;
			}
		}

		// Clear the component's cache
		$this->cleanCache();

		return true;
	}
}
?>