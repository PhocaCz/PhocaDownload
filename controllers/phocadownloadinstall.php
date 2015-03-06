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
defined('_JEXEC') or die();

class PhocaDownloadCpControllerPhocaDownloadinstall extends PhocaDownloadCpController
{
	function __construct(){
		parent::__construct();
		$this->registerTask( 'install'  , 'install' );
		$this->registerTask( 'upgrade'  , 'upgrade' );		
	}
	
	function install() {		
		$db		= JFactory::getDBO();
		$msgSQL 	= '';
		$msgFile	= '';
		$msgError	= '';
		
		// --------------------------------------------------------------------------
		
		$query =' DROP TABLE IF EXISTS '.$db->quoteName('#__phocadownload_categories').' ;';
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		
		$query ='CREATE TABLE '.$db->quoteName('#__phocadownload_categories').' ('."\n";
		$query.=' '.$db->quoteName('id').' int(11) NOT NULL auto_increment,'."\n";
		$query.=' '.$db->quoteName('parent_id').' int(11) NOT NULL default 0,'."\n";
		$query.=' '.$db->quoteName('section').' int(11) NOT NULL default 0,'."\n";
		$query.=' '.$db->quoteName('title').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('name').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('alias').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('image').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('image_position').' varchar(30) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('description').' text,'."\n";
		$query.=' '.$db->quoteName('published').' tinyint(1) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out').' int(11) unsigned NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out_time').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('editor').' varchar(50) default NULL,'."\n";
		$query.=' '.$db->quoteName('ordering').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('access').' int(11) unsigned NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('uploaduserid').' text,'."\n";
		$query.=' '.$db->quoteName('accessuserid').' text,'."\n";
		$query.=' '.$db->quoteName('deleteuserid').' text,'."\n";
		$query.=' '.$db->quoteName('date').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('count').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('params').' text,'."\n";
		$query.=' '.$db->quoteName('hits').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('metakey').' text,'."\n";
		$query.=' '.$db->quoteName('metadesc').' text,'."\n";
		$query.=' '.$db->quoteName('metadata').' text,'."\n";
		$query.=' '.$db->quoteName('language').' char(7) NOT NULL default \'\','."\n";
		$query.='  PRIMARY KEY  ('.$db->quoteName('id').'),'."\n";
		$query.='  KEY '.$db->quoteName('cat_idx').' ('.$db->quoteName('section').', '. $db->quoteName('published').', '. $db->quoteName('access').'),'."\n";
		$query.='  KEY '.$db->quoteName('idx_access').' ('.$db->quoteName('access').'),'."\n";
		$query.='  KEY '.$db->quoteName('idx_checkout').' ('.$db->quoteName('checked_out').')'."\n";
		$query.=') default CHARSET=utf8;';
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		// --------------------------------------------------------------------------
		/*
		$query=' DROP TABLE IF EXISTS '.$db->quoteName('#__phocadownload_sections').' ;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		$query ='CREATE TABLE '.$db->quoteName('#__phocadownload_sections').' ('."\n";
		$query.=' '.$db->quoteName('id').' int(11) NOT NULL auto_increment,'."\n";
		$query.=' '.$db->quoteName('title').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('name').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('alias').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('image').' text,'."\n";
		$query.=' '.$db->quoteName('scope').' varchar(50) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('image_position').' varchar(30) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('description').' text,'."\n";
		$query.=' '.$db->quoteName('published').' tinyint(1) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out').' int(11) unsigned NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out_time').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('ordering').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('access').' int(11) unsigned NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('date').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('count').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('params').' text,'."\n";
		$query.=' '.$db->quoteName('metakey').' text,'."\n";
		$query.=' '.$db->quoteName('metadesc').' text,'."\n";
		$query.='  PRIMARY KEY  ('.$db->quoteName('id').'),'."\n";
		$query.='  KEY '.$db->quoteName('idx_scope').' ('.$db->quoteName('scope').')'."\n";
		$query.=') TYPE=MyISAM CHARACTER SET '.$db->quoteName('utf8').' ;';
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		*/
		// --------------------------------------------------------------------------
		
		$query=' DROP TABLE IF EXISTS '.$db->quoteName('#__phocadownload').' ;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		$query ='CREATE TABLE '.$db->quoteName('#__phocadownload').' ('."\n";
		$query.=' '.$db->quoteName('id').' int(11) unsigned NOT NULL auto_increment,'."\n";
		$query.=' '.$db->quoteName('catid').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('sectionid').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('owner_id').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('sid').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('title').' varchar(250) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('alias').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('filename').' varchar(250) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('filesize').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('filename_play').' varchar(250) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('filename_preview').' varchar(250) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('author').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('author_email').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('author_url').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('license').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('license_url').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('video_filename').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('image_filename').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('image_filename_spec1').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('image_filename_spec2').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('image_download').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('link_external').' varchar(255) NOT NULL default \'\','."\n";
		
		$query.=' '.$db->quoteName('mirror1link').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('mirror1title').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('mirror1target').' varchar(10) NOT NULL default \'\','."\n";
		
		$query.=' '.$db->quoteName('mirror2link').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('mirror2title').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('mirror2target').' varchar(10) NOT NULL default \'\','."\n";
		
		$query.=' '.$db->quoteName('description').' text,'."\n";
		$query.=' '.$db->quoteName('features').' text,'."\n";
		$query.=' '.$db->quoteName('changelog').' text,'."\n";
		$query.=' '.$db->quoteName('notes').' text,'."\n";
		$query.=' '.$db->quoteName('userid').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('version').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('directlink').' tinyint(1) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('date').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('publish_up').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('publish_down').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('hits').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('textonly').' tinyint(1) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('published').' tinyint(1) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('approved').' tinyint(3) unsigned NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out_time').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('ordering').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('access').' int(11) unsigned NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('confirm_license').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('unaccessible_file').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('params').' text,'."\n";
		$query.=' '.$db->quoteName('metakey').' text,'."\n";
		$query.=' '.$db->quoteName('metadesc').' text,'."\n";
		$query.=' '.$db->quoteName('metadata').' text,'."\n";
		$query.=' '.$db->quoteName('language').' char(7) NOT NULL default \'\','."\n";
		$query.='  PRIMARY KEY  ('.$db->quoteName('id').'),'."\n";
		$query.='  KEY '.$db->quoteName('catid').' ('.$db->quoteName('catid').', '. $db->quoteName('published').')'."\n";
		$query.=') default CHARSET=utf8;';
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		// --------------------------------------------------------------------------
		/*
		$query=' DROP TABLE IF EXISTS '.$db->quoteName('#__phocadownload_settings').' ;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		$query =' CREATE TABLE '.$db->quoteName('#__phocadownload_settings').' ('."\n";
		$query.=' '.$db->quoteName('id').' int(11) unsigned NOT NULL auto_increment,'."\n";
		$query.=' '.$db->quoteName('title').' varchar(250) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('value').' text,'."\n";
		$query.=' '.$db->quoteName('values').' text,'."\n";
		$query.=' '.$db->quoteName('type').' varchar(50) NOT NULL default \'\','."\n";
		$query.='  PRIMARY KEY  ('.$db->quoteName('id').')'."\n";
		$query.=') TYPE=MyISAM CHARACTER SET '.$db->quoteName('utf8').' ;';
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		// VALUES
		
		$queries[] = "INSERT INTO ".$db->quoteName('#__phocadownload_settings')." VALUES (null, 'download_folder', 'phocadownload','', 'text');"."\n";
		
		$queries[] = "INSERT INTO ".$db->quoteName('#__phocadownload_settings')." VALUES (null, 'allowed_file_types', '{hqx=application/mac-binhex40}\n{cpt=application/mac-compactpro}\n{csv=text/x-comma-separated-values}\n{bin=application/macbinary}\n{dms=application/octet-stream}\n{lha=application/octet-stream}\n{lzh=application/octet-stream}\n{exe=application/octet-stream}\n{class=application/octet-stream}\n{psd=application/x-photoshop}\n{so=application/octet-stream}\n{sea=application/octet-stream}\n{dll=application/octet-stream}\n{oda=application/oda}\n{pdf=application/pdf}\n{ai=application/postscript}\n{eps=application/postscript}\n{ps=application/postscript}\n{smi=application/smil}\n{smil=application/smil}\n{mif=application/vnd.mif}\n{xls=application/vnd.ms-excel}\n{ppt=application/powerpoint}\n{wbxml=application/wbxml}\n{wmlc=application/wmlc}\n{dcr=application/x-director}\n{dir=application/x-director}\n{dxr=application/x-director}\n{dvi=application/x-dvi}\n{gtar=application/x-gtar}\n{gz=application/x-gzip}\n{php=application/x-httpd-php}\n{php4=application/x-httpd-php}\n{php3=application/x-httpd-php}\n{phtml=application/x-httpd-php}\n{phps=application/x-httpd-php-source}\n{js=application/x-javascript}\n{swf=application/x-shockwave-flash}\n{sit=application/x-stuffit}\n{tar=application/x-tar}\n{tgz=application/x-tar}\n{xhtml=application/xhtml+xml}\n{xht=application/xhtml+xml}\n{zip=application/x-zip}\n{mid=audio/midi}\n{midi=audio/midi}\n{mpga=audio/mpeg}\n{mp2=audio/mpeg}\n{mp3=audio/mpeg}\n{aif=audio/x-aiff}\n{aiff=audio/x-aiff}\n{aifc=audio/x-aiff}\n{ram=audio/x-pn-realaudio}\n{rm=audio/x-pn-realaudio}\n{rpm=audio/x-pn-realaudio-plugin}\n{ra=audio/x-realaudio}\n{rv=video/vnd.rn-realvideo}\n{wav=audio/x-wav}\n{bmp=image/bmp}\n{gif=image/gif}\n{jpeg=image/jpeg}\n{jpg=image/jpeg}\n{jpe=image/jpeg}\n{png=image/png}\n{tiff=image/tiff}\n{tif=image/tiff}\n{css=text/css}\n{html=text/html}\n{htm=text/html}\n{shtml=text/html}\n{txt=text/plain}\n{text=text/plain}\n{log=text/plain}\n{rtx=text/richtext}\n{rtf=text/rtf}\n{xml=text/xml}\n{xsl=text/xml}\n{mpeg=video/mpeg}\n{mpg=video/mpeg}\n{mpe=video/mpeg}\n{qt=video/quicktime}\n{mov=video/quicktime}\n{avi=video/x-msvideo}\n{flv=video/x-flv}\n{movie=video/x-sgi-movie}\n{doc=application/msword}\n{xl=application/excel}\n{eml=message/rfc822}', '', 'textarea');"."\n";

		$queries[] = "INSERT INTO ".$db->quoteName('#__phocadownload_settings')." VALUES (null, 'disallowed_file_types', '','', 'textarea');"."\n";
		$queries[] = "INSERT INTO ".$db->quoteName('#__phocadownload_settings')." VALUES (null, 'upload_maxsize', '3145728','', 'text');"."\n";
		$queries[] = "INSERT INTO ".$db->quoteName('#__phocadownload_settings')." VALUES (null, 'enable_flash', 0,'{0=No}{1=Yes}', 'select');"."\n";
		
		// Version 1.0.6
		$queries[] = "INSERT INTO ".$db->quoteName('#__phocadownload_settings')." VALUES (null, 'enable_user_statistics', 1,'{0=No}{1=Yes}', 'select');"."\n";
		// Version 1.1.0
		$queries[] = "INSERT INTO ".$db->quoteName('#__phocadownload_settings')." VALUES (null, 'absolute_path', '','', 'text');"."\n";
		// Version 1.3.4
		$queries[] = "INSERT INTO ".$db->quoteName('#__phocadownload_settings')." VALUES (null, 'description', '','', 'textareaeditor');"."\n";
		
		foreach ($queries as $valueQuery) {
			$db->setQuery( $valueQuery );
			if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		}
*/
		// --------------------------------------------------------------------------
		
		$query=' DROP TABLE IF EXISTS '.$db->quoteName('#__phocadownload_user_stat').' ;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		$query='CREATE TABLE '.$db->quoteName('#__phocadownload_user_stat').' ('."\n";
		$query.=' '.$db->quoteName('id').' int(11) NOT NULL auto_increment,'."\n";
		$query.=' '.$db->quoteName('fileid').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('userid').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('count').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('date').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('published').' tinyint(1) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('ordering').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('language').' char(7) NOT NULL default \'\','."\n";
		$query.='  PRIMARY KEY  ('.$db->quoteName('id').')'."\n";
		$query.=') default CHARSET=utf8;';
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
	
		// --------------------------------------------------------------------------
	
		$query=' DROP TABLE IF EXISTS '.$db->quoteName('#__phocadownload_licenses').' ;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		$query ='CREATE TABLE '.$db->quoteName('#__phocadownload_licenses').' ('."\n";
		$query.=' '.$db->quoteName('id').' int(11) NOT NULL auto_increment,'."\n";
		$query.=' '.$db->quoteName('title').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('alias').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('description').' text,'."\n";
		$query.=' '.$db->quoteName('checked_out').' int(11) unsigned NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out_time').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('published').' tinyint(1) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('ordering').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('language').' char(7) NOT NULL default \'\','."\n";
		$query.='  PRIMARY KEY  ('.$db->quoteName('id').')'."\n";
		$query.=') default CHARSET=utf8;';
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		
		// ------------------------------------------
		// PHOCADOWNLOAD FILE VOTES  (2.0.0 RC2)
		// ------------------------------------------
		$query ='DROP TABLE IF EXISTS '.$db->quoteName('#__phocadownload_file_votes').';'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
	
		$query =' CREATE TABLE '.$db->quoteName('#__phocadownload_file_votes').' ('."\n";
		$query.=' '.$db->quoteName('id').' int(11) NOT NULL auto_increment,'."\n";
		$query.=' '.$db->quoteName('fileid').' int(11) NOT NULL default 0,'."\n";
		$query.=' '.$db->quoteName('userid').' int(11) NOT NULL default 0,'."\n";
		$query.=' '.$db->quoteName('date').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('rating').' tinyint(1) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('published').' tinyint(1) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out').' int(11) unsigned NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out_time').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('ordering').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('params').' text,'."\n";
		$query.=' '.$db->quoteName('language').' char(7) NOT NULL default \'\','."\n";
		$query.=' PRIMARY KEY  ('.$db->quoteName('id').')'."\n";
		$query.=') default CHARSET=utf8;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		// ------------------------------------------
		// PHOCADOWNLOAD FILE VOTES STATISTICS (2.0.0 RC2)
		// ------------------------------------------
		$query ='DROP TABLE IF EXISTS '.$db->quoteName('#__phocadownload_file_votes_statistics').';'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}

		$query =' CREATE TABLE '.$db->quoteName('#__phocadownload_file_votes_statistics').' ('."\n";
		$query.=' '.$db->quoteName('id').' int(11) NOT NULL auto_increment,'."\n";
		$query.=' '.$db->quoteName('fileid').' int(11) NOT NULL default 0,'."\n";
		$query.=' '.$db->quoteName('count').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('average').' float(8,6) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('language').' char(7) NOT NULL default \'\','."\n";
		$query.=' PRIMARY KEY  ('.$db->quoteName('id').')'."\n";
		$query.=') default CHARSET=utf8;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		
		// ------------------------------------------
		// PHOCADOWNLOAD TAGS (2.1.0)
		// ------------------------------------------
		
		$query =' DROP TABLE IF EXISTS '.$db->quoteName('#__phocadownload_tags').' ;';
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		$query ='CREATE TABLE '.$db->quoteName('#__phocadownload_tags').' ('."\n";
		$query.=' '.$db->quoteName('id').' int(11) NOT NULL auto_increment,'."\n";
		$query.=' '.$db->quoteName('title').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('alias').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('link_cat').' int(11) NOT NULL default 0,'."\n";
		$query.=' '.$db->quoteName('link_ext').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('description').' text,'."\n";
		$query.=' '.$db->quoteName('published').' tinyint(1) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out').' int(11) unsigned NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out_time').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('ordering').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('params').' text,'."\n";
		$query.=' '.$db->quoteName('language').' char(7) NOT NULL default \'\','."\n";
		$query.=' PRIMARY KEY  ('.$db->quoteName('id').')'."\n";
		$query.=') default CHARSET=utf8;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		
		
		$query =' DROP TABLE IF EXISTS '.$db->quoteName('#__phocadownload_tags_ref').' ;';
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		$query ='CREATE TABLE '.$db->quoteName('#__phocadownload_tags_ref').' ('."\n";
		$query.=' '.$db->quoteName('id').' SERIAL,'."\n";
		$query.=' '.$db->quoteName('fileid').' int(11) NOT NULL default 0,'."\n";
		$query.=' '.$db->quoteName('tagid').' int(11) NOT NULL default 0,'."\n";
		$query.=' PRIMARY KEY  ('.$db->quoteName('id').'),'."\n";
		$query.=' UNIQUE KEY '.$db->quoteName('i_fileid').' ('.$db->quoteName('fileid').','.$db->quoteName('tagid').')'."\n";
		$query.=') default CHARSET=utf8;'."\n";
		
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		
		
		$query =' DROP TABLE IF EXISTS '.$db->quoteName('#__phocadownload_layout').' ;';
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		$query ='CREATE TABLE '.$db->quoteName('#__phocadownload_layout').' ('."\n";
		$query.=' '.$db->quoteName('id').' int(11) NOT NULL auto_increment,'."\n";
		$query.=' '.$db->quoteName('categories').' text,'."\n";
		$query.=' '.$db->quoteName('category').' text,'."\n";
		$query.=' '.$db->quoteName('file').' text,'."\n";
		$query.=' '.$db->quoteName('checked_out').' int(11) unsigned NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out_time').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('params').' text,'."\n";
		$query.=' PRIMARY KEY  ('.$db->quoteName('id').')'."\n";
		$query.=') default CHARSET=utf8;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		
		$query ='INSERT INTO '.$db->quoteName('#__phocadownload_layout').' ('."\n";
		$query.=' '.$db->quoteName('id').' ,'."\n";
		$query.=' '.$db->quoteName('categories').' ,'."\n";
		$query.=' '.$db->quoteName('category').' ,'."\n";
		$query.=' '.$db->quoteName('file').' ,'."\n";
		$query.=' '.$db->quoteName('checked_out').' ,'."\n";
		$query.=' '.$db->quoteName('checked_out_time').' ,'."\n";
		$query.=' '.$db->quoteName('params').' )'."\n";
		$query.=' VALUES ('."\n";
		$query.=' NULL,'."\n";
		
		$query.=' '.$db->Quote('<div class="pd-categoriesbox">
<div class="pd-title">{pdtitle}</div>
{pdsubcategories}
{pdclear}
</div>').','."\n";
		$query.=' '.$db->Quote('<div class="pd-filebox">
{pdfiledesctop}
{pdfile}
<div class="pd-buttons">{pdbuttondownload}</div>
<div class="pd-buttons">{pdbuttondetails}</div>
<div class="pd-buttons">{pdbuttonpreview}</div>
<div class="pd-buttons">{pdbuttonplay}</div>
<div class="pd-mirrors">{pdmirrorlink2} {pdmirrorlink1}</div>
<div class="pd-rating">{pdrating}</div>
<div class="pd-tags">{pdtags}</div>
{pdfiledescbottom}
<div class="pd-cb"></div>
</div>').','."\n";
		$query.=' '.$db->Quote('<div class="pd-filebox">
{pdimage}
{pdfile}
{pdfilesize}
{pdversion}
{pdlicense}
{pdauthor}
{pdauthoremail}
{pdfiledate}
{pddownloads}
{pddescription}
{pdfeatures}
{pdchangelog}
{pdnotes}
<div class="pd-mirrors">{pdmirrorlink2} {pdmirrorlink1}</div>
<div class="pd-report">{pdreportlink}</div>
<div class="pd-rating">{pdrating}</div>
<div class="pd-tags">{pdtags}</div>
<div class="pd-cb"></div>
</div>').','."\n";
		$query.=' '.$db->Quote('0').','."\n";
		$query.=' '.$db->Quote('0000-00-00 00:00:00').','."\n";
		$query.=' NULL'."\n";
		$query.=' );'."\n";

		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}

		
		// END -------------------------------------------------------------------------------
		

		
		// Error
		if ($msgSQL !='') {
			$msgError .= '<br />' . $msgSQL;
		}
		if ($msgFile !='') {
			$msgError .= '<br />' . $msgFile;
		}
		
		// End Message
		if ($msgError !='') {
			$msg = JText::_( 'Phoca Download not successfully installed' ) . ': ' . $msgError;
		} else {
			$msg = JText::_( 'Phoca Download successfully installed' );
		}
	
		
		$link = 'index.php?option=com_phocadownload';
		$this->setRedirect($link, $msg);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	function upgrade()
	{
		$db			= JFactory::getDBO();
		$dbPref 	= $db->getPrefix();
		$msgSQL 	= '';
		$msgFile	= '';
		$msgError	= '';
		
		
		$query =' SELECT * FROM '.$db->quoteName('#__phocadownload').' LIMIT 1;';
		$db->setQuery( $query );
		$result = $db->loadResult();
		if ($db->getErrorNum())
		{
			$msgSQL .= $db->getErrorMsg(). '<br />';
		}
		
		
		$query=' SELECT * FROM '.$db->quoteName('#__phocadownload_categories').' LIMIT 1;'."\n";
		
		$db->setQuery( $query );
		$result = $db->loadResult();
		if ($db->getErrorNum())
		{
			$msgSQL .= $db->getErrorMsg(). '<br />';
		}
		/*
		$query=' SELECT * FROM '.$db->quoteName('#__phocadownload_sections').' LIMIT 1;'."\n";
		
		$db->setQuery( $query );
		$result = $db->loadResult();
		if ($db->getErrorNum())
		{
			$msgSQL .= $db->getErrorMsg(). '<br />';
		}
		*/
		// UPGRADE PHOCA DOWNLOAD 2.1.0 VERSION
		// ------------------------------------------
		// PHOCADOWNLOAD TAGS
		// ------------------------------------------
		
		$query ='CREATE TABLE IF NOT EXISTS '.$db->quoteName('#__phocadownload_tags').' ('."\n";
		$query.=' '.$db->quoteName('id').' int(11) NOT NULL auto_increment,'."\n";
		$query.=' '.$db->quoteName('title').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('alias').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('link_cat').' int(11) NOT NULL default 0,'."\n";
		$query.=' '.$db->quoteName('link_ext').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('description').' text,'."\n";
		$query.=' '.$db->quoteName('published').' tinyint(1) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out').' int(11) unsigned NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out_time').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('ordering').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('params').' text,'."\n";
		$query.=' '.$db->quoteName('language').' char(7) NOT NULL default \'\','."\n";
		$query.=' PRIMARY KEY  ('.$db->quoteName('id').')'."\n";
		$query.=') default CHARSET=utf8;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		
		$query ='CREATE TABLE IF NOT EXISTS'.$db->quoteName('#__phocadownload_tags_ref').' ('."\n";
		$query.=' '.$db->quoteName('id').' SERIAL,'."\n";
		$query.=' '.$db->quoteName('fileid').' int(11) NOT NULL default 0,'."\n";
		$query.=' '.$db->quoteName('tagid').' int(11) NOT NULL default 0,'."\n";
		$query.=' PRIMARY KEY  ('.$db->quoteName('id').'),'."\n";
		$query.=' UNIQUE KEY '.$db->quoteName('i_fileid').' ('.$db->quoteName('fileid').','.$db->quoteName('tagid').')'."\n";
		$query.=') default CHARSET=utf8;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		
		$query ='CREATE TABLE IF NOT EXISTS '.$db->quoteName('#__phocadownload_layout').' ('."\n";
		$query.=' '.$db->quoteName('id').' int(11) NOT NULL auto_increment,'."\n";
		$query.=' '.$db->quoteName('categories').' text,'."\n";
		$query.=' '.$db->quoteName('category').' text,'."\n";
		$query.=' '.$db->quoteName('file').' text,'."\n";
		$query.=' '.$db->quoteName('checked_out').' int(11) unsigned NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out_time').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('params').' text,'."\n";
		$query.=' PRIMARY KEY  ('.$db->quoteName('id').')'."\n";
		$query.=') default CHARSET=utf8;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		
		$update0 	= false;
		$errorMsg	= '';
		$update0 	= $this->AddColumnIfNotExists( $errorMsg, "#__phocadownload", "features", "text", "description" );
		if (!$update0) {
			$msgSQL .= 'Error while updating Features column';
		}
		$update1 	= false;
		$errorMsg	= '';
		$update1 	= $this->AddColumnIfNotExists( $errorMsg, "#__phocadownload", "changelog", "text", "description" );
		if (!$update1) {
			$msgSQL .= 'Error while updating Changelog column';
		}
		$update2 	= false;
		$errorMsg	= '';
		$update2 	= $this->AddColumnIfNotExists( $errorMsg, "#__phocadownload", "notes", "text", "description" );
		if (!$update2) {
			$msgSQL .= 'Error while updating Notes column';
		}
		
		/*
		$query=' SELECT title FROM '.$db->quoteName('#__phocadownload_settings').' WHERE title = \'enable_user_statistics\' LIMIT 1;'."\n";
		$db->setQuery($query);

		if (!$result = $db->loadObject()) {
			$query = "INSERT INTO ".$db->quoteName('#__phocadownload_settings')." VALUES (null, 'enable_user_statistics', 1,'{0=No}{1=Yes}', 'select');"."\n";
			$db->setQuery( $query );
			if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		}
		
		// --------------------------------------------------------------------------
		
		// UPGRADE PHOCA DOWNLOAD 1.1.0 VERSION
		// ------------------------------------------
		// PHOCADOWNLOAD USER STAT
		// ------------------------------------------
		
		$query='CREATE TABLE IF NOT EXISTS '.$db->quoteName('#__phocadownload_licenses').' ('."\n";
		$query.=' '.$db->quoteName('id').' int(11) NOT NULL auto_increment,'."\n";
		$query.=' '.$db->quoteName('title').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('alias').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('description').' text,'."\n";
		$query.=' '.$db->quoteName('checked_out').' int(11) unsigned NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out_time').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('published').' tinyint(1) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('ordering').' int(11) NOT NULL default \'0\','."\n";
		$query.='  PRIMARY KEY  ('.$db->quoteName('id').')'."\n";
		$query.=') TYPE=MyISAM CHARACTER SET '.$db->quoteName('utf8').' ;';
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		// --------------------------------------------------------------------------
		
		// ------------------------------------------
		// PHOCADOWNLOAD UPDATE confirm_license
		// ------------------------------------------
		$updateCL 	= false;
		$updateCL	= $this->AddColumnIfNotExists($errorMsg, "#__phocadownload", "confirm_license", "int(11) NOT NULL default '0'", "access" );
		if (!$updateCL) {
			$msgSQL .= 'Error while updating Confirm License column<br />';
		}
		
		// ------------------------------------------
		// PHOCADOWNLOAD UPDATE confirm_license
		// ------------------------------------------
		$updateUF	= false;
		$updateUF	= $this->AddColumnIfNotExists($errorMsg, "#__phocadownload", "unaccessible_file", "int(11) NOT NULL default '0'", "access" );
		if (!$updateUF) {
			$msgSQL .= 'Error while updating Display Unaccessible Files column <br />';
		}
		
		// ------------------------------------------
		// PHOCADOWNLOAD CATEGORIES UPDATE date
		// ------------------------------------------
		$updateCD	= false;
		$updateCD	= $this->AddColumnIfNotExists($errorMsg, "#__phocadownload_categories", "date", "datetime NOT NULL default '0000-00-00 00:00:00'", "access" );
		if (!$updateCD) {
			$msgSQL .= 'Error while updating Date column (categories) <br />';
		}
		
		// ------------------------------------------
		// PHOCADOWNLOAD SECTIONS UPDATE date
		// ------------------------------------------
		$updateSD	= false;
		$updateSD	= $this->AddColumnIfNotExists($errorMsg, "#__phocadownload_sections", "date", "datetime NOT NULL default '0000-00-00 00:00:00'", "access" );
		if (!$updateSD) {
			$msgSQL .= 'Error while updating Date column (sections) <br />';
		}
		// ------------------------------------------
		// PHOCADOWNLOAD SETTINGS UPDATE absolute_path
		// ------------------------------------------
		
		$query=' SELECT title FROM '.$db->quoteName('#__phocadownload_settings').' WHERE title = \'absolute_path\' LIMIT 1;'."\n";
		$db->setQuery($query);

		if (!$result = $db->loadObject()) {
			$query = "INSERT INTO ".$db->quoteName('#__phocadownload_settings')." VALUES (null, 'absolute_path', '','', 'text');"."\n";
			$db->setQuery( $query );
			if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		}
		
		// ------------------------------------------
		// PHOCA DOWNLOAD UPDATE 1.2.0
		// ------------------------------------------
		
		// Filename_preview
		$updateFPR	= false;
		$updateFPR	= $this->AddColumnIfNotExists($errorMsg, "#__phocadownload", "filename_preview", "varchar(250) NOT NULL default ''", "filename" );
		if (!$updateFPR) {
			$msgSQL .= 'Error while updating Filename Preview column<br />';
		}
		
		// Filename_play
		$updateFPL	= false;
		$updateFPL	= $this->AddColumnIfNotExists($errorMsg, "#__phocadownload", "filename_play", "varchar(250) NOT NULL default ''", "filename" );
		if (!$updateFPL) {
			$msgSQL .= 'Error while updating Filename Play column<br />';
		}
		
		$updateIFS1	= false;
		$updateIFS1	= $this->AddColumnIfNotExists($errorMsg, "#__phocadownload", "image_filename_spec1", "varchar(250) NOT NULL default ''", "filename" );
		if (!$updateIFS1) {
			$msgSQL .= 'Error while updating Image Filename Spec1 column<br />';
		}
		
		$updateIFS2	= false;
		$updateIFS2	= $this->AddColumnIfNotExists($errorMsg, "#__phocadownload", "image_filename_spec2", "varchar(250) NOT NULL default ''", "filename" );
		if (!$updateIFS2) {
			$msgSQL .= 'Error while updating Image Filename Spec2 column<br />';
		}
		
		// ------------------------------------------
		// PHOCA DOWNLOAD UPDATE 1.3.0
		// ------------------------------------------
		
		// Approved
		$updateApr	= false;
		$updateApr	= $this->AddColumnIfNotExists($errorMsg, "#__phocadownload", "approved", "tinyint(1) NOT NULL default '0'", "published" );
		if (!$updateApr) {
			$msgSQL .= 'Error while updating Approved column<br />';
		}
		
		// Upload
		$updateUpl	= false;
		$updateUpl	= $this->AddColumnIfNotExists($errorMsg, "#__phocadownload_categories", "uploaduserid", "text", "access" );
		if (!$updateUpl) {
			$msgSQL .= 'Error while updating Upload User ID column<br />';
		}
		
		// Owner ID
		$updateOid	= false;
		$updateOid	= $this->AddColumnIfNotExists($errorMsg, "#__phocadownload", "owner_id", "int(11) NOT NULL default '0'", "id" );
		if (!$updateOid) {
			$msgSQL .= 'Error while updating Owner ID column<br />';
		}
		
		// Owner ID
		$fileSize	= false;
		$fileSize	= $this->AddColumnIfNotExists($errorMsg, "#__phocadownload", "filesize", "int(11) NOT NULL default '0'", "filename" );
		if (!$fileSize) {
			$msgSQL .= 'Error while updating File Size column<br />';
		}
		
		// ------------------------------------------
		// PHOCA DOWNLOAD UPDATE 1.3.2
		// ------------------------------------------
		
		$updateMK = false;
		$errorMsg	= '';
		$updateMK = $this->AddColumnIfNotExists( $errorMsg, "#__phocadownload", "metakey", "text", "params" );
		if (!$updateMK) {
			$msgSQL .= 'Error while updating Metakey (File) column';
		}
		$updateMKC = false;
		$errorMsg	= '';
		$updateMKC = $this->AddColumnIfNotExists( $errorMsg, "#__phocadownload_categories", "metakey", "text", "params" );
		if (!$updateMKC) {
			$msgSQL .= 'Error while updating Metakey (Category) column';
		}
		$updateMKS = false;
		$errorMsg	= '';
		$updateMKS = $this->AddColumnIfNotExists( $errorMsg, "#__phocadownload_sections", "metakey", "text", "params" );
		if (!$updateMKS) {
			$msgSQL .= 'Error while updating Metakey (Section) column';
		}
		$updateMD = false;
		$errorMsg	= '';
		$updateMD = $this->AddColumnIfNotExists( $errorMsg, "#__phocadownload", "metadesc", "text", "params" );
		if (!$updateMD) {
			$msgSQL .= 'Error while updating Metadesc (File) column';
		}
		$updateMDC = false;
		$errorMsg	= '';
		$updateMDC = $this->AddColumnIfNotExists( $errorMsg, "#__phocadownload_categories", "metadesc", "text", "params" );
		if (!$updateMDC) {
			$msgSQL .= 'Error while updating Metadesc (Category) column';
		}
		$updateMDS = false;
		$errorMsg	= '';
		$updateMDS = $this->AddColumnIfNotExists( $errorMsg, "#__phocadownload_sections", "metadesc", "text", "params" );
		if (!$updateMDS) {
			$msgSQL .= 'Error while updating Metadesc (Section) column';
		}
		
		// ------------------------------------------
		// PHOCA DOWNLOAD UPDATE 1.3.4
		// ------------------------------------------
		
		$query=' SELECT title FROM '.$db->quoteName('#__phocadownload_settings').' WHERE title = \'description\' LIMIT 1;'."\n";
		$db->setQuery($query);

		if (!$result = $db->loadObject()) {
			$query = "INSERT INTO ".$db->quoteName('#__phocadownload_settings')." VALUES (null, 'description', '','', 'textareaeditor');"."\n";
			$db->setQuery( $query );
			if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		}
		
		$updatePU = false;
		$errorMsg	= '';
		$updatePU = $this->AddColumnIfNotExists( $errorMsg, "#__phocadownload", "publish_up", "datetime NOT NULL default '0000-00-00 00:00:00'", "date" );
		if (!$updatePU) {
			$msgSQL .= 'Error while updating Publish Up column';
		}
		
		$updatePD = false;
		$errorMsg	= '';
		$updatePD = $this->AddColumnIfNotExists( $errorMsg, "#__phocadownload", "publish_down", "datetime NOT NULL default '0000-00-00 00:00:00'", "date" );
		if (!$updatePD) {
			$msgSQL .= 'Error while updating Publish Down column';
		}
		
		
		// ------------------------------------------
		// PHOCA DOWNLOAD UPDATE 1.3.4
		// ------------------------------------------
		
		// Access User ID
		$updateAid	= false;
		$updateAid	= $this->AddColumnIfNotExists($errorMsg, "#__phocadownload_categories", "accessuserid", "text", "access" );
		if (!$updateAid) {
			$msgSQL .= 'Error while updating Access User ID column<br />';
		}
		
		// Delete User ID
		$updateDid	= false;
		$updateDid	= $this->AddColumnIfNotExists($errorMsg, "#__phocadownload_categories", "deleteuserid", "text", "access" );
		if (!$updateDid) {
			$msgSQL .= 'Error while updating Delete User ID column<br />';
		}
		
	*/
		// CHECK TABLES
		
		$query =' SELECT * FROM '.$db->quoteName('#__phocadownload').' LIMIT 1;';
		$db->setQuery( $query );
		$result = $db->loadResult();
		if ($db->getErrorNum()) {
			$msgSQL .= $db->getErrorMsg(). '<br />';
		}
		
		$query =' SELECT * FROM '.$db->quoteName('#__phocadownload_categories').' LIMIT 1;';
		$db->setQuery( $query );
		$result = $db->loadResult();
		if ($db->getErrorNum()) {
			$msgSQL .= $db->getErrorMsg(). '<br />';
		}
		/*
		$query =' SELECT * FROM '.$db->quoteName('#__phocadownload_sections').' LIMIT 1;';
		$db->setQuery( $query );
		$result = $db->loadResult();
		if ($db->getErrorNum()) {
			$msgSQL .= $db->getErrorMsg(). '<br />';
		}
		
		$query =' SELECT * FROM '.$db->quoteName('#__phocadownload_settings').' LIMIT 1;';
		$db->setQuery( $query );
		$result = $db->loadResult();
		if ($db->getErrorNum()) {
			$msgSQL .= $db->getErrorMsg(). '<br />';
		}
		*/
		$query =' SELECT * FROM '.$db->quoteName('#__phocadownload_user_stat').' LIMIT 1;';
		$db->setQuery( $query );
		$result = $db->loadResult();
		if ($db->getErrorNum()) {
			$msgSQL .= $db->getErrorMsg(). '<br />';
		}
		
		$query =' SELECT * FROM '.$db->quoteName('#__phocadownload_licenses').' LIMIT 1;';
		$db->setQuery( $query );
		$result = $db->loadResult();
		if ($db->getErrorNum()) {
			$msgSQL .= $db->getErrorMsg(). '<br />';
		}
		
		$query =' SELECT * FROM '.$db->quoteName('#__phocadownload_tags').' LIMIT 1;';
		$db->setQuery( $query );
		$result = $db->loadResult();
		if ($db->getErrorNum()) {
			$msgSQL .= $db->getErrorMsg(). '<br />';
		}
		
		$query =' SELECT * FROM '.$db->quoteName('#__phocadownload_layout').' LIMIT 1;';
		$db->setQuery( $query );
		$result = $db->loadObjectList();
		if ($db->getErrorNum()) {
			$msgSQL .= $db->getErrorMsg(). '<br />';
		}
		
		if ((!isset($result->categories) && !isset($result->category) && !isset($result->file)) ||
		(isset($result->categories) && $result->category == '' && isset($result->category) && $result->category == '' && isset($result->file) && $result->file == '')
		) {
	
			$query ='INSERT INTO '.$db->quoteName('#__phocadownload_layout').' ('."\n";
		$query.=' '.$db->quoteName('id').' ,'."\n";
		$query.=' '.$db->quoteName('categories').' ,'."\n";
		$query.=' '.$db->quoteName('category').' ,'."\n";
		$query.=' '.$db->quoteName('file').' ,'."\n";
		$query.=' '.$db->quoteName('checked_out').' ,'."\n";
		$query.=' '.$db->quoteName('checked_out_time').' ,'."\n";
		$query.=' '.$db->quoteName('params').' )'."\n";
		$query.=' VALUES ('."\n";
		$query.=' NULL,'."\n";
		
		$query.=' '.$db->Quote('<div class="pd-categoriesbox">
<div class="pd-title">{pdtitle}</div>
{pdsubcategories}
{pdclear}
</div>').','."\n";
		$query.=' '.$db->Quote('<div class="pd-filebox">
{pdfiledesctop}
{pdfile}
<div class="pd-buttons">{pdbuttondownload}</div>
<div class="pd-buttons">{pdbuttondetails}</div>
<div class="pd-buttons">{pdbuttonpreview}</div>
<div class="pd-buttons">{pdbuttonplay}</div>
<div class="pd-mirrors">{pdmirrorlink2} {pdmirrorlink1}</div>
<div class="pd-rating">{pdrating}</div>
<div class="pd-tags">{pdtags}</div>
{pdfiledescbottom}
<div class="pd-cb"></div>
</div>').','."\n";
		$query.=' '.$db->Quote('<div class="pd-filebox">
{pdimage}
{pdfile}
{pdfilesize}
{pdversion}
{pdlicense}
{pdauthor}
{pdauthoremail}
{pdfiledate}
{pddownloads}
{pddescription}
{pdfeatures}
{pdchangelog}
{pdnotes}
<div class="pd-mirrors">{pdmirrorlink2} {pdmirrorlink1}</div>
<div class="pd-report">{pdreportlink}</div>
<div class="pd-rating">{pdrating}</div>
<div class="pd-tags">{pdtags}</div>
<div class="pd-cb"></div>
</div>').','."\n";
		$query.=' '.$db->Quote('0').','."\n";
		$query.=' '.$db->Quote('0000-00-00 00:00:00').','."\n";
		$query.=' NULL'."\n";
		$query.=' );'."\n";
			
			$db->setQuery( $query );
			if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		}
		// - - - - - - - - - - - - - - - - 
		
	
		// ------------------------------------------
		// PHOCA DOWNLOAD UPDATE 2.1.1
		// ------------------------------------------
	
		$updateVL = false;
		$errorMsg	= '';
		$updateVL = $this->AddColumnIfNotExists( $errorMsg, "#__phocadownload", "video_filename", "varchar(255) NOT NULL default ''", "params"  );
		if (!$updateVL) {
			$msgSQL .= 'Error while updating Video Filename column';
		}
	
		
		
		// Error
		if ($msgSQL !='') {
			$msgError .= '<br />' . $msgSQL;
		}
		if ($msgFile !='') {
			$msgError .= '<br />' . $msgFile;
		}
			
		// End Message
		if ($msgError !='') {
			$msg = JText::_( 'Phoca Download not successfully upgraded' ) . ': ' . $msgError;
		} else {
			$msg = JText::_( 'Phoca Download successfully upgraded' );
		}
		
		$link = 'index.php?option=com_phocadownload';
		$this->setRedirect($link, $msg);
	}
	
	function AddColumnIfNotExists(&$errorMsg, $table, $column, $attributes = "INT( 11 ) NOT NULL default '0'", $after = '' ) {
		
		
		$db				= JFactory::getDBO();
		$columnExists 	= false;

		$query = 'SHOW COLUMNS FROM '.$table;
		$db->setQuery( $query );
		if (!$result = $db->query()){return false;}
		$columnData = $db->loadObjectList();
		
		foreach ($columnData as $valueColumn) {
			if ($valueColumn->Field == $column) {
				$columnExists = true;
				break;
			}
		}
		
		if (!$columnExists) {
			if ($after != '') {
				$query = 'ALTER TABLE '.$db->quoteName($table).' ADD '.$db->quoteName($column).' '.$attributes.' AFTER '.$db->quoteName($after).';';
			} else {
				$query = 'ALTER TABLE '.$db->quoteName($table).' ADD '.$db->quoteName($column).' '.$attributes.';';
			}
			$db->setQuery( $query );
			if (!$result = $db->query()){return false;}
			$errorMsg = 'notexistcreated';
		}
		
		return true;
	}
}
// utf-8 test: ä,ö,ü,ø,ž
?>