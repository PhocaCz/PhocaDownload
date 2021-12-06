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
/*
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
*/
$class		= $this->t['n'] . 'RenderAdminView';
$r = $this->r;

?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == '<?php echo $this->t['task'] ?>.cancel' || document.formvalidator.isValid(document.getElementById('adminForm'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		}
		else {
			Joomla.renderMessages({"error": ["<?php echo Text::_('JGLOBAL_VALIDATION_FORM_FAILED', true);?>"]});
		<?php /* alert('<?php echo JText::_('JGLOBAL_VALIDATION_FORM_FAILED', true);?>'); */ ?>
		}
	}
</script><?php
echo $r->startForm($this->t['o'], $this->t['task'], $this->item->id, 'adminForm', 'adminForm');
// First Column
echo '<div class="span12 form-horizontal">';
$tabs = array (
'general' 		=> Text::_($this->t['l'].'_GENERAL_OPTIONS'),
'publishing' 	=> Text::_($this->t['l'].'_PUBLISHING_OPTIONS'));
echo $r->navigation($tabs);

$formArray = array ('title');
echo $r->groupHeader($this->form, $formArray);

echo $r->startTabs();

echo $r->startTab('general', $tabs['general'], 'active');

if ($this->ftp) { echo $this->loadTemplate('ftp');}

//$formArray = array ('title', 'type', 'filename', 'ordering');
//echo $r->group($this->form, $formArray);

//echo '<div class="control-group">';
//echo $r->item($this->form, 'title');
echo $this->form->getInput('type');
echo $r->item($this->form, 'typeoutput');
echo $r->item($this->form, 'filename', $this->t['ssuffixtype']);
echo $r->item($this->form, 'ordering');

//echo '</div>';

echo '<div class="clr"></div>';
echo $this->form->getLabel('source');
echo '<div class="clr"></div>';
echo '<div class="editor-border" id="ph-editor">';
echo $this->form->getInput('source');
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

// Second Column
//echo '<div class="span2"></div>';//end span2
echo $r->formInputs($this->t['task']);
echo $r->endForm();
?>
