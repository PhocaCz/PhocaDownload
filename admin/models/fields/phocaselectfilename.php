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
defined('JPATH_BASE') or die;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
jimport('joomla.form.formfield');

class JFormFieldPhocaSelectFilename extends FormField
{
	public $type = 'PhocaSelectFilename';

	protected function getInput()
	{

		// Initialize variables.
		$html 		= array();

		$idA		= 'phFileNameModal';
		$onchange 	= (string) $this->element['onchange'];
		//$size     = ($v = $this->element['size']) ? ' size="' . $v . '"' : '';
		//$class    = ($v = $this->element['class']) ? ' class="' . $v . '"' : 'class="form-control"';
		$required = ($v = $this->element['required']) ? ' required="required"' : '';

		// Initialize some field attributes.
		$attr = $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : 'form-control';
		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';

		// Manager
		$manager		=	$this->element['manager'] ? $this->element['manager'] : '';
		$managerOutput	 =	$this->element['manager'] ? '&amp;manager='.(string) $this->element['manager'] : '';

		$idA .= 'mo' . $manager;
		$group = PhocaDownloadSettings::getManagerGroup((string) $this->element['manager']);
		$textButton	= 'COM_PHOCADOWNLOAD_FORM_SELECT_'.strtoupper($group['t']);

		$link = 'index.php?option=com_phocadownload&amp;view=phocadownloadmanager'.$group['c'].$managerOutput.'&amp;field='.$this->id;



		HTMLHelper::_('jquery.framework');

		$script = array();
		$script[] = '	function phocaSelectFileName_'.$this->id.'(title) {';
		$script[] = '		document.getElementById("'.$this->id.'").value = title;';
		$script[] = '		'.$onchange;
		//$script[] = '		jModalClose();';

		$script[] = '   jQuery(\'#'.$idA.'\').modal(\'toggle\');';

		//$script[] = '		SqueezeBox.close();';
		//$script[] = '		jQuery(\'#'.$idA.'\').modal(\'toggle\');';
		$script[] = '	}';

		// Add the script to the document head.
		Factory::getDocument()->addScriptDeclaration(implode("\n", $script));

		$html[] = '<div class="input-append input-group">';
        $html[] = '<span class="input-append input-group"><input type="text" id="' . $this->id . '" name="' . $this->name . '"'
            . ' value="' . $this->value . '"' . $attr . ' />';
        $html[] = '<a href="'.$link.'" role="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#'.$idA.'" title="' . Text::_($textButton) . '">'
            . '<span class="icon-list icon-white"></span> '
            . Text::_($textButton) . '</a></span>';
        $html[] = '</div>'. "\n";

        $html[] = HTMLHelper::_(
            'bootstrap.renderModal',
            $idA,
            array(
                'url'    => $link,
                'title'  => Text::_($textButton),
                'width'  => '',
                'height' => '',
                'modalWidth' => '80',
                'bodyHeight' => '80',
                'footer' => '<div  class="ph-info-modal"></div><button type="button" class="btn btn-primary" data-bs-dismiss="modal" aria-hidden="true">'
                    . Text::_('COM_PHOCADOWNLOAD_CLOSE') . '</button>'
            )
        );

        return implode("\n", $html);

		// Load the modal behavior script.
		//JHtml::_('behavior.modal', 'a.modal_'.$this->id);



		// Build the script.
	/*	$script = array();
		$script[] = '	function phocaSelectFileName_'.$this->id.'(title) {';
		$script[] = '		document.getElementById("'.$this->id.'_id").value = title;';
		$script[] = '		'.$onchange;
		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		// Add the script to the document head.
		Factory::getDocument()->addScriptDeclaration(implode("\n", $script));

*/
		/*$html[] = '<div class="fltlft">';
		$html[] = '	<input type="text" id="'.$this->id.'_id" name="'.$this->name.'" value="'. $this->value.'"' .
					' '.$attr.' />';
		$html[] = '</div>';

		// Create the user select button.
		$html[] = '<div class="button2-left">';
		$html[] = '  <div class="blank">';
		$html[] = '		<a class="modal_'.$this->id.'" title="'.Text::_($textButton).'"' .
							' href="'.($this->element['readonly'] ? '' : $link).'"' .
							' rel="{handler: \'iframe\', size: {x: 780, y: 560}}">';
		$html[] = '			'.Text::_($textButton).'</a>';
		$html[] = '  </div>';
		$html[] = '</div>';*/

	/*	Factory::getDocument()->addScriptDeclaration('
			function phocaSelectFileName_' . $this->id . '(name) {
				document.getElementById("' . $this->id . '").value = name;
				jQuery(\'#'.$idA.'\').modal(\'toggle\');
			}
		');*/

		/*$html[] = '<span class="input-append"><input type="text" ' . $required . ' id="' . $this->id . '" name="' . $this->name . '"'
			. ' value="' . $this->value . '"' . $size . $class . ' />';
		$html[] = '<a href="#'.$idA.'" role="button" class="btn btn-primary" data-toggle="modal" title="' . Text::_($textButton) . '">'
			. '<span class="icon-list icon-white"></span> '
			. Text::_($textButton) . '</a></span>';
		$html[] = HTMLHelper::_(
			'bootstrap.renderModal',
			$idA,
			array(
				'url'    => $link,
				'title'  => Text::_($textButton),
				'width'  => '700px',
				'height' => '400px',
				'modalWidth' => '80',
				'bodyHeight' => '70',
				'footer' => '<button type="button" class="btn btn-primary" data-bs-dismiss="modal" aria-hidden="true">'
					. Text::_('COM_PHOCADOWNLOAD_CLOSE') . '</button>'
			)
		);*/


	}
}
