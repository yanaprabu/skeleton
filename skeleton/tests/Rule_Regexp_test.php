<?php
require_once('A/Rule/Regexp.php');

class Rule_RegexpTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testRule_RegexpNotNull() {
  		$field = 'foo';
  		$regexp = '';
  		$errorMsg = 'foo error';
		$Rule_Regexp = new A_Rule_Regexp($field, $regexp, $errorMsg);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
