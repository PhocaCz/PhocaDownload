<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Session\Session;

$app		= Factory::getApplication();
$db			= Factory::getDBO();
$user 		= Factory::getUser();
$config		= Factory::getConfig();
$nullDate 	= $db->getNullDate();
$now		= Factory::getDate();

echo '<div id="phocadownload-upload">';

if ($this->t['displayupload'] == 1) {


?>
<script type="text/javascript">
Joomla.submitbutton = function(task, id)
{
	if (id > 0) {
		document.getElementById('adminForm').actionid.value = id;
	}
	Joomla.submitform(task, document.getElementById('adminForm'));

}
jQuery(document).on('change', '.btn-file :file', function() {
	var input = jQuery(this);
    /* numFiles = input.get(0).files ? input.get(0).files.length : 1,*/
    label = input.val().replace(/\\/g, '/').replace(/.*\//, '');

	jQuery('#file-upload-filename').val(label);
});
</script>



<h4><?php echo Text::_( 'COM_PHOCADOWNLOAD_UPLOADED_FILES' ); ?></h4>
<?php
if ($this->t['catidfiles'] == 0 || $this->t['catidfiles'] == '') {
	echo '<div class="alert alert-danger">'.Text::_('COM_PHOCADOWNLOAD_PLEASE_SELECT_CATEGORY_TO_BE_ABLE_TO_UPLOAD_FILES').'</div>';
}
?>
<form action="<?php echo htmlspecialchars($this->t['action']);?>" method="post" name="phocadownloadfilesform" id="adminForm">

<div class="row">
	<div class="col-sm-12 col-md-4">
		<div class="input-group">
			<?php /*<label for="filter_search" class="element-invisible"><?php echo JText::_( 'COM_PHOCADOWNLOAD_FILTER' ); ?></label> */ ?>
			<input type="text" name="search" id="pdsearch" placeholder="<?php echo Text::_( 'COM_PHOCADOWNLOAD_SEARCH' ); ?>" value="<?php echo $this->t['listsfiles']['search'];?>" title="<?php echo Text::_( 'COM_PHOCADOWNLOAD_SEARCH' ); ?>" class="form-control" />

			<span class="input-group-btn">
			<button class="btn btn-primary tip hasTooltip" type="submit" onclick="this.form.submit();"  title="<?php echo Text::_( 'COM_PHOCADOWNLOAD_SEARCH' ); ?>"><span class="icon-fw icon-search"></span></button>
			<button class="btn btn-primary tip hasTooltip" type="button" onclick="document.getElementById('pdsearch').value='';document.phocadownloadfilesform.submit();" title="<?php echo Text::_( 'COM_PHOCADOWNLOAD_SEARCH' ); ?>"><span class="icon-fw icon-remove"></span></button>
			</span>
		</div>
	</div>

	<div class="col-sm-12 col-md-4"></div>
	<div class="col-sm-12 col-md-4 ph-right"><?php echo $this->t['listsfiles']['catid'] ?></div>

</div>


<table class="table">
<thead>
	<tr>
	<th class="title ph-th-50"><?php echo HTMLHelper::_('grid.sort',  'COM_PHOCADOWNLOAD_TITLE', 'a.title', $this->t['listsfiles']['order_Dir'], $this->t['listsfiles']['order'], 'image'); ?></th>
	<th class="ph-th-3"><?php echo HTMLHelper::_('grid.sort',  'COM_PHOCADOWNLOAD_PUBLISHED', 'a.published', $this->t['listsfiles']['order_Dir'], $this->t['listsfiles']['order'], 'image' ); ?></th>
	<th class="ph-th-3"><?php echo Text::_('COM_PHOCADOWNLOAD_DELETE'); ?></th>
	<th class="ph-th-3"><?php echo Text::_('COM_PHOCADOWNLOAD_ACTIVE'); ?></th>
	<th class="ph-th-3"><?php echo HTMLHelper::_('grid.sort', 'COM_PHOCADOWNLOAD_APPROVED', 'a.approved', $this->t['listsfiles']['order_Dir'], $this->t['listsfiles']['order'], 'image' ); ?></th>

	<th class="ph-th-3"><?php echo HTMLHelper::_('grid.sort', 'COM_PHOCADOWNLOAD_DATE_UPLOAD', 'a.date', $this->t['listsfiles']['order_Dir'], $this->t['listsfiles']['order'], 'image' ); ?></th>


	<th class="ph-th-3"><?php echo HTMLHelper::_('grid.sort', 'COM_PHOCADOWNLOAD_CATEGORY', 'a.catid', $this->t['listsfiles']['order_Dir'], $this->t['listsfiles']['order'], 'image' ); ?></th>

</thead>

<tbody><?php
$k 		= 0;
$i 		= 0;
$n 		= count( $this->t['filesitems'] );
$rows 	= &$this->t['filesitems'];

if (is_array($rows)) {
	foreach ($rows as $row) {

	// USER RIGHT - Delete (Publish/Unpublish) - - - - - - - - - - -
	// 2, 2 means that user access will be ignored in function getUserRight for display Delete button
	// because we cannot check the access and delete in one time
	$user = Factory::getUser();
	$rightDisplayDelete	= 0;
	$catAccess	= PhocaDownloadAccess::getCategoryAccessByFileId((int)$row->id);
	if (!empty($catAccess)) {
		$rightDisplayDelete = PhocaDownloadAccess::getUserRight('deleteuserid', $catAccess->deleteuserid, 2, $user->getAuthorisedViewLevels(), $user->get('id', 0), 0);
	}
	// - - - - - - - - - - - - - - - - - - - - - -

	?><tr class="<?php echo "row$k"; ?>">

	<td><?php
	$icon = PhocaDownloadFile::getMimeTypeIcon($row->filename);
	echo $icon . ' ' . $row->title;
	?></td>

	<?php

	// Publish Unpublish
	echo '<td class="ph-td-center">';
	if ($row->published == 1) {
		if ($rightDisplayDelete) {
			echo '<a href="javascript:void(0)" onclick="javascript:Joomla.submitbutton(\'unpublish\', '.(int)$row->id.');" >';
			//echo HTMLHelper::_('image', $this->t['pi'].'icon-publish.png', Text::_('COM_PHOCADOWNLOAD_PUBLISHED'));
			echo '<span class="icon-fw icon-check-circle phc-green" title="'.Text::_('COM_PHOCADOWNLOAD_PUBLISHED').'"></span>';
			echo '</a>';
		} else {
			//echo HTMLHelper::_('image', $this->t['pi'].'icon-publish-g.png', Text::_('COM_PHOCADOWNLOAD_PUBLISHED'));
			echo '<span class="icon-fw icon-check-circle phc-green" title="'.Text::_('COM_PHOCADOWNLOAD_PUBLISHED').'"></span>';
		}
	}
	if ($row->published == 0) {
		if ($rightDisplayDelete) {
			echo '<a href="javascript:void(0)" onclick="javascript:Joomla.submitbutton(\'publish\', '.(int)$row->id.');" >';
			//echo HTMLHelper::_('image', $this->t['pi'].'icon-unpublish.png', Text::_('COM_PHOCADOWNLOAD_UNPUBLISHED'));
			echo '<span class="icon-fw icon-minus-circle phc-red" title="'.Text::_('COM_PHOCADOWNLOAD_UNPUBLISHED').'"></span>';
			echo '</a>';
		} else {
			//echo HTMLHelper::_('image', $this->t['pi'].'icon-unpublish-g.png', Text::_('COM_PHOCADOWNLOAD_UNPUBLISHED'));
			echo '<span class="icon-fw icon-minus-circle phc-red" title="'.Text::_('COM_PHOCADOWNLOAD_UNPUBLISHED').'"></span>';
		}
	}
	echo '</td>';

	echo '<td class="ph-td-center">';
	if ($rightDisplayDelete) {
		echo '<a href="javascript:void(0)" onclick="javascript: if (confirm(\''.Text::_('COM_PHOCADOWNLOAD_WARNING_DELETE_ITEMS').'\')) {Joomla.submitbutton(\'delete\', '.(int)$row->id.');}" >';
		//echo HTMLHelper::_('image', $this->t['pi'].'icon-trash.png', Text::_('COM_PHOCADOWNLOAD_DELETE'));
		echo '<span class="icon-fw icon-trash phc-red" title="'.Text::_('COM_PHOCADOWNLOAD_DELETE').'"></span>';
		echo '</a>';
	} else {
		//echo HTMLHelper::_('image', $this->t['pi'].'icon-trash-g.png', Text::_('COM_PHOCADOWNLOAD_DELETE'));
		echo '<span class="icon-fw icon-trash phc-grey" title="'.Text::_('COM_PHOCADOWNLOAD_DELETE').'"></span>';
	}
	echo '</td>';

	echo '<td class="ph-td-center">';
	// User should get info about active/not active file (if e.g. admin change the active status)
	$publish_up 	= Factory::getDate($row->publish_up);
	$publish_down 	= Factory::getDate($row->publish_down);
	$tz 			= new DateTimeZone($config->get('offset'));
	$publish_up->setTimezone($tz);
	$publish_down->setTimezone($tz);


	if ( $now->toUnix() <= $publish_up->toUnix() ) {
		$text = Text::_( 'COM_PHOCADOWNLOAD_PENDING' );
	} else if ( ( $now->toUnix() <= $publish_down->toUnix() || $row->publish_down == $nullDate ) ) {
		$text = Text::_( 'COM_PHOCADOWNLOAD_ACTIVE' );
	} else if ( $now->toUnix() > $publish_down->toUnix() ) {
		$text = Text::_( 'COM_PHOCADOWNLOAD_EXPIRED' );
	}

	$times = '';
	if (isset($row->publish_up)) {
		if ($row->publish_up == $nullDate) {
			$times .= "\n".Text::_( 'COM_PHOCADOWNLOAD_START') . ': '.Text::_( 'COM_PHOCADOWNLOAD_ALWAYS' );
		} else {
			$times .= "\n".Text::_( 'COM_PHOCADOWNLOAD_START') .": ". $publish_up->format("D, d M Y H:i:s");
		}
	}
	if (isset($row->publish_down)) {
		if ($row->publish_down == $nullDate) {
			$times .= "\n". Text::_( 'COM_PHOCADOWNLOAD_FINISH'). ': '. Text::_('COM_PHOCADOWNLOAD_NO_EXPIRY' );
		} else {
			$times .= "\n". Text::_( 'COM_PHOCADOWNLOAD_FINISH') .": ". $publish_up->format("D, d M Y H:i:s");
		}
	}

	if ( $times ) {
		echo '<span class="editlinktip hasTip" title="'. Text::_( 'COM_PHOCADOWNLOAD_PUBLISH_INFORMATION' ).': '. $times.'">'
			.'<a href="javascript:void(0);" >'. $text.'</a></span>';
	}


	echo '</td>';

	// Approved
	echo '<td class="ph-td-center">';
	if ($row->approved == 1) {
		//echo HTMLHelper::_('image', $this->t['pi'].'icon-publish.png', Text::_('COM_PHOCADOWNLOAD_APPROVED'));
		echo '<span class="icon-fw icon-check-circle phc-green" title="'.Text::_('COM_PHOCADOWNLOAD_APPROVED').'"></span>';
	} else {
		//echo HTMLHelper::_('image', $this->t['pi'].'icon-unpublish.png', Text::_('COM_PHOCADOWNLOAD_NOT_APPROVED'));
		echo '<span class="icon-fw icon-minus-circle phc-red" title="'.Text::_('COM_PHOCADOWNLOAD_NOT_APPROVED').'"></span>';
	}
	echo '</td>';

	$upload_date = Factory::getDate($row->date);
	$upload_date->setTimezone($tz);
	echo '<td class="ph-td-center">'. $upload_date .'</td>';

	//echo '<td class="ph-td-center">'. $row->date .'</td>';


	echo '<td class="ph-td-center">'. $row->categorytitle .'</td>'
	//echo '<td class="ph-td-center">'. $row->id .'</td>'
	.'</tr>';

		$k = 1 - $k;
		$i++;
	}
}
?></tbody>
<tfoot>
	<tr>
	<td colspan="7" class="footer"><?php

//$this->t['filespagination']->setTab($this->t['currenttab']['files']);
if (!empty($this->t['filesitems'])) {
	echo '<div class="pd-center pagination">';
	echo '<div class="pd-inline">';

	echo '<div style="margin:0 10px 0 10px;display:inline;">'
		.Text::_('COM_PHOCADOWNLOAD_DISPLAY_NUM') .'&nbsp;'
		.$this->t['filespagination']->getLimitBox()
		.'</div>';
	echo '<div class="sectiontablefooter'.$this->t['p']->get( 'pageclass_sfx' ).'" style="margin:0 10px 0 10px;display:inline;" >'
		.$this->t['filespagination']->getPagesLinks()
		.'</div>';
	echo '<div class="pagecounter" style="margin:0 10px 0 10px;display:inline;">'
		.$this->t['filespagination']->getPagesCounter()
		.'</div>';
	echo '</div></div>';
}




?></td>
	</tr>
</tfoot>
</table>


<?php echo HTMLHelper::_( 'form.token' ); ?>

<input type="hidden" name="controller" value="user" />
<input type="hidden" name="task" value=""/>
<input type="hidden" name="view" value="user"/>
<input type="hidden" name="actionid" value=""/>
<input type="hidden" name="tab" value="<?php echo $this->t['currenttab']['files'];?>" />
<input type="hidden" name="limitstart" value="<?php echo $this->t['filespagination']->limitstart;?>" />
<input type="hidden" name="Itemid" value="<?php echo $app->input->get('Itemid', 0, 'int') ?>"/>
<input type="hidden" name="filter_order" value="<?php echo $this->t['listsfiles']['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />

</form>

<?php

// Upload
$currentFolder = '';
if (isset($this->state->folder) && $this->state->folder != '') {
	$currentFolder = $this->state->folder;
}
?>
<h4><?php
	echo Text::_( 'COM_PHOCADOWNLOAD_UPLOAD_FILE' ).' [ '. Text::_( 'COM_PHOCADOWNLOAD_MAX_SIZE' ).':&nbsp;'.$this->t['uploadmaxsizeread'].']';
?></h4>

<?php
if ($this->t['errorcatid'] != '') {
	echo '<div class="alert alert-danger alert-danger alert-dismissible" role="alert">'
			.'' . $this->t['errorcatid'] . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
} ?>

<form onsubmit="return OnUploadSubmitFile();" action="<?php echo $this->t['actionamp'] ?>task=upload&amp;<?php echo $this->t['session']->getName().'='.$this->t['session']->getId(); ?>&amp;<?php echo Session::getFormToken();?>=1" name="phocadownloaduploadform" id="phocadownload-upload-form" method="post" enctype="multipart/form-data">
<table class="pd-user-upload-table">
	<tr>
		<td><strong><?php echo Text::_('COM_PHOCADOWNLOAD_FILENAME');?>:</strong></td><td>

			<div class="input-append input-group pd-file-upload-form">
			<span class="input-group-btn">
				<span class="btn btn-primary btn-file"> <?php echo Text::_('COM_PHOCADOWNLOAD_SELECT_FILE')?>
					<input type="file" id="file-upload" class="form-control phfileuploadcheckcat" name="Filedata" />
				</span>
			</span>
			<input class="form-control" id="file-upload-filename" readonly="" type="text">
			<button class="btn btn-primary" id="file-upload-submit"><span class="icon-fw icon-upload"></span> <?php echo Text::_('COM_PHOCADOWNLOAD_START_UPLOAD')?></button>
			<span id="upload-clear"></span>
			</div>
			</td>
		</tr>

		<?php
		if ($this->t['errorfile'] != '') {
			echo '<tr><td></td><td><div class="alert alert-danger alert-danger alert-dismissible" role="alert">'
			.'' . $this->t['errorfile'] . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div></td></tr>';
		} ?>

		<tr>
			<td><strong><?php echo Text::_( 'COM_PHOCADOWNLOAD_FILE_TITLE' ); ?>:</strong></td>
			<td><input type="text" id="phocadownload-upload-title" name="phocadownloaduploadtitle" value="<?php echo $this->t['formdata']->title ?>"  maxlength="255" class="form-control comment-input" /></td>
		</tr>
		<tr>
			<td><strong><?php echo Text::_( 'COM_PHOCADOWNLOAD_DESCRIPTION' ); ?>:</strong></td>
			<td><textarea id="phocadownload-upload-description" name="phocadownloaduploaddescription" onkeyup="countCharsUpload();" rows="7" class="form-control comment-input pd-comment-input"><?php echo $this->t['formdata']->description ?></textarea></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><?php echo Text::_('COM_PHOCADOWNLOAD_CHARACTERS_WRITTEN');?> <input name="phocadownloaduploadcountin" value="0" readonly="readonly" class="form-control comment-input2" /> <?php echo Text::_('COM_PHOCADOWNLOAD_AND_LEFT_FOR_DESCRIPTION');?> <input name="phocadownloaduploadcountleft" value="<?php echo $this->t['maxuploadchar'];?>" readonly="readonly" class="form-control comment-input2" />
			</td>
		</tr>

		<tr>
			<td><strong><?php echo Text::_( 'COM_PHOCADOWNLOAD_AUTHOR' ); ?>:</strong></td>
			<td><input type="text" id="phocadownload-upload-author" name="phocadownloaduploadauthor" value="<?php echo $this->t['formdata']->author ?>"  maxlength="255" class="form-control comment-input" /></td>
		</tr>
		<tr>
			<td><strong><?php echo Text::_( 'COM_PHOCADOWNLOAD_AUTHOR_EMAIL' ); ?>:</strong></td>
			<td><input type="text" id="phocadownload-upload-email" name="phocadownloaduploademail" value="<?php echo $this->t['formdata']->email ?>"  maxlength="255" class="form-control comment-input" /></td>
		</tr>

		<?php
		if ($this->t['erroremail'] != '') {
			echo '<tr><td></td><td><div class="alert alert-danger alert-danger alert-dismissible" role="alert">'
			.'' . $this->t['erroremail'] . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div></td></tr>';
		} ?>

		<tr>
			<td><strong><?php echo Text::_( 'COM_PHOCADOWNLOAD_AUTHOR_WEBSITE' ); ?>:</strong></td>
			<td><input type="text" id="phocadownload-upload-website" name="phocadownloaduploadwebsite" value="<?php echo $this->t['formdata']->website ?>"  maxlength="255" class="form-control comment-input" /></td>
		</tr>

		<?php
		if ($this->t['errorwebsite'] != '') {
			echo '<tr><td></td><td><div class="alert alert-danger alert-danger alert-dismissible" role="alert">'
			.'' . $this->t['errorwebsite'] . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div></td></tr>';
		} ?>

		<tr>
			<td><strong><?php echo Text::_( 'COM_PHOCADOWNLOAD_LICENSE' ); ?>:</strong></td>
			<td><input type="text" id="phocadownload-upload-license" name="phocadownloaduploadlicense" value="<?php echo $this->t['formdata']->license ?>"  maxlength="255" class="form-control comment-input" /></td>
		</tr>

		<tr>
			<td><strong><?php echo Text::_( 'COM_PHOCADOWNLOAD_VERSION' ); ?>:</strong></td>
			<td><input type="text" id="phocadownload-upload-version" name="phocadownloaduploadversion" value="<?php echo $this->t['formdata']->version ?>"  maxlength="255" class="form-control comment-input" /></td>
		</tr>

	</table>

	<ul class="upload-queue" id="upload-queue"><li style="display: none" ></li></ul>

	<?php /*<input type="hidden" name="controller" value="user" /> */ ?>
	<input type="hidden" name="viewback" value="user" />
	<input type="hidden" name="view" value="user"/>
	<input type="hidden" name="task" value="upload"/>
	<input type="hidden" name="tab" value="<?php echo $this->t['currenttab']['files'];?>" />
	<input type="hidden" name="Itemid" value="<?php echo $app->input->get('Itemid', 0, 'int') ?>"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->t['listsfiles']['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<input type="hidden" name="catidfiles" value="<?php echo $this->t['catidfiles'] ?>"/>
</form>
<div id="loading-label-file"><?php echo HTMLHelper::_('image', $this->t['pi'].'icon-loading.gif', '') . Text::_('COM_PHOCADOWNLOAD_LOADING'); ?></div>

    <div id="loading-label-user" class="ph-loading-text ph-loading-hidden">
        <div class="ph-lds-ellipsis"><div></div><div></div><div></div><div></div></div>
        <div><?php echo Text::_('COM_PHOCADOWNLOAD_LOADING') ?></div>
    </div>

	<?php
}
echo '</div>';

?>
