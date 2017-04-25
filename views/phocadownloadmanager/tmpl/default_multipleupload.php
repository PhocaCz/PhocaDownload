<?php 
defined('_JEXEC') or die('Restricted access');
echo '<div id="'.$this->t['c'].'-multipleupload" class="ph-in">';
echo $this->t['mu_response_msg'] ;
echo '<form action="'. JURI::base().'index.php?option='.$this->t['o'].'" >';
if ($this->t['ftp']) {echo PhocaDownloadFileUpload::renderFTPaccess();}
echo '<div class="control-label ph-head-form-small">' . JText::_( $this->t['l'].'_UPLOAD_FILE' ).' [ '. JText::_( $this->t['l'].'_MAX_SIZE' ).':&nbsp;'.$this->t['uploadmaxsizeread'].','
	.']</div>';
echo '<small>'.JText::_($this->t['l'].'_SELECT_FILES').'. '.JText::_($this->t['l'].'_ADD_FILES_TO_UPLOAD_QUEUE_AND_CLICK_START_BUTTON').'</small>';
echo $this->t['mu_output'];
echo '</form>';
echo '</div>';
?>