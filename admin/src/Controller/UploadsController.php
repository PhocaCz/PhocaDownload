<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
namespace Phoca\Component\Phocadownload\Administrator\Controller;

defined('_JEXEC') or die();
use Joomla\CMS\Language\Text;

class UploadsController extends CpController
{
// 	function __construct() {
// 		parent::__construct();
// 		$this->registerTask( 'approveall', 'approveall');
// 	}

	function approveall() {

		$model = $this->getModel('Upload');
		if(!$model->approveall()) {
			$msg = Text::_( 'COM_PHOCADOWNLOAD_ERROR_APPROVE_ALL' );
		} else {
			$msg = Text::_( 'COM_PHOCADOWNLOAD_ALL_APPROVED' );
		}

		$this->setRedirect( 'index.php?option=com_phocadownload&view=uploads' , $msg);
	}
}
?>
