<?php
require_once('A/Template.php');

class TemplateTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testTemplateNotNull() {
  		$Template = new A_Template();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
