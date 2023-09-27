<?php

/*
 *  HTTP handler test
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2023 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\lib;

use syncgw\lib\Msg;
use syncgw\lib\HTTP;
use syncgw\lib\Config;

require_once('../../Functions.php');

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
$cnf = Config::getInstance();
$cnf->updVar(Config::DBG_SCRIPT, 'HTTP');
$cnf->updVar(Config::DBG_EXCL, [

	'syncgw\lib\DTD:getInstance',
]);

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------

require_once 'D:\Software\syncgw\core-bundle\src\lib\HTTP.php';
$http = HTTP::getInstance();

$http->receive($_SERVER, file_get_contents('php://input'));

msg('Get/Put');
Msg::InfoMsg($http->getHTTPVar(HTTP::RCV_HEAD), 'Input header');
Msg::InfoMsg('getVar "SCRIPT_FILENAME" = "'.$http->getHTTPVar('SCRIPT_FILENAME').'"');

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('+++ End of script');
