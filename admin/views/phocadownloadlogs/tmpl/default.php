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
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
$r = $this->r;
$user		= Factory::getUser();
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
//echo $r->selectFilterLanguage('JOPTION_SELECT_LANGUAGE', $this->state->get('filter.language'));
//echo $r->selectFilterCategory(PhocaDownloadCategory::options($this->t['o']), 'JOPTION_SELECT_CATEGORY', $this->state->get('filter.category_id'));
//echo $r->endFilter();

echo $r->startMainContainer();

if ($this->t['p']->get('enable_logging', 0) == 0) {
	echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">'. Text::_('COM_PHOCADOWNLOAD_LOGGING_NOT_ENABLED').'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="'.Text::_('COM_PHOCADOWNLOAD_CLOSE').'"></button></div>';
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
echo $r->selectFilterType($this->t['l'].'_SELECT_TYPE', $this->state->get('filter.type'), array(1 => Text::_($this->t['l'].'_DOWNLOADS'), 2 =>Text::_($this->t['l'].'_UPLOADS')));
echo $r->endFilterBar();


echo $r->endFilterBar();*/
echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this));

echo $r->startTable('categoryList');

echo $r->startTblHeader();

echo $r->firstColumnHeader($listDirn, $listOrder);
echo $r->secondColumnHeader($listDirn, $listOrder);

//echo '<th></th>';//$r->thOrderingXML('JGRID_HEADING_ORDERING', 0,0);
//echo $r->thCheck('JGLOBAL_CHECK_ALL');
echo '<th class="ph-date">'.HTMLHelper::_('searchtools.sort', $this->t['l'].'_DATE', 'a.date', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-uploaduser">'.HTMLHelper::_('searchtools.sort', $this->t['l'].'_USER', 'username', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-ip">'.HTMLHelper::_('searchtools.sort', $this->t['l'].'_IP', 'a.ip', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-file-short">'.HTMLHelper::_('searchtools.sort',  	$this->t['l'].'_FILE', 'filename', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-catid">'.HTMLHelper::_('searchtools.sort',  	$this->t['l'].'_CATEGORY', 'category_id', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-page">'.HTMLHelper::_('searchtools.sort', $this->t['l'].'_PAGE', 'a.page', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-type">'.HTMLHelper::_('searchtools.sort', $this->t['l'].'_TYPE', 'a.type', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-id">'.HTMLHelper::_('searchtools.sort', $this->t['l'].'_ID', 'a.id', $listDirn, $listOrder ).'</th>'."\n";

echo $r->endTblHeader();

echo $r->startTblBody($saveOrder, $saveOrderingUrl, $listDirn);

$originalOrders = array();
$parentsStr 	= "";
$j 				= 0;

if (is_array($this->items)) {
	foreach ($this->items as $i => $item) {
		//if ($i >= (int)$this->pagination->limitstart && $j < (int)$this->pagination->limit) {
			$j++;
/*
$urlEdit		= 'index.php?option='.$this->t['o'].'&task='.$this->t['task'].'.edit&id=';
$urlTask		= 'index.php?option='.$this->t['o'].'&task='.$this->t['task'];
$orderkey   	= array_search($item->id, $this->ordering[$item->catid]);
$ordering		= ($listOrder == 'a.ordering');
$canCreate		= $user->authorise('core.create', $this->t['o']);
$canEdit		= $user->authorise('core.edit', $this->t['o']);
$canCheckin		= $user->authorise('core.manage', 'com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;
$canChange		= $user->authorise('core.edit.state', $this->t['o']) && $canCheckin;
$linkEdit 		= Route::_( $urlEdit. $item->id );

$linkCat	= Route::_( 'index.php?option='.$this->t['o'].'&task='.$this->t['c'].'cat.edit&id='.(int) $item->category_id );
$canEditCat	= $user->authorise('core.edit', $this->t['o']);*/
$canChange = 0;
$orderkey = 0;
$item->ordering = 0;

echo $r->startTr($i, isset($item->catid) ? (int)$item->catid : 0);
echo $r->firstColumn($i, $item->id, $canChange, $saveOrder, $orderkey, $item->ordering);
echo $r->secondColumn($i, $item->id, $canChange, $saveOrder, $orderkey, $item->ordering);


echo $r->td($this->escape($item->date));

$usrO = $item->usernameno;
if ($item->username) {$usrO = $usrO . ' ('.$item->username.')';}
if (!$usrO) {
	$usrO = Text::_('COM_PHOCADOWNLOAD_GUEST');
}
echo $r->td($usrO, "small hidden-phone");

echo $r->td($this->escape($item->ip));

//echo $r->td($this->escape($item->filetitle));
echo $r->td($this->escape($item->file_title) . ' ('.$this->escape($item->filename) . ')');

echo $r->td($this->escape($item->category_id));
echo $r->td('<span class="editlinktip hasTip" title="'. Text::_( $this->t['l'].'_PAGE' ).'::'. $this->escape($item->page).'">'
			.'<a href="'.$this->escape($item->page).'" target="_blank" >'. Text::_( $this->t['l'].'_PAGE' ).'</a></span>');

if ($item->type == 2) {
	echo $r->td('<span class="label label-warning badge bg-warning">'.Text::_($this->t['l'].'_UPLOAD').'</span>', "small hidden-phone");
} else {
	echo $r->td('<span class="label label-success badge bg-success">'.Text::_($this->t['l'].'_DOWNLOAD').'</span>', "small hidden-phone");
}

echo $r->td($item->id, "small hidden-phone");

echo $r->endTr();

		//}
	}
}
echo $r->endTblBody();

echo $r->tblFoot($this->pagination->getListFooter(), 15);
echo $r->endTable();

//echo $r->formInputsXML($listOrder, $originalOrders);
echo $r->formInputsXML($listOrder, $listDirn, $originalOrders);
echo $r->endMainContainer();
echo $r->endForm();
?>
