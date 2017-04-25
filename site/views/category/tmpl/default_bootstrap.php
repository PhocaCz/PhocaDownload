<?php
defined('_JEXEC') or die('Restricted access'); 

echo '<div id="phoca-dl-category-box" class="pd-category-view'.$this->t['p']->get( 'pageclass_sfx' ).'">';

if ( $this->t['p']->get( 'show_page_heading' ) ) { 
	echo '<h1>'. $this->escape($this->t['p']->get('page_heading')) . '</h1>';
}
// Search by tags - the category rights must be checked for every file
$this->checkRights = 1;
// -------------------------------------------------------------------
if ((int)$this->t['tagid'] > 0) {
	
	echo $this->loadTemplate('files');
	$this->checkRights = 1;
	if (count($this->files)) {
		echo $this->loadTemplate('pagination');
	}
} else {
	if (!empty($this->category[0])) {
		echo '<div class="pd-category">';
		if ($this->t['display_up_icon'] == 1) {
			
			if (isset($this->category[0]->parentid)) {
				if ($this->category[0]->parentid == 0) {
					
					$linkUp = JRoute::_(PhocaDownloadRoute::getCategoriesRoute());
					$linkUpText = JText::_('COM_PHOCADOWNLOAD_CATEGORIES');
				} else if ($this->category[0]->parentid > 0) {
					$linkUp = JRoute::_(PhocaDownloadRoute::getCategoryRoute($this->category[0]->parentid, $this->category[0]->parentalias));
					$linkUpText = $this->category[0]->parenttitle;
				} else {
					$linkUp 	= '#';
					$linkUpText = ''; 
				}
			
					
				echo '<div class="ph-top">'
				.'<a class="btn btn-default" title="'.$linkUpText.'" href="'. $linkUp.'" ><span class="glyphicon glyphicon-arrow-left"></span> '
				. $linkUpText
				.'</a></div>';
			}
		}
	} else {
		echo '<div class="pd-category"><div class="pdtop"></div>';
	}

	if (!empty($this->category[0])) {
	
		// USER RIGHT - Access of categories (if file is included in some not accessed category) - - - - -
		// ACCESS is handled in SQL query, ACCESS USER ID is handled here (specific users)
		$rightDisplay	= 0;
		if (!empty($this->category[0])) {
			$rightDisplay = PhocaDownloadAccess::getUserRight('accessuserid', $this->category[0]->cataccessuserid, $this->category[0]->cataccess, $this->t['user']->getAuthorisedViewLevels(), $this->t['user']->get('id', 0), 0);
		}
		
			
		// - - - - - - - - - - - - - - - - - - - - - -
		if ($rightDisplay == 1) {
			$this->checkRights = 0;
			$l = new PhocaDownloadLayout();
			
			echo '<h3>'.$this->category[0]->title. '</h3>';

			// Description
			/*if ($l->isValueEditor($this->category[0]->description)) {
				echo '<div class="pd-cdesc">'.$this->category[0]->description.'</div>';
			}*/
			
			// Description
			 if ($l->isValueEditor($this->category[0]->description)) {
				echo '<div class="pd-cdesc">';
				echo JHTML::_('content.prepare', $this->category[0]->description);
				echo '</div>';
			 }

			// Subcategories
			
			if (!empty($this->subcategories)) {	
				foreach ($this->subcategories as $valueSubCat) {
					
					echo '<div class="pd-subcategory">';
					echo '<a href="'. JRoute::_(PhocaDownloadRoute::getCategoryRoute($valueSubCat->id, $valueSubCat->alias))
						 .'">'. $valueSubCat->title.'</a>';
					echo ' <small>('.$valueSubCat->numdoc.')</small></div>' . "\n";
					$subcategory = 1;
				}
				
				echo '<div class="pd-hr-cb"></div>';
			}
			
			// =====================================================================================		
			// BEGIN LAYOUT AREA
			// =====================================================================================

			echo $this->loadTemplate('files_bootstrap');

			// =====================================================================================		
			// END LAYOUT AREA
			// =====================================================================================
			
			if (count($this->category[0])) {
				echo $this->loadTemplate('pagination');
			}
			
			if ($this->t['display_category_comments'] == 1) {
				if (JComponentHelper::isEnabled('com_jcomments', true)) {
					include_once(JPATH_BASE.DS.'components'.DS.'com_jcomments'.DS.'jcomments.php');
					echo JComments::showComments($this->category[0]->id, 'com_phocadownload', JText::_('COM_PHOCADOWNLOAD_CATEGORY') .' '. $this->category[0]->title);
				}
			}
			
			if ($this->t['display_category_comments'] == 2) {
				echo '<div class="pd-fbcomments">'.$this->loadTemplate('comments-fb').'</div>';
			}
			
		} else {
			echo '<h3>'.JText::_('COM_PHOCADOWNLOAD_CATEGORY'). '</h3>';
			echo '<div class="alert alert-error alert-danger">'.JText::_('COM_PHOCADOWNLOAD_NO_RIGHTS_ACCESS_CATEGORY').'</div>';
		}
		
		echo '</div>';
	} else {
		//echo '<h3>&nbsp;</h3>';
		echo '</div>';
	}
}

echo $this->t['bootstrapmodal'];
echo '</div><div class="pd-cb">&nbsp;</div>';
echo $this->t['afd'];
?>
