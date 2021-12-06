<?php
/*
 * @package Joomla
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die('Restricted access');
$d      = $displayData;
$t      = $d['t'];


?><div class="modal fade" id="pdCategoryModal" tabindex="-1" aria-labelledby="pdCategoryModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="pdCategoryModalLabel"><?php echo Text::_('COM_PHOCADOWNLOAD_TITLE') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo Text::_('COM_PHOCADOWNLOAD_CLOSE') ?>"></button>
      </div>
      <div class="modal-body">
        <iframe id="pdCategoryModalIframe" height="100%" frameborder="0"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo Text::_('COM_PHOCADOWNLOAD_CLOSE') ?></button>
      </div>
    </div>
  </div>
</div>
