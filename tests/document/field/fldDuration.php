<?php
declare(strict_types=1);

/*
 *  Duration field handler
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2025 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\document\field;

use syncgw\lib\XML;

class fldDuration extends \syncgw\document\field\fldDuration {

 	/**
     * 	Singleton instance of object
     */
    static private $_obj = null;

	/**
	 *  Get class instance handler
	 *
	 *  @return - Class object
	 */
	public static function getInstance(): fldDuration {

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

		if ($typ == 'text/calendar' || $typ == 'text/x-vcalendar') {

			$ext = [[ 'T' => $xpath, 'P' => [ 'CALSCALE' => 'gregorian' ], 'D' => 'PT15M' ]];
			$cmp1 = '<Data><'.self::TAG.'>900</'.self::TAG.'></Data>';
			$cmp2 = $ext;
			unset($cmp2[0]['P']['CALSCALE']);
		}

		if ($typ == 'application/activesync.mail+xml') {

			$ext = new XML();
			$ext->loadXML('<syncgw><ApplicationData><'.$xpath.'>10</'.$xpath.'></ApplicationData></syncgw>');
			$cmp1 = '<Data><'.self::TAG.'>10</'.self::TAG.'></Data>';
			$cmp2 = new XML();
			$cmp2->loadXML('<Data><'.$xpath.' xml-ns="activesync:Mail2">10</'.$xpath.'></Data>');
		}

		if ($ext && ($int = $obj->testImport($this, true, $typ, $ver, $xpath, $ext, $cmp1)))
			$obj->testExport($this, $typ, $ver, $xpath, $int, $cmp2);
	}

}
