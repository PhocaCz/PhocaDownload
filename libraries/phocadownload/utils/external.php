<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
class PhocaDownloadExternal
{
	public static function checkOSE($fileName) {
		if (file_exists(JPATH_SITE.DS.'components'.DS.'com_osemsc'.DS.'init.php') 
		&& file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ose_cpu'.DS.'define.php')) {
            require_once(JPATH_SITE.DS.'components'.DS.'com_osemsc'.DS.'init.php');
            oseRegistry :: call('content')->checkAccess('phoca', 'category', $fileName->catid);
        } else if (file_exists(JPATH_ADMINISTRATOR . DS . "components" . DS . "com_osemsc" . DS . "warehouse" . DS . "api.php")) {
            require_once (JPATH_ADMINISTRATOR . DS . "components" . DS . "com_osemsc" . DS . "warehouse" . DS . "api.php");
            $checkmsc = new OSEMSCAPI();
            $checkmsc->ACLCheck("phoca", "cat", $fileName->catid, true);
        }
	}
}
?>