<?php

/*
 *  fld handler test
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2024 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\document\mime;

use syncgw\lib\Config;
use syncgw\lib\DataStore;
use syncgw\lib\Device;
use syncgw\activesync\masHandler;

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
require_once('../../../Functions.php');

$cnf = Config::getInstance();
$cnf->updVar(Config::DBG_SCRIPT, 'MIME01');
$cnf->updVar(Config::DBG_EXCL, [

	'syncgw\lib\XML:hasChild',
	'syncgw\lib\XML:getVal',
	'syncgw\lib\XML:getItem',
	'syncgw\lib\XML:saveXML',
	'syncgw\lib\Config::getVar',
]);

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
switch (strtoupper($_SERVER['QUERY_STRING'])) {

case 'T=1':
    $mod = DataStore::NOTE;
    $dev = '';
    $tst = [

    	'syncgw\\webdav\\mime\\mimPlain',
    	'syncgw\\webdav\\mime\\mimvNote',
    ];
    break;

case 'T=3':
    $mod = DataStore::NOTE;
    $dev = 'MAS';
    $dev = '';
    $tst = [

    	'syncgw\\activesync\\mime\\mimAsNote',
    ];
    break;

case 'T=10':
    $mod = DataStore::CONTACT;
    $dev = '';
    $tst = [

    	'syncgw\\webdav\\mime\\mimvCard',
    ];
    break;

case 'T=12':
    $mod = DataStore::CONTACT;
    $dev = 'MAS';
    $tst = [

    	'syncgw\\activesync\\mime\\mimAsGAL',
    ];
    break;

case 'T=13':
    $mod = DataStore::CONTACT;
    $dev = 'MAS';
    $tst = [

    	'syncgw\\activesync\\mime\\mimAsContact',
    ];
    break;

case 'T=20':
    $mod = DataStore::CALENDAR;
    $dev = '';
    $tst = [

    	'syncgw\\webdav\\mime\\mimvCal',
    ];
    break;

case 'T=22':
    $mod = DataStore::CALENDAR;
    $dev = 'MAS';
    $tst = [

    	'syncgw\\activesync\\mime\\mimAsCalendar',
    ];
    break;

case 'T=30':
    $mod = DataStore::TASK;
    $dev = '';
    $tst = [

    	'syncgw\\webdav\\mime\\mimvTask',
    ];
    break;

case 'T=32':
    $mod = DataStore::TASK;
    $dev = 'MAS';
    $tst = [

    	'syncgw\\activesync\\mime\\mimAsTask',
    ];
    break;

case 'T=42':
    $mod = DataStore::MAIL;
    $dev = 'MAS';
    $tst = [

    	'syncgw\\activesync\\mime\\mimAsMail',
    ];
	break;

case 'T=43':
    $mod = DataStore::DOCLIB;
    $dev = 'MAS';
    $tst = [

    	'syncgw\\activesync\\mime\\mimAsDocLib',
    ];
	break;

default:
    msg('+++ Unknown parameter "'.$_SERVER['QUERY_STRING'].'"');
    exit;
}

// we need this for attachment handöer
setDB();

if ($dev) {

	$d = Device::getInstance();
	$d->actDev($dev);
}

$cnf = Config::getInstance();
$cnf->updVar(Config::ENABLED, $mod);

// we need to emulate setting of supported Active-Sync version
$mas = masHandler::getInstance();
$mas->setCallParm('BinVer', floatval(MASHandler::MSVER));

foreach ($tst as $mime) {

    $mime = $mime::getInstance();

    foreach ($mime->_mime as $typ) {

	    foreach ($mime->_map as $tag => $class) {

			$c = get_class($class);
	    	$c = explode('\\', $c);
			$c = array_pop($c);
			$n = 'tests\\document\\field\\'.$c;
			$class = $n::getInstance();
	    	msg('Checking field <'.$c.'> for tag <'.$tag.'> MIME <'.$typ[0].' '.$typ[1].'>');
			$class->testClass($typ[0], $typ[1], $tag);
	    }
    }
}

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('+++ End of script');
