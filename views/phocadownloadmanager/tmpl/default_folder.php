
<?php defined('_JEXEC') or die('Restricted access');
$group 	= PhocaDownloadSettings::getManagerGroup($this->manager);

if ($this->manager == 'filemultiple') {
	
	$checked 	= JHTML::_('grid.id', $this->folderi, $this->folders[$this->folderi]->path_with_name_relative_no, 0, 'foldercid' );
	$link		= 'index.php?option=com_phocadownload&amp;view=phocadownloadmanager'
		 .'&amp;manager='.$this->manager
		 .$group['c']
		 .'&amp;folder='.$this->_tmp_folder->path_with_name_relative_no
		 .'&amp;field='. $this->field;
	
	echo '<tr>'
	.' <td>'. $checked .'</td>'
	.' <td class="ph-img-table"><a href="'. JRoute::_( $link ).'">'
	. JHTML::_( 'image', $this->t['i'].'icon-16-folder-small.png', '').'</a></td>'
	.' <td><a href="'. JRoute::_( $link ).'">'. $this->_tmp_folder->name.'</a></td>'
	.'</tr>';
	
} else {
	
	$link		= 'index.php?option=com_phocadownload&amp;view=phocadownloadmanager'
		 .'&amp;manager='. $this->manager
		 . $group['c']
		 .'&amp;folder='.$this->_tmp_folder->path_with_name_relative_no
		 .'&amp;field='. $this->field;
	
	echo '<tr>'
	.' <td></td>'
	.' <td class="ph-img-table"><a href="'. JRoute::_( $link ).'">'
	. JHTML::_( 'image', $this->t['i'].'icon-16-folder-small.png', JText::_('COM_PHOCADOWNLOAD_OPEN')).'</a></td>'
	.' <td><a href="'. JRoute::_( $link ).'">'. $this->_tmp_folder->name.'</a></td>'
	.'</tr>'; 
}
?>