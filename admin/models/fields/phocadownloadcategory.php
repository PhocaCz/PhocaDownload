<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

if (! class_exists('PhocaDownloadCategory')) {
    require_once( JPATH_ADMINISTRATOR.'/components/com_phocadownload/libraries/phocadownload/category/category.php');
}

class JFormFieldPhocaDownloadCategory extends FormField
{
	protected $type 		= 'PhocaDownloadCategory';

	protected function getInput() {

		$db = Factory::getDBO();

       //build the list of categories
		$query = 'SELECT a.title AS text, a.id AS value, a.parent_id as parent_id'
		. ' FROM #__phocadownload_categories AS a'
		//. ' WHERE a.published = 1' // don't lose information about category when it will be unpublished - you should still be able to edit file with such category in administration
		. ' ORDER BY a.ordering';
		$db->setQuery( $query );
		$data = $db->loadObjectList();


		$view 	= Factory::getApplication()->input->get( 'view' );
		$catId	= -1;
		if ($view == 'phocadownloadcat') {
			$id 	= $this->form->getValue('id'); // id of current category
			if ((int)$id > 0) {
				$catId = $id;
			}
		}/*
		if ($view == 'phocadownloadfile') {
			$id 	= $this->form->getValue('catid'); // id of current category

			if ((int)$id > 0) {
				$catId = $id;
			}
		}*/



		//$required	= ((string) $this->element['required'] == 'true') ? TRUE : FALSE;
		$attr = '';
		$attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';
		$attr .= $this->required ? ' required aria-required="true"' : '';
		$attr .= ' class="form-select"';

		$tree = array();
		$text = '';
		$tree = PhocaDownloadCategory::CategoryTreeOption($data, $tree, 0, $text, $catId);

		//if ($required == TRUE) {

		//} else {

			array_unshift($tree, HTMLHelper::_('select.option', '', '- '.Text::_('COM_PHOCADOWNLOAD_SELECT_CATEGORY').' -', 'value', 'text'));
		//}
		return HTMLHelper::_('select.genericlist',  $tree,  $this->name, trim($attr), 'value', 'text', $this->value, $this->id );
	}
}
?>
