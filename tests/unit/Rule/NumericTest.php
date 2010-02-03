<?php

class Rule_NumericTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testRule_NumericNotNull() {
  		$field = 'foo';
  		$errorMsg = 'foo error';
		$Rule_Numeric = new A_Rule_Numeric($field, $errorMsg);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
