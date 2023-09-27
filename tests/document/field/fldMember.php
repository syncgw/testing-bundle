<?php
declare(strict_types=1);

/*
 *  Member field handler
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2023 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\document\field;

use syncgw\lib\XML;

class fldMember extends \syncgw\document\field\fldMember {

	/**
     * 	Singleton instance of object
     */
    static private $_obj = null;

	/**
	 *  Get class instance handler
	 *
	 *  @return - Class object
	 */
	public static function getInstance(): fldMember {

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

			$ext = [[ 'T' => $xpath, 'P' => [ 'TYPE' => 'bad value' ], 'D' => 's03a0e51f' ]];
			if ($int = $obj->testImport($this, false, $typ, $ver, $xpath, $ext, ''))
				$obj->testExport($this, $typ, $ver, $xpath, $int, $ext);

			$ext = [[ 'T' => $xpath, 'P' => [ 'ALTID' => 'gov' ],
					'D' => 'urn:uuid:03a0e51f-d1aa-4385-8a53-e29025acd8af' ]];
			$cmp1 = '<Data><'.fldGovernmentId::TAG.'>urn:uuid:03a0e51f-d1aa-4385-8a53-e29025acd8af</'.
					fldGovernmentId::TAG.'></Data>';
			$cmp2 = $ext;
		}

		if ($ext && ($int = $obj->testImport($this, true, $typ, $ver, $xpath, $ext, $cmp1)))
			$obj->testExport($this, $typ, $ver, $xpath, $int, $cmp2);
	}

}
