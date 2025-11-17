<?php
declare(strict_types=1);

use syncgw\lib\ErrorHandler;

/*
 *  PHP interfaces functions
 *
 *	@package	sync*gw
 *	@subpackage	Testing
 *	@copyright	(c) 2008 - 2025 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

/**
 *  sync*gw class auto loader
 *
 *	@param 	- Class name to load
 *  @return - true or false
 */
spl_autoload_register(function ($class): bool {
	static $_Loader = [];

	if (isset($_Loader[$class]))
		return true;

	$file = null;

	foreach ( [
		'syncgw\\lib' 						=> 'core-bundle/src/lib',
		'syncgw\\gui'						=> 'gui-bundle/src',
		'syncgw\\document\\doc'				=> 'core-bundle/src/document',
		'syncgw\\document\\mime\\mimH'		=> 'core-bundle/src/document/mime',
		'syncgw\\document\\mime\\mimR'		=> 'core-bundle/src/document/mime',
		'syncgw\\document\\field'			=> 'core-bundle/src/document/field',
		'syncgw\\interface\\DB'				=> 'core-bundle/src/interface',
		'syncgw\\interface\\file'			=> 'file-bundle/src/',
		'syncgw\\interface\\mysql'			=> 'mysql-bundle/src',
		'syncgw\\interface\\roundcube'		=> 'roundcube-bundle/src',
		'syncgw\\interface\\mail'			=> 'mail-bundle/src',
		'syncgw\\interface\\myapp'			=> 'myapp-bundle/src',
		'syncgw\\activesync\\mime'			=> 'activesync-bundle/src/mime',
		'syncgw\\activesync'				=> 'activesync-bundle/src',
		'syncgw\\webdav\\mime'				=> 'webdav-bundle/src/mime',
		'syncgw\\webdav'					=> 'webdav-bundle/src',
		'syncgw\\mapi'						=> 'mapi-bundle/src',
		'syncgw\\ics'						=> 'ics-bundle/src',
		'syncgw\\rpc'						=> 'rpc-bundle/src',
		'syncgw\\rops'						=> 'rops-bundle/src',
		'tests\\document\\field'			=> 'testing-bundle/tests/document/field',
		'tests'								=> 'testing-bundle/src',
		'PHPMailer\\PHPMailer\\PHPMailer'	=> '../phpmailer/phpmailer/src',
		'PHPMailer\\PHPMailer\\SMTP'		=> '../phpmailer/phpmailer/src',
		'Sabre\\Event'						=> '../sabre/event/lib',
		'Sabre\\Xml'						=> '../sabre/xml/lib',
		'Sabre\\HTTP\\Auth'					=> '../sabre/http/lib/Auth',
		'Sabre\\HTTP'						=> '../sabre/http/lib',
		'Sabre'								=> 'sabre/lib/',
		'Psr\\Log\\'						=> '../psr/log/src',
	] as $c => $d)
		if (strpos($class, $c) !== false) {

			if ($c == 'Sabre')
				$file = $_SERVER['DOCUMENT_ROOT'].'/vendor/syncgw/'.$d.
						str_replace('\\', '/', substr($class, 6)).'.php';
			else
				$file = $_SERVER['DOCUMENT_ROOT'].'/vendor/syncgw/'.$d.'/'.substr($class, strrpos($class, '\\') + 1).'.php';
			break;
		}

	if (!$file)
		return false;

    // Only use this very carefully - everything might go into wrong direction
	if (!file_exists($file)) {

		echo '<pre><code style="color:red;">+++ ERROR: Class "'.$class.'" in "'.$file.'" not found!</code><br>';
        if (class_exists('syncgw\\lib\\ErrorHandler'))
			foreach (ErrorHandler::Stack() as $msg)
 		       echo '<code style="color:red;">'.htmlspecialchars($msg).'</code><br>';
		echo '<br>';
		return false;
	}

	// autoload class file - we only take care about our own files
    require_once($file);
  	$_Loader[$class] = 1;

    return true;
});
