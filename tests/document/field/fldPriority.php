<?php
declare(strict_types=1);

/*
 *  Priority field handler
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2025 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\document\field;

use syncgw\lib\XML;

class fldPriority extends \syncgw\document\field\fldPriority {

	/**
     * 	Singleton instance of object
     */
    static private $_obj = null;

	/**
	 *  Get class instance handler
	 *
	 *  @return - Class object
	 */
	public static function getInstance(): fldPriority {

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

			$ext = [[ 'T' => $xpath, 'P' => [ 'dummy' => 'droo' ], 'D' => '991' ]];
			$cmp2 = $ext;
			if ($int = $obj->testImport($this,false, $typ, $ver, $xpath, $ext, ''))
				$obj->testExport($this,$typ, $ver, $xpath, $int, $cmp2);

			$ext = [[ 'T' => $xpath, 'P' => [], 'D' => '2' ]];
			$cmp1 = '<Data><'.self::TAG.'>2</'.self::TAG.'></Data>';
			$cmp2 = $ext;
		}

		if ($typ == 'application/activesync.task+xml') {

			$ext = new XML();
			$ext->loadXML('<syncgw><ApplicationData><Importance>2</Importance></ApplicationData></syncgw>');
			$cmp1 = '<Data><'.self::TAG.'>2</'.self::TAG.'></Data>';
			$cmp2 = new XML();
			$cmp2->loadXML('<Data><'.$xpath.' xml-ns="activesync:Tasks">2</'.$xpath.'></Data>');
		}

		if ($ext && ($int = $obj->testImport($this, true, $typ, $ver, $xpath, $ext, $cmp1)))
			$obj->testExport($this, $typ, $ver, $xpath, $int, $cmp2);
	}

}
