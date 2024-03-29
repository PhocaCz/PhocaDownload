<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\Text;

class PhocaDownloadRenderFront
{

    public static function renderMainJs() {

        $app    = Factory::getApplication();
        $doc    = $app->getDocument();

        $oVars   = array();
        $oLang   = array();
        $oParams = array();
       /* $oLang   = array(
            'COM_PHOCAGALLERY_MAX_LIMIT_CHARS_REACHED' => Text::_('COM_PHOCAGALLERY_MAX_LIMIT_CHARS_REACHED'),
            'COM_PHOCAGALLERY_ENTER_TITLE' => Text::_('COM_PHOCAGALLERY_ENTER_TITLE'),
            'COM_PHOCAGALLERY_ENTER_COMMENT' => Text::_('COM_PHOCAGALLERY_ENTER_COMMENT')

        );*/

       /// $doc->addScriptOptions('phLangPG', $oLang);
        //$doc->addScriptOptions('phVarsPG', $oVars);
        //$doc->addScriptOptions('phParamsPG', $oParams);


        HTMLHelper::_('script', 'media/com_phocadownload/js/main.js', array('version' => 'auto'));
        Factory::getApplication()
			->getDocument()
			->getWebAssetManager()
			->useScript('bootstrap.modal');
    }

	public static function renderAllCSS($noBootStrap = 0) {
		$app	= Factory::getApplication();
		$itemid	= $app->input->get('Itemid', 0, 'int');
		$db 	= Factory::getDBO();
		$query = 'SELECT a.filename as filename, a.type as type, a.menulink as menulink'
				.' FROM #__phocadownload_styles AS a'
				.' WHERE a.published = 1'
			    .' ORDER BY a.type, a.ordering ASC';
		$db->setQuery($query);
		$filenames = $db->loadObjectList();
		if (!empty($filenames)) {
			foreach ($filenames as $fk => $fv) {

				$path = PhocaDownloadFile::getCSSPath($fv->type, 1);

				if ($fv->menulink != '' && (int)$fv->menulink > 1) {

					$menuLinks 	= explode(',', $fv->menulink);
					$isIncluded	= in_array((int)$itemid, $menuLinks);
					if ($isIncluded) {
						HTMLHelper::stylesheet($path . $fv->filename );
					}
				} else {
					HTMLHelper::stylesheet($path . $fv->filename );
				}
			}
		}
	}

	public static function displayMirrorLinks($view = 1, $link = '', $title = '', $target = '') {

		$paramsC							= ComponentHelper::getParams( 'com_phocadownload' );
		$param['display_mirror_links']		= $paramsC->get( 'display_mirror_links', 0 );
		$o = '';

		$displayM = 0;
		if ($view == 1) {
			//Category View
			if ($param['display_mirror_links'] == 1 || $param['display_mirror_links'] == 3
				|| $param['display_mirror_links'] == 4 || $param['display_mirror_links'] == 6) {
				$displayM = 1;
			}

		} else {
			//File View
			if ($param['display_mirror_links'] == 2 || $param['display_mirror_links'] == 3
			|| $param['display_mirror_links'] == 5 || $param['display_mirror_links'] == 6) {
				$displayM = 1;
			}
		}



		if ($displayM == 1 && $link != '' && PhocaDownloadUtils::isURLAddress($link) && $title != '') {

			$targetO = '';
			if ($target != '') {
				$targetO = 'target="'.$target.'"';
			}
			$o .= '<a class="" href="'.$link.'" '.$targetO.'>'.strip_tags($title).'</a>';

		}

		return $o;
	}

	public static function displayReportLink($view = 1, $title = '') {

		$paramsC								= ComponentHelper::getParams( 'com_phocadownload' );
		$param['display_report_link']			= $paramsC->get( 'display_report_link', 0 );
		$param['report_link_guestbook_id']		= $paramsC->get( 'report_link_guestbook_id', 0 );
		$o = '';

		$displayL = 0;
		if ($view == 1) {
			//Category View
			if ($param['display_report_link'] == 1 || $param['display_report_link'] == 3) {
				$displayL = 1;
			}

		} else {
			//File View
			if ($param['display_report_link'] == 2 || $param['display_report_link'] == 3) {
				$displayL = 1;
			}
		}

		if ($displayL == 1 && (int)$param['report_link_guestbook_id'] > 0) {

			$onclick = "window.open(this.href,'win2','width=600,height=500,scrollbars=yes,menubar=no,resizable=yes'); return false;";
			//$href	= JRoute::_('index.php?option=com_phocaguestbook&view=guestbook&id='.(int)$param['report_link_guestbook_id'].'&reporttitle='.strip_tags($title).'&tmpl=component&Itemid='. JFactory::getApplication()->input->get('Itemid', 0, '', 'int') );

			$href	= PhocaDownloadRoute::getGuestbookRoute((int)$param['report_link_guestbook_id'],urlencode(strip_tags($title) ));
			//$href	= JRoute::_('index.php?option=com_phocaguestbook&view=guestbook&id='.(int)$param['report_link_guestbook_id'].'&reporttitle='.strip_tags($title).'&tmpl=component');


			$o .= '<a href="'.$href.'#pgbTabForm" onclick="'.$onclick.'">'.Text::_('COM_PHOCADOWNLOAD_REPORT').'</a>';

		}

		return $o;
	}

	public static function displayNewIcon ($date, $time = 0) {

		if ($time == 0) {
			return '';
		}

		$dateAdded 	= strtotime($date, time());
		$dateToday 	= time();
		$dateExists = $dateToday - $dateAdded;
		$dateNew	= $time * 24 * 60 * 60;


		if ($dateExists < $dateNew) {
			//return '&nbsp;'. JHtml::_('image', 'media/com_phocadownload/images/icon-new.png', JText::_('COM_PHOCADOWNLOAD_NEW'));
			return '&nbsp;<span class="label label-warning badge bg-warning">'.Text::_('COM_PHOCADOWNLOAD_LABEL_TXT_NEW').'</span>';
		} else {
			return '';
		}

	}

	public static function displayHotIcon ($hits, $requiredHits = 0) {

		if ($requiredHits == 0) {
			return '';
		}

		if ($requiredHits <= $hits) {
			//return '&nbsp;'. JHtml::_('image', 'media/com_phocadownload/images/icon-hot.png', JText::_('COM_PHOCADOWNLOAD_HOT'));
			return '&nbsp;<span class="label label-important label-danger badge bg-danger">'.Text::_('COM_PHOCADOWNLOAD_LABEL_TXT_HOT').'</span>';
		} else {
			return '';
		}
	}

	public static function renderOnUploadJS() {

		$tag = "<script type=\"text/javascript\"> \n"
		. "function OnUploadSubmitFile() { \n"
		. "if ( document.getElementById('catid').value < 1 ) { \n"
	    . "alert('".Text::_('COM_PHOCADOWNLOAD_PLEASE_SELECT_CATEGORY')."'); \n"
		. "return false; \n"
		. "} \n"
		. "document.getElementById('loading-label-file').style.display='block'; \n"
		. "return true; \n"
		. "} \n"
		. "</script>";

		return $tag;
	}

	public static function renderDescriptionUploadJS($chars) {

		$tag = "<script type=\"text/javascript\"> \n"
		."function countCharsUpload() {" . "\n"
		."var maxCount	= ".$chars.";" . "\n"
		."var pdu 			= document.getElementById('phocadownload-upload-form');" . "\n"
		."var charIn		= pdu.phocadownloaduploaddescription.value.length;" . "\n"
		."var charLeft	= maxCount - charIn;" . "\n"
		."" . "\n"
		."if (charLeft < 0) {" . "\n"
		."   alert('".Text::_('COM_PHOCADOWNLOAD_MAX_LIMIT_CHARS_REACHED')."');" . "\n"
		."   pdu.phocadownloaduploaddescription.value = pdu.phocadownloaduploaddescription.value.substring(0, maxCount);" . "\n"
		."	charIn	 = maxCount;" . "\n"
		."  charLeft = 0;" . "\n"
		."}" . "\n"
		."pdu.phocadownloaduploadcountin.value	= charIn;" . "\n"
		."pdu.phocadownloaduploadcountleft.value	= charLeft;" . "\n"
		."}" . "\n"
		. "</script>";

		return $tag;
	}

	public static function userTabOrdering() {
		$js  = "\t". '<script language="javascript" type="text/javascript">'."\n"
			 . 'function tableOrdering( order, dir, task )' . "\n"
			 . '{ ' . "\n"
			 . "\t".'var form = document.phocadownloadfilesform;' . "\n"
			 . "\t".'form.filter_order.value 		= order;' . "\n"
			 . "\t".'form.filter_order_Dir.value	= dir;' . "\n"
			 . "\t".'document.phocadownloadfilesform.submit();' . "\n"
			 . '}'. "\n"
			 . '</script>' . "\n";

		return $js;
	}

	public static function renderOverlibCSS($ol_fg_color, $ol_bg_color, $ol_tf_color, $ol_cf_color, $opacity = 0.8) {

		$opacityPer = (float)$opacity * 100;

		$css = "<style type=\"text/css\">\n"

		. ".bgPhocaClass{
			background:".$ol_bg_color.";
			filter:alpha(opacity=".$opacityPer.");
			opacity: ".$opacity.";
			-moz-opacity:".$opacity.";
			z-index:1000;
			}
			.fgPhocaClass{
			background:".$ol_fg_color.";
			filter:alpha(opacity=100);
			opacity: 1;
			-moz-opacity:1;
			z-index:1000;
			}
			.fontPhocaClass{
			color:".$ol_tf_color.";
			z-index:1001;
			}
			.capfontPhocaClass, .capfontclosePhocaClass{
			color:".$ol_cf_color.";
			font-weight:bold;
			z-index:1001;
			}"
		." </style>\n";

		return $css;
	}

	public static function renderBootstrapModalJs($item = '.btn') {

		$document	= Factory::getDocument();
		HTMLHelper::_('jquery.framework', false);
		$s = '
		jQuery(document).ready(function(){
			
			jQuery("#phModalPlay").on("hidden.bs.modal", function (e) {
				jQuery("#phModalPlay iframe").attr("src", jQuery("#phModalPlay iframe").attr("src"));
				jQuery("audio").each(function(){this.pause();this.currentTime = 0;});
				jQuery("video").each(function(){this.pause();this.currentTime = 0;});
			});
			
			
			jQuery("'.$item.'").on("click", function () {
				var $target	= jQuery(this).data("target");
				var $href 	= jQuery(this).data("href");
				var $body	= $target + "Body";
				var $dialog	= $target + "Dialog";
				var $height	= jQuery(this).data("height");
				var $width	= jQuery(this).data("width");
				var $heightD= jQuery(this).data("height-dialog");
				var $widthD	= jQuery(this).data("width-dialog");
				var $type	= jQuery(this).data("type");
				jQuery($body).css("height", $height);
				jQuery($target).css("width", $width);
				jQuery($body).css("overflow-y", "auto");
				jQuery($dialog).css("height", $heightD);
				jQuery($dialog).css("width", $widthD);
				

				if ($type == "image") {
					jQuery($body).html(\'<img class="img-responsive" src="\' + $href + \'" />\');
				} else if ($type == "document") {
					$widthD = $widthD -50;
					$heightD = $heightD -40;
					jQuery($body).html(\'<object type="application/pdf" data="\' + $href + \'" width="\' + $widthD + \'" height="\' + $heightD + \'" ></object>\');
				} else {
				    
					/*jQuery($body).load($href, function (response, status, xhr) {
						if (status == "success") {
						

							//jQuery($target).modal({ show: true });
						}
					});*/
				}
				
			});
		});';
		$document->addScriptDeclaration($s);
	}

	/* Launch
	<a type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#phModalDetail" data-href="..." data-height="500px">..</a>
	*/

	public static function bootstrapModalHtml($item = 'phModal', $title = '') {

		$close = Text::_('COM_PHOCADOWNLAD_CLOSE');
		$o = '<div id="'.$item.'" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="'.$item.'Label">
		  <div class="modal-dialog" role="document" id="'.$item.'Dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<h3 class="modal-title" id="'.$item.'Label">'.$title.'</h3>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="'.$close.'"></button>
			  </div>
			  <div class="modal-body" id="'.$item.'Body" ></div>
			  <div class="modal-footer"></div>
			</div>
		  </div>
		</div>';

		return $o;
	}

		public static function renderPhocaDownload() {
			return '<div sty'.'le="t'.'ext-al'.'ign:ri'.'ght;">Po'
			.'wered by <a href="ht'.'tp://www.pho'
			.'ca.cz/phocad'.'ownload" targe'
			.'t="_bla'.'nk" title="Pho'.'ca Dow'
			.'nload">Phoca Down'.'load</a></div>';
	}

	public static function renderIcon($type, $img, $alt, $class = '', $attributes = '') {

        //return JHtml::_('image', $img, $alt);

        $paramsC         = ComponentHelper::getParams('com_phocadownload');

        // possible FR
        $icons = 0;//$paramsC->get('icons', 0);

        if ($icons == 0) {
            return HTMLHelper::_('image', $img, $alt, $attributes);
        }

        $i = '';

        if ($icons == 1) {

            $icon                    = array();
            $icon['view']            = 'zoom-in';
            $icon['download']        = 'download-alt';
            $icon['geo']             = 'globe';
            $icon['bold']            = 'bold';
            $icon['italic']          = 'italic';
            $icon['underline']       = 'text-color';
            $icon['camera']          = 'camera';
            $icon['comment']         = 'comment';
            $icon['comment-a']       = 'comment'; //ph-icon-animated
            $icon['comment-fb']      = 'comment'; //ph-icon-fb
            $icon['cart']            = 'shopping-cart';
            $icon['extlink1']        = 'share';
            $icon['extlink2']       = 'share';
            $icon['trash']           = 'trash';
            $icon['publish']         = 'ok';
            $icon['unpublish']       = 'remove';
            $icon['viewed']          = 'modal-window';
            $icon['calendar']        = 'calendar';
            $icon['vote']            = 'star';
            $icon['statistics']      = 'stats';
            $icon['category']        = 'folder-close';
            $icon['subcategory']     = 'folder-open';
            $icon['upload']          = 'upload';
            $icon['upload-ytb']      = 'upload';
            $icon['upload-multiple'] = 'upload';
            $icon['upload-java']     = 'upload';
            $icon['user']            = 'user';
            $icon['icon-up-images']  = 'arrow-left';
            $icon['icon-up']         = 'arrow-up';
            $icon['minus-sign']      = 'minus-sign';
            $icon['next']            = 'forward';
            $icon['prev']            = 'backward';
            $icon['reload']          = 'repeat';
            $icon['play']            = 'play';
            $icon['stop']            = 'stop';
            $icon['pause']           = 'pause';
            $icon['off']             = 'off';
            $icon['image']           = 'picture';
            $icon['save']            = 'floppy-disk';
            $icon['feed']            = 'bullhorn';
            $icon['remove']          = 'remove';
            $icon['search']         = 'zoom-in';
            $icon['lock']           = 'lock';

            if (isset($icon[$type])) {
                return '<span class="ph-icon-' . $type . ' glyphicon glyphicon-' . $icon[$type] . ' ' . $class . '"></span>';

            } else {
                if ($img != '') {
                    return HTMLHelper::_('image', $img, $alt, $attributes);
                }
            }

            // NOT glyphicon
            // smile, sad, lol, confused, wink, cooliris

            // Classes
            // ph-icon-animated, ph-icon-fb, icon-up-images, ph-icon-disabled

        } else if ($icons == 2) {

            $icon                    = array();
            $icon['view']            = 'search';
            $icon['download']        = 'download';
            $icon['geo']             = 'globe';
            $icon['bold']            = 'bold';
            $icon['italic']          = 'italic';
            $icon['underline']       = 'underline';
            $icon['camera']          = 'camera';
            $icon['comment']         = 'comment';
            $icon['comment-a']       = 'comment'; //ph-icon-animated
            $icon['comment-fb']      = 'comment'; //ph-icon-fb
            $icon['cart']            = 'shopping-cart';
            $icon['extlink1']        = 'share';
            $icon['extlink2']       = 'share';
            $icon['trash']           = 'trash';
            $icon['publish']         = 'check-circle';
            $icon['unpublish']       = 'times-circle';
            $icon['viewed']          = 'window-maximize';
            $icon['calendar']        = 'calendar';
            $icon['vote']            = 'star';
            $icon['statistics']      = 'chart-bar';
            $icon['category']        = 'folder';
            $icon['subcategory']     = 'folder-open';
            $icon['upload']          = 'upload';
            $icon['upload-ytb']      = 'upload';
            $icon['upload-multiple'] = 'upload';
            $icon['upload-java']     = 'upload';
            $icon['user']            = 'user';
            $icon['icon-up-images']  = 'arrow-left';
            $icon['icon-up']         = 'arrow-up';
            $icon['minus-sign']      = 'minus-circle';
            $icon['next']            = 'forward';
            $icon['prev']            = 'backward';
            $icon['reload']          = 'sync';
            $icon['play']            = 'play';
            $icon['stop']            = 'stop';
            $icon['pause']           = 'pause';
            $icon['off']             = 'power-off';
            $icon['image']           = 'image';
            $icon['save']            = 'save';
            $icon['feed']            = 'rss';
            $icon['remove']          = 'times-circle';
            $icon['search']         = 'search';
            $icon['lock']           = 'lock';


            if (isset($icon[$type])) {
                return '<span class="ph-icon-' . $type . ' fa fa5 fa-' . $icon[$type] . ' ' . $class . '"></span>';

            } else {
                if ($img != '') {
                    return HTMLHelper::_('image', $img, $alt, $attributes);
                }
            }
        }
    }

    public static function renderHeader($headers = array(), $tag = '', $imageMeta = '')
    {

        $app = Factory::getApplication();
        //$menus		= $app->getMenu();
        //$menu 		= $menus->getActive();
        $p = $app->getParams();
        $showPageHeading    = $p->get('show_page_heading');
        $pageHeading        = $p->get('page_heading');
        $displayHeader      = $p->get('display_header_type', 'h1');
        //$hideHeader         = $p->get('hide_header_view', array());



        if ($displayHeader == '-1') {
            return '';
        }

        //$view = $app->input->get('view', '', 'string');

        /*if (!empty($hideHeader) && $view != '') {
            if (in_array($view, $hideHeader)) {
                return '';
            }

        }*/


        if ($tag == '') {
            $tag = $displayHeader;
        }

        $h = array();
        if ($showPageHeading && $pageHeading != '') {
            $h[] = htmlspecialchars($pageHeading);
        }

        if (!empty($headers)) {
            foreach ($headers as $k => $v) {
                if ($v != '') {
                    $h[] = htmlspecialchars($v);
                    break; // in array there are stored OR items (if empty try next, if not empty use this and do not try next)
                    // PAGE HEADING AND NEXT ITEM OR NEXT NEXT ITEM
                }
            }
        }

        $imgMetaAttr = '';
        if ($imageMeta != '') {
            $imgMetaAttr = 'data-image-meta="'.$imageMeta.'"';
        }

        if (!empty($h)) {
            return '<' . strip_tags($tag) . ' class="ph-header" '.$imgMetaAttr.'>' . implode(" - ", $h) . '</' . strip_tags($tag) . '>';
        } else if ($imgMetaAttr != '') {
            return '<div style="display:none;" '.$imgMetaAttr.'></div>';// use hidden tag for open graph info
        }
        return false;
    }

    public static function renderSubHeader($headers = array(), $tag = '', $class = '', $imageMeta = '')
    {

        $app = Factory::getApplication();
        //$menus		= $app->getMenu();
        //$menu 		= $menus->getActive();
        $p = $app->getParams();
        //$showPageHeading    = $p->get('show_page_heading');
        //$pageHeading        = $p->get('page_heading');
        $displayHeader      = $p->get('display_subheader_type', 'h3');
        //$hideHeader         = $p->get('hide_header_view', array());



        if ($displayHeader == '-1') {
            return '';
        }

        //$view = $app->input->get('view', '', 'string');

        /*if (!empty($hideHeader) && $view != '') {
            if (in_array($view, $hideHeader)) {
                return '';
            }

        }*/


        if ($tag == '') {
            $tag = $displayHeader;
        }

        $h = array();
       // if ($showPageHeading && $pageHeading != '') {
        //    $h[] = htmlspecialchars($pageHeading);
       // }

        if (!empty($headers)) {
            foreach ($headers as $k => $v) {
                if ($v != '') {
                    $h[] = htmlspecialchars($v);
                    break; // in array there are stored OR items (if empty try next, if not empty use this and do not try next)
                    // PAGE HEADING AND NEXT ITEM OR NEXT NEXT ITEM
                }
            }
        }

        $imgMetaAttr = '';
        if ($imageMeta != '') {
            $imgMetaAttr = 'data-image-meta="'.$imageMeta.'"';
        }

        if (!empty($h)) {
            return '<' . strip_tags($tag) . ' class="ph-subheader '.htmlspecialchars(strip_tags($class)).'" '.$imgMetaAttr.'>' . implode(" - ", $h) . '</' . strip_tags($tag) . '>';
        } else if ($imgMetaAttr != '') {
            return '<div style="display:none;" '.$imgMetaAttr.'></div>';// use hidden tag for open graph info
        }
        return false;
    }
}
?>
