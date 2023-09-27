<?php

/*
 *  Utility class test
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2023 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\lib;

use syncgw\lib\Msg;
use syncgw\lib\Config;
use syncgw\lib\DataStore;
use syncgw\lib\Util;
use syncgw\gui\guiHandler;
use syncgw\gui\guiTrace;

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
require_once('../../Functions.php');

$cnf = Config::getInstance();
$cnf->updVar(Config::DBG_SCRIPT, 'Util');

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------

msg('unfoldStr()');

$s = '1;2;3;4;5;6;';
Msg::InfoMsg($s, 'Original');
Msg::InfoMsg($a = Util::unfoldStr($s, ';'), 'Unfold');

Msg('foldStr()');
Msg::InfoMsg(Util::foldStr($a, ';'), 'Fold');

msg('unfoldStr()');
Msg::InfoMsg($a = Util::unfoldStr($s, ';', 10), 'Unfold');

Msg('foldStr()');
Msg::InfoMsg(Util::foldStr($a, ';'), 'Fold');

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------

msg('HID()');
Msg::InfoMsg(Util::HID(Util::HID_TAB, DataStore::ALL, true));

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------

msg('getTmpFile(): "'.Util::getTmpFile().'"');
$s = '\'k39d44löä1##+.bin';
msg('normFileName(): "'.$s.'": "'.Util::normFileName($s).'"');

msg('getFileExt()');
foreach ([ 'application/gpx+xml', 'application/inkml+xml' ] as $mime)
    msg('File extenson for "'.$mime.'" is "'.Util::getFileExt($mime).'"');

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------

msg('unxTime()');
$s = 'Thu Dec 20 08:15:39 2018 CET';
Msg::InfoMsg($s.' = "'.(Util::unxTime($s)).'"');

msg('cnvDuration()');
Msg::InfoMsg('12938 Sek: "'.Util::cnvDuration(false, 12938).'"');

msg('getTZName()');
Msg::InfoMsg('Default time zone = "'.date_default_timezone_get().'"');
Msg::InfoMsg('UTC time zone offset = "'.($off = date('Z')).'"');
Msg::InfoMsg('Time zone name based on offset= "'.Util::getTZName($off, $off*2).'"');

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------

msg('Sleep()');
Util::Sleep();

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------

msg('Hash()');
$s = 'hello world from Frankfurt';
Msg::InfoMsg('Hash for "'.$s.'" is: '.Util::Hash($s));

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------

msg('diffArray()');
$a = [

    '[Response] => HTTP/1.1 200 OK',
    '[Cache-Control] => private',
    '[Accept-Charset] => UTF-8',
    '[Connection] => Keep-Alive',
    '[Content-Type] => application/vnd.ms-sync.wbxml; charset=UTF-8',
    '[Content-Length] => 449',
    '[MS-Server-ActiveSync] => 7.07.09',
    '[Date] => Sun, 14 Jan 2018 18:28:28 GMT',
];
$b = [

    '[Response] => HTTP/1.1 200 OK',
    '[Cache-Control] => private',
    '[Accept-Charset] => UTF-8',
    '[Connection] => Keep-Alive',
    '[Content-Type] => application/vnd.ms-sync.wbxml; charset=UTF-8',
    '[Content-Length] => 415',
    '[MS-Server-ActiveSync] => 7.07.09',
    '[Date] => Wed, 09 Jan 2019 22:42:53 GMT',
];
Msg::InfoMsg($a, 'array #1');
Msg::InfoMsg($b, 'array #2');
$r = Util::diffArray($a, $b);
Msg::InfoMsg('Number of differences: '.$r[0]);
echo $r[1];

$a = [
'<?xml version="1.0" encoding="UTF-8"?>',
'<Settings xmlns="activesync:Settings">',
  '<Status>1</Status>',
  '<UserInformation>',
    '<Status>1</Status>',
    '<Get>',
      '<Accounts>',
        '<Account>',
          '<AccountId>debug</AccountId>',
          '<AccountName>0100000002000000-debug</AccountName>',
          '<UserDisplayName>debug</UserDisplayName>',
          '<EmailAddresses>',
            '<PrimarySmtpAddress>debug@dev.fd</PrimarySmtpAddress>',
          '</EmailAddresses>',
        '</Account>',
      '</Accounts>',
    '</Get>',
  '</UserInformation>',
'</Settings>',
];

$b = [
'<?xml version="1.0" encoding="UTF-8"?>',
'<Settings xmlns="activesync:Settings">',
  '<Status>1</Status>',
  '<UserInformation>',
    '<Status>1</Status>',
    '<Get>',
      '<Accounts>',
        '<Account>',
          '<AccountId>t1</AccountId>',
          '<AccountName>0100000001000000-t1</AccountName>',
          '<UserDisplayName>t1</UserDisplayName>',
          '<EmailAddresses>',
            '<PrimarySmtpAddress>t1@dev.fd</PrimarySmtpAddress>',
          '</EmailAddresses>',
        '</Account>',
      '</Accounts>',
    '</Get>',
  '</UserInformation>',
'</Settings>',
];
Msg::InfoMsg($a, 'array #1');
Msg::InfoMsg($b, 'array #2');
$r = Util::diffArray($a, $b, guiTrace::EXCLUDE);
Msg::InfoMsg('Number of differences: '.$r[0]);
echo $r[1];

msg('isbinary()');
$s = '93äößd9k2_:;';
Msg::InfoMsg('Checking: "'.$s.'" : '.Util::isBinary($s) ? 'Yes' : 'No');

msg('cnvImg()');
$s = $cnf->getVar(Config::ROOT).'/core-bundle/assets/TooBig.png';
$d = file_get_contents($s);
$d = Util::cnvImg($d, 'jpg');
Msg::InfoMsg($d, 'Converting "'.$s.'" to ".jpg"');

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('+++ End of script');
