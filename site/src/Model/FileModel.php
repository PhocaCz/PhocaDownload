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

namespace Phoca\Component\Phocadownload\Site\Model;

defined('_JEXEC') or die();
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Event\Event;
jimport('joomla.application.component.model');


class FileModel extends BaseDatabaseModel
{
	public $_file 				= null;
	public $_category 			= null;
	public $_section			= null;
	public $_filename			= null;
	public $_directlink			= 0;

	public function __construct() {

		$app	= Factory::getApplication();

		parent::__construct();

		$this->setState('filter.language',$app->getLanguageFilter());
	}

	public function getFile( $fileId, $limitstartUrl) {
		if (empty($this->_file)) {
			$query			= $this->_getFileQuery( $fileId );
			$this->_file	= $this->_getList( $query, 0 , 1 );

			// Don't display file if user has no access
			// - - - - - - - - - - - - - - -
			if (empty($this->_file)) {
				return null;
			}

			if (isset($this->_file[0]->access)) {
				$app		= Factory::getApplication();
				$user 		= $app->getIdentity();


				if (!in_array($this->_file[0]->access, $user->getAuthorisedViewLevels())) {
					//$app->redirect(JRoute::_('index.php?option=com_user&view=login', false), JText::_("Please login to download the file"));
					// Return URL
					$return	= 'index.php?option=com_phocadownload&view=file&catid='.$this->_file[0]->catid.':'.$this->_file[0]->categoryalias
							. '&id='.$this->_file[0]->id.':'.$this->_file[0]->alias. $limitstartUrl . '&Itemid='. $app->getInput()->get('Itemid', 0, 'int');
					$returnUrl  	= 'index.php?option=com_users&view=login&return='.base64_encode($return);
					$app->enqueueMessage(Text::_("COM_PHOCADOWNLOAD_PLEASE_LOGIN_DOWNLOAD_FILE"), 'error');
					$app->redirect(Route::_($returnUrl, false));
					return;
				}
			} else {
				return null;
			}
			// - - - - - - - - - - - - - - - -
		}
		return $this->_file;
	}

	public function _getFileQuery( $fileId ) {

		$app		= Factory::getApplication();
		$params 	= \Joomla\CMS\Component\ComponentHelper::getParams('com_phocadownload');		
		$user 		= $app->getIdentity();
		$userLevels	= implode (',', $user->getAuthorisedViewLevels());

		$pQ			= $params->get( 'enable_plugin_query', 0 );

		$categoryId	= 0;
		$category	= $this->getCategory($fileId);
		if (isset($category[0]->id)) {
			$categoryId = $category[0]->id;
		}

		$wheres[]	= " c.catid= ".(int) $categoryId;
		$wheres[]	= " c.catid= cc.id";
		$wheres[] = '( (unaccessible_file = 1 ) OR (unaccessible_file = 0 AND c.access IN ('.$userLevels.') ) )';
		$wheres[] = '( (unaccessible_file = 1 ) OR (unaccessible_file = 0 AND cc.access IN ('.$userLevels.') ) )';
		$wheres[] = " c.published = 1";
		$wheres[] = " c.approved = 1";
		$wheres[] = " cc.published IN (1,2)";
		$wheres[] = " c.id = " . (int) $fileId;

		if ($this->getState('filter.language')) {
			$wheres[] =  ' c.language IN ('.$this->_db->Quote(Factory::getLanguage()->getTag()).','.$this->_db->Quote('*').')';
			$wheres[] =  ' cc.language IN ('.$this->_db->Quote(Factory::getLanguage()->getTag()).','.$this->_db->Quote('*').')';
		}

		// Active
		$jnow		= Factory::getDate();
		$now		= $jnow->toSql();
		$nullDate	= $this->_db->getNullDate();
		$wheres[] = ' ( c.publish_up = '.$this->_db->Quote($nullDate).' OR c.publish_up <= '.$this->_db->Quote($now).' )';
		$wheres[] = ' ( c.publish_down = '.$this->_db->Quote($nullDate).' OR c.publish_down >= '.$this->_db->Quote($now).' )';

		if ($pQ == 1) {
			// GWE MOD - to allow for access restrictions
			PluginHelper::importPlugin("phoca");
			//$dispatcher = JEventDispatcher::getInstance();
			$joins = [];
			//$results = Factory::getApplication()->triggerEvent('onGetFile', array (&$wheres, &$joins, $fileId,  $params));
			$event = new Event('onGetFile', [
				'wheres' => $wheres,
				'joins'  => $joins,
				'fileId' => $fileId,
				'params' => $params,
			]);
			$dispatchedEvent = $app->getDispatcher()->dispatch('onGetFile', $event);
			$results = $dispatchedEvent->getArgument('result') ?? [];
			$wheres = $dispatchedEvent->getArgument('wheres', $wheres);
			$joins  = $dispatchedEvent->getArgument('joins', $joins);
			$fileId  = $dispatchedEvent->getArgument('fileId', $fileId);
			// END GWE MOD
		}

		$query = ' SELECT c.*, cc.id AS categoryid, cc.title AS categorytitle, cc.alias AS categoryalias, cc.published AS categorypublished, cc.access as cataccess, cc.accessuserid as cataccessuserid, lc.title AS licensetitle, lc.description AS licensetext, lc.id AS licenseid'
				.' FROM #__phocadownload AS c'
				.' LEFT JOIN #__phocadownload_categories AS cc ON cc.id = c.catid'
				.' LEFT JOIN #__phocadownload_licenses AS lc ON lc.id = c.confirm_license'
				. ($pQ == 1 ? ((count($joins)>0?( " LEFT JOIN " .implode( " LEFT JOIN ", $joins )):"")):"") // GWE MOD
				.' WHERE ' . implode( ' AND ', $wheres )
				.' ORDER BY c.ordering';

		return $query;
	}

	public function getCategory($fileId) {
		if (empty($this->_category)) {
			$query			= $this->_getCategoryQuery( $fileId );
			$this->_category= $this->_getList( $query, 0, 1 );
		}
		return $this->_category;
	}

	public function _getCategoryQuery( $fileId ) {

		$wheres		= [];
		$app		= Factory::getApplication();
		$params 	= \Joomla\CMS\Component\ComponentHelper::getParams('com_phocadownload');		
		$user 		= $app->getIdentity();
		$userLevels	= implode (',', $user->getAuthorisedViewLevels());

		$pQ			= $params->get( 'enable_plugin_query', 0 );


		$wheres[]	= " c.id= ".(int)$fileId;
		$wheres[] = " cc.access IN (".$userLevels.")";
		$wheres[] = " cc.published IN (1,2)";

		if ($this->getState('filter.language')) {
			$wheres[] =  ' c.language IN ('.$this->_db->Quote(Factory::getLanguage()->getTag()).','.$this->_db->Quote('*').')';
			$wheres[] =  ' cc.language IN ('.$this->_db->Quote(Factory::getLanguage()->getTag()).','.$this->_db->Quote('*').')';
		}

		if ($pQ == 1) {
			// GWE MOD - to allow for access restrictions
			PluginHelper::importPlugin("phoca");
			//$dispatcher = JEventDispatcher::getInstance();
			$joins = [];
			//$results = Factory::getApplication()->triggerEvent('onGetCategory', array (&$wheres, &$joins, $fileId,  $params));
			$event = new Event('onGetFile', [
				'wheres' => $wheres,
				'joins'  => $joins,
				'fileId' => $fileId,
				'params' => $params,
			]);
			$dispatchedEvent = $app->getDispatcher()->dispatch('onGetFile', $event);
			$results = $dispatchedEvent->getArgument('result') ?? [];
			$wheres = $dispatchedEvent->getArgument('wheres', $wheres);
			$joins  = $dispatchedEvent->getArgument('joins', $joins);
			$fileId  = $dispatchedEvent->getArgument('fileId', $fileId);
			// END GWE MOD
		}

		$query = " SELECT cc.id, cc.title, cc.alias, cc.published, cc.description, cc.archived_description, cc.access as cataccess, cc.accessuserid as cataccessuserid, cc.parent_id as parent_id"
				. " FROM #__phocadownload_categories AS cc"
				. " LEFT JOIN #__phocadownload AS c ON c.catid = cc.id"
				. ($pQ == 1 ? ((count($joins)>0?( " LEFT JOIN " .implode( " LEFT JOIN ", $joins )):"")):"") // GWE MOD
				. " WHERE " . implode( " AND ", $wheres )
				. " ORDER BY cc.ordering";


		return $query;
	}

}
?>
