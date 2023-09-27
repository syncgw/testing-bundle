<?php
declare(strict_types=1);

/*
 *  URLCalAdr field handler
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2023 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\document\field;

use syncgw\lib\XML;

class fldCalAdrURI extends \syncgw\document\field\fldCalAdrURI {

 	/**
     * 	Singleton instance of object
     */
    static private $_obj = null;

	/**
	 *  Get class instance handler
	 *
	 *  @return - Class object
	 */
	public static function getInstance(): fldCalAdrURI {

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

			$ext = [[ 'T' => $xpath, 'P' => [ 'TYPE' => 'bad value' ],
					'D' => 'http://www.example.com/busy/janedoe' ]];
			$cmp1 = '<Data><'.self::TAG.'>http://www.example.com/busy/janedoe</'.self::TAG.'></Data>';
			$cmp2 = $ext;
			unset($cmp2[0]['P']['TYPE']);
			if ($int = $obj->testImport($this,true, $typ, $ver, $xpath, $ext, $cmp1))
				$obj->testExport($this,$typ, $ver, $xpath, $int, $cmp2);

			$ext = [[ 'T' => $xpath, 'P' => [ 'TYPE' => 'work' ],
					'D' => 'http://www.example.com/busy/janedoe' ]];
			$cmp1 = '<Data><'.self::TAG.' TYPE="work">http://www.example.com/busy/janedoe</'.self::TAG.'></Data>';
			$cmp2 = $ext;
		}

		if ($ext && ($int = $obj->testImport($this, true, $typ, $ver, $xpath, $ext, $cmp1)))
			$obj->testExport($this, $typ, $ver, $xpath, $int, $cmp2);
	}

}
