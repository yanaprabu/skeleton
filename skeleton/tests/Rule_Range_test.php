<?php
require_once('A/Rule/Range.php');

class Rule_RangeTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testRule_RangeNotNull() {
  		$field = 'foo';
  		$min = 2;
  		$max = 9;
  		$errorMsg = 'foo error';
		$Rule_Range = new A_Rule_Range($field, $min, $max, $errorMsg);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
