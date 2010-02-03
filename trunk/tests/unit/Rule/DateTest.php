<?php

class Rule_DateTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testRule_DateNotNull() {
  		$field = 'foo';
  		$errorMsg = 'foo error';
		$Rule_Date = new A_Rule_Date($field, $errorMsg);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
