<?php

class Rule_NumericTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testRule_NumericNotNull() {
		$dataspace = new A_DataContainer();

		$rule = new A_Rule_Numeric('foo', 'foo error');
		
		$this->assertFalse($rule->isValid($dataspace));
		
		$dataspace->set('foo', '');		
		$this->assertFalse($rule->isValid($dataspace));
		
		$dataspace->set('foo', 'foo');		
		$this->assertFalse($rule->isValid($dataspace));
		
		$dataspace->set('foo', '47');		
		$this->assertTrue($rule->isValid($dataspace));
		
		$dataspace->set('foo', 47);		
		$this->assertTrue($rule->isValid($dataspace));
		
		$dataspace->set('foo', 0);		
		$this->assertTrue($rule->isValid($dataspace));
	}
	
}
