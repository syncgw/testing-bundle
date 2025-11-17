<?php

/*
 *  Basic data base handler functions
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2025 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\lib;

use syncgw\lib\Msg;
use syncgw\lib\Config;
use syncgw\lib\DataStore;
use syncgw\lib\User;

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
require_once('../../Functions.php');

$cnf = Config::getInstance();
$cnf->updVar(Config::DBG_SCRIPT, 'User');
$cnf->updVar(Config::DBG_EXCL, [

	'syncgw\lib\XML',
	'syncgw\lib\Config:getVar',
]);

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('Setting data base to "file"');
setDB('file', DataStore::USER);

$usr = User::getInstance();

msg('Login to internal file datastore');
$usr->Login($cnf->getVar(Config::DBG_USR), $cnf->getVar(Config::DBG_UPW), 'BubbaDevice');
$usr->setTop();
Msg::InfoMsg($usr, 'User object');

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('+++ End of script');
