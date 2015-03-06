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
	/**
	 * @param	int	The route of the content item
	 */
	public static function getFileRoute($id, $catid = 0, $idAlias = '', $catidAlias = '', $sectionid = 0, $type = 'file')
	{
		$needles = array(
			'file'  => (int) $id,
			'category' => (int) $catid,
			//'section'  => (int) $sectionid,
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
				$link = 'index.php?option=com_phocadownload&view=play&id='. $id.'&tmpl=component';
			break;
			case 'detail':
				$link = 'index.php?option=com_phocadownload&view=file&id='. $id.'&tmpl=component';
			break;
			case 'download':
				$link = 'index.php?option=com_phocadownload&view=category&download='. $id . '&id='. $catid;
			break;
			default:
				$link = 'index.php?option=com_phocadownload&view=file&id='. $id;
			break;
		}

		if($item = self::_findItem($needles)) {
			if (isset($item->id)) {
				$link .= '&Itemid='.$item->id;
			}
		}

		return $link;
	}
	
	public static function getDownloadRoute($id, $catid, $token, $directDownload = 1)
	{
		$needles = array(
			'download' => '',
			'categories' => '',
			'category' => (int) $catid,
			'file'  => (int) $id			
		);
		if ($directDownload == 1) {
			$link = 'index.php?option=com_phocadownload&view=download&id='. $token.'&download=1&' . JSession::getFormToken() . '=1';
		} else {
			$link = 'index.php?option=com_phocadownload&view=download&id='. $token;
		}

		if($item = self::_findItem($needles)) {
			if (isset($item->id)) {
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
		
	/*	
		if ($idAlias != '') {
			$id = $id . ':' . $idAlias;
		}
		if ($catidAlias != '') {
			$catid = $catid . ':' . $catidAlias;
		}*/
		
		//Create the link
		$link = 'index.php?option=com_phocadownload&view=feed&id='.$id.'&format=feed&type='.$type;

		if($item = self::_findItem($needles, 1)) {
			if (isset($item->id)) {
				$link .= '&Itemid='.$item->id;
			}
		}
		return $link;
	}
	
	
	
	public static function getCategoryRoute($catid, $catidAlias = '')
	{
		$needles = array(
			'category' => (int) $catid,
			//'section'  => (int) $sectionid,
			'categories' => ''
		);
		
		if ($catidAlias != '') {
			$catid = $catid . ':' . $catidAlias;
		}

		//Create the link
		$link = 'index.php?option=com_phocadownload&view=category&id='.$catid;

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
		
		$db = JFactory::getDBO();
				
		$query = 'SELECT a.id, a.title, a.link_ext, a.link_cat'
		.' FROM #__phocadownload_tags AS a'
		.' WHERE a.id = '.(int)$tagId;

		$db->setQuery($query, 0, 1);
		$tag = $db->loadObject();
		
		if (!$db->query()) {
			$this->setError($db->getErrorMsg());
			return false;
		}

		//Create the link
		if (isset($tag->id)) {
			$link = 'index.php?option=com_phocadownload&view=category&id=tag&tagid='.(int)$tag->id;
		} else {
			$link = 'index.php?option=com_phocadownload&view=category&id=tag&tagid=0';
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
	
	public static function getCategoriesRoute()
	{
		$needles = array(
			'categories' => ''
		);
		
		//Create the link
		$link = 'index.php?option=com_phocadownload&view=categories';

		if($item = self::_findItem($needles)) {
			if(isset($item->query['layout'])) {
				$link .= '&layout='.$item->query['layout'];
			}
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

	protected static function _findItem($needles, $notCheckId = 0)
	{
		
		$app	= JFactory::getApplication();
		$menus	= $app->getMenu('site', array());
		$items	= $menus->getItems('component', 'com_phocadownload');

		if(!$items) {
			return $app->input->get('Itemid', 0, '', 'int');
			//return null;
		}
		
		$match = null;
		

		foreach($needles as $needle => $id)
		{
			
			if ($notCheckId == 0) {
				foreach($items as $item) {
					if ((@$item->query['view'] == $needle) && (@$item->query['id'] == $id)) {
						$match = $item;
						break;
					}
				}
			} else {
				foreach($items as $item) {
					if (@$item->query['view'] == $needle) {
						$match = $item;
						break;
					}
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
