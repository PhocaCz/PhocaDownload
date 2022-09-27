<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

//JHtml::_('behavior.tooltip');
?>
<script type="text/javascript">
    function insertLink() {

        if (!Joomla.getOptions('xtd-phocadownload')) {
            return false;
        }

        var _Joomla$getOptions = Joomla.getOptions('xtd-phocadownload'), editor = _Joomla$getOptions.editor;

        var title = document.getElementById("title").value;
        if (title != '') {
            title = "|text=" + title;
        }
        var target = document.getElementById("target").value;
        if (target != '') {
            target = "|target=" + target;
        }
        var categoryIdOutput;
        var categoryid = document.getElementById("catid").value;
        if (categoryid != '' && parseInt(categoryid) > 0) {
            categoryIdOutput = "|id=" + categoryid;
        } else {
            categoryIdOutput = '';
        }

        if (categoryIdOutput != '' && parseInt(categoryid) > 0) {
            var tag = "{phocadownload view=category" + categoryIdOutput + title + target + "}";
            window.parent.Joomla.editors.instances[editor].replaceSelection(tag);

            if (window.parent.Joomla.Modal) {
                window.parent.Joomla.Modal.getCurrent().close();
            }

            return false;
        } else {
            alert("<?php echo Text::_('COM_PHOCADOWNLOAD_YOU_MUST_SELECT_CATEGORY', true); ?>");
            return false;
        }
    }
</script>

<div id="phocadownload-links">
    <fieldset class="adminform options-menu options-form">
        <legend><?php echo Text::_('COM_PHOCADOWNLOAD_CATEGORY'); ?></legend>
        <form name="adminForm" id="adminForm">
            <div class="control-group">
                <div class="control-label">
                    <label for="title">
                        <?php echo Text::_('COM_PHOCADOWNLOAD_CATEGORY'); ?>
                    </label>
                </div>
                <div class="controls">
                    <?php echo $this->t['lists']['catid']; ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="title">
                        <?php echo Text::_('COM_PHOCADOWNLOAD_TITLE'); ?>
                    </label>
                </div>
                <div class="controls">
                    <input type="text" id="title" name="title" class="form-control"/>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="target">
                        <?php echo Text::_('COM_PHOCADOWNLOAD_TARGET'); ?>
                    </label>
                    <
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
    </fieldset>
    <div class="btn-box-back"><a class="btn btn-light" href="<?php echo $this->t['backlink']; ?>"><span class="icon-arrow-left"></span> <?php echo Text::_('COM_PHOCADOWNLOAD_BACK') ?></a></div>
</div>
