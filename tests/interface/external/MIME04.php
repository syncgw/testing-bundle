<?php

/*
 *  MIME creation test
 *
 *	@package	sync*gw
 *	@subpackage	Test scripts
 *	@copyright	(c) 2008 - 2023 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\interface\external;

use syncgw\activesync\MASHandler;
use syncgw\lib\Msg;
use syncgw\lib\Config;
use syncgw\lib\DataStore;
use syncgw\lib\User;
use syncgw\lib\Util;
use syncgw\lib\XML;

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
require_once('../../../Functions.php');

$cnf = Config::getInstance();
$cnf->updVar(Config::DBG_SCRIPT, 'MIME04');
$cnf->updVar(Config::DBG_EXCL, [

	'syncgw\lib\Config',
	'syncgw\lib\XML',
	'syncgw\lib\Device',
]);

Util::CleanDir('comp*.*');

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
$start = 1;
$end   = 0;

foreach (explode('&', $_SERVER['QUERY_STRING']) as $cmd) {

    list($c, $p) = explode('=', $cmd);
    switch (strtoupper($c)) {

    case 'S':
        $start = $p;
        $end   = $p + 1;
        break;

    case 'E':
        $end = $p;
        break;

    case 'T':
        switch ($p) {

        case '01':
            $hid   = DataStore::NOTE;
            $class = 'syncgw\\webdav\\mime\\mimPlain';
            $idir  = 'rc-note';
            $odir  = $idir.'/'.'plain';
            $mtyp  = 'text/plain';
            $mver  = 1.1;
            $opt   = null;
            break;

        case '02':
            $hid   = DataStore::NOTE;
            $class = 'syncgw\\webdav\\mime\\mimvNote';
            $idir  = 'rc-note';
            $odir  = $idir.'/'.'vnote';
            $mtyp  = 'text/x-vnote';
            $mver  = 1.1;
            $opt   = 'Note';
            break;

        case '04':
            $hid   = DataStore::NOTE;
            $class = 'syncgw\\activesync\\mime\\mimAsNote';
            $idir  = 'rc-note';
            $odir  = $idir.'/'.'asnote';
            $mtyp  = 'application/activesync.note+xml';
            $mver  = 1.0;
            $opt   = 'Note';
            break;

        case '10':
            $hid   = DataStore::CONTACT;
            $class = 'syncgw\\webdav\\mime\\mimvCard';
            $idir  = 'rc-contacts';
            $odir  = $idir.'/'.'vcard21';
            $opt   = 'Contact';
            $mtyp  = 'text/x-vcard';
            $mver  = 2.1;
            break;

        case '11':
            $hid   = DataStore::CONTACT;
            $class = 'syncgw\\webdav\\mime\\mimvCard';
            $idir  = 'rc-contacts';
            $odir  = $idir.'/'.'vcard30';
            $opt   = 'Contact';
            $mtyp  = 'text/vcard';
            $mver  = 3.0;
            break;

        case '12':
            $hid   = DataStore::CONTACT;
            $class = 'syncgw\\webdav\\mime\\mimvCard';
            $idir  = 'rc-contacts';
            $odir  = $idir.'/'.'vcard40';
            $opt   = 'Contact';
            $mtyp  = 'text/vcard';
            $mver  = 4.0;
            break;

        case '14':
            $hid   = DataStore::CONTACT;
            $class = 'syncgw\\activesync\\mime\\mimAsContact';
            $idir  = 'rc-contacts';
            $odir  = $idir.'/'.'ascontact';
            $mtyp  = 'application/activesync.contact+xml';
            $mver  = 1.0;
            $opt   = 'Contact';
            break;

        case '15':
            $hid   = DataStore::CONTACT;
            $class = 'syncgw\\activesync\\mime\\mimAsGAL';
            $idir  = 'rc-contacts';
            $odir  = $idir.'/'.'asgal';
            $mtyp  = 'application/activesync.gal+xml';
            $mver  = 1.0;
            $opt   = 'Contact';
            break;

        case '20':
            $hid   = DataStore::CALENDAR;
            $class = 'syncgw\\webdav\\mime\\mimvCal';
            $idir  = 'rc-calendars';
            $odir  = $idir.'/'.'vcal10';
            $mtyp  = 'text/x-vcalendar';
            $mver  = 1.0;
            $opt   = 'Calendar';
            break;

        case '21':
            $hid   = DataStore::CALENDAR;
            $class = 'syncgw\\webdav\\mime\\mimvCal';
            $idir  = 'rc-calendars';
            $odir  = $idir.'/'.'vcal20';
            $mtyp  = 'text/calendar';
            $mver  = 2.0;
            $opt   = 'Calendar';
            break;

        case '23':
            $hid   = DataStore::CALENDAR;
            $class = 'syncgw\\activesync\\mime\\mimAsCalendar';
            $idir  = 'rc-calendars';
            $odir  = $idir.'/'.'ascalendar';
            $mtyp  = 'application/activesync.calendar+xml';
            $mver  = 1.0;
            $opt   = 'Calendar';
            break;

        case '30':
            $hid   = DataStore::TASK;
            $class = 'syncgw\\webdav\\mime\\mimvTask';
            $idir  = 'rc-tasks';
            $odir  = $idir.'/'.'vcal10';
            $mtyp  = 'text/x-vcalendar';
            $mver  = '1.0';
            $opt   = 'Calendar';
            break;

        case '31':
            $hid   = DataStore::TASK;
            $class = 'syncgw\\webdav\\mime\\mimvTask';
            $idir  = 'rc-tasks';
            $odir  = $idir.'/'.'vcal20';
            $mtyp  = 'text/calendar';
            $mver  = 2.0;
            $opt   = 'Calendar';
            break;

        case '33':
            $hid   = DataStore::TASK;
            $class = 'syncgw\\activesync\\mime\\mimAsTask';
            $idir  = 'rc-tasks';
            $odir  = $idir.'/'.'astask';
            $mtyp  = 'application/activesync.task+xml';
            $mver  = 1.0;
            $opt   = 'Calendar';
            break;

 		default:
          	msg('+++ Unknown parameter "'.$c.' = '.$p.'"');
          	exit;
        }
        break;

    default:
        msg('+++ Unknown parameter "'.$c.' = '.$p.'"');
        exit;
    }
}

$idir = $cnf->getVar(Config::ROOT).'testing-bundle/appdata/'.$idir.'/';
$odir = $cnf->getVar(Config::ROOT).'testing-bundle/appdata/'.$odir.'/';

echo 'Call Parameter:<br><br>';
echo 'T=  - MIME typ to test<br>';
echo 'S=  - Start test number (first to show) -> "'.$start.'"<br>';
echo 'E=  - End test number (last to show) -> "'.$end.'"<br>';
echo '<br>';

setDB();

$mas = MASHandler::getInstance();

// load <Options>
if ($opt) {

	msg('Set <Body> options');
	$xml = new XML();
	$xml->loadXML('<Sync><Collection><Options><Class>'.$opt.'</Class>'.
			  '<BodyPreference><Type>1</Type><TruncationSize>51201</TruncationSize></BodyPreference>'.
			  '<BodyPreference><Type>2</Type><TruncationSize>51202</TruncationSize></BodyPreference>'.
			  '<BodyPreference><Type>3</Type><TruncationSize>51203</TruncationSize></BodyPreference>'.
			  '</Options></Collection></Sync>');
	$mas->loadOptions('Sync', $xml);
}

// we need to emulate setting of supported Active-Sync version
$mas->setCallParm('BinVer', floatval(MASHandler::MSVER));

msg('Loading MIME type "'.$class.'"');
// be sure to enable MIME handler
$cnf = Config::getInstance();
$cnf->updVar(Config::ENABLED, $hid);
$mh = $class::getInstance();

msg('Starting server');
setDB('file');

$usr = User::getInstance();
$usr->updVar('LUID', '11', true);

if (!$start)
 	$start = 1;
if (!$end)
 	for ($end=$start; file_exists($idir.sprintf('sgw%02d.xml', $end)); $end++)
  		;

msg(($end-$start).' test files found in "'.$idir.'"');

// load request data
for ($cnt=$start; $cnt < $end; $cnt++) {

	$int = new XML();
 	msg('Loading test file "'.$idir.sprintf('sgw%02d.xml', $cnt).'"');
 	$int->loadFile($idir.sprintf('sgw%02d.xml', $cnt));
	Msg::InfoMsg($int, 'Internal document');

	msg('Creating output document from internal document');
 	$ext = new XML();
 	$ext->setDocType('xml', '', '');

	if ($mh->export($mtyp, $mver, $int, $ext) === false) {

		msg('+++ No matching MIME handler found', Config::CSS_ERR);
		exit;
	}

	comp($ext, true, $odir.sprintf('out%02d.xml', $cnt));
}

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('+++ End of script');
