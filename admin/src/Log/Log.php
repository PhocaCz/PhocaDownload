<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
namespace Phoca\Component\Phocadownload\Administrator\Log;

defined( '_JEXEC' ) or die( 'Restricted access' );
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Table\Table;
use Phoca\Component\Phocadownload\Administrator\Utils\Utils;

class Log
{

	public static function log($fileid, $type = 1) {

		$paramsC 	= ComponentHelper::getParams('com_phocadownload');
		$logging	= $paramsC->get('enable_logging', 0);
		// No Logging
		if ($logging == 0) {
			return false;
		}

		// Only Downloads
		if ($logging == 1 && $type == 2) {
			return false;
		}

		// Only Uploads
		if ($logging == 2 && $type == 1) {
			return false;
		}

		/** @var CMSWebApplicationInterface $app */
		$app = Factory::getApplication();
		$user 	= $app->getIdentity();
		$uri 	= Uri::getInstance();
		$db 	= Factory::getContainer()->get(\Joomla\Database\DatabaseInterface::class);

		$row 	= Factory::getApplication()->bootComponent('com_phocadownload')->getMVCFactory()->createTable('Logging', 'Administrator');
		$data					= [];
		$data['type']			= (int)$type;
		$data['fileid']			= (int)$fileid;
		$data['catid']			= 0;// Don't stored catid, bind the catid while displaying log
		$data['userid']			= (int)$user->id;
		$data['ip']	=			Utils::getIp();
		$data['page']			= $uri->toString();
		$data['params']			= '';

		if (!$row->bind($data)) {
			throw new \Exception($row->getError());
			return false;
		}

		$jnow		= Factory::getDate();
		$row->date	= $jnow->toSql();

		if (!$row->check()) {
			throw new \Exception($row->getError());
			return false;
		}

		if (!$row->store()) {
			throw new \Exception($row->getError());
			return false;
		}
		return true;
	}
}
?>
