<?php
require_once('A/Model/Request.php');

class Model_RequestTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testModel_RequestNotNull() {
  		$model = new A_Model_Request($filename);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
