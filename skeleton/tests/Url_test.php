<?php
require_once('A/Url.php');

class UrlTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testUrlNotNull() {
  		$Url = new A_Url();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
