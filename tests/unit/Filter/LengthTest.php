<?php

class Filter_LengthTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testFilter_LengthNotNull() {
  		$length = 9;
  		$Filter_Length = new A_Filter_Length($length);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
