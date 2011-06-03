<?php

class Filter_RegexpTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testFilter_RegexpNotNull() {
  		$from = '';
  		$to = '';
  		$Filter_Regexp = new A_Filter_Regexp($from, $to);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
