<?php

class Rule_IteratorTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testRule_IteratorNotNull() {
		$dataspace = new A_Collection();

		$field = 'foo';
		$rule = null;
		$errorMsg = 'foo error';
		$Rule_Iterator = new A_Rule_Iterator($rule, $field, $errorMsg);
		
		$result = true;
		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
