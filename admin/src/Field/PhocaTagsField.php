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
namespace Phoca\Component\Phocadownload\Administrator\Field;
use Phoca\Component\Phocadownload\Administrator\Tag\Tag;

defined('_JEXEC') or die();
use Joomla\CMS\Form\FormField;



class PhocaTagsField extends FormField
{
	protected $type 		= 'PhocaTags';

	protected function getInput() {

        $id = (int) $this->form->getValue('id');

		$activeTags = [];
		if ((int)$id > 0) {
			$activeTags	= Tag::getTags($id, 1);
		}
		//return PhocaGalleryTag::getAllTagsSelectBox($this->name, $this->id, $activeTags, NULL,'id' );


		$tags 				= Tag::getAllTags();
		$data               = $this->getLayoutData();
		$data['options']    = (array)$tags;
		$data['value']      = $activeTags;

		return $this->getRenderer($this->layout)->render($data);


	}
}
?>
