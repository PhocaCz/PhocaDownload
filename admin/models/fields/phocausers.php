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
defined('_JEXEC') or die();

use Joomla\CMS\Form\FormField;
use Joomla\CMS\Form\Field\ListField;
phocadownloadimport('phocadownload.user.user');


class JFormFieldPhocaUsers extends ListField
{
	protected $type 		= 'PhocaUsers';

	protected function getInput() {

        $data = $this->getLayoutData();

        $userId	= (string) $this->form->getValue($this->element['name']);



		$data['options'] = (array) PhocaDownloadUser::usersList($this->name, $this->id, $userId, 1, NULL,'name', 0, 1 );

		$activeArray = $userId;
		if ($userId != '') {
			$activeArray = explode(',',$userId);
		}
		if (!empty($activeArray)) {
			$data['value'] = $activeArray;
		} else {
			$data['value'] = $this->value;
		}

		return $this->getRenderer($this->layout)->render($data);

	}
}
?>
