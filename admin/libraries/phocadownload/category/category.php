<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
jimport('joomla.application.component.model');

class PhocaDownloadCategory
{

	private static $categoryA = array();
	private static $categoryF = array();
	private static $categoryP = array();
	private static $categoryI = array();

	public static function CategoryTreeOption($data, $tree, $id=0, $text='', $currentId = 0) {

		foreach ($data as $key) {
			$show_text =  $text . $key->text;

			if ($key->parent_id == $id && $currentId != $id && $currentId != $key->value) {
				$tree[$key->value] 			= new CMSObject();
				$tree[$key->value]->text 	= $show_text;
				$tree[$key->value]->value 	= $key->value;
				$tree = PhocaDownloadCategory::CategoryTreeOption($data, $tree, $key->value, $show_text . " - ", $currentId );
			}
		}
		return($tree);
	}

	public static function filterCategory($query, $active = NULL, $frontend = NULL, $onChange = TRUE, $fullTree = NULL ) {

		$db	= Factory::getDBO();

		$form = 'adminForm';
		if ($frontend == 1) {
			$form = 'phocadownloadfilesform';
		}

		if ($onChange) {
			$onChO = 'class="form-select" size="1" onchange="document.'.$form.'.submit( );"';
		} else {
			$onChO = 'class="form-select" size="1"';
		}

		$categories[] = HTMLHelper::_('select.option', '0', '- '.Text::_('COM_PHOCADOWNLOAD_SELECT_CATEGORY').' -');
		$db->setQuery($query);
		$catData = $db->loadObjectList();



		if ($fullTree) {

			// Start - remove in case there is a memory problem
			$tree = array();
			$text = '';

			$queryAll = ' SELECT cc.id AS value, cc.title AS text, cc.parent_id as parent_id'
					.' FROM #__phocadownload_categories AS cc'
					.' ORDER BY cc.ordering';
			$db->setQuery($queryAll);
			$catDataAll 		= $db->loadObjectList();

			$catDataTree	= PhocaDownloadCategory::CategoryTreeOption($catDataAll, $tree, 0, $text, -1);

			$catDataTreeRights = array();
			/*foreach ($catData as $k => $v) {
				foreach ($catDataTree as $k2 => $v2) {
					if ($v->value == $v2->value) {
						$catDataTreeRights[$k]->text 	= $v2->text;
						$catDataTreeRights[$k]->value = $v2->value;
					}
				}
			}*/

			foreach ($catDataTree as $k => $v) {
                foreach ($catData as $k2 => $v2) {
                   if ($v->value == $v2->value) {
						$catDataTreeRights[$k] = new StdClass();
						$catDataTreeRights[$k]->text  = $v->text;
						$catDataTreeRights[$k]->value = $v->value;
                   }
                }
             }



			$catDataTree = array();
			$catDataTree = $catDataTreeRights;
			// End - remove in case there is a memory problem

			// Uncomment in case there is a memory problem
			//$catDataTree	= $catData;
		} else {
			$catDataTree	= $catData;
		}

		$categories = array_merge($categories, $catDataTree );

		$category = HTMLHelper::_('select.genericlist',  $categories, 'catid', $onChO, 'value', 'text', $active);

		return $category;
	}

	public static function options($type = 0)
	{
		if ($type == 1) {
			$tree[0] 			= new CMSObject();
			$tree[0]->text 		= Text::_('COM_PHOCADOWNLOAD_MAIN_CSS');
			$tree[0]->value 	= 1;
			$tree[1] 			= new CMSObject();
			$tree[1]->text 		= Text::_('COM_PHOCADOWNLOAD_CUSTOM_CSS');
			$tree[1]->value 	= 2;
			return $tree;
		}

		$db = Factory::getDBO();

       //build the list of categories
		$query = 'SELECT a.title AS text, a.id AS value, a.parent_id as parent_id'
		. ' FROM #__phocadownload_categories AS a'
		. ' WHERE a.published = 1'
		. ' ORDER BY a.ordering';
		$db->setQuery( $query );
		$items = $db->loadObjectList();

		$catId	= -1;

		$javascript 	= 'class="form-control" size="1" onchange="submitform( );"';

		$tree = array();
		$text = '';
		$tree = PhocaDownloadCategory::CategoryTreeOption($items, $tree, 0, $text, $catId);

		return $tree;

	}

	public static function getCategoryByFile($id = 0) {
		$db	= Factory::getDBO();
		$query = 'SELECT c.id, c.title, c.alias'
		. ' FROM #__phocadownload_categories AS c'
		. ' LEFT JOIN #__phocadownload AS a ON a.catid = c.id'
		//. ' WHERE c.published = 1'
		. ' WHERE a.id ='.(int)$id
		. ' ORDER BY c.id'
		. ' LIMIT 1';
		$db->setQuery( $query );
		$item = $db->loadObject();
		return $item;

	}

	public static function getCategoryById($id) {

		$id = (int)$id;
		if( empty(self::$categoryI[$id])) {

			$db = Factory::getDBO();
			$query = 'SELECT a.title, a.alias, a.id, a.parent_id'
			. ' FROM #__phocadownload_categories AS a'
			. ' WHERE a.id = '.(int)$id
			. ' ORDER BY a.ordering'
			. ' LIMIT 1';
			$db->setQuery( $query );

			$category = $db->loadObject();
			if (!empty($category) && isset($category->id) && (int)$category->id > 0) {

				$query = 'SELECT a.title, a.alias, a.id, a.parent_id'
				. ' FROM #__phocadownload_categories AS a'
				. ' WHERE a.parent_id = '.(int)$id
				. ' ORDER BY a.ordering';
				//. ' LIMIT 1'; We need all subcategories
				$db->setQuery( $query );
				$subcategories = $db->loadObjectList();
				if (!empty($subcategories)) {
					$category->subcategories = $subcategories;
				}
			}

			self::$categoryI[$id] = $category;
		}
		return self::$categoryI[$id];
	}

	public static function getPath($path = array(), $id = 0, $parent_id = 0, $title = '', $alias = '') {

		if( empty(self::$categoryA[$id])) {
			self::$categoryP[$id]	= self::getPathTree($path, $id, $parent_id, $title, $alias);
		}
		return self::$categoryP[$id];
	}

	public static function getPathTree($path = array(), $id = 0, $parent_id = 0, $title = '', $alias = '') {

		static $iCT = 0;

		if ((int)$id > 0) {
			//$path[$iCT]['id'] = (int)$id;
			//$path[$iCT]['catid'] = (int)$parent_id;
			//$path[$iCT]['title'] = $title;
			//$path[$iCT]['alias'] = $alias;

			$path[$id] = (int)$id. ':'. $alias;
		}

		if ((int)$parent_id > 0) {
			$db = Factory::getDBO();
			$query = 'SELECT a.title, a.alias, a.id, a.parent_id'
			. ' FROM #__phocadownload_categories AS a'
			. ' WHERE a.id = '.(int)$parent_id
			. ' ORDER BY a.ordering';
			$db->setQuery( $query );
			$category = $db->loadObject();

			if (isset($category->id)) {
				$id 	= (int)$category->id;
				$title 	= '';
				if (isset($category->title)) {
					$title = $category->title;
				}

				$alias 	= '';
				if (isset($category->alias)) {
					$alias = $category->alias;
				}

				$parent_id = 0;
				if (isset($category->parent_id)) {
					$parent_id = (int)$category->parent_id;
				}
				$iCT++;

				$path = self::getPathTree($path, (int)$id, (int)$parent_id, $title, $alias);
			}
		}
		return $path;
	}
}
?>
