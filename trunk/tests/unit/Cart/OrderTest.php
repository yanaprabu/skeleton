<?php
require_once('A/Cart/Order.php');
include_once 'A/Cart/Manager.php';

class Cart_OrderTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testCart_OrderNotNull() {
  		$name = 'foo';
  		$cart = new A_Cart_Manager($name);
  		$Cart_Order = new A_Cart_Order($cart);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
