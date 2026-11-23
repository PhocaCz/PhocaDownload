<?php
/*
 * @package Joomla
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
namespace Phoca\Component\Phocadownload\Administrator\Utils;

defined( '_JEXEC' ) or die( 'Restricted access' );
use Joomla\CMS\Language\Text;

class Exception
{

	public static function renderErrorInfo ($msg, $jText = false){

		if ($jText) {
			return '<div class="alert alert-danger pg-error-info">'.Text::_($msg).'</div>';
		} else {
			return '<div class="alert alert-danger pg-error-info">'.$msg.'</div>';
		}
	}
}
?>
