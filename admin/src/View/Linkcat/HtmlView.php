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
namespace Phoca\Component\Phocadownload\Administrator\View\Linkcat;

defined('_JEXEC') or die();
use Joomla\CMS\MVC\View\HtmlView AS BaseHtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper;
/* jimport( 'joomla.application.component.view' ); */
use Joomla\String\StringHelper;
use Phoca\Component\Phocadownload\Administrator\Category\Category;
use Phoca\Component\Phocadownload\Administrator\Render\Adminviews;
use Phoca\Component\Phocadownload\Administrator\Utils\Utils;

class HtmlView extends BaseHtmlView
{
	public $_context 	= 'com_phocadownload.linkcat';

	protected $t;
	protected $r;

	function display($tpl = null) {
		$app	= Factory::getApplication();
		$db		= Factory::getContainer()->get(\Joomla\Database\DatabaseInterface::class);
		$this->r = new AdminViews();
		$this->t = Utils::setVars('linkcat');
		//Frontend Changes
		$tUri = '';
		if (!$app->isClient('administrator')) {
			$tUri = Uri::base();

		}

		$editor    = $app->getInput()->getCmd('editor', '');
		if (!empty($editor)) {
			$this->document->addScriptOptions('xtd-phocadownload', array('editor' => $editor));
		}

		$document	= Factory::getApplication()->getDocument();
		$uri		= Uri::getInstance();
		//HTMLHelper::stylesheet( 'media/com_phocadownload/css/administrator/phocadownload.css' );
		//HTMLHelper::stylesheet( 'media/plg_editors-xtd_phocadownload/css/phocadownload.css' );
		$wa 				= $app->getDocument()->getWebAssetManager();
		$wa->registerAndUseStyle('com_phocadownload.phocadownload.admin', 'media/com_phocadownload/css/administrator/phocadownload.css', array('version' => 'auto'));
		$wa->registerAndUseStyle('plg_editors-xtd_phocadownload', 'media/plg_editors-xtd_phocadownload/css/phocadownload.css', array('version' => 'auto'));

		$eName				= Factory::getApplication()->getInput()->get('editor');
		$this->t['ename']		= preg_replace( '#[^A-Z0-9\-\_\[\]]#i', '', $eName );
		$this->t['backlink']	= $tUri.'index.php?option=com_phocadownload&amp;view=links&amp;tmpl=component&amp;editor='.$this->t['ename'];

		//$model 			= $this->getModel();

		// build list of categories
		//$javascript 	= 'class="form-control" size="1" onchange="submitform( );"';
		$attr 	= 'class="form-select" size="1"';
		$filter_catid	= '';

		$query = 'SELECT a.title AS text, a.id AS value, a.parent_id as parent_id'
		. ' FROM #__phocadownload_categories AS a'
		. ' WHERE a.published = 1'
		//. ' AND a.approved = 1'
		. ' ORDER BY a.ordering';
		$db->setQuery( $query );
		$phocadownloads = $db->loadObjectList();

		$tree = array();
		$text = '';
		$tree = Category::CategoryTreeOption($phocadownloads, $tree, 0, $text, -1);
		array_unshift($tree, HTMLHelper::_('select.option', '0', '- '.Text::_('COM_PHOCADOWNLOAD_SELECT_CATEGORY').' -', 'value', 'text'));
		$lists['catid'] = HTMLHelper::_( 'select.genericlist', $tree, 'catid',  $attr , 'value', 'text', $filter_catid );
		//-----------------------------------------------------------------------

		//$this->assignRef('lists',	$lists);
		$this->t['lists'] = $lists;

		parent::display($tpl);
	}
}
?>
