<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_templates
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
use Joomla\CMS\Language\Text;
?>
<fieldset class="adminform" title="<?php echo Text::_('COM_PHOCADOWNLOAD_FTP_TITLE'); ?>">
	<legend><?php echo Text::_('COM_PHOCADOWNLOAD_FTP_TITLE'); ?></legend>

	<?php echo Text::_('COM_PHOCADOWNLOAD_FTP_DESC'); ?>

	<?php if ($this->ftp instanceof Exception): ?>
		<p class="error"><?php echo Text::_($this->ftp->message); ?></p>
	<?php endif; ?>

	<table class="adminform">
		<tbody>
			<tr>
				<td width="120">
					<label for="username"><?php echo Text::_('JGLOBAL_USERNAME'); ?></label>
				</td>
				<td>
					<input type="text" id="username" name="username" class="form-control" size="70" value="" />
				</td>
			</tr>
			<tr>
				<td width="120">
					<label for="password"><?php echo Text::_('JGLOBAL_PASSWORD'); ?></label>
				</td>
				<td>
					<input type="password" id="password" name="password" class="form-control" size="70" value="" />
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>
