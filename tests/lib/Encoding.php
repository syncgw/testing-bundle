<?php

/*
 *  Encoding handler test
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2023 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\lib;

use syncgw\lib\Msg;
use syncgw\lib\Config;
use syncgw\lib\Encoding;

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
require_once('../../Functions.php');

$cnf = Config::getInstance();
$cnf->updVar(Config::DBG_SCRIPT, 'Encoding');
$cnf->updVar(Config::DBG_EXCL, [

	'syncgw\lib\XML:hasChild',
	'syncgw\lib\XML:getItem',
	'syncgw\lib\Config:getVal',
]);

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
$enc = Encoding::getInstance();

$s  = 'Hello world';
msg('Input string: "'.$s.'"');

$cs = 1018;
msg('Setting external character set "'.$cs.'" - SHOULD WORK');
$enc->setEncoding($cs);
$t = $enc->getEncoding();
msg('External character set: "'.$t.'"');

$cs = 'iso-IR-1101';
msg('Setting illegal external character set "'.$cs.'"');
$enc->setEncoding($cs);
$t = $enc->getEncoding();
msg('External character set: "'.$t.'"');

$cs = 'UTF-32be';
msg('Setting external character set "'.$cs.'"');
$enc->setEncoding($cs);
$t = $enc->getEncoding();
msg('External character set: "'.$t.'"');

msg('Encode string to external');
$t = $enc->export($s, false);
Msg::InfoMsg($t, 'String dump', 0);

msg('Decode string back to internal');
$t = $enc->import($t, true);
Msg::InfoMsg($t, 'String dump', 0);

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('+++ End of script');
