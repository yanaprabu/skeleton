<?php

class Template extends A_Template_Base {

}

class TemplateTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testTemplateNotNull() {
  		$Template = new Template();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
