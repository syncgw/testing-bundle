<?php
declare(strict_types=1);

/*
 *  Decode group attribute
 *
 *	@package	sync*gw
 *	@copyright	(c) 2008 - 2023 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\helper;

use syncgw\lib\Config;
use syncgw\document\field\fldAttribute;

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
require_once('../../Functions.php');

Config::getInstance()->updVar(Config::DBG_SCRIPT, 'ShowAttr');

if (!strlen($_SERVER['QUERY_STRING'])) {
	msg('+++ Missing parameter', Config::CSS_ERR);
	exit;
}
$args = explode('&', $_SERVER['QUERY_STRING']);

if (!isset($args[0])) {
	msg('+++ Missing attribute', Config::CSS_ERR);
	exit;
}

msg('+++ Attributes: '.fldAttribute::showAttr(intval($args[0])));

msg('+++ End of script');

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
