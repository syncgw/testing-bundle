<?php
declare(strict_types=1);

/*
 *  YomiCompany field handler
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2023 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\document\field;

use syncgw\lib\XML;

class fldYomiCompany extends \syncgw\document\field\fldYomiCompany {

	/**
     * 	Singleton instance of object
     */
    static private $_obj = null;

	/**
	 *  Get class instance handler
	 *
	 *  @return - Class object
	 */
	public static function getInstance(): fldYomiCompany {

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

		if ($typ == 'application/activesync.contact+xml') {

			$ext = new XML();
			$ext->loadXML('<syncgw><ApplicationData><Type>3</Type><EstimatedDataSize>5500</EstimatedDataSize>'.
					'<YomiCompanyName>Japanase company name</YomiCompanyName>><NameMiddle>M.</NameMiddle>'.
					'<NativeBodyType>3</NativeBodyType></ApplicationData></syncgw>');
			$cmp1 = '<Data><'.self::TAG.'>Japanase company name</'.self::TAG.'></Data>';
			$cmp2 = new XML();
			$cmp2->loadXML('<Data><'.$xpath.' xml-ns="activesync:Contacts">Japanase company name</'.$xpath.
					'></Data>');
		}

		if ($ext && ($int = $obj->testImport($this, true, $typ, $ver, $xpath, $ext, $cmp1)))
			$obj->testExport($this, $typ, $ver, $xpath, $int, $cmp2);
	}

}
