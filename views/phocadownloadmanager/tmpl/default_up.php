<?php defined('_JEXEC') or die('Restricted access');

$group 	= PhocaDownloadSettings::getManagerGroup($this->manager);
$link = 'index.php?option='.$this->t['o'].'&amp;view='.$this->t['task'].'&amp;manager='.$this->manager . $group['c'] .'&amp;folder='.$this->folderstate->parent .'&amp;field='. $this->field;
echo '<tr><td>&nbsp;</td>'
.'<td class="ph-img-table">'
.'<a href="'.$link.'" >'
. JHTML::_( 'image', $this->t['i'].'icon-16-up.png', '').'</a>'
.'</td>'
.'<td><a href="'.$link.'" >..</a></td>'
.'</tr>';