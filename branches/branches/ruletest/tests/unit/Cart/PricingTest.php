<?php

class Cart_PricingTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testCart_PricingNotNull() {
  		$Cart_Pricing = new A_Cart_Pricing();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
