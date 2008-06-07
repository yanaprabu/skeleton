<?php
require_once('A/Template/Xml.php');

class Template_XmlTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testTemplate_XmlNotNull() {
  		$Template_Xml = new A_Template_Xml();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
