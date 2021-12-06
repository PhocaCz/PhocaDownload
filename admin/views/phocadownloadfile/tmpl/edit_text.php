<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 defined('_JEXEC') or die;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
/*JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
*/
$extlink 	= 0;
if (isset($this->item->extid) && $this->item->extid != '') {
	$extlink = 1;
}

$r = $this->r;


JFactory::getDocument()->addScriptDeclaration(

'Joomla.submitbutton = function(task) {
	if (task != "'. $this->t['task'].'.cancel" && document.getElementById("jform_catid").value == "") {
		alert("'. $this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED')) . ' - '. $this->escape(Text::_('COM_PHOCADOWNLOAD_CATEGORY_NOT_SELECTED')).'");
	} else if (task == "'. $this->t['task'].'.cancel" || document.formvalidator.isValid(document.getElementById("adminForm"))) {
		Joomla.submitform(task, document.getElementById("adminForm"));
	} else {
        Joomla.renderMessages({"error": ["'. Text::_('JGLOBAL_VALIDATION_FORM_FAILED', true).'"]});
	}
}'

);

echo $r->startForm($this->t['o'], $this->t['task'], $this->item->id, 'adminForm', 'adminForm');
// First Column
echo '<div class="span12 form-horizontal">';
$tabs = array (
'general' 		=> Text::_($this->t['l'].'_GENERAL_OPTIONS'),
'publishing' 	=> Text::_($this->t['l'].'_PUBLISHING_OPTIONS')
);
echo $r->navigation($tabs);

$formArray = array ('title', 'alias');
echo $r->groupHeader($this->form, $formArray);

echo $r->startTabs();

echo $r->startTab('general', $tabs['general'], 'active');
$formArray = array ( 'catid', 'ordering','access');
echo $r->group($this->form, $formArray);
$formArray = array('description');
echo $r->group($this->form, $formArray, 1);
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


echo $r->endTabs();
echo '</div>';//end span10
// Second Column
//echo '<div class="span2"></div>';//end span2

echo '<input type="hidden" name="jform[filename]" id="jform_filename" value="-" />'
	.'<input type="hidden" name="jform[textonly]" id="jform_textonly" value="1" />';
echo $r->formInputs($this->t['task']);
echo $r->endForm();
?>
