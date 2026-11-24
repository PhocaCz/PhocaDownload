<?php
/*
 * @package Joomla
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
namespace Phoca\Component\Phocadownload\Site\Model;
defined('_JEXEC') or die();
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Event\Event;

class PlayModel extends BaseDatabaseModel
{
	var $_file 				= null;
	var $_category 			= null;
	var $_section			= null;
	var $_filename			= null;

	public function __construct() {
		
		/** @var CMSWebApplicationInterface $app */
		$app	= Factory::getApplication();
		parent::__construct();
		$this->setState('filter.language',$app->getLanguageFilter());
		
	}

	function getFile( $fileId) {		
		if (empty($this->_file)) {	
			$query			= $this->_getFileQuery( $fileId );
			$this->_file	= $this->_getList( $query, 0 , 1 );
			
		/*	// Don't display file if user has no access
			// - - - - - - - - - - - - - - - 
			if (empty($this->_file)) {
				return null;
			} 
			
			if (isset($this->_file[0]->access)) {
				if ($aid !== null) {
					if ($this->_file[0]->access > (int) $aid) {
							$app->redirect(Route::_('index.php?option=com_users&view=login', false), Text::_("Please login to download the file"));
							exit;
					}
				} else {
					return null;
				}
			} else {
				return null;
			}
			// - - - - - - - - - - - - - - - -*/
		}
		return $this->_file;
	}
	
	protected function _getFileQuery( $fileId ) {
		
		/** @var CMSWebApplicationInterface $app */
		$app			= Factory::getApplication();
		$params 		= \Joomla\CMS\Component\ComponentHelper::getParams('com_phocadownload');		
		$categoryId		= 0;
		$category		= $this->getCategory($fileId);
		if (isset($category[0]->id)) {
			$categoryId = $category[0]->id;
		}
		
		$pQ			= $params->get( 'enable_plugin_query', 0 );
		
		$wheres[]	= " c.catid= ".(int) $categoryId;
		$wheres[]	= " c.catid= cc.id";
		/*if ($aid !== null) {
		
			// Should be not displayed, only in case user will add direct url
			// IF unaccessible file = 1 then display unaccessible file for all
			// IF unaccessible file = 0 then display it only for them who have access to this file
			$wheres[] = '( (unaccessible_file = 1 ) OR (unaccessible_file = 0 AND c.access <= ' . (int) $aid.') )';
			$wheres[] = '( (unaccessible_file = 1 ) OR (unaccessible_file = 0 AND cc.access <= ' . (int) $aid.') )';
			//$wheres[] = "c.access <= " . (int) $aid;
			//$wheres[] = "cc.access <= " . (int) $aid;
		}*/
		$wheres[] = " c.published = 1";
		$wheres[] = " c.approved = 1";
		$wheres[] 	= " cc.published IN (1,2)";
		
		$wheres[] = " c.id = " . (int) $fileId;
		
		if ($this->getState('filter.language')) {
			$wheres[] =  ' c.language IN ('.$this->getDatabase()->Quote(Factory::getApplication()->getLanguage()->getTag()).','.$this->getDatabase()->Quote('*').')';
			$wheres[] =  ' cc.language IN ('.$this->getDatabase()->Quote(Factory::getApplication()->getLanguage()->getTag()).','.$this->getDatabase()->Quote('*').')';
		}
		
		// Active
		$jnow		= Factory::getDate();
		$now		= $jnow->toSql();
		$nullDate	= $this->getDatabase()->getNullDate();
		$wheres[] = ' ( c.publish_up = '.$this->getDatabase()->Quote($nullDate).' OR c.publish_up <= '.$this->getDatabase()->Quote($now).' )';
		$wheres[] = ' ( c.publish_down = '.$this->getDatabase()->Quote($nullDate).' OR c.publish_down >= '.$this->getDatabase()->Quote($now).' )';
		

		
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
		
		$query = ' SELECT c.*, cc.id AS categoryid, cc.title AS categorytitle, cc.alias AS categoryalias, cc.access as cataccess, cc.accessuserid as cataccessuserid, lc.title AS licensetitle, lc.description AS licensetext'
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
	
	protected function _getCategoryQuery( $fileId ) {
		
		$wheres		= [];
		$app		= Factory::getApplication();
		$params 	= \Joomla\CMS\Component\ComponentHelper::getParams('com_phocadownload');		
		$user 		= $app->getIdentity();
		$userLevels	= implode (',', $user->getAuthorisedViewLevels());
		
		$pQ			= $params->get( 'enable_plugin_query', 0 );
		
		$wheres[]	= " c.id= ".(int)$fileId;
		$wheres[] 	= " cc.access IN (".$userLevels.")";
		$wheres[] 	= " cc.published IN (1,2)";
		$categoryId	= 0;
		
		if ($this->getState('filter.language')) {
			$wheres[] =  ' c.language IN ('.$this->getDatabase()->Quote(Factory::getApplication()->getLanguage()->getTag()).','.$this->getDatabase()->Quote('*').')';
			$wheres[] =  ' cc.language IN ('.$this->getDatabase()->Quote(Factory::getApplication()->getLanguage()->getTag()).','.$this->getDatabase()->Quote('*').')';
		}

		if ($pQ == 1) {
			// GWE MOD - to allow for access restrictions
			PluginHelper::importPlugin("phoca");
			//$dispatcher = JEventDispatcher::getInstance();
			$joins = [];
			//$results = Factory::getApplication()->triggerEvent('onGetCategory',  array (&$wheres, &$joins,$categoryId,  $params));		
			$event = new Event('onGetCategory', [
				'wheres' => $wheres,
				'joins'  => $joins,
				'categoryId' => $categoryId,
				'params' => $params,
			]);
			$dispatchedEvent = $app->getDispatcher()->dispatch('onGetCategory', $event);
			$results = $dispatchedEvent->getArgument('result') ?? [];
			$wheres = $dispatchedEvent->getArgument('wheres', $wheres);
			$joins  = $dispatchedEvent->getArgument('joins', $joins);
			$categoryId  = $dispatchedEvent->getArgument('categoryId', $categoryId);
			// END GWE MOD
		}
		
		$query = " SELECT cc.id, cc.title, cc.alias, cc.image, cc.image_position, cc.description"
				. " FROM #__phocadownload_categories AS cc"
				. " LEFT JOIN #__phocadownload AS c ON c.catid = cc.id"
				. ($pQ == 1 ? ((count($joins)>0?( " LEFT JOIN " .implode( " LEFT JOIN ", $joins )):"")):"") // GWE MOD
				. " WHERE " . implode( " AND ", $wheres )
				. " ORDER BY cc.ordering";
		return $query;
	}
	
}
?>
