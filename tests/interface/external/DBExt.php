<?php

/*
 *  External handler query test
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2024 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\interface\external;

use syncgw\lib\Msg;
use syncgw\lib\Config;
use syncgw\lib\DB;
use syncgw\lib\DataStore;
use syncgw\lib\Util;
use syncgw\document\field\fldAttribute;
use syncgw\document\field\fldGroupName;
use syncgw\document\field\fldFullName;
use syncgw\document\field\fldSummary;

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
require_once('../../../Functions.php');

$cnf = Config::getInstance();
$cnf->updVar(Config::DBG_SCRIPT, 'DBExt');
$cnf->updVar(Config::DBG_EXCL, [

	'syncgw\lib\Log:Caller',
	'syncgw\lib\XML:hasChild',
	'syncgw\lib\XML:getVal',
	'syncgw\lib\XML:addVar',
	'syncgw\lib\XML:getVar',
	'syncgw\lib\XML:getName',
	'syncgw\lib\ML:getItem',
	'syncgw\lib\XML:updVar',
	'syncgw\lib\Config:getVar',
	'syncgw\lib\User',
	'syncgw\lib\Device',
]);

Util::CleanDir('comp*.*');

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
// we store variables, so we need to start session
session_start();

const xxREAD      = 0x01;
const xxDUPLICATE = 0x02;
const xxUPDATE    = 0x04;
const xxCOMP      = 0x08;
const xxDELETE    = 0x10;
const xxGRPS 	  = 0x20;
const xxRIDS	  = 0x40;

$tests		 	  = [
	1 	=> 	[ 'myapp',			DataStore::NOTE,		xxREAD,			[ fldGroupName::TAG, fldSummary::TAG ],		],
	2	=> 	[ 'myapp',			DataStore::NOTE,		xxDUPLICATE,	[ fldGroupName::TAG, fldSummary::TAG ],		],
	3	=> 	[ 'myapp',			DataStore::NOTE,		xxUPDATE,		[ fldGroupName::TAG, fldSummary::TAG ],		],
	4	=> 	[ 'myapp',			DataStore::NOTE,		xxCOMP,			[ fldGroupName::TAG, fldSummary::TAG ],		],
	5	=> 	[ 'myapp',			DataStore::NOTE,		xxDELETE,		[ fldGroupName::TAG, fldSummary::TAG ],		],
	6	=> 	[ 'myapp',			DataStore::NOTE,		xxGRPS,			[ ],										],
	7	=> 	[ 'myapp',			DataStore::NOTE,		xxRIDS,			[ ],										],

	101 => 	[ 'roundcube',		DataStore::CONTACT,		xxREAD,			[ fldGroupName::TAG, fldFullName::TAG ],	],
	102 => 	[ 'roundcube',		DataStore::CONTACT,		xxDUPLICATE,	[ fldGroupName::TAG, fldFullName::TAG ],	],
	103 => 	[ 'roundcube',		DataStore::CONTACT,		xxUPDATE,		[ fldGroupName::TAG, fldFullName::TAG ],	],
	104 => 	[ 'roundcube',		DataStore::CONTACT,		xxCOMP,			[ fldGroupName::TAG, fldFullName::TAG ],	],
	105 => 	[ 'roundcube',		DataStore::CONTACT,		xxDELETE,		[ fldGroupName::TAG, fldFullName::TAG ],	],
	106 => 	[ 'roundcube',		DataStore::CONTACT,		xxGRPS,			[ ],										],
	107 => 	[ 'roundcube',		DataStore::CONTACT,		xxRIDS,			[ ],										],

	121 => 	[ 'roundcube',		DataStore::CALENDAR,	xxREAD,			[ fldGroupName::TAG, fldSummary::TAG ],		],
	122 => 	[ 'roundcube',		DataStore::CALENDAR,	xxDUPLICATE,	[ fldGroupName::TAG, fldSummary::TAG ],		],
	123 => 	[ 'roundcube',		DataStore::CALENDAR,	xxUPDATE,		[ fldGroupName::TAG, fldSummary::TAG ],		],
	124 => 	[ 'roundcube',		DataStore::CALENDAR,	xxCOMP,			[ fldGroupName::TAG, fldSummary::TAG ],		],
	125 => 	[ 'roundcube',		DataStore::CALENDAR,	xxDELETE,		[ fldGroupName::TAG, fldSummary::TAG ],		],
	126 => 	[ 'roundcube',		DataStore::CALENDAR,	xxGRPS,			[ ],										],
	127 => 	[ 'roundcube',		DataStore::CALENDAR,	xxRIDS,			[ ],										],

	131 => 	[ 'roundcube',		DataStore::TASK,		xxREAD,			[ fldGroupName::TAG, fldSummary::TAG ],		],
	132 => 	[ 'roundcube',		DataStore::TASK,		xxDUPLICATE,	[ fldGroupName::TAG, fldSummary::TAG ],		],
	133 => 	[ 'roundcube',		DataStore::TASK,		xxUPDATE,		[ fldGroupName::TAG, fldSummary::TAG ],		],
	134 => 	[ 'roundcube',		DataStore::TASK,		xxCOMP,			[ fldGroupName::TAG, fldSummary::TAG ],		],
	135 => 	[ 'roundcube',		DataStore::TASK,		xxDELETE,		[ fldGroupName::TAG, fldSummary::TAG ],		],
	136 => 	[ 'roundcube',		DataStore::TASK,		xxGRPS,			[ ],										],
	137 => 	[ 'roundcube',		DataStore::TASK,		xxRIDS,			[ ],										],

	141 => 	[ 'roundcube',		DataStore::NOTE,		xxREAD,			[ fldGroupName::TAG, fldSummary::TAG ],		],
	142 => 	[ 'roundcube',		DataStore::NOTE,		xxDUPLICATE,	[ fldGroupName::TAG, fldSummary::TAG ],		],
	143 => 	[ 'roundcube',		DataStore::NOTE,		xxUPDATE,		[ fldGroupName::TAG, fldSummary::TAG ],		],
	144 => 	[ 'roundcube',		DataStore::NOTE,		xxCOMP,			[ fldGroupName::TAG, fldSummary::TAG ],		],
	145 => 	[ 'roundcube',		DataStore::NOTE,		xxDELETE,		[ fldGroupName::TAG, fldSummary::TAG ],		],
	146 => 	[ 'roundcube',		DataStore::NOTE,		xxGRPS,			[ ],										],
	147 => 	[ 'roundcube',		DataStore::NOTE,		xxRIDS,			[ ],										],

	151 => 	[ 'mail',			DataStore::MAIL,		xxREAD,			[ fldGroupName::TAG, fldSummary::TAG ],		],
	152 => 	[ 'mail',			DataStore::MAIL,		xxDUPLICATE,	[ fldGroupName::TAG, fldSummary::TAG ],		],
	153 => 	[ 'mail',			DataStore::MAIL,		xxUPDATE,		[ fldGroupName::TAG, fldSummary::TAG ],		],
	154 => 	[ 'mail',			DataStore::MAIL,		xxCOMP,			[ fldGroupName::TAG, fldSummary::TAG ],		],
	155 => 	[ 'mail',			DataStore::MAIL,		xxDELETE,		[ fldGroupName::TAG, fldSummary::TAG ],		],
	156 => 	[ 'mail',			DataStore::MAIL,		xxGRPS,			[ ],										],
	157 => 	[ 'mail',			DataStore::MAIL,		xxRIDS,			[ ],										],
];

if (!strlen($_SERVER['QUERY_STRING'])) {

	msg('+++ Missing parameter', Config::CSS_ERR);
	exit;
}
$t = explode('&', $_SERVER['QUERY_STRING']);

if (!isset($tests[$t[0]])) {

	msg('+++ Test "'.$t[0].'" not found', Config::CSS_ERR);
	exit;
}

$tst  = $tests[$t[0]];
$be   = $tst[0];
$hid  = $tst[1]|DataStore::EXT;
$mod  = $tst[2];
$flds = $tst[3];

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('Testing back end handler "'.$be.'"');
setDB($be, $hid);

$cnf = Config::getInstance();
$cnf->updVar(Config::ENABLED, $hid);
$cnf->updVar(Config::HANDLER, 'MAS');

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('Using "RoundCube" installation data bases');

$dir = $cnf->getVar(Config::RC_DIR);
$config = [];
require($dir.'/config/config.inc.php');

$cnf->updVar(Config::IMAP_HOST, $config['imap_host']);
$cnf->updVar(Config::IMAP_PORT, $config['imap_port']);
$cnf->updVar(Config::IMAP_ENC, null);
$cnf->updVar(Config::IMAP_CERT, 'N');
$cnf->updVar(Config::SMTP_HOST, $config['smtp_host']);
$cnf->updVar(Config::SMTP_PORT, $config['smtp_port']);
$cnf->updVar(Config::SMTP_AUTH, 'N');
$cnf->updVar(Config::SMTP_ENC, null);

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
// check data store handler
if (!Util::HID(Util::HID_CNAME, $hid == (DataStore::CONTACT|DataStore::CALENDAR) ? DataStore::CONTACT : $hid)) {

	msg('+++ Cannot get class for handler "'.sprintf('0x04x', $hid).'" - may data store is not activated', Config::CSS_ERR);
   	exit;
}

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
// perform action

switch ($mod) {

case xxDUPLICATE:
	foreach ($_SESSION['DBExt-RecOld'] as $rid => $typ) {

		msg('Duplicating record "'.$rid.'"');
		if (!$xml = $db->Query($hid, DataStore::RGID, $rid)) {

			msg('+++ Failed to read record "'.$rid.'"', Config::CSS_ERR);
			Msg::InfoMsg($_SESSION['DBExt-RecOld'], 'Existing records');
			Msg::InfoMsg($_SESSION['DBExt-RecNew'], 'New records');
			exit;
		}
		foreach ($flds as $fld) {

			if (!$xml->xpath('//Data/'.$fld))
				continue;

			// skip default groups
			if ($xml->getVar('Type') == DataStore::TYP_GROUP &&
				$xml->getVar('Attributes') & fldAttribute::DEFAULT)
				continue;

			$t = $xml->getItem();
			msg('Change <'.$fld.'>'.$t.'</'.$fld.'>');
			$t = 'DUP-'.$t;
			msg('To     <'.$fld.'>'.$t.'</'.$fld.'>');
		   	$xml->setVal($t);

			if (($id = $db->Query($hid, DataStore::ADD, $xml)) === false)
				msg('+++ Failed to add record - this may be ok', Config::CSS_CODE);
			else {

				msg('New record id is "'.$id.'"');
				$_SESSION['DBExt-RecNew'][$rid] = $id;
			}
		}
	}
	$typ; // disable Eclipse warning
	break;

case xxUPDATE:
	foreach ($_SESSION['DBExt-RecNew'] as $id => $rid) {

		msg('Updating record "'.$rid.'"');
		if (!$xml = $db->Query($hid, DataStore::RGID, $rid)) {

			msg('+++ Failed to read record "'.$rid.'"', Config::CSS_ERR);
			Msg::InfoMsg($_SESSION['DBExt-RecOld'], 'Existing records');
			Msg::InfoMsg($_SESSION['DBExt-RecNew'], 'New records');
			exit;
		}
		foreach ($flds as $fld) {

			if (!$xml->xpath('//Data/'.$fld))
				continue;
			$t = $xml->getItem();
		    msg('Change <'.$fld.'>'.$t.'</'.$fld.'>');
			$t = str_replace('DUP-','UPD-', $t);
			msg('To     <'.$fld.'>'.$t.'</'.$fld.'>');
		   	$xml->setVal($t);

			if ($db->Query($hid, DataStore::UPD, $xml) === false) {

				msg('+++ Failed to update record "'.$rid.'"', Config::CSS_ERR);
				Msg::InfoMsg($_SESSION['DBExt-RecOld'], 'Existing records');
				Msg::InfoMsg($_SESSION['DBExt-RecNew'], 'New records');
				exit;
			}
		}
    	// external record id may have changed!!!
   	 	$_SESSION['DBExt-RecNew'][$id] = $xml->getVar('extID');
	}
    break;

case xxCOMP:
	foreach ($_SESSION['DBExt-RecNew'] as $id => $rid) {

		msg('Compare record "'.$rid.'"');
		if (!($xml = $db->Query($hid, DataStore::RGID, $rid))) {

			msg('+++ Failed to read record "'.$rid.'"', Config::CSS_ERR);
			Msg::InfoMsg($_SESSION['DBExt-RecOld'], 'Existing records');
			Msg::InfoMsg($_SESSION['DBExt-RecNew'], 'New records');
			exit;
		}
		$xml->getVar('Data');
		comp($xml, false, $_SESSION['DBExt-RecComp'][$id]);
	}
	msg('+++ End of script');
	if (isset($_SESSION['DBExt-RecOld']))
		Msg::InfoMsg($_SESSION['DBExt-RecOld'], 'Existing records');
	if (isset($_SESSION['DBExt-RecNew']))
		Msg::InfoMsg($_SESSION['DBExt-RecNew'], 'New records');
	if (isset($_SESSION['DBExt-RecComp']))
		Msg::InfoMsg($_SESSION['DBExt-RecComp'], 'Compare records');
		exit;

case xxDELETE:
	if (isset($_SESSION['DBExt-RecNew'])) {

		foreach ($_SESSION['DBExt-RecNew'] as $id => $rid) {

			msg('Deleting record "'.$rid.'"');
			if (!$db->Query($hid, DataStore::DEL, $rid)) {

				foreach($_SESSION['DBExt-RecNew'] as $k => $v)
					if ($rid == $v) {

						unset($_SESSION['DBExt-RecNew'][$k]);
						break;
					}
				msg('+++ Failed to delete record', Config::CSS_ERR);
				Msg::InfoMsg($_SESSION['DBExt-RecOld'], 'Existing records');
				Msg::InfoMsg($_SESSION['DBExt-RecNew'], 'New records');
				exit;
			}
			unlink($_SESSION['DBExt-RecComp'][$id]);
		}
	}
	unset($_SESSION['DBExt-RecOld']);
	unset($_SESSION['DBExt-RecComp']);
	unset($_SESSION['DBExt-RecNew']);
	break;

case xxGRPS:
	Msg::InfoMsg($db->Query($hid, DataStore::GRPS), 'Group ids in datastore');
	msg('+++ End of script');
	exit;

case xxRIDS:
	Msg::InfoMsg($db->getRIDS($hid), 'All record ids in datastore');
	msg('+++ End of script');
	exit;

default:
	break;
}

msg('Reading all records');
$ids = '|';

$rids = $db->getRIDS($hid);

if ($mod & xxREAD) {

	$_SESSION['DBExt-RecOld'] = $rids;
	unset($_SESSION['DBExt-RecNew']);
	$first = true;
} else
	$first = false;

foreach ($rids as $id => $typ) {

	$xml = $db->Query($hid, DataStore::RGID, $id);
	$ids .= $id.'|';
	if ($first) {

		$rid = $xml->getVar('extID');
		$xml->getVar('Data');
		$_SESSION['DBExt-RecComp'][$id] = Util::Save('DBExtComp'.$rid.'.xml', $xml, false);
	}
	if ($be == 'myapp') {

		$xml->getVar('syncgw');
		Msg::InfoMsg($xml, 'Record');
	}
}
msg('Available records "'.$ids.'"');

if (isset($_SESSION['DBExt-RecOld']))
	Msg::InfoMsg($_SESSION['DBExt-RecOld'], 'Existing records');
if (isset($_SESSION['DBExt-RecNew']))
	Msg::InfoMsg($_SESSION['DBExt-RecNew'], 'New records');

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('+++ End of script');
