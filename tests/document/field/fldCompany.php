<?php
declare(strict_types=1);

/*
 *  Company name field handler
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2023 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\document\field;

use syncgw\lib\XML;

class fldCompany extends \syncgw\document\field\fldCompany {

 	/**
     * 	Singleton instance of object
     */
    static private $_obj = null;

	/**
	 *  Get class instance handler
	 *
	 *  @return - Class object
	 */
	public static function getInstance(): fldCompany {

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

		if ($typ == 'application/activesync.gal+xml') {

			$ext = new XML();
			$ext->loadXML('<syncgw><ApplicationData><Company>Contoso, Ltd.</Company></ApplicationData></syncgw>');
			$cmp1 = '<Data><'.self::TAG.'>Contoso, Ltd.</'.self::TAG.'></Data>';
			$cmp2 = new XML();
			$cmp2->loadXML('<Data><'.$xpath.' xml-ns="'.XML::CP[XML::AS_GAL].'">Contoso, Ltd.</'.$xpath.'></Data>');
		}

		if ($typ == 'application/activesync.contact+xml') {

			$ext = new XML();
			$ext->loadXML('<syncgw><ApplicationData><Body><Type>3</Type><EstimatedDataSize>5500</EstimatedDataSize>'.
						'<Truncated>1</Truncated></Body><WebPage>http://www.contoso.com/</WebPage>'.
						'<BusinessAddressCountry>United States of America</BusinessAddressCountry>'.
						'<Email1Address>"Anat Kerry (anat@contoso.com)"&lt;anat@contoso.com&gt;</Email1Address>'.
						'<BusinessFaxNumber>(206) 555-0100</BusinessFaxNumber><FileAs>Kerry, Anat</FileAs>'.
						'<NameFirst>Anat</NameFirst><PhoneHome>88383777</PhoneHome><PhoneHomeNumber>(206) 555-0101</PhoneHomeNumber>'.
						'<BusinessAddressCity>Redmond</BusinessAddressCity><NameMiddle>M.</NameMiddle>'.
						'<MobilePhoneNumber>(206) 555-0102</MobilePhoneNumber><CompanyName>Contoso, Ltd.</CompanyName>'.
						'<BusinessAddressPostalCode>10021</BusinessAddressPostalCode><NameLast>Kerry</NameLast>'.
						'<BusinessAddressState>WA</BusinessAddressState><BusinessAddressStreet>234 Main St.'.
						'</BusinessAddressStreet><BusinessPhoneNumber>(206) 555-0103</BusinessPhoneNumber>'.
						'<TitleJob>Development Manager</TitleJob><Picture>/9j/4AAQSkZJRgABAQEAYABgAAD/...</Picture>'.
						'<NativeBodyType>3</NativeBodyType></ApplicationData></syncgw>');
			$cmp1 = '<Data><'.self::TAG.'>Contoso, Ltd.</'.self::TAG.'></Data>';
			$cmp2 = new XML();
			$cmp2->loadXML('<Data><'.$xpath.' xml-ns="'.XML::CP[XML::AS_CONTACT].'">Contoso, Ltd.</'.$xpath.'></Data>');
		}

		if ($ext && ($int = $obj->testImport($this, true, $typ, $ver, $xpath, $ext, $cmp1)))
			$obj->testExport($this, $typ, $ver, $xpath, $int, $cmp2);
	}

}
