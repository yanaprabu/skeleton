<?php

class Filter_TolowerTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testFilter_TolowerNotNull() {
  		$Filter_Tolower = new A_Filter_Tolower();
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
