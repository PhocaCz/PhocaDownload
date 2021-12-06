<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\HTML\HTMLHelper;
jimport( 'joomla.application.component.view' );
phocadownloadimport('phocadownload.render.renderadminviews');
 
class phocaDownloadViewphocaDownloadLinks extends HtmlView
{
	protected $t;
	
	function display($tpl = null) {
		$app	= Factory::getApplication();

		
		//Frontend Changes
		$tUri = '';
		if (!$app->isClient('administrator')) {
			$tUri = Uri::base();
			
		}
		$this->r = new PhocaDownloadRenderAdminViews();
		$this->t = PhocaDownloadUtils::setVars('links');
		$document	= Factory::getDocument();
		$uri		= Uri::getInstance();
		HTMLHelper::stylesheet( 'media/com_phocadownload/css/administrator/phocadownload.css' );
		
		$eName	= $app->input->get('e_name');
		$eName	= preg_replace( '#[^A-Z0-9\-\_\[\]]#i', '', $eName );
		
		
		$this->t['linkcategories']	= $tUri.'index.php?option=com_phocadownload&amp;view=phocadownloadlinkcats&amp;tmpl=component&amp;e_name='.$eName;
		$this->t['linkcategory']	= $tUri.'index.php?option=com_phocadownload&amp;view=phocadownloadlinkcat&amp;tmpl=component&amp;e_name='.$eName;
		$this->t['linkfile']		= $tUri.'index.php?option=com_phocadownload&amp;view=phocadownloadlinkfile&amp;tmpl=component&amp;e_name='.$eName;
		$this->t['linkytb']		= $tUri.'index.php?option=com_phocadownload&amp;view=phocadownloadlinkytb&amp;tmpl=component&amp;e_name='.$eName;
		
		
		parent::display($tpl);
	}
}
?>