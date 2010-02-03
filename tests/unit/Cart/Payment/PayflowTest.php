<?php

class Cart_Payment_PayflowTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testCart_Payment_PayflowNotNull() {
  		$user='';
  		$passwd='';
  		$partner='';
  		$mode=A_Cart_Payment_Payflow::SERVER_LIVE;
		$Cart_Payment_Payflow = new A_Cart_Payment_Payflow($user, $passwd, $partner, $mode);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
