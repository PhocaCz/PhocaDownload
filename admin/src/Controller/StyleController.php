<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
namespace Phoca\Component\Phocadownload\Administrator\Controller;

defined('_JEXEC') or die();
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Factory;
/* jimport('joomla.application.component.controllerform'); */

class StyleController extends FormController
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
