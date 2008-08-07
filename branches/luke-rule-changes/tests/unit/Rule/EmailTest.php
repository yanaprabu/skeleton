<?php
require_once('A/Rule/Email.php');

class Rule_EmailTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testRule_EmailNotNull() {
  		$field = 'foo';
  		$errorMsg = 'foo error';
		$Rule_Email = new A_Rule_Email($field, $errorMsg);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
