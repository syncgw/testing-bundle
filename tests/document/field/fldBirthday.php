<?php
declare(strict_types=1);

/*
 *  Birthday field handler
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2023 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\document\field;

use syncgw\lib\XML;

class fldBirthday extends \syncgw\document\field\fldBirthday {

 	/**
     * 	Singleton instance of object
     */
    static private $_obj = null;

	/**
	 *  Get class instance handler
	 *
	 *  @return - Class object
	 */
	public static function getInstance(): fldBirthday {

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

			$ext = [[ 'T' => $xpath, 'P' => [ 'DUMMY' => 'error' ], 'D' => '20061029T010000Z' ]];
	   		$cmp1 = '<Data><'.self::TAG.'>1162083600</'.self::TAG.'></Data>';
	   		$cmp2 = $ext;
	   		unset($cmp2[0]['P']['DUMMY']);
	   		$cmp2[0]['D'] = substr($cmp2[0]['D'], 0, 8);
	   		if ($int = $obj->testImport($this, true, $typ, $ver, $xpath, $ext, $cmp1))
				$obj->testExport($this, $typ, $ver, $xpath, $int, $cmp2);

			$ext = [[ 'T' => $xpath, 'P' => [ 'CALSCALE' => 'gregorian' ], 'D' => '20061029T010000Z' ]];
	   		$cmp1 = '<Data><'.self::TAG.' CALSCALE="gregorian">1162083600</'.self::TAG.'></Data>';
	   		$cmp2 = $ext;
	   		$cmp2[0]['D'] = substr($cmp2[0]['D'], 0, 8);
		}

		if ($typ == 'application/activesync.contact+xml') {

			$ext = new XML();
	   		$ext->loadXML('<syncgw><ApplicationData><Body><Type>3</Type><EstimatedDataSize>5500</EstimatedDataSize>'.
						  '</Body><'.$xpath.'>2010-08-01T00:00:00Z</'.$xpath.'><WebPage>http://www.contoso.com/</WebPage>'.
						  '<NativeBodyType>3</NativeBodyType></ApplicationData></syncgw>');
	   		$cmp1 = '<Data><'.self::TAG.' VALUE=\'date\'>1280620800</'.self::TAG.'></Data>';
	   		$cmp2 = new XML();
	   		$cmp2->loadXML('<Data><'.$xpath.' xml-ns="activesync:Contacts">2010-08-01T00:00:00.000Z</'.$xpath.'></Data>');
		}

		if ($ext && ($int = $obj->testImport($this, true, $typ, $ver, $xpath, $ext, $cmp1)))
			$obj->testExport($this, $typ, $ver, $xpath, $int, $cmp2);
	}

}
