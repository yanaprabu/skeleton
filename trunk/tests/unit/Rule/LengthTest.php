<?php

class Rule_LengthTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testRuleLength() {
		$dataspace = new A_DataContainer();

		$rule = new A_Rule_Length(5, 10, 'test', 'error');
 
		$dataspace->set('test', 'TEST123');
		$this->assertTrue($rule->isValid($dataspace));

		$dataspace->set('test', 'TEST');
		$this->assertFalse($rule->isValid($dataspace));

		$dataspace->set('test', 'TEST1234567890');
		$this->assertFalse($rule->isValid($dataspace));
	}
	
}
