<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class PhocaDownloadFileUploadSingle
{
	public $tab			= '';
	public $returnUrl	= '';

	public function __construct() {}
	
	public function getSingleUploadHTML( $frontEnd = 0) {
		
		if ($frontEnd == 1) {
			
			$html = '<input type="hidden" name="controller" value="category" />'
			.'<input type="hidden" name="tab" value="'.$this->tab.'" />';
		
		} else {
			$html = '<input type="file" id="sfile-upload" name="Filedata" />'
			//.'<input type="submit" id="sfile-upload-submit" value="'.JText::_('COM_PHOCADOWNLOAD_START_UPLOAD').'"/>'
			.'<button class="btn btn-primary" id="upload-submit"><i class="icon-upload icon-white"></i> '.JText::_('COM_PHOCADOWNLOAD_START_UPLOAD').'</button>'
			.'<input type="hidden" name="return-url" value="'. base64_encode($this->returnUrl).'" />'
			.'<input type="hidden" name="tab" value="'.$this->tab.'" />';
		}
		
		return $html;
		
	}
}
?>