<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

namespace Phoca\PhocaDownload\MVC\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\Database\DatabaseInterface;
use Joomla\Database\QueryInterface;
use Joomla\Event\DispatcherInterface;

trait AdminModelTrait
{
    public function getDispatcher()
    {


        if (!$this->dispatcher) {
            return Factory::getContainer()->get(DispatcherInterface::class);
        }
        return $this->dispatcher;
    }
}
