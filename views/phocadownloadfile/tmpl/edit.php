<?php defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

$extlink 	= 0;
if (isset($this->item->extid) && $this->item->extid != '') {
	$extlink = 1;
}
$class		= $this->t['n'] . 'RenderAdminView';
$r 			=  new $class();

?>
<script type="text/javascript">
Joomla.submitbutton = function(task){
	if (task != '<?php echo $this->t['task'] ?>.cancel' && document.id('jform_catid').value == '') {
		alert('<?php echo JText::_('JGLOBAL_VALIDATION_FORM_FAILED', true) . ' - '. JText::_($this->t['l'].'_ERROR_CATEGORY_NOT_SELECTED', true);?>');
	} else if (task == '<?php echo $this->t['task'] ?>.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
		<?php echo $this->form->getField('description')->save(); ?>
		<?php echo $this->form->getField('features')->save(); ?>
		<?php echo $this->form->getField('changelog')->save(); ?>
		<?php echo $this->form->getField('notes')->save(); ?>
		Joomla.submitform(task, document.getElementById('adminForm'));
	}
	else {
		alert('<?php echo JText::_('JGLOBAL_VALIDATION_FORM_FAILED', true);?>');
	}
}
</script><?php
echo $r->startForm($this->t['o'], $this->t['task'], $this->item->id, 'adminForm', 'adminForm');
// First Column
echo '<div class="span10 form-horizontal">';
$tabs = array (
'general' 		=> JText::_($this->t['l'].'_GENERAL_OPTIONS'),
'publishing' 	=> JText::_($this->t['l'].'_PUBLISHING_OPTIONS'),
'metadata'		=> JText::_($this->t['l'].'_METADATA_OPTIONS'),
'mirror'		=> JText::_($this->t['l'].'_MIRROR_DETAILS'),
'video'			=> JText::_($this->t['l'].'_YOUTUBE_OPTIONS')
);
echo $r->navigation($tabs);

echo '<div class="tab-content">'. "\n";

echo '<div class="tab-pane active" id="general">'."\n"; 
$formArray = array ('title', 'alias', 'catid', 'ordering',
			'filename', 'filename_play', 'filename_preview', 'image_filename', 'image_filename_spec1', 'image_filename_spec2', 'image_download', 'version', 'author', 'author_url', 'author_email', 'license', 'license_url', 'confirm_license', 'directlink', 'link_external', 'access', 'unaccessible_file', 'userid', 'owner_id');
echo $r->group($this->form, $formArray);
$formArray = array('description', 'features', 'changelog', 'notes' );
echo $r->group($this->form, $formArray, 1);
echo '</div>'. "\n";

echo '<div class="tab-pane" id="publishing">'."\n"; 
foreach($this->form->getFieldset('publish') as $field) {
	echo '<div class="control-group">';
	if (!$field->hidden) {
		echo '<div class="control-label">'.$field->label.'</div>';
	}
	echo '<div class="controls">';
	echo $field->input;
	echo '</div></div>';
}
echo '</div>';
				
echo '<div class="tab-pane" id="metadata">'. "\n";
echo $this->loadTemplate('metadata');
echo '</div>'. "\n";

echo '<div class="tab-pane" id="mirror">'. "\n";
$formArray = array ('mirror1link', 'mirror1title', 'mirror1target', 'mirror2link',  'mirror2title', 'mirror2target');
echo $r->group($this->form, $formArray);
echo '</div>'. "\n";

echo '<div class="tab-pane" id="video">'. "\n";
$formArray = array ('video_filename');
echo $r->group($this->form, $formArray);
echo '</div>'. "\n";
	
				
echo '</div>';//end tab content
echo '</div>';//end span10
// Second Column
echo '<div class="span2">';

if (isset($this->item->id) && isset($this->item->catid) && isset($this->item->token)
	&& (int)$this->item->id > 0 && (int)$this->item->catid > 0 && $this->item->token != '') {
	phocadownloadimport('phocadownload.path.route');
	$downloadLink = PhocaDownloadRoute::getDownloadRoute((int)$this->item->id, (int)$this->item->catid, $this->item->token, 0);
	$app    		= JApplication::getInstance('site');
	$router 		= $app->getRouter();
	$uri 			= $router->build($downloadLink);
	$frontendUrl 	= JURI::root(false). str_replace(JURI::root(true).'/administrator/', '',$uri->toString());
	echo '<div>'.JText::_('COM_PHOCADOWNLOAD_UNIQUE_DOWNLOAD_URL').'</div>';
	echo '<textarea rows="7">'.$frontendUrl.'</textarea>';
	echo '<div><small>('.JText::_('COM_PHOCADOWNLOAD_URL_FORMAT_DEPENDS_ON_SEF').')</small></div>';
}


echo '</div>';//end span2
echo $r->formInputs();
echo $r->endForm();

/*
?>


?><form action="<?php JRoute::_('index.php?option=com_phocadownload'); ?>" method="post" name="adminForm" id="phocadownloadfile-form" class="form-validate">
	<div class="width-60 fltlft">
		
		<fieldset class="adminform">
			<legend><?php echo empty($this->item->id) ? JText::_('COM_PHOCADOWNLOAD_NEW_FILE') : JText::sprintf('COM_PHOCADOWNLOAD_EDIT_FILE', $this->item->id); ?></legend>
			
						
		<ul class="adminformlist">
			<?php
			// Extid is hidden - only for info if this is an external image (the filename field will be not required)
			$formArray = array ('title', 'alias', 'catid', 'ordering',
			'filename', 'filename_play', 'filename_preview', 'image_filename', 'image_filename_spec1', 'image_filename_spec2', 'image_download', 'version', 'author', 'author_url', 'author_email', 'license', 'license_url', 'confirm_license', 'directlink', 'link_external', 'access', 'unaccessible_file', 'userid', 'owner_id');
			foreach ($formArray as $value) {
				echo '<li>'.$this->form->getLabel($value) . $this->form->getInput($value).'</li>' . "\n";
			} ?>
		</ul>
		
			<?php 
			$formArray2 = array('description', 'features', 'changelog', 'notes' );
			foreach ($formArray2 as $value) {
				echo $this->form->getLabel($value);
				echo '<div class="clearfix"></div>';
				echo $this->form->getInput($value);
			}
			?>
		
		<div class="clearfix"></div>
		</fieldset>
	</div>

<div class="width-40 fltrt">
	<div style="text-align:right;margin:5px;"><?php echo $this->tmpl['enablethumbcreationstatus']; ?></div>
	<?php echo JHtml::_('sliders.start','phocadownloadx-sliders-'.$this->item->id, array('useCookie'=>1)); ?>

	<?php echo JHtml::_('sliders.panel',JText::_('COM_PHOCADOWNLOAD_GROUP_LABEL_PUBLISHING_DETAILS'), 'publishing-details'); ?>
		<fieldset class="adminform">
		<ul class="adminformlist">
			<?php foreach($this->form->getFieldset('publish') as $field) {
				echo '<li>';
				if (!$field->hidden) {
					echo $field->label;
				}
				echo $field->input;
				echo '</li>';
			} ?>
			</ul>
		</fieldset>
		
		<?php echo $this->loadTemplate('metadata'); ?>
		
		<?php echo JHtml::_('sliders.panel',JText::_('COM_PHOCADOWNLOAD_GROUP_LABEL_MIRROR_DETAILS'), 'publishing-details'); ?>
		<fieldset class="adminform">
		<ul class="adminformlist">
			<?php
			// Extid is hidden - only for info if this is an external image (the filename field will be not required)
			$formArray = array ( 'mirror1link', 'mirror1title', 'mirror1target', 'mirror2link',  'mirror2title', 'mirror2target');
			foreach ($formArray as $value) {
				echo '<li>'.$this->form->getLabel($value) . $this->form->getInput($value).'</li>' . "\n";
			} ?>
		</ul>

	
		
		<?php echo JHtml::_('sliders.panel',JText::_('COM_PHOCADOWNLOAD_GROUP_LABEL_YOUTUBE_DETAILS'), 'publishing-details'); ?>
		<fieldset class="adminform">
		<ul class="adminformlist">
			<?php
			// Extid is hidden - only for info if this is an external image (the filename field will be not required)
			$formArray = array ( 'video_filename');
			foreach ($formArray as $value) {
				echo '<li>'.$this->form->getLabel($value) . $this->form->getInput($value).'</li>' . "\n";
			} ?>
		</ul>

		<?php echo JHtml::_('sliders.end'); ?>
</div>



<div class="clearfix"></div>

<input type="hidden" name="task" value="" />
<?php echo JHtml::_('form.token'); ?>
</form>
*/ ?>
	
