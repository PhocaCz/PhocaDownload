<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

$this->t['action'] = str_replace('&amp;', '&', $this->t['action']);
//$this->t['action'] = str_replace('&', '&amp;', $this->t['action']);
$this->t['action'] = htmlspecialchars($this->t['action']);

if ($this->t['p']->get('show_ordering_files') || $this->t['p']->get('show_pagination_limit') || $this->t['p']->get('show_pagination')) {

echo '<form action="'.$this->t['action'].'" method="post" name="adminForm">'. "\n";

	if (count($this->files)) {
			echo '<div class="pagination pagination-centered">';
			if ($this->t['p']->get('show_ordering_files')) {
				echo Text::_('COM_PHOCADOWNLOAD_ORDER_FRONT') .'&nbsp;'.$this->t['ordering'];
			}
			if ($this->t['p']->get('show_pagination_limit')) {
				echo Text::_('COM_PHOCADOWNLOAD_DISPLAY_NUM') .'&nbsp;'.$this->t['pagination']->getLimitBox();
			}
			if ($this->t['p']->get('show_pagination')) {

				echo '<div class="counter pull-right">'.$this->t['pagination']->getPagesCounter().'</div>'
					.'<div class="pagination pagination-centered">'.$this->t['pagination']->getPagesLinks().'</div>';
			}
			echo '</div>'. "\n";

		}
		echo '<input type="hidden" name="controller" value="category" />';
		echo HTMLHelper::_( 'form.token' );
		echo '</form>';

		echo '<div class="ph-cb pd-cv-paginaton">&nbsp;</div>';
} else {
	echo '<div class="ph-cb pd-csv-paginaton">&nbsp;</div>';
}



















/*

echo '<div class="pagination pagination-centered">';

if ($this->t['p']->get('show_ordering_files') || $this->t['p']->get('show_pagination_limit') || $this->t['p']->get('show_pagination')) {
	echo '<div class="pginline">';
	if ($this->t['p']->get('show_ordering_files')) {
		echo Text::_('COM_PHOCADOWNLOAD_ORDER_FRONT') .'&nbsp;'.$this->t['ordering']. ' &nbsp;';
	}

	if ($this->t['p']->get('show_pagination_limit')) {

			echo Text::_('COM_PHOCADOWNLOAD_DISPLAY_NUM') .'&nbsp;'
			.$this->t['pagination']->getLimitBox() . ' &nbsp;';

	}
	if ($this->t['p']->get('show_pagination')) {
		echo $this->t['pagination']->getPagesCounter();
	}
	echo '</div>';
}

if ($this->t['p']->get('show_pagination')) {
	echo '<div style="margin:0 10px 0 10px;display:inline;" class="sectiontablefooter'.$this->t['p']->get( 'pageclass_sfx' ).'" id="pg-pagination" >'
		.$this->t['pagination']->getPagesLinks()
		.'</div>';

		/*.'<div style="margin:0 10px 0 10px;display:inline;" class="pagecounter">'
		.$this->t['pagination']->getPagesCounter()
		.'</div>';*//*
}

echo '</div></div>'. "\n";

//echo '<input type="hidden" name="controller" value="category" />';
echo HTMLHelper::_( 'form.token' );
echo '</form>';
*/
?>
