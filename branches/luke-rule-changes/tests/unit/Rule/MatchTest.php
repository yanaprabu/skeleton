<?php
require_once('A/Rule/Match.php');

class Rule_MatchTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testRule_MatchNotNull() {
  		$field = 'foo';
  		$refField = 'bar';
  		$errorMsg = 'foo error';
		$Rule_Match = new A_Rule_Match($field, $refField, $errorMsg);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
