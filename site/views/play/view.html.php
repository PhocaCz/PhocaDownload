<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Language\Text;
jimport( 'joomla.application.component.view');

class PhocaDownloadViewPlay extends HtmlView
{

	function display($tpl = null){


		$app			= Factory::getApplication();
		$params 		= $app->getParams();
		$this->t			= array();
		$this->t['user'] 	= Factory::getUser();
		$uri 			= Uri::getInstance();
		$model			= $this->getModel();
		$document		= Factory::getDocument();
		$fileId			= $app->input->get('id', 0, 'int');
		$file			= $model->getFile($fileId);

		$fileExt		= '';

		$filePath	= PhocaDownloadPath::getPathSet('fileplay');
		$filePath	= str_replace ( '../', Uri::base(false).'', $filePath['orig_rel_ds']);
		if (isset($file[0]->filename_play) && $file[0]->filename_play != '') {

			$fileExt = PhocaDownloadFile::getExtension($file[0]->filename_play);
			$canPlay	= PhocaDownloadFile::canPlay($file[0]->filename_play);
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
