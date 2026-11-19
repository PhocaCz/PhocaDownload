<?php
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Factory;

echo '<div id="phoca-dl-category-box" class="pd-category-view'.$this->t['p']->get('pageclass_sfx').'">';
echo PhocaDownloadRenderFront::renderHeader(array());

if ($this->t['description'] !== '' && in_array(2, $this->t['display_main_description'])) {
	echo '<div class="pd-desc">'.$this->t['description'].'</div>';
}

$category = $this->category[0] ?? null;
$this->checkRights = 1;

if ($category) {
	$canAccessCategory = PhocaDownloadAccess::getUserRight(
		'accessuserid',
		$category->cataccessuserid,
		$category->cataccess,
		$this->t['user']->getAuthorisedViewLevels(),
		$this->t['user']->get('id', 0),
		0
	);

	if ($canAccessCategory === 1) {
		$this->checkRights = 0;
		$layout = new PhocaDownloadLayout();

		echo '<div class="pd-category">';
		echo PhocaDownloadRenderFront::renderSubHeader(array($category->title), '', 'pd-ctitle');

		if ((int) $category->published === 2 && $layout->isValueEditor($category->archived_description)) {
			echo '<div class="pd-cdesc">'.HTMLHelper::_('content.prepare', $category->archived_description).'</div>';
		}

		if ($layout->isValueEditor($category->description)) {
			echo '<div class="pd-cdesc">'.HTMLHelper::_('content.prepare', $category->description).'</div>';
		}

		$appInput = Factory::getApplication()->getInput();
		$hiddenSubcategories = $appInput->get('hide_subcategories', null, 'array');
		$showOnlySubcategories = $appInput->get('show_only_subcategories', null, 'array');

		if ($hiddenSubcategories === null) {
			$hiddenSubcategories = $this->t['p']->get('hide_subcategories', []);
		}
		if ($showOnlySubcategories === null) {
			$showOnlySubcategories = $this->t['p']->get('show_only_subcategories', []);
		}

		$normalizeCategoryIds = static function ($categoryIds) {
			if (!is_array($categoryIds)) {
				$categoryIds = explode(',', (string) $categoryIds);
			}

			return array_filter(array_map('intval', $categoryIds), static function ($categoryId) {
				return $categoryId > 0;
			});
		};

		$hiddenSubcategories = array_fill_keys($normalizeCategoryIds($hiddenSubcategories), true);
		$showOnlySubcategories = array_fill_keys($normalizeCategoryIds($showOnlySubcategories), true);
		$visibleSubcategories = array_filter(
			is_array($this->subcategories) ? $this->subcategories : [],
			static function ($subcategory) use ($hiddenSubcategories, $showOnlySubcategories) {
				$subcategoryId = (int) $subcategory->id;
				$isIncluded = empty($showOnlySubcategories) || isset($showOnlySubcategories[$subcategoryId]);

				return $isIncluded && !isset($hiddenSubcategories[$subcategoryId]);
			}
		);

		if (!empty($visibleSubcategories)) {
			echo '<div class="row row-cols-1 row-cols-md-3 g-4 pd-category-subcategories">';

			foreach ($visibleSubcategories as $subcategory) {
				if (PhocaDownloadAccess::getUserRight(
					'accessuserid',
					$subcategory->cataccessuserid,
					$subcategory->cataccess,
					$this->t['user']->getAuthorisedViewLevels(),
					$this->t['user']->get('id', 0),
					0
				) !== 1) {
					continue;
				}

				echo '<div class="col"><div class="card h-100"><div class="card-body">';
				echo '<h3 class="card-title"><a href="'.Route::_(PhocaDownloadRoute::getCategoryRoute($subcategory->id, $subcategory->alias)).'">'.htmlspecialchars($subcategory->title, ENT_QUOTES, 'UTF-8').'</a></h3>';

				if ((int) $this->t['p']->get('display_num_doc_secs', 0) === 1) {
					echo '<p class="card-text"><small>('.(int) $subcategory->numdoc.')</small></p>';
				}

				echo '</div></div></div>';
			}

			echo '</div><div class="pd-hr-cb"></div>';
		}

		include __DIR__.'/default_files.php';
		if (!empty($this->category)) {
			include __DIR__.'/default_pagination.php';
		}

		echo '</div>';
	} else {
		echo '<h3>'.Text::_('COM_PHOCADOWNLOAD_CATEGORY').'</h3>';
		echo '<div class="alert alert-danger">'.Text::_('COM_PHOCADOWNLOAD_NO_RIGHTS_ACCESS_CATEGORY').'</div>';
	}
}

echo $this->t['bootstrapmodal'];
echo '</div><div class="pd-cb">&nbsp;</div>';
echo PhocaDownloadUtils::getInfo();
