<?php
declare(strict_types=1);

/*
 *  Decode MAPI / HTTP
 *
 *	@package	sync*gw
 *	@subpackage	Test scripts
 *	@copyright	(c) 2008 - 2025 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\mapi;

use syncgw\lib\Config;
use syncgw\lib\Encoding;
use syncgw\lib\HTTP;
use syncgw\lib\DB;
use syncgw\lib\Msg;
use syncgw\mapi\mapiHandler;
use syncgw\lib\XML;
use syncgw\mapi\mapiHTTP;

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
require_once('../../Functions.php');

$cnf = Config::getInstance();
$cnf->updVar(Config::DBG_SCRIPT, 'mapiDecode');
$cnf->updVar(Config::DBG_EXCL, [

	'syncgw\lib\User',
	'syncgw\lib\Log',
	'syncgw\lib\XML',
	'syncgw\lib\Config:getVar',
	'syncgw\lib\Server:shutDown',
]);

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
$parms = explode('&', $_SERVER['QUERY_STRING']);

foreach ($parms as $parm) {

	list($c, $p) = explode('=', $parm);
	switch($c) {
	// Cmd=GetProps
	case 'Cmd':
		$cmd = $p;
		break;

	// Typ=req(n)
	// Typ=resp(n)
	case 'Typ':
		$mod = $p;
		break;

	default:
		break;
	}
}

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------

msg('Authorizing user "'.($uid = $cnf->getVar(Config::DBG_USR)).'"');
$host = '';
$db   = DB::getInstance();
if (strpos($uid, '@'))
	list($uid, $host) = explode('@', $uid);
if (!$db->Authorize($uid, $host, $cnf->getVar(Config::DBG_UPW))) {

	msg('+++ Login failed!', Config::CSS_ERR);
   	exit;
}

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------

$cnf->updVar(Config::HANDLER, 'MAPI');

$enc = Encoding::getInstance();
$enc->setEncoding('UTF-8');

$http = HTTP::getInstance();

$n = str_replace([ 'req', 'mkresp', 'resp' ], [ null, null, null ], $mod);
$reqXML = $cnf->getVar(Config::TMP_DIR).$cmd.$n.'.xml';

if (substr($mod, 0, 3) != 'req') {

	if (!file_exists($reqXML)) {

		Msg('+++ Please call first decoding of <'.$cmd.'> request ['.$n.']', Config::CSS_WARN);
		exit;
	}
	$xml = new XML();
	$xml->loadFile($reqXML);
	$http->updHTTPVar(HTTP::RCV_BODY, null, $xml);
	Msg::InfoMsg('Loading "'.$reqXML.'" as XML converted request body');
}

if (substr($mod, 0, 2) == 're') {
	$fnam = $cnf->getVar(Config::ROOT).'testing-bundle/mimedata/mapi/'.strtolower($cmd).'_'.$mod.'.bin';
	Msg('Loading "'.$fnam.'"');
	$bdy = file_get_contents($fnam);
}

if (substr($mod, 0, 3) == 'req') {

	$http->updHTTPVar(HTTP::SERVER, 'REQUEST_METHOD', 'POST');
	$http->updHTTPVar(HTTP::SERVER, 'REQUEST_URI', '/mapi/1');
	$http->updHTTPVar(HTTP::SERVER, 'HTTP_X_REQUESTTYPE', $cmd);
	$http->updHTTPVar(HTTP::RCV_BODY, null, $bdy);

	Msg('Decoding "'.$cmd.'" request');
	$http->checkIn();
	Msg::InfoMsg($http->getHTTPVar(HTTP::RCV_BODY), 'Decoded <'.$cmd.'> Request');

	$xml = $http->getHTTPVar(HTTP::RCV_BODY);
	if (is_object($xml))
		$xml->saveFile($reqXML);
} else { // resp / mkresp

	$http->updHTTPVar(HTTP::SND_HEAD, 'X-Requesttype', $cmd);

	if (substr($mod, 0, 4) == 'resp') {

		$http->addBody($bdy);
		Msg('Encoding "'.$cmd.'" response');
	} else {

		$mapi = mapiHandler::getInstance();
		if ($xml = $mapi->Parse($cmd, mapiHTTP::MKRESP))
			$http->addBody($xml);
		Msg('Creating "'.$cmd.'" response');
	}
	$http->checkOut();
##	Util::Save('req999', $http->getHTTPVar(HTTP::SND_BODY));
}

msg('+++ End of script');

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
