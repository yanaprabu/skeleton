<?php
require_once('A/Rule/Notnull.php');

class Rule_NotnullTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testRule_NotnullNotNull() {
  		$field = 'foo';
  		$errorMsg = 'foo error';
		$Rule_Notnull = new A_Rule_Notnull($field, $errorMsg);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
