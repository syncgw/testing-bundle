<?php

/*
 *  XML class test
 *
 *	@package	sync*gw
 *	@subpackage	Testing bundle
 *	@copyright	(c) 2008 - 2024 Florian Daeumling, Germany. All right reserved
 * 	@license 	LGPL-3.0-or-later
 */

namespace tests\lib;

use syncgw\lib\Msg;
use syncgw\lib\Config;
use syncgw\lib\XML;

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
require_once('../../Functions.php');

$cnf = Config::getInstance();
$cnf->updVar(Config::DBG_SCRIPT, 'XML');
$cnf->updVar(Config::DBG_EXCL, $exc = [

	'syncgw\lib\Config',
	'syncgw\lib\XML:hasChild',
	'syncgw\lib\XML:saveXML',
]);

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
$xml = new XML();

$xml->addVar('Record');
Msg::InfoMsg($xml, '<Record> added');

$s = 'Unterä&amp;-Ordner<&\'ö"ß>sync&bull;gw';
$xml->addVar('Data', $s);
Msg::InfoMsg($xml, '<Record> added');
if ($xml->getVar('Data') != $s) {

	msg('+++ Inconsistent data', Config::CSS_ERR);
	exit;
}

$xml->addVar('Empty', '');
$xml->addVar('SubRec', 'This is a short text, with some commas ,,,;;;.
# comment
This <Pitty>tag</Pitty> should survive.
$%äöü)([]=^\'"1!
&nbsp;&auml;#&tag
	tab1	tab2 --END');
$xml->getVar('Record');
Msg::InfoMsg($xml, '<Empty>, <Subrec>Content<Subrec> added');

$xml->addVar('Record1');
$xml->getVar('Record');
Msg::InfoMsg($xml, 'Full record');

$xml->getVar('SubRec');
$xml->addComment('This is a comment - note the ugly position');
$xml->getVar('Record');
Msg::InfoMsg($xml, 'Full record with comment at "SubRec"');

$xml->delVar('SubRec');
$xml->getVar('Record');
Msg::InfoMsg($xml, 'Full record');

$xml->addVar('SubRec', 'Content');
$xml->getVar('Record');
Msg::InfoMsg($xml, 'Full record');

$xml->getVar('SubRec');
$xml->setVal('Replaced content');
$xml->getVar('Record');
Msg::InfoMsg($xml, 'Full record');

$x = $xml->updVar('SubRec', 'NEW Replaced content');
$xml->getVar('Record');
Msg::InfoMsg('Old Value = "'.$x.'"');
Msg::InfoMsg($xml, 'Full record');

$xml->addVar('NewSubRec', 'Should be sub record of "Record"');
$xml->getVar('Record');
Msg::InfoMsg($xml, 'Full record');

$xml->setName('Otto');
$xml->getVar('Record');
Msg::InfoMsg($xml, 'Full record');

msg('hasChild()');
$exc = $cnf->getVar(Config::DBG_EXCL);
unset($exc['syncgw\lib\XML:hasChild']);
$cnf->updVar(Config::DBG_EXCL, $exc);
$xml->hasChild();
$cnf->updVar(Config::DBG_EXCL, array_merge($exc, [ 'syncgw\lib\XML:hasChild' ]));
$xml->getVar('SubRec', true);
$xml->hasChild();

msg('updObj() - object updated '.$xml->updObj(false).' times (should be 10)');

msg('loadFile()');
$xml->loadFile($cnf->getVar(Config::ROOT).'core-bundle/assets/mime_types.xml');
Msg::InfoMsg($xml, 'Loaded record from file');

msg('xpath() - should find: 767');
Msg::InfoMsg('Found value is: '.$xml->xpath('//Name'));

msg('getVar("Ext")');
Msg::InfoMsg('Found value is: '.$xml->getVar('Ext', true));

msg('setAttr("Attribut", "Value")');
$xml->setAttr([ 'Attribut' => 'Value' ]);
msg('getAttr()');
$xml->getAttr();
$xml->getVar('Device');
Msg::InfoMsg($xml, 'Full record');

msg('Number of childrens: of "Application": '.$xml->getChild('Application'));
show($xml);

msg('Getting "video/x-mng" Application');
$xml->xvalue('//Application/Name', 'video/x-mng');
$xml->getItem();
msg('Calling XML2Array()');
Msg::InfoMsg($xml, 'Input document');
$arr = $xml->XML2Array();
Msg::InfoMsg($arr, 'Output');
$x = new XML();
$x->loadXML('<syncgw/>');
$x->getVar('MIME');
$x->Array2XML($arr);
$x->getVar('MIME');
Msg::InfoMsg($x, 'Output');

$dup = new XML();
$dup->loadXML('<syncgw><Tag A="1" B="2" C="3">1</Tag></syncgw>');
Msg::InfoMsg($dup, 'Document to insert');
$xml->loadXML('<syncgw><Dummy/></syncgw>');
Msg::InfoMsg($xml, 'Destination document');
$xml->getVar('Tag');
$xml->getVar('Dummy');
$xml->append($dup, true, true);
$xml->setTop();
Msg::InfoMsg($xml, 'Append whole docuement as first child node');
$dup->getVar('Tag');
$xml->loadXML('<syncgw><Dummy/></syncgw>');
$xml->getVar('Dummy');
$xml->append($dup, false, false);
$xml->setTop();
Msg::InfoMsg($xml, 'Append from current position <Dummy> as child node');

$xml->loadXML('<syncgw><Tag A="1" B="2" C="3">1</Tag></syncgw>');
$xml->getVar('Tag');
$xml->dupVar(3);
$xml->setTop();
Msg::InfoMsg($xml, 'Document with 3 duplcated = 5 <Tag>');

# -------------------------------------------------------------------------------------------------------------------------------------------------------------------
msg('+++ End of script');

function show(XML &$xml): void {

	while ($xml->getItem() !== null) {

		if ($xml->hasChild()) {

			Msg::InfoMsg(''.$xml->getName().'="";');
			$save = $xml->savePos();
			$xml->getChild($xml->getName());
			show($xml);
			$xml->restorePos($save);
		} else {
			Msg::InfoMsg('['.$xml->getName().'] = "'.$xml->getVal().'"');
		}
	}
}
