<?php
declare(strict_types=1);

/*
 *  Begin field handler
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2025 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\document\field;

use syncgw\lib\XML;

class fldBegin extends \syncgw\document\field\fldBegin {

  	/**
     * 	Singleton instance of object
     */
    static private $_obj = null;

	/**
	 *  Get class instance handler
	 *
	 *  @return - Class object
	 */
	public static function getInstance(): fldBegin {

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

		if ($typ == 'text/x-vnote' || $typ == 'text/vcard' || 'text/x-vcard' ||
			$typ == 'text/calendar' || $typ == 'text/x-vcalendar') {

			$int = new XML();
		 	$int->loadXML('<syncgw></syncgw>');
			$obj = new fldHandler;
		 	$obj->testExport($this, $typ, $ver, $xpath, $int, null);
		}
	}

}
