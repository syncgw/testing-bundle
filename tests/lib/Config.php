<?php

/*
 *  Config handler test
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2024 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\lib;

use syncgw\lib\Config;

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
require_once('../../Functions.php');

$cnf = Config::getInstance();
$cnf->updVar(Config::DBG_SCRIPT, 'Config');
$cnf->updVar(Config::DBG_EXCL, [

	'syncgw\lib\XML:hasChild',
	'syncgw\lib\XML:addVar',
	'syncgw\lib\XML:saveXML',
]);

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
$cnf = Config::getInstance();
msg('Setting invalid configuration paramater "KO"');
$cnf->updVar('KO', 'Does not work');

msg('Setting valid configuration paramater "Usr_KO"');
$cnf->updVar('Usr_KO', 'Mamma');
msg('Parameter value for "Usr_KO": '.$cnf->getVar('Usr_KO'));

msg('Parameter value for "Config::CRONJOB": '.$cnf->getVar(Config::CRONJOB));
msg('Setting invalid value "klo" to "Config::CRONJOB"');
$cnf->updVar(Config::CRONJOB, 'klo');
msg('Parameter value for "Config::CRONJOB": '.$cnf->getVar(Config::CRONJOB));

msg('Parameter value for "Config::LOG_DEST": '.$cnf->getVar(Config::LOG_DEST));
msg('Setting valid value "Heaven" for "Config::LOG_DEST"');
$cnf->updVar(Config::LOG_DEST, 'Heaven').'<br>';
msg('Parameter value for "Config::LOG_DEST": '.$cnf->getVar(Config::LOG_DEST));

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('+++ End of script');
