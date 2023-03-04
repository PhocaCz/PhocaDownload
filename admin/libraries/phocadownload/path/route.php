<?php
/**
 * @version		$Id: route.php 11190 2008-10-20 00:49:55Z ian $
 * @package		Joomla
 * @subpackage	Content
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;
use Joomla\CMS\Menu\AbstractMenu;
use Joomla\CMS\Session\Session;
// Component Helper
jimport('joomla.application.component.helper');

/**
 * Content Component Route Helper
 *
 * @static
 * @package		Joomla
 * @subpackage	Content
 * @since 1.5
 */
class PhocaDownloadRoute
{

	public static function getCategoriesRoute() {

		$needles = array(
			'categories' => ''
		);

		$link = 'index.php?option=com_phocadownload&view=categories';

		if($item = self::_findItem($needles, 1)) {
			if(isset($item->query['layout'])) {
				$link .= '&layout='.$item->query['layout'];
			}
			if (isset($item->id)) {
				$link .= '&Itemid='.(int)$item->id;;
			}
		}
		return $link;
	}

	public static function getCategoryRoute($catid, $catidAlias = '') {

		$needles = array(
			'category' => (int)$catid,
			'categories' => ''
		);

		if ($catidAlias != '') {
			$catid = $catid . ':' . $catidAlias;
		}

		//Create the link
		$link = 'index.php?option=com_phocadownload&view=category&id='. $catid;

		if($item = self::_findItem($needles)) {
			if(isset($item->query['layout'])) {
				$link .= '&layout='.$item->query['layout'];
			}
			if(isset($item->id)) {
				$link .= '&Itemid='.$item->id;
			}
		};

		return $link;
	}

	public static function getCategoryRouteByTag($tagId)
	{
		$needles = array(
			'category' => '',
			//'section'  => (int) $sectionid,
			'categories' => ''
		);

		$db = Factory::getDBO();

		$query = 'SELECT a.id, a.title, a.link_ext, a.link_cat'
		.' FROM #__phocadownload_tags AS a'
		.' WHERE a.id = '.(int)$tagId;

		$db->setQuery($query, 0, 1);
		$tag = $db->loadObject();

		/*if (!$db->query()) {
			throw new Exception($db->getErrorMsg(), 500);
			return false;
		}*/

		//Create the link
		if (isset($tag->id)) {
			$link = 'index.php?option=com_phocadownload&view=category&id=0:category&tagid='.(int)$tag->id;
		} else {
			$link = 'index.php?option=com_phocadownload&view=category&id=0:category&tagid=0';
		}

		if($item = self::_findItem($needles)) {
			if(isset($item->query['layout'])) {
				$link .= '&layout='.$item->query['layout'];
			}
			if(isset($item->id)) {
				$link .= '&Itemid='.$item->id;
			}
		};

		return $link;
	}


	public static function getFileRoute($id, $catid = 0, $idAlias = '', $catidAlias = '', $sectionid = 0, $type = 'file', $suffix = '')
	{

		$needles = array(
			'file'  => (int) $id,
			'category' => (int) $catid,
			'categories' => ''
		);

		if ($idAlias != '') {
			$id = $id . ':' . $idAlias;
		}
		if ($catidAlias != '') {
			$catid = $catid . ':' . $catidAlias;
		}

		//Create the link
		switch ($type)
		{


			case 'play':
				$link = 'index.php?option=com_phocadownload&view=play&catid='.$catid.'&id='. $id.'&tmpl=component';
			break;
			case 'detail':
				$link = 'index.php?option=com_phocadownload&view=file&catid='.$catid.'&id='. $id.'&tmpl=component';
			break;
			case 'download':
				$link = 'index.php?option=com_phocadownload&view=category&download='. $id . '&id='. $catid;
			break;
			default:
				$link = 'index.php?option=com_phocadownload&view=file&catid='.$catid.'&id='. $id;
			break;

		}

		if ($item = self::_findItem($needles)) {
			if (isset($item->id) && ((int)$item->id > 0)) {
				$link .= '&Itemid='.$item->id;
			}
		}

		if ($suffix != '') {
			$link .= '&'.$suffix;
		}


		return $link;


	}

	public static function getDownloadRoute($id, $catid, $token, $directDownload = 1)
	{
		$needles = array(
			'download' => '',
			'file'  => (int) $id,
			'category' => (int) $catid,
			'categories' => ''
		);

		if ($directDownload == 1) {
			$link = 'index.php?option=com_phocadownload&view=download&id='. $token.'&download=1&' . Session::getFormToken() . '=1';
		} else {
			$link = 'index.php?option=com_phocadownload&view=download&id='. $token;
		}

		if($item = self::_findItem($needles)) {
			if (isset($item->id)) {
				$link .= '&Itemid='.$item->id;
			}
		}

		if ($item = PhocaDownloadRoute::_findItem($needles, 0)) {
			if (isset($item->id) && ((int)$item->id > 0)) {
				$link .= '&Itemid='.$item->id;
			}
		}

		return $link;
	}

	public static function getFeedRoute($id, $catid = 0, $sectionid = 0, $type = 'rss')
	{
		$needles = array(
			'categories' => '',
			//'section'  => (int) $sectionid,
			'category' => (int) $catid,
			'file'  => (int) $id
		);

		//Create the link
		$link = 'index.php?option=com_phocadownload&view=feed&id='.$id.'&format=feed&type='.$type;

		if($item = self::_findItem($needles, 1)) {
			if (isset($item->id)) {
				$link .= '&Itemid='.$item->id;
			}
		}
		return $link;
	}

	public static function getGuestbookRoute($id, $title)
	{
		$needles = array(
			'guestbook' => (int) $id
		);

		$link = 'index.php?option=com_phocaguestbook&view=guestbook&cid='.(int)$id.'&reporttitle='.strip_tags($title).'&tmpl=component';

		if($item = self::_findItem($needles, 1, 'com_phocaguestbook')) {
			if (isset($item->id)) {
				$link .= '&Itemid='.$item->id;
			}
		}
		return $link;
	}

	/*
	function getSectionRoute($sectionid, $sectionidAlias = '')
	{
		$needles = array(
			'section' => (int) $sectionid,
			'sections' => ''
		);

		if ($sectionidAlias != '') {
			$sectionid = $sectionid . ':' . $sectionidAlias;
		}

		//Create the link
		$link = 'index.php?option=com_phocadownload&view=section&id='.$sectionid;

		if($item = self::_findItem($needles)) {
			if(isset($item->query['layout'])) {
				$link .= '&layout='.$item->query['layout'];
			}
			$link .= '&Itemid='.$item->id;
		}

		return $link;
	}

	function getSectionsRoute()
	{
		$needles = array(
			'sections' => ''
		);

		//Create the link
		$link = 'index.php?option=com_phocadownload&view=sections';

		if($item = self::_findItem($needles)) {
			if(isset($item->query['layout'])) {
				$link .= '&layout='.$item->query['layout'];
			}
			if (isset($item->id)) {
				$link .= '&Itemid='.$item->id;
			}
		}

		return $link;
	}*/

	protected static function _findItem($needles, $notCheckId = 0, $component = 'com_phocadownload')
	{

		$app		= Factory::getApplication();
		//$menus		= $app->getMenu('site', array()); // Problems in indexer
		$menus    = AbstractMenu::getInstance('site');
		$items		= $menus->getItems('component', $component);
		//$menu 		= $menus;//$app->getMenu();
		$active 	= $menus->getActive();
		$option		= $app->input->get( 'option', '', 'string' );

		// Don't check ID for specific views. e.g. categories view does not have ID
		$notCheckIdArray =  array('categories');

		if(!$items) {
			$itemId =  $app->input->get('Itemid', 0, 'int');
			if ($itemId > 0) {
				$item = new stdClass();
				$item->id = $itemId;
				return $item;
			}
			return null;
		}

		$match = null;
		// FIRST - test active menu link
		foreach($needles as $needle => $id) {
			if (isset($active->query['option']) && $active->query['option'] == $component
				&& isset($active->query['view']) && $active->query['view'] == $needle
				&& (in_array($needle, $notCheckIdArray) || (isset($active->query['id']) && $active->query['id'] == $id ))
			) {
				$match = $active;
			}
		}

		if(isset($match)) {
			return $match;
		}

		// SECOND - if not find in active, try to run other items
		//          ordered by function which calls this function - e.g. file, category, categories
		//          as last the categories view should be checked, it has no ID so we skip the checking
		//          of ID for categories view with OR: in_array($needle, $notCheckIdArray) ||
		foreach($needles as $needle => $id) {

			foreach($items as $item) {

				if (isset($item->query['option']) && $item->query['option'] == $component
					&& isset($item->query['view']) && $item->query['view'] == $needle
					&& (in_array($needle, $notCheckIdArray) || (isset($item->query['id']) && $item->query['id'] == $id ))
				) {
					$match = $item;
				}
			}

			if(isset($match)) {
				break;
			}
		}

		return $match;
	}
}
?>
