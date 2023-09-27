<?php
declare(strict_types=1);

/*
 *  Other address field handler
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2023 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\document\field;

use syncgw\document\field\fldAddresses;
use syncgw\lib\XML;

class fldAddressOther extends \syncgw\document\field\fldAddressOther {

 	/**
     * 	Singleton instance of object
     */
    static private $_obj = null;

	/**
	 *  Get class instance handler
	 *
	 *  @return - Class object
	 */
	public static function getInstance(): fldAddressOther {

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

			$cmp1 = '<Data><'.self::TAG.'><'.fldAddresses::SUB_TAG[2].
				   '>'.self::ASA_TAG[2].'</'.fldAddresses::SUB_TAG[2].'><'.fldAddresses::SUB_TAG[3].'>'.self::ASA_TAG[3].
				   '</'.fldAddresses::SUB_TAG[3].'><'.fldAddresses::SUB_TAG[4].'>'.self::ASA_TAG[4].'</'.fldAddresses::SUB_TAG[4].
				   '><'.fldAddresses::SUB_TAG[5].'>'.self::ASA_TAG[5].'</'.fldAddresses::SUB_TAG[5].'><'.fldAddresses::SUB_TAG[6].
				   '>'.self::ASA_TAG[6].'</'.fldAddresses::SUB_TAG[6].'>'.'</'.self::TAG.'></Data>';
		 	$cmp2 = new XML();
			$cmp2->loadXML('<Data><'.self::ASA_TAG[2].' xml-ns="activesync:Contacts">'.
				   	self::ASA_TAG[2].'</'.self::ASA_TAG[2].'><'.self::ASA_TAG[3].'>'.self::ASA_TAG[3].
				   '</'.self::ASA_TAG[3].'><'.self::ASA_TAG[4].'>'.self::ASA_TAG[4].'</'.self::ASA_TAG[4].
				   '><'.self::ASA_TAG[5].'>'.self::ASA_TAG[5].'</'.self::ASA_TAG[5].'><'.self::ASA_TAG[6].
				   '>'.self::ASA_TAG[6].'</'.self::ASA_TAG[6].'></Data>');
		}

		if ($ext && ($int = $obj->testImport($this, true, $typ, $ver, $xpath, $ext, $cmp1)))
			$obj->testExport($this, $typ, $ver, $xpath, $int, $cmp2);
	}

}
