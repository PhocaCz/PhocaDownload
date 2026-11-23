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
namespace Phoca\Component\Phocadownload\Administrator\Model;

defined( '_JEXEC' ) or die();
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Factory;
use Joomla\CMS\Application\ApplicationHelper;
use Phoca\Component\Phocadownload\Administrator\MVC\Model\AdminModelTrait;
use Phoca\Component\Phocadownload\Administrator\Utils\Utils;
/* jimport('joomla.application.component.modeladmin'); */


class TagModel extends AdminModel
{

    use AdminModelTrait;
	protected	$option 		= 'com_phocadownload';
	protected 	$text_prefix	= 'com_phocadownload';
	public 		$typeAlias 		= 'com_phocadownload.tag';

	protected function canDelete($record)
	{
		//$user = J$app->getIdentity();
		return parent::canDelete($record);
	}

	protected function canEditState($record)
	{
		//$user = J$app->getIdentity();
		return parent::canEditState($record);
	}

	/*
	public function getTable($type = 'PhocaDownloadTag', $prefix = 'Table', $config = array())
	{
		return Table::getInstance($type, $prefix, $config);
	}
	*/

	public function getForm($data = array(), $loadData = true) {

		$app	= Factory::getApplication();
		$form 	= $this->loadForm('com_phocadownload.tag', 'tag', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = Factory::getApplication()->getUserState('com_phocadownload.edit.phocadownloadtag.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

	protected function prepareTable($table)
	{
		/* jimport('joomla.filter.output'); */
		$date = Factory::getDate();
		$app = Factory::getApplication();
		$user = $app->getIdentity();

		$table->title		= htmlspecialchars_decode($table->title, ENT_QUOTES);
		$table->alias		= ApplicationHelper::stringURLSafe($table->alias);

		$table->link_cat	= Utils::getIntFromString($table->link_cat);

		if (empty($table->alias)) {
			$table->alias = ApplicationHelper::stringURLSafe($table->title);
		}

		if (empty($table->id)) {
			// Set the values
			//$table->created	= $date->toSql();

			// Set ordering to the last item if not set
			if (empty($table->ordering)) {
				$db = Factory::getContainer()->get(\Joomla\Database\DatabaseInterface::class);
				$db->setQuery('SELECT MAX(ordering) FROM #__phocadownload_tags');
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
}
?>
