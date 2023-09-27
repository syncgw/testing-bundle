<?php

/*
 *  WBXML decoder test
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2023 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\lib;

use syncgw\lib\Msg;
use syncgw\lib\WBXML;
use syncgw\lib\XML;
use syncgw\lib\Util;
use syncgw\lib\Config;

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
require_once('../../Functions.php');

$cnf = Config::getInstance();
$cnf->updVar(Config::DBG_SCRIPT, 'WBXML');
$cnf->updVar(Config::DBG_EXCL, [

	'syncgw\lib\Encoding',
	'syncgw\lib\XML',
]);
Util::CleanDir('comp*.*');
Util::CleanDir('WBXML*.*');

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
$start = 1;
$end   = 0;
foreach (explode('&', $_SERVER['QUERY_STRING']) as $cmd) {

    list($c, $p) = explode('=', $cmd);
    switch (strtoupper($c)) {

    case 'S':
        $start = $p;
        $end   = $p + 1;
        break;

    case 'E':
        $end = $p;
        break;

    default:
        msg('+++ Unknown parameter "'.$c.' = '.$p.'"');
        exit;
    }
}

echo 'Call Parameter:<br><br>';
echo 'S=  - Start test number (first to show) -> "'.$start.'"<br>';
echo 'E=  - End test number (last to show) -> "'.$end.'"<br>';
echo '<br>';

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
$wb   = WBXML::getInstance();

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
for ($cnt=$start; $cnt < $end; $cnt++) {

	$ifile = $cnf->getvar(Config::ROOT).'testing-bundle/mimedata/wbxml/in'.sprintf('%02d', $cnt).'.wbxml';
	$ofile = $cnf->getvar(Config::ROOT).'testing-bundle/mimedata/wbxml/out'.sprintf('%02d', $cnt).'.xml';

	if (!file_exists($ifile))
		break;

	if (!($buf = file_get_contents($ifile))) {

	 	msg('Can\'t open file '.$ifile);
	 	exit();
	}

	$o = new XML;
	$o->loadFile($ofile);
	msg(sprintf('%02d', $cnt).' Encoding XML document '.$ofile);
	$buf = $wb->Encode($o);
	if (!comp($buf, true, $ifile, 'Compare WBXML')) {

		$len = 256;
		Msg::WarnMsg($wrk = file_get_contents($ifile),
									'old WBXML (0-'.$len.') of '.strlen($wrk).' bytes', 0, $len);
		if (!$buf)
			Msg::WarnMsg('new WBXML is EMPTY');
		else
			Msg::WarnMsg($buf,
									'new WBXML (0-'.$len.') of '.strlen($buf).' bytes', 0, $len);
	}

	msg(sprintf('%02d', $cnt).' Decoding WBXML document');
	if ($buf) {

		$xml = $wb->Decode($buf);
		$xml->getVar('SyncML');
		comp($xml, true, $ofile, 'Compare XML');
	}
}

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('+++ End of script');
