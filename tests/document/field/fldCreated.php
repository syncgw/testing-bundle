<?php
declare(strict_types=1);

/*
 *  Date created field handler
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2025 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\document\field;

use syncgw\lib\XML;

class fldCreated extends \syncgw\document\field\fldCreated {

 	/**
     * 	Singleton instance of object
     */
    static private $_obj = null;

	/**
	 *  Get class instance handler
	 *
	 *  @return - Class object
	 */
	public static function getInstance(): fldCreated {

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
		$int = new XML();
		$int->loadXML('<syncgw><Created>1388404736</Created></syncgw>');

		if ($typ == 'text/vcard' || $typ == 'text/x-vcard' || $typ == 'text/x-vnote' ||
			$typ == 'text/calendar' || $typ == 'text/x-vcalendar')
		 	$ext = [[ 'T' => ($typ == 'text/calendar' || $typ == 'text/x-vcalendar') ? 'CREATED' : $xpath, 'P' => [], 'D' => '20131230T115856Z' ]];

	 	if ($ext) {

			$obj = new fldHandler;
			$obj->testExport($this, $typ, $ver, $xpath, $int, $ext);
	 	}
	}

}
