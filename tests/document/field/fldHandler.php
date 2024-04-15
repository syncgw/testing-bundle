<?php
declare(strict_types=1);

/*
 * 	fld handler class
 *
 *	@package	sync*gw
 *	@copyright	(c) 2008 - 2024 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\document\field;

use syncgw\lib\Msg;
use syncgw\lib\Config;
use syncgw\lib\Util;
use syncgw\lib\XML;

class fldHandler {

	/**
	 *  Test import
	 *
	 *	@patam	- Pointer to field object
	 *  @param  - true=Should work; false=Should fail
	 *  @param  - MIME type
	 *  @param  - MIME version
	 *	@param  - External path
	 *  @param  - External document
	 *  @param  - Internal document as string
	 *  @return - New internal record or false=Skipped
	 *
	 */
	public function testImport($class, bool $mod, string $typ, float $ver, string $xpath, $ext, string $cmp) {

		$int = new XML();
		$int->loadXML('<syncgw><Data/></syncgw>');
		$int->getVar('Data');
		Msg::InfoMsg(''.str_repeat('-', 49).' Import should '.(!$mod ? 'NOT ' : '').'work');
		if (strpos($typ, 'activesync') !== false)
			$ext->getVar('ApplicationData');
		elseif (strpos($typ, 's4j') !== false)
			$ext->getVar('syncgw');
		elseif (strpos($typ, 'omads') !== false)
			$ext->getVar('Folder');
		Msg::InfoMsg($ext, 'Input document');

		if ($class->import($typ, $ver, $xpath, $ext, '', $int)) {

			if (!$mod)
				msg('+++ Import unexpectly succeeded for "'.get_class($class).'"', Config::CSS_ERR);
			$int->getVar('Data');
			Msg::InfoMsg($int, 'Internal document');
			if ($cmp) {

				ob_start();
 				print_r($int->saveXML(false, true));
 				$arr1 = ob_get_contents();
 				ob_end_clean();
		        $xml = new XML();
		        $xml->loadXML($cmp);
				$xml->getVar('Data');
 				ob_start();
				print_r($xml->saveXML(false, true));
   				$arr2 = ob_get_contents();
	   			ob_end_clean();
	   			$rc = Util::diffArray(explode("\n", $arr1), explode("\n", $arr2));
	   			if ($rc[0] > 0) {

	   				msg('+++ Import #1 failed for "'.get_class($class).'"', Config::CSS_ERR);
					echo $rc[1];

					return false;
	   			}
			}
			return $int;

		} elseif ($mod)
			msg('+++ Import #2 failed for "'.get_class($class).'"', Config::CSS_ERR);

		return false;
	}

	/**
	 *  Test export
	 *
	 *	@patam	- Pointer to field object
	 *  @param  - MIME type
	 *  @param  - MIME version
	 *	@param  - External path
	 *  @param  - Internal document
	 *  @param 	- External document
	 *  @return - true=Ok; false=Error
	 *
	 */
	public function testExport($class, string $typ, float $ver, string $xpath, XML &$int, $cmp): bool {

	    $ext= new XML();
	    $ext->loadXML('<syncgw><Data/></syncgw>');
		$ext->getVar('Data');
		Msg::InfoMsg(''.str_repeat('-', 49).' Export should work');

		if (is_array($rc = $class->export($typ, $ver, '', $int, $xpath, $ext))) {

			Msg::InfoMsg($rc, 'Output document');
			if ($cmp) {

				ob_start();
   				print_r($rc);
   				$arr1 = ob_get_contents();
	   			ob_end_clean();
		        if (strstr($cmp[0]['T'], '/')) {

		        	$t = explode('/', $cmp[0]['T']);
		        	$cmp[0]['T'] = array_pop($t);
				}
	   			ob_start();
		        print_r($cmp);
   				$arr2 = ob_get_contents();
	   			ob_end_clean();
				$rc = Util::diffArray(explode("\n", $arr1), explode("\n", $arr2));
				if ($rc[0] > 0) {

					msg('+++ Export #1 failed for "'.get_class($class).'"', Config::CSS_ERR);
					echo $rc[1];

					return false;
				}
			}
			return true;
		} elseif ($rc !== false) {

			$ext->getVar('Data');
			Msg::InfoMsg($ext, 'Output document');
			if ($cmp) {

				ob_start();
  				print_r($ext->saveXML(false, true));
  				$arr1 = ob_get_contents();
	   			ob_end_clean();
		        $cmp->getVar('Data');
   				ob_start();
		        print_r($cmp->saveXML(false, true));
   				$arr2 = ob_get_contents();
	   			ob_end_clean();
				$rc = Util::diffArray(explode("\n", $arr1), explode("\n", $arr2));
				if ($rc[0] > 0) {

					msg('+++ Export #2 failed for "'.get_class($class).'"', Config::CSS_ERR);
					echo $rc[1];

					return false;
				}
			}
			return true;
		} else
			msg('+++ Export #3 failed for "'.get_class($class).'"', Config::CSS_ERR);

		return false;
	}

}
