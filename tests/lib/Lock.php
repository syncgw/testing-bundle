<?php

/*
 *  Locking test
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2023 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\lib;

use syncgw\lib\Config;
use syncgw\lib\Lock;

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
require_once('../../Functions.php');

$cnf = Config::getInstance();
$cnf->updVar(Config::DBG_SCRIPT, 'Lock');
$cnf->updVar(Config::DBG_EXCL, [

	'syncgw\lib\Config:getVar',
]);

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
$lock = 'test';

$lck = Lock::getInstance();

msg('Set lock with counter');
$lck->lock($lock, true);

msg('Unlock without deletion');
$lck->unlock($lock, false);

msg('Set lock with counter');
$lck->lock($lock, true);

msg('Unlock without deletion');
$lck->unlock($lock, false);

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('+++ End of script');
