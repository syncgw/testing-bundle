<?php
declare(strict_types=1);

/*
 *  BinLogo field handler
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2023 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\document\field;

use syncgw\lib\XML;

class fldLogo extends \syncgw\document\field\fldLogo {

	/**
     * 	Singleton instance of object
     */
    static private $_obj = null;

	/**
	 *  Get class instance handler
	 *
	 *  @return - Class object
	 */
	public static function getInstance(): fldLogo {

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

			$ext = [[ 'T' => $xpath, 'P' => [ 'DUMMY' => 'error' ], 'D' => 'http://xxx.com/pic.jpg' ]];
			$cmp1 = '<Data><'.self::TAG.'>sgw-5c7b0808</'.self::TAG.'></Data>';
			$cmp2 = $ext;
			unset($cmp2[0]['P']['DUMMY']);
			$cmp2[0]['P']['VALUE'] = $ver == 2.1 ? 'URL' : 'uri';
			if ($int = $obj->testImport($this, true, $typ, $ver, $xpath, $ext, $cmp1))
				$obj->testExport($this, $typ, $ver, $xpath, $int, $cmp2);

			$ext = [[ 'T' => $xpath, 'P' => [ 'TYPE' => 'home' ], 'D' => 'http://xxx.com/pic.jpg' ]];
			$cmp1 = '<Data><'.self::TAG.' TYPE="home">sgw-5c7b0808</'.self::TAG.'></Data>';
			$cmp2 = $ext;
			$cmp2[0]['P']['VALUE'] = $ver == 2.1 ? 'URL' : 'uri';
		}

		if ($ext && ($int = $obj->testImport($this, true, $typ, $ver, $xpath, $ext, $cmp1)))
			$obj->testExport($this, $typ, $ver, $xpath, $int, $cmp2);
	}

}
