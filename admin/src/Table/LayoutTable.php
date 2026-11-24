<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
namespace Phoca\Component\Phocadownload\Administrator\Table;

defined('_JEXEC') or die('Restricted access');
use Joomla\Event\DispatcherInterface;
use Joomla\Database\DatabaseInterface;
use Joomla\CMS\Table\Table;
/* jimport('joomla.filter.input'); */

class LayoutTable extends Table
{
	public function __construct(DatabaseInterface $db, ?DispatcherInterface $dispatcher = null) {
		parent::__construct('#__phocadownload_layout', 'id', $db, $dispatcher);
	}
	
}
?>