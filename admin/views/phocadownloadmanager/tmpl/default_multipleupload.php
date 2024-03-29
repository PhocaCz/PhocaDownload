<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Language\Text;
echo '<div id="'.$this->t['c'].'-multipleupload" class="ph-in">';
echo $this->t['mu_response_msg'] ;
echo '<form action="'. Uri::base().'index.php?option='.$this->t['o'].'" >';
if ($this->t['ftp']) {echo PhocaDownloadFileUpload::renderFTPaccess();}
echo '<div class="control-label ph-head-form-small">' . Text::_( $this->t['l'].'_UPLOAD_FILE' ).' ['. Text::_( $this->t['l'].'_MAX_SIZE' ).':&nbsp;'.$this->t['uploadmaxsizeread']
	.']</div>';
echo '<small>'.Text::_($this->t['l'].'_SELECT_FILES').'. '.Text::_($this->t['l'].'_ADD_FILES_TO_UPLOAD_QUEUE_AND_CLICK_START_BUTTON').'</small>';
echo $this->t['mu_output'];
echo '</form>';
echo '</div>';
?>
