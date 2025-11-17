<?php
declare(strict_types=1);

/*
 *  IM field handler
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2025 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\document\field;

use syncgw\lib\XML;

class fldIMAddresses extends \syncgw\document\field\fldIMAddresses {

 	/**
     * 	Singleton instance of object
     */
    static private $_obj = null;

	/**
	 *  Get class instance handler
	 *
	 *  @return - Class object
	 */
	public static function getInstance(): fldIMAddresses {

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

			$ext = [[ 'T' => $xpath, 'P' => [ 'BAD' => 'bad value' ], 'D' => 'bad value' ]];
			if ($int = $obj->testImport($this,false, $typ, $ver, $xpath, $ext, ''))
				$obj->testExport($this,$typ, $ver, $xpath, $int, $ext);

			$ext = [[ 'T' => $xpath, 'P' => [], 'D' => 'mamma:to get here' ]];
			if ($int = $obj->testImport($this,false, $typ, $ver, $xpath, $ext, ''))
				$obj->testExport($this,$typ, $ver, $xpath, $int, $ext);

			$ext = [[ 'T' => $xpath, 'P' => [], 'D' => 'icq:A939378727' ]];
			$cmp = '<Data><'.fldIMICQ::TAG.'>icq:A939378727</'.fldIMICQ::TAG.'></Data>';
		}

		if ($ext && ($int = $obj->testImport($this, true, $typ, $ver, $xpath, $ext, $cmp)))
			$obj->testExport($this, $typ, $ver, $xpath, $int, $ext);
	}

}
