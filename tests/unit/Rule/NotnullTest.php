<?php

class Rule_NotnullTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testRule_NotnullNotNull() {
		$dataspace = new A_Collection();

		$rule = new A_Rule_Notnull('foo', 'foo error');
		
		$this->assertFalse($rule->isValid($dataspace));
		
		$dataspace->set('foo', '');		
		$this->assertFalse($rule->isValid($dataspace));

		$dataspace->set('foo', 0);		
		$this->assertTrue($rule->isValid($dataspace));

		$dataspace->set('foo', false);		
		$this->assertTrue($rule->isValid($dataspace));

		$dataspace->set('foo', '0');		
		$this->assertTrue($rule->isValid($dataspace));

		$dataspace->set('foo', 'foo');		
		$this->assertTrue($rule->isValid($dataspace));
	}
	
}
