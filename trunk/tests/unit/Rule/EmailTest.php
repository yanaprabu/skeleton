<?php

class Rule_EmailTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testRule_EmailNotNull() {
		$dataspace = new A_DataContainer();

		$rule = new A_Rule_Email('foo', 'foo error');
		
		$this->assertFalse($rule->isValid($dataspace));
		
		$dataspace->set('foo', 'test');
		$this->assertFalse($rule->isValid($dataspace));
		
		// should this rule all this kind of valid address by default?
		$dataspace->set('foo', 'test@test');
		$this->assertTrue($rule->isValid($dataspace));
		
		$dataspace->set('foo', 'test@test.com');
		$this->assertTrue($rule->isValid($dataspace));
	}
	
}
