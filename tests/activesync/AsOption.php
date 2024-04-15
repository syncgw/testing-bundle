<?php

/*
 *  ActiveSync <Optons> handler test
 *
 *	@package	sync*gw
 *	@subpackage	Test scripts
 *	@copyright	(c) 2008 - 2024 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\activesync;

use syncgw\lib\Config;
use syncgw\lib\Msg;
use syncgw\lib\XML;
use syncgw\activesync\MASHandler;
use syncgw\lib\DataStore;

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
require_once('../../Functions.php');

$cnf = Config::getInstance();
$cnf->updVar(Config::DBG_SCRIPT, 'AsOption');
$cnf->updVar(Config::DBG_EXCL, [

	'syncgw\lib\Log:Caller',
	'syncgw\lib\XML:hasChild',
	'syncgw\lib\XML:getVal',
	'syncgw\lib\XML:addVar',
	'syncgw\lib\XML:getVar',
	'syncgw\lib\XML:getName',
	'syncgw\lib\ML:getItem',
	'syncgw\lib\XML:updVar',
	'syncgw\lib\Config:getVar',
]);

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
$tests		 	  = [
		1 => [
				'Sync',
				'in01.xml',
				'M1',
				#'in07.xml',
				#'C1',
			 ],
		2 => [
				'Find',
				'in02.xml',
				''
			 ],
		3 => [
				'GetItemEstimate',
				'in03.xml',
				DataStore::MAIL,
			 ],
		4 => [
				'ItemOperations',
				'in04.xml',
				DataStore::MAIL,
			 ],
		5 => [
				'ResolveRecipients',
				'in05.xml',
				'',
			 ],
		6 => [
				'Search',
				'in06.xml',
				DataStore::DOCLIB,
		],
];

if (!strlen($_SERVER['QUERY_STRING'])) {

	msg('+++ Missing parameter', Config::CSS_ERR);
	exit;
}

if (!isset($tests[$_SERVER['QUERY_STRING']])) {

	msg('+++ Test "'.$_SERVER['QUERY_STRING'].'" not found', Config::CSS_ERR);
	exit;
}

$tst = $tests[$_SERVER['QUERY_STRING']];

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
$xml = new XML();
$xml->loadXML(file_get_contents($file = $cnf->getVar(Config::ROOT).'testing-bundle/mimedata/asoption/'.$tst[1]));

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('Load options for <'.$tst[0].'> from XML');

$mas = MASHandler::getInstance();

msg('Input file "'.$file.'"');
Msg::InfoMsg($xml);
$mas->loadOptions($tst[0], $xml);

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('Get specific option for "'.$tst[2].'"');
Msg::InfoMsg($mas->getOption($tst[2]), 'Should work');

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('Get specific option for "Error"');
Msg::InfoMsg($mas->getOption('Error'), 'Should return only default values');

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('Restore saved options / Load default options');
$xml->loadXML('<syncgw><'.$tst[0].'/></syncgw>');
$mas->loadOptions($tst[0], $xml);

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('+++ End of script');
