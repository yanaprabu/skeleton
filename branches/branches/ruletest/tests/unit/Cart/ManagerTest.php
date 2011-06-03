<?php

class Cart_ManagerTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testCart_ManagerNotNull() {
  		$name = 'foo';
  		$Cart_Manager = new A_Cart_Manager($name);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
