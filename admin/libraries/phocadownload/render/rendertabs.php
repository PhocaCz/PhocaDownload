<?php
/**
 * @package   Phoca Gallery
 * @author    Jan Pavelka - https://www.phoca.cz
 * @copyright Copyright (C) Jan Pavelka https://www.phoca.cz
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 and later
 * @cms       Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class PhocaDownloadRenderTabs
{

    protected $id           = '';
    protected $activeTab    = '';
    protected $countTab     = 0;

    public function __construct() {

        $this->id = uniqid();
        Joomla\CMS\HTML\HTMLHelper::_('jquery.framework', false);
        Joomla\CMS\HTML\HTMLHelper::_('script', 'media/com_phocadownload/js/tabs/tabs.js', array('version' => 'auto'));
        Joomla\CMS\HTML\HTMLHelper::_('stylesheet', 'media/com_phocadownload/js/tabs/tabs.css', array('version' => 'auto'));
    }

    public function setActiveTab($item) {
        if ($item != '') {
            $this->activeTab = $item;
        }
    }

    public function startTabs() {
        return '<div class="phTabs" id="phTabsId' . $this->id . '">';
    }


    public function endTabs() {
        return '</div>';
    }

    public function renderTabsHeader($items) {

        $o   = array();
        $o[] = '<ul class="phTabsUl">';
        if (!empty($items)) {
            $i = 0;
            foreach ($items as $k => $v) {

                $activeO = '';
                if ($this->activeTab == '' && $i == 0) {
                    $activeO = ' active';
                } else if ($this->activeTab == $v['id']) {
                    $activeO = ' active';

                }
                $o[] = '<li class="phTabsLi"><a class="phTabsA phTabsHeader' . $activeO . '" id="phTabId' . $this->id . 'Item' . $v['id'] . '">' . PhocaDownloadRenderFront::renderIcon($v['icon'], 'media/com_phocadownload/images/icon-' . $v['image'] . '.png', '') . '&nbsp;' . $v['title'] . '</a></li>';
                $i++;
            }
        }

        $o[] = '</ul>';
        return implode("\n", $o);

    }

    public function startTab($name) {

        $activeO = '';
        if ($this->activeTab == '' && $this->countTab == 0) {
            $activeO = ' active';
        } else if ($this->activeTab == $name) {
            $activeO = ' active';
        }
        $this->countTab++;
        return '<div class="phTabsContainer' . $activeO . '" id="phTabId' . $this->id . 'Item' . $name . 'Container">';
    }

    public function endTab() {
        return '</div>';
    }
}

