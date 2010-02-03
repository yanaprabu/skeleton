<?php

class Filter_ToupperTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testFilter_ToupperNotNull() {
  		$Filter_Toupper = new A_Filter_Toupper();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
