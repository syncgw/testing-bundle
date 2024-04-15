<?php
declare(strict_types=1);

/*
 *  Phone field handler
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2024 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\document\field;

use syncgw\lib\XML;

class fldPhones extends \syncgw\document\field\fldPhones {

	/**
     * 	Singleton instance of object
     */
    static private $_obj = null;

	/**
	 *  Get class instance handler
	 *
	 *  @return - Class object
	 */
	public static function getInstance(): fldPhones {

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

			$ext = [[ 'T' => $xpath, 'P' => [ 'TYPE' => 'bad value' ], 'D' => '01928 1111111111' ]];
			if ($int = $obj->testImport($this, false, $typ, $ver, $xpath, $ext, ''))
				$obj->testExport($this, $typ, $ver, $xpath, $int, $ext);

			$ext = [[ 'T' => $xpath, 'P' => [ 'PREF' => '2', 'TYPE' => 'home' ], 'D' => '01928 22222222222' ]];
			$cmp1 = '<Data><'.fldHomePhone2::TAG.'>01928 22222222222</'.fldHomePhone2::TAG.'></Data>';
			$cmp2 = $ext;
			if ($ver != 4.0) {

				$cmp2[0]['P']['TYPE'] = 'home,pref';
				unset($cmp2[0]['P']['PREF']);
			}
			if ($int = $obj->testImport($this, true, $typ, $ver, $xpath, $ext, $cmp1))
				$obj->testExport($this, $typ, $ver, $xpath, $int, $cmp2);

			$ext = [[ 'T' => $xpath, 'P' => [ 'TYPE' => 'work,x-assistant' ], 'D' => '01928 333333333333' ]];
			$cmp1 = '<Data><'.fldAssistantPhone::TAG.'>01928 333333333333</'.fldAssistantPhone::TAG.'></Data>';
			$cmp2 = $ext;
		}

		if ($ext && ($int = $obj->testImport($this, true, $typ, $ver, $xpath, $ext, $cmp1)))
			$obj->testExport($this, $typ, $ver, $xpath, $int, $cmp2);
	}

}
