<?php
require_once('A/Http/Request.php');

class Http_RequestTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHttp_RequestNotNull() {
  		$Http_Request = new A_Http_Request();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
