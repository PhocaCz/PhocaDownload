<?php
/*
 * @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die;

$r = $this->r;
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= $user->authorise('core.edit.state', $this->t['o']);
$saveOrder	= $listOrder == 'a.ordering';
$saveOrderingUrl = '';
if ($saveOrder && !empty($this->items)) {
	$saveOrderingUrl = $r->saveOrder($this->t, $listDirn);
}
$sortFields = $this->getSortFields();

echo $r->jsJorderTable($listOrder);


echo $r->startForm($this->t['o'], $this->t['tasks'], 'adminForm');
//echo $r->startFilter();
//echo $r->endFilter();

echo $r->startMainContainer();

if (isset($this->tmpl['notapproved']->count) && (int)$this->tmpl['notapproved']->count > 0 ) {
	echo '<div class="alert alert-error"><a class="close" data-dismiss="alert" href="#">&times;</a>'.JText::_($this->t['l'].'_NOT_APPROVED_FILES_COUNT').': '
	.(int)$this->tmpl['notapproved']->count.'</div>';
}
/*
echo $r->startFilterBar();
echo $r->inputFilterSearch($this->t['l'].'_FILTER_SEARCH_LABEL', $this->t['l'].'_FILTER_SEARCH_DESC',
							$this->escape($this->state->get('filter.search')));
echo $r->inputFilterSearchClear('JSEARCH_FILTER_SUBMIT', 'JSEARCH_FILTER_CLEAR');
echo $r->inputFilterSearchLimit('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC', $this->pagination->getLimitBox());
echo $r->selectFilterDirection('JFIELD_ORDERING_DESC', 'JGLOBAL_ORDER_ASCENDING', 'JGLOBAL_ORDER_DESCENDING', $listDirn);
echo $r->selectFilterSortBy('JGLOBAL_SORT_BY', $sortFields, $listOrder);

echo $r->startFilterBar(2);
echo $r->selectFilterPublished('JOPTION_SELECT_PUBLISHED', $this->state->get('filter.published'));
echo $r->selectFilterLanguage('JOPTION_SELECT_LANGUAGE', $this->state->get('filter.language'));
echo $r->selectFilterCategory(PhocaDownloadCategory::options($this->t['o']), 'JOPTION_SELECT_CATEGORY', $this->state->get('filter.category_id'));
echo $r->endFilterBar();

echo $r->endFilterBar();*/
echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));

echo $r->startTable('categoryList');

echo $r->startTblHeader();
echo $r->firstColumnHeader($listDirn, $listOrder);
echo $r->secondColumnHeader($listDirn, $listOrder);
//echo $r->thOrderingXML('JGRID_HEADING_ORDERING', $listDirn, $listOrder);
//echo $r->thCheck('JGLOBAL_CHECK_ALL');
echo '<th class="ph-title">'.Joomla\CMS\HTML\HTMLHelper::_('searchtools.sort',  	$this->t['l'].'_TITLE', 'a.title', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-filename-long">'.Joomla\CMS\HTML\HTMLHelper::_('searchtools.sort',  	$this->t['l'].'_FILENAME', 'a.filename', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-published">'.Joomla\CMS\HTML\HTMLHelper::_('searchtools.sort',  $this->t['l'].'_PUBLISHED', 'a.published', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-approved">'.Joomla\CMS\HTML\HTMLHelper::_('searchtools.sort',  	$this->t['l'].'_APPROVED', 'a.approved', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-parentcattitle">'.Joomla\CMS\HTML\HTMLHelper::_('searchtools.sort', $this->t['l'].'_CATEGORY', 'category_id', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-owner">'.Joomla\CMS\HTML\HTMLHelper::_('searchtools.sort',  	$this->t['l'].'_OWNER', 'a.owner_id', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-uploaduser">'.Joomla\CMS\HTML\HTMLHelper::_('searchtools.sort', $this->t['l'].'_UPLOADED_BY', 'uploadusername', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-hits">'.Joomla\CMS\HTML\HTMLHelper::_('searchtools.sort',  		$this->t['l'].'_DOWNLOADS', 'a.hits', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-active">'.JTEXT::_($this->t['l'].'_ACTIVE').'</th>'."\n";
echo '<th class="ph-access">'.JTEXT::_($this->t['l'].'_ACCESS').'</th>'."\n";
echo '<th class="ph-language">'.Joomla\CMS\HTML\HTMLHelper::_('searchtools.sort',  	'JGRID_HEADING_LANGUAGE', 'a.language', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-id">'.Joomla\CMS\HTML\HTMLHelper::_('searchtools.sort',  		$this->t['l'].'_ID', 'a.id', $listDirn, $listOrder ).'</th>'."\n";

echo $r->endTblHeader();

echo $r->startTblBody($saveOrder, $saveOrderingUrl, $listDirn);

$originalOrders = array();
$parentsStr 	= "";
$j 				= 0;

if (is_array($this->items)) {
	foreach ($this->items as $i => $item) {
		//if ($i >= (int)$this->pagination->limitstart && $j < (int)$this->pagination->limit) {
			$j++;

$urlEdit		= 'index.php?option='.$this->t['o'].'&task='.$this->t['task'].'.edit&id=';
$urlTask		= 'index.php?option='.$this->t['o'].'&task='.$this->t['task'];
$orderkey   	= array_search($item->id, $this->ordering[$item->catid]);
$ordering		= ($listOrder == 'a.ordering');
$canCreate		= $user->authorise('core.create', $this->t['o']);
$canEdit		= $user->authorise('core.edit', $this->t['o']);
$canCheckin		= $user->authorise('core.manage', 'com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;
$canChange		= $user->authorise('core.edit.state', $this->t['o']) && $canCheckin;
$linkEdit 		= JRoute::_( $urlEdit. $item->id );

$linkCat	= JRoute::_( 'index.php?option='.$this->t['o'].'&task='.$this->t['c'].'cat.edit&id='.(int) $item->category_id );
$canEditCat	= $user->authorise('core.edit', $this->t['o']);


echo $r->startTr($i, isset($item->catid) ? (int)$item->catid : 0);
echo $r->firstColumn($i, $item->id, $canChange, $saveOrder, $orderkey, $item->ordering);
echo $r->secondColumn($i, $item->id, $canChange, $saveOrder, $orderkey, $item->ordering);

$checkO = '';
if ($item->checked_out) {
	$checkO .= Joomla\CMS\HTML\HTMLHelper::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, $this->t['tasks'].'.', $canCheckin);
}
if ($canCreate || $canEdit) {
	$checkO .= '<a href="'. JRoute::_($linkEdit).'">'. $this->escape($item->title).'</a>';
} else {
	$checkO .= $this->escape($item->title);
}
$checkO .= '<br /><span class="smallsub">(<span>'.JText::_($this->t['l'].'_FIELD_ALIAS_LABEL').':</span>'. $this->escape($item->alias).')</span>';
echo $r->td($checkO, "small hidden-phone ph-wrap-12");

echo $r->td($item->filename, "small ph-wrap-12");

echo $r->td(Joomla\CMS\HTML\HTMLHelper::_('jgrid.published', $item->published, $i, $this->t['tasks'].'.', $canChange), "small hidden-phone");
echo $r->td(PhocaDownloadJGrid::approved( $item->approved, $i, $this->t['tasks'].'.', $canChange), "small hidden-phone");

if ($canEditCat) {
	$catO = '<a href="'. JRoute::_($linkCat).'">'. $this->escape($item->category_title).'</a>';
} else {
	$catO = $this->escape($item->category_title);
}
echo $r->td($catO, "small hidden-phone");
//echo $r->td($this->escape($item->access_level), "small hidden-phone");

$usrO = $item->usernameno;
if ($item->username) {$usrO = $usrO . ' ('.$item->username.')';}
echo $r->td($usrO, "small hidden-phone");

$usrU = $item->uploadname;
if ($item->uploadusername) {$usrU = $usrU . ' ('.$item->uploadusername.')';}
echo $r->td($usrU, "small hidden-phone");

echo $r->td($item->hits, "small hidden-phone");

echo $r->tdPublishDownUp ($item->publish_up, $item->publish_down, $this->t['l']);

echo $r->td($this->escape($item->access_level));

echo $r->tdLanguage($item->language, $item->language_title, $this->escape($item->language_title));
echo $r->td($item->id, "small hidden-phone");

echo $r->endTr();

		//}
	}
}
echo $r->endTblBody();

echo $r->tblFoot($this->pagination->getListFooter(), 15);
echo $r->endTable();

echo $this->loadTemplate('batch');

//echo $r->formInputsXML($listOrder, $originalOrders);
echo $r->formInputsXML($listOrder, $listDirn, $originalOrders);
echo $r->endMainContainer();
echo $r->endForm();
?>
