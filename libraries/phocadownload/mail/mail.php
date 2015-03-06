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

class PhocaDownloadMail
{
	/*
	 * param method 1 = download, 2 = upload
	 */
	public static function sendMail ( $id, $fileName, $method = 1 ) {
		$app = JFactory::getApplication();
		
		$db 		= JFactory::getDBO();
		$sitename 	= $app->getCfg( 'sitename' );
		$mailfrom 	= $app->getCfg( 'mailfrom' );
		$fromname	= $sitename;
		$date		= JHTML::_('date',  gmdate('Y-m-d H:i:s'), JText::_( 'DATE_FORMAT_LC2' ));
		$user 		= JFactory::getUser();
		$params 	= $app->getParams();
		
		if (isset($user->name) && $user->name != '') {
			$name = $user->name;
		} else {
			$name = JText::_('Anonymous');
		}
		if (isset($user->username) && $user->username != '') {
			$userName = ' ('.$user->username.')';
		} else {
			$userName = '';
		}
		
		if ($method == 1) {
			$subject 		= $sitename. ' - ' . JText::_( 'File downloaded' );
			$title 			= JText::_( 'File downloaded' );
			$messageText 	= JText::_( 'File') . ' "' .$fileName . '" '.JText::_('was downloaded by'). ' '.$name . $userName.'.';
		} else {
			$subject 		= $sitename. ' - ' . JText::_( 'File uploaded' );
			$title 			= JText::_( 'New File uploaded' );
			$messageText 	= JText::_( 'File') . ' "' .$fileName . '" '.JText::_('was uploaded by'). ' '.$name . $userName.'.';
		}
		
		//get all super administrator
		$query = 'SELECT name, email, sendEmail' .
		' FROM #__users' .
		' WHERE id = '.(int)$id;
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		
		if (isset($rows[0]->email)) {
			$email 	= $rows[0]->email;
		}

		
		$message = $title . "\n\n"
		. JText::_( 'Website' ) . ': '. $sitename . "\n"
		. JText::_( 'Date' ) . ': '. $date . "\n"
		. 'IP: ' . $_SERVER["REMOTE_ADDR"]. "\n\n"
		. JText::_( 'Message' ) . ': '."\n"
		. "\n\n"
		. $messageText
		. "\n\n"
		. JText::_( 'Regards' ) .", \n"
		. $sitename ."\n";
					
		$subject = html_entity_decode($subject, ENT_QUOTES);
		$message = html_entity_decode($message, ENT_QUOTES);
		
		$mail = JFactory::getMailer();
		$mail->sendMail($mailfrom, $fromname, $email, $subject, $message);	
		return true;
	}
}
?>