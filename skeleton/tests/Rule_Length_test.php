<?php
require_once('A/Rule/Length.php');

class Rule_LengthTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testRule_LengthNotNull() {
  		$field = 'foo';
  		$min = 2;
  		$max = 9;
  		$errorMsg = 'foo error';
		$Rule_Length = new A_Rule_Length($field, $min, $max, $errorMsg);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
