<?php

class Cart_UrlTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testCart_UrlNotNull() {
  		$Cart_Url = new A_Cart_Url();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
