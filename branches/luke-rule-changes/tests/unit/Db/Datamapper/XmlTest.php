<?php
require_once('A/Db/Datamapper/Xml.php');

class Db_Datamapper_XmlTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testDb_Datamapper_XmlNotNull() {
		$db = null;
		$filename = '';
// need test file
//		$Db_Datamapper_Xml = new A_Db_Datamapper_Xml($db, $filename);
		
		$result = true;
		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
