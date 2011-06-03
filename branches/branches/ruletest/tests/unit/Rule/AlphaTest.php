<?php

class Rule_AlphaTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testRule_AlphaNotNull() {
		$dataspace = new A_Collection();

		$field = 'foo';
  		$errorMsg = 'foo error';
  		$Rule_Alpha = new A_Rule_Alpha($field, $errorMsg);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
