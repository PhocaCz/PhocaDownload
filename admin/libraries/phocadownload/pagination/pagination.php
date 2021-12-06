<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
use Joomla\CMS\Pagination\Pagination;
use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
jimport('joomla.html.pagination');
class PhocaDownloadPagination extends Pagination
{
	function getLimitBox() {

		$app				= Factory::getApplication();
		$paramsC 			= ComponentHelper::getParams('com_phocadownload') ;
		$pagination 		= $paramsC->get( 'pagination', '5,10,15,20,50,100' );
		$paginationArray	= explode( ',', $pagination );

		// Initialize variables
		$limits = array ();

		foreach ($paginationArray as $paginationValue) {
			$limits[] = HTMLHelper::_('select.option', $paginationValue);
		}
		$limits[] = HTMLHelper::_('select.option', '0', Text::_('COM_PHOCADOWNLOAD_ALL'));

		$selected = $this->viewall ? 0 : $this->limit;

		// Build the select list
		if ($app->isClient('administrator')) {
			$html = HTMLHelper::_('select.genericlist',  $limits, 'limit', 'class="form-select" size="1" onchange="submitform();"', 'value', 'text', $selected);
		} else {
			$html = HTMLHelper::_('select.genericlist',  $limits, 'limit', 'class="form-select input-mini" size="1" onchange="this.form.submit()"', 'value', 'text', $selected);
		}
		return $html;
	}

}
?>
