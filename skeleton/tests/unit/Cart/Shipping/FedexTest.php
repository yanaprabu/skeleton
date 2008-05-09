<?php
require_once('A/Cart/Shipping/Fedex.php');

class Cart_Shipping_FedexTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testCart_Shipping_FedexNotNull() {
		$shipping_type='';
		$postal_from='';
		$postal_to='';
		$country_to='US';
		$weight='';
  		$Cart_Shipping_Fedex = new A_Cart_Shipping_Fedex($shipping_type,  $postal_from, $postal_to,  $country_to,  $weight);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
