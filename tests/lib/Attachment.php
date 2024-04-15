<?php

/*
 *  Attachment handler class test
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2024 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\lib;

use syncgw\lib\Msg;
use syncgw\lib\Attachment;
use syncgw\lib\Config;
use syncgw\lib\DB;
use syncgw\lib\DataStore;

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
require_once('../../Functions.php');

$cnf = Config::getInstance();
$cnf->updVar(Config::DBG_SCRIPT, 'Attachment');
$cnf->updVar(Config::DBG_EXCL, [

	'syncgw\lib\XML',
	'syncgw\lib\Config:getVar',
	'syncgw\lib\Attachment::getVar',
]);

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('Setting data base to "file"');
setDB('file');

$instr = 'Hello world, how are you today!';

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
$cnf = Config::getInstance();
msg('Set database record size to "6"');
$cnf->updVar(Config::DB_RSIZE, 6);

$att = Attachment::getInstance();
msg('Write attachment');
$gid = $att->create($instr);
msg('Reading attachment "'.$gid.'"');
$f = $att->read($gid);
msg('Attachment data "'.$f.'" with " '.$att->getVar('Size').' bytes');
msg('Dumping data store');
show();

msg('Deleting attachment record "'.$gid.'"');
$db = DB::getInstance();
$db->Query(DataStore::ATTACHMENT, DataStore::DEL, $gid);
show();

msg('Rewrite attachment');
msg('Set database record size to "1440"');
$cnf->updVar(Config::DB_RSIZE, 1400);
$att = Attachment::getInstance();
$att->create($instr);
$f = $att->read($gid);
msg('Attachment data "'.$f.'" with "'.$att->getVar('Size').'" bytes');
msg('Dumping data store');
show($att);

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('+++ End of script');

function show(): void {

 	$db = DB::getInstance();
 	foreach ($db->Query(DataStore::ATTACHMENT, DataStore::RIDS) as $gid => $typ) {

 	    if ($typ == DataStore::TYP_DATA)
 	        continue;

 	    $xml = $db->Query(DataStore::ATTACHMENT, DataStore::RGID, $gid);
 	 	$xml->getVar('syncgw');
 	 	Msg::InfoMsg($xml, 'Group record');
 	 	foreach ($db->Query(DataStore::ATTACHMENT, DataStore::RIDS, $gid) as $id => $unused) {

 	 		$xml = $db->Query(DataStore::ATTACHMENT, DataStore::RGID, $id);
 	 		$xml->getVar('syncgw');
 	 		Msg::InfoMsg($xml, 'Sub record');
 	 	}
 	 	$unused; // disable Eclipse warning
 	}
}
