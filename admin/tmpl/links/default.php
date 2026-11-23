<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Language\Text;


echo '<div id="phocadownload-links">'
.'<fieldset class="adminform">'
.'<legend>'.Text::_( 'COM_PHOCADOWNLOAD_SELECT_TYPE' ).'</legend>'
.'<ul>'
.'<li class="ph-icon-16-edb-categories"><a href="'.$this->t['linkcategories'].'">'.Text::_('COM_PHOCADOWNLOAD_LINK_TO_CATEGORIES').'</a></li>'
.'<li class="ph-icon-16-edb-category"><a href="'.$this->t['linkcategory'].'">'.Text::_('COM_PHOCADOWNLOAD_LINK_TO_CATEGORY').'</a></li>'
.'<li class="ph-icon-16-edb-file"><a href="'.$this->t['linkfile'].'&type=0">'.Text::_('COM_PHOCADOWNLOAD_LINK_TO_FILE').'</a></li>'
.'<li class="ph-icon-16-edb-play"><a href="'.$this->t['linkfile'].'&type=1">'.Text::_('COM_PHOCADOWNLOAD_PLAY_FILE_LINK').'</a> <a href="'.$this->t['linkfile'].'&type=2">'.Text::_('COM_PHOCADOWNLOAD_PLAY_FILE_DIRECT').'</a></li>'
.'<li class="ph-icon-16-edb-preview"><a href="'.$this->t['linkfile'].'&type=3">'.Text::_('COM_PHOCADOWNLOAD_PREVIEW_FILE_LINK').'</a></li>'
.'<li class="ph-icon-16-edb-file"><a href="'.$this->t['linkfile'].'&type=4">'.Text::_('COM_PHOCADOWNLOAD_FILELIST').'</a></li>'
.'<li class="ph-icon-16-edb-play"><a href="'.$this->t['linkytb'].'">'.Text::_('COM_PHOCADOWNLOAD_YOUTUBE_VIDEO').'</a></li>'
.'</ul>'
.'</div>'
.'</fieldset>'
.'</div>';
?>
