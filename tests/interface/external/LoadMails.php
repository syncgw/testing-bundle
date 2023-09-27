<?php
declare(strict_types=1);

/*
 *  Load Mail records
 *
 *	@package	sync*gw
 *	@subpackage	Tools
 *	@copyright	(c) 2008 - 2023 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\interface\external;

use syncgw\lib\Msg;
use syncgw\lib\Config;
use syncgw\lib\DB;
use syncgw\lib\XML;
use syncgw\lib\DataStore;
use syncgw\lib\Util;
use syncgw\lib\Attachment;
use syncgw\document\field\fldAttach;
use syncgw\document\field\fldAttribute;

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
require_once('../../../Functions.php');

$cnf = Config::getInstance();
$cnf->updVar(Config::DBG_SCRIPT, 'LoadMails');
$cnf->updVar(Config::DBG_EXCL, [

	'syncgw\lib\Msg::Caller',
	'syncgw\lib\XML::hasChild',
	'syncgw\lib\XML::getVal',
	'syncgw\lib\XML::addVar',
	'syncgw\lib\XML::getVar',
	'syncgw\lib\XML::getItem',
	'syncgw\lib\XML::updVar',
	'syncgw\lib\Config:getVar',
]);

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
$be  = 'mail';
$hid = DataStore::EXT|DataStore::MAIL;

Msg::InfoMsg('Starting back end handler "'.$be.'"');
setDB($be, $hid);

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
$uid  = 't1@dev.fd';
msg('Authorizing user "'.$uid.'"');
$host = '';
if (strpos($uid, '@'))
	list($uid, $host) = explode('@', $uid);
$db = DB::getInstance();
if (!$db->Authorize($uid, $host, 'mamma')) {

	msg('+++ Login failed!', Config::CSS_ERR);
   	exit;
}

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('Deleting INBOX content - folder itself will not be deleted');
$db  = DB::getInstance();
Msg::InfoMsg($recs = $db->getRIDS($hid));
foreach ($recs as $gid => $att) {

	if ($att != DataStore::TYP_GROUP)
		$db->Query($hid, DataStore::DEL, $gid);
	else {

		$xml = $db->Query($hid, DataStore::RGID, $gid);
		if ($xml->getVar(fldAttribute::TAG) & fldAttribute::MBOX_USER)
			$db->Query($hid, DataStore::DEL, $gid);
	}
}

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
$dir = $cnf->getVar(Config::ROOT).'testing-bundle/appdata/rc-mail/';
$xml = new XML();
$att = Attachment::getInstance();

msg('Loading mails from '.$dir);

for ($cnt=1; file_exists($file = $dir.sprintf('sgw.%02d', $cnt)); $cnt++) {

	msg('Loading file "'.$file.'"');
	$xml->loadFile($file);

	if ($xml->xpath('//Data/'.fldAttach::TAG)) {

		while ($xml->getItem() !== null) {

			$xp = $xml->savePos();
			$name = $xml->getVar(fldAttach::SUB_TAG[1], false);
			msg('Saving attachment "'.$name.'"');
			$att->create(file_get_contents($dir.$name));
			$xml->restorePos($xp);
		}
	}

	msg('Creating mail record');
	$xml->getVar('syncgw');
	Msg::InfoMsg($xml, 'Mail record');

	// do MIME comparison only
	if (0) {

		$n = Util::Save('MIME%02d.txt', $db->cnv2MIME($xml));
	 	exec('C:\Windows\System32\fc.exe /N /T /L '.$n.' '.$dir.sprintf('org.%02d', $cnt).' > '.$dir.'Compare 2>&1');
 		echo '<br><hr><font color="green"><h3>'.''.'</h3>'.
	 	     XML::cnvStr(str_replace('*****', '+++', file_get_contents($dir.'Compare'))).
 		     '</font><hr><br>';

	} else {

		if (!$db->Query($hid, DataStore::ADD, $xml)) {
	        msg('+++ Record "'.$cnt.'" not written', Config::CSS_WARN);
	        exit;
		}
	}
}

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('+++ End of script');
