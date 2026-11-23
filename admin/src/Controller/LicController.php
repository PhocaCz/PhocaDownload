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
namespace Phoca\Component\Phocadownload\Administrator\Controller;

defined('_JEXEC') or die();
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Factory;
/* jimport('joomla.application.component.controllerform'); */

class LicController extends FormController
{
	protected	$option 		= 'com_phocadownload';
	
// 	function __construct($config=array()) {
// 		parent::__construct($config);
// 	}
	
	protected function allowAdd($data = array()) {
		$app = Factory::getApplication();
		$user		= $app->getIdentity();
		$allow		= null;
		$allow	= $user->authorise('core.create', 'com_phocadownload');
		if ($allow === null) {
			return parent::allowAdd($data);
		} else {
			return $allow;
		}
	}

	protected function allowEdit($data = array(), $key = 'id') {
		$app = Factory::getApplication();
		$user		= $app->getIdentity();
		$allow		= null;
		$allow	= $user->authorise('core.edit', 'com_phocadownload');
		if ($allow === null) {
			return parent::allowEdit($data, $key);
		} else {
			return $allow;
		}
	}
	
}
?>
