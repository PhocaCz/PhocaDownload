<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
namespace Phoca\Component\Phocadownload\Administrator\Model;

defined('_JEXEC') or die();
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Phoca\Component\Phocadownload\Administrator\MVC\Model\AdminModelTrait;
/* jimport('joomla.application.component.model'); */

class LogModel extends BaseDatabaseModel
{
	use AdminModelTrait;

	function __construct() {
		parent::__construct();
	}
	function reset() {
		//$user 	= J$app->getIdentity();
		$query = 'TRUNCATE #__phocadownload_logging';
		$this->getDatabase()->setQuery( $query );
		if (!$this->getDatabase()->execute()) {
			throw new \Exception('Database error');
			return false;
		}
		return true;
	}
}
?>
