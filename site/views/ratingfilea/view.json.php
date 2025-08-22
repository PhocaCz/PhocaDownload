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
use Joomla\CMS\Session\Session;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
jimport( 'joomla.application.component.view');

class PhocaDownloadViewRatingFileA extends HtmlView
{

	function display($tpl = null){
		
		if (!Session::checkToken('request')) {
			$response = array(
				'status' => '0',
				'error' => Text::_('JINVALID_TOKEN')
			);
			echo json_encode($response);
			return;
		}
	
		$app	= Factory::getApplication();
		$params			= $app->getParams();
		
		
		$ratingVote 	= $app->getInput()->get( 'ratingVote', 0, 'post', 'int'  );
		$ratingId 		= $app->getInput()->get( 'ratingId', 0, 'post', 'int'  );// ID of File
		$format 		= $app->getInput()->get( 'format', '', 'post', 'string'  );
		$task 			= $app->getInput()->get( 'task', '', 'get', 'string'  );
		$view 			= $app->getInput()->get( 'view', '', 'get', 'string'  );
		$small			= $app->getInput()->get( 'small', 1, 'get', 'string'  );//small or large rating icons
		
		$paramsC 		= ComponentHelper::getParams('com_phocadownload');
		$param['displayratingfile'] = $paramsC->get( 'display_rating_file', 0 );
		
		// Check if rating is enabled - if not then user should not be able to rate or to see updated reating
		
		
		
		if ($task == 'refreshrate' && (int)$param['displayratingfile'] > 0) {			
			$ratingOutput 		= PhocaDownloadRate::renderRateFile((int)$ratingId, 1, $small, true);// ID of File
			$response = array(
					'status' => '0',
					'message' => $ratingOutput);
				echo json_encode($response);
				return;
			//return $ratingOutput;
			
		} else if ($task == 'rate') {
		
			$user 		= Factory::getUser();
			//$view 		= $app->getInput()->get( 'view', '', 'get', '', J R EQUEST_NOTRIM  );
			//$Itemid		= $app->getInput()->get( 'Itemid', 0, 'int');
		
			$neededAccessLevels	= PhocaDownloadAccess::getNeededAccessLevels();
			$access				= PhocaDownloadAccess::isAccess($user->getAuthorisedViewLevels(), $neededAccessLevels);
		
			
			$post['fileid'] 	= (int)$ratingId;
			$post['userid']		= $user->id;
			$post['rating']		= (int)$ratingVote;

			
			if ($format != 'json') {
				$msg = Text::_('COM_PHOCADOWNLOAD_ERROR_WRONG_RATING') ;
				$response = array(
					'status' => '0',
					'error' => $msg);
				echo json_encode($response);
				return;
			}
			
			if ((int)$post['fileid'] < 1) {
				$msg = Text::_('COM_PHOCADOWNLOAD_ERROR_FILE_NOT_EXISTS');
				$response = array(
					'status' => '0',
					'error' => $msg);
				echo json_encode($response);
				return;
			}
			
			$model = $this->getModel();
			
			$checkUserVote	= PhocaDownloadRate::checkUserVoteFile( $post['fileid'], $post['userid'] );
			
			// User has already rated this category
			if ($checkUserVote) {
				$msg = Text::_('COM_PHOCADOWNLOAD_RATING_ALREADY_RATED_FILE');
				$response = array(
					'status' => '0',
					'error' => '',
					'message' => $msg);
				echo json_encode($response);
				return;
			} else {
				if ((int)$post['rating']  < 1 || (int)$post['rating'] > 5) {
					
					$msg = Text::_('COM_PHOCADOWNLOAD_ERROR_WRONG_RATING');
					$response = array(
					'status' => '0',
					'error' => $msg);
					echo json_encode($response);
					return;
				}
				
				if ($access > 0 && $user->id > 0) {
					if(!$model->rate($post)) {
						$msg = Text::_('COM_PHOCADOWNLOAD_ERROR_RATING_FILE');
						$response = array(
						'status' => '0',
						'error' => $msg);
						echo json_encode($response);
						return;
					} else {
						$msg = Text::_('COM_PHOCADOWNLOAD_SUCCESS_RATING_FILE');
						$response = array(
						'status' => '1',
						'error' => '',
						'message' => $msg);
						echo json_encode($response);
						return;
					} 
				} else {
					$msg = Text::_('COM_PHOCADOWNLOAD_NOT_AUTHORISED_ACTION');
						$response = array(
						'status' => '0',
						'error' => $msg);
						echo json_encode($response);
						return;
				}
			}
		} else {
			$msg = Text::_('COM_PHOCADOWNLOAD_NOT_AUTHORISED_ACTION');
			$response = array(
			'status' => '0',
			'error' => $msg);
			echo json_encode($response);
			return;
		}
	}
}
?>
