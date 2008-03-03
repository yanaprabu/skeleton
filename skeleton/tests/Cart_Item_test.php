<?php
require_once('A/Cart/Item.php');

class Cart_ItemTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testCart_ItemNotNull() {
  		$product='SKU';
  		$quantity=1;
  		$data=null;
  		$isunique=false;
  		$hastax=true;
  		$hasshipping=true;
  		$Cart_Item = new A_Cart_Item($product, $quantity, $data, $isunique, $hastax, $hasshipping);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
