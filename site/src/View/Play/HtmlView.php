<?php
/*
 * @package Joomla
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

namespace Phoca\Component\Phocadownload\Site\View\Play;
defined('_JEXEC') or die();
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Language\Text;
use Phoca\Component\Phocadownload\Administrator\File\File;
use Phoca\Component\Phocadownload\Administrator\Path\Path;
//jimport( 'joomla.application.component.view');

class HtmlView extends BaseHtmlView
{

	public function display($tpl = null){

		/** @var CMSWebApplicationInterface $app */
		$app			= Factory::getApplication();
		$params 		= \Joomla\CMS\Component\ComponentHelper::getParams('com_phocadownload');		$this->t			= [];
		$this->t['user'] 	= $app->getIdentity();
		$uri 			= Uri::getInstance();
		$model			= $this->getModel();
		$document		= $app->getDocument();
		$fileId			= $app->getInput()->get('id', 0, 'int');
		$file			= $model->getFile($fileId);

		$fileExt		= '';

		$filePath	= Path::getPathSet('fileplay');
		$filePath	= str_replace ( '../', Uri::base(false).'', $filePath['orig_rel_ds']);
		if (isset($file[0]->filename_play) && $file[0]->filename_play != '') {

			$fileExt = File::getExtension($file[0]->filename_play);
			$canPlay	= File::canPlay($file[0]->filename_play);
			if ($canPlay) {
				$this->t['playfilewithpath']	= $filePath . $file[0]->filename_play;
				//$this->t['playerpath']		= JUri::base().'components/com_phocadownload/assets/jwplayer/';
				//$this->t['playerpath']			= Uri::base().'media/com_phocadownload/js/flowplayer/';
				$this->t['playerwidth']			= $params->get( 'player_width', 328 );
				$this->t['playerheight']		= $params->get( 'player_height', 200 );
				$this->t['html5_play']			= 1;//$params->get( 'html5_play', 1 );
			} else {
				echo Text::_('COM_PHOCADOWNLOAD_ERROR_NO_CORRECT_FILE_TO_PLAY_FOUND');exit;
			}
		} else {
			echo Text::_('COM_PHOCADOWNLOAD_ERROR_NO_FILE_TO_PLAY_FOUND');exit;
		}

		$this->t['filetype']	= $fileExt;
		if ($fileExt == 'mp3') {
			$this->t['filetype'] 		= 'mp3';
			$this->t['playerheight']	= $params->get( 'player_mp3_height', 30 );
		} else if ($fileExt == 'ogg') {
			$this->t['filetype'] 		= 'ogg';
			$this->t['playerheight']	= $params->get( 'player_mp3_height', 30 );
		}


        $this->t['file'] = $file;
		//$this->assignRef('file',			$file);
		//$this->assignRef('tmpl',			$this->t);
		//$this->assignRef('params',			$params);
		//$uriT = $uri->toString();
		//$this->assignRef('request_url',		$uriT);
		parent::display($tpl);
	}
}
?>
