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

class LogsController extends CpController
{
// 	function __construct() {
// 		parent::__construct();
// 		$this->registerTask( 'reset', 'reset');
// 	}

	function reset() {

		$model = $this->getModel( 'Log' );

		if ($model->reset()) {
			$msg = Text::_( 'COM_PHOCADOWNLOAD_SUCCESS_RESET_LOG_STAT' );
		} else {
			$msg = Text::_( 'COM_PHOCADOWNLOAD_ERROR_RESET_LOG_STAT' );
		}

		$link = 'index.php?option=com_phocadownload&view=logs';
		$this->setRedirect($link, $msg);
	}
}
?>
