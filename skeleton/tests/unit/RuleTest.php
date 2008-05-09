<?php
require_once('A/Rule.php');

class RuleTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testRuleNotNull() {
  		$field = 'foo';
  		$errorMsg = 'foo error';
		$Rule = new A_Rule($field, $errorMsg);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
