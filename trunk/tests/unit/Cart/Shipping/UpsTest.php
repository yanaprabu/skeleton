<?php
require_once('A/Cart/Shipping/Ups.php');

class Cart_Shipping_UpsTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testCart_Shipping_UpsNotNull() {
		$shipping_type='';
		$postal_from='';
		$postal_to='';
		$country_to='US';
		$weight='';
		$Cart_Shipping_Ups = new A_Cart_Shipping_Ups($shipping_type,  $postal_from, $postal_to,  $country_to,  $weight);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
