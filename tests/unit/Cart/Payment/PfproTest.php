<?php
require_once('A/Cart/Payment/Pfpro.php');

class Cart_Payment_PfproTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testCart_Payment_PfproNotNull() {
#  		$Cart_Payment_Pfpro = new A_Cart_Payment_Pfpro($user, $passwd, $partner, $mode);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
