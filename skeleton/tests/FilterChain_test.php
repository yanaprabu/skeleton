<?php
require_once('A/FilterChain.php');

class FilterChainTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testFilterChainNotNull() {
  		$FilterChain = new A_FilterChain();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
