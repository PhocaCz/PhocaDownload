<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 defined('_JEXEC') or die('Restricted access');

//if ( $this->t['p']->get( 'show_page_heading' ) ) {
//	echo '<h1>'. $this->escape($this->t['p']->get('page_heading')) . '</h1>';
//}
echo PhocaDownloadRenderFront::renderHeader(array());


echo '<ul class="nav nav-tabs">';
echo '<li role="phtabupload" class="active"><a href="#">'.JText::_('COM_PHOCADOWNLOAD_UPLOAD').'</a></li>';
echo '</ul>';

echo '<div id="phtabupload">';
//echo Joomla\CMS\HTML\HTMLHelper::_('tabs.panel', Joomla\CMS\HTML\HTMLHelper::_( 'image', $this->t['pi'].'icon-document-16.png', '') . '&nbsp;'.JText::_('COM_PHOCADOWNLOAD_UPLOAD'), 'files' );
echo $this->loadTemplate('files_bootstrap');

echo '</div>';
echo PhocaDownloadUtils::getInfo();
?>
