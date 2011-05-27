<?php

class Rule_MatchTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testRule_MatchMatches() {
		$dataspace = new A_Collection();

		$rule = new A_Rule_Match('foo', 'bar', 'foo error');
		
		$dataspace->set('foo', 'TEST123');
		$dataspace->set('bar', 'TEST123');		
		$this->assertTrue($rule->isValid($dataspace));
		
		$dataspace->set('foo', 'TEST');
		$dataspace->set('bar', 'TEST123');		
		$this->assertFalse($rule->isValid($dataspace));

		$this->assertEqual($rule->getErrorMsg(), 'foo error');
	}
	
	function testRule_MatchParams() {
		$dataspace = new A_Collection();

		$rule = new A_Rule_Match();
		
		$dataspace->set('foo', 'TEST123');
		$dataspace->set('bar', 'TEST123');		
		$this->assertTrue($rule->isValid($dataspace));
		
		$rule = new A_Rule_Match('foo');
		
		$dataspace->set('foo', 'TEST123');
		$dataspace->set('bar', 'TEST123');		
		$this->assertFalse($rule->isValid($dataspace));
		
	}
	
}
