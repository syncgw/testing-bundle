<?php

/*
 *  DTD handler test
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2023 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\lib;

use syncgw\lib\Msg;
use syncgw\lib\Config;
use syncgw\lib\DTD;

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
require_once('../../Functions.php');

$cnf = Config::getInstance();
$cnf->updVar(Config::DBG_SCRIPT, 'DTD');
$cnf->updVar(Config::DBG_EXCL, [

	'syncgw\lib\XML:loadFile',
	'syncgw\lib\XML:hasChild',
	'syncgw\lib\XML:getVar',
	'syncgw\lib\XML:getVal',
	'syncgw\lib\XML:saveXML',
]);

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------

$dtd = DTD::getInstance();
$n = 399282;
msg('Setting "UNKNOWN" DTD "'.$n.'"');
$dtd->actDTD($n);

$n = 8196;
msg('Setting "KNOWN" DTD "'.$n.'"');
$dtd->actDTD($n);

msg('Getting PID and Name');
Msg::InfoMsg('PID="'.$dtd->getVar('PID', false).'"');
Msg::InfoMsg('Name="'.$dtd->getVar('Name', false).'"');

msg('Getting "99" (should be "Unknown-0x63")');
$t = $dtd->getTag('99');
Msg::InfoMsg('Return Value: "'.$t.'"');

$nn = 'Unknown-0x63';
msg('Getting "'.$nn.'" (should be 99)');
$t = $dtd->getTag($nn);
Msg::InfoMsg('Return Value: "'.$t.'"');

msg('Getting "0x16" (should be "ExceptionStartTime")');
$t = $dtd->getTag(strval(0x16));
Msg::InfoMsg('Return Value: '.$t);

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('+++ End of script');
