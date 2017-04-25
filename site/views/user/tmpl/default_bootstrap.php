<?php defined('_JEXEC') or die('Restricted access'); 

$heading = '';
if ($this->t['p']->get( 'page_heading' ) != '') {
	$heading .= $this->t['p']->get( 'page_heading' );
}

if ($this->t['showpageheading'] != 0) {
	if ( $heading != '') {
	    echo '<h1>'. $this->escape($heading) . '</h1>';
	} 
}


echo '<ul class="nav nav-tabs">';
echo '<li role="phtabupload" class="active"><a href="#">'.JText::_('COM_PHOCADOWNLOAD_UPLOAD').'</a></li>';
echo '</ul>';

echo '<div id="phtabupload">';
//echo JHtml::_('tabs.panel', JHtml::_( 'image', $this->t['pi'].'icon-document-16.png', '') . '&nbsp;'.JText::_('COM_PHOCADOWNLOAD_UPLOAD'), 'files' );
echo $this->loadTemplate('files_bootstrap');

echo '</div>';
echo $this->t['pw'];
?>
