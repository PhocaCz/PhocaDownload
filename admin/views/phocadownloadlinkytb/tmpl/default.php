<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;

$js = '';
$js .= '
function insertLink() {
	
	if (!Joomla.getOptions(\'xtd-phocadownload\')) {
            return false;
        }

        var _Joomla$getOptions = Joomla.getOptions(\'xtd-phocadownload\'), editor = _Joomla$getOptions.editor;
	
	var urlOutput;
	var url = document.getElementById("url").value;
	if (url != \'\' ) {
		urlOutput = "|url="+url;
	}

	if (urlOutput != \'\' && urlOutput) {
		var tag = "{phocadownload view=youtube"+urlOutput+"}";
		window.parent.Joomla.editors.instances[editor].replaceSelection(tag);

          if (window.parent.Joomla.Modal) {
            window.parent.Joomla.Modal.getCurrent().close();
          }

        return false;
	} else {
		alert("' . Text::_('COM_PHOCADOWNLOAD_WARNING_SET_YOUTUBE_URL', true) . '");
		return false;
	}
}';

Factory::getDocument()->addScriptDeclaration($js); ?>

<div id="phocadownload-links">
    <fieldset class="adminform options-menu options-form">
        <legend><?php echo Text::_('COM_PHOCADOWNLOAD_YOUTUBE_VIDEO'); ?></legend>
        <form name="adminFormLink" id="adminFormLink">
            <div class="control-group">
                <div class="control-label">
                    <label for="url">
                        <?php echo Text::_('COM_PHOCADOWNLOAD_YOUTUBE_URL'); ?>
                    </label>
                </div>
                <div class="controls">
                    <input type="text" id="url" name="url" class="form-control"/>
                </div>
            </div>


            <div class="btn-box-submit">
                <button class="btn btn-primary plg-button-insert " onclick="insertLink();return false;"><span class="icon-ok"></span> <?php echo Text::_('COM_PHOCADOWNLOAD_INSERT_CODE'); ?></button>
            </div>
        </form>

    </fieldset>
    <div class="btn-box-back"><a class="btn btn-light" href="<?php echo $this->t['backlink']; ?>"><span class="icon-arrow-left"></span> <?php echo Text::_('COM_PHOCADOWNLOAD_BACK') ?></a></div>
</div>
