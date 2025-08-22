<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Download
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();

use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Pagination\Pagination;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Client\ClientHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\Text;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Path;
use Joomla\Filesystem\Folder;
jimport('joomla.application.component.model');
jimport( 'joomla.filesystem.folder' );
jimport( 'joomla.filesystem.file' );
use Joomla\String\StringHelper;

class PhocaDownloadModelUser extends BaseDatabaseModel
{
	var $_data_files 			= null;
	var $_total_files	 		= null;
	var $_pagination_files 		= null;
	var $_context_files			= 'com_phocadownload.phocadownloaduserfiles';

	protected $event_before_save = null;
	protected $event_after_save = null;


	function __construct() {
		parent::__construct();

		//if (isset($config['event_before_save'])) {
        //    $this->event_before_save = $config['event_before_save'];
        //} elseif (empty($this->event_before_save)) {
            $this->event_before_save = 'onContentBeforeSave';
        //}
		$this->event_after_save = 'onContentAfterSave';

		$app	= Factory::getApplication();
		// SubCategory
		$limit_files		= $app->getUserStateFromRequest( $this->_context_files.'.list.limit', 'limit', 20, 'int' );
		$limitstart_files 	= $app->getInput()->get('limitstart', 0, 'int');
		$limitstart_files 	= ($limit_files != 0 ? (floor($limitstart_files / $limit_files) * $limit_files) : 0);
		$this->setState($this->_context_files.'.list.limit', $limit_files);
		$this->setState($this->_context_files.'.list.limitstart', $limitstart_files);

	}

	function getDataFiles($userId) {
		if (empty($this->_data_files)) {
			$query = $this->_buildQueryFiles($userId);
			$this->_data_files = $this->_getList($query, $this->getState($this->_context_files.'.list.limitstart'), $this->getState($this->_context_files.'.list.limit'));

		}
		return $this->_data_files;
	}

	function getTotalFiles($userId) {
		if (empty($this->_total_files)) {
			$query = $this->_buildQueryFiles($userId);
			$this->_total_files = $this->_getListCount($query);
		}
		return $this->_total_files;
	}

	function getPaginationFiles($userId) {
		if (empty($this->_pagination_files)) {
			jimport('joomla.html.pagination');
			$this->_pagination_files = new Pagination( $this->getTotalFiles($userId),  $this->getState($this->_context_files.'.list.limitstart'), $this->getState($this->_context_files.'.list.limit') );
		}
		return $this->_pagination_files;
	}

	function _buildQueryFiles($userId) {
		$where		= $this->_buildContentWhereFiles($userId);
		$orderby	= $this->_buildContentOrderByFiles();

		$query = ' SELECT a.*, cc.title AS categorytitle, u.name AS editor, ag.title AS access_level, us.id AS ownerid, us.username AS ownername '
			. ' FROM #__phocadownload AS a '
			. ' LEFT JOIN #__phocadownload_categories AS cc ON cc.id = a.catid'
			. ' LEFT JOIN #__viewlevels AS ag ON ag.id = a.access'
			. ' LEFT JOIN #__users AS u ON u.id = a.checked_out'
			. ' LEFT JOIN #__users AS us ON us.id = a.owner_id'
			. $where
			. $orderby;
		return $query;
	}


	function _buildContentOrderByFiles() {
		$app				= Factory::getApplication();
		$filter_order		= $app->getUserStateFromRequest( $this->_context_files.'.filter_order',	'filter_order',	'a.ordering', 'cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( $this->_context_files.'.filter_order_Dir',	'filter_order_Dir',	'',	'word' );

		if ($filter_order == 'a.ordering'){
			$orderby 	= ' ORDER BY categorytitle, a.ordering '.$filter_order_Dir;
		} else {
			$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir.' , categorytitle, a.ordering ';
		}
		return $orderby;
	}

	function _buildContentWhereFiles($userId) {
		$app				= Factory::getApplication();
		$filter_published		= $app->getUserStateFromRequest( $this->_context_files.'.filter_published','filter_published','',	'word' );
		$filter_catid		= $app->getUserStateFromRequest( $this->_context_files.'.catid','catid',0,'int' );
		//$filter_sectionid	= $app->getUserStateFromRequest( $this->_context_files.'.filter_sectionid',	'filter_sectionid',	0,	'int' );
		$filter_order		= $app->getUserStateFromRequest( $this->_context_files.'.filter_order','filter_order','a.ordering','cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( $this->_context_files.'.filter_order_Dir','filter_order_Dir_files',	'', 'word' );
		$search				= $app->getUserStateFromRequest( $this->_context_files.'.search', 'search', '', 'string' );
		$search				= StringHelper::strtolower( $search );

		$where = array();

		$where[] = 'a.owner_id = '.(int)$userId;
		$where[] = 'a.owner_id > 0'; // Ignore -1

		if ($filter_catid > 0) {
			$where[] = 'a.catid = '.(int) $filter_catid;
		}
		if ($search) {
			$where[] = 'LOWER(a.title) LIKE '.$this->_db->Quote('%'.$search.'%');
		}
		if ( $filter_published ) {
			if ( $filter_published == 'P' ) {
				$where[] = 'a.published = 1';
			} else if ($filter_published == 'U' ) {
				$where[] = 'a.published = 0';
			}
		}
		//if ( $filter_sectionid ) {
		//	$where[] = 'cc.section = '.(int)$filter_sectionid;
		//}
		$where 		= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );
		return $where;
	}





	/*
	 * Add Image
	 */
/*
	function storefile($data, $return, $edit = false) {

		if (!$edit) {
			//If this file doesn't exists don't save it
			if (!PhocaDownloadFile::existsFileOriginal($data['filename'])) {
				$this->set Error('File not exists');
				return false;
			}

			$data['imgorigsize'] 	= phocadownloadFile::getFileSize($data['filename'], 0);

			//If there is no title and no alias, use filename as title and alias
			if (!isset($data['title']) || (isset($data['title']) && $data['title'] == '')) {
				$data['title'] = phocadownloadFile::getTitleFromFile($data['filename']);
			}

			if (!isset($data['alias']) || (isset($data['alias']) && $data['alias'] == '')) {
				$data['alias'] = phocadownloadFile::getTitleFromFile($data['filename']);
			}

			//clean alias name (no bad characters)
			$data['alias'] = phocadownloadText::getAliasName($data['alias']);

		} else {
			$data['alias'] = phocadownloadText::getAliasName($data['title']);
		}

		$row = $this->getTable('phocadownload');


		if(isset($data['id']) && $data['id'] > 0) {
			if (!$row->load($data['id'])) {
				throw new Exception($this->_db->getErrorMsg(), 500);
				return false;
			}
		}

		// Bind the form fields to the Phoca gallery table
		if (!$row->bind($data)) {
			throw new Exception($this->_db->getErrorMsg(), 500);
			return false;
		}

		// Create the timestamp for the date
		$row->date 				= gmdate('Y-m-d H:i:s');

		// if new item, order last in appropriate group
		if (!$row->id) {
			$where = 'catid = ' . (int) $row->catid ;
			$row->ordering = $row->getNextOrder( $where );
		}

		// Make sure the Phoca gallery table is valid
		if (!$row->check()) {
			throw new Exception($this->_db->getErrorMsg(), 500);
			return false;
		}

		// Store the Phoca gallery table to the database
		if (!$row->store()) {
			throw new Exception($this->_db->getErrorMsg(), 500);
			return false;
		}

		if(!$edit) {
			//Create thumbnail small, medium, large
			$returnFrontMessage = phocadownloadFileThumbnail::getOrCreateThumbnail($row->filename, $return, 1, 1, 1, 1);

			if ($returnFrontMessage == 'Success') {
				return true;
			} else {
				return false;
			}
		} else {
			if (isset($row->id)) {
				return $row->id;
			} else {
				return false;
			}
		}
	}
	*/

	function singleFileUpload(&$errUploadMsg, $file, $post) {

		$app		= Factory::getApplication();;
		Session::checkToken( 'request' ) or jexit( 'Invalid Token' );
		jimport('joomla.client.helper');
		$user 				= Factory::getUser();
		$ftp 		= ClientHelper::setCredentialsFromRequest('ftp');
		$path		= PhocaDownloadPath::getPathSet();
		$folder		= $app->getInput()->get( 'folder', '', '', 'path' );
		$format		= $app->getInput()->get( 'format', 'html', '', 'cmd');
		$return		= $app->getInput()->get( 'return-url', null, 'post', 'base64' );
		$viewBack	= $app->getInput()->get( 'viewback', '', 'post', 'string' );
		//$catid 		= $app->getInput()->get( 'catid', '', '', 'int'  );
		$paramsC 	= ComponentHelper::getParams('com_phocadownload') ;

		$overwriteExistingFiles 	= $paramsC->get( 'overwrite_existing_files', 0 );

		// USER RIGHT - UPLOAD - - - - - - - - - - -
		// 2, 2 means that user access will be ignored in function getUserRight for display Delete button
		$rightDisplayUpload	= 0;
		$catAccess	= PhocaDownloadAccess::getCategoryAccess((int)$post['catidfiles']);
		if (!empty($catAccess)) {
			$rightDisplayUpload = PhocaDownloadAccess::getUserRight('uploaduserid', $catAccess->uploaduserid, 2, $user->getAuthorisedViewLevels(), 1, 0);
		}
		// - - - - - - - - - - - - - - - - - - - - - -


		/*$post['sectionid'] = $this->getSection((int)$post['catidfiles']);
		if(!$post['sectionid']) {
			$errUploadMsg = Text::_('COM_PHOCADOWNLOAD_WRONG_SECTION');
			return false;
		}*/

		//$userFolder = substr(md5($user->username),0, 10);
		$userFolder = PhocaDownloadUtils::cleanFolderUrlName(htmlspecialchars(strip_tags($user->username)));

		if ($rightDisplayUpload == 1) {

			// Make the filename safe
			if (isset($file['name'])) {
				$file['name']	= File::makeSafe($file['name']);
			}

			if($file['tmp_name'] == '') {
				$errUploadMsg = Text::_("COM_PHOCADOWNLOAD_ERROR_SERVER_NOT_ABLE_TO_STORE_FILE_TEMP_FOLDER");
				return false;
			}

			if (isset($file['name'])) {
				$filepath 				= Path::clean($path['orig_abs_user_upload']. '/'. $userFolder . '/'.$file['name']);
				$filepathUserFolder 	= Path::clean($path['orig_abs_user_upload']. '/'. $userFolder);
				if (!PhocaDownloadFileUpload::canUpload( $file, $errUploadMsg, 'file', 2 )) {

					if ($errUploadMsg == 'COM_PHOCADOWNLOAD_WARNUSERFILESTOOLARGE') {
						$errUploadMsg 	= Text::_($errUploadMsg) . ' ('.PhocaDownloadFile::getFileSizeReadable($file['size']).')';
					} else {
						$errUploadMsg 	= Text::_($errUploadMsg);
					}

					return false;
				}

				if (PhocaDownloadFile::exists($filepath) && $overwriteExistingFiles == 0) {
					$errUploadMsg = Text::_("COM_PHOCADOWNLOAD_FILE_ALREADY_EXISTS");
					return false;
				}

				// Overwrite file and add no new item to database
				$fileExists = 0;
				if (PhocaDownloadFile::exists($filepath) && $overwriteExistingFiles == 1) {
					$fileExists = 1;
				}

				if (!File::upload($file['tmp_name'], $filepath, false, true)) {
					$errUploadMsg = Text::_("COM_PHOCADOWNLOAD_UNABLE_TO_UPLOAD_FILE");
					return false;
				} else {

					// Saving file name into database with relative path
					if (!PhocaDownloadFile::exists($filepathUserFolder . '/' ."index.html")) {
						$data = "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>";
						File::write($filepathUserFolder . '/' ."index.html", $data);
					}
					$file['namepap']	= $file['name'];
					$file['name']		=  'userupload/'.$userFolder.'/' . $file['name'];
					$succeeded 			= false;

					// =================================================
					// Make a copy for play and preview
					$papCopy 	= $paramsC->get( 'pap_copy', 0 );

					if ($papCopy == 1 || $papCopy == 3) {
						$canPlay	= PhocaDownloadFile::canPlay($file['namepap']);
						$canPreview = PhocaDownloadFile::canPreview($file['namepap']);
						$filepathPAP 			= Path::clean($path['orig_abs_user_upload_pap']. '/'. $userFolder . '/'.$file['namepap']);
						$filepathUserFolderPAP 	= Path::clean($path['orig_abs_user_upload_pap']. '/'. $userFolder);

						if ($canPlay || $canPreview) {

							// 1. UPLOAD - this is real upload to folder
							// 2. STORE - this is storing info to database (e.g. download and play/preview files are identical, then there will be no copy of the file but only storing DB info
							$uploadPAP = 1;// upload file for preview and play
							$storePAP = 0;


							// 1. Care about upload
							if (PhocaDownloadFile::exists($filepathPAP) && $overwriteExistingFiles == 0) {
								//$errUploadMsg = JText::_("COM_PHOCADOWNLOAD_FILE_ALREADY_EXISTS");
								//return false;
								$uploadPAP = 0; // don't upload if it exists, it is not main file, don't do false and exit
							}

							// Overwrite file and add no new item to database
							$fileExistsPAP = 0;
							if (PhocaDownloadFile::exists($filepathPAP) && $overwriteExistingFiles == 1) {
								$fileExistsPAP = 1;
							}

							if ($uploadPAP == 0) {

							} else {
								if (!PhocaDownloadFile::folderExists($filepathUserFolderPAP)) {
									if (Folder::create($filepathUserFolderPAP)) {
										$data = "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>";
										File::write($filepathUserFolderPAP . '/' ."index.html", $data);
									}
									// else {
										//$errUploadMsg = JText::_("COM_PHOCADOWNLOAD_UNABLE_TO_CREATE_FOLDER");
										//return false;
									//}
								}

								if ($filepath === $filepathPAP) {
									// Don't try to copy the same file to the same file (including path) because you get error
									$storePAP = 1;
								} else if (!File::copy($filepath, $filepathPAP)) {

									//$errUploadMsg = JText::_("COM_PHOCADOWNLOAD_UNABLE_TO_UPLOAD_FILE");
									//return false;
								} else {
									$storePAP = 1;
								}
							}

							// 2. Care about store
							if ($filepath === $filepathPAP) {

								// SPECIFIC CASE - administrator set the download folder the same like preview/play folder
								//               - in such case, there will be no copy because both files including path are identical
								//               - but we still write the info about play/preview into database
								//               - so no set uploadPAP to 0
								$storePAP = 1;
							}

							if ($storePAP == 1) {
								if ($canPlay == 1) {
									$post['filename_play']		=  'userupload/'.$userFolder.'/' . $file['namepap'];
								} else if ($canPreview == 1) {
									$post['filename_preview']	=  'userupload/'.$userFolder.'/' . $file['namepap'];
								}
							}
						}
					}
					// ==============================================

					if ($this->_save($post, $file['name'], $errUploadMsg, $fileExists)) {

						return true;
					} else {
						return false;
					}
				}
			} else {
				$errUploadMsg = Text::_("COM_PHOCADOWNLOAD_WARNFILETYPE");
				$redirectUrl = $return;
				return false;
			}
		} else {
			$errUploadMsg = Text::_("COM_PHOCADOWNLOAD_NOT_AUTHORISED_TO_UPLOAD");

			return false;
		}
		return false;


	}

	function _save($data, $filename, &$errSaveMsg, $fileExists = 0) {

		$user 	= Factory::getUser();
		$app = Factory::getApplication();

		$paramsC 					= ComponentHelper::getParams('com_phocadownload') ;
		$default_access 			= $paramsC->get( 'default_access', 1 );
		$frontend_run_events 		= $paramsC->get( 'frontend_run_events', 0 );
		$fileId = false;
		if ($fileExists == 1) {
			// We not only owerwrite the file but we must update it
			if (isset($filename) && $filename != '') {

				$db = Factory::getDBO();

				$query = 'SELECT a.id AS id'
				.' FROM #__phocadownload AS a'
			    .' WHERE a.filename = '.$db->Quote($filename);

				$db->setQuery($query, 0, 1);
				$fileId = $db->loadObject();

				/*if (!$db->query()) {
					throw new Exception($db->getErrorMsg(), 500);
					return false;
				}*/
			}
		}

		$row = $this->getTable('phocadownload');

		$isNew = true;
		if (isset($fileId->id) && (int)$fileId->id > 0) {
			$data['id'] = (int)$fileId->id;
			$isNew = false;
		}



		$data['filesize'] 	= PhocaDownloadFile::getFileSize($filename, 0);

		$data['userid']			= $user->id;
		$data['author_email']	= $data['email'];
		$data['author_url']		= $data['website'];
		$data['access']			= $default_access;
		$data['token']			= PhocaDownloadUtils::getToken($data['title'].$filename);
		//$data['token']			= PhocaDownloadUtils::getToken($data['title'].$data['filename']);

		// Bind the form fields to the Phoca gallery table
		if (!$row->bind($data)) {
			//throw new Exception($this->_db->getError());
			$this->setError($row->getError());
			return false;
		}



		// Create the timestamp for the date
		//$row->date 			= gmdate('Y-m-d H:i:s');
		//$row->publish_up	= gmdate('Y-m-d H:i:s');
		//$jnow		=JFactory::getDate();
		/*$jnowU		= $jnow->toUnix();
		if (isset($jnowU)) {
			$jnowU = (int)$jnowU - 2; // to not display pending because of 1 second
		}*/

		$unow		= time();
		$unow		= $unow - 2;//Frontend will display pending if standard $jnow->toSql(); will be used
		$jnow		= Factory::getDate($unow);// the class JDate construct works with unix date
		$now		= $jnow->toSql();

		$row->date 			= $now;
		$row->publish_up	= $now; //date('Y-m-d H:i:s', $jnowU);
		//$row->publish_down	= null;
		$row->publish_down	= '0000-00-00 00:00:00';
		$row->filename	= $filename;
		$row->catid		= $data['catidfiles'];

		// Lang
		$userLang			= PhocaDownloadUser::getUserLang();
		$row->language		= $userLang['lang'];


		// if new item, order last in appropriate group
		if (!$row->id) {
			$where = 'catid = ' . (int) $row->catid ;
			$row->ordering = $row->getNextOrder( $where );
		}

		// Make sure the Phoca gallery table is valid
		if (!$row->check()) {
			//throw new Exception($this->_db->getError());
			$this->setError($row->getError());
			return false;
		}

		/*
		PluginHelper::importPlugin($this->events_map['save']);
		$result = $app->triggerEvent($this->event_before_save, array($this->option.'.'.$this->name, $row, $isNew, $data));
		if (\in_array(false, $result, true)) {
			$this->setError($row->getError());
			return false;
		}

		// Store the data.
		/*if (!$table->store()) {
			throw new Exception($table->getError(), 500);
			return false;
		}*/

		// Trigger the before save event.
		if ($frontend_run_events == 1) {
			PluginHelper::importPlugin('content');
			$context    = $this->option . '.' . 'file';// com_phocadownload.file
			$table      = $row;
			$dispatcher = $this->getDispatcher();

			// Before Save
			$beforeSaveEvent = new Joomla\CMS\Event\Model\BeforeSaveEvent($this->event_before_save, [
				'context' => $context,
				'subject' => $table,
				'isNew' => $isNew,
				'data' => $data,
			]);
			$result          = $dispatcher->dispatch($this->event_before_save, $beforeSaveEvent)->getArgument('result', []);
			//$result = $app->triggerEvent($this->event_before_save, array($context, $row, $isNew, $data));


			/*if (\in_array(false, $result, true)) {
                $this->setError($table->getError());
                return false;
            }*/
		}

		// Store the Phoca gallery table to the database
		if (!$row->store()) {
			//throw new Exception($this->_db->getError());
			$this->setError($row->getError());
			return false;
		}

		if ($frontend_run_events == 1) {
			// After Save
			$afterSaveEvent = new Joomla\CMS\Event\Model\AfterSaveEvent($this->event_after_save, [
				'context' => $context,
				'subject' => $table,
				'isNew' => $isNew,
				'data' => $data,
			]);
			$result         = $dispatcher->dispatch($this->event_after_save, $afterSaveEvent)->getArgument('result', []);
		}
		PhocaDownloadLog::log($row->id, 2);

		return true;
	}
	/*
	function getSection($catid) {

		$query = 'SELECT c.section'
			. ' FROM #__phocadownload_categories AS c'
			. ' WHERE c.id = '.(int)$catid;

		$this->_db->setQuery( $query );
		$sectionId = $this->_db->loadObject();

		if (isset($sectionId->section)) {
			return $sectionId->section;
		}
		return false;
	}*/

	function isOwnerCategoryFile($userId, $fileId) {

		$query = 'SELECT cc.id'
			. ' FROM #__phocadownload_categories AS cc'
			. ' LEFT JOIN #__phocadownload AS a ON a.catid = cc.id'
			. ' WHERE cc.owner_id = '.(int)$userId
			. ' AND a.id = '.(int)$fileId;

		$this->_db->setQuery( $query );
		$ownerCategoryId = $this->_db->loadObject();
		if (isset($ownerCategoryId->id)) {
			return $ownerCategoryId->id;
		}
		return false;
	}

	function publish($id = 0, $publish = 1) {

		//$user 	= JFactory::getUser();
		$query = 'UPDATE #__phocadownload AS a'
			//. ' LEFT JOIN #__phocadownload_categories AS cc ON cc.id = a.catid '
			. ' SET a.published = '.(int) $publish
			. ' WHERE a.id = '.(int)$id;
			//. ' AND cc.owner_id = '.(int) $user->get('id');

		$this->_db->setQuery( $query );
		if (!$this->_db->execute()) {

			throw new Exception('Database Error Publishing', 500);
			return false;
		}
		return true;
	}

	function delete($id = 0) {

		$paramsC 		= ComponentHelper::getParams('com_phocadownload');
		$deleteExistingFiles 	= $paramsC->get( 'delete_existing_files', 0 );

		// - - - - - - - - - - - - -
		// Get all filenames we want to delete from database, we delete all thumbnails from server of this file
		$queryd = 'SELECT filename as filename FROM #__phocadownload WHERE id = '.(int)$id;
		$this->_db->setQuery($queryd);
		$fileObject = $this->_db->loadObjectList();
		// - - - - - - - - - - - - -

		$query = 'DELETE FROM #__phocadownload'
			. ' WHERE id ='.(int)$id;

		$this->_db->setQuery( $query );
		if(!$this->_db->execute()) {
			throw new Exception('Database Error - Delete Files', 500);
			return false;
		}

		 //Delete record from statistics table
		$query = 'DELETE FROM #__phocadownload_user_stat WHERE fileid='.(int)$id;
		$this->_db->setQuery( $query );
		if(!$this->_db->execute()) {
			throw new Exception('Database Error - Delete User Stats (Files)', 500);
			return false;
		}

		// Delete tags
		$query = 'DELETE FROM #__phocadownload_tags_ref'
			. ' WHERE fileid ='.(int)$id;

		$this->_db->setQuery( $query );
		if(!$this->_db->execute()) {

			throw new Exception('Database Error - Delete Tags (Files)', 500);
			return false;
		}

		// - - - - - - - - - - - - - -
		// DELETE FILES ON SERVER
		if ($deleteExistingFiles == 1) {
			$path	= PhocaDownloadPath::getPathSet();
			foreach ($fileObject as $key => $value) {
				//The file can be stored in other category - don't delete it from server because other category use it
				$querys = "SELECT id as id FROM #__phocadownload WHERE filename='".$value->filename."' ";
				$this->_db->setQuery($querys);
				$sameFileObject = $this->_db->loadObject();
				// same file in other category doesn't exist - we can delete it
				if (!$sameFileObject) {
					File::delete(Path::clean($path['orig_abs_ds'].$value->filename));
				}
			}
		}

		return true;
	}
}
?>
