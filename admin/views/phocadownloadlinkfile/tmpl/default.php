<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

$user = Factory::getUser();

//Ordering allowed ?
$ordering = ($this->t['lists']['order'] == 'a.ordering');


if ($this->t['type'] == 0) {
    $view = 'file';
} else if ($this->t['type'] == 1) {
    $view = 'fileplaylink';
} else if ($this->t['type'] == 2) {
    $view = 'fileplay';
} else if ($this->t['type'] == 3) {
    $view = 'filepreviewlink';
} else if ($this->t['type'] == 4) {
    $view = 'filelist';
}

$js = '';
$js .= '
function insertLink() {

    if (!Joomla.getOptions(\'xtd-phocadownload\')) {
       return false;
    }

       var _Joomla$getOptions = Joomla.getOptions(\'xtd-phocadownload\'), editor = _Joomla$getOptions.editor;

	var title = document.getElementById("title").value;
	if (title != "") {
		title = "|text="+title;
	}';
if ($this->t['type'] == 0) {
    $js .= 'var target = document.getElementById("target").value;
        if (target != "") {
            target = "|target="+target;
        }';
} else if ($this->t['type'] == 1 || $this->t['type'] == 2) {
    $js .= 'var playerwidth = document.getElementById("playerwidth").value;
        if (playerwidth != "") {
            playerwidth = "|playerwidth="+playerwidth;
        }
        var playerheight = document.getElementById("playerheight").value;
        if (playerheight != "") {
            playerheight = "|playerheight="+playerheight;
        }
        var playerheightmp3 = document.getElementById("playerheightmp3").value;
        if (playerheightmp3 != "") {
            playerheightmp3 = "|playerheightmp3="+playerheightmp3;
        }';
} else if ($this->t['type'] == 3) {
    $js .= 'var previewwidth = document.getElementById("previewwidth").value;
        if (previewwidth != "") {
            previewwidth = "|previewwidth="+previewwidth;
        }
        var previewheight = document.getElementById("previewheight").value;
        if (previewheight != "") {
            previewheight = "|previewheight="+previewheight;
        }';

} else if ($this->t['type'] == 4) {
    $js .= 'var limit = document.getElementById("limit").value;
        if (limit != "") {
            limit = "|limit="+limit;
        }
        var categoryid = document.getElementById("catid").value;
        if (categoryid != "" && parseInt(categoryid) > 0) {
            categoryIdOutput = "|id="+categoryid;
        } else {
            categoryIdOutput = "";
        }';

}

$js .= 'var fileIdOutput;
	fileIdOutput = "";
	len = document.getElementsByName("fileid").length;
	for (i = 0; i <len; i++) {
		if (document.getElementsByName(\'fileid\')[i].checked) {
			fileid = document.getElementsByName(\'fileid\')[i].value;
			if (fileid != "" && parseInt(fileid) > 0) {
				fileIdOutput = "|id="+fileid;
			} else {
				fileIdOutput = "";
			}
		}
	}

	if (fileIdOutput != "" &&  parseInt(fileid) > 0) {';
if ($this->t['type'] == 0) {
    $js .= 'var tag = "{phocadownload view=' . $view . '"+fileIdOutput+title+target+"}";';
} else if ($this->t['type'] == 1) {
    $js .= 'var tag = "{phocadownload view=' . $view . '"+fileIdOutput+title+playerwidth+playerheight+playerheightmp3+"}";';
} else if ($this->t['type'] == 2) {
    $js .= 'var tag = "{phocadownload view=' . $view . '"+fileIdOutput+title+playerwidth+playerheight+playerheightmp3+"}";';
} else if ($this->t['type'] == 3) {
    $js .= 'var tag = "{phocadownload view=' . $view . '"+fileIdOutput+title+previewwidth+previewheight+"}";';
} else if ($this->t['type'] == 4) {
    $js .= 'var tag = "{phocadownload view=' . $view . '"+fileIdOutput+limit+"}";';
}
/*$js .= 'window.parent.jInsertEditorText(tag, \''. htmlspecialchars($this->t['ename']).'\');';
//window.parent.document.getElementById('sbox-window').close();
$js .= 'window.parent.SqueezeBox.close();
return false;*/
$js .= 'window.parent.Joomla.editors.instances[editor].replaceSelection(tag);

          if (window.parent.Joomla.Modal) {
            window.parent.Joomla.Modal.getCurrent().close();
          }

        return false;
	} else {';

if ($this->t['type'] == 4) {

    $js .= 'if (categoryIdOutput != \'\' &&  parseInt(categoryid) > 0) {
			var tag = "{phocadownload view=' . $view . '"+categoryIdOutput+limit+"}";';

    $js .= 'window.parent.Joomla.editors.instances[editor].replaceSelection(tag);

          if (window.parent.Joomla.Modal) {
            window.parent.Joomla.Modal.getCurrent().close();
          }

        return false;
        
		} else {
			alert("' . Text::_('COM_PHOCADOWNLOAD_YOU_MUST_SELECT_CATEGORY', true) . '");
			return false;
		}';
} else {
    $js .= 'alert("' . Text::_('COM_PHOCADOWNLOAD_YOU_MUST_SELECT_FILE', true) . '");
		return false;';
}
$js .= '}';
$js .= '}';

JFactory::getDocument()->addScriptDeclaration($js); ?>

<div id="phocadownload-links">
    <fieldset class="adminform options-menu options-form">

        <legend><?php echo Text::_('COM_PHOCADOWNLOAD_FILE'); ?></legend>
        <form action="<?php echo $this->t['request_url']; ?>" method="post" name="adminForm" id="adminForm">
            <?php if ($this->t['type'] != 4) { ?>
                <table class="admintable" width="100%">
                    <tr>
                        <td class="key" align="right" width="20%">
                            <label for="title">
                                <?php echo Text::_('COM_PHOCADOWNLOAD_FILTER'); ?>
                            </label>
                        </td>
                        <td width="80%">
                            <div class="input-append"><input type="text" name="search" id="search" value="<?php echo PhocaDownloadUtils::filterValue($this->t['lists']['search'], 'text'); ?>" class="form-control"
                                                             onchange="document.adminForm.submit();"/>
                                <button class="btn btn-primary" onclick="this.form.submit();"><?php echo Text::_('COM_PHOCADOWNLOAD_FILTER'); ?></button>
                                <button class="btn btn-primary" onclick="document.getElementById('search').value='';this.form.submit();"><?php echo Text::_('COM_PHOCADOWNLOAD_RESET'); ?></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="key" align="right" nowrap="nowrap">
                            <label for="title" nowrap="nowrap"><?php echo Text::_('COM_PHOCADOWNLOAD_CATEGORY'); ?></label>
                        </td>
                        <td><?php echo $this->t['lists']['catid']; ?></td>
                    </tr>
                </table>
            <?php } ?>

            <?php if ($this->t['type'] != 4) { ?>
                <div id="editcell">
                    <table class="adminlist plg-button-tbl">
                        <thead>
                        <tr>
                            <th width="5%"><?php echo Text::_('COM_PHOCADOWNLOAD_NUM'); ?></th>
                            <th width="5%"></th>
                            <th class="title" width="60%"><?php echo HTMLHelper::_('grid.sort', 'COM_PHOCADOWNLOAD_TITLE', 'a.title', $this->t['lists']['order_Dir'], $this->t['lists']['order']); ?>
                            </th>
                            <th width="20%" nowrap="nowrap"><?php echo HTMLHelper::_('grid.sort', 'COM_PHOCADOWNLOAD_FILENAME', 'a.filename', $this->t['lists']['order_Dir'], $this->t['lists']['order']); ?>
                            </th>
                            <th width="10%" nowrap="nowrap"><?php echo HTMLHelper::_('grid.sort', 'COM_PHOCADOWNLOAD_ID', 'a.id', $this->t['lists']['order_Dir'], $this->t['lists']['order']); ?>
                            </th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <td colspan="5"><?php echo $this->t['pagination']->getListFooter(); ?></td>
                        </tr>
                        </tfoot>
                        <tbody>
                        <?php
                        $k = 0;
                        for ($i = 0, $n = count($this->t['items']); $i < $n; $i++) {
                            $row = &$this->t['items'][$i];


                            ?>
                            <tr class="<?php echo "row$k"; ?>">
                                <td><?php echo $this->t['pagination']->getRowOffset($i); ?></td>
                                <td><label style="width: 100%; display: inline-block;"><input type="radio" name="fileid" value="<?php echo $row->id ?>"/></label></td>

                                <td><?php echo $row->title; ?></td>
                                <td><?php echo $row->filename; ?></td>
                                <td align="center"><?php echo $row->id; ?></td>
                            </tr>
                            <?php
                            $k = 1 - $k;
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>

            <input type="hidden" name="controller" value="phocadownloadlinkfile"/>
            <input type="hidden" name="type" value="<?php echo $this->t['type']; ?>"/>
            <input type="hidden" name="task" value=""/>
            <input type="hidden" name="boxchecked" value="0"/>
            <input type="hidden" name="filter_order" value="<?php echo $this->t['lists']['order']; ?>"/>
            <input type="hidden" name="filter_order_Dir" value="<?php echo $this->t['lists']['order_Dir']; ?>"/>
            <input type="hidden" name="editor" value="<?php echo $this->t['ename'] ?>"/>
        </form>

<hr>
        <?php

        if ($this->t['type'] == 0) {
            ?>
            <form name="adminFormLink" id="adminFormLink">
                <div class="control-group">
                    <div class="control-label">
                        <label for="title"><?php echo Text::_('COM_PHOCADOWNLOAD_TITLE'); ?></label>
                    </div>
                    <div class="controls"><input class="form-control" type="text" id="title" name="title"/>
                    </div>
                </div>

                <div class="control-group">
                    <div class="control-label">
                        <label for="target">
                            <?php echo Text::_('COM_PHOCADOWNLOAD_TARGET'); ?>
                        </label>
                    </div>
                    <div class="controls">
                        <select name="target" id="target" class="form-select">
                            <option value="s" selected="selected"><?php echo Text::_('COM_PHOCADOWNLOAD_TARGET_SELF'); ?></option>
                            <option value="b"><?php echo Text::_('COM_PHOCADOWNLOAD_TARGET_BLANK'); ?></option>
                            <option value="t"><?php echo Text::_('COM_PHOCADOWNLOAD_TARGET_TOP'); ?></option>
                            <option value="p"><?php echo Text::_('COM_PHOCADOWNLOAD_TARGET_PARENT'); ?></option>
                        </select>
                    </div>
                </div>

                <div class="btn-box-submit">
                    <button class="btn btn-primary plg-button-insert " onclick="insertLink();return false;"><span class="icon-ok"></span> <?php echo Text::_('COM_PHOCADOWNLOAD_INSERT_CODE'); ?></button>
                </div>
            </form>

            <?php
        } else if ($this->t['type'] == 1 || $this->t['type'] == 2) {
            ?>

            <form name="adminFormLink" id="adminFormLink">
                <div class="control-group">

                    <?php if ($this->t['type'] == 1) { ?>
                        <div class="control-label">
                            <label for="title">
                                <?php echo Text::_('COM_PHOCADOWNLOAD_TITLE'); ?>
                            </label>
                        </div>
                        <div class="controls">
                            <input class="form-control" type="text" id="title" name="title"/>
                        </div>

                    <?php } else { ?>
                        <input type="hidden" id="title" name="title"/>
                    <?php } ?>
                </div>

                <div class="control-group">

                    <div class="control-label">
                        <label for="playerwidth">
                            <?php echo Text::_('COM_PHOCADOWNLOAD_PLAYER_WIDTH'); ?>
                        </label>
                    </div>
                    <div class="controls">
                        <input class="form-control" type="text" id="playerwidth" name="playerwidth" value="328"/>
                    </div>
                </div>

                <div class="control-group">

                    <div class="control-label">
                        <label for="playerheight">
                            <?php echo Text::_('COM_PHOCADOWNLOAD_PLAYER_HEIGHT'); ?>
                        </label>
                    </div>
                    <div class="controls">
                        <input class="form-control" type="text" id="playerheight" name="playerheight" value="200"/>
                    </div>
                </div>

                <div class="control-group">

                    <div class="control-label">
                        <label for="playerheightmp3">
                            <?php echo Text::_('COM_PHOCADOWNLOAD_PLAYER_HEIGHT_MP3'); ?>
                        </label>
                    </div>
                    <div class="controls">
                        <input class="form-control" type="text" id="playerheightmp3" name="playerheightmp3" value="30"/>
                    </div>
                </div>
                <?php if ($this->t['type'] == 1) { ?>
                    <div class="ph-warning"><?php echo Text::_('COM_PHOCADOWNLOAD_WARNING_PLAYER_SIZE') ?></div>
                <?php } ?>
                <div class="btn-box-submit">
                    <button class="btn btn-primary plg-button-insert " onclick="insertLink();return false;"><span class="icon-ok"></span> <?php echo Text::_('COM_PHOCADOWNLOAD_INSERT_CODE'); ?></button>
                </div>
            </form>

            <?php
        } else if ($this->t['type'] == 3) {
            ?>

            <form name="adminFormLink" id="adminFormLink">
                <div class="control-group">

                    <?php if ($this->t['type'] == 1) { ?>
                        <div class="control-label">
                            <label for="title">
                                <?php echo Text::_('COM_PHOCADOWNLOAD_TITLE'); ?>
                            </label>
                        </div>
                        <div class="controls">
                            <input class="form-control" type="text" id="title" name="title"/>
                        </div>
                    <?php } else { ?>
                        <input type="hidden" id="title" name="title"/>
                    <?php } ?>

                </div>
                <div class="control-group">
                    <div class="control-label">
                        <label for="previewwidth">
                            <?php echo Text::_('COM_PHOCADOWNLOAD_PREVIEW_WIDTH'); ?>
                        </label>
                    </div>
                    <div class="controls">
                        <input class="form-control" type="text" id="previewwidth" name="previewwidth" value="640"/>
                    </div>

                </div>
                <div class="control-group">
                    <div class="control-label">
                        <label for="previewheight">
                            <?php echo Text::_('COM_PHOCADOWNLOAD_PREVIEW_HEIGHT'); ?>
                        </label>
                    </div>
                    <div class="controls">
                        <input type="text" class="form-control" id="previewheight" name="previewheight" value="480"/>
                    </div>
                </div>

                <div class="btn-box-submit">
                    <button class="btn btn-primary plg-button-insert " onclick="insertLink();return false;"><span class="icon-ok"></span> <?php echo Text::_('COM_PHOCADOWNLOAD_INSERT_CODE'); ?></button>
                </div>
            </form>

            <?php
        } else if ($this->t['type'] == 4) {
            ?>

            <form name="adminFormLink" id="adminFormLink">
                <div class="control-group">
                    <div class="control-label">
                        <label for="title" nowrap="nowrap"><?php echo Text::_('COM_PHOCADOWNLOAD_CATEGORY'); ?></label>
                    </div>
                    <div class="controls"><?php echo $this->t['lists']['catid']; ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <label for="title">
                            <?php echo Text::_('COM_PHOCADOWNLOAD_LIMIT'); ?>
                        </label>
                    </div>
                    <div class="controls">
                        <input type="text" id="limit" name="limit" class="form-control"/>
                        <input type="hidden" id="title" name="title"/>
                    </div>
                </div>

                <div class="btn-box-submit">
                    <button class="btn btn-primary plg-button-insert " onclick="insertLink();return false;"><span class="icon-ok"></span> <?php echo Text::_('COM_PHOCADOWNLOAD_INSERT_CODE'); ?></button>
                </div>
            </form>

            <?php
        }
        ?>
    </fieldset>
    <div class="btn-box-back"><a class="btn btn-light" href="<?php echo $this->t['backlink']; ?>"><span class="icon-arrow-left"></span> <?php echo Text::_('COM_PHOCADOWNLOAD_BACK') ?></a></div>
</div>
