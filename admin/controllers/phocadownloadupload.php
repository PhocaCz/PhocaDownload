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
defined('_JEXEC') or die( 'Restricted access' );
use Joomla\CMS\Factory;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Client\ClientHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Filesystem\File;
jimport('joomla.client.helper');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class PhocaDownloadCpControllerPhocaDownloadUpload extends PhocaDownloadCpController
{
	function __construct() {
		parent::__construct();
	}

	function createfolder() {
		$app	= Factory::getApplication();
		// Check for request forgeries
		Session::checkToken() or jexit( 'COM_PHOCADOWNLOAD_INVALID_TOKEN' );

		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		ClientHelper::setCredentialsFromRequest('ftp');

		$paramsC = ComponentHelper::getParams('com_phocadownload');
		$folder_permissions = $paramsC->get( 'folder_permissions', 0755 );
		//$folder_permissions = octdec((int)$folder_permissions);


		$folderNew		= Factory::getApplication()->input->getCmd( 'foldername', '');
		$folderCheck	= Factory::getApplication()->input->get( 'foldername', null, 'string');
		$parent			= Factory::getApplication()->input->get( 'folderbase', '', 'path' );
		$tab			= Factory::getApplication()->input->get( 'tab', 0, 'string' );
		$field			= Factory::getApplication()->input->get( 'field');
		$viewBack		= Factory::getApplication()->input->get( 'viewback', '', 'phocadownloadmanager' );
		$manager		= Factory::getApplication()->input->get( 'manager', 'file', 'string' );


		$link = '';
		if ($manager != '') {
			$group 	= PhocaDownloadSettings::getManagerGroup($manager);
			$link	= 'index.php?option=com_phocadownload&view='.(string)$viewBack.'&manager='.(string)$manager
						 .str_replace('&amp;', '&', $group['c']).'&folder='.$parent.'&tab='.(string)$tab.'&field='.$field;

			$path	= PhocaDownloadPath::getPathSet($manager);// we use viewback to get right path
		} else {

			$app->enqueueMessage( Text::_('COM_PHOCADOWNLOAD_ERROR_CONTROLLER_MANAGER_NOT_SET'));
			$app->redirect('index.php?option=com_phocadownload');
			exit;
		}

		Factory::getApplication()->input->set('folder', $parent);

		if (($folderCheck !== null) && ($folderNew !== $folderCheck)) {
			$app->enqueueMessage( Text::_('COM_PHOCADOWNLOAD_WARNING_DIRNAME'));
			$app->redirect($link);
		}


		if (strlen($folderNew) > 0) {
			$folder = Path::clean($path['orig_abs_ds'].$parent.'/'.$folderNew);

			if (!Folder::exists($folder) && !File::exists($folder)) {
				//JFolder::create($path, $folder_permissions );

				switch((int)$folder_permissions) {
					case 777:
						Folder::create($folder, 0777 );
					break;
					case 705:
						Folder::create($folder, 0705 );
					break;
					case 666:
						Folder::create($folder, 0666 );
					break;
					case 644:
						Folder::create($folder, 0644 );
					break;
					case 755:
					Default:
						Folder::create($folder, 0755 );
					break;
				}
				if (isset($folder)) {
					$data = "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>";
					File::write($folder.'/'."index.html", $data);
				} else {
				    $app->enqueueMessage(Text::_("COM_PHOCADOWNLOAD_ERROR_FOLDER_CREATING"), 'error');
					$app->redirect($link);
				}

				$app->enqueueMessage(Text::_("COM_PHOCADOWNLOAD_SUCCESS_FOLDER_CREATING"), 'success');
				$app->redirect($link);
			} else {
			    $app->enqueueMessage(Text::_("COM_PHOCADOWNLOAD_ERROR_FOLDER_CREATING_EXISTS"), 'error');
				$app->redirect($link);
			}
			//JFactory::getApplication()->input->set('folder', ($parent) ? $parent.'/'.$folder : $folder);
		}
		$app->redirect($link);
	}

	function multipleupload() {
		$result = PhocaDownloadFileUpload::realMultipleUpload();
		return true;
	}

	function upload() {
		$result = PhocaDownloadFileUpload::realSingleUpload();
		return true;
	}


}
