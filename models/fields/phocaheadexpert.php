<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('JPATH_BASE') or die;
jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldPhocaHeadExpert extends JFormField
{
	protected $type = 'PhocaHeadExpert';
	protected function getLabel() { return '';}
	
	protected function getInput() {
	
		$tc = 'phocadownload';
		$ts = 'media/com_'.$tc.'/css/administrator/';
		$ti = 'media/com_'.$tc.'/images/administrator/';
		JHTML::stylesheet( $ts.'/'.$tc.'options.css' );
		echo '<div style="clear:both;"></div>';
		$phocaImage	= ( (string)$this->element['phocaimage'] ? $this->element['phocaimage'] : '' );
		$image 		= '';
		
		if ($phocaImage != ''){
			$image 	= JHTML::_('image', $ti . $phocaImage, '' );
		}
		
		if ($this->element['default']) {
			if ($image != '') {
				return '<div class="ph-options-head-expert">'
				.'<div>'. $image.' <strong>'. JText::_($this->element['default']) . '</strong></div>'
				.'</div>';
			} else {
				return '<div class="ph-options-head-expert">'
				.'<strong>'. JText::_($this->element['default']) . '</strong>'
				.'</div>';
			}
		} else {
			return parent::getLabel();
		}
		echo '<div style="clear:both;"></div>';
	}
}
?>