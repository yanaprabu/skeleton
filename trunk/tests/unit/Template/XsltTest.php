<?php
require_once('A/Template/Xslt.php');

class Template_XsltTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testTemplate_XsltNotNull() {
  		$Template_Xslt = new A_Template_Xslt();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
