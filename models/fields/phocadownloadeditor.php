<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('JPATH_BASE') or die;
jimport('joomla.html.editor');
jimport('joomla.form.formfield');

class JFormFieldPhocaDownloadEditor extends JFormField
{

	public $type 			= 'PhocaDownloadEditor';
	protected $phocaParams 	= null;
	protected $editor;

	protected function getInput()
	{
		// Initialize some field attributes.
		$rows		= (int) $this->element['rows'];
		$cols		= (int) $this->element['cols'];
		$height		= ((string) $this->element['height']) ? (string) $this->element['height'] : '250';
		$width		= ((string) $this->element['width']) ? (string) $this->element['width'] : '100%';

		// Build the buttons array.
		$buttons = (string) $this->element['buttons'];
		if ($buttons == 'true' || $buttons == 'yes' || $buttons == '1') {
			$buttons = true;
		} else if ($buttons == 'false' || $buttons == 'no' || $buttons == '0') {
			$buttons = false;
		} else {
			$buttons = explode(',', $buttons);
		}

		$hide		= ((string) $this->element['hide']) ? explode(',',(string) $this->element['hide']) : array();

		$globalValue = $this->_getPhocaParameter( 'display_editor' );
		if ($globalValue == '') {
			$globalValue = 1;
		}
		$widthE = $width + 200;
		if ($globalValue == 1) {
			// Get an editor object.
			$editor = $this->getEditor();
			
			$editorOutput = '<div style="width:'.$widthE.'px">'. $editor->display($this->name, htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8'), $width, $height, $cols, $rows, $buttons ? (is_array($buttons) ? array_merge($buttons,$hide) : $hide) : false, $this->id).'</div>';
			
			return '<div style="clear:both;margin-top:5px"></div>' .$editorOutput;
		}
		else {
			$style = '';
			if ($width != '' && $height != '') {
				$style = 'style="width:'.$width.'; height:'.$height.'"';
			}
			return '<textarea name="'.$this->name.'" cols="'.$cols.'" rows="'.$rows.'" '.$style.' id="'.$this->name.'" >'.htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8').'</textarea>';
		}

	}

	protected function & getEditor()
	{
		// Only create the editor if it is not already created.
		if (empty($this->editor)) {

			// Initialize variables.
			$editor = null;

			// Get the editor type attribute. Can be in the form of: editor="desired|alternative".
			$type = trim((string) $this->element['editor']);
			if ($type) {
				// Get the list of editor types.
				$types = explode('|', $type);

				// Get the database object.
				$db = JFactory::getDBO();

				// Iterate over teh types looking for an existing editor.
				foreach ($types as $element) {
					// Build the query.
					$query	= $db->getQuery(true);
					$query->select('element');
					$query->from('#__extensions');
					$query->where('element = '.$db->quote($element));
					$query->where('folder = '.$db->quote('editors'));
					$query->where('enabled = 1');

					// Check of the editor exists.
					$db->setQuery($query, 0, 1);
					$editor = $db->loadResult();

					// If an editor was found stop looking.
					if ($editor) {
						break;
					}
				}
			}

			// Create the JEditor intance based on the given editor.
			$this->editor = JFactory::getEditor($editor ? $editor : null);
		}

		return $this->editor;
	}

	public function save()
	{
		return $this->getEditor()->save($this->id);
	}
	
	protected function _setPhocaParams(){
		$component 			= 'com_phocadownload';
		$paramsC			= JComponentHelper::getParams($component) ;
		$this->phocaParams	= $paramsC;
	}

	protected function _getPhocaParameter( $name ){
	
		// Don't call sql query by every param item (it will be loaded only one time)
		if (!$this->phocaParams) {
			$params = $this->_setPhocaParams();
		}
		$globalValue 	= $this->phocaParams->get( $name, '' );	
		return $globalValue;
	}
}
