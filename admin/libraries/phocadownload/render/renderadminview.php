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
defined( '_JEXEC' ) or die( 'Restricted access' );

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

class PhocaDownloadRenderAdminView
{
	public function __construct(){

		$app				= JFactory::getApplication();
		$version 			= new \Joomla\CMS\Version();
		$this->compatible 	= $version->isCompatible('4.0.0-alpha');
		$this->view			= $app->input->get('view');
		$this->option		= $app->input->get('option');
		$this->sidebar 		= Factory::getApplication()->getTemplate(true)->params->get('menu', 1) ? true : false;

		switch($this->view) {


			case 'phocadownloadcat':
			case 'phocadownloadfile':
			case 'phocadownloadlayout':
			case 'phocadownloadlic':
			case 'phocadownloadstyle':
			case 'phocadownloadtag':
            default:
				Joomla\CMS\HTML\HTMLHelper::_('behavior.formvalidator');
				Joomla\CMS\HTML\HTMLHelper::_('behavior.keepalive');

				if (!$this->compatible) {
					Joomla\CMS\HTML\HTMLHelper::_('behavior.tooltip');
					Joomla\CMS\HTML\HTMLHelper::_('formbehavior.chosen', 'select');

				}

			break;
		}

		HTMLHelper::_('stylesheet', 'media/'.$this->option.'/duotone/joomla-fonts.css', array('version' => 'auto'));
		

		HTMLHelper::_('stylesheet', 'media/'.$this->option.'/css/administrator/'.str_replace('com_', '', $this->option).'.css', array('version' => 'auto'));

		if ($this->compatible) {
			HTMLHelper::_('stylesheet', 'media/'.$this->option.'/css/administrator/4.css', array('version' => 'auto'));
		} else {
			HTMLHelper::_('stylesheet', 'media/'.$this->option.'/css/administrator/3.css', array('version' => 'auto'));
		}

	}

	public function startCp() {

		$o = array();
		if ($this->compatible) {

			if ($this->sidebar) {

			} else {
				$o[] = '<div class="row">';
				$o[] = '<div id="j-main-container" class="col-md-2">'.JHtmlSidebar::render().'</div>';
				$o[] = '<div id="j-main-container" class="col-md-10">';
			}

		} else {
			$o[] = '<div id="j-sidebar-container" class="span2">' . JHtmlSidebar::render() . '</div>'."\n";
			$o[] = '<div id="j-main-container" class="span10">'."\n";
		}

		return implode("\n", $o);
	}

	public function endCp() {

		$o = array();
		if ($this->compatible) {
			if ($this->sidebar) {

			} else {

				$o[] = '</div></div>';
			}
		} else {
			$o[] = '</div>';
		}

		return implode("\n", $o);
	}

	public function startForm($option, $view, $itemId, $id = 'adminForm', $name = 'adminForm', $class = '', $layout = 'edit',  $tmpl = '') {


		if ($layout != '') {
			$layout = '&layout='.$layout;
		}
		$viewP = '';
		if ($view != '') {
			$viewP = '&view='.$view;
		}
		if ($tmpl != '') {
			$tmpl = '&tmpl='.$tmpl;
		}

		return '<div id="'.$view.'"><form action="'.JRoute::_('index.php?option='.$option . $viewP . $layout . '&id='.(int) $itemId . $tmpl).'" method="post" name="'.$name.'" id="'.$id.'" class="form-validate '.$class.'" role="form">'."\n"
		.'<div id="phAdminEdit" class="row-fluid">'."\n";

	}

	public function endForm() {
		$o = '</div>'."\n";
		$o .= '</form>'."\n";
		$o .= '</div>'."\n";

		return $o;
	}

	public function formInputs() {

		$o = '<input type="hidden" name="task" value="" />'. "\n";
		$o .= Joomla\CMS\HTML\HTMLHelper::_('form.token'). "\n";

		return $o;
	}


	public function group($form, $formArray, $clear = 0) {

		$o = '';
		if (!empty($formArray)) {
			if ($clear == 1) {
				foreach ($formArray as $value) {
					$o .= '<div>'. $form->getLabel($value) . '</div>'."\n"
					. '<div class="clearfix"></div>'. "\n"
					. '<div>' . $form->getInput($value). '</div>'."\n";
				}
			} else {
				foreach ($formArray as $value) {
					$o .= '<div class="control-group">'."\n"
					. '<div class="control-label">'. $form->getLabel($value) . '</div>'."\n"
					. '<div class="controls">' . $form->getInput($value). '</div>'."\n"
					. '</div>' . "\n";
				}
			}
		}
		return $o;
	}

	public function item($form, $item, $suffix = '') {

		$value = $o = '';
		if ($suffix != '') {
			$value = $suffix;
		} else {
			$value = $form->getInput($item);
		}
		$o .= '<div class="control-group">'."\n";
		$o .= '<div class="control-label">'. $form->getLabel($item) . '</div>'."\n"
		. '<div class="controls">' . $value.'</div>'."\n"
		. '</div>' . "\n";
		return $o;
	}

	public function quickIconButton( $link, $text = '', $icon = '', $color = '') {

		$o = '<div class="ph-cp-item">';
		$o .= ' <div class="ph-cp-item-icon">';
		$o .= '  <a class="ph-cp-item-icon-link" href="'.$link.'"><span style="background-color: '.$color.'20;"><i style="color: '.$color.';" class="phi '.$icon.' ph-cp-item-icon-link-large"></i></span></a>';
		$o .= ' </div>';

		$o .= ' <div class="ph-cp-item-title"><a class="ph-cp-item-title-link" href="'.$link.'"><span>'.$text.'</span></a></div>';
		$o .= '</div>';

		return $o;
	}



	public function getLinks($internalLinksOnly = 0) {
		$app	= JFactory::getApplication();
		$option = $app->input->get('option');
		$oT		= strtoupper($option);

		$links =  array();
		switch ($option) {
			
			case 'com_phocadownload':
				$links[]	= array('Phoca Download site', 'https://www.phoca.cz/phocadownload');
				$links[]	= array('Phoca Download documentation site', 'https://www.phoca.cz/documentation/category/17-phoca-download-component');
				$links[]	= array('Phoca Download download site', 'https://www.phoca.cz/download/category/68-phoca-download');
			break;
			
			case 'com_phocagallery':
				$links[]	= array('Phoca Gallery site', 'https://www.phoca.cz/phocagallery');
				$links[]	= array('Phoca Gallery documentation site', 'https://www.phoca.cz/documentation/category/2-phoca-gallery-component');
				$links[]	= array('Phoca Gallery download site', 'https://www.phoca.cz/download/category/66-phoca-gallery');
			break;

			case 'com_phocaemail':
				$links[]	= array('Phoca Email site', 'https://www.phoca.cz/phocaemail');
				$links[]	= array('Phoca Email documentation site', 'https://www.phoca.cz/documentation/category/60-phoca-email-component');
				$links[]	= array('Phoca Email download site', 'https://www.phoca.cz/download/category/47-phoca-email-component');
			break;

		}

		$links[]	= array('Phoca News', 'https://www.phoca.cz/news');
		$links[]	= array('Phoca Forum', 'https://www.phoca.cz/forum');

		if ($internalLinksOnly == 1) {
		    return $links;
        }

		$components 	= array();
		$components[]	= array('Phoca Gallery','phocagallery', 'pg');
		$components[]	= array('Phoca Guestbook','phocaguestbook', 'pgb');
		$components[]	= array('Phoca Download','phocadownload', 'pd');
		$components[]	= array('Phoca Documentation','phocadocumentation', 'pdc');
		$components[]	= array('Phoca Favicon','phocafavicon', 'pfv');
		$components[]	= array('Phoca SEF','phocasef', 'psef');
		$components[]	= array('Phoca PDF','phocapdf', 'ppdf');
		$components[]	= array('Phoca Restaurant Menu','phocamenu', 'prm');
		$components[]	= array('Phoca Maps','phocamaps', 'pm');
		$components[]	= array('Phoca Font','phocafont', 'pf');
		$components[]	= array('Phoca Email','phocaemail', 'pe');
		$components[]	= array('Phoca Install','phocainstall', 'pi');
		$components[]	= array('Phoca Template','phocatemplate', 'pt');
		$components[]	= array('Phoca Panorama','phocapanorama', 'pp');
		$components[]	= array('Phoca Commander','phocacommander', 'pcm');
		$components[]	= array('Phoca Photo','phocaphoto', 'ph');
		$components[]	= array('Phoca Cart','phocacart', 'pc');

		$banners	= array();
		$banners[]	= array('Phoca Restaurant Menu','phocamenu', 'prm');
		$banners[]	= array('Phoca Cart','phocacart', 'pc');

		$o = '';
		$o .= '<p>&nbsp;</p>';
		$o .= '<h4 style="margin-bottom:5px;">'.JText::_($oT.'_USEFUL_LINKS'). '</h4>';
		$o .= '<ul>';
		foreach ($links as $k => $v) {
			$o .= '<li><a style="text-decoration:underline" href="'.$v[1].'" target="_blank">'.$v[0].'</a></li>';
		}
		$o .= '</ul>';

		$o .= '<div>';
		$o .= '<p>&nbsp;</p>';
		$o .= '<h4 style="margin-bottom:5px;">'.JText::_($oT.'_USEFUL_TIPS'). '</h4>';

		$m = mt_rand(0, 10);
		if ((int)$m > 0) {
			$o .= '<div>';
			$num = range(0,(count($components) - 1 ));
			shuffle($num);
			for ($i = 0; $i<3; $i++) {
				$numO = $num[$i];
				$o .= '<div style="float:left;width:33%;margin:0 auto;">';
				$o .= '<div><a style="text-decoration:underline;" href="https://www.phoca.cz/'.$components[$numO][1].'" target="_blank">'.Joomla\CMS\HTML\HTMLHelper::_('image',  'media/'.$option.'/images/administrator/icon-box-'.$components[$numO][2].'.png', ''). '</a></div>';
				$o .= '<div style="margin-top:-10px;"><small><a style="text-decoration:underline;" href="https://www.phoca.cz/'.$components[$numO][1].'" target="_blank">'.$components[$numO][0].'</a></small></div>';
				$o .= '</div>';
			}
			$o .= '<div style="clear:both"></div>';
			$o .= '</div>';
		} else {
			$num = range(0,(count($banners) - 1 ));
			shuffle($num);
			$numO = $num[0];
			$o .= '<div><a href="https://www.phoca.cz/'.$banners[$numO][1].'" target="_blank">'.Joomla\CMS\HTML\HTMLHelper::_('image',  'media/'.$option.'/images/administrator/b-'.$banners[$numO][2].'.png', ''). '</a></div>';

		}

		$o .= '<p>&nbsp;</p>';
		$o .= '<h4 style="margin-bottom:5px;">'.JText::_($oT.'_PLEASE_READ'). '</h4>';
		$o .= '<div><a style="text-decoration:underline" href="https://www.phoca.cz/phoca-needs-your-help/" target="_blank">'.JText::_($oT.'_PHOCA_NEEDS_YOUR_HELP'). '</a></div>';

		$o .= '</div>';
		return $o;
	}

	// TABS

	public function navigation($tabs, $activeTab = '') {

		if ($this->compatible) {
			return '';
		}

		$o = '<ul class="nav nav-tabs">';
		$i = 0;
		foreach($tabs as $k => $v) {
			$cA = 0;
			if ($activeTab != '') {
				if ($activeTab == $k) {
					$cA = 'class="active"';
				}
			} else {
				if ($i == 0) {
					$cA = 'class="active"';
				}
			}
			$o .= '<li '.$cA.'><a href="#'.$k.'" data-toggle="tab">'. $v.'</a></li>'."\n";
			$i++;
		}
		$o .= '</ul>';
		return $o;
	}


	public function startTabs($active = 'general') {
		if ($this->compatible) {
			return HTMLHelper::_('uitab.startTabSet', 'myTab', array('active' => $active));
		} else {
			return '<div class="tab-content">'. "\n";
		}
	}

	public function endTabs() {
		if ($this->compatible) {
			return HTMLHelper::_('uitab.endTabSet');
		} else {
			return '</div>';
		}
	}

	public function startTab($id, $name, $active = '') {
		if ($this->compatible) {
			return HTMLHelper::_('uitab.addTab', 'myTab', $id, $name);
		} else {
			return '<div class="tab-pane '.$active.'" id="'.$id.'">'."\n";
		}
	}

	public function endTab() {
		if ($this->compatible) {
			return HTMLHelper::_('uitab.endTab');
		} else {
			return '</div>';
		}
	}

}
?>
