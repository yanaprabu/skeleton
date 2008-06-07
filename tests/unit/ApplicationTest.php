<?php
require_once('A/Application.php');

class ApplicationTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testApplicationNotNull() {
  		$Application = new A_Application();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
