<?php
declare(strict_types=1);

/*
 *  Conversation Uid field handler
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2024 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\document\field;

use syncgw\lib\XML;

class fldConversationId extends \syncgw\document\field\fldConversationId {

 	/**
     * 	Singleton instance of object
     */
    static private $_obj = null;

	/**
	 *  Get class instance handler
	 *
	 *  @return - Class object
	 */
	public static function getInstance(): fldConversationId {

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

		$obj = new fldHandler;

		if ($typ == 'application/activesync.mail+xml') {

			$int = new XML();
			$int->loadXML('<Data><'.self::TAG.'>FF68022058BD485996BE15F6F6D99320</'.self::TAG.'></Data>');
			$int->getVar('Data');
			$cmp2 = new XML();
			$cmp2->loadXML('<Data><'.$xpath.' xml-ns="activesync:Mail2">FF68022058BD485996BE15F6F6D99320</'.$xpath.'></Data>');
		}

		$obj->testExport($this, $typ, $ver, $xpath, $int, $cmp2);
	}

}
