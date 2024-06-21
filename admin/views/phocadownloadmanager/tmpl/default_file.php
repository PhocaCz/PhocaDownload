<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

jimport( 'joomla.filesystem.file' );

$ext 	= PhocaDownloadFile::getExtension( $this->_tmp_file->path_without_name_relative );
$group 	= PhocaDownloadSettings::getManagerGroup($this->manager);



$checked 	= HTMLHelper::_('grid.id', $this->filei + count($this->folders), $this->files[$this->filei]->path_with_name_relative_no );
$deleteCode = '<a class="ph-action-inline-icon-box ph-inline-task" href="javascript:void(0);" onclick="javascript:if (confirm(\''.Text::_('COM_PHOCADOWNLOAD_DELETE_FILE_SERVER_WARNING').'\')){ return Joomla.listItemTask(\'cb'.$this->filei + count($this->folders).'\',\'phocadownloadmanager.delete\');}" title="'.Text::_('COM_PHOCADOWNLOAD_DELETE').'"><span class="ph-cp-item ph-icon-task ph-icon-leftm"><i class="duotone icon-purge"></i></span></a>';

if ($this->manager == 'filemultiple') {


	$icon		= PhocaDownloadFile::getMimeTypeIcon($this->_tmp_file->name);

	//$fileNameEncode = urlencode($this->_tmp_file->path_with_name_relative_no);
	//$deleteCode = '<input class="form-check-input" autocomplete="off" type="checkbox" id="cid'.$fileNameEncode.'" name="cid['.$fileNameEncode.']" value="'.$fileNameEncode.'" onclick="Joomla.isChecked(this.checked);">';


	echo '<tr>'
	.' <td>'. $checked .'</td>'
	.' <td class="ph-img-table">'
	. $icon .'</td>'
	.' <td>'
	.'<div class="ph-files-row">'
	.'<div>' . $this->_tmp_file->name . '</div>'
	.'<div class="ph-files-row-item">' . $deleteCode . '</div>'
	.'</div>'
	. '</td>'
	.'</tr>';


} else {



	if (($group['i'] == 1) && ($ext == 'png' || $ext == 'jpg' || $ext == 'gif' || $ext == 'jpeg') ) {

		echo '<tr>'
		.'<td><div style="display:none">'.$checked.'</div></td>'
		.'<td>'
		.'<a href="#" onclick="if (window.parent) window.parent.'. $this->fce.'(\'' .$this->_tmp_file->path_with_name_relative_no.'\')">'
		. HTMLHelper::_( 'image', str_replace( '../', '', $this->_tmp_file->path_without_name_relative), Text::_('COM_PHOCADOWNLOAD_INSERT'), array('title' => Text::_('COM_PHOCADOWNLOAD_INSERT_ICON'), 'class' => 'pd-file-image'))
		.'</a>'
		.'</td><td>'
		.'<div class="ph-files-row">'
		.'<div>'
		.'<a href="#" onclick="if (window.parent) window.parent.'. $this->fce.'(\'' . $this->_tmp_file->path_with_name_relative_no.'\')">'
		. $this->_tmp_file->name
		.'</a>'
		.'</div>'
		.'<div class="ph-files-row-item">' . $deleteCode . '</div>'
		.'</div>'

		.'</td>'
		.'</tr>';

	} else {

		echo '<tr>'
		.'<td><div style="display:none">'.$checked.'</div></td>'
		.'<td>'
		.'<a href="#" onclick="if (window.parent) window.parent.'. $this->fce.'(\'' .$this->_tmp_file->path_with_name_relative_no.'\')">'
		. HTMLHelper::_( 'image', $this->t['i'].'icon-file.png', '', Text::_('COM_PHOCADOWNLOAD_INSERT_FILENAME'))
		.'</a>'
		.'</td><td>'
		.'<div class="ph-files-row">'
		.'<div>'
		.'<a href="#" onclick="if (window.parent) window.parent.'. $this->fce.'(\'' .$this->_tmp_file->path_with_name_relative_no.'\')">'
		. $this->_tmp_file->name
		.'</a>'
		.'</div>'
		.'<div class="ph-files-row-item">' . $deleteCode . '</div>'
		.'</div>'

		.'</td>'
		.'</tr>';
	}
}
?>
