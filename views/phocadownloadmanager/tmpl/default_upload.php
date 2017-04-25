<?php 
defined('_JEXEC') or die('Restricted access');
echo '<div id="'.$this->t['c'].'-upload" class="ph-in">';
echo '<div id="upload-noflash" class="actions">';
echo '<form action="'. $this->t['su_url'] .'" id="uploadFormU" method="post" enctype="multipart/form-data">';
if ($this->t['ftp']) { echo PhocaDownloadFileUpload::renderFTPaccess();}  
echo '<div class="control-label ph-head-form">'. JText::_( $this->t['l'].'_UPLOAD_FILE' ).' [ '. JText::_( $this->t['l'].'_MAX_SIZE' ).':&nbsp;'.$this->t['uploadmaxsizeread'].'] </div>';
echo $this->t['su_output'];
echo '</form>';
echo '</div>';
echo '</div>';
?>