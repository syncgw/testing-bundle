<?php
declare(strict_types=1);

/*
 *  Date executed field handler
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2023 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\document\field;

use syncgw\lib\XML;

class fldLastVerbExecuted extends \syncgw\document\field\fldLastVerbExecuted {

	/**
     * 	Singleton instance of object
     */
    static private $_obj = null;

	/**
	 *  Get class instance handler
	 *
	 *  @return - Class object
	 */
	public static function getInstance(): fldLastVerbExecuted {

		if (!self::$_obj)
            self::$_obj = new self();

		return self::$_obj;
	}

	/**
	 *  Test this class
	 *
	 *	@param  - MIME type
	 *  @param  - MIME version
	 *  $param  - External path
	 */
	public function testClass(string $typ, float $ver, string $xpath): void {

		$ext = null;
		$obj = new fldHandler;

	   	if ($typ == 'application/activesync.mail+xml') {

	   		$ext = new XML();
	   		$ext->loadXML('<syncgw><ApplicationData>'.
	   				'<'.$xpath.'>2019-09-09 10:01:04</'.$xpath.'></ApplicationData></syncgw>');
	   		$cmp1 = '<Data><'.self::TAG.'>1568023264</'.self::TAG.'></Data>';
	   		$cmp2 = new XML();
	   		$cmp2->loadXML('<Data><'.$xpath.' xml-ns="activesync:Mail2">2019-09-09T10:01:04.000Z</'.
	   				$xpath.'></Data>');
	   	}

		if ($ext && ($int = $obj->testImport($this, true, $typ, $ver, $xpath, $ext, $cmp1)))
			$obj->testExport($this, $typ, $ver, $xpath, $int, $cmp2);
	}

}
