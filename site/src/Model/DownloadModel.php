<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

namespace Phoca\Component\Phocadownload\Site\Model;
defined('_JEXEC') or die();
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Event\Event;


class DownloadModel extends BaseDatabaseModel
{
	public $_file 				= null;
	public $_category 			= null;
	public $_filename			= null;
	public $_directlink		= 0;

	public function __construct() {
		$app	= Factory::getApplication();
		parent::__construct();
		$this->setState('filter.language',$app->getLanguageFilter());
	}

	public function getFile( $downloadToken) {
		if (empty($this->_file)) {			
			$query			= $this->_getFileQuery( $downloadToken);
			$this->_file	= $this->_getList( $query, 0 , 1 );
			
			// Don't display file if user has no access
			// - - - - - - - - - - - - - - - 
			if (empty($this->_file)) {
				return null;
			} 
			// - - - - - - - - - - - - - - - -
		}
		return $this->_file;
	}
	
	public function _getFileQuery( $downloadToken ) {
		
		$app		= Factory::getApplication();
		$params 	= \Joomla\CMS\Component\ComponentHelper::getParams('com_phocadownload');		
		$user 		= $app->getIdentity();
		
		$pQ			= $params->get( 'enable_plugin_query', 0 );
		
		$wheres[] = " c.approved = 1";
		$wheres[] = " c.published = 1";
		$wheres[] = " c.token = " . $this->getDatabase()->Quote($downloadToken);
		
		
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
			//$results = Factory::getApplication()->triggerEvent('onGetFile', array (&$wheres, &$joins, $downloadToken,  $params));	
			$event = new Event('onGetFile', [
				'wheres' => $wheres,
				'joins'  => $joins,
				'downloadToken' => $downloadToken,
				'params' => $params,
			]);
			$dispatchedEvent = $app->getDispatcher()->dispatch('onGetFile', $event);
			$results = $dispatchedEvent->getArgument('result') ?? [];
			$wheres = $dispatchedEvent->getArgument('wheres', $wheres);
			$joins  = $dispatchedEvent->getArgument('joins', $joins);
			$downloadToken  = $dispatchedEvent->getArgument('downloadToken', $downloadToken);
				
			// END GWE MOD
		}
		
		$query = ' SELECT c.*, cc.published AS categorypublished, lc.title AS licensetitle, lc.description AS licensetext, lc.id AS licenseid'
				.' FROM #__phocadownload AS c'
				.' LEFT JOIN #__phocadownload_categories AS cc ON cc.id = c.catid'
				.' LEFT JOIN #__phocadownload_licenses AS lc ON lc.id = c.confirm_license'
				. ($pQ == 1 ? ((count($joins)>0?( " LEFT JOIN " .implode( " LEFT JOIN ", $joins )):"")):"") // GWE MOD
				.' WHERE ' . implode( ' AND ', $wheres )
				.' ORDER BY c.ordering';
				
		return $query;
	}
}
?>
