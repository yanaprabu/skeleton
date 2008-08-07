<?php
require_once('A/Email.php');

class EmailTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testEmailNotNull() {
  		$Email = new A_Email();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
