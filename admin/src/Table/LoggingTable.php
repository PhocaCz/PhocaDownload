<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
namespace Phoca\Component\Phocadownload\Administrator\Table;

defined('_JEXEC') or die('Restricted access');
use Joomla\Event\DispatcherInterface;
use Joomla\Database\DatabaseInterface;
use Joomla\CMS\Table\Table;
/* jimport('joomla.filter.input'); */

class LoggingTable extends Table
{
	public function __construct(DatabaseInterface $db, ?DispatcherInterface $dispatcher = null) {
		parent::__construct('#__phocadownload_logging', 'id', $db, $dispatcher);
	}
}
?>