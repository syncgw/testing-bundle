<?php
declare(strict_types=1);

/*
 *  Status field handler
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2024 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\document\field;

use syncgw\lib\XML;

class fldStatus extends \syncgw\document\field\fldStatus {

	/**
     * 	Singleton instance of object
     */
    static private $_obj = null;

	/**
	 *  Get class instance handler
	 *
	 *  @return - Class object
	 */
	public static function getInstance(): fldStatus {

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

			$ext = [[ 'T' => $xpath, 'P' => [ 'dummy' => 'droo' ], 'D' => 'Invalid Status' ]];
			if ($int = $obj->testImport($this, false, $typ, $ver, $xpath, $ext, ''))
				$obj->testExport($this, $typ, $ver, $xpath, $int, '');

			if (strpos($xpath, 'VTODO'))
				$ext = [[ 'T' => $xpath, 'P' => [], 'D' => 'COMPLETED' ]];
			else
				$ext = [[ 'T' => $xpath, 'P' => [], 'D' => 'CONFIRMED' ]];
			if (strpos($xpath, 'VTODO'))
				$cmp1 = '<Data><'.self::TAG.' X-PC="0">COMPLETED</'.self::TAG.'></Data>';
			else
				$cmp1 = '<Data><'.self::TAG.' X-PC="0">CONFIRMED</'.self::TAG.'></Data>';
			$cmp2 = $ext;
			$cmp2[0]['P']['X-PC'] = 0;
			unset($cmp2[0]['P']['dummy']);
		}

		if ($typ == 'application/activesync.task+xml') {

			$ext = new XML();
			$ext->loadXML('<syncgw><ApplicationData><Complete>1</Complete></ApplicationData></syncgw>');
			$cmp1 = '<Data><'.self::TAG.' X-PC="0">COMPLETED</'.self::TAG.'></Data>';
			$cmp2 = new XML();
			$cmp2->loadXML('<Data><'.$xpath.' xml-ns="activesync:Tasks">1</'.$xpath.'></Data>');
		}

		if ($ext && ($int = $obj->testImport($this, true, $typ, $ver, $xpath, $ext, $cmp1)))
			$obj->testExport($this, $typ, $ver, $xpath, $int, $cmp2);
	}

}
