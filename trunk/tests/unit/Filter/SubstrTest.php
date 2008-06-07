<?php
require_once('A/Filter/Substr.php');

class Filter_SubstrTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testFilter_SubstrNotNull() {
  		$start = 2;
  		$length = 4;
  		$Filter_Substr = new A_Filter_Substr($start, $length);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
