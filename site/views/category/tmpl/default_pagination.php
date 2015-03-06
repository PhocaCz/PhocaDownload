<?php defined('_JEXEC') or die('Restricted access'); 

$this->t['action'] = str_replace('&amp;', '&', $this->t['action']);
//$this->t['action'] = str_replace('&', '&amp;', $this->t['action']);
$this->t['action'] = htmlspecialchars($this->t['action']);

echo '<form action="'.$this->t['action'].'" method="post" name="adminForm">'. "\n";
echo '<div class="pd-cb">&nbsp;</div>';
echo '<div class="pgcenter"><div class="pagination">';

if ($this->t['p']->get('show_pagination_limit')) {
	echo '<div class="pginline">'
		.JText::_('COM_PHOCADOWNLOAD_DISPLAY_NUM') .'&nbsp;'
		.$this->t['pagination']->getLimitBox()
		.'</div>';
}
	
if ($this->t['p']->get('show_pagination')) {
	echo '<div style="margin:0 10px 0 10px;display:inline;" class="sectiontablefooter'.$this->t['p']->get( 'pageclass_sfx' ).'" id="pg-pagination" >'
		.$this->t['pagination']->getPagesLinks()
		.'</div>'
	
		.'<div style="margin:0 10px 0 10px;display:inline;" class="pagecounter">'
		.$this->t['pagination']->getPagesCounter()
		.'</div>';
}

echo '</div></div>'. "\n";

//echo '<input type="hidden" name="controller" value="category" />';
echo JHTML::_( 'form.token' );
echo '</form>';
?>