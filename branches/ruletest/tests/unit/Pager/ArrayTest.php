<?php

class Pager_ArrayTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testPager_ArrayNotNull() {
  		$data = array();
  		$Pager_Array = new A_Pager_Array($data);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
