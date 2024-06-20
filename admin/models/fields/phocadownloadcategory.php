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
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;

if (! class_exists('PhocaDownloadCategory')) {
    require_once( JPATH_ADMINISTRATOR.'/components/com_phocadownload/libraries/phocadownload/category/category.php');
}

Factory::getApplication()->getLanguage()->load('com_phocadownload');

class JFormFieldPhocaDownloadCategory extends FormField
{
	protected $type 		= 'PhocaDownloadCategory';
	protected $layout   	= 'phocadownload.form.field.category';

	protected function getRenderer($layoutId = 'default')
	{
		// Make field usable outside of Phoca Cart component
		$renderer = parent::getRenderer($layoutId);
		$renderer->addIncludePath(JPATH_ADMINISTRATOR . '/components/com_phocadownload/layouts');
		return $renderer;
	}

	private function buildCategoryTree(array &$options, array $categories, string $treeTitle, array $typeFilter, array $langFilter, array $omitIds): void {
			foreach ($categories as $category) {
				  if ($typeFilter && !in_array($category->type, $typeFilter)) continue;
				  if ($langFilter && !in_array($category->language, $langFilter)) continue;
				  if ($omitIds && in_array($category->id, $omitIds)) continue;

				  $title = ($treeTitle ? $treeTitle . ' - ' : '') . $category->title;
				  $options[] = (object)[
					'text' => $title . ($category->language === '*' ? '' : ' (' . $category->language . ')'),
					'value' => $category->id,
				  ];
				  if ($category->children)
					$this->buildCategoryTree($options, $category->children, $title, $typeFilter, $langFilter, $omitIds);
			}
	}

	protected function getInput() {

		$db 			= Factory::getDBO();
		$multiple		= (string)$this->element['multiple'] == 'true';
		$typeMethod		= $this->element['typemethod'];

       	switch($this->element['categorytype']) {
		  case 1:
			$typeFilter = [0, 1];
			break;
		  case 2:
			$typeFilter = [0, 2];
			break;
		  case 0:
		  default:
			$typeFilter = [];
			break;
		}

		if ($this->element['language']) {
		  $langFilter = explode(',', $this->element['language']);
		} elseif ($this->form->getValue('language', 'filter')) {
		  $langFilter = [$this->form->getValue('language', 'filter')];
		} else {
		  $langFilter = [];
		}

		 // TO DO - check for other views than category edit
		$omitIds = [];
		switch (Factory::getApplication()->input->get('view')) {
		  case 'phocadownloadcategory':
			if ($this->form->getValue('id') > 0)
			  $omitIds[] = $this->form->getValue('id');
			break;
		}

		$db->setQuery('SELECT a.*, null AS children FROM #__phocadownload_categories AS a ORDER BY a.ordering, a.id');
		$categories = $db->loadObjectList('id') ?? [];

		array_walk($categories, function ($category) use ($categories) {
			if ($category->parent_id) {
				if ($categories[$category->parent_id]->children === null) {
					$categories[$category->parent_id]->children = [];
				}
				$categories[$category->parent_id]->children[] = $category;
			}
		});

		$rootCategories = array_filter($categories, function($category) {
		  return !$category->parent_id;
		});

		$options = [];
		if ($multiple) {
		  if ($typeMethod == 'allnone') {
			$options[] = HTMLHelper::_('select.option', '0', Text::_('COM_PHOCADOWNLOAD_NONE'), 'value', 'text');
			$options[] = HTMLHelper::_('select.option', '-1', Text::_('COM_PHOCADOWNLOAD_ALL'), 'value', 'text');
		  }
		} else {
		  // in filter we need zero value for canceling the filter

			if ($typeMethod == 'menulink') {
				// Required for menu link,
			} else if ($typeMethod == 'filter') {
			$options[] = HTMLHelper::_('select.option', '', '- ' . Text::_('COM_PHOCADOWNLOAD_SELECT_CATEGORY') . ' -', 'value', 'text');
		  } else {
			$options[] = HTMLHelper::_('select.option', '0', '- '.Text::_('COM_PHOCADOWNLOAD_SELECT_CATEGORY').' -', 'value', 'text');
		  }
		}

		$this->buildCategoryTree($options, $rootCategories, '', $typeFilter, $langFilter, $omitIds);

		$data = $this->getLayoutData();
		$data['options'] = $options;

		//if (!empty($activeCats)) {
		//	$data['value'] = $activeCats;
		//} else {
			$data['value'] = $this->value;
		//}



		$data['refreshPage']    = (bool)$this->element['refresh-enabled'];
		$data['refreshCatId']   = (string)$this->element['refresh-cat-id'];
		$data['refreshSection'] = (string)$this->element['refresh-section'];
		$data['hasCustomFields']= !empty(FieldsHelper::getFields('com_phocadownload.phocadownloadfile'));



		$document					= Factory::getDocument();
		$document->addCustomTag('<script type="text/javascript">
function changeCatid() {
	/* var catid = document.getElementById(\'jform_catid\').value;*/ 
}
</script>');


		return $this->getRenderer($this->layout)->render($data);

	}


	/*
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
		}*//*



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
	}*/
}
?>
