<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Layout\LayoutHelper;

abstract class PhocaDownloadBatch
{

	/*public static function item($published, $category = 0)
	{
		// Create the copy/move options.
		$options = array(
			HTMLHelper::_('select.option', 'c', Text::_('JLIB_HTML_BATCH_COPY')),
			HTMLHelper::_('select.option', 'm', Text::_('JLIB_HTML_BATCH_MOVE'))
		);

		$db		= Factory::getDbo();

       //build the list of categories
		$query = 'SELECT a.title AS text, a.id AS value, a.parent_id as parent_id'
		. ' FROM #__phocadownload_categories AS a'
		// TO DO. ' WHERE a.published = '.(int)$published
		. ' ORDER BY a.ordering';
		$db->setQuery( $query );
		$data = $db->loadObjectList();
		$tree = array();
		$text = '';
		$catId= -1;
		$tree = PhocaDownloadCategory::CategoryTreeOption($data, $tree, 0, $text, $catId);

		if ($category == 1) {
			array_unshift($tree, HTMLHelper::_('select.option', 0, Text::_('JLIB_HTML_ADD_TO_ROOT'), 'value', 'text'));
		}


		// Create the batch selector to change select the category by which to move or copy.
		$lines = array(
			'<label id="batch-choose-action-lbl" for="batch-choose-action">',
			Text::_('JLIB_HTML_BATCH_MENU_LABEL'),
			'</label>',
			'<fieldset id="batch-choose-action" class="combo">',
				'<select name="batch[category_id]" class="form-control" id="batch-category-id">',
					'<option value=""> - '.Text::_('JSELECT').' - </option>',
					/*JHtml::_('select.options',	JHtml::_('category.options', $extension, array('published' => (int) $published))),*//*
					HTMLHelper::_('select.options',  $tree ),
				'</select>',
				HTMLHelper::_( 'select.radiolist', $options, 'batch[move_copy]', '', 'value', 'text', 'm'),
			'</fieldset>'
		);

		return implode("\n", $lines);
	}
*/

	private static function buildCategoryTree(array &$options, array $categories, string $treeTitle): void {
		foreach ($categories as $category) {
		  $title = ($treeTitle ? $treeTitle . ' - ' : '') . $category->title;
		  $options[] = (object)[
			'text' => $title . ($category->language === '*' ? '' : ' (' . $category->language . ')'),
			'value' => $category->id,
		  ];
		  if ($category->children)
			self::buildCategoryTree($options, $category->children, $title);
		}
	  }

	public static function item($published, $category = 0)
	{
		// Create the copy/move options.
		$options = array(
			HTMLHelper::_('select.option', 'c', Text::_('JLIB_HTML_BATCH_COPY')),
			HTMLHelper::_('select.option', 'm', Text::_('JLIB_HTML_BATCH_MOVE'))
		);

    $rootCategories = array_filter(PhocadownloadCategory::getCategories(), function($category) {
      return !$category->parent_id;
    });

    $tree = [];
    $tree[] = HTMLHelper::_('select.option', '', Text::_('JSELECT'), 'value', 'text');
    if ($category) {
      $tree[] = HTMLHelper::_('select.option', 0, Text::_('JLIB_HTML_ADD_TO_ROOT'), 'value', 'text');
    }
    self::buildCategoryTree($tree, $rootCategories, '');

    $fancySelectData = [
      'autocomplete'   => 'off',
      'autofocus'      => false,
      'class'          => '',
      'description'    => '',
      'disabled'       => false,
      'group'          => false,
      'id'             => 'batch-category-id',
      'hidden'         => false,
      'hint'           => '',
      'label'          => '',
      'labelclass'     => '',
      'onchange'       => '',
      'onclick'        => '',
      'multiple'       => false,
      'pattern'        => '',
      'readonly'       => false,
      'repeat'         => false,
      'required'       => false,
      'size'           => 4,
      'spellcheck'     => false,
      'validate'       => '',
      'value'          => '',
      'options'        => $tree,
      'dataAttributes' => [],
      'dataAttribute'  => '',
      'name'           => 'batch[category_id]',
    ];

		// Create the batch selector to change select the category by which to move or copy.
		$lines = array(
			'<label id="batch-choose-action-lbl" for="batch-choose-action">',
			Text::_('JLIB_HTML_BATCH_MENU_LABEL'),
			'</label>',
			'<fieldset id="batch-choose-action" class="combo">',
      LayoutHelper::render('joomla.form.field.list-fancy-select', $fancySelectData),
  		HTMLHelper::_( 'select.radiolist', $options, 'batch[move_copy]', '', 'value', 'text', 'm'),
			'</fieldset>'
		);

		return implode("\n", $lines);
	}
}
?>
