<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper;
jimport( 'joomla.application.component.view' );
phocadownloadimport('phocadownload.render.renderadminviews');
use Joomla\String\StringHelper;

class PhocaDownloadViewPhocaDownloadLinkFile extends HtmlView
{
	public $_context 	= 'com_phocadownload.phocadownloadlinkfile';
	protected $t;
	protected $r;

	function display($tpl = null) {
		$app = Factory::getApplication();
		$this->r = new PhocaDownloadRenderAdminViews();
		$this->t = PhocaDownloadUtils::setVars('linkfile');

		$uri		= Uri::getInstance();
		$document	= Factory::getDocument();
		$db		    = Factory::getDBO();

		//Frontend Changes
		$tUri = '';
		if (!$app->isClient('administrator')) {
			$tUri = Uri::base();

		}

		$editor    = $app->getInput()->getCmd('editor', '');
		if (!empty($editor)) {
			$this->document->addScriptOptions('xtd-phocadownload', array('editor' => $editor));
		}

		HTMLHelper::stylesheet( 'media/com_phocadownload/css/administrator/phocadownload.css' );
		HTMLHelper::stylesheet( 'media/plg_editors-xtd_phocadownload/css/phocadownload.css' );

		//JHtml::stylesheet( 'media/com_phocadownload/css/administrator/phocadownload.css' );

		$eName				= $app->getInput()->get('editor');
		$this->t['ename']		= preg_replace( '#[^A-Z0-9\-\_\[\]]#i', '', $eName );
		$this->t['type']		= $app->getInput()->get( 'type', 1, '', 'int' );
		$this->t['backlink']	= $tUri.'index.php?option=com_phocadownload&amp;view=phocadownloadlinks&amp;tmpl=component&amp;editor='.$this->t['ename'];


		$params = ComponentHelper::getParams('com_phocadownload') ;

		//Filter
		$context			= 'com_phocadownload.phocadownload.list.';
		//$sectionid			= $app->getInput()->get( 'sectionid', -1, '', 'int' );
		//$redirect			= $sectionid;
		$option				= Factory::getApplication()->getInput()->getCmd( 'option' );

		$filter_published		= $app->getUserStateFromRequest( $this->_context.'.filter_published',	'filter_published', '',	'word' );
		$filter_catid		= $app->getUserStateFromRequest( $this->_context.'.filter_catid',	'filter_catid', 0,	'int' );
		$catid				= $app->getUserStateFromRequest( $this->_context.'.catid',	'catid', 0,	'int');
	//	$filter_sectionid	= $app->getUserStateFromRequest( $this->_context.'.filter_sectionid','filter_sectionid',	-1,	'int');
		$filter_order		= $app->getUserStateFromRequest( $this->_context.'.filter_order',	'filter_order',		'a.ordering', 'cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( $this->_context.'.filter_order_Dir',	'filter_order_Dir',	'', 'word' );
		$search				= $app->getUserStateFromRequest( $this->_context.'.search','search', '', 'string' );
		$search				= StringHelper::strtolower( $search );

		// Get data from the model
		$items		=  $this->get( 'Data');
		$total		=  $this->get( 'Total');
		$pagination =  $this->get( 'Pagination' );

		// build list of categories

		if ($this->t['type'] != 4) {
			$javascript = 'class="form-control" size="1" onchange="submitform( );"';
		} else {
			$javascript	= '';
		}
		// get list of categories for dropdown filter
		$filter = '';

		//if ($filter_sectionid > 0) {
		//	$filter = ' WHERE cc.section = '.$db->Quote($filter_sectionid);
		//}

		// get list of categories for dropdown filter
		$query = 'SELECT cc.id AS value, cc.title AS text' .
				' FROM #__phocadownload_categories AS cc' .
				$filter .
				' ORDER BY cc.ordering';

		if ($this->t['type'] != 4) {
             $lists['catid'] = PhocaDownloadCategory::filterCategory($query, $catid, null, true, true);
        } else {
             $lists['catid'] = PhocaDownloadCategory::filterCategory($query, $catid, null, false, true);
        }
/*
		if ($this->t['type'] != 4) {
			$lists['catid'] = PhocaDownloadCategory::filterCategory($query, $catid, null, true);
		} else {
			$lists['catid'] = PhocaDownloadCategory::filterCategory($query, $catid, null, false);
		}*/

		// sectionid
		/*$query = 'SELECT s.title AS text, s.id AS value'
		. ' FROM #__phocadownload_sections AS s'
		. ' WHERE s.published = 1'
		. ' ORDER BY s.ordering';

		$lists['sectionid'] = PhocaDownloadCategory::filterSection($query, $filter_sectionid);*/

		// state filter
		$lists['state']	= HTMLHelper::_('grid.state',  $filter_published );

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] 	= $filter_order;

		// search filter
		$lists['search']= $search;


		$user = Factory::getUser();
		$uriS = $uri->toString();
		//$this->assignRef('user',		$user);
		//$this->assignRef('lists',		$lists);
        $this->t['lists'] = $lists;

		//$this->assignRef('items',		$items);
        $this->t['items'] = $items;
		//$this->assignRef('pagination',	$pagination);
        $this->t['pagination'] = $pagination;
		//$this->assignRef('request_url',	$uriS);
        $this->t['request_url'] = $uriS;

		parent::display($tpl);
	}
}
?>
