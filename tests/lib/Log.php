<?php

/*
 *  Log and error test
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2023 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\lib;

use syncgw\lib\Log;
use syncgw\lib\Config;
use syncgw\lib\ErrorHandler;

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
require_once('../../Functions.php');

$cnf = Config::getInstance();
$cnf->updVar(Config::DBG_SCRIPT, 'Log');
$cnf->updVar(Config::DBG_EXCL, [

	'syncgw\lib\XML:hasChild',
	'syncgw\lib\XML:addVar',
	'syncgw\lib\Config:getVar',
	'syncgw\lib\HTTP',
]);

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('Basic logging functions');

$cnf->updVar(Config::LOG_LVL, Log::ERR|Log::WARN|Log::INFO|Log::APP|Log::DEBUG);

$log = Log::getInstance();
$log->setLogMsg([ 9999 => 'Hello world', 9998 => 'User error raised with "%s"', ]);

msg("All currently defined messages");
print_r($log->getLogMsg());

msg('Message "Hello world" should be shown!');
$log->logMsg(Log::INFO, 9999);

if (class_exists('syncgw\\lib\\ErrorHandler')) {
	msg('User error "9998"');
	ErrorHandler::getInstance()->Raise(9998, 'Additional information');
}

msg('Unknown log message "9997"');
$log->logMsg(Log::ERR, 9997, 'Unknown log message!');

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('+++ End of script');
