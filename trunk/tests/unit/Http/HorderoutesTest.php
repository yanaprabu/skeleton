<?php

class Http_HorderoutesTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHttp_HorderoutesNotNull() {
#  		$Http_Horderoutes = new A_Http_Horderoutes();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
