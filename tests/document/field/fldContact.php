<?php
declare(strict_types=1);

/*
 *  Contact name field handler
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2025 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\document\field;

class fldContact extends \syncgw\document\field\fldContact {

 	/**
     * 	Singleton instance of object
     */
    static private $_obj = null;

	/**
	 *  Get class instance handler
	 *
	 *  @return - Class object
	 */
	public static function getInstance(): fldContact {

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

			$ext = [[ 'T' => $xpath, 'P' => [], 'D' => 'Jim Dolittle\, ABC Industries\, +1-919-555-1234' ]];
			$cmp1 = '<Data><'.self::TAG.'>Jim Dolittle, ABC Industries, +1-919-555-1234</'.self::TAG.'></Data>';
			$cmp2 = $ext;
		}

		if ($ext && ($int = $obj->testImport($this, true, $typ, $ver, $xpath, $ext, $cmp1)))
			$obj->testExport($this, $typ, $ver, $xpath, $int, $cmp2);
	}

}
