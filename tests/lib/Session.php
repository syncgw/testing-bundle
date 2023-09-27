<?php

/*
 *  Session handler test
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2023 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\lib;

use syncgw\lib\Msg;
use syncgw\lib\DataStore;
use syncgw\lib\Server;
use syncgw\lib\Session;
use syncgw\lib\Util;
use syncgw\lib\Config;

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
require_once('../../Functions.php');

$cnf = Config::getInstance();
$cnf->updVar(Config::DBG_SCRIPT, 'Session');
$cnf->updVar(Config::DBG_EXCL, [

	'syncgw\lib\XML',
	'syncgw\lib\Config:getVar',
]);

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('Setting data base to "file"');
setDB('file');

msg('Setting handler to "ActiveSync"');
$cnf->updVar(Config::HANDLER, 'MAS');

$sess = Session::getInstance();
$sess->mkSession();

msg('Adding variable "Pitty=jugabuha" to session');
$sess->updSessVar('Pitty', 'jugabuha');
msg('Adding variable "Manga=pilox" in data store "'.Util::HID(Util::HID_CNAME, DataStore::CONTACT).'" to session');
$sess->updSessVar('Manga', 'pilox', DataStore::CONTACT);
$sess->getVar('syncgw');
Msg::InfoMsg($sess);

msg('Overwrite variable "Pitty=dapaletaa" in session');
$sess->updSessVar('Pitty', 'dapaletaa');
$id = $sess->getVar('GUID');
$sess->getVar('syncgw');
Msg::InfoMsg($sess);

msg('Shutdown sync*gw server');
Server::getInstance()->shutDown();
setDB('file', DataStore::CONTACT);

msg('Restarting session "'.$id.'"');
$sess = Session::getInstance();
if (!$sess->mkSession()) {

	msg('Failed!');
	exit();
}
msg('Ok');

msg('Variable "Pitty" is "'.$sess->updSessVar('Pitty').'"');
msg('Variable "Manga" is "'.$sess->updSessVar('Manga', '4711', DataStore::CONTACT).'"');

msg('Shutdown sync*gw server');
Server::getInstance()->shutDown();
setDB('file', DataStore::CONTACT);

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('+++ End of script');
