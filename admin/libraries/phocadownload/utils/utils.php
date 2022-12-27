<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Installer\Installer;
use Joomla\CMS\Factory;
use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Plugin\PluginHelper;
class PhocaDownloadUtils
{
	public static function footer() {
		echo '<div>Powe'.'red b'.'y <a href="ht'.'tp://www.pho'.'ca.c'.'z/pho'
		.'cado'.'wn'.'load" tar'.'get="_bl'.'ank" title="Pho'.'ca Down' .'load">Pho'
		.'ca Downl'.'oad</a></div>';
	}

	public static function getExtensionVersion($c = 'phocadownload') {
		$folder = JPATH_ADMINISTRATOR .'/components/com_'.$c;
		if (Folder::exists($folder)) {
			$xmlFilesInDir = Folder::files($folder, '.xml$');
		} else {
			$folder = JPATH_SITE . '/components/com_'.$c;
			if (Folder::exists($folder)) {
				$xmlFilesInDir = Folder::files($folder, '.xml$');
			} else {
				$xmlFilesInDir = null;
			}
		}

		$xml_items = array();
		if (count($xmlFilesInDir))
		{
			foreach ($xmlFilesInDir as $xmlfile)
			{
				if ($data = JInstaller::parseXMLInstallFile($folder.'/'.$xmlfile)) {
					foreach($data as $key => $value) {
						$xml_items[$key] = $value;
					}
				}
			}
		}

		if (isset($xml_items['version']) && $xml_items['version'] != '' ) {
			return $xml_items['version'];
		} else {
			return '';
		}
	}

	public static function setVars( $task = '') {

		$a			= array();
		$app		= Factory::getApplication();
		$a['o'] 	= htmlspecialchars(strip_tags($app->input->get('option')));
		$a['c'] 	= str_replace('com_', '', $a['o']);
		$a['n'] 	= 'Phoca' . ucfirst(str_replace('com_phoca', '', $a['o']));
		$a['l'] 	= strtoupper($a['o']);
		$a['i']		= 'media/'.$a['o'].'/images/administrator/';
		$a['s']		= 'media/'.$a['o'].'/css/administrator/'.$a['c'].'.css';
		$a['task']	= $a['c'] . htmlspecialchars(strip_tags($task));
		$a['tasks'] = $a['task']. 's';
		return $a;
	}

	public static function getAliasName($alias) {
		$alias = ApplicationHelper::stringURLSafe($alias);
		if (trim(str_replace('-', '', $alias)) == '') {
			$alias = Factory::getDate()->format("Y-m-d-H-i-s");
		}
		return $alias;

	}

	public static function strTrimAll($input) {
		$output	= '';
	    $input	= trim($input);
	    for($i=0;$i<strlen($input);$i++) {
	        if(substr($input, $i, 1) != " ") {
	            $output .= trim(substr($input, $i, 1));
	        } else {
	            $output .= " ";
	        }
	    }
	    return $output;
	}

	public static function toArray($value = FALSE) {
		if ($value == FALSE) {
			return array(0 => 0);
		} else if (empty($value)) {
			return array(0 => 0);
		} else if (is_array($value)) {
			return $value;
		} else {
			return array(0 => $value);
		}

	}
	public static function isURLAddress($url) {
		return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
	}

	public static  function setQuestionmarkOrAmp($url) {
		$isThereQMR = false;
		$isThereQMR = preg_match("/\?/i", $url);
		if ($isThereQMR) {
			return '&amp;';
		} else {
			return '?';
		}
	}

	public static function getToken($title = '') {
		$salt = md5($title . 'string '. date('s'). mt_rand(0,9999) . str_replace(mt_rand(0,9), mt_rand(0,9999), date('r')). 'end string');
		$token = hash('sha256', $salt . time());
		return $token;
	}

	public static function cleanFolderUrlName($string) {
		$string = str_replace('@', '-', $string);
		$string = str_replace('?', '-', $string);
		$string = str_replace('&', '-', $string);
		$string = str_replace('%', '-', $string);
		return $string;

	}

	public static function getIntFromString($string) {

		if (empty($string)) {
			return 0;
		}
		$int	= '';//$int = 0
		$parts 	= explode(':', $string);
		if (isset($parts[0])) {
			$int = (int)$parts[0];
		}
		return $int;
	}

    public static function getInfo() {

        PluginHelper::importPlugin('phocatools');
        $results = Factory::getApplication()->triggerEvent('onPhocatoolsOnDisplayInfo', array('NjI5NTcxNzcxMTc='));
        if (isset($results[0]) && $results[0] === true) {
            return '';
        }
        return '<div style="text-align:right;color:#ccc;display:block">Powered by <a href="https://www.phoca.cz/phocadownload">Phoca Download</a></div>';

    }



	public static function filterValue($string, $type = 'html') {

		switch ($type) {

			case 'url':
				return rawurlencode($string);
				break;

			case 'number':
				return preg_replace( '/[^.0-9]/', '', (string)$string );
				break;

			case 'number2':
       			//return preg_replace( '/[^0-9\.,+-]/', '', $string );
       			return preg_replace( '/[^0-9\.,-]/', '', (string)$string );
			break;

			case 'alphanumeric':
				return preg_replace("/[^a-zA-Z0-9]+/", '', (string)$string);
				break;

			case 'alphanumeric2':
				return preg_replace("/[^\\w-]/", '', (string)$string);// Alphanumeric plus _  -
				break;

			case 'alphanumeric3':
				return preg_replace("/[^\\w.-]/", '', (string)$string);// Alphanumeric plus _ . -
				break;

			case 'folder':
			case 'file':
				$string =  preg_replace('/[\"\*\/\\\:\<\>\?\'\|]+/', '', (string)$string);
				return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
				break;

			case 'folderpath':
			case 'filepath':
				$string = preg_replace('/[\"\*\:\<\>\?\'\|]+/', '', (string)$string);
				return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
				break;

			case 'text':
				return htmlspecialchars(strip_tags((string)$string), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
				break;

			case 'html':
			default:
				return htmlspecialchars((string)$string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
				break;

		}

	}

	public static function getIp() {


		$params 				= ComponentHelper::getParams('com_phocadownload');
		$store_ip				= $params->get( 'store_ip', 0 );

		if ($store_ip == 0) {
			return '';
		}

		$ip = false;
		if(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != getenv('SERVER_ADDR')) {
			$ip  = $_SERVER['REMOTE_ADDR'];
		} else {
			$ip  = getenv('HTTP_X_FORWARDED_FOR');
		}
		if (!$ip) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		return $ip;
	}
}
?>
