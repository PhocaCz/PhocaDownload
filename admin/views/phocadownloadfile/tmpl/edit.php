<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplication;

$extlink 	= 0;
if (isset($this->item->extid) && $this->item->extid != '') {
	$extlink = 1;
}

$r = $this->r;

JFactory::getDocument()->addScriptDeclaration(

'Joomla.submitbutton = function(task) {
	if (task != "'. $this->t['task'].'.cancel" && document.getElementById("jform_catid").value == "") {
		alert("'. $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')) . ' - '. $this->escape(JText::_('COM_PHOCADOWNLOAD_CATEGORY_NOT_SELECTED')).'");
	} else if (task == "'. $this->t['task'].'.cancel" || document.formvalidator.isValid(document.getElementById("adminForm"))) {
		Joomla.submitform(task, document.getElementById("adminForm"));
	} else {
        Joomla.renderMessages({"error": ["'. JText::_('JGLOBAL_VALIDATION_FORM_FAILED', true).'"]});
	}
}'

);

echo $r->startForm($this->t['o'], $this->t['task'], $this->item->id, 'adminForm', 'adminForm');
// First Column
echo '<div class="span12 form-horizontal">';
$tabs = array (
'general' 		=> JText::_($this->t['l'].'_GENERAL_OPTIONS'),
'publishing' 	=> JText::_($this->t['l'].'_PUBLISHING_OPTIONS'),
'metadata'		=> JText::_($this->t['l'].'_METADATA_OPTIONS'),
'mirror'		=> JText::_($this->t['l'].'_MIRROR_DETAILS'),
'video'			=> JText::_($this->t['l'].'_YOUTUBE_OPTIONS')
);
echo $r->navigation($tabs);

echo $r->startTabs();

echo $r->startTab('general', $tabs['general'], 'active');
$formArray = array ('title', 'alias', 'catid', 'ordering',
			'filename', 'filename_play', 'filename_preview', 'image_filename', 'image_filename_spec1', 'image_filename_spec2', 'image_download', 'project_name', 'version', 'author', 'author_url', 'author_email', 'license', 'license_url', 'confirm_license', 'directlink', 'link_external', 'access', 'unaccessible_file', 'userid', 'owner_id');
echo $r->group($this->form, $formArray);
$formArray = array('description', 'features', 'changelog', 'notes' );
echo $r->group($this->form, $formArray, 1);


echo '<div class="ph-float-right ph-admin-additional-box">';

if (isset($this->item->id) && isset($this->item->catid) && isset($this->item->token)
	&& (int)$this->item->id > 0 && (int)$this->item->catid > 0 && $this->item->token != '') {
	phocadownloadimport('phocadownload.path.route');
	$downloadLink = PhocaDownloadRoute::getDownloadRoute((int)$this->item->id, (int)$this->item->catid, $this->item->token, 0);
	$app    		= CMSApplication::getInstance('site');
	$router 		= $app->getRouter();
	$uri 			= $router->build($downloadLink);
    $frontendUrl 	= str_replace(JURI::root(true).'/administrator/', '',$uri->toString());
    $frontendUrl 	= str_replace(JURI::root(true), '', $frontendUrl);
    $frontendUrl 	= str_replace('\\', '/', $frontendUrl);
    //$frontendUrl 	= JURI::root(false). str_replace('//', '/', $frontendUrl);
    $frontendUrl 	= preg_replace('/([^:])(\/{2,})/', '$1/', JURI::root(false). $frontendUrl);
	echo '<div>'.JText::_('COM_PHOCADOWNLOAD_UNIQUE_DOWNLOAD_URL').'</div>';
	echo '<textarea rows="7">'.$frontendUrl.'</textarea>';
	echo '<div><small>('.JText::_('COM_PHOCADOWNLOAD_URL_FORMAT_DEPENDS_ON_SEF').')</small></div>';
}
echo '</div>';


echo $r->endTab();

echo $r->startTab('publishing', $tabs['publishing']);
foreach($this->form->getFieldset('publish') as $field) {
	echo '<div class="control-group">';
	if (!$field->hidden) {
		echo '<div class="control-label">'.$field->label.'</div>';
	}
	echo '<div class="controls">';
	echo $field->input;
	echo '</div></div>';
}
echo $r->endTab();

echo $r->startTab('metadata', $tabs['metadata']);
echo $this->loadTemplate('metadata');
echo $r->endTab();

echo $r->startTab('mirror', $tabs['mirror']);
$formArray = array ('mirror1link', 'mirror1title', 'mirror1target', 'mirror2link',  'mirror2title', 'mirror2target');
echo $r->group($this->form, $formArray);
echo $r->endTab();

echo $r->startTab('video', $tabs['video']);
$formArray = array ('video_filename');
echo $r->group($this->form, $formArray);
echo $r->endTab();


echo $r->endTabs();
//echo '</div>';//end span10
// Second Column
//echo '<div class="span2">';


echo '</div>';//end span2



echo $r->formInputs($this->t['task']);
echo $r->endForm();
?>

