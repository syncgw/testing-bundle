<?php

/*
 *  Log and error test
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2023 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\lib;

use syncgw\lib\Msg;
use syncgw\lib\XML;
use syncgw\lib\Config;

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
require_once('../../Functions.php');

$cnf = Config::getInstance();
$cnf->updVar(Config::DBG_SCRIPT, 'Msg');
$cnf->updVar(Config::DBG_EXCL, [

	'syncgw\lib\XML:hasChild',
	'syncgw\lib\XML:addVar',
	'syncgw\lib\Config:getVar',
]);

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('Basic message functions');

Msg::InfoMsg('Hello world string - standard level');
Msg::WarnMsg('Hello world string - warning level');
Msg::ErrMsg('Hello world string - error level');

Msg::InfoMsg('Hello world string - dumped as hex string', 'Hex string dump', 0);

$a = [ 'Key' => 'Value' ];
Msg::InfoMsg($a, 'Array dump');

$x = new XML();
$x->loadXML('<syncgw><Msg>Hello World</Msg></syncgw>');
Msg::InfoMsg($x, 'XML dump');

msg('Debug stack');
$a = new Test();
$a->test1();

$cnf->updVar(Config::DBG_EXCL, array_merge($cnf->getVar(Config::DBG_EXCL), [ 'tests\\lib\\Test:test2' ]));
msg('Excluded test2() (and childs) from debugging');
$a = new Test();
$a->test1();

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('+++ End of script');

class Test {
    public function test1(): void {
	    Msg::InfoMsg('We\'re in function test1()');
        self::test2();
    }

    public function test2(): void {
	    Msg::InfoMsg('We\'re in function test2()');
        self::test3();
    }

    public function test3(): void {
	    Msg::InfoMsg('We\'re in function test3()');
	    self::test4();
    }

    public function test4(): void {
	    Msg::InfoMsg('We\'re in function test4()');
    }
}
