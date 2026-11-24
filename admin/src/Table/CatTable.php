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
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Phoca\Component\Phocadownload\Administrator\Utils\Utils;
/* jimport('joomla.filter.input'); */

class CatTable extends Table
{

	public function __construct(DatabaseInterface $db, ?DispatcherInterface $dispatcher = null) {
		parent::__construct('#__phocadownload_categories', 'id', $db, $dispatcher);
	}

	function check()
	{

		$app = Factory::getApplication();
		// check for valid name
		if (trim( $this->title ) == '') {


			$app->enqueueMessage(Text::_( 'CATEGORY MUST HAVE A TITLE') , 'error');
			return false;
		}

		if(empty($this->alias)) {
			$this->alias = $this->title;
		}
		$this->alias = Utils::getAliasName($this->alias);

		return true;
	}
}
?>
