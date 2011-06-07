<?php

class Rule_NotEmptyTest extends UnitTestCase {
	
	function testRule_NotEmptyValidate() {
		$dataspace = new A_Collection();

		$rule = new A_Rule_NotEmpty('foo', 'foo error');
		
		$this->assertFalse($rule->isValid($dataspace));
		
		$dataspace->set('foo', '');		
		$this->assertFalse($rule->isValid($dataspace));

		$dataspace->set('foo', 0);		
		$this->assertFalse($rule->isValid($dataspace));

		$dataspace->set('foo', false);		
		$this->assertFalse($rule->isValid($dataspace));

		$dataspace->set('foo', '0');		
		$this->assertFalse($rule->isValid($dataspace));

		$dataspace->set('foo', 'foo');		
		$this->assertTrue($rule->isValid($dataspace));
	}
	
}
