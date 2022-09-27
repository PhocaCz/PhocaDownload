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

defined( '_JEXEC' ) or die( 'Restricted access' );
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

class PhocaDownloadMail
{
	/*
	 * param method 1 = download, 2 = upload
	 */
	public static function sendMail ( $id, $fileName, $method = 1 ) {
		$app = Factory::getApplication();

		$db 		= Factory::getDBO();
		$sitename 	= $app->get( 'sitename' );
		$mailfrom 	= $app->get( 'mailfrom' );
		$fromname	= $sitename;
		$date		= HTMLHelper::_('date',  gmdate('Y-m-d H:i:s'), Text::_( 'DATE_FORMAT_LC2' ));
		$user 		= Factory::getUser();
		$params 	= $app->getParams();

		if (isset($user->name) && $user->name != '') {
			$name = $user->name;
		} else {
			$name = Text::_('COM_PHOCADOWNLOAD_ANONYMOUS');
		}
		if (isset($user->username) && $user->username != '') {
			$userName = ' ('.$user->username.')';
		} else {
			$userName = '';
		}

		if ($method == 1) {
			$subject 		= $sitename. ' - ' . Text::_( 'COM_PHOCADOWNLOAD_FILE_DOWNLOADED' );
			$title 			= Text::_( 'COM_PHOCADOWNLOAD_FILE_DOWNLOADED' );
			$messageText 	= Text::_( 'COM_PHOCADOWNLOAD_FILE') . ' "' .$fileName . '" '.Text::_('COM_PHOCADOWNLOAD_WAS_DOWNLOADED_BY'). ' '.$name . $userName.'.';
		} else {
			$subject 		= $sitename. ' - ' . Text::_( 'COM_PHOCADOWNLOAD_SUCCESS_FILE_UPLOADED' );
			$title 			= Text::_( 'COM_PHOCADOWNLOAD_SUCCESS_NEW_FILE_UPLOADED' );
			$messageText 	= Text::_( 'COM_PHOCADOWNLOAD_FILE') . ' "' .$fileName . '" '.Text::_('COM_PHOCADOWNLOAD_WAS_UPLOADED_BY'). ' '.$name . $userName.'.';
		}

		//get all super administrator
		$query = 'SELECT name, email, sendEmail' .
		' FROM #__users' .
		' WHERE id = '.(int)$id .
		' ORDER BY id';
		$db->setQuery( $query );
		$rows = $db->loadObjectList();

		if (isset($rows[0]->email)) {
			$email 	= $rows[0]->email;
		}


		$message = $title . "\n\n"
		. Text::_( 'COM_PHOCADOWNLOAD_WEBSITE' ) . ': '. $sitename . "\n"
		. Text::_( 'COM_PHOCADOWNLOAD_DATE' ) . ': '. $date . "\n"
		. 'IP: ' . PhocaDownloadUtils::getIp(). "\n\n"
		. Text::_( 'COM_PHOCADOWNLOAD_MESSAGE' ) . ': '."\n"
		. "\n\n"
		. $messageText
		. "\n\n"
		. Text::_( 'COM_PHOCADOWNLOAD_REGARDS' ) .", \n"
		. $sitename ."\n";

		$subject = html_entity_decode($subject, ENT_QUOTES);
		$message = html_entity_decode($message, ENT_QUOTES);

		$mail = Factory::getMailer();
		$mail->sendMail($mailfrom, $fromname, $email, $subject, $message);
		return true;
	}
}
?>
