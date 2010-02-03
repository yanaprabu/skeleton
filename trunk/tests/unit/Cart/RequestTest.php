<?php

class Cart_RequestTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testCart_RequestNotNull() {
  		$name = 'foo';
  		$cart = new A_Cart_Manager($name);
   		$Cart_Request = new A_Cart_Request($cart);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
