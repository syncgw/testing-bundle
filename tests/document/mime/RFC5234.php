<?php

/*
 *  RFC5234 test
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2024 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\document\mime;

use syncgw\lib\Config;
use syncgw\lib\Msg;
use syncgw\document\mime\mimRFC5234;

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
require_once('../../../Functions.php');

Config::getInstance()->updVar(Config::DBG_SCRIPT, 'RFC5234');

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
$data = [

	'A:Simple zeile',
	'A:%d97 %d98 %d99',
	'A:%d97.98.99',
	'A:%x61 %x62 %x63',
	'A:%x61.62.63',
	'A:%b01 %b00',
	'A:%b01.00',
	'A:%x92tab',
    'DDD:This is a short text.%x0d %x0a # comment %x0d %x0a This <Pitty>tag</Pitty>'.
    ' should survive.%x0d %x0a $%äöü)([]=^\'"1!%x0d %x0a &nbsp;&auml;#&tag %x0d'.
    'A:%x0a tab%x092tab --END',
];

class tst extends mimRFC5234 {

    public function decode(string $data): array {

        return parent::decode($data);
    }

	public function encode(array $rec): string {

	    return parent::encode($rec);
	}

};
$obj = new tst();

foreach ($data as $r) {

	echo '<br>';
	msg('Input');
	Msg::InfoMsg($r);

	msg('RFC5234 Decoded');
	Msg::InfoMsg($n = $obj->decode($r));
    #Msg::InfoMsg($n[0]['D'], '# DUMP #', 0);

	msg('RFC5234 Encoded');
	foreach ($n as $x)
	    Msg::InfoMsg($obj->encode($x));
}

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('+++ End of script');
