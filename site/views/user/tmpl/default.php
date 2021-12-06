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

//if ( $this->t['p']->get( 'show_page_heading' ) ) {
//	echo '<h1>'. $this->escape($this->t['p']->get('page_heading')) . '</h1>';
//}
echo PhocaDownloadRenderFront::renderHeader(array());


//echo '<ul class="nav nav-tabs">';
//echo '<li role="phtabupload" class="active"><a href="#">'.Text::_('COM_PHOCADOWNLOAD_UPLOAD').'</a></li>';
//echo '</ul>';

echo '<h3>'.Text::_('COM_PHOCADOWNLOAD_UPLOAD').'</h3>';
//echo '<div id="phtabupload">';
//echo JHtml::_('tabs.panel', JHtml::_( 'image', $this->t['pi'].'icon-document-16.png', '') . '&nbsp;'.JText::_('COM_PHOCADOWNLOAD_UPLOAD'), 'files' );
echo $this->loadTemplate('files');

//echo '</div>';
echo PhocaDownloadUtils::getInfo();
?>
