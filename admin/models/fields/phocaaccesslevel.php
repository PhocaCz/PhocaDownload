<?php
/**
 * @version		$Id: accesslevel.php 20196 2011-01-09 02:40:25Z ian $
 * @package		Joomla.Framework
 * @subpackage	Form
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the Joomla Framework.
 *
 * @package		Joomla.Framework
 * @subpackage	Form
 * @since		1.6
 */
class JFormFieldPhocaAccessLevel extends ListField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	public $type = 'AccessLevel';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		// Initialize variables.
		$attr = '';

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$attr .= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		$attr .= $this->multiple ? ' multiple="multiple"' : '';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';

		// Get the field options.
		$options = $this->getOptions();


		//return $this->_level( $this->name, $this->value, $attr, $options, $this->id);
        $data = $this->getLayoutData();
		$data['options'] = (array) $this->_level( $this->name, $this->value, $attr, $options, $this->id);
		return $this->getRenderer($this->layout)->render($data);
	}


	public static function _level($name, $selected, $attribs = '', $params = true, $id = false) {

		$db		= Factory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('a.id AS value, a.title AS text');
		$query->from('#__viewlevels AS a');
		//PHOCAEDIT
		$query->where('a.id <> 1');
		//ENDPHOCAEDIT
		$query->group('a.id, a.title');
		$query->order('a.ordering ASC');
		$query->order('`title` ASC');



		// Check for a database error.
	/*	if ($db->getErrorNum()) {
			throw new Exception($db->getErrorMsg(), 500);
			return false;
		}*/
		try {
			// Get the options.
			$db->setQuery($query);
			$options = $db->loadObjectList();
		} catch (RuntimeException $e) {

			throw new Exception($e->getMessage(), 500);
			return false;
		}

		// If params is an array, push these options to the array
		if (is_array($params)) {
			$options = array_merge($params,$options);
		}
		// If all levels is allowed, push it into the array.
		elseif ($params) {
			array_unshift($options, HTMLHelper::_('select.option', '', Text::_('JOPTION_ACCESS_SHOW_ALL_LEVELS')));
		}

		return $options;
		/*
		return HTMLHelper::_('select.genericlist', $options, $name,
			array(
				'list.attr' => $attribs,
				'list.select' => $selected,
				'id' => $id
			)
		);*/
	}
}
