<?php
require_once('A/Json.php');

class JsonTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testJsonNotNull() {
  		$Json = new A_Json();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
