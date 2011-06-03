<?php

class Cart_Payment_AuthorizenetTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testCart_Payment_AuthorizenetNotNull() {
  		$user='';
  		$passwd='';
  		$partner='';
  		$mode=A_CART_PAYMENT_AUTHORIZENET_SERVER_LIVE;
  		$Cart_Payment_Authorizenet = new A_Cart_Payment_Authorizenet($user, $passwd, $partner, $mode);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
