<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.html.pagination');
class PhocaDownloadPagination extends JPagination
{
	function getLimitBox() {

		$app				= JFactory::getApplication();
		$paramsC 			= JComponentHelper::getParams('com_phocadownload') ;
		$pagination 		= $paramsC->get( 'pagination', '5,10,15,20,50,100' );
		$paginationArray	= explode( ',', $pagination );

		// Initialize variables
		$limits = array ();

		foreach ($paginationArray as $paginationValue) {
			$limits[] = Joomla\CMS\HTML\HTMLHelper::_('select.option', $paginationValue);
		}
		$limits[] = Joomla\CMS\HTML\HTMLHelper::_('select.option', '0', JText::_('COM_PHOCADOWNLOAD_ALL'));

		$selected = $this->viewall ? 0 : $this->limit;

		// Build the select list
		if ($app->isClient('administrator')) {
			$html = Joomla\CMS\HTML\HTMLHelper::_('select.genericlist',  $limits, 'limit', 'class="inputbox" size="1" onchange="submitform();"', 'value', 'text', $selected);
		} else {
			$html = Joomla\CMS\HTML\HTMLHelper::_('select.genericlist',  $limits, 'limit', 'class="inputbox input-mini" size="1" onchange="this.form.submit()"', 'value', 'text', $selected);
		}
		return $html;
	}

}
?>
