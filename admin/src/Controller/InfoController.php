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

// Check to ensure this file is included in Joomla!
namespace Phoca\Component\Phocadownload\Administrator\Controller;

defined('_JEXEC') or die();

class InfoController extends CpController
{
// 	function __construct() {
// 		parent::__construct();
// 	}
	
	function cancel($key = NULL) {
		$this->setRedirect( 'index.php?option=com_phocadownload' );
	}
}
?>
