<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\Text;
class PhocaDownloadOrdering
{
	public static function getOrderingText ($ordering, $type = 1) {

		$pref = 'c';
		if ($type == 2) {
			$pref = 'cc';
		} else if ($type == 3) {
			$pref = 'a';
		}
		switch ((int)$ordering) {
			case 2:
				$orderingOutput	= 'ordering DESC';
			break;

			case 3:
				$orderingOutput	= 'title ASC';
			break;

			case 4:
				$orderingOutput	= 'title DESC';
			break;

			case 5:
				$orderingOutput	= 'date ASC';
			break;

			case 6:
				$orderingOutput	= 'date DESC';
			break;

			case 7:
				$orderingOutput	= 'id ASC';
			break;

			case 8:
				$orderingOutput	= 'id DESC';
			break;

			case 9:
				$orderingOutput	= 'hits ASC';
			break;

            case 10:
				$orderingOutput	= 'hits DESC';
			break;

			case 11:
				$orderingOutput	= 'filename ASC';
			break;

            case 12:
				$orderingOutput	= 'filename DESC';
			break;

			case 13:
				$orderingOutput 	= 'average ASC';
				$pref = 'r';
			break;
			case 14:
				$orderingOutput 	= 'average DESC';
				$pref = 'r';
			break;

			case 15:
				$orderingOutput 	= 'count ASC';
				$pref = 'r';
			break;
			case 16:
				$orderingOutput 	= 'count DESC';
				$pref = 'r';
			break;

			case 17:
				$orderingOutput	= 'publish_up ASC';
			break;

			case 18:
				$orderingOutput	= 'publish_up DESC';
			break;

			case 19:
				$orderingOutput	= 'publish_down ASC';
			break;

			case 20:
				$orderingOutput	= 'publish_down DESC';
			break;

			case 1:
			default:
				$orderingOutput = 'ordering ASC';
			break;
		}
		return $pref . '.' . $orderingOutput;
	}

	public static function renderOrderingFront( $selected, $type = 1) {

		switch($type) {
			case 2:
				$typeOrdering 	= PhocaDownloadOrdering::getOrderingCategoryArray();
				$ordering		= 'catordering';
			break;

			default:
				$typeOrdering 	= PhocaDownloadOrdering::getOrderingFileArray(1);
				$ordering		= 'fileordering';
			break;
		}

		$html 	= HTMLHelper::_('select.genericlist',  $typeOrdering, $ordering, 'class="form-select" size="1" onchange="this.form.submit()"', 'value', 'text', $selected);

		return $html;
	}


	public static function getOrderingFileArray($frontend = 0) {

		$paramsC 	= ComponentHelper::getParams('com_phocadownload') ;

		if ($frontend == 1) {
			$ordering_asc_desc_arrows 	= $paramsC->get('ordering_asc_desc_arrows', 0);

			$item_ordering_values 		= $paramsC->get('file_ordering_values', '1,2,3,4,5,6,11,12,15,16,13,14,9,10');
		} else {
			$ordering_asc_desc_arrows 	= 0;
			$item_ordering_values 		= '1,2,3,4,5,6,7,8,11,12,15,16,13,14,9,10';
		}

		if ($ordering_asc_desc_arrows == 1) {


			$itemOrdering	= array(
				1 => Text::_('COM_PHOCADOWNLOAD_ORDERING') . " &nbsp;" . "&#8679;",
				2 => Text::_('COM_PHOCADOWNLOAD_ORDERING') . " &nbsp;" .  "&#8681;",
				3 => Text::_('COM_PHOCADOWNLOAD_TITLE') . " &nbsp;" . "&#8679;",
				4 => Text::_('COM_PHOCADOWNLOAD_TITLE') . " &nbsp;" .  "&#8681;",
				5 => Text::_('COM_PHOCADOWNLOAD_DATE') . " &nbsp;" . "&#8679;",
				6 => Text::_('COM_PHOCADOWNLOAD_DATE') . " &nbsp;" .  "&#8681;",
				//7 => JText::_('COM_PHOCADOWNLOAD_ID') . " &nbsp;" . "&#8679;",
				//8 => JText::_('COM_PHOCADOWNLOAD_ID') . " &nbsp;" .  "&#8681;",
				11 => Text::_('COM_PHOCADOWNLOAD_FILENAME') . " &nbsp;" . "&#8679;",
				12 => Text::_('COM_PHOCADOWNLOAD_FILENAME') . " &nbsp;" .  "&#8681;",

				15 => Text::_('COM_PHOCADOWNLOAD_COUNT') . " &nbsp;" . "&#8679;",
				16 => Text::_('COM_PHOCADOWNLOAD_COUNT') . " &nbsp;" .  "&#8681;",
				13 => Text::_('COM_PHOCADOWNLOAD_RATING') . " &nbsp;" . "&#8679;",
				14 => Text::_('COM_PHOCADOWNLOAD_RATING') . " &nbsp;" .  "&#8681;",
				9 => Text::_('COM_PHOCADOWNLOAD_DOWNLOADS') . " &nbsp;" . "&#8679;",
				10 => Text::_('COM_PHOCADOWNLOAD_DOWNLOADS') . " &nbsp;" .  "&#8681;");

		} else {


			$itemOrdering	= array(
				1 => Text::_('COM_PHOCADOWNLOAD_ORDERING_ASC'),
				2 => Text::_('COM_PHOCADOWNLOAD_ORDERING_DESC'),
				3 => Text::_('COM_PHOCADOWNLOAD_TITLE_ASC'),
				4 => Text::_('COM_PHOCADOWNLOAD_TITLE_DESC'),
				5 => Text::_('COM_PHOCADOWNLOAD_DATE_ASC'),
				6 => Text::_('COM_PHOCADOWNLOAD_DATE_DESC'),
				//7 => JText::_('COM_PHOCADOWNLOAD_ID_ASC'),
				//8 => JText::_('COM_PHOCADOWNLOAD_ID_DESC'),
				11 => Text::_('COM_PHOCADOWNLOAD_FILENAME_ASC'),
				12 => Text::_('COM_PHOCADOWNLOAD_FILENAME_DESC'),

				15 => Text::_('COM_PHOCADOWNLOAD_RATING_COUNT_ASC'),
				16 => Text::_('COM_PHOCADOWNLOAD_RATING_COUNT_DESC'),
				13 => Text::_('COM_PHOCADOWNLOAD_AVERAGE_ASC'),
				14 => Text::_('COM_PHOCADOWNLOAD_AVERAGE_DESC'),
				9 => Text::_('COM_PHOCADOWNLOAD_DOWNLOADS_ASC'),
				10 => Text::_('COM_PHOCADOWNLOAD_DOWNLOADS_DESC'));
		}

		$itemOrderingValuesA = explode(',', $item_ordering_values);

		//$itemOrdering = array_intersect_key($itemOrdering, $itemOrderingValues);
		$validItemOrdering = array();
		foreach ($itemOrderingValuesA as $k => $v) {
			if (isset($itemOrdering[$v])) {
				$validItemOrdering[$v] = $itemOrdering[$v];
			}
		}

		return $validItemOrdering;
	}



	public static function getOrderingCategoryArray() {
		$imgOrdering	= array(
				1 => Text::_('COM_PHOCADOWNLOAD_ORDERING_ASC'),
				2 => Text::_('COM_PHOCADOWNLOAD_ORDERING_DESC'),
				3 => Text::_('COM_PHOCADOWNLOAD_TITLE_ASC'),
				4 => Text::_('COM_PHOCADOWNLOAD_TITLE_DESC'),
				5 => Text::_('COM_PHOCADOWNLOAD_DATE_ASC'),
				6 => Text::_('COM_PHOCADOWNLOAD_DATE_DESC'),
				//7 => JText::_('COM_PHOCADOWNLOAD_ID_ASC'),
				//8 => JText::_('COM_PHOCADOWNLOAD_ID_DESC'),
				11 => Text::_('COM_PHOCADOWNLOAD_RATING_COUNT_ASC'),
				12 => Text::_('COM_PHOCADOWNLOAD_RATING_COUNT_DESC'),
				13 => Text::_('COM_PHOCADOWNLOAD_AVERAGE_ASC'),
				14 => Text::_('COM_PHOCADOWNLOAD_AVERAGE_DESC'),
				15 => Text::_('COM_PHOCADOWNLOAD_HITS_ASC'),
				16 => Text::_('COM_PHOCADOWNLOAD_HITS_DESC'));
		return $imgOrdering;
	}
}
?>
