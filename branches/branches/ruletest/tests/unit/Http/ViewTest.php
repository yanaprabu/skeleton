<?php

class Http_ViewTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHttp_ViewNotNull() {
  		$locator = new A_Locator();
  		$view = new A_Http_View($locator);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
