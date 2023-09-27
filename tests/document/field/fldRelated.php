<?php
declare(strict_types=1);

/*
 *  Related field handler
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2023 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\document\field;

use syncgw\lib\XML;

class fldRelated extends \syncgw\document\field\fldRelated {

	/**
     * 	Singleton instance of object
     */
    static private $_obj = null;

	/**
	 *  Get class instance handler
	 *
	 *  @return - Class object
	 */
	public static function getInstance(): fldRelated {

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
		$obj = new fldHandler;

		if ($typ == 'text/x-vcard' || $typ == 'text/vcard') {

			if ($ver != 4.0)
				return;

			$ext = [[ 'T' => $xpath, 'P' => [ 'DUMMY' => 'error' ], 'D' => 'error' ]];
			if ($int = $obj->testImport($this, false, $typ, $ver, $xpath, $ext, ''))
				$obj->testExport($this, $typ, $ver, $xpath, $int, $ext);

			$ext = [[ 'T' => $xpath, 'P' => [ 'TYPE' => 'x-manager' ],
					'D' => 'Luis Collage' ]];
			$cmp1 = '<Data><'.fldManagerName::TAG.'>Luis Collage</'.fldManagerName::TAG.'></Data>';
			$cmp2 = $ext;
			unset($cmp2[0]['P']['TYPE']);
			$cmp2[0]['P']['TYPE'] = 'x-manager';
		}

		if ($typ == 'text/calendar' || $typ == 'text/x-vcalendar') {

			$ext = [[ 'T' => $xpath, 'P' => [ 'CNN' => 'John Smith' ],
					'D' => '19960401-080045-4000F192713-0052@example.com' ]];
			$cmp1 = '<Data><'.self::TAG.'>19960401-080045-4000F192713-0052@example.com</'.self::TAG.'></Data>';
			$cmp2 = $ext;
			unset($cmp2[0]['P']['CNN']);
		}

		if ($ext && ($int = $obj->testImport($this, true, $typ, $ver, $xpath, $ext, $cmp1)))
			$obj->testExport($this, $typ, $ver, $xpath, $int, $cmp2);
	}

}
