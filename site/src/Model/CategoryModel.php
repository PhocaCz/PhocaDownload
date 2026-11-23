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
use Joomla\CMS\Plugin\PluginHelper;
use Phoca\Component\Phocadownload\Administrator\Ordering\Ordering;
use Phoca\Component\Phocadownload\Administrator\Pagination\PhocaPagination;
use Joomla\Event\Event;

//jimport('joomla.application.component.model');

class CategoryModel extends BaseDatabaseModel
{
	public $_document 			= null;
	public $_category 			= null;
	public $_subcategories 	= null;
	public $_filename			= null;
	public $_directlink		= 0;
	public $_file_ordering		= null;
	public $_category_ordering	= null;
	public $file_ordering_select		= null;
	public $category_ordering_select	= null;
	public $_pagination		= null;
	public $_total				= null;
	public $_context 			= 'com_phocadownload.category';

	public function __construct() {

		$app	= Factory::getApplication();

		/** @var CMSWebApplicationInterface $app */
		parent::__construct();

		//$config = Factory::getConfig();

		//$paramsC 			= JComponentHelper::getParams('com_phocadownload') ;
		$paramsC = \Joomla\CMS\Component\ComponentHelper::getParams('com_phocadownload');		$defaultPagination	= (int)$paramsC->get( 'default_pagination', '20' );
		$file_ordering		= $paramsC->get( 'file_ordering', 1 );

		$context			= $this->_context.'.';

		// Get the pagination request variables
		$this->setState('limit', $app->getUserStateFromRequest($context.'limit', 'limit', $defaultPagination, 'int'));
		$this->setState('limitstart', $app->getInput()->get('limitstart', 0, 'int'));

		// In case limit has been changed, adjust limitstart accordingly
		$this->setState('limitstart', ($this->getState('limit') != 0 ? (floor($this->getState('limitstart') / $this->getState('limit')) * $this->getState('limit')) : 0));

		$this->setState('filter.language',$app->getLanguageFilter());

		$this->setState('fileordering', $app->getUserStateFromRequest($context .'fileordering', 'fileordering', $file_ordering, 'int'));

		// Get the filter request variables
		$this->setState('filter_order', Factory::getApplication()->getInput()->getCmd('filter_order', 'ordering'));
		$this->setState('filter_order_dir', Factory::getApplication()->getInput()->getCmd('filter_order_Dir', 'ASC'));

	}

	public function getPagination($categoryId, $tagId) {
		if (empty($this->_pagination)) {
			//jimport('joomla.html.pagination');
			$this->_pagination = new PhocaPagination( $this->getTotal($categoryId, $tagId), $this->getState('limitstart'), $this->getState('limit') );
		}
		return $this->_pagination;
	}

	public function getTotal($categoryId, $tagId) {
		if (empty($this->_total)) {
			$query = $this->_getFileListQuery($categoryId, $tagId, 1);
			$this->_total = $this->_getListCount($query);
		}
		return $this->_total;
	}

	public function getFileList($categoryId, $tagId) {
		if (empty($this->_document)) {
			$query			= $this->_getFileListQuery( $categoryId, $tagId);
			$this->_document= $this->_getList( $query ,$this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_document;
	}

	public function getCategory($categoryId) {
		if (empty($this->_category)) {
			$query					= $this->_getCategoriesQuery( $categoryId, FALSE );
			$this->_category 		= $this->_getList( $query, 0, 1 );
		}
		return $this->_category;
	}

	public function getSubcategories($categoryId) {
		if (empty($this->_subcategories)) {
			$query					= $this->_getCategoriesQuery( $categoryId, TRUE );
			$this->_subcategories 	= $this->_getList( $query );
		}
		return $this->_subcategories;
	}

	public function _getFileListQuery( $categoryId, $tagId = 0, $count = 0 ) {

		$wheres		= [];
		$app		= Factory::getApplication();
		$params 	= \Joomla\CMS\Component\ComponentHelper::getParams('com_phocadownload');		$user 		= $app->getIdentity();
		$userLevels	= implode (',', $user->getAuthorisedViewLevels());


		$pQ			= $params->get( 'enable_plugin_query', 0 );

		if ((int)$tagId > 0) {
			$wheres[]	= ' t.tagid= '.(int)$tagId;
		} else {
			$wheres[]	= ' c.catid= '.(int)$categoryId;
		}

		$wheres[] = '( (unaccessible_file = 1 ) OR (unaccessible_file = 0 AND c.access IN ('.$userLevels.') ) )';
		$wheres[] = '( (unaccessible_file = 1 ) OR (unaccessible_file = 0 AND cc.access IN ('.$userLevels.') ) )';

		$wheres[] = ' c.published = 1';
		$wheres[] = ' c.approved = 1';
		$wheres[] = ' cc.published IN (1,2)';

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
			//$results = Factory::getApplication()->triggerEvent('onGetFileList', array (&$wheres, &$joins,$categoryId , $params));
			
			$event = new Event('onGetFileList', [
				'wheres' => $wheres,
				'joins'  => $joins,
				'categoryId' => $categoryId,
				'params' => $params,
			]);
			$dispatchedEvent = $app->getDispatcher()->dispatch('onGetFileList', $event);
			$results = $dispatchedEvent->getArgument('result') ?? [];
			$wheres = $dispatchedEvent->getArgument('wheres', $wheres);
			$joins  = $dispatchedEvent->getArgument('joins', $joins);
			// END GWE MOD
		}


		$fileOrdering = $this->_getFileOrdering();


		if ($count == 1) {
			$query = ' SELECT c.id'
					.' FROM #__phocadownload AS c'
					.' LEFT JOIN #__phocadownload_categories AS cc ON cc.id = c.catid';
			if ((int)$tagId > 0) {
				$query .= ' LEFT JOIN #__phocadownload_tags_ref AS t ON t.fileid = c.id';
			}
			$query .= ($pQ == 1 ? ((count($joins)>0?( " LEFT JOIN " .implode( " LEFT JOIN ", $joins )):"")):"") // GWE MOD
					. ' WHERE ' . implode( ' AND ', $wheres )
					//. ' ORDER BY '.$fileOrdering;
					. ' ORDER BY c.id';

		} else {

			$query = ' SELECT c.*, cc.id AS categoryid, cc.title AS categorytitle, cc.alias AS categoryalias, cc.published AS categorypublished, cc.access as cataccess, cc.accessuserid as cataccessuserid '
					.' FROM #__phocadownload AS c'
					.' LEFT JOIN #__phocadownload_categories AS cc ON cc.id = c.catid';
			if ((int)$tagId > 0) {
				$query .= ' LEFT JOIN #__phocadownload_tags_ref AS t ON t.fileid = c.id';
			}

			$query .= ' LEFT JOIN #__phocadownload_file_votes_statistics AS r ON r.fileid = c.id';

			$query .= ($pQ == 1 ? ((count($joins)>0?( " LEFT JOIN " .implode( " LEFT JOIN ", $joins )):"")):"") // GWE MOD
					. ' WHERE ' . implode( ' AND ', $wheres )
					. ' ORDER BY '.$fileOrdering;


		}

		return $query;
	}



	function _getCategoriesQuery( $categoryId, $subcategories = FALSE ) {

		$wheres		= [];
		$app		= Factory::getApplication();
		$params 	= \Joomla\CMS\Component\ComponentHelper::getParams('com_phocadownload');		$user 		= $app->getIdentity();
		$userLevels	= implode (',', $user->getAuthorisedViewLevels());

		$pQ			= $params->get( 'enable_plugin_query', 0 );


		// Get the current category or get parent categories of the current category
		if ($subcategories) {
			$wheres[]			= " cc.parent_id = ".(int)$categoryId;
			$categoryOrdering 	= $this->_getCategoryOrdering();
		} else {
			$wheres[]	= " cc.id= ".(int)$categoryId;
		}

		$wheres[] = " cc.access IN (".$userLevels.")";
		$wheres[] = " cc.published IN (1,2)";

		if ($this->getState('filter.language')) {
			$wheres[] =  ' cc.language IN ('.$this->_db->Quote(Factory::getLanguage()->getTag()).','.$this->_db->Quote('*').')';
		}


		if ($pQ == 1) {
			// GWE MOD - to allow for access restrictions
			PluginHelper::importPlugin("phoca");
			//$dispatcher = JEventDispatcher::getInstance();
			$joins = [];
			//$results = Factory::getApplication()->triggerEvent('onGetCategory', array (&$wheres, &$joins,$categoryId , $params));
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
			$categoryId = $dispatchedEvent->getArgument('categoryId', $categoryId);
			// END GWE MOD
		}

		if ($subcategories) {
			$query = " SELECT  cc.id, cc.title, cc.alias, cc.published, cc.access as cataccess, cc.accessuserid as cataccessuserid, COUNT(c.id) AS numdoc"
				. " FROM #__phocadownload_categories AS cc"
				. " LEFT JOIN #__phocadownload AS c ON c.catid = cc.id AND c.published = 1 AND c.textonly = 0"
				. ($pQ == 1 ? ((count($joins)>0?( " LEFT JOIN " .implode( " LEFT JOIN ", $joins )):"")):"") // GWE MOD
				. " WHERE " . implode( " AND ", $wheres )
				. " GROUP BY cc.id, cc.title, cc.alias, cc.published, cc.access, cc.accessuserid"
				. " ORDER BY ".$categoryOrdering;
		} else {
			$query = " SELECT cc.id, cc.title, cc.alias, cc.published, cc.access as cataccess, cc.accessuserid as cataccessuserid, cc.description, cc.archived_description, cc.metakey, cc.metadesc, pc.title as parenttitle, cc.parent_id as parent_id, pc.alias as parentalias"
				. " FROM #__phocadownload_categories AS cc"
				. " LEFT JOIN #__phocadownload_categories AS pc ON pc.id = cc.parent_id"
				. ($pQ == 1 ? ((count($joins)>0?( " LEFT JOIN " .implode( " LEFT JOIN ", $joins )):"")):"") // GWE MOD
				. " WHERE " . implode( " AND ", $wheres )
				. " ORDER BY cc.ordering";
		}

		return $query;
	}


	function _getFileOrdering() {
		if (empty($this->_file_ordering)) {
			$ordering 					= $this->getState('fileordering');
			$this->_file_ordering 		= Ordering::getOrderingText($ordering);

		}

		return $this->_file_ordering;
	}

	public function getFileOrderingSelect() {
		if(empty($this->file_ordering_select)) {

			$this->file_ordering_select = Ordering::renderOrderingFront($this->getState('fileordering'), 1);
		}

		return $this->file_ordering_select;
	}

	function _getCategoryOrdering() {
		if (empty($this->_category_ordering)) {


			$app						= Factory::getApplication();
			$params						= \Joomla\CMS\Component\ComponentHelper::getParams('com_phocadownload');			$ordering					= $params->get( 'category_ordering', 1 );
			$this->_category_ordering 	= Ordering::getOrderingText($ordering, 2);

		}
		return $this->_category_ordering;
	}


}
?>
