<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 defined('_JEXEC') or die('Restricted access');

//if ( $this->t['p']->get( 'show_page_heading' ) ) {
//	echo '<h1>'. $this->escape($this->t['p']->get('page_heading')) . '</h1>';
//}
echo PhocaDownloadRenderFront::renderHeader(array());
$tab = 0;
switch ($this->t['tab']) {
	case 'up':
		$tab = 1;
	break;

	case 'cc':
	default:
		$tab = 0;
	break;
}

echo '<div>&nbsp;</div>';

if ($this->t['displaytabs'] > 0) {
	
	$tabItems = array();
	phocadownloadimport('phocadownload.render.rendertabs');
	$tabs = new PhocaDownloadRenderTabs();
	echo $tabs->startTabs();
	$tabItems[0] = array('id' => 'files', 'title' => JText::_('COM_PHOCADOWNLOAD_UPLOAD'), 'image' => 'document-16', 'icon' => 'file');
	$tabs->setActiveTab(isset($tabItems[$this->t['tab']]['id']) ? $tabItems[$this->t['tab']]['id'] : 0);
	echo $tabs->renderTabsHeader($tabItems);
	echo $tabs->startTab('files');
	echo $this->loadTemplate('files');
	echo $tabs->endTab();
	echo $tabs->endTabs();
	
	
	/*
	echo '<div id="phocadownload-pane">';
	//$pane =& J Pane::getInstance('Tabs', array('startOffset'=> $this->t['tab']));
	//echo $pane->startPane( 'pane' );
	echo Joomla\CMS\HTML\HTMLHelper::_('tabs.start', 'config-tabs-com_phocadownload-user', array('useCookie'=>1, 'startOffset'=> $this->t['tab']));

	//echo $pane->startPanel( Joomla\CMS\HTML\HTMLHelper::_( 'image .site', $this->t['pi'].'icon-document-16.png','', '', '', '', '') . '&nbsp;'.JText::_('COM_PHOCADOWNLOAD_UPLOAD'), 'files' );
	echo Joomla\CMS\HTML\HTMLHelper::_('tabs.panel', Joomla\CMS\HTML\HTMLHelper::_( 'image', $this->t['pi'].'icon-document-16.png', '') . '&nbsp;'.JText::_('COM_PHOCADOWNLOAD_UPLOAD'), 'files' );
	echo $this->loadTemplate('files');
	//echo $pane->endPanel();

	//echo $pane->endPane();
	echo Joomla\CMS\HTML\HTMLHelper::_('tabs.end');
	echo '</div>';
	*/
	
	
}
echo PhocaDownloadUtils::getInfo();
?>
