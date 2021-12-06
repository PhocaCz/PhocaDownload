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
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

class PhocaDownloadCpControllerPhocaDownloadset extends PhocaDownloadCpController
{
	function __construct() {
		parent::__construct();
		
		$this->registerTask( 'apply'  , 'save' );
	}

	function save() {
		$post					= Factory::getApplication()->input->get('post');
		$phocaSet				= Factory::getApplication()->input->get( 'phocaset', array(0), 'post', 'array' );

		$model = $this->getModel( 'phocadownloadset' );
		$errorMsg = '';
		switch ( Factory::getApplication()->input->getCmd('task') ) {
			case 'apply':
				
				if ($model->store($phocaSet, $errorMsg)) {
					$msg = Text::_( 'Changes to Phoca Download Settings Saved' );
					if ($errorMsg != '') {
						$msg .= '<br />'.Text::_($errorMsg);
					}
				} else {
					$msg = Text::_( 'Error Saving Phoca Download Settings' );
				}
				$this->setRedirect( 'index.php?option=com_phocadownload&view=phocadownloadset', $msg );
				break;

			case 'save':
			default:
				if ($model->store($phocaSet, $errorMsg)) {
					$msg = Text::_( 'Phoca Download Settings Saved' );
					if ($errorMsg != '') {
						$msg .= '<br />'.Text::_($errorMsg);
					}
				} else {
					$msg = Text::_( 'Error Saving Phoca Download Settings' );
				}
				$this->setRedirect( 'index.php?option=com_phocadownload', $msg );
				break;
		}
		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
	}
	
	
	function cancel($key = NULL) {
		$model = $this->getModel( 'phocadownload' );
		$model->checkin();

		$this->setRedirect( 'index.php?option=com_phocadownload' );
	}
}
?>
