<?php

/*
 *  Load (and sort) device information test
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2023 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\lib;

use syncgw\lib\Msg;
use syncgw\lib\Config;
use syncgw\lib\DB;
use syncgw\lib\DataStore;
use syncgw\lib\Device;
use syncgw\lib\Server;
use syncgw\lib\XML;

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
require_once('../../Functions.php');

$cnf = Config::getInstance();
$cnf->updVar(Config::DBG_SCRIPT, 'Device');
$cnf->updVar(Config::DBG_EXCL, [

	'syncgw\lib\XML',
	'syncgw\lib\Config:getVar',
]);

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('Setting handler to "ActiveSync"');

$cnf->updVar(Config::HANDLER, 'MAS');

msg('Setting data base to "file"');
setDB('file');
$dev = Device::getInstance();

$file = $cnf->getVar(Config::ROOT).'activesync-bundle/assets/device.xml';
msg('Loading XML file '.$file);
$xml = new XML();
$xml->loadFile($file);

msg('Activating "IMEI:dummy" device');
$dev->actDev('IMEI:empty');
$dev->getVar('syncgw');
Msg::InfoMsg($dev, 'Device "IMEI:dummy (should be ActiveSync skeleton)"');

msg('Restart server');
$srv = Server::getInstance();
$srv->shutDown();
setDB('file', DataStore::CONTACT);
$dev = Device::getInstance();

msg('Activating device "IMEI:dummy" - should show imported device');
$dev->actDev('IMEI:dummy');
$dev->getVar('syncgw');
Msg::InfoMsg($dev);

msg('Delete device information');
$db = DB::getInstance();
$db->Query(DataStore::DEVICE, DataStore::DEL, 'IMEI:dummy');

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('+++ End of script');
