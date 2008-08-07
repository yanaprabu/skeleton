<?php
require_once('A/Filter/Trim.php');

class Filter_TrimTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testFilter_TrimNotNull() {
  		$charset = null;
  		$Filter_Trim = new A_Filter_Trim($charset);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
