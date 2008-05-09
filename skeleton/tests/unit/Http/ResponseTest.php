<?php
require_once('A/Http/Response.php');

class Http_ResponseTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHttp_ResponseNotNull() {
  		$Http_Response = new A_Http_Response();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
