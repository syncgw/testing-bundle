<?php
declare(strict_types=1);

/*
 *  Last modified field handler
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2023 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\document\field;

use syncgw\lib\XML;

class fldLastMod extends \syncgw\document\field\fldLastMod {

  	/**
     * 	Singleton instance of object
     */
    static private $_obj = null;

	/**
	 *  Get class instance handler
	 *
	 *  @return - Class object
	 */
	public static function getInstance(): fldLastMod {

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

		$int = new XML();
		$int->loadXML('<syncgw><LastMod>1162083600</LastMod></syncgw>');

		if ($typ == 'application/activesync.note+xml' || $typ == 'application/activesync.doclib+xml' ||
			$typ == 'text/x-vnote' || $typ == 'text/calendar' || $typ == 'text/x-vcalendar') {

			$obj = new fldHandler;
		 	$obj->testExport($this, $typ, $ver, $xpath, $int, null);
		}
	}

}
