<?php
/*
* @package      Joomla.Framework
* @copyright   Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
* @license      GNU General Public License version 2 or later; see LICENSE.txt
*
* @component Phoca Component
* @copyright Copyright (C) Jan Pavelka www.phoca.cz
* @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
*/
defined('_JEXEC') or die();
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

class JFormFieldPhocaDownloadFile extends FormField
{
   protected $type = 'PhocaDownloadFile';

   protected function getInput() {

      $db = Factory::getDBO();

       //build the list of files
      $query = 'SELECT a.title , a.id , a.catid'
      . ' FROM #__phocadownload AS a'
      . ' WHERE a.published = 1'
      . ' ORDER BY a.ordering';
      $db->setQuery( $query );

      $messages = $db->loadObjectList();
      $options = array();
      if ($messages)
      {
         foreach($messages as $message)
         {
            $options[] = HTMLHelper::_('select.option', $message->id, $message->title);
         }
      }

	  $attr = '';
		$attr .= $this->required ? ' required aria-required="true"' : '';
		$attr .= ' class="form-control"';

      array_unshift($options, HTMLHelper::_('select.option', '', '- '.Text::_('COM_PHOCADOWNLOAD_SELECT_FILE').' -', 'value', 'text'));
      return HTMLHelper::_('select.genericlist',  $options,  $this->name, trim($attr), 'value', 'text', $this->value, $this->id );

   }
}
?>
