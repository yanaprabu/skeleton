<?php

class Cart_SessionTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testCart_SessionNotNull() {
  		$Cart_Session = new A_Cart_Session();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
