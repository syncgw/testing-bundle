<?php
declare(strict_types=1);

/*
 *  Test helper functions
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2024 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

use syncgw\lib\Config;
use syncgw\lib\Log;
use syncgw\lib\Msg;
use syncgw\lib\Encoding;
use syncgw\lib\DataStore;
use syncgw\lib\Util;
use syncgw\lib\XML;
use syncgw\lib\ErrorHandler;

require_once(__DIR__.'/Loader.php');

if (class_exists('syncgw\lib\\ErrorHandler'))
	ErrorHandler::getInstance();

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
echo '<pre>';
msg('Start of script - search for "+++" to locate error', Config::CSS_WARN);

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
// enable log reader
# Log::getInstance()->Plugin('logread', NULL);

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
// enable debug output
Config::getInstance()->updVar(Config::DBG_LEVEL, Config::DBG_VIEW);

/**
 * 	Show message
 *
 * 	@param	- Message string
 * 	@param	- Color setting (CSS_*)
 */
function msg(string $str, string $col = 'color: #009933'): void {

 	echo '<font style="'.$col.'">'.str_pad('', 67, '-').' </font>'.
 		 '<font style="'.$col.'" size=+2><strong>'.XML::cnvStr($str).'</strong></font><br>';
}

/**
 * 	Load reader function
 *
 * 	@param	- Log message type
 * 	@param	- Long message
 */
function logread(int $typ, string $msg): void {

	$map = [
   		Log::ERR 	=> Config::CSS_ERR,
   		Log::WARN	=> Config::CSS_WARN,
   		Log::INFO	=> Config::CSS_INFO,
   		Log::APP	=> Config::CSS_APP,
 		Log::DEBUG  => Config::CSS_DBG,
 	];

	$typ &= ~Log::ONETIME;
 	echo '<font style="'.$map[$typ].'"><strong>'.str_pad('LOG MESSAGE ', 67, $typ == Log::ERR ? '+' : '-').
 		 ' '.XML::cnvStr(trim($msg)).'</strong></font><br />';
}

/**
 * 	Compare XML object with data
 *
 * 	@param	- XML object to compare / First file name
 * 	@param	- true=whole document; false=from current position
 * 	@param	- File name to compare with
 *  @param  - Additional message to show
 *  @return - true= Equal; false= Changes detected
 */
function comp($out, bool $top = true, string $name = '', string $msg = ''): bool {

 	$cnf = Config::getInstance();
 	$dir = $cnf->getVar(Config::DBG_DIR);
 	$enc = Encoding::getInstance();

	if (is_object($out)) {

		$bdy = $out->saveXML($top, true);
	 	$out = Util::Save('comp%d.xml', $bdy);
	 	$mod = '/N /T /L';
	} else {

		$out = Util::Save('wbxml%d.wbxml', $out);
		$mod = '/B';
	}
	if (!file_exists($name)) {

  		msg('+++ FILE "'.$name.'" DOES NOT EXIST', Config::CSS_ERR);
  		exit;
 	}

 	exec('C:\Windows\System32\fc.exe '.$mod.' '.$out.' '.$name.' > '.$dir.'Compare 2>&1');
 	$wrk = $enc->import(file_get_contents($dir.'Compare'));
 	if (strpos($wrk, 'Keine Unterschiede') === false) {

 		$wrk .= '+++ Changes detected!'."\n\n";
		echo '<br><hr><strong><font color="red"><h3>'.$msg.'</h3>'.htmlentities($wrk, ENT_SUBSTITUTE).
			 '</font></strong><hr><br>';
		return false;
 	}

	echo '<br><hr><font color="green"><h3>'.$msg.'</h3>'.XML::cnvStr($wrk).'</font><hr><br>';

	return true;
}

/**
 * 	Configure data base backend for testing
 *
 * 	@param	- Back end name (defauts to 'file')
 * 	@param	- Config Handler ID (defaults no none)
 */
function setDB(string $be = 'file', int $hid = -1): void {
	static $_fildel = 0;

 	$cnf = Config::getInstance();
 	$cnf->updVar(Config::TRACE_CONF, 'Off');
 	$cnf->updVar(Config::LOG_LVL, Log::ERR|Log::WARN|Log::INFO|Log::APP|Log::DEBUG);

 	// force endless max_execution
	$cnf->updVar(Config::EXECUTION, 0);

 	switch ($be) {

 	case 'file':
  		if (!$_fildel) {

		    // cleanup file directory
		    $dir = $cnf->getVar(Config::FILE_DIR);
    		Msg::InfoMsg('Deleting files in "'.$dir.'"');
	  	    Util::rmDir($dir);
	        mkdir($dir);
	  	    $_fildel = 1;
 		}
 		$cnf->updVar(Config::DATABASE, $be);
  		$mid = DataStore::DATASTORES & ~DataStore::MAIL;
  		break;

 	case 'mail':
 	case 'mysql':
 	case 'roundcube':
	case 'myapp':
 		$cnf->updVar(Config::DATABASE, $be);
  		$mid = DataStore::DATASTORES & ~DataStore::MAIL;
		break;

 	default:
  		msg('+++ Unknown back end "'.$be.'"', Config::CSS_ERR);
  		exit;
 	}

 	if ($hid != -1) {

 	    $hid = $hid & $mid;
	 	$cnf->updVar(Config::ENABLED, $hid);
	 	if (is_array($h = Util::HID(Util::HID_CNAME, $hid)))
	 		$h = implode(', ', $h);
	 	Msg::InfoMsg('Using handler "'.$h.'"');
 	}
}
