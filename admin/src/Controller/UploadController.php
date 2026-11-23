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
namespace Phoca\Component\Phocadownload\Administrator\Controller;

defined('_JEXEC') or die( 'Restricted access' );
use Joomla\CMS\Factory;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Client\ClientHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\Text;
use Joomla\Filesystem\Path;
use Joomla\Filesystem\Folder;
use Joomla\Filesystem\File;
use Phoca\Component\Phocadownload\Administrator\File\File as PhocaDownloadFile;
use Phoca\Component\Phocadownload\Administrator\File\Upload;
use Phoca\Component\Phocadownload\Administrator\Path\Path as PhocaDownloadPath;
use Phoca\Component\Phocadownload\Administrator\Utils\Settings;
/* jimport('joomla.client.helper'); */
/* jimport('joomla.filesystem.file'); */
/* jimport('joomla.filesystem.folder'); */

class UploadController extends CpController
{
// 	function __construct() {
// 		parent::__construct();
// 	}

	function createfolder() {
		$app	= Factory::getApplication();
		// Check for request forgeries
		Session::checkToken() or jexit( 'COM_PHOCADOWNLOAD_INVALID_TOKEN' );

		// Set FTP credentials, if given
		/* jimport('joomla.client.helper'); */
		ClientHelper::setCredentialsFromRequest('ftp');

		$paramsC = ComponentHelper::getParams('com_phocadownload');
		$folder_permissions = $paramsC->get( 'folder_permissions', 0755 );
		//$folder_permissions = octdec((int)$folder_permissions);


		$folderNew		= Factory::getApplication()->getInput()->getCmd( 'foldername', '');
		$folderCheck	= Factory::getApplication()->getInput()->get( 'foldername', null, 'string');
		$parent			= Factory::getApplication()->getInput()->get( 'folderbase', '', 'path' );
		$tab			= Factory::getApplication()->getInput()->get( 'tab', 0, 'string' );
		$field			= Factory::getApplication()->getInput()->get( 'field');
		$viewBack		= Factory::getApplication()->getInput()->get( 'viewback', '', 'phocadownloadmanager' );
		$manager		= Factory::getApplication()->getInput()->get( 'manager', 'file', 'string' );


		$link = '';
		if ($manager != '') {
			$group 	= Settings::getManagerGroup($manager);
			$link	= 'index.php?option=com_phocadownload&view='.(string)$viewBack.'&manager='.(string)$manager
						 .str_replace('&amp;', '&', $group['c']).'&folder='.$parent.'&tab='.(string)$tab.'&field='.$field;

			$path	= PhocaDownloadPath::getPathSet($manager);// we use viewback to get right path
		} else {

			$app->enqueueMessage( Text::_('COM_PHOCADOWNLOAD_ERROR_CONTROLLER_MANAGER_NOT_SET'));
			$app->redirect('index.php?option=com_phocadownload');
			exit;
		}

		Factory::getApplication()->getInput()->set('folder', $parent);

		if (($folderCheck !== null) && ($folderNew !== $folderCheck)) {
			$app->enqueueMessage( Text::_('COM_PHOCADOWNLOAD_WARNING_DIRNAME'));
			$app->redirect($link);
		}


		if (strlen($folderNew) > 0) {
			$folder = Path::clean($path['orig_abs_ds'].$parent.'/'.$folderNew);

			if (!PhocaFile::folderExists($folder) && !PhocaFile::exists($folder)) {
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
					PhocaFile::hardenFolder($folder);
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
			//JFactory::getApplication()->getInput()->set('folder', ($parent) ? $parent.'/'.$folder : $folder);
		}
		$app->redirect($link);
	}

	function multipleupload() {
		$result = Upload::realMultipleUpload();
		return true;
	}

	function upload() {
		$result = Upload::realSingleUpload();
		return true;
	}


}
